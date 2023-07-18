<?php

use App\Models\Content\AllContent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAllContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('all_contents', function (Blueprint $table) {
            $table->id();


            $table->foreignId('company_id')->default(1)->constrained('companies')->cascadeOnDelete();
            $table->foreignId('user_id')->default(1)->constrained('users')->cascadeOnDelete();

            $table->string('type', 50);
            $table->string('title')->unique();
            $table->string('slug')->unique();
            $table->longtext('content');
            $table->text('meta_title');
            $table->string('meta_description', 1000)->nullable();
            $table->string('keywords', 1000)->nullable();
            $table->string('meta_image')->nullable();

            $table->unsignedBigInteger('created_by')->default(1)->nullable();
            $table->unsignedBigInteger('updated_by')->default(1)->nullable();
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');

            $table->foreignId('status_id')->index('status_id')->default(1)->constrained('statuses');


            $table->timestamps();

            // database table index create
            $table->index(['type', 'title', 'slug']);
        });


   
        


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('all_contents');
    }
}
