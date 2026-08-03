<?php

namespace Database\Seeders;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin account
        User::factory()->create([
            'name' => 'Admin TSAQIB',
            'email' => 'admin@tsaqib.sch.id',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'selected_community' => 'tahfidz',
        ]);

        // Sample member account
        User::factory()->create([
            'name' => 'Anggota TSAQIB',
            'email' => 'member@tsaqib.sch.id',
            'password' => Hash::make('password'),
            'role' => 'member',
            'selected_community' => 'young-stars',
        ]);

        // Default recruitment setting
        Setting::setByKey('recruitment_open', '1');
    }
}
