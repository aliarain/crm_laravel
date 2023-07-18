<?php

namespace Database\Seeders\Management;

use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // client
        $clients = [
            [
                'name' => 'Arun Kumar',
                'email' => 'arun@crm.com',
                'phone' => '1234567891',
                'address' => '123 Main St1',
                'city' => 'New York1',
                'state' => 'NY1',
                'zip' => '100011',
                'country' => 'USA1',
                'website' => 'www.john1.com',
                'status_id' => 1,
                'company_id' => 2,
                'avatar_id' => rand(37, 56),
            
            ],
            [
                'name' => 'Ebrahim',
                'email' => 'ebrahim@crm.com',
                'phone' => '12345678912',
                'address' => '123 Main St1',
                'city' => 'New York1',
                'state' => 'NY1',
                'zip' => '100011',
                'country' => 'USA1',
                'website' => 'www.john1.com',
                'status_id' => 1,
                'company_id' => 2,
                'avatar_id' => rand(37, 56),
            
            ],
            [
                'name' => 'Nithin',
                'email' => 'nithin@crm.com',
                'phone' => '12345678913',
                'address' => '123 Main St1',
                'city' => 'New York1',
                'state' => 'NY1',
                'zip' => '100011',
                'country' => 'USA1',
                'website' => 'www.john1.com',
                'status_id' => 1,
                'company_id' => 2,
                'avatar_id' => rand(37, 56),
            
            ],
            [
                'name' => 'Rajesh',
                'email' => 'rajesh@crm.com',
                'phone' => '12345678914',
                'address' => '123 Main St1',
                'city' => 'New York1',
                'state' => 'NY1',
                'zip' => '100011',
                'country' => 'USA1',
                'website' => 'www.john1.com',
                'status_id' => 1,
                'company_id' => 2,
                'avatar_id' => rand(37, 56),
            
            ],
            [
                'name' => 'Viraj Kumar',
                'email' => 'viraj@crm.com',
                'phone' => '12345678915',
                'address' => '123 Main St1',
                'city' => 'New York1',
                'state' => 'NY1',
                'zip' => '100011',
                'country' => 'USA1',
                'website' => 'www.john1.com',
                'status_id' => 1,
                'company_id' => 2,
                'avatar_id' => rand(37, 56),
            
            ],
            [
                'name' => 'Eng Khaled',
                'email' => 'khaled@crm.com',
                'phone' => '12345678916',
                'address' => '123 Main St1',
                'city' => 'New York1',
                'state' => 'NY1',
                'zip' => '100011',
                'country' => 'USA1',
                'website' => 'www.john1.com',
                'status_id' => 1,
                'company_id' => 2,
                'avatar_id' => rand(37, 56),
            
            ],
            [
                'name' => 'Mark Nicolau',
                'email' => 'mark@crm.com',
                'phone' => '12345678917',
                'address' => '123 Main St1',
                'city' => 'New York1',
                'state' => 'NY1',
                'zip' => '100011',
                'country' => 'USA1',
                'website' => 'www.john1.com',
                'status_id' => 1,
                'company_id' => 2,
                'avatar_id' => rand(37, 56),
            
            ],
            [
                'name' => 'Harriben',
                'email' => 'harriben@crm.com',
                'phone' => '12345678918',
                'address' => '123 Main St1',
                'city' => 'New York1',
                'state' => 'NY1',
                'zip' => '100011',
                'country' => 'USA1',
                'website' => 'www.john1.com',
                'status_id' => 1,
                'company_id' => 2,
                'avatar_id' => rand(37, 56),
            
            ],
            [
                'name' => 'Muhammad Irfan',
                'email' => 'irfan@crm.com',
                'phone' => '12345678919',
                'address' => '123 Main St1',
                'city' => 'New York1',
                'state' => 'NY1',
                'zip' => '100011',
                'country' => 'USA1',
                'website' => 'www.john1.com',
                'status_id' => 1,
                'company_id' => 2,
                'avatar_id' => rand(37, 56),
            
            ],
            [
                'name' => 'Prakash',
                'email' => 'prakash@crm.com',
                'phone' => '123456789',
                'address' => '123 Main St1',
                'city' => 'New York1',
                'state' => 'NY1',
                'zip' => '100011',
                'country' => 'USA1',
                'website' => 'www.john1.com',
                'status_id' => 1,
                'company_id' => 2,
                'avatar_id' => rand(37, 56),
            
            ]
        ];
      
        foreach($clients as $client){
            \App\Models\Management\Client::create($client);

        }
    }
}
