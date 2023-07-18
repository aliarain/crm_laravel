<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUploadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('uploads', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->string('file_original_name')->nullable();
            $table->string('file_name')->nullable();
            $table->string('img_path', 255)->nullable();
            $table->string('big_path', 255)->nullable()->comment('1920 x 1080');
            $table->string('small_path', 255)->nullable()->comment('300 x 300');
            $table->string('thumbnail_path', 255)->nullable()->comment('500 x 400');

            $table->string('extension', 10)->nullable();
            $table->string('type', 15)->nullable();
            $table->integer('file_size')->nullable();
            $table->integer('width')->nullable();
            $table->integer('height')->nullable();
            $table->foreignId('status_id')->index()->nullable()->constrained('statuses')->cascadeOnDelete()->comment('Status of the day');
            $table->timestamps();
        });




    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('uploads');
    }
}
