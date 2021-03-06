<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OutletSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $outlets = [
            [
                'name' => 'Outlet Bandung',
                'phone_number' => '081244556677',
                'address' => 'Jl. Bandung Negara',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'Outlet Jakarta',
                'phone_number' => '081244556688',
                'address' => 'Jl. Jakarta Negara',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ];

        $faker = \Faker\Factory::create('id_ID');
        for ($i = 0; $i < 10; $i++) {
            $outlets[] = [
                'name' => $faker->name,
                'phone_number' => $faker->phoneNumber,
                'address' => $faker->address,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];
        }

        DB::table('outlets')->insert($outlets);
    }
}
