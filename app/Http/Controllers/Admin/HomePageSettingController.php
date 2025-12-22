<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomePageSetting;
use Illuminate\Http\Request;

class HomePageSettingController extends Controller
{
    /**
     * Show the form for editing the home page settings.
     */
    public function edit()
    {
        $settings = HomePageSetting::getSettings();
        return view('admin.home-page-settings.edit', compact('settings'));
    }

    /**
     * Update the home page settings in storage.
     */
    public function update(Request $request)
    {
        $settings = HomePageSetting::getSettings();
        
        $validated = $request->validate([
            'page_title' => 'required|string|max:255',
            'countdown_title' => 'required|string|max:255',
            'countdown_message' => 'required|string|max:255',
            'waiting_title' => 'required|string|max:255',
            'waiting_message_1' => 'required|string',
            'waiting_message_2' => 'required|string',
            'election_info_title' => 'required|string|max:255',
            'area_name' => 'required|string|max:255',
            'election_center' => 'required|string|max:255',
            'total_voters' => 'required|string|max:255',
            'voters_section_title' => 'required|string|max:255',
            'total_voters_label' => 'required|string|max:255',
            'countdown_target_date' => 'required|date',
            'countdown_target_time' => 'required|string',
            'post_countdown_title' => 'nullable|string|max:255',
            'post_countdown_subtitle' => 'nullable|string|max:255',
        ]);

        // Combine date and time
        $dateTime = $validated['countdown_target_date'] . ' ' . $validated['countdown_target_time'];
        $validated['countdown_target_date'] = $dateTime;
        unset($validated['countdown_target_time']);

        $settings->update($validated);

        return redirect()->route('admin.home-page-settings.edit')
            ->with('success', 'Home page settings updated successfully.');
    }
}
