<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Voter;
use App\Services\VoterImportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Helpers\NumberConverter;

class VoterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Voter::query();

        // Search by name (similar name search)
        if ($request->filled('name')) {
            $name = $request->name;
            $query->where('name', 'like', '%' . $name . '%');
        }

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

        // Search by voter area number (convert Bangla to English)
        if ($request->filled('voter_area_number')) {
            $voterAreaNumber = NumberConverter::banglaToEnglish($request->voter_area_number);
            $query->where('voter_area_number', 'like', '%' . $voterAreaNumber . '%');
        }

        // Search by voter serial number (convert Bangla to English)
        if ($request->filled('voter_serial_number')) {
            $voterSerialNumber = NumberConverter::banglaToEnglish($request->voter_serial_number);
            $query->where('voter_serial_number', 'like', '%' . $voterSerialNumber . '%');
        }

        // Filter by date of birth (জন্ম তারিখ) - parse dd/mm/yyyy format
        if ($request->filled('date_of_birth')) {
            try {
                $dobValue = trim($request->date_of_birth);
                $dobValue = NumberConverter::convertDateToEnglish($dobValue);
                $searchDate = null;
                
                // Parse date - prioritize dd/mm/yyyy format (Bangladesh format)
                if (preg_match('/^(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{4})$/', $dobValue, $matches)) {
                    $first = (int)$matches[1];
                    $second = (int)$matches[2];
                    $year = (int)$matches[3];
                    
                    // If first part > 12, it must be dd/mm/yyyy format
                    if ($first > 12) {
                        $day = $first;
                        $month = $second;
                        $parsedDate = \Carbon\Carbon::create($year, $month, $day);
                    } elseif ($second > 12) {
                        $month = $first;
                        $day = $second;
                        $parsedDate = \Carbon\Carbon::create($year, $month, $day);
                    } else {
                        // Ambiguous: prioritize dd/mm/yyyy (Bangladesh format)
                        try {
                            $day = $first;
                            $month = $second;
                            $parsedDate = \Carbon\Carbon::create($year, $month, $day);
                        } catch (\Exception $e) {
                            $month = $first;
                            $day = $second;
                            $parsedDate = \Carbon\Carbon::create($year, $month, $day);
                        }
                    }
                    $searchDate = $parsedDate->format('Y-m-d');
                } else {
                    // Try standard date formats
                    $formats = ['d/m/Y', 'd-m-Y', 'Y-m-d', 'm/d/Y'];
                    foreach ($formats as $format) {
                        try {
                            $parsedDate = \Carbon\Carbon::createFromFormat($format, $dobValue);
                            if ($parsedDate !== false && $parsedDate->format($format) === $dobValue) {
                                $searchDate = $parsedDate->format('Y-m-d');
                                break;
                            }
                        } catch (\Exception $e) {
                            continue;
                        }
                    }
                    
                    if (!$searchDate) {
                        $parsedDate = \Carbon\Carbon::parse($dobValue);
                        $searchDate = $parsedDate->format('Y-m-d');
                    }
                }
                
                if ($searchDate) {
                    $query->whereDate('date_of_birth', $searchDate);
                }
            } catch (\Exception $e) {
                // Invalid date format, skip filter
                \Log::warning('Date parsing error in admin voter filter: ' . $e->getMessage(), [
                    'date_value' => $request->date_of_birth
                ]);
            }
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
            'voter_number' => 'required|string|max:255',
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

        // Duplicate only when BOTH name AND voter number are same (same voter number + different name = allowed)
        $name = $validated['name'];
        $voterNumber = NumberConverter::banglaToEnglish($validated['voter_number']);
        if (Voter::where('name', $name)->where('voter_number', $voterNumber)->exists()) {
            return redirect()->back()->withInput()->withErrors(['voter_number' => __('The combination of name and voter number has already been taken.')]);
        }

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
            'voter_number' => 'required|string|max:255',
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

        // Duplicate only when BOTH name AND voter number are same (exclude current)
        $name = $validated['name'];
        $voterNumber = NumberConverter::banglaToEnglish($validated['voter_number']);
        if (Voter::where('name', $name)->where('voter_number', $voterNumber)->where('id', '!=', $voter->id)->exists()) {
            return redirect()->back()->withInput()->withErrors(['voter_number' => __('The combination of name and voter number has already been taken.')]);
        }

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
     * Show CSV upload form (Bulk Upload page)
     */
    public function showUploadForm()
    {
        return view('admin.voters.upload');
    }

    /**
     * Handle CSV file upload: process immediately (chunked, memory-safe for 1.5L+ DB).
     * No queue – upload and import run in one step.
     */
    public function importCsv(Request $request)
    {
        $request->validate([
            'csv_file' => [
                'required',
                'file',
                function ($attribute, $value, $fail) {
                    if ($value) {
                        $extension = strtolower($value->getClientOriginalExtension());
                        if ($extension !== 'csv') {
                            $fail('The file must be a CSV file (.csv extension).');
                        }
                    }
                },
                'max:51200', // 50MB max
            ],
            'polling_center_name' => 'required|string|max:255',
            'ward_number' => 'required|string|max:255',
            'voter_area_number' => 'required|string|max:255',
        ]);

        $commonFields = [
            'polling_center_name' => $request->input('polling_center_name'),
            'ward_number' => NumberConverter::banglaToEnglish($request->input('ward_number')),
            'voter_area_number' => NumberConverter::banglaToEnglish($request->input('voter_area_number')),
        ];

        $file = $request->file('csv_file');
        $path = $file->getRealPath();

        ini_set('memory_limit', '512M');
        set_time_limit(600); // 10 minutes for 3k–4k rows

        try {
            $results = VoterImportService::runImport($path, $commonFields);
        } catch (\Throwable $e) {
            return redirect()->route('admin.voters.upload')
                ->with('error', 'Import failed: ' . $e->getMessage())
                ->withInput();
        }

        $message = 'Import completed. Imported ' . $results['success'] . ' voters.';
        if ($results['duplicates'] > 0) {
            $message .= ' ' . $results['duplicates'] . ' duplicate(s) skipped.';
        }
        if (count($results['errors']) > 0) {
            $message .= ' ' . count($results['errors']) . ' error(s).';
        }

        return redirect()->route('admin.voters.upload')
            ->with('success', $message)
            ->with('import_errors', array_slice($results['errors'], 0, 100));
    }

    /**
     * Export voters to CSV (respects current filters).
     */
    public function exportCsv(Request $request)
    {
        set_time_limit(0); // Allow long-running export (e.g. 400k+ rows)
        $query = Voter::query();

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        if ($request->filled('voter_number')) {
            $voterNumber = NumberConverter::banglaToEnglish($request->voter_number);
            $query->where('voter_number', 'like', '%' . $voterNumber . '%');
        }
        if ($request->filled('ward_number')) {
            $wardNumber = NumberConverter::banglaToEnglish($request->ward_number);
            $query->where('ward_number', 'like', '%' . $wardNumber . '%');
        }
        if ($request->filled('voter_area_number')) {
            $voterAreaNumber = NumberConverter::banglaToEnglish($request->voter_area_number);
            $query->where('voter_area_number', 'like', '%' . $voterAreaNumber . '%');
        }
        if ($request->filled('voter_serial_number')) {
            $voterSerialNumber = NumberConverter::banglaToEnglish($request->voter_serial_number);
            $query->where('voter_serial_number', 'like', '%' . $voterSerialNumber . '%');
        }
        if ($request->filled('date_of_birth')) {
            $query->whereDate('date_of_birth', $request->date_of_birth);
        }

        $filename = 'voters_export_' . date('Y-m-d_His') . '.csv';

        // Stream CSV in chunks to avoid memory limit (export all = 400k+ rows)
        return response()->streamDownload(function () use ($request) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($file, [
                'নাম (Name)',
                'ভোটার নম্বর (Voter Number)',
                'পিতা (Father Name)',
                'মাতা (Mother Name)',
                'পেশা (Occupation)',
                'ঠিকানা (Address)',
                'ভোট কেন্দ্র (Polling Center)',
                'ওয়ার্ড নম্বর (Ward Number)',
                'ভোটার এলাকার নম্বর (Voter Area Number)',
                'ভোটার সিরিয়াল নম্বর (Voter Serial Number)',
                'জন্ম তারিখ (Date of Birth)',
            ]);

            $query = Voter::query();
            if ($request->filled('name')) {
                $query->where('name', 'like', '%' . $request->name . '%');
            }
            if ($request->filled('voter_number')) {
                $voterNumber = NumberConverter::banglaToEnglish($request->voter_number);
                $query->where('voter_number', 'like', '%' . $voterNumber . '%');
            }
            if ($request->filled('ward_number')) {
                $wardNumber = NumberConverter::banglaToEnglish($request->ward_number);
                $query->where('ward_number', 'like', '%' . $wardNumber . '%');
            }
            if ($request->filled('voter_area_number')) {
                $voterAreaNumber = NumberConverter::banglaToEnglish($request->voter_area_number);
                $query->where('voter_area_number', 'like', '%' . $voterAreaNumber . '%');
            }
            if ($request->filled('voter_serial_number')) {
                $voterSerialNumber = NumberConverter::banglaToEnglish($request->voter_serial_number);
                $query->where('voter_serial_number', 'like', '%' . $voterSerialNumber . '%');
            }
            if ($request->filled('date_of_birth')) {
                $query->whereDate('date_of_birth', $request->date_of_birth);
            }

            $query->orderBy('id', 'asc')->chunk(2000, function ($voters) use ($file) {
                foreach ($voters as $voter) {
                    $dob = $voter->date_of_birth
                        ? (\Carbon\Carbon::parse($voter->date_of_birth)->format('Y-m-d'))
                        : '';
                    fputcsv($file, [
                        $voter->name ?? '',
                        "'" . ($voter->voter_number ?? ''),
                        $voter->father_name ?? '',
                        $voter->mother_name ?? '',
                        $voter->occupation ?? '',
                        $voter->address ?? '',
                        $voter->polling_center_name ?? '',
                        $voter->ward_number ?? '',
                        $voter->voter_area_number ?? '',
                        $voter->voter_serial_number ?? '',
                        $dob,
                    ]);
                }
            });
            fclose($file);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
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
