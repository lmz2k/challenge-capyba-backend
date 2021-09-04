<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < 5; $i += 1) {
            User::insert(
                [
                    'name' => Str::random(10),
                    'email' => Str::random(10).'@gmail.com',
                    'photo' => 'https://storage.glima.me/capyba.jpeg',
                    'password' => Hash::make('password'),
                ],
            );
        }
    }
}
