<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');
 
    	for($i = 1; $i <= 10; $i++){
 
    	      // insert data ke table pegawai menggunakan Faker
    		DB::table('users')->insert([
            'name' => $faker->name,
            'username' => $faker->username,
            'role' => 'user',            
            'email' => $faker->unique()->safeEmail,
            'password' => Hash::make('1234'),
            'remember_token' => Str::random(10),
    		]);
 
    	}
    }
}
