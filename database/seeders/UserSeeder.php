<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{

    public function run(): void
    {
        // 48|IrTyHivYfplboUbvHGZLzGxtymjtZDhNDThYEGMv0b535f37
        $users = [
            [
                'first_name' => 'موظف ديوان',
                'last_name' => 'البداية',
                'birth_date' => '1990-01-01',
                'gender' => 1,
                'role' => 4,
                'phone_number' => '1234567890',
                'country' => 'syr',
                'city' => 'Dms',
                'street' => '5th',
                'user_name' => 'john_doe',
                'email' => 'johndoe@example.com',
            ],
            [
                'first_name' => 'موظف ديوان',
                'last_name' => 'الاستئناف',
                'birth_date' => '1985-05-05',
                'gender' => 2,
                'role' => 4,
                'phone_number' => '0987654321',
                'country' => 'syr',
                'city' => 'Dms',
                'street' => 'Street',
                'user_name' => 'jane_smith',
                'email' => 'janesmith@example.com',
            ],
            [
                'first_name' => 'موظف ديوان',
                'last_name' => 'النقض',
                'birth_date' => '1992-08-15',
                'gender' => 2,
                'role' => 4,
                'phone_number' => '9876543210',
                'country' => 'syr',
                'city' => 'Dms',
                'street' => 'Baker',
                'user_name' => 'alice_johnson',
                'email' => 'alicejohnson@example.com',
            ],
            [
                'first_name' => 'موظف ديوان',
                'last_name' => 'التنفيذ',
                'birth_date' => '1988-12-22',
                'gender' => 1,
                'role' => 4,
                'phone_number' => '0123456789',
                'country' => 'Australia',
                'city' => 'Sydney',
                'street' => 'George Street',
                'user_name' => 'bob_williams',
                'email' => 'bobwilliams@example.com',
            ],
        ];

        // إنشاء المستخدمين وحساباتهم
        foreach ($users as $userData) {
            // إنشاء المستخدم
            $user = User::create([
                'first_name' => $userData['first_name'],
                'last_name' => $userData['last_name'],
                'birth_date' => $userData['birth_date'],
                'gender' => $userData['gender'],
                'role' => $userData['role'],
                'phone_number' => $userData['phone_number'],
                'country' => $userData['country'],
                'city' => $userData['city'],
                'street' => $userData['street'],
            ]);

            // إنشاء الحساب المرتبط بالمستخدم
            Account::create([
                'user_id' => $user->id,
                'user_name' => $userData['user_name'],
                'email' => $userData['email'],
                'password' => Hash::make('password'), // تشفير كلمة المرور
                'status' => 2, // تعيين الحالة 2
                'email_verified_at' => now(),
            ]);
        }
    }
}
