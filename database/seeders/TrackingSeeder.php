<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TrackingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $arrGeoData = array(
            array(
                  "latitude"  => 23.707310,
                  "longitude" => 90.415480,
                  "cityname"  => "Nurjahan Road, Dhaka, Bangladesh",
              ),
            array(
                  "latitude"  => 23.7752459,
                  "longitude" => 90.3875153,
                  "cityname"  => "Jahangir Gate, Dhaka, Bangladesh",
              ),
             array(
                  "latitude"  => 23.7947653,
                  "longitude" => 90.4013282,
                  "cityname"  => "Banani Model Town, Dacca, Bangladesh",
              ),
          );

        $userList = DB::table('users')->get();

        foreach ($arrGeoData as $key => $value) {

            foreach ($userList as $user) {
                DB::table('location_logs')->insert([
                    'user_id' => $user->id,
                    'company_id' => $user->company_id,
                    'latitude' => $value['latitude'],
                    'longitude' => $value['longitude'],
                    'location' => $value['cityname'],
                    'date' => date('Y-m-d'),
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }
        }


        
    }
}
