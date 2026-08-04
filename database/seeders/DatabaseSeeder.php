<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ١. دروستکردنی ڕۆڵەکان (دەسەڵاتەکان)
        $adminRole = Role::create(['name' => 'Admin']);
        $teacherRole = Role::create(['name' => 'Teacher']);
        $userRole = Role::create(['name' => 'Normal_User']);

        // ٢. دروستکردنی یەکەمین ئەکاونتی بەڕێوەبەر (Super Admin)
        $admin = User::create([
            'name' => 'بەڕێوەبەری سەرەکی',
            'username' => 'admin',
            'password' => Hash::make('admin'), // پاسۆردێکی کاتی
            'teacher_id' => null,
        ]);

        // ٣. پێدانی ڕۆڵی بەڕێوەبەر بەم ئەکاونتە
        $admin->assignRole($adminRole);
    }
}
