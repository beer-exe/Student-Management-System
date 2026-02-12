<?php

namespace Database\Seeders;

use App\Models\Guardian;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        DB::table('user_roles')->insert([
            ['name' => 'Admin'],
            ['name' => 'Teacher'],
            ['name' => 'Student'],
        ]);

        DB::table('users')->insert([
            [
                'email' => 'test@admin.com',
                'role_id' => 1,
                'password' => Hash::make('test@admin.com'),
                'created_at' => now(),
            ],
            [
                'email' => 'test@teacher.com',
                'password' => Hash::make('test@teacher.com'),
                'role_id' => 2,
                'created_at' => now(),
            ],
            [
                'email' => 'test@student.com',
                'password' => Hash::make('test@student.com'),
                'role_id' => 3,
                'created_at' => now(),
            ],
        ]);

        DB::table('admins')->insert([
            [
                'user_id' => User::first()->id,
                'name' => 'Test Admin',
                'created_at' => now(),
            ],
        ]);

        DB::table('teachers')->insert([
            [
                'user_id' => User::find(2)->id,
                'salutation' => 'Mr.',
                'initials' => 'T.',
                'first_name' => 'Test',
                'last_name' => 'Teacher',
                'nic' => '123456789V',
                'dob' => '1990-01-01',
                'created_at' => now(),
            ],
        ]);


        DB::table('guardians')->insert([
            [
                'initials' => 'T.',
                'first_name' => 'Test',
                'last_name' => 'Guardian',
                'nic' => '123456789V',
                'phone_number' => '0712345678',
                'created_at' => now(),
            ],
        ]);

        DB::table('students')->insert([
            [
                'user_id' => User::find(3)->id,
                'first_name' => 'Test',
                'last_name' => 'Student',
                'gender' => 'Male',
                'nic' => '987654321V',
                'dob' => '2000-01-01',
                'guardian_id' => Guardian::first()->id,
                'created_at' => now(),
            ]
        ]);

        DB::table('grades')->insert([
            ['name' => 'Lớp 1', 'created_at' => now()],
            ['name' => 'Lớp 2', 'created_at' => now()],
            ['name' => 'Lớp 3', 'created_at' => now()],
            ['name' => 'Lớp 4', 'created_at' => now()],
            ['name' => 'Lớp 5', 'created_at' => now()],
            ['name' => 'Lớp 6', 'created_at' => now()],
            ['name' => 'Lớp 7', 'created_at' => now()],
            ['name' => 'Lớp 8', 'created_at' => now()],
            ['name' => 'Lớp 9', 'created_at' => now()],
            ['name' => 'Lớp 10', 'created_at' => now()],
            ['name' => 'Lớp 11', 'created_at' => now()],
            ['name' => 'Lớp O/L', 'created_at' => now()],
            ['name' => 'Lớp 12', 'created_at' => now()],
            ['name' => 'Lớp 13', 'created_at' => now()],
            ['name' => 'After A/L', 'created_at' => now()],
        ]);

        DB::table('subject_streams')->insert([
            ['stream_name' => 'Khoa học vật lý', 'created_at' => now()],
            ['stream_name' => 'Khoa học sinh học', 'created_at' => now()],
            ['stream_name' => 'Công nghệ kỹ thuật', 'created_at' => now()],
            ['stream_name' => 'Công nghệ hệ thống sinh học', 'created_at' => now()],
            ['stream_name' => 'Thương mại', 'created_at' => now()],
            ['stream_name' => 'Nghệ thuật', 'created_at' => now()],
            ['stream_name' => 'Common', 'created_at' => now()],
            ['stream_name' => 'Other', 'created_at' => now()],
        ]);
    }
}
