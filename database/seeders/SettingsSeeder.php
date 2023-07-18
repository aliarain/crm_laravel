<?php

namespace Database\Seeders;

use App\Models\coreApp\Setting\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $array = [
            'company_name',
            'dark_logo',
            'white_logo',
            'company_icon',
            'android_url',
            'android_icon',
            'ios_url',
            'ios_icon',
            'language',
            'emailSettingsProvider',
            'emailSettings_from_name',
            'emailSettings_from_email',
            'site_under_maintenance',
            'company_description',
            'copy_right_text',
        ];

        $values = [
            'One Stop CRM',
            'public/assets/images/dark.png', //dark_logo
            'public/assets/images/white.png', //white_logo
            'public/assets/images/favicon.png', //company_icon
            '#',
            'public/assets/images/favicon.png',
            '#',
            'public/assets/images/favicon.png',
            'en',
            'smtp',
            'crm@onest.com',
            'crm@onest.com',
            '0',
            'We believes in painting the perfect picture of your idea while maintaining industry standards.',
            '2023 One Stop CRM. All rights reserved.',
        ];
        foreach ($array as $key => $item) {
            Setting::create([
                'name' => $item,
                'value' => $values[$key],
                'context' => 'app',
                'company_id' => 1,
            ]);
        }
    }
}
