<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class VacanciesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $occupations = [
            Vacancy::FULLSTACK_OCCUPATION,
            Vacancy::FRONTEND_OCCUPATION,
            Vacancy::BACKEND_OCCUPATION
        ];

        $hiringModes = [
            Vacancy::PJ_HIRING,
            Vacancy::CLT_HIRING,
            Vacancy::BOTH_HIRING
        ];

        $users = User::latest()->take(5)->get();
        $usersIds = $users->pluck('id');

        for ($i = 0; $i < 25; $i += 1) {
            $randomUserIndex = random_int(0, 4);
            $randomOccupationIndex = random_int(0, 2);
            $randomHiringIndex = random_int(0, 2);

            $selectedCity = random_int(0, 100);
            $isHomeOffice = random_int(0, 1);
            $salary = random_int(0, 25000);
            $randomString = Str::random(10);

            $vacancyObjects[] = [
                'title' => "Vaga de trabalho na " . $randomString,
                'description' => $randomString,
                'salary' => $salary,
                'occupation' => $occupations[$randomOccupationIndex],
                'is_home_office' => $isHomeOffice,
                'hiring_mode' => $hiringModes[$randomHiringIndex],
                'city_id' => $selectedCity,
                'announcement_by' => $usersIds[$randomUserIndex],
            ];
        }

        Vacancy::insert($vacancyObjects);
    }
}
