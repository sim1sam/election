<?php

namespace App\Services;

use App\Helpers\NumberConverter;
use App\Models\Voter;
use Illuminate\Support\Facades\DB;

class VoterImportService
{
    protected const CHUNK_SIZE = 500;
    protected const EXPECTED_COLUMNS = [
        'name', 'voter_number', 'father_name', 'mother_name',
        'occupation', 'address', 'voter_serial_number', 'date_of_birth'
    ];

    /**
     * Run full CSV import from file path (full path). Processes in chunks of 500.
     * Only queries DB for current batch's voter numbers (safe with 1.5L+ existing rows).
     */
    public static function runImport(string $filePath, array $commonFields): array
    {
        $results = ['success' => 0, 'errors' => [], 'duplicates' => 0];
        
        // Open file and detect/remove UTF-8 BOM if present
        $handle = fopen($filePath, 'r');
        if ($handle === false) {
            throw new \RuntimeException('Failed to open CSV file.');
        }
        
        // Read first 3 bytes to check for UTF-8 BOM (EF BB BF)
        $bom = fread($handle, 3);
        if ($bom !== chr(0xEF) . chr(0xBB) . chr(0xBF)) {
            // No BOM, rewind to start
            rewind($handle);
        }
        // If BOM was found, file pointer is already after BOM, so continue reading

        $header = fgetcsv($handle);
        // Remove BOM from first header cell if still present (extra safety)
        if (!empty($header[0])) {
            $header[0] = preg_replace('/^\xEF\xBB\xBF/', '', $header[0]);
        }
        
        $columnIndices = self::mapCsvHeaderToColumns($header ?? [], self::EXPECTED_COLUMNS);
        if ($columnIndices === null) {
            fclose($handle);
            throw new \RuntimeException('CSV header could not be mapped. Please use the template.');
        }

        $batch = [];
        $rowNumber = 1;

        try {
            while (($row = fgetcsv($handle)) !== false) {
                $rowNumber++;
                if (empty(array_filter($row))) {
                    continue;
                }

                $voterData = [];
                foreach (self::EXPECTED_COLUMNS as $column) {
                    $index = $columnIndices[$column] ?? null;
                    $value = ($index !== null && isset($row[$index])) ? trim($row[$index]) : null;
                    if ($value !== null) {
                        $value = ltrim($value, "'\t");
                        if (in_array($column, ['voter_number', 'voter_serial_number'])) {
                            $value = NumberConverter::banglaToEnglish($value);
                        } elseif ($column === 'date_of_birth') {
                            $value = NumberConverter::convertDateToEnglish($value);
                        }
                    }
                    $voterData[$column] = $value;
                }

                if (empty($voterData['name']) || empty($voterData['voter_number'])) {
                    $results['errors'][] = "Row {$rowNumber}: Name and Voter Number are required.";
                    continue;
                }
                if (preg_match('/^[\d.]+[eE][\+\-]?\d+$/', $voterData['voter_number'])) {
                    $results['errors'][] = "Row {$rowNumber}: Voter number in scientific notation. Format as TEXT in Excel.";
                    continue;
                }

                if (!empty($voterData['date_of_birth'])) {
                    try {
                        $voterData['date_of_birth'] = self::parseDate($voterData['date_of_birth'], $rowNumber);
                    } catch (\Exception $e) {
                        $results['errors'][] = "Row {$rowNumber}: " . $e->getMessage();
                        continue;
                    }
                } else {
                    $voterData['date_of_birth'] = null;
                }

                $voterData = array_merge($voterData, $commonFields);
                $batch[] = ['data' => $voterData, 'row' => $rowNumber];

                if (count($batch) >= self::CHUNK_SIZE) {
                    self::processBatch($batch, $results);
                    $batch = [];
                }
            }

            if (!empty($batch)) {
                self::processBatch($batch, $results);
            }
        } finally {
            fclose($handle);
        }

        return $results;
    }

    protected static function parseDate(string $dateValue, int $rowNumber): string
    {
        $dateValue = trim($dateValue);
        $date = null;
        if (is_numeric($dateValue) && (float)$dateValue > 1 && (float)$dateValue < 1000000 && !preg_match('/[\/\-]/', $dateValue)) {
            $excelEpoch = \Carbon\Carbon::create(1899, 12, 30, 0, 0, 0);
            $date = $excelEpoch->copy()->addDays((int)$dateValue - 1);
        } elseif (preg_match('/^(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{4})$/', $dateValue, $matches)) {
            $first = (int)$matches[1];
            $second = (int)$matches[2];
            $year = (int)$matches[3];
            if ($first > 12) {
                $date = \Carbon\Carbon::create($year, $second, $first);
            } elseif ($second > 12) {
                $date = \Carbon\Carbon::create($year, $first, $second);
            } else {
                try {
                    $date = \Carbon\Carbon::create($year, $second, $first);
                } catch (\Exception $e) {
                    $date = \Carbon\Carbon::create($year, $first, $second);
                }
            }
        } else {
            $formats = ['d/m/Y', 'd-m-Y', 'Y-m-d', 'Y/m/d', 'm/d/Y', 'n/j/Y'];
            foreach ($formats as $format) {
                try {
                    $parsed = \Carbon\Carbon::createFromFormat($format, $dateValue);
                    if ($parsed !== false) {
                        $date = $parsed;
                        break;
                    }
                } catch (\Exception $e) {
                    continue;
                }
            }
            if (!$date) {
                $date = \Carbon\Carbon::parse($dateValue);
            }
        }
        if (!$date || $date->isFuture() || $date->year < 1900 || $date->year > 2100) {
            throw new \Exception("Invalid date: {$dateValue}");
        }
        return $date->format('Y-m-d');
    }

