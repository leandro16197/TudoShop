<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class ClienteSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        for ($i = 0; $i < 30; $i++) {
            DB::table('clientes')->insert([
                'nombre'     => $faker->firstName,
                'apellido'   => $faker->lastName,
                'email'      => $faker->unique()->safeEmail,
                'password'   => Hash::make('test1234'), 
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}