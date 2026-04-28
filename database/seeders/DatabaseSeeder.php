<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Course;
use App\Models\Student;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create courses
        $courses = [
            [
                'course_name' => 'Web Development',
                'description' => 'Learn HTML, CSS, JavaScript, and Laravel',
                'duration' => 40,
            ],
            [
                'course_name' => 'Mobile Development',
                'description' => 'Learn React Native and Flutter',
                'duration' => 35,
            ],
            [
                'course_name' => 'Data Science',
                'description' => 'Learn Python, Machine Learning, and Data Analysis',
                'duration' => 50,
            ],
            [
                'course_name' => 'Cloud Computing',
                'description' => 'Learn AWS, Azure, and Google Cloud',
                'duration' => 30,
            ],
            [
                'course_name' => 'Cybersecurity',
                'description' => 'Learn network security and ethical hacking',
                'duration' => 45,
            ],
        ];

        foreach ($courses as $course) {
            Course::create($course);
        }

        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create CEO user
        User::create([
            'name' => 'CEO User',
            'email' => 'ceo@example.com',
            'password' => Hash::make('password'),
            'role' => 'ceo',
        ]);

        // Create staff user
        User::create([
            'name' => 'Staff User',
            'email' => 'staff@example.com',
            'password' => Hash::make('password'),
            'role' => 'staff',
        ]);

        // Create sample students
        $students = [
            [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'course_id' => 1,
                'marks' => 85,
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane@example.com',
                'course_id' => 1,
                'marks' => 92,
            ],
            [
                'name' => 'Mike Johnson',
                'email' => 'mike@example.com',
                'course_id' => 2,
                'marks' => 78,
            ],
            [
                'name' => 'Sarah Williams',
                'email' => 'sarah@example.com',
                'course_id' => 3,
                'marks' => 88,
            ],
            [
                'name' => 'Tom Brown',
                'email' => 'tom@example.com',
                'course_id' => 4,
                'marks' => 95,
            ],
            [
                'name' => 'Emily Davis',
                'email' => 'emily@example.com',
                'course_id' => 5,
                'marks' => 82,
            ],
            [
                'name' => 'David Miller',
                'email' => 'david@example.com',
                'course_id' => 1,
                'marks' => 76,
            ],
            [
                'name' => 'Lisa Anderson',
                'email' => 'lisa@example.com',
                'course_id' => 2,
                'marks' => 89,
            ],
            [
                'name' => 'Robert Taylor',
                'email' => 'robert@example.com',
                'course_id' => 3,
                'marks' => 65,
            ],
            [
                'name' => 'Jennifer White',
                'email' => 'jennifer@example.com',
                'course_id' => 4,
                'marks' => 72,
            ],
            [
                'name' => 'James Harris',
                'email' => 'james@example.com',
                'course_id' => 5,
                'marks' => 35,
            ],
            [
                'name' => 'Patricia Martin',
                'email' => 'patricia@example.com',
                'course_id' => 1,
                'marks' => 58,
            ],
        ];

        foreach ($students as $student) {
            Student::create($student);
        }
    }
}
