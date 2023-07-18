<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('conversations')->insert([
            'sender_id' => 1,
            'receiver_id' => 3,
            'title' => 'New Message',
            'message' => 'Thank you for joining',
            'image_id' => null,
        ]);

        DB::table('conversations')->insert([
            'sender_id' => 1,
            'receiver_id' => 6,
            'title' => 'New Message',
            'message' => 'Thank you for joining',
            'image_id' => null,
        ]);

        DB::table('notifications')->insert([
            'sender_id' => 1,
            'receiver_id' => 3,
            'title' => 'New Notification',
            'message' => 'Thank you for joining',
            'image_id' => null,
        ]);

        DB::table('notifications')->insert([
            'sender_id' => 1,
            'receiver_id' => 6,
            'title' => 'New Notification',
            'message' => 'Thank you for joining',
            'image_id' => null,
            'type' => 'notification',
        ]);
    }
}
