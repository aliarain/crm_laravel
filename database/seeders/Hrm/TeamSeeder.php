<?php

namespace Database\Seeders\Hrm;

use App\Models\Hrm\Team\Team;
use App\Models\Company\Company;
use App\Models\Hrm\Team\TeamMember;
use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $companis = Company::all();
        foreach ($companis as $company) {
            $departments = $company->departments;
            foreach ($departments as $department) {
                $team_members = $department->users;

                $team = new Team();
                $team->name = $department->title;
                $team->company_id = $company->id;
                $team->status_id = 1;
                $team->user_id = $company->user->id;
                $team->team_lead_id = $company->user->id;
                $team->save();

                foreach ($team_members as $team_member) {
                    $team_members = TeamMember::create([
                        'team_id' => $team->id,
                        'user_id' => $team_member->id,
                        'expire_date' => null,
                    ]);
                }
            }
        }
    }
}
