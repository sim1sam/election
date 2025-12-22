<?php

namespace App\Http\Controllers;

use App\Models\Voter;
use App\Models\HomePageSetting;
use Illuminate\Http\Request;
use Carbon\Carbon;

class VoterSearchController extends Controller
{
    /**
     * Show search form
     */
    public function index()
    {
        $settings = HomePageSetting::getSettings();
        return view('voter-search', compact('settings'));
    }

    /**
     * Search voters by ward number and date of birth
     */
    public function search(Request $request)
    {
        $settings = HomePageSetting::getSettings();
        
        $request->validate([
            'ward_number' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date',
        ]);

        $query = Voter::query();

        // Search by ward number
        if ($request->filled('ward_number')) {
            $query->where('ward_number', 'like', '%' . $request->ward_number . '%');
        }

        // Search by date of birth
        if ($request->filled('date_of_birth')) {
            try {
                $dateValue = trim($request->date_of_birth);
                $searchDate = null;
                
                // Try multiple date formats in order of preference
                $formats = [
                    'd/m/Y',      // dd/mm/yyyy (preferred format)
                    'd-m-Y',      // dd-mm-yyyy
                    'Y-m-d',      // yyyy-mm-dd (ISO format)
                    'm/d/Y',      // mm/dd/yyyy (US format)
                ];
                
                foreach ($formats as $format) {
                    try {
                        $parsedDate = Carbon::createFromFormat($format, $dateValue);
                        if ($parsedDate) {
                            $searchDate = $parsedDate->format('Y-m-d');
                            break;
                        }
                    } catch (\Exception $e) {
                        // Try next format
                        continue;
                    }
                }
                
                // If no format matched, try Carbon's parse (more flexible)
                if (!$searchDate) {
                    $searchDate = Carbon::parse($dateValue)->format('Y-m-d');
                }
                
                $query->whereDate('date_of_birth', $searchDate);
            } catch (\Exception $e) {
                // Invalid date format, skip this filter
            }
        }

        // If no search criteria provided, return empty results
        if (!$request->filled('ward_number') && !$request->filled('date_of_birth')) {
            $voters = collect([]);
        } else {
            $voters = $query->orderBy('name')->get();
        }

        return view('voter-search-results', compact('voters', 'settings', 'request'));
    }
}
