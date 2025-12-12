<?php
namespace Database\Seeders;

use App\Models\State;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatesTableSeeder extends Seeder
{

    public function run()
    {
        DB::table('states')->delete();

        $states = [
            'Montserrado', 'Margibi', 'Lofa', 'Nimba', 'River Cess', 'River Gee', 'Grand Bassa', 'Borno', 'Grand Cape Mount', 'Grand Gedeh', 'Maryland', 'Bomi', 'Bong', 'Grand Kru', 'Sinoe', 'Gbarpolu',
        ];

        foreach ($states as $state) {
            State::create(['name' => $state]);
        }
    }

}
