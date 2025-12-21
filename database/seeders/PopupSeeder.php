<?php

namespace Database\Seeders;

use App\Models\Popup;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PopupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create the default popup with existing data
        Popup::create([
            'title' => 'আল্লামা মামুনুল হক এর সালাম নিন',
            'message' => 'রিকশা মার্কায় ভোট দিন',
            'image' => null, // Can be uploaded later
            'icon_image' => null, // Can be uploaded later
            'is_active' => true,
        ]);
        
        $this->command->info('Default popup created with existing data.');
    }
}
