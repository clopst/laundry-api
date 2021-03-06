<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'name' => 'John Doe',
                'email' => 'johndoe@example.com',
                'phone_number' => '081244556677',
                'address' => 'Jl. Sukamiskin No.7',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'Jane Doe',
                'email' => 'janedoe@example.com',
                'phone_number' => '081244556688',
                'address' => 'Jl. Sukamiskin No.9',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ];

        $faker = \Faker\Factory::create('id_ID');
        for ($i = 0; $i < 10; $i++) {
            $users[] = [
                'name' => $faker->name,
                'email' => $faker->safeEmail,
                'phone_number' => $faker->phoneNumber,
                'address' => $faker->address,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];
        }

        DB::table('customers')->insert($users);
    }
}
