<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first user or create one
        $user = User::first();
        
        if ($user) {
            // Set the first user as admin
            $user->update(['role' => 'admin']);
            $this->command->info('First user set as admin: ' . $user->email);
        } else {
            // Create admin user if no users exist
            $admin = User::create([
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]);
            $this->command->info('Admin user created: ' . $admin->email);
            $this->command->warn('Default password: password - Please change it!');
        }
    }
}