    /** Only queries DB for voter numbers in this batch (avoids loading 1.5L+ rows). */
    public static function processBatch(array $batch, array &$results): void
    {
        if (empty($batch)) {
            return;
        }
        // Duplicate only when BOTH name AND voter_number are same (not voter_number alone)
        $voterNumbers = array_column(array_column($batch, 'data'), 'voter_number');
        $existingInDb = Voter::whereIn('voter_number', $voterNumbers)->get(['voter_number', 'name']);
        $pairKey = fn ($name, $voterNo) => trim((string)$name) . "\x00" . trim((string)$voterNo);
        $existingPairs = $existingInDb->map(fn ($v) => $pairKey($v->name, $v->voter_number))->flip()->toArray();

        $insertData = [];
        $rowMapping = [];
        $nextId = Voter::getNextSequentialId();
        $batchSeenPairs = [];

        foreach ($batch as $item) {
            $voterData = $item['data'];
            $rowNumber = $item['row'];
            $key = $pairKey($voterData['name'], $voterData['voter_number']);
            if (isset($existingPairs[$key]) || isset($batchSeenPairs[$key])) {
                $results['duplicates']++;
                $results['errors'][] = "Row {$rowNumber}: Name + voter number already exists (duplicate).";
                continue;
            }
            $batchSeenPairs[$key] = true;
            $insertData[] = array_merge($voterData, [
                'id' => $nextId++,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $rowMapping[$voterData['voter_number']] = $rowNumber;
        }

        if (empty($insertData)) {
            return;
        }
        $insertChunks = array_chunk($insertData, 100);
        DB::beginTransaction();
        try {
            foreach ($insertChunks as $chunk) {
                // Use DB::table()->insert() instead of Model::insert() to allow 'id' field
                DB::table('voters')->insert($chunk);
            }
            DB::commit();
            $results['success'] += count($insertData);
        } catch (\Exception $e) {
            DB::rollBack();
            // Fallback: try inserting one by one with explicit ID
            foreach ($insertData as $data) {
                $rowNumber = $rowMapping[$data['voter_number']] ?? 'unknown';
                try {
                    // Ensure ID is set (it should already be in $data)
                    if (!isset($data['id'])) {
                        $data['id'] = Voter::getNextSequentialId();
                    }
                    // Use DB::table()->insert() to bypass fillable and include 'id'
                    DB::table('voters')->insert($data);
                    $results['success']++;
                } catch (\Exception $e2) {
                    $results['errors'][] = "Row {$rowNumber}: " . $e2->getMessage();
                }
            }
        }
    }

    public static function mapCsvHeaderToColumns(array $header, array $expectedColumns): ?array
    {
        $normalized = array_map(function ($h) {
            $h = trim((string)$h);
            return ['raw' => $h, 'lower' => mb_strtolower($h)];
        }, $header);
        $columnToPhrases = [
            'name' => ['name', 'নাম'],
            'voter_number' => ['voter number', 'ভোটার নম্বর', 'voter_number'],
            'father_name' => ['father', 'পিতা', 'father name'],
            'mother_name' => ['mother', 'মাতা', 'mother name'],
            'occupation' => ['occupation', 'পেশা'],
            'address' => ['address', 'ঠিকানা'],
            'voter_serial_number' => ['voter serial', 'serial number', 'সিরিয়াল', 'voter_serial_number'],
            'date_of_birth' => ['date of birth', 'birth', 'জন্ম', 'date_of_birth'],
        ];
        $indices = [];
        foreach ($expectedColumns as $column) {
            $phrases = $columnToPhrases[$column] ?? [str_replace('_', ' ', $column)];
            $found = false;
            foreach ($normalized as $idx => $h) {
                foreach ($phrases as $phrase) {
                    if (mb_strpos($h['lower'], $phrase) !== false) {
                        if ($column === 'name' && (mb_strpos($h['lower'], 'father') !== false || mb_strpos($h['lower'], 'mother') !== false)) {
                            continue;
                        }
                        if ($column === 'voter_number' && mb_strpos($h['lower'], 'serial') !== false) {
                            continue;
                        }
                        $indices[$column] = $idx;
                        $found = true;
                        break 2;
                    }
                }
            }
            if (!$found) {
                return null;
            }
        }
        return $indices;
    }
}
