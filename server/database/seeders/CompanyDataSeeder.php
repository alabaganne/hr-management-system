<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Skill;
use App\Models\Leave;
use App\Models\Training;
use App\Models\Evaluation;
use Carbon\Carbon;

class CompanyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create web development related skills
        $skills = [
            'JavaScript',
            'React',
            'Vue.js',
            'Angular',
            'Node.js',
            'PHP',
            'Laravel',
            'Python',
            'Django',
            'MySQL',
            'PostgreSQL',
            'MongoDB',
            'Git',
            'Docker',
            'AWS',
            'CI/CD',
            'HTML/CSS',
            'TypeScript',
            'REST API',
            'GraphQL',
            'Unit Testing',
            'Agile/Scrum',
            'UI/UX Design',
            'Redux',
            'Webpack',
        ];

        foreach ($skills as $skill) {
            Skill::create(['name' => $skill]);
        }

        // Get all users except the default ones
        $users = User::whereNotIn('email', ['rh@example.com', 'manager@example.com', 'projectmanager@example.com'])->get();

        foreach ($users as $user) {
            // Assign 3-7 random skills to each user with random scores
            $randomSkills = Skill::inRandomOrder()->take(rand(3, 7))->pluck('id');
            foreach ($randomSkills as $skillId) {
                $user->skills()->attach($skillId, [
                    'note' => rand(3, 5), // Score between 3-5 (Good to Excellent)
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Create 1-2 leaves for each user
            $leaveTypes = ['Annual Leave', 'Sick Leave', 'Personal Leave', 'Emergency Leave'];
            $leaveCount = rand(1, 2);
            
            for ($i = 0; $i < $leaveCount; $i++) {
                Leave::create([
                    'user_id' => $user->id,
                    'type' => $leaveTypes[array_rand($leaveTypes)],
                    'days' => rand(1, 5), // 1-5 days of leave
                    'created_at' => Carbon::now()->subMonths(rand(1, 6)),
                    'updated_at' => Carbon::now()->subMonths(rand(1, 6)),
                ]);
            }

            // Create 1-2 trainings for each user
            $trainingCount = rand(1, 2);
            $trainingTopics = [
                'Advanced React Patterns',
                'Microservices Architecture',
                'Cloud Security Best Practices',
                'Agile Project Management',
                'Machine Learning Fundamentals',
                'DevOps Automation',
                'Performance Optimization',
                'Mobile App Development',
                'Data Analytics with Python',
                'Kubernetes Orchestration',
                'API Design Principles',
                'Software Testing Strategies',
                'Leadership in Tech',
                'Database Optimization',
                'Cybersecurity Awareness',
            ];

            for ($i = 0; $i < $trainingCount; $i++) {
                $startDate = Carbon::now()->subMonths(rand(1, 8));
                
                Training::create([
                    'user_id' => $user->id,
                    'entitled' => $trainingTopics[array_rand($trainingTopics)],
                    'start_date' => $startDate,
                    'duration' => rand(1, 5), // 1-5 days duration
                    'note' => rand(3, 5), // Score 3-5
                    'created_at' => $startDate->subDays(rand(7, 14)),
                    'updated_at' => $startDate,
                ]);
            }

            // Create 1-2 evaluations for each user
            $evaluationCount = rand(1, 2);
            $evaluationTypes = ['Annual Review', 'Mid-Year Review', 'Performance Review', 'Project Review'];
            $managers = ['John Smith', 'Emily Johnson', 'Michael Brown', 'Sarah Davis'];
            $statuses = ['Completed', 'In Progress', 'Scheduled'];
            
            for ($i = 0; $i < $evaluationCount; $i++) {
                $evaluationDate = Carbon::now()->subMonths(rand(1, 6));
                
                Evaluation::create([
                    'user_id' => $user->id,
                    'type' => $evaluationTypes[array_rand($evaluationTypes)],
                    'manager' => $managers[array_rand($managers)],
                    'date' => $evaluationDate,
                    'status' => $statuses[array_rand($statuses)],
                    'created_at' => $evaluationDate->subDays(rand(7, 14)),
                    'updated_at' => $evaluationDate,
                ]);
            }
        }
    }

}