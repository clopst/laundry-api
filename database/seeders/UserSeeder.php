<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = ['admin', 'owner', 'cashier'];

        $users = [
            [
                'name' => 'Superadmin',
                'username' => 'admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('12345678'),
                'role' => 'admin',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'John Doe',
                'username' => 'johndoe',
                'email' => 'johndoe@example.com',
                'password' => Hash::make('12345678'),
                'role' => 'owner',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Smith Doe',
                'username' => 'smithdoe',
                'email' => 'smithdoe@example.com',
                'password' => Hash::make('12345678'),
                'role' => 'cashier',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ];

        $faker = \Faker\Factory::create('id_ID');
        for ($i = 0; $i < 10; $i++) {
            $users[] =
                [
                    'name' => $faker->name,
                    'username' => $faker->userName,
                    'email' => $faker->safeEmail,
                    'password' => Hash::make('12345678'),
                    'role' => $roles[array_rand($roles)],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ];
        }

        DB::table('users')->insert($users);
    }
}
