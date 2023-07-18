<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use App\Models\Settings\HrmLanguage;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use App\Models\coreApp\Setting\Setting;
use Illuminate\Support\ServiceProvider;
use App\Models\Hrm\Attendance\Attendance;
use App\Repositories\Team\TeamRepository;
use App\Helpers\CoreApp\Traits\DateHandler;
use App\Repositories\Interfaces\TeamInterface;
use App\Helpers\CoreApp\Traits\GeoLocationTrait;
use App\Helpers\CoreApp\Traits\TimeDurationTrait;
use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;
use App\Models\coreApp\Relationship\RelationshipTrait;
use App\Repositories\Hrm\Attendance\AttendanceRepository;


class AppServiceProvider extends ServiceProvider
{
    use ApiReturnFormatTrait, RelationshipTrait, TimeDurationTrait, GeoLocationTrait, DateHandler;

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            \App\Repositories\Interfaces\TeamInterface::class,
            \App\Repositories\Team\TeamRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        try {
            DB::connection()->getPdo();
            if (Schema::hasTable('settings')) {
                $settings = Setting::get()->pluck('value', 'name');
                foreach ($settings as $key => $value) {
                    config()->set("settings.app.{$key}", $value);
                }
            }

            //app singleton
            $this->app->singleton('settings', function () {
                return Setting::get()->pluck('value', 'name');
            });
            $this->app->singleton('hrm_languages', function () {
                return HrmLanguage::with('language')->where('status_id',1)->get();
            });

            if(env('APP_HTTPS')==true){
                URL::forceScheme('https');
           }
        } catch (\Exception $e) {
        }


    }
}

