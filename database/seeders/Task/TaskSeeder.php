<?php

namespace Database\Seeders\Task;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\TaskManagement\Task;
use App\Models\TaskManagement\TaskFile;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $task                           = new Task();
        $task->name                     = 'eCommerce task';
        $task->date                     = date('Y-m-d');
        $task->progress                 = 1;
        $task->status_id                = 26;
        $task->priority                 = 29;
        $task->description              = '<strong>Lorem Ipsum</strong>&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#39;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.';
        $task->start_date               = date('Y-m-d');
        $task->end_date                 = date("Y-m-d", strtotime("+1 month", strtotime($task->start_date)));
        $task->notify_all_users         = 0;
        $task->notify_all_users_email   = 0;
        $task->company_id               = 2;
        $task->created_by               = 2;
        $task->save();

        //team members assign to task
        DB::table('task_members')->insert([
            'task_id' => $task->id,
            'company_id' => 2,
            'user_id' => 4,
            'added_by' => 2,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        // task discussions

        DB::table('task_discussions')->insert([
            'task_id' => $task->id,
            'company_id' => 2,
            'user_id' => 4,
            'subject' => 'Discussion 1',
            'description' => '<strong>Lorem Ipsum</strong>&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#39;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'show_to_customer' => 33,
            'last_activity' => date('Y-m-d H:i:s')
        ]);

        // discussions comments

        DB::table('task_discussion_comments')->insert([
            'task_discussion_id' => 1,
            'company_id' => 2,
            'user_id' => 4,
            'description' => '<strong>Lorem Ipsum</strong>&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#39;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'show_to_customer' => 33
        ]);

        DB::table('task_discussion_comments')->insert([
            'task_discussion_id' => 1,
            'company_id' => 2,
            'comment_id' => 1,
            'user_id' => 3,
            'description' => '<strong>Lorem Ipsum</strong>&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#39;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'show_to_customer' => 33
        ]);


        // task files
        $task_files = new TaskFile();
        $task_files->company_id = 2;
        $task_files->task_id = $task->id;
        $task_files->subject = 'Demo File';
        $task_files->user_id = 3;
        $task_files->show_to_customer = 22;
        $task_files->last_activity = date('Y-m-d H:i:s');
        $task_files->save();

        // task comments
        DB::table('task_file_comments')->insert([
            'task_file_id' => 1,
            'company_id' => 2,
            'user_id' => 4,
            'description' => '<strong>Lorem Ipsum</strong>&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#39;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'show_to_customer' => 33
        ]);

        DB::table('task_file_comments')->insert([
            'task_file_id' => 1,
            'company_id' => 2,
            'comment_id' => 1,
            'user_id' => 3,
            'description' => '<strong>Lorem Ipsum</strong>&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#39;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'show_to_customer' => 33
        ]);

        // end task file
    }
}
