<?php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin.pahina@gmail.com'],
            [
                'name' => 'Admin Pahina',
                'email' => 'admin.pahina@gmail.com',
                'email_verified_at' => now(),
                'password' => Hash::make('pahina123'),
                'birthday' => '1990-01-01',
                'phone' => '09123456789',
                'address' => 'Pahina Bookstore, Manila, Philippines',
                'profile_pic' => null,
                'is_admin' => true,
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        $this->command->info('✅ Admin user created: admin.pahina@gmail.com / pahina123');
        $users = [
            [
                'name' => 'Roger',
                'email' => 'rperonalim5@gmail.com',
                'password' => Hash::make('12345678'),
                'birthday' => '2004-10-26',
                'phone' => '09707425547',
                'address' => '123 Main St, Manila',
                'is_admin' => false,
            ],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                array_merge($userData, [
                    'email_verified_at' => now(),
                    'remember_token' => Str::random(10),
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }

        $this->command->info('✅ Sample users created successfully!');
        $this->command->info('Total users: ' . User::count());
    }
}