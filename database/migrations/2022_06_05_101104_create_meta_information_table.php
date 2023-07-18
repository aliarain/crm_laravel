<?php

use App\Models\MetaInformation;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMetaInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meta_information', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('type', [
                'all_shop',
                'all_brand',
                'all_search',
                'login',
                'registration',
                'student_registration',
                'affiliate_registration',
                'be_a_seller',
                'compare_list',
                'add_to_cart',
                'about_us',
                'faqs',
                'contact_us',
                'careers',
                'return_refund',
                'support_policy',
                'privacy_policy',
                'terms_condition',
            ])->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('meta_image')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();

            // database table index create
            $table->index(['id', 'type']);
        });

        $data = [
            'all_shop',
            'all_brand',
            'all_search',
            'login',
            'registration',
            'student_registration',
            'affiliate_registration',
            'be_a_seller',
            'compare_list',
            'add_to_cart',
            'about_us',
            'faqs',
            'contact_us',
            'careers',
        ];

        foreach ($data as $value) {
            MetaInformation::create([
                'type' => $value,
                'meta_title' => $value . '-title',
                'meta_description' => $value . '-description',
                'meta_keywords' => $value . '-keywors',
                'meta_image' => $value . '-image',
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('meta_information');
    }
}
