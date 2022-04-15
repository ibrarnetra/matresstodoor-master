<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use App\Models\Admin\Geozone;

class GeozoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $geozone_1 = Geozone::create([
            'name' => "Ontario Zone",
            'description' => "Ontario Zone",
        ]);

        DB::table("geozone_zone")->insert([
            'geozone_id' => $geozone_1->id,
            'country_id' => '38',
            'zone_id' => '610',
        ]);

        $geozone_2 = Geozone::create([
            'name' => "Alberta Zone",
            'description' => "Alberta Zone",
        ]);

        DB::table("geozone_zone")->insert([
            'geozone_id' => $geozone_2->id,
            'country_id' => '38',
            'zone_id' => '602',
        ]);

        $geozone_3 = Geozone::create([
            'name' => "Manitoba Zone",
            'description' => "Manitoba Zone",
        ]);

        DB::table("geozone_zone")->insert([
            'geozone_id' => $geozone_3->id,
            'country_id' => '38',
            'zone_id' => '604',
        ]);
    }
}
