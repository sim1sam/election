<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Voter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class VoterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Voter::query();

        // Search by voter number
        if ($request->filled('voter_number')) {
            $query->where('voter_number', 'like', '%' . $request->voter_number . '%');
        }

        // Search by ward number
        if ($request->filled('ward_number')) {
            $query->where('ward_number', 'like', '%' . $request->ward_number . '%');
        }

        // Search by voter serial number
        if ($request->filled('voter_serial_number')) {
            $query->where('voter_serial_number', 'like', '%' . $request->voter_serial_number . '%');
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
        ]);

        $file = $request->file('csv_file');
        $path = $file->getRealPath();
        
        $data = array_map('str_getcsv', file($path));
        
        // Remove header row
        $header = array_shift($data);
        
        // Expected columns (in order)
        $expectedColumns = [
            'name', 'voter_number', 'father_name', 'mother_name', 
            'occupation', 'address', 'polling_center_name', 'ward_number', 
            'voter_area_number', 'voter_serial_number', 'date_of_birth'
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
                        
                        // Try multiple date formats in order of preference
                        $formats = [
                            'Y-m-d',      // 1990-01-15
                            'd/m/Y',      // 15/01/1990
                            'd-m-Y',      // 15-01-1990
                            'm/d/Y',      // 01/15/1990 (US format with leading zeros)
                            'n/j/Y',      // 1/15/1990 (US format without leading zeros)
                            'Y/m/d',      // 1990/01/15
                        ];
                        
                        foreach ($formats as $format) {
                            try {
                                $parsedDate = \Carbon\Carbon::createFromFormat($format, $dateValue);
                                if ($parsedDate) {
                                    $date = $parsedDate;
                                    break;
                                }
                            } catch (\Exception $e) {
                                // Try next format
                                continue;
                            }
                        }
                        
                        // If no format matched, try Carbon's parse (more flexible)
                        if (!$date) {
                            $date = \Carbon\Carbon::parse($dateValue);
                        }
                        
                        $voterData['date_of_birth'] = $date->format('Y-m-d');
                    } catch (\Exception $e) {
                        $results['errors'][] = "Row {$rowNumber}: Invalid date format for date_of_birth '{$voterData['date_of_birth']}'. Supported formats: YYYY-MM-DD, DD/MM/YYYY, M/D/YYYY, or DD-MM-YYYY.";
                        continue;
                    }
                } else {
                    $voterData['date_of_birth'] = null;
                }
                
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
            
            // Header row
            fputcsv($file, [
                'Name (নাম)',
                'Voter Number (ভোটার নম্বর)',
                'Father Name (পিতা)',
                'Mother Name (মাতা)',
                'Occupation (পেশা)',
                'Address (ঠিকানা)',
                'Polling Center Name (ভোট কেন্দ্রের নাম)',
                'Ward Number (ওয়ার্ড নম্বর)',
                'Voter Area Number (ভোটার এলাকার নম্বর)',
                'Voter Serial Number (ভোটার সিরিয়াল নম্বর)',
                'Date of Birth (জন্ম তারিখ)'
            ]);
            
            // Sample row - prefix voter number and ward number with single quote to force text format
            // Single quote prefix forces Excel to treat as text (won't show in cell)
            fputcsv($file, [
                'আহমেদ হাসান',
                "'V-001234",  // Single quote prefix forces Excel to treat as text
                'মোহাম্মদ আলী',
                'ফাতেমা খাতুন',
                'ব্যবসায়ী',
                'ঢাকা, বাংলাদেশ',
                'ঢাকা কলেজ',
                "'১০",  // Single quote prefix for ward number
                "'১০১",  // Single quote prefix for voter area number
                "'১২৩৪",  // Single quote prefix for voter serial number
                '1990-01-15'
            ]);
            
            fclose($file);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
