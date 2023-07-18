<?php

use App\Models\StockCategory;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_categories', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            
            $table->string('name', 255)->nullable();
            $table->string('slug', 255)->nullable();
            $table->foreignId('status_id')->index()->nullable()->constrained('statuses')->cascadeOnDelete()->comment('Status of the day');
            $table->foreignId('author_info_id')->index()->nullable()->constrained('author_infos')->cascadeOnDelete()->comment('Author info of the day');
            $table->foreignId('avatar_id')->nullable()->constrained('uploads');

            $table->softDeletes();
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
        Schema::dropIfExists('stock_categories');
    }
}
