<?php

namespace Database\Seeders;

use App\Models\Feature;
use Illuminate\Database\Seeder;

class FeatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      
    $list = [
        [
            'company_growth',
            'Employee-Centric',
            'Make the lives of your employees less problematic. Try to create an atmosphere where your employees feel like giving their best every day. You can only expect more work efficiency if you are able to keep your employees happier.'
        ],
        [
            'company_growth',
            'Development-Centric',
            'To meet your business demands, it is very crucial to meet current and future growth requirements. For fulfilling them, employees’ development is a must. Through your agile strategies and planning it out beforehand can be helpful to reach your goals.'
        ],
        [
            'company_growth',
            'Individual Progress',
            'Having the ability to develop individual relationships with the employees can be beneficial for any company. You can easily get to know their general behavior, social aspects of life, emotional well- being and act upon it to improve employee experience.'
        ],
        [
            'company_growth',
            'Decision Making',
            'It is very essential to know how to use data rather than just collecting them. Crunching data after getting helpful information can make an impact on decision-making. Easily dive into future possibilities, also analyze potential outcomes beforehand.'
        ],
        [
            'company_growth',
            'Continuity',
            'It may occur to anybody, even the HR management people can get sick. Keeping constant workflow and overcome such disruptions, it is vital to get notified earlier or get to know employees’ health condition, effectiveness, feelings towards their job.'
        ],
        [
            'company_growth',
            'Universal',
            'Universality is the most vital feature for HRM software. It really doesn’t matter if you are running only a two-person job or a company of 500+ employees, this software is applicable for any. It is truly reliable for any type of organization.'
        ],
        [
            'advance_features',
            'Leave',
            'Employees can express their Leave Type, Find Assigned Leaves and get Leave Request approval. They can also submit necessary documents to ensure the validity of their leave.'
        ],
        [
            'advance_features',
            'Attendance',
            'Records employees’ In /Out time, Working hours, Overtime automatically in its system. Whether they are working from home or office, their activities can be easily traceable to authority.'
        ],
        [
            'advance_features',
            'Expense',
            'For any additional expenses, managing legal claims or keeping track on payment history can be easily done in few clicks. You can also Keep an updated routine for any additional disbursement.'
        ],
        [
            'advance_features',
            'Visit',
            'For outdoor visits or participating in crucial meetings, employees can input their check in/out timings too. Also such visits can be monitored by the officials anytime of the day.'
        ],
        [
            'advance_features',
            'Notice',
            'Let everyone aware of any upcoming events, disciplinary, holidays at once. You can also update any notice for individuals, departmental wise or even for all without any effort.'
        ],
        [
            'advance_features',
            'Report',
            'Collects data of individuals -Working days/On time/Late Comings/Early Leave/Overtime and creates monthly/half-yearly, annual report based on their regular performance.'
        ],
        [
            'awesome_features',
            'Employee Data',
            'Records everything that indicates all necessary information for any of the employees.'
        ],
        [
            'awesome_features',
            'Custom Permission',
            'Provide accessibility to the designated personnel for further analysis of any individual.'
        ],
        [
            'awesome_features',
            'Employee Onboarding',
            'Onboard employees online and make a remarkable first impression during the process.'
        ],
        [
            'awesome_features',
            'Announcement',
            'Celebrate special moments with everyone in the company with a few words.'
        ],
        [
            'awesome_features',
            'Custom Profile',
            'You can also get to customize your own profile as you may seem right for the company.'
        ],
        [
            'awesome_features',
            'Project & Tasks',
            'Allows transparent access to overview employee’s assigned tasks for daily reports.'
        ]
    ];

    foreach( $list as $l){
         
        $feature = new Feature();
        $feature->type = $l[0];
        $feature->title =$l[1];
        $feature->short_description =$l[2];
        $feature->long_description =$l[2];
        $feature->save();
    }

    }
}
