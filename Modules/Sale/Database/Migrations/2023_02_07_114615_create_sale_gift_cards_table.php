<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaleGiftCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_gift_cards', function (Blueprint $table) {
            $table->id();
            $table->string('card_no')->nullable();
            $table->double('amount')->nullable();
            $table->double('expense')->nullable();
            $table->integer('customer_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->date('expired_date')->nullable();
            $table->boolean('is_active')->nullable();
            $table->foreignId('created_by')->index('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->index('updated_by')->nullable()->constrained('users');
            $table->timestamps();
        });

          if (config('app.APP_CRM')){

            $card_no = ['ABC123', 'ABC456', 'ABC789','ABC101112', 'ABC131415', 'ABC161718', 'ABC192021', 'ABC222324', 'ABC252627']; 

            foreach ($card_no as $card) {
                DB::table('sale_gift_cards')->insert([
                    'card_no' => $card,
                    'amount' => 100,
                    'expense' => 0,
                    'customer_id' => 1,
                    'user_id' => 1,
                    'expired_date' => now()->addDays(30),
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
        Schema::dropIfExists('sale_gift_cards');
    }
}
