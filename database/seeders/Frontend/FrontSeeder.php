<?php

namespace Database\Seeders\Frontend;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FrontSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // services
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        DB::table('services')->insert([
            [
                'title'             => 'Web Design',
                'slug'              => 'web-design',
                'description'       => 'Our developers are always updated with the latest HTML5 Website, Encoding transcoding, User management CRM, CDN, DRM',
                'user_id'           => 2,
                'created_at'        => Carbon::now()
            ],
            [
                'title'             => 'Web Development',
                'slug'              => 'web-development',
                'description'       => 'We also provide Enterprise Web Applications Development, Cross-platform Apps, HRM,CRM, Multivendor E-commerce etc',
                'user_id'           => 2,
                'created_at'        => Carbon::now()
            ],
            [
                'title'             => 'Graphics Design',
                'slug'              => 'graphics-design',
                'description'       => 'A Graphic Designer is an artist who creates visual text and imagery. They design creative content for online campaigns, print ads, websites, and even videos.',
                'user_id'           => 2,
                'created_at'        => Carbon::now()
            ],
            [
                'title'             => 'Digital Marketing',
                'slug'              => 'digital-marketing',
                'description'       => 'The objective of digital marketing is to develop strong and innovative strategies to promote the business brand, products, and services. A digital marketing professional is expected to effectively use all marketing tools and techniques like PPC, SEO, SEM, email, social media, and display advertising',
                'user_id'           => 2,
                'created_at'        => Carbon::now()
            ],
            [
                'title'             => 'Domain',
                'slug'              => 'domain',
                'description'       => 'Most competitive price. Huge Choice & New Extension. Register your perfect domain name today.',
                'user_id'           => 2,
                'created_at'        => Carbon::now()
            ],
            [
                'title'             => 'Hosting',
                'slug'              => 'hosting',
                'description'       => 'Web hosting, a service that hosts websites for clients and makes a website accessible on world wide web. We provide essential techniques and services for any website.',
                'user_id'           => 2,
                'created_at'        => Carbon::now()
            ],

        ]);

        // portfolios

        DB::table('portfolios')->insert([
            [
                'title'             => 'web & software dev',
                'slug'              => 'web-software-dev',
                'description'       => 'We also provide Enterprise Web Applications Development, Cross-platform Apps, HRM,CRM, Multivendor E-commerce etc',
                'user_id'           => 2,
                'created_at'        => Carbon::now(),
                'attachment'        => 21
            ],
            [
                'title'             => 'UX UI designers',
                'slug'              => 'ux-ui-designers',
                'description'       => 'We also provide Most trending, Eye catching UI for our clients.',
                'user_id'           => 2,
                'created_at'        => Carbon::now(),
                'attachment'        => 22
            ],
            [
                'title'             => 'project managers',
                'slug'              => 'project-managers',
                'description'       => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore.',
                'user_id'           => 2,
                'created_at'        => Carbon::now(),
                'attachment'        => 23
            ],
            [
                'title'             => 'Java dev',
                'slug'              => 'java-dev',
                'description'       => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore.',
                'user_id'           => 2,
                'created_at'        => Carbon::now(),
                'attachment'        => 24
            ],
            [
                'title'             => 'Mechanical support',
                'slug'              => 'mechanical-support',
                'description'       => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore.',
                'user_id'           => 2,
                'created_at'        => Carbon::now(),
                'attachment'        => 25
            ],
            [
                'title'             => 'Scrum master',
                'slug'              => 'scrum-master',
                'description'       => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore.',
                'user_id'           => 2,
                'created_at'        => Carbon::now(),
                'attachment'        => 26
            ],
            [
                'title'             => 'Finance Experts',
                'slug'              => 'finance-experts',
                'description'       => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore.',
                'user_id'           => 2,
                'created_at'        => Carbon::now(),
                'attachment'        => 27
            ],
            [
                'title'             => 'Ride Share',
                'slug'              => 'ride-share',
                'description'       => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore.',
                'user_id'           => 2,
                'created_at'        => Carbon::now(),
                'attachment'        => 28
            ],
        ]);

        // team members

        DB::table('front_teams')->insert([
            [
                'name'              => 'Ahsan ahmed',
                'designation'       => 'Software engineer',
                'description'       => 'Dedicated,fast forward Software engineer with 4+ years of professional experience.',
                'user_id'           => 2,
                'created_at'        => Carbon::now(),
                'attachment'        => 29
            ],
            [
                'name' => 'Jobbar ali',
                'designation'       => 'Project manager',
                'description'       => 'Dedicated,fast forward Project manager with 5+ years of professional experience.',
                'user_id'           => 2,
                'created_at'        => Carbon::now(),
                'attachment'        => 30
            ],
            [
                'name' => 'Johan evan',
                'designation'       => 'Designer',
                'description'       => 'Dedicated,fast forward  Designer with 3+ years of professional experience.',
                'user_id'           => 2,
                'created_at'        => Carbon::now(),
                'attachment'        => 31
            ],
            [
                'name' => 'Akram khan',
                'designation'       => 'Mechanical Engineer',
                'description'       => 'Dedicated,fast forward Mechanical Engineer with 3+ years of professional experience.',
                'user_id'           => 2,
                'created_at'        => Carbon::now(),
                'attachment'        => 32
            ],
            [
                'name' => 'Ahsan ahmed',
                'designation'       => 'Software engineer',
                'description'       => 'Dedicated,fast forward Software engineer with 7+ years of professional experience.',
                'user_id'           => 2,
                'created_at'        => Carbon::now(),
                'attachment'        => 33
            ],
            [
                'name' => 'Jobbar ali',
                'designation'       => 'Project manager',
                'description'       => 'Dedicated,fast forward Project manager with 3+ years of professional experience.',
                'user_id'           => 2,
                'created_at'        => Carbon::now(),
                'attachment'        => 34
            ],
            [
                'name' => 'Johan evan',
                'designation'       => 'Designer',
                'description'       => 'Dedicated,fast forward Project Designer with 3+ years of professional experience.',
                'user_id'           => 2,
                'created_at'        => Carbon::now(),
                'attachment'        => 35
            ],
            [
                'name' => 'Akram khan',
                'designation'       => 'Mechanical Engineer',
                'description'       => 'Dedicated,fast forward Mechanical Engineer with 5+ years of professional experience.',
                'user_id'           => 2,
                'created_at'        => Carbon::now(),
                'attachment'        => 36
            ],

        ]);

        // menus

        DB::table('menus')->insert([
            [
                'name'  => 'Home',
                'position'  => 1,
                'url'   => url('/'),
                'type'  => 1,
                'created_at'    => Carbon::now()
            ]
        ]);

        
        DB::table('menus')->insert([
            [
                'name' => 'About Us',
                'position' => 2,
                'all_content_id' => 1,
                'type' => 1,
                'created_at' => Carbon::now()
            ],
            [
                'name' => 'Contact Us',
                'position' => 3,
                'all_content_id' => 2,
                'type' => 1,
                'created_at' => Carbon::now()
            ],
            [
                'name' => 'Privacy Policy',
                'position' => 1,
                'all_content_id' => 3,
                'type' => 2,
                'created_at' => Carbon::now()
            ],
            [
                'name' => 'Terms of Use',
                'position' => 2,
                'all_content_id' => 5,
                'type' => 2,
                'created_at' => Carbon::now()
            ]
        ]);

        // home page
        $section1 = json_encode([
            'title' => 'Get Best Innovation Software',
            'description' => 'Integrated market before enterprise wide e-commerce. Competently actualize bleeding-edge testing.',
            'attachment' => url('public/assets/images/user.png')
        ]);
        $section2 = json_encode([
            'title' => 'About Us',
            'slogan' => 'One Goal, One Passion',
            'description' => 'We believes in painting the perfect picture of your idea while maintaining industry standards and following upcoming trends. It is a professional software development company managed by tech-heads, engineers who are highly qualified in creating and solving issues of all kinds.

            This software development company was established in Dhaka, Bangladesh on September 1, 2017 and since then, it has developed a relentless focus on technical achievement both nationally and internationally.
            
            So, you can certainly bet the farm as our expertise uses every muscle to provide dogmatic solutions, that results in best user experience with us.',
            'image' => null,
        ]);
        DB::table('home_pages')->insert([
            [
                'title' => 'Home Section 1',
                'contents' => $section1,
                'created_by' => 2,
                'updated_by' => 2,
                'created_at' => Carbon::now()
            ],
            [
                'title' => 'About Section',
                'contents' => $section2,
                'created_by' => 2,
                'updated_by' => 2,
                'created_at' => Carbon::now()
            ]
        ]);
    }
}
