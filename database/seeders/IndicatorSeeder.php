<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Performance\Appraisal;
use App\Models\Performance\Indicator;

class IndicatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // performance type
        $names = ['Organizational Competencies', 'Technical Competencies', 'Behavioural Competencies'];
        foreach ($names as $name) {
            DB::table('competence_types')->insert([
                'name' => $name,
                'company_id' => 2,
                'created_by' => 2,
                'updated_by' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        //competences

        DB::table('competences')->insert([
            'name' => 'Leadership',
            'competence_type_id' => 1,
            'company_id' => 2,
            'created_by' => 2,
            'updated_by' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('competences')->insert([
            'name' => 'Project Management',
            'competence_type_id' => 1,
            'company_id' => 2,
            'created_by' => 2,
            'updated_by' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('competences')->insert([
            'name' => 'Allocating Resources',
            'competence_type_id' => 2,
            'company_id' => 2,
            'created_by' => 2,
            'updated_by' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('competences')->insert([
            'name' => 'Team Work',
            'competence_type_id' => 2,
            'company_id' => 2,
            'created_by' => 2,
            'updated_by' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('competences')->insert([
            'name' => 'Business Process',
            'competence_type_id' => 3,
            'company_id' => 2,
            'created_by' => 2,
            'updated_by' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('competences')->insert([
            'name' => 'Oral Communication',
            'competence_type_id' => 3,
            'company_id' => 2,
            'created_by' => 2,
            'updated_by' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);


        // indicator
        $rates_json = [

            [
                'id' => 1,
                'rating' => 1,
            ],
            [
                'id' => 2,
                'rating' => 1,
            ],
            [
                'id' => 3,
                'rating' => 1,
            ],
            [
                'id' => 4,
                'rating' => 1,
            ],
            [
                'id' => 5,
                'rating' => 1,
            ],
            [
                'id' => 6,
                'rating' => 1,
            ],
        ];
        $indicator = new Indicator();
        $indicator->name = 'Project Management';
        $indicator->department_id = 18;
        $indicator->shift_id = 4;
        $indicator->designation_id = 43;
        $indicator->company_id = 2;
        $indicator->rating = 1;
        $indicator->added_by = 2;
        $indicator->rates = $rates_json;
        $indicator->save();

        // appraisals

        $appraisals = new Appraisal();
        $appraisals->name = 'Project Management';
        $appraisals->user_id = 4;
        $appraisals->company_id = 2;
        $appraisals->added_by = 2;
        $appraisals->rating = 1;
        $appraisals->rates = $rates_json;
        $appraisals->date = date('Y-m-d');
        $appraisals->save();
    }
}
