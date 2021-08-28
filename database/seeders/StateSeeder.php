<?php

namespace Database\Seeders;

use App\Models\State;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $states = json_decode(
            Storage::disk('app')->get('Estados.json'),
            true
        );

        foreach ($states as $state) {
            $statesAsArray[] = [
                'id' => $state['ID'],
                'name' => $state['Nome']
            ];
        }

        State::insert($statesAsArray);
    }
}
