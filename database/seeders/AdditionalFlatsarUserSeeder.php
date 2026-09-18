<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\User;

class AdditionalFlatsarUserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['ADMIN', '199112182025061001', 'admin@gmail.com', '082292812021', 'admin'],
            ['KARYAWAN', '199606302015031001', 'karyawan@gmail.com', '085395479190', 'karyawan'],
        ];

        foreach ($users as $u) {
            User::updateOrCreate(
                ['email' => $u[2]],
                [
                    'name' => $u[0],
                    'NIP' => $u[1],
                    'phone' => $u[3],
                    'role' => $u[4],
                    'institution' => null,
                    'password' => Hash::make('password'),
                    'email_verified_at' => Carbon::now(),
                ]
            );
        }
    }
}