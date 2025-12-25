<?php

namespace Database\Seeders;

use App\Models\Collaboration\Comment;
use App\Models\Collaboration\Company;
use App\Models\Collaboration\File;
use App\Models\Project\Project;
use App\Models\Project\Task;
use App\Models\User;
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
            $user->userCompany()->associate($companies->random());
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

    }
}
