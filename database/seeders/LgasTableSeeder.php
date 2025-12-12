<?php
namespace Database\Seeders;

use App\Models\Lga;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class LgasTableSeeder extends Seeder
{

    public function run()
    {
        DB::table('lgas')->delete();

        $state_id = [
            1,
            1,
            1,
            1,
            1,
            1,
            1,
            1,
            1,
            1,
            1,
            1,
            1,
            1,
            1,
            1,
            1,
            1,
            1,
            1,
            1,
            1,
            1,
            1,
            1,
            1,
            1,
            1,
            1,
            1,
            1,
            1,
            1,
            1,
            1,
            1,
            1,
            1,
            1,
            1,
            1,
            1,
            1,
            1,
            1,
            1,
            1,
            1,
            1,
            1,
        ];
        $lgas = [
        "Bakoi",
        "Banjoa",
        "Barekling",
        "Bassa Community",
        "Buzzi Quarters",
        "Clara Town",
        "Crown Hill",
        "Dixville",
        "Doin Town",
        "Dwahn Town",
        "Duala",
        "Fanti Town",
        "Jatuja",
        "Jacob Town",
        "Jallah Town",
        "Logan Town",
        "Matadi",
        "New Kru Town",
        "Old Road",
        "Point Four",
        "Red Light",
        "Slipway",
        "Snapper Hill",
        "South Beach",
        "Toe Town",
        "Tomo",
        "Topoe Village",
        "Vai Town",
        "Virginia",
        "A.B. Tolbert Community",
        "Duport Road",
        "ELWA",
        "Gobuychop",
        "Grayja",
        "Kendeja",
        "Kenny Town",
        "King Gray Town",
        "Nizohn",
        "Parker-Paint",
        "Plofe",
        "Police Academy",
        "Red Light Market",
        "Rehab Road",
        "Peace Island",
        "SD Cooper Road",
        "Sinda Town",
        "SKD Boulevard",
        "Stephan Tolbert Estates",
        "Wamba Town",
        "GSA Road",
        ];
        for($i=0; $i<count($lgas); $i++){
            Lga::create(['state_id' => $state_id[$i], 'name' => $lgas[$i]]);
        }
    }

}
