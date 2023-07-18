<?php

namespace Database\Seeders;

use App\Models\Notification\NotificationType as NotificationNotificationType;
use Illuminate\Database\Seeder;

class NotificationTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'title' => 'Leave Request',
                'description' => 'Your Leave Request has been sent',
                'type' => 'leave_request',
            ],
            [
                'title' => 'Leave Approved',
                'description' => 'Your Leave Application has been approved',
                'type' => 'leave_approved',
            ],
            [
                'title' => 'Leave Rejected',
                'description' => 'Your Leave Application has been Rejected',
                'type' => 'leave_rejected',
            ],
            [
                'title' => 'Leave Cancelled',
                'description' => 'Your Leave Application has been Cancelled',
                'type' => 'leave_cancelled',
            ],
            [
                'title' => 'Leave Referred',
                'description' => 'Your Leave Application has been Referred ',
                'type' => 'leave_referred',
            ],
            [
                'title' => 'Notice',
                'description' => 'Notice ',
                'type' => 'notice',
            ],
        ];

        foreach ($data as $key => $value) {
            NotificationNotificationType::create($value);
        }
    }
}
