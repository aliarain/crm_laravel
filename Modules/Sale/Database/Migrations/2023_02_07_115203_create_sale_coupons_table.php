<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaleCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->string('type')->nullable();
            $table->double('amount')->nullable();
            $table->double('minimum_amount')->nullable();
            $table->integer('quantity')->nullable();
            $table->integer('used')->nullable();
            $table->date('expired_date')->nullable();
            $table->integer('user_id')->nullable();
            $table->boolean('is_active')->nullable();
            $table->foreignId('created_by')->index('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->index('updated_by')->nullable()->constrained('users');
            $table->timestamps();
        });

        // env app sync check
          if (config('app.APP_CRM')){
            $codes = ['ABC123', 'ABC456', 'ABC789','ABC101112', 'ABC131415', 'ABC161718', 'ABC192021', 'ABC222324', 'ABC252627']; 
            
            foreach ($codes as $code) {
                DB::table('sale_coupons')->insert([
                    'code' => $code,
                    'type' => 'fixed',
                    'amount' => 10,
                    'minimum_amount' => 100,
                    'quantity' => 10,
                    'used' => 0,
                    'expired_date' => now()->addDays(30),
                    'user_id' => 1,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(), 
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sale_coupons');
    }
}
