<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomePageSetting extends Model
{
    protected $fillable = [
        'page_title',
        'countdown_title',
        'countdown_message',
        'waiting_title',
        'waiting_message_1',
        'waiting_message_2',
        'election_info_title',
        'area_name',
        'election_center',
        'total_voters',
        'voters_section_title',
        'total_voters_label',
        'countdown_target_date',
        'post_countdown_title',
        'post_countdown_subtitle',
    ];

    protected $casts = [
        'countdown_target_date' => 'datetime',
    ];

    /**
     * Get the current settings (singleton pattern)
     */
    public static function getSettings()
    {
        $settings = static::first();
        if (!$settings) {
            $now = now();
            $targetDate = $now->copy()->addDays(20)->setTime(8, 0, 0);
            
            $settings = static::create([
                'page_title' => '🇧🇩 নির্বাচন তথ্য',
                'countdown_title' => '⏰ তথ্য প্রকাশের তারিখ পর্যন্ত অবশিষ্ট সময়',
                'countdown_message' => '📋 তথ্য প্রকাশের অপেক্ষায়...',
                'waiting_title' => '⏳ তথ্য প্রকাশের অপেক্ষায়',
                'waiting_message_1' => 'নির্ধারিত তারিখে সকল নির্বাচন তথ্য এখানে প্রকাশ করা হবে।',
                'waiting_message_2' => 'অনুগ্রহ করে অপেক্ষা করুন...',
                'election_info_title' => 'নির্বাচনী এলাকা তথ্য',
                'area_name' => 'ঢাকা-১',
                'election_center' => '১০',
                'total_voters' => '৫০,০০০',
                'voters_section_title' => 'সকল ভোটার তালিকা',
                'total_voters_label' => 'মোট ভোটার সংখ্যা',
                'countdown_target_date' => $targetDate,
            ]);
        }
        return $settings;
    }
}
