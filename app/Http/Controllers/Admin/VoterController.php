<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Voter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Helpers\NumberConverter;

class VoterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Voter::query();

        // Search by voter number (convert Bangla to English)
        if ($request->filled('voter_number')) {
            $voterNumber = NumberConverter::banglaToEnglish($request->voter_number);
            $query->where('voter_number', 'like', '%' . $voterNumber . '%');
        }

        // Search by ward number (convert Bangla to English)
        if ($request->filled('ward_number')) {
            $wardNumber = NumberConverter::banglaToEnglish($request->ward_number);
            $query->where('ward_number', 'like', '%' . $wardNumber . '%');
        }

        // Search by voter serial number (convert Bangla to English)
        if ($request->filled('voter_serial_number')) {
            $voterSerialNumber = NumberConverter::banglaToEnglish($request->voter_serial_number);
            $query->where('voter_serial_number', 'like', '%' . $voterSerialNumber . '%');
        }

        $voters = $query->orderBy('id', 'asc')->paginate(20)->withQueryString();
        
        return view('admin.voters.index', compact('voters'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.voters.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'voter_number' => 'required|string|max:255|unique:voters,voter_number',
            'father_name' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'occupation' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'polling_center_name' => 'nullable|string|max:255',
            'ward_number' => 'nullable|string|max:255',
            'voter_area_number' => 'nullable|string|max:255',
            'voter_serial_number' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date',
        ]);

        // Convert Bangla digits to English before storing
        if (isset($validated['voter_number'])) {
            $validated['voter_number'] = NumberConverter::banglaToEnglish($validated['voter_number']);
        }
        if (isset($validated['voter_serial_number'])) {
            $validated['voter_serial_number'] = NumberConverter::banglaToEnglish($validated['voter_serial_number']);
        }
        if (isset($validated['ward_number'])) {
            $validated['ward_number'] = NumberConverter::banglaToEnglish($validated['ward_number']);
        }
        if (isset($validated['voter_area_number'])) {
            $validated['voter_area_number'] = NumberConverter::banglaToEnglish($validated['voter_area_number']);
        }
        if (isset($validated['date_of_birth'])) {
            $validated['date_of_birth'] = NumberConverter::convertDateToEnglish($validated['date_of_birth']);
        }

        Voter::create($validated);

        return redirect()->route('admin.voters.index')
            ->with('success', 'Voter created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Voter $voter)
    {
        return view('admin.voters.show', compact('voter'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Voter $voter)
    {
        return view('admin.voters.edit', compact('voter'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Voter $voter)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'voter_number' => 'required|string|max:255|unique:voters,voter_number,' . $voter->id,
            'father_name' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'occupation' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'polling_center_name' => 'nullable|string|max:255',
            'ward_number' => 'nullable|string|max:255',
            'voter_area_number' => 'nullable|string|max:255',
            'voter_serial_number' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date',
        ]);

        // Convert Bangla digits to English before storing
        if (isset($validated['voter_number'])) {
            $validated['voter_number'] = NumberConverter::banglaToEnglish($validated['voter_number']);
        }
        if (isset($validated['voter_serial_number'])) {
            $validated['voter_serial_number'] = NumberConverter::banglaToEnglish($validated['voter_serial_number']);
        }
        if (isset($validated['ward_number'])) {
            $validated['ward_number'] = NumberConverter::banglaToEnglish($validated['ward_number']);
        }
        if (isset($validated['voter_area_number'])) {
            $validated['voter_area_number'] = NumberConverter::banglaToEnglish($validated['voter_area_number']);
        }
        if (isset($validated['date_of_birth'])) {
            $validated['date_of_birth'] = NumberConverter::convertDateToEnglish($validated['date_of_birth']);
        }

        $voter->update($validated);

        return redirect()->route('admin.voters.index')
            ->with('success', 'Voter updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Voter $voter)
    {
        $voter->delete();

        return redirect()->route('admin.voters.index')
            ->with('success', 'Voter deleted successfully.');
    }

    /**
     * Show CSV upload form
     */
    public function showUploadForm()
    {
        return view('admin.voters.upload');
    }

    /**
     * Handle CSV file upload and import
     * Optimized for large files (5k+ rows) using chunked processing
     */
    public function importCsv(Request $request)
    {
        // Increase memory limit and execution time for large uploads
        ini_set('memory_limit', '512M');
        set_time_limit(600); // 10 minutes
        
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:51200', // 50MB max (increased from 10MB)
            'polling_center_name' => 'required|string|max:255',
            'ward_number' => 'required|string|max:255',
            'voter_area_number' => 'required|string|max:255',
        ]);

        // Get the 3 common fields that will apply to all voters
        // Convert Bangla digits to English
        $commonFields = [
            'polling_center_name' => $request->input('polling_center_name'),
            'ward_number' => NumberConverter::banglaToEnglish($request->input('ward_number')),
            'voter_area_number' => NumberConverter::banglaToEnglish($request->input('voter_area_number')),
        ];

        $file = $request->file('csv_file');
        $path = $file->getRealPath();
        
        // Expected columns (in order)
        $expectedColumns = [
            'name', 'voter_number', 'father_name', 'mother_name', 
            'occupation', 'address', 'voter_serial_number', 'date_of_birth'
        ];
        
        $results = [
            'success' => 0,
            'errors' => [],
            'duplicates' => 0,
        ];
        
        // Process in chunks to avoid memory issues
        $chunkSize = 500; // Process 500 rows at a time
        $batch = [];
        $rowNumber = 1; // Track row number (1 = header)
        $processedRows = 0;
        
        // Open file for streaming (memory efficient)
        $handle = fopen($path, 'r');
        if ($handle === false) {
            return redirect()->route('admin.voters.index')
                ->with('error', 'Failed to open CSV file.');
        }
        
        // Skip header row
        $header = fgetcsv($handle);
        $rowNumber++;
        
        try {
            while (($row = fgetcsv($handle)) !== false) {
                $rowNumber++;
                
                // Skip empty rows
                if (empty(array_filter($row))) {
                    continue;
                }
                
                // Map row data to columns
                $voterData = [];
                foreach ($expectedColumns as $index => $column) {
                    $value = isset($row[$index]) ? trim($row[$index]) : null;
                    
                    // Remove single quote or tab prefix (used to force Excel to treat as text)
                    if ($value !== null) {
                        $value = ltrim($value, "'\t");
                        
                        // Convert Bangla digits to English for numeric fields
                        if (in_array($column, ['voter_number', 'voter_serial_number'])) {
                            $value = NumberConverter::banglaToEnglish($value);
                        } elseif ($column === 'date_of_birth') {
                            $value = NumberConverter::convertDateToEnglish($value);
                        }
                    }
                    
                    $voterData[$column] = $value;
                }
                
                // Validate required fields
                if (empty($voterData['name']) || empty($voterData['voter_number'])) {
                    $results['errors'][] = "Row {$rowNumber}: Name and Voter Number are required.";
                    continue;
                }
                
                // Check if voter number was converted to scientific notation by Excel
                if (preg_match('/^[\d.]+[eE][\+\-]?\d+$/', $voterData['voter_number'])) {
                    $results['errors'][] = "Row {$rowNumber}: Voter number appears to be in scientific notation (e.g., 2.61E+11). Please format the voter number column as TEXT in Excel and prefix with a single quote (') to preserve leading zeros.";
                    continue;
                }
                
                // Parse date if provided
                if (!empty($voterData['date_of_birth'])) {
                    try {
                        $dateValue = trim($voterData['date_of_birth']);
                        $date = null;
                        
                        // Check if it's an Excel serial date
                        if (is_numeric($dateValue) && (float)$dateValue > 1 && (float)$dateValue < 1000000 && !preg_match('/[\/\-]/', $dateValue)) {
                            $excelEpoch = \Carbon\Carbon::create(1899, 12, 30, 0, 0, 0);
                            $days = (int)$dateValue;
                            $date = $excelEpoch->copy()->addDays($days - 1);
                        } else {
                            // Handle date strings - prioritize Bangladesh format (dd/mm/yyyy)
                            if (preg_match('/^(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{4})$/', $dateValue, $matches)) {
                                $first = (int)$matches[1];
                                $second = (int)$matches[2];
                                $year = (int)$matches[3];
                                
                                if ($first > 12) {
                                    $day = $first;
                                    $month = $second;
                                    $date = \Carbon\Carbon::create($year, $month, $day);
                                } elseif ($second > 12) {
                                    $month = $first;
                                    $day = $second;
                                    $date = \Carbon\Carbon::create($year, $month, $day);
                                } else {
                                    try {
                                        $day = $first;
                                        $month = $second;
                                        $date = \Carbon\Carbon::create($year, $month, $day);
                                    } catch (\Exception $e) {
                                        $month = $first;
                                        $day = $second;
                                        $date = \Carbon\Carbon::create($year, $month, $day);
                                    }
                                }
                            } else {
                                $formats = [
                                    'd/m/Y', 'd-m-Y', 'Y-m-d', 'Y/m/d', 'm/d/Y', 'n/j/Y',
                                ];
                                
                                foreach ($formats as $format) {
                                    try {
                                        $parsedDate = \Carbon\Carbon::createFromFormat($format, $dateValue);
                                        if ($parsedDate !== false && $parsedDate->format($format) === $dateValue) {
                                            $date = $parsedDate;
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
                        }
                        
                        // Validate date
                        if (!$date || $date->isFuture() || $date->year < 1900 || $date->year > 2100) {
                            throw new \Exception("Invalid date: {$dateValue}");
                        }
                        
                        $voterData['date_of_birth'] = $date->format('Y-m-d');
                    } catch (\Exception $e) {
                        $results['errors'][] = "Row {$rowNumber}: Invalid date format for date_of_birth '{$voterData['date_of_birth']}'. Error: " . $e->getMessage() . ". Expected format: DD/MM/YYYY (e.g., 01/03/1992 for 1st March 1992).";
                        continue;
                    }
                } else {
                    $voterData['date_of_birth'] = null;
                }
                
                // Apply common fields
                $voterData = array_merge($voterData, $commonFields);
                
                // Add to batch
                $batch[] = [
                    'data' => $voterData,
                    'row' => $rowNumber
                ];
                
                // Process batch when it reaches chunk size
                if (count($batch) >= $chunkSize) {
                    $this->processBatch($batch, $results);
                    $batch = [];
                    $processedRows += $chunkSize;
                    
                    // Log progress for large files
                    if ($processedRows % 5000 == 0) {
                        Log::info("CSV Import Progress: {$processedRows} rows processed");
                    }
                }
            }
            
            // Process remaining batch
            if (!empty($batch)) {
                $this->processBatch($batch, $results);
            }
            
            fclose($handle);
            
            $message = "Import completed! Successfully imported {$results['success']} voters.";
            if ($results['duplicates'] > 0) {
                $message .= " {$results['duplicates']} duplicate(s) skipped.";
            }
            if (count($results['errors']) > 0) {
                $message .= " " . count($results['errors']) . " error(s) occurred.";
            }
            
            return redirect()->route('admin.voters.index')
                ->with('success', $message)
                ->with('import_errors', array_slice($results['errors'], 0, 100)); // Limit errors shown to first 100
                
        } catch (\Exception $e) {
            fclose($handle);
            return redirect()->route('admin.voters.index')
                ->with('error', 'Import failed: ' . $e->getMessage());
        }
    }
    
    /**
     * Process a batch of voters efficiently using batch insert
     */
    private function processBatch(array $batch, array &$results)
    {
        if (empty($batch)) {
            return;
        }
        
        // Get existing voter numbers to check for duplicates in one query
        $voterNumbers = array_column(array_column($batch, 'data'), 'voter_number');
        $existingVoters = Voter::whereIn('voter_number', $voterNumbers)
            ->pluck('voter_number')
            ->toArray();
        
        // Prepare data for batch insert
        $insertData = [];
        $rowMapping = []; // Map voter_number to row number for error reporting
        $nextId = Voter::getNextSequentialId();
        
        foreach ($batch as $item) {
            $voterData = $item['data'];
            $rowNumber = $item['row'];
            
            // Check for duplicate
            if (in_array($voterData['voter_number'], $existingVoters)) {
                $results['duplicates']++;
                $results['errors'][] = "Row {$rowNumber}: Voter number '{$voterData['voter_number']}' already exists.";
                continue;
            }
            
            // Add to insert batch
            $insertData[] = array_merge($voterData, [
                'id' => $nextId++,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            // Track row number for error reporting
            $rowMapping[$voterData['voter_number']] = $rowNumber;
            
            // Track existing voters to avoid duplicates within same batch
            $existingVoters[] = $voterData['voter_number'];
        }
        
        if (empty($insertData)) {
            return;
        }
        
        // Batch insert in chunks of 100 to avoid query size limits
        $insertChunks = array_chunk($insertData, 100);
        
        DB::beginTransaction();
        try {
            foreach ($insertChunks as $chunk) {
                Voter::insert($chunk);
            }
            DB::commit();
            $results['success'] += count($insertData);
        } catch (\Exception $e) {
            DB::rollBack();
            // Fallback to individual inserts if batch fails
            foreach ($insertData as $data) {
                $voterNumber = $data['voter_number'];
                $rowNumber = $rowMapping[$voterNumber] ?? 'unknown';
                try {
                    // Remove id, created_at, updated_at for create() method
                    $createData = $data;
                    unset($createData['id'], $createData['created_at'], $createData['updated_at']);
                    Voter::create($createData);
                    $results['success']++;
                } catch (\Exception $e2) {
                    $results['errors'][] = "Row {$rowNumber}: " . $e2->getMessage();
                }
            }
        }
    }

    /**
     * Download CSV template
     */
    public function downloadTemplate()
    {
        $filename = 'voters_import_template.csv';
        
        return response()->streamDownload(function() {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8 (helps with Excel)
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Header row (removed polling_center_name, ward_number, voter_area_number - these are entered in the form)
            fputcsv($file, [
                'Name (নাম)',
                'Voter Number (ভোটার নম্বর)',
                'Father Name (পিতা)',
                'Mother Name (মাতা)',
                'Occupation (পেশা)',
                'Address (ঠিকানা)',
                'Voter Serial Number (ভোটার সিরিয়াল নম্বর)',
                'Date of Birth (জন্ম তারিখ)'
            ]);
            
            // Sample row - prefix voter number with single quote to force text format
            // Single quote prefix forces Excel to treat as text (won't show in cell)
            // Note: ভোট কেন্দ্র, ওয়ার্ড নম্বর, and ভোটার এলাকার নম্বর are entered in the upload form, not in CSV
            fputcsv($file, [
                'আহমেদ হাসান',
                "'V-001234",  // Single quote prefix forces Excel to treat as text
                'মোহাম্মদ আলী',
                'ফাতেমা খাতুন',
                'ব্যবসায়ী',
                'ঢাকা, বাংলাদেশ',
                "'১২৩৪",  // Single quote prefix for voter serial number
                '1990-01-15'
            ]);
            
            fclose($file);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
