<?php

namespace Database\Seeders;

use App\Models\Collaboration\Comment;
use App\Models\Collaboration\Company;
use App\Models\Collaboration\File;
use App\Models\Project\Project;
use App\Models\Project\Task;
use App\Models\User\Permission;
use App\Models\User\Role;
use App\Models\User\User;
use Illuminate\Database\Seeder;

class DevelopmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::factory()
            ->count(10)
            ->create();

        $companies = Company::factory()
        ->count(5)
        ->create([
            'owner_id' => fn() => $users->random()
        ]);


        $users->each(function ($user) use ($companies){
            $user->ownedCompany()->associate($companies->random());
            $user->save();
        });

        $projects = Project::factory()
            ->count(10)
            ->has(
                Comment::factory()
                ->for($users->random(),'author')
                ->count(3)
            )
            ->has(
                File::factory()
                ->count(2)
                ->for($users->random())
            )
            ->create([
                'creator_id' => fn() => $users->random(),
                'company_id' => fn() => $companies->random()
            ]);

        Task::factory()
            ->count(100)
            ->has(
                Comment::factory()
                    ->for($users->random(),'author')
                    ->count(2)
            )
            ->has(
                File::factory()
                    ->count(2)
                    ->for($users->random())
            )
            ->create([
                'project_id' => fn() => $projects->random(),
                'user_id' => fn() => $users->random(),
                'creator_id' => fn() => $users->random()
            ]);

        $entities = collect( ['companies','projecs','tasks','users','files','permissions','roles','comments']);
        $operations = collect(['create','view','update','delete']);
        $entities->map(function ($entity) use ($operations){
            $operations->map(function ($operation)  use ($entity){
                Permission::factory()->create([
                    'name' => $operation.'.'.$entity,
                    'description' => $operation.' in '.$entity,
                    'status' => 1
                ]);
            });
        });

        $roles = collect(
            [
                ['name' => 'super.admin', 'description' => 'A Super Administrator is a user who has complete access to all objects, folders, role templates, and groups in the system. A deployment can have one or more Super Administrators. A Super Administrator can create users, groups, and other super administrators.'],
                ['name' => 'company.owner', 'description' => 'The primary owner of the system or company with full access to all resources and overall control over system configuration and management.'],
                ['name' => 'project.management', 'description' => 'A user responsible for creating and managing projects,tasks,and project members,with addminstraative access limited to their assigned projects'],
                ['name' => 'developer', 'description' => 'An operational user who has access to assigned projects and tasks and is responsible for executing assigned work and contributing to project activities.']
            ]
        );
        $roles->map(function ($role){
            Role::factory()->create([
                'name' => $role['name'],
                'description' => $role['description'],
                'status' => 1
            ]);
        });

    }
}
