<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $states = json_decode(
            Storage::disk('app')->get('Cidades.json'),
            true
        );

        foreach ($states as $state) {
            $citiesAsArray[] = [
                'id' => $state['ID'],
                'name' => $state['Nome'],
                'state_id' => $state['Estado']
            ];
        }

        City::insert($citiesAsArray);
    }
}
