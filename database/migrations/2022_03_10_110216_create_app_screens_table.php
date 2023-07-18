<?php

use Illuminate\Support\Facades\Schema;
use App\Models\Hrm\AppSetting\AppScreen;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppScreensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_screens', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('slug')->nullable();
            $table->integer('position')->nullable();
            $table->string('icon')->nullable();
            $table->foreignId('status_id')->constrained('statuses');
            $table->string('lavel')->nullable();
            $table->timestamps();
        });

        $menus = [
            'Clients', 
            'Employees',
            // 'Sales', 'Stock',
            // 'Income', 'Accounts',
            'Projects', 
            'Tasks',
            // 'Reports',
            'Notice',
            'Phonebook', 
            'Meeting',
            'Attendance', 
            'Leave',
            'Visit', 
            'Support',
        ];
        $path = "public/assets/app/ScreenIcons/";
        foreach ($menus as $key => $menu) {
            $iconName = $menu . '.png';
            AppScreen::create([
                'name' => $menu,
                'slug' => $menu,
                'position' => $key + 1,
                'icon' => $path . '' . $iconName,
                'status_id' => 1,
                'lavel' => $key % 2 == 0 ? '' : 'PRO',
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
        Schema::dropIfExists('app_screens');
    }
}
