<?php
namespace Database\Seeders;

use App\Models\MyClass;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class SectionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('sections')->delete();
        $c = MyClass::pluck('id')->all();

        $data = [
            ['name' => '1', 'my_class_id' => $c[0], 'active' => 1],
            ['name' => '2', 'my_class_id' => $c[0], 'active' =>0],
            ['name' => '3', 'my_class_id' => $c[0], 'active' => 1],
            ['name' => '1', 'my_class_id' => $c[1], 'active' => 1],
            ['name' => '2', 'my_class_id' => $c[1], 'active' => 1],
            ['name' => '3', 'my_class_id' => $c[1], 'active' => 1],
        ];

        DB::table('sections')->insert($data);
    }
}
