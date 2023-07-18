<?php

namespace Database\Seeders\Management;

use App\Models\User;
use Illuminate\Database\Seeder;
use App\Models\Management\Client;
use App\Models\Management\Project;
use Illuminate\Support\Facades\DB;
use App\Models\TaskManagement\Task;
use Illuminate\Support\Facades\Log;
use App\Models\Management\ProjectFile;
use App\Models\TaskManagement\TaskFile;
use App\Models\TaskManagement\TaskMember;
use App\Models\TaskManagement\TaskDiscussion;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

            //project name
            $project_names = [
                'eCommerce',
                'Project Management',
                'CRM',
                'ERP',
                'POS',
                'HRM',
                'Inventory',
                'Accounting',
                'Payroll',
                'Hotel Management',
                'School Management',
                'Hospital Management',
                'Restaurant Management',
                'Real Estate',
                'Construction',
    
            ];
            $project_details = [
                [
                    'name' => 'eCommerce',
                    'description' => 'E-commerce industry has grown significantly in recent years. Specially, the COVID-19 pandemic Bangladesh.',
                    'start_date' => date("Y-m-d", strtotime("-15 days", strtotime(date('Y-m-d')))),
                    'end_date' => date("Y-m-d", strtotime("+45 days", strtotime(date('Y-m-d')))),
                    'amount' => 8500.00,
                    'due' => 4500.00,
                ],
                [
                    'name' => 'Project Management',
                    'description' => 'Project management is the practice of initiating, planning, executing, controlling, and closing the work of a team to achieve specific goals and meet specific success criteria at the specified time. A project is a temporary endeavor designed to produce a unique product, service or result with a defined beginning and end (usually time-constrained, and often constrained by funding or staffing) undertaken to meet unique goals and objectives, typically to bring about beneficial change or added value. The temporary nature of projects stands in contrast with business as usual (or operations), which are repetitive, permanent, or semi-permanent functional activities to produce products or services. In practice, the management of these two systems is often quite different, and as such requires the development of distinct technical skills and management strategies.',
                    'start_date' => date('Y-m-d'),
                    'end_date' => date("Y-m-d", strtotime("+15 days", strtotime(date('Y-m-d')))),
                    'amount' => 8500.00,
                    'due' => 4500.00,
                ],
                [
                    'name' => 'CRM',
                    'description' => 'Customer relationship management (CRM) is an approach to manage a company’s interaction with current and potential customers. It uses data analysis about customers’ history with a company to improve business relationships with customers, specifically focusing on customer retention and ultimately driving sales growth. One important aspect of the CRM approach is the systems of CRM that compile data from a range of different communication channels, including a company’s website, telephone, email, live chat, marketing materials and more.',
                    'start_date' => date('Y-m-d'),
                    'end_date' => date("Y-m-d", strtotime("+15 days", strtotime(date('Y-m-d')))),
                    'amount' => 8500.00,
                    'due' => 4500.00,
                ],
                [
                    'name' => 'ERP',
                    'description' => 'Enterprise resource planning (ERP) is the integrated management of main business processes, often in real-time and mediated by software and technology. ERP is usually referred to as a category of business-management software — typically a suite of integrated applications — that an organization can use to collect, store, manage, and interpret data from these many business activities. ERP software integrates all facets of an operation — including product planning, development, manufacturing, sales and marketing — in a single database, application, and user interface. ERP systems track business resources—cash, raw materials, production capacity—and the status of business commitments: orders, purchase orders, and payroll. The applications that make up the system share data across various departments (e.g., sales, purchasing, inventory, shipping and finance) to facilitate information flow between all business functions inside the boundaries of the organization and manage the connections to outside stakeholders.',
                    'start_date' => date('Y-m-d'),
                    'end_date' => date("Y-m-d", strtotime("+15 days", strtotime(date('Y-m-d')))),
                    'amount' => 8500.00,
                    'due' => 4500.00,
                ],
                [
                    'name' => 'POS',
                    'description' => 'Point of sale (POS) is the time and place where a retail transaction is completed. At the point of sale, the merchant calculates the amount owed by the customer, indicates that amount, may prepare an invoice for the customer (which may be a cash register printout), and indicates the options for the customer to make payment. The merchant then indicates the amount of cash given by the customer, if any, and indicates the change due to the customer, if any. The merchant may also accept payment by credit card, debit card, EFTPOS or other electronic means. The point of sale is also the time and place where a customer makes a payment to the merchant in exchange for goods or after provision of a service. The point of sale is the point at which a retail transaction is completed.',
                    'start_date' => date('Y-m-d'),
                    'end_date' => date("Y-m-d", strtotime("+15 days", strtotime(date('Y-m-d')))),
                    'amount' => 8500.00,
                    'due' => 4500.00,
                ],
                [
                    'name' => 'HRM',
                    'description' => 'Human resource management (HRM) is the strategic approach to the effective management of people in a company or organization such that they help their business gain a competitive advantage. It is designed to maximize employee performance in service of an employer’s strategic objectives. HRM can also be performed by line managers. HRM is primarily concerned with the management of people within organizations, focusing on policies and systems. HRM can be defined as a set of integrated and coherent activities and tasks that are directed at attracting, developing, and maintaining a competent workforce. HRM is a strategic and coherent approach to the management of an organization’s most valued assets – the people working there who individually and collectively contribute to the achievement of the objectives of the business.',
                    'start_date' => date('Y-m-d'),
                    'end_date' => date("Y-m-d", strtotime("+15 days", strtotime(date('Y-m-d')))),
                    'amount' => 8500.00,
                    'due' => 4500.00,
                ],
                [
                    'name' => 'Inventory',
                    'description' => 'Inventory management is the supervision of non-capitalized assets (inventory) and stock items. Inventory management supervises the flow of goods from manufacturers to warehouses and from these facilities to point of sale. Inventory management is a process through which a company is able to oversee the ordering, storing, and delivery of its products. It is a critical component of supply chain management and logistics. Inventory management is the process of ordering, storing, and delivering products from manufacturers to warehouses and from these facilities to point of sale. Inventory management is a process through which a company is able to oversee the ordering, storing, and delivery of its products. It is a critical component of supply chain management and logistics.',
                    'start_date' => date('Y-m-d'),
                    'end_date' => date("Y-m-d", strtotime("+15 days", strtotime(date('Y-m-d')))),
                    'amount' => 8500.00,
                    'due' => 4500.00,
                ],
    
    
            ];
            $clients = Client::get();
            foreach ($clients as $key => $client) {
                foreach ($project_details as $key => $project_info) {
                    $project                           = new Project();
                    $project->name                     = $project_info['name'];
                    $project->client_id                = $client->id;
                    $project->date                     = date('Y-m-d');
                    $project->progress                 = 1;
                    $project->billing_type             = 'hourly';
                    $project->per_rate                 = 15;
                    $project->total_rate               = 0;
                    $project->estimated_hour           = 300;
                    $project->status_id                = 26;
                    $project->priority                 = 29;
                    $project->description              = $project_info['description'];
                    $project->start_date               = $project_info['start_date'];
                    $project->end_date                 = date("Y-m-d", strtotime("+1 month", strtotime($project->start_date)));
                    $project->amount                   = 4500.00;
                    $project->due                      = 4500.00;
                    $project->paid                     = 0;
                    $project->notify_all_users         = 0;
                    $project->notify_all_users_email   = 0;
                    $project->company_id               = 2;
                    $project->created_by               = 2;
                    $project->invoice                  = 1;
                    $project->avatar_id                = rand(57, 66);
                    $project->save();
    
                    //team members assign to project
                    //Project Tasks
                    $tasks_array = [
                        "Requirement Gathering",
                        "Requirement Analysis",
                        "Wireframe Design",
                        "UI/UX Design",
                        "Frontend Development",
                        "Registration",
                        "Authentication",
                        "Role Management",
                        "Backend Development",
                        "API Development",
                        "Testing",
                        "Deployment",
                        "Maintenance"
                    ];
    
                    
                    $employees = User::inRandomOrder()->limit(5)->get();
                    foreach ($employees as $key => $employee) {
                        DB::table('project_membars')->insert([
                            'project_id' => $project->id,
                            'company_id' => 2,
                            'user_id' => $employee->id,
                            'added_by' => 2,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s')
                        ]);
    
                        foreach ($tasks_array as $key => $task_info) {
                            if ($key < 2) {
                                $task = new Task();
                                $task->project_id = $project->id;
                                $task->company_id = 2;
                                $task->client_id = $project->client_id  ;
                                $task->name = $tasks_array[array_rand($tasks_array)];
                                $task->description = $project_info['description'];
                                $task->start_date = date('Y-m-d');
                                $task->end_date = date("Y-m-d", strtotime("+1 month", strtotime($task->start_date)));
                                $task->status_id = 26;
                                $task->priority = 29;
                                $task->progress = 1;
                                $task->type = 1;
                                $task->save();
        
                                //task members assign to task
                                $task_members =new TaskMember();
                                $task_members->task_id = $task->id;
                                $task_members->company_id = 2;
                                $task_members->user_id = $employee->id;
                                $task_members->added_by = 2;
                                $task_members->save();
        
                                //task discussion
                                $task_discussion = new TaskDiscussion();
                                $task_discussion->task_id = $task->id;
                                $task_discussion->company_id = 2;
                                $task_discussion->user_id = $employee->id;
                                $task_discussion->subject = 'A new task has been assigned to you';
                                $task_discussion->description = 'The Competence List table would overlap offscreen when on mobile view. I have added a scroll bar to the table so that the user can scroll to see the rest of the table.';
                                $task_discussion->created_at = date('Y-m-d H:i:s');
                                $task_discussion->updated_at = date('Y-m-d H:i:s');
                                $task_discussion->show_to_customer = 33;
                                $task_discussion->last_activity = date('Y-m-d H:i:s');
                                $task_discussion->save();
        
                                //Task file
                                $task_file = new TaskFile();
                                $task_file->task_id = $task->id;
                                $task_file->company_id = 2;
                                $task_file->user_id = $employee->id;
                                $task_file->show_to_customer = 33;
                                $task_file->subject=$task_info;
                                $task_file->attachment=rand(57, 66);
                                $task_file->save();
                            }
                            
                        }
                    }
    
    
                 
    
    
                    // project files
                    $project_files = new ProjectFile();
                    $project_files->company_id = 2;
                    $project_files->project_id = $project->id;
                    $project_files->subject = 'Demo File';
                    $project_files->user_id = 3;
                    $project_files->attachment = $project->avatar_id;
                    $project_files->show_to_customer = 22;
                    $project_files->last_activity = date('Y-m-d H:i:s');
                    $project_files->save();
    
                    // project comments
                    DB::table('project_file_comments')->insert([
                        'project_file_id' => 1,
                        'company_id' => 2,
                        'user_id' => 4,
                        'description' => 'When editing Competence data, the modal label is “Create Competence” which is incorrect it should be “Edit Competence” when editing Competence data. ',
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                        'show_to_customer' => 33
                    ]);
    
                    DB::table('project_file_comments')->insert([
                        'project_file_id' => 1,
                        'company_id' => 2,
                        'comment_id' => 1,
                        'user_id' => 3,
                        'description' => 'When editing data on mobile view the modal would overlap offscreen. I have added a scroll bar to the modal so that the user can scroll to see the rest of the modal.',
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                        'show_to_customer' => 33
                    ]);
                }
            }
    
    }
}
