<?php

namespace App\Http\Controllers;

use App\Models\Voter;
use App\Models\HomePageSetting;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Helpers\NumberConverter;

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

        return view('voter-search-results', compact('voters', 'settings', 'request'));
    }
}
