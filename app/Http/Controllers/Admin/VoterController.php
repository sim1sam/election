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
     */
    public function importCsv(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:10240', // 10MB max
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
        
        $data = array_map('str_getcsv', file($path));
        
        // Remove header row
        $header = array_shift($data);
        
        // Expected columns (in order) - removed polling_center_name, ward_number, voter_area_number
        $expectedColumns = [
            'name', 'voter_number', 'father_name', 'mother_name', 
            'occupation', 'address', 'voter_serial_number', 'date_of_birth'
        ];
        
        $results = [
            'success' => 0,
            'errors' => [],
            'duplicates' => 0,
        ];
        
        DB::beginTransaction();
        
        try {
            foreach ($data as $rowIndex => $row) {
                $rowNumber = $rowIndex + 2; // +2 because header is row 1, and array is 0-indexed
                
                // Skip empty rows
                if (empty(array_filter($row))) {
                    continue;
                }
                
                // Map row data to columns
                $voterData = [];
                foreach ($expectedColumns as $index => $column) {
                    $value = isset($row[$index]) ? trim($row[$index]) : null;
                    
                    // Remove single quote or tab prefix (used to force Excel to treat as text)
                    // Excel adds single quote to preserve leading zeros
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
                
                // Check for duplicate voter number
                if (Voter::where('voter_number', $voterData['voter_number'])->exists()) {
                    $results['duplicates']++;
                    $results['errors'][] = "Row {$rowNumber}: Voter number '{$voterData['voter_number']}' already exists.";
                    continue;
                }
                
                // Parse date if provided
                if (!empty($voterData['date_of_birth'])) {
                    try {
                        $dateValue = trim($voterData['date_of_birth']);
                        $date = null;
                        
                        // Check if it's an Excel serial date (numeric value like 33603)
                        // Excel serial dates: 1 = 1900-01-01, but Excel incorrectly treats 1900 as leap year
                        // So we need to subtract 2 days from the calculation
                        if (is_numeric($dateValue) && (float)$dateValue > 1 && (float)$dateValue < 1000000 && !preg_match('/[\/\-]/', $dateValue)) {
                            // Convert Excel serial date to actual date
                            // Excel epoch starts at 1900-01-01 (day 1)
                            // But Excel incorrectly treats 1900 as a leap year, so we adjust
                            $excelEpoch = \Carbon\Carbon::create(1899, 12, 30, 0, 0, 0);
                            $days = (int)$dateValue;
                            $date = $excelEpoch->copy()->addDays($days - 1); // Subtract 1 because Excel day 1 = 1900-01-01
                        } else {
                            // Handle date strings - prioritize Bangladesh format (dd/mm/yyyy)
                            
                            // First, try to parse as dd/mm/yyyy (Bangladesh format - PRIORITY)
                            if (preg_match('/^(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{4})$/', $dateValue, $matches)) {
                                $first = (int)$matches[1];
                                $second = (int)$matches[2];
                                $year = (int)$matches[3];
                                
                                // If first part > 12, it must be dd/mm/yyyy (day cannot be > 31, but month can't be > 12)
                                if ($first > 12) {
                                    // Definitely dd/mm/yyyy format
                                    $day = $first;
                                    $month = $second;
                                    $date = \Carbon\Carbon::create($year, $month, $day);
                                } elseif ($second > 12) {
                                    // Definitely mm/dd/yyyy format (second part is day > 12)
                                    $month = $first;
                                    $day = $second;
                                    $date = \Carbon\Carbon::create($year, $month, $day);
                                } else {
                                    // Ambiguous: both parts <= 12
                                    // Prioritize dd/mm/yyyy (Bangladesh format) over mm/dd/yyyy (US format)
                                    try {
                                        // Try as dd/mm/yyyy first
                                        $day = $first;
                                        $month = $second;
                                        $date = \Carbon\Carbon::create($year, $month, $day);
                                    } catch (\Exception $e) {
                                        // If invalid (e.g., Feb 30), try as mm/dd/yyyy
                                        $month = $first;
                                        $day = $second;
                                        $date = \Carbon\Carbon::create($year, $month, $day);
                                    }
                                }
                            } else {
                                // Try standard date formats
                                $formats = [
                                    'd/m/Y',      // 01/03/1992 (dd/mm/yyyy - Bangladesh format)
                                    'd-m-Y',      // 01-03-1992 (dd-mm-yyyy)
                                    'Y-m-d',      // 1992-01-03 (ISO format)
                                    'Y/m/d',      // 1992/01/03
                                    'm/d/Y',      // 01/03/1992 (mm/dd/yyyy - US format)
                                    'n/j/Y',      // 1/3/1992 (m/d/yyyy - US format without leading zeros)
                                ];
                                
                                foreach ($formats as $format) {
                                    try {
                                        $parsedDate = \Carbon\Carbon::createFromFormat($format, $dateValue);
                                        if ($parsedDate !== false) {
                                            // Verify the format matches by reformatting
                                            if ($parsedDate->format($format) === $dateValue) {
                                                $date = $parsedDate;
                                                break;
                                            }
                                        }
                                    } catch (\Exception $e) {
                                        continue;
                                    }
                                }
                                
                                // Last resort: use Carbon's parse (less reliable)
                                if (!$date) {
                                    $date = \Carbon\Carbon::parse($dateValue);
                                }
                            }
                        }
                        
                        // Validate date is reasonable
                        if (!$date) {
                            throw new \Exception("Could not parse date: {$dateValue}");
                        }
                        
                        if ($date->isFuture()) {
                            throw new \Exception("Date cannot be in the future: {$dateValue}");
                        }
                        
                        if ($date->year < 1900 || $date->year > 2100) {
                            throw new \Exception("Date year seems invalid: {$dateValue}");
                        }
                        
                        // Store the date in Y-m-d format
                        $storedDate = $date->format('Y-m-d');
                        
                        // Log for debugging (can be removed in production)
                        Log::info('Date parsed in CSV import', [
                            'original' => $dateValue,
                            'parsed' => $storedDate,
                            'row' => $rowNumber
                        ]);
                        
                        $voterData['date_of_birth'] = $storedDate;
                    } catch (\Exception $e) {
                        $results['errors'][] = "Row {$rowNumber}: Invalid date format for date_of_birth '{$voterData['date_of_birth']}'. Error: " . $e->getMessage() . ". Expected format: DD/MM/YYYY (e.g., 01/03/1992 for 1st March 1992).";
                        continue;
                    }
                } else {
                    $voterData['date_of_birth'] = null;
                }
                
                // Apply common fields to all voters
                $voterData = array_merge($voterData, $commonFields);
                
                // Create voter
                try {
                    Voter::create($voterData);
                    $results['success']++;
                } catch (\Exception $e) {
                    $results['errors'][] = "Row {$rowNumber}: " . $e->getMessage();
                }
            }
            
            DB::commit();
            
            $message = "Import completed! Successfully imported {$results['success']} voters.";
            if ($results['duplicates'] > 0) {
                $message .= " {$results['duplicates']} duplicate(s) skipped.";
            }
            if (count($results['errors']) > 0) {
                $message .= " " . count($results['errors']) . " error(s) occurred.";
            }
            
            return redirect()->route('admin.voters.index')
                ->with('success', $message)
                ->with('import_errors', $results['errors']);
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.voters.index')
                ->with('error', 'Import failed: ' . $e->getMessage());
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
