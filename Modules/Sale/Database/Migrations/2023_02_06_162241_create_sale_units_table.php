<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaleUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_units', function (Blueprint $table) {
            $table->id();
            $table->string('unit_code');
            $table->string('unit_name');
            $table->integer('base_unit')->nullable(); 
            $table->string('operator')->nullable();
            $table->double('operation_value')->nullable();
            $table->boolean('is_active')->nullable();
            $table->foreignId('created_by')->index('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->index('updated_by')->nullable()->constrained('users');
            $table->timestamps();
        });

        // Sale Units Seeding Start
        if (config('app.APP_CRM')){
            
            $units = 
                [
                    [
                        'unit_name' => "Piece",
                        'unit_code' => "Single"
                    ],
                    [
                        'unit_name' => "pair",
                        'unit_code' => "pair"
                    ],
                    [
                        'unit_name' => "dozen",
                        'unit_code' => "dozen"
                    ],
                    [
                        'unit_name' => "Grand",
                        'unit_code' => "Grand"
                    ],
                    [
                        'unit_name' => "Myriad",
                        'unit_code' => "Myriad"
                    ],
                    [
                        'unit_name' => "millimeter",
                        'unit_code' => "mm"
                    ], 
                    [
                        'unit_name' => "centimeter",
                        'unit_code' => "cm"
                    ], 
                    [
                        'unit_name' => "meter",
                        'unit_code' => "m"
                    ], 
                    [
                        'unit_name' => "inch",
                        'unit_code' => "inm"
                    ], 
                    [
                        'unit_name' => "foot",
                        'unit_code' => "ft"
                    ],
                    [
                        'unit_name' => "milligram",
                        'unit_code' => "mg"
                    ],
                    [
                        'unit_name' => "gram",
                        'unit_code' => "g"
                    ],
                    [
                        'unit_name' => "kilogram",
                        'unit_code' => "kg"
                    ],
                    [
                        'unit_name' => "ounce",
                        'unit_code' => "oz"
                    ],
                    [
                        'unit_name' => "pound",
                        'unit_code' => "lb"
                    ],
                    [
                        'unit_name' => "milliliter",
                        'unit_code' => "ml"
                    ],
                    [
                        'unit_name' => "liter",
                        'unit_code' => "L"
                    ],
                    [
                        'unit_name' => "gallon",
                        'unit_code' => "gal"
                    ],
                    [
                        'unit_name' => "pc",
                        'unit_code' => "pc"
                    ],
                ];

            $data = [];
            foreach($units as $unit){
                $data[] = [
                'unit_code' => $unit['unit_code'],
                'unit_name' => $unit['unit_name'],
                'base_unit' => null,
                'operator' => "*",
                'operation_value' => 1,
                'is_active' => 1, 
                ];
            }

            DB::table('sale_units')->insert($data);
        }
        // Sale Unit Seeding End

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sale_units');
    }
}
