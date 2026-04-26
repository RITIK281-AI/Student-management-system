# Student Management System

A full-stack web application built with Laravel for managing students and courses with role-based access control.

## Features

- **Authentication**: User registration (Staff only) and login
- **Role-Based Access Control**: Staff, Admin, and CEO roles
- **Student Management**: CRUD operations for Staff
- **Course Management**: View and assign courses
- **Search & Filter**: Find students by name or course
- **Pagination**: Navigate through student lists
- **Responsive UI**: Clean HTML/CSS interface with JavaScript interactions

## Roles

- **Staff**: Full CRUD access to students and courses
- **Admin**: Read-only access to all data
- **CEO**: Read-only access to all data

## Installation

1. Clone the repository
2. Run `composer install`
3. Copy `.env.example` to `.env`
4. Run `php artisan key:generate`
5. Configure database in `.env`
6. Run `php artisan migrate --seed`
7. Run `php artisan serve`

## Default Credentials

- **Admin**: admin@example.com / password
- **CEO**: ceo@example.com / password
- **Staff**: Can register via registration page

## Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── AuthController.php
│   │   ├── StudentController.php
│   │   └── CourseController.php
│   └── Middleware/
│       ├── CheckRole.php
│       └── CheckStaffRole.php
├── Models/
│   ├── User.php
│   ├── Student.php
│   └── Course.php
database/
├── migrations/
│   ├── create_users_table.php
│   ├── create_courses_table.php
│   └── create_students_table.php
└── seeders/
    └── DatabaseSeeder.php
resources/
└── views/
    ├── auth/
    │   ├── login.blade.php
    │   └── register.blade.php
    ├── layouts/
    │   └── app.blade.php
    ├── dashboards/
    │   ├── staff.blade.php
    │   ├── admin.blade.php
    │   └── ceo.blade.php
    └── students/
        ├── index.blade.php
        ├── create.blade.php
        └── edit.blade.php
routes/
└── web.php
```
