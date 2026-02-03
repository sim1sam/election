<?php

namespace App\Http\Controllers;

use App\Models\Voter;
use App\Models\HomePageSetting;
use App\Models\Popup;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Helpers\NumberConverter;
use Mpdf\Mpdf;

class VoterSearchController extends Controller
{
    /**
     * Check if countdown has ended
     */
    private function isCountdownFinished($settings)
    {
        if (!$settings->countdown_target_date) {
            return true; // If no countdown date set, allow access
        }
        
        $now = Carbon::now();
        $targetDate = Carbon::parse($settings->countdown_target_date);
        
        return $now->greaterThanOrEqualTo($targetDate);
    }

    /**
     * Show search form
     */
    public function index()
    {
        $settings = HomePageSetting::getSettings();
        
        // Check if countdown has finished
        if (!$this->isCountdownFinished($settings)) {
            return redirect('/')->with('error', 'ভোটার তথ্য এখনও প্রকাশিত হয়নি। অনুগ্রহ করে অপেক্ষা করুন।');
        }
        
        return view('voter-search', compact('settings'));
    }

    /**
     * Search voters by ward number and date of birth
     */
    public function search(Request $request)
    {
        $settings = HomePageSetting::getSettings();
        
        // Check if countdown has finished
        if (!$this->isCountdownFinished($settings)) {
            return redirect('/')->with('error', 'ভোটার তথ্য এখনও প্রকাশিত হয়নি। অনুগ্রহ করে অপেক্ষা করুন।');
        }
        
        $request->validate([
            'ward_number' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date',
        ]);

        $query = Voter::query();

        // Search by ward number (convert Bangla to English, use exact match)
        if ($request->filled('ward_number')) {
            $wardNumber = NumberConverter::banglaToEnglish(trim($request->ward_number));
            // Use exact match instead of LIKE for better accuracy
            $query->where('ward_number', $wardNumber);
        }

        // Search by date of birth (convert Bangla to English)
        if ($request->filled('date_of_birth')) {
            try {
                $dateValue = NumberConverter::convertDateToEnglish(trim($request->date_of_birth));
                $searchDate = null;
                
                // Parse date - prioritize dd/mm/yyyy format (Bangladesh format)
                if (preg_match('/^(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{4})$/', $dateValue, $matches)) {
                    $first = (int)$matches[1];
                    $second = (int)$matches[2];
                    $year = (int)$matches[3];
                    
                    // If first part > 12, it must be dd/mm/yyyy format
                    if ($first > 12) {
                        // Definitely dd/mm/yyyy format
                        $day = $first;
                        $month = $second;
                        $parsedDate = Carbon::create($year, $month, $day);
                    } elseif ($second > 12) {
                        // Definitely mm/dd/yyyy format
                        $month = $first;
                        $day = $second;
                        $parsedDate = Carbon::create($year, $month, $day);
                    } else {
                        // Ambiguous: both parts <= 12
                        // Prioritize dd/mm/yyyy (Bangladesh format) over mm/dd/yyyy (US format)
                        try {
                            // Try as dd/mm/yyyy first
                            $day = $first;
                            $month = $second;
                            $parsedDate = Carbon::create($year, $month, $day);
                        } catch (\Exception $e) {
                            // If invalid (e.g., Feb 30), try as mm/dd/yyyy
                            $month = $first;
                            $day = $second;
                            $parsedDate = Carbon::create($year, $month, $day);
                        }
                    }
                    $searchDate = $parsedDate->format('Y-m-d');
                } else {
                    // Try standard date formats
                    $formats = [
                        'd/m/Y',      // dd/mm/yyyy (preferred format)
                        'd-m-Y',      // dd-mm-yyyy
                        'Y-m-d',      // yyyy-mm-dd (ISO format)
                        'm/d/Y',      // mm/dd/yyyy (US format)
                    ];
                    
                    foreach ($formats as $format) {
                        try {
                            $parsedDate = Carbon::createFromFormat($format, $dateValue);
                            if ($parsedDate !== false) {
                                // Verify the format matches by reformatting
                                if ($parsedDate->format($format) === $dateValue) {
                                    $searchDate = $parsedDate->format('Y-m-d');
                                    break;
                                }
                            }
                        } catch (\Exception $e) {
                            continue;
                        }
                    }
                    
                    // If no format matched, try Carbon's parse (less reliable)
                    if (!$searchDate) {
                        $parsedDate = Carbon::parse($dateValue);
                        $searchDate = $parsedDate->format('Y-m-d');
                    }
                }
                
                if ($searchDate) {
                    // Use whereDate for exact date match
                    $query->whereDate('date_of_birth', $searchDate);
                }
            } catch (\Exception $e) {
                // Invalid date format, skip this filter
                // Log error for debugging
                Log::warning('Date parsing error in voter search: ' . $e->getMessage(), [
                    'date_value' => $request->date_of_birth,
                    'converted_value' => $dateValue ?? null
                ]);
            }
        }

        // Only show results if BOTH fields are filled
        if ($request->filled('ward_number') && $request->filled('date_of_birth')) {
            $voters = $query->orderBy('name')->get();
        } else {
            $voters = collect([]);
        }

        // Check if results are from IndexedDB
        if ($request->has('from_indexeddb') && $request->from_indexeddb == '1' && $request->has('indexeddb_results')) {
            try {
                $results = json_decode($request->indexeddb_results, true);
                if (is_array($results)) {
                    $voters = collect($results)->map(function ($item) {
                        return (object) $item;
                    });
                    return view('voter-search-results', compact('voters', 'settings', 'request'));
                }
            } catch (\Exception $e) {
                Log::error('Error parsing IndexedDB results: ' . $e->getMessage());
            }
        }

        return view('voter-search-results', compact('voters', 'settings', 'request'));
    }

