<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebToLeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('web_to_leads', function (Blueprint $table) {
            $table->id();
            $table->string('form_key', 32);
            $table->integer('lead_source');
            $table->integer('lead_status');
            $table->integer('notify_lead_imported')->default(1);
            $table->string('notify_type', 20)->nullable();
            $table->mediumText('notify_ids')->nullable();
            $table->integer('responsible')->default(0);
            $table->string('name', 191);
            $table->mediumText('form_data')->nullable();
            $table->integer('recaptcha')->default(0);
            $table->string('submit_btn_name', 40)->nullable();
            $table->string('submit_btn_text_color', 10)->default('#ffffff');
            $table->string('submit_btn_bg_color', 10)->default('#84c529');
            $table->text('success_submit_msg')->nullable();
            $table->integer('submit_action')->default(0);
            $table->string('lead_name_prefix', 255)->nullable();
            $table->mediumText('submit_redirect_url')->nullable();
            $table->string('language', 40)->nullable();
            $table->integer('allow_duplicate')->default(1);
            $table->integer('mark_public')->default(0);
            $table->string('track_duplicate_field', 20)->nullable();
            $table->string('track_duplicate_field_and', 20)->nullable();
            $table->integer('create_task_on_duplicate')->default(0);
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
        Schema::dropIfExists('web_to_leads');
    }
}
