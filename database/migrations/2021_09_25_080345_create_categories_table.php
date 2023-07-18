<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('name');
            $table->tinyInteger('type')->comment('1=income 2=expense');
            $table->string('serial')->comment('serial');
            $table->string('description')->nullable()->comment('description');
            $table->foreignId('status_id')->index()->nullable()->constrained('statuses')->cascadeOnDelete()->comment('Status of the day');
            $table->foreignId('author_info_id')->index()->nullable()->constrained('author_infos')->cascadeOnDelete()->comment('Author info of the day');
            $table->softDeletes();
            $table->timestamps();

            // index 
            $table->index(['name', 'type','serial']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
}
