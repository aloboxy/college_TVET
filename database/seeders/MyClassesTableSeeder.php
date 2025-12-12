<?php
namespace Database\Seeders;

use App\Models\ClassType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class MyClassesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('my_classes')->delete();
        $ct = ClassType::pluck('id')->all();

        $data = [
            ['name' => 'Freshmen-NA', 'class_type_id' => $ct[0]],
            ['name' => 'Freshmen-LS', 'class_type_id' => $ct[1]],
            ['name' => 'Sophamore-NA', 'class_type_id' => $ct[0]],
            ['name' => 'Sophamore-LS', 'class_type_id' => $ct[1]],
            ['name' => 'Junior-NA', 'class_type_id' => $ct[0]],
            ['name' => 'Junior-LS', 'class_type_id' => $ct[1]],
            ['name' => 'Senior-NA', 'class_type_id' => $ct[0]],
            ['name' => 'Senior-LS', 'class_type_id' => $ct[1]],
            ];

        DB::table('my_classes')->insert($data);

    }
}