    /**
     * API endpoint to get all voters for IndexedDB storage (with pagination)
     */
    public function getAllVoters(Request $request)
    {
        $settings = HomePageSetting::getSettings();
        
        // Check if countdown has finished
        if (!$this->isCountdownFinished($settings)) {
            return response()->json(['error' => 'ভোটার তথ্য এখনও প্রকাশিত হয়নি।'], 403);
        }

        // Get pagination parameters
        $page = $request->get('page', 1);
        $perPage = min($request->get('per_page', 100), 100); // Max 100 per page to avoid timeout
        
        // Get total count (cached for performance)
        try {
            $totalCount = Voter::count();
        } catch (\Exception $e) {
            Log::error('Error getting voter count: ' . $e->getMessage());
            return response()->json(['error' => 'Database error'], 500);
        }
        
        // Get voters with pagination using chunking for better performance
        try {
            $voters = Voter::select([
                'id',
                'name',
                'voter_number',
                'father_name',
                'mother_name',
                'occupation',
                'address',
                'polling_center_name',
                'ward_number',
                'voter_area_number',
                'voter_serial_number',
                'date_of_birth',
                'created_at',
                'updated_at'
            ])
            ->orderBy('id')
            ->skip(($page - 1) * $perPage)
            ->take($perPage)
            ->get();
        } catch (\Exception $e) {
            Log::error('Error fetching voters: ' . $e->getMessage());
            return response()->json(['error' => 'Database query error'], 500);
        }

        // Format date_of_birth as Y-m-d string
        $formattedVoters = $voters->map(function ($voter) {
            return [
                'id' => $voter->id,
                'name' => $voter->name,
                'voter_number' => $voter->voter_number,
                'father_name' => $voter->father_name,
                'mother_name' => $voter->mother_name,
                'occupation' => $voter->occupation,
                'address' => $voter->address,
                'polling_center_name' => $voter->polling_center_name,
                'ward_number' => $voter->ward_number,
                'voter_area_number' => $voter->voter_area_number,
                'voter_serial_number' => $voter->voter_serial_number,
                'date_of_birth' => $voter->date_of_birth ? $voter->date_of_birth->format('Y-m-d') : null,
                'created_at' => $voter->created_at ? $voter->created_at->toDateTimeString() : null,
                'updated_at' => $voter->updated_at ? $voter->updated_at->toDateTimeString() : null,
            ];
        });

        $totalPages = ceil($totalCount / $perPage);

        return response()->json([
            'success' => true,
            'total_count' => $totalCount,
            'current_page' => $page,
            'per_page' => $perPage,
            'total_pages' => $totalPages,
            'has_more' => $page < $totalPages,
            'count' => $formattedVoters->count(),
            'voters' => $formattedVoters
        ]);
    }

    /**
     * Download voter information as PDF
     */
    public function downloadPdf($id)
    {
        $voter = Voter::findOrFail($id);
        
        // Get active popup data (for candidate image, title, subtitle, icon)
        $popup = Popup::getFirstActive();
        
        // Generate PDF filename using person's name
        $filename = str_replace([' ', '/', '\\', ':', '*', '?', '"', '<', '>', '|'], '_', $voter->name) . '.pdf';
        
        // Render the view to HTML
        $html = view('voter-pdf', compact('voter', 'popup'))->render();
        
        // Configure mPDF with SolaimanLipi font support and sky blue theme
        $fontConfig = [
            'mode' => 'utf-8',
            'format' => 'A4',
            'orientation' => 'P',
            'margin_left' => 15,
            'margin_right' => 15,
            'margin_top' => 15,
            'margin_bottom' => 15,
            'tempDir' => storage_path('app/temp'),
            'autoScriptToLang' => true,
            'autoLangToFont' => true,
            'useSubstitutions' => true,
            'default_font' => 'freeserif',
            'default_font_size' => 14,
        ];
        
        // Check for SolaimanLipi font in multiple locations
        $solaimanLipiPaths = [
            base_path('vendor/mpdf/mpdf/ttfonts/SolaimanLipi.ttf'),
            base_path('vendor/mpdf/mpdf/ttfonts/solaimanlipi.ttf'),
            storage_path('fonts/SolaimanLipi.ttf'),
            storage_path('fonts/solaimanlipi.ttf'),
            public_path('fonts/SolaimanLipi.ttf'),
            public_path('fonts/solaimanlipi.ttf'),
        ];
        
        $fontFile = null;
        $fontDir = null;
        
        foreach ($solaimanLipiPaths as $fontPath) {
            if (file_exists($fontPath) && filesize($fontPath) > 1000) {
                $fontFile = basename($fontPath);
                $fontDir = dirname($fontPath);
                
                // If font is in storage or public, copy to vendor/mpdf/ttfonts for mPDF to use
                if (strpos($fontDir, 'vendor/mpdf/mpdf/ttfonts') === false) {
                    $targetPath = base_path('vendor/mpdf/mpdf/ttfonts/' . $fontFile);
                    if (!file_exists($targetPath)) {
                        @copy($fontPath, $targetPath);
                    }
                    if (file_exists($targetPath) && filesize($targetPath) > 1000) {
                        $fontFile = basename($targetPath);
                        $fontDir = base_path('vendor/mpdf/mpdf/ttfonts');
                    }
                }
                break;
            }
        }
        
        // If SolaimanLipi not found, fallback to NotoSansBengali
        if (!$fontFile) {
            $bengaliFontPath = base_path('vendor/mpdf/mpdf/ttfonts/NotoSansBengali-Regular.ttf');
            $storageFontPath = storage_path('fonts/NotoSansBengali-Regular.ttf');
            
            if (file_exists($bengaliFontPath) && filesize($bengaliFontPath) > 1000) {
                $fontFile = 'NotoSansBengali-Regular.ttf';
                $fontDir = base_path('vendor/mpdf/mpdf/ttfonts');
            } elseif (file_exists($storageFontPath) && filesize($storageFontPath) > 1000) {
                if (!file_exists($bengaliFontPath)) {
                    @copy($storageFontPath, $bengaliFontPath);
                }
                if (file_exists($bengaliFontPath) && filesize($bengaliFontPath) > 1000) {
                    $fontFile = 'NotoSansBengali-Regular.ttf';
                    $fontDir = base_path('vendor/mpdf/mpdf/ttfonts');
                }
            }
        }
        
        if ($fontFile && $fontDir) {
            $fontConfig['fontDir'] = [$fontDir];
            $fontName = strtolower(str_replace(['.ttf', '.TTF'], '', $fontFile));
            $fontName = str_replace(' ', '', $fontName);
            
            // Normalize font name for SolaimanLipi - ensure lowercase
            if (stripos($fontFile, 'solaiman') !== false) {
                $fontName = 'solaimanlipi';
            } elseif (stripos($fontFile, 'noto') !== false) {
                $fontName = 'notosansbengali';
            } else {
                $fontName = strtolower(str_replace(['.ttf', '.TTF', ' ', '-'], '', $fontFile));
            }
            
            // Register font with all variants (R=Regular, B=Bold, I=Italic, BI=BoldItalic)
            // Use same file for all variants if only one file available
            $fontData = [
                'R' => $fontFile,
                'B' => $fontFile,  // Use same file for bold
                'I' => $fontFile,  // Use same file for italic
                'BI' => $fontFile, // Use same file for bold italic
            ];
            
            $fontConfig['fontdata'] = [
                $fontName => $fontData,
            ];
            
            // Also register with different name variations for compatibility
            $fontConfig['fontdata'][strtolower($fontName)] = $fontData;
            if ($fontName !== str_replace(' ', '', $fontName)) {
                $fontConfig['fontdata'][str_replace(' ', '', $fontName)] = $fontData;
            }
            
            $fontConfig['default_font'] = $fontName;
        }
        
        $mpdf = new Mpdf($fontConfig);
        
        // Set default font for all text elements
        $defaultFont = $fontConfig['default_font'] ?? 'freeserif';
        $mpdf->SetDefaultFont($defaultFont);
        
        // Write HTML content with explicit font settings
        // Use SetFont before writing to ensure font is active
        if (isset($fontConfig['fontdata']) && $defaultFont !== 'freeserif') {
            try {
                $mpdf->SetFont($defaultFont, '', 14);
            } catch (\Exception $e) {
                // If font setting fails, continue with default
            }
        }
        
        $mpdf->WriteHTML($html);
        
        // Output PDF as download
        return $mpdf->Output($filename, 'D');
    }
}
