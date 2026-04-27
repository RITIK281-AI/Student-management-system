# Student Management System - Setup Guide

## Prerequisites

- PHP 8.1 or higher
- Composer
- MySQL or MariaDB
- Node.js and npm (optional, for frontend assets)

## Installation Steps

### 1. Clone or Extract the Project

```bash
cd student-management-system
```

### 2. Install Dependencies

```bash
composer install
```

### 3. Environment Configuration

Copy the example environment file:

```bash
cp .env.example .env
```

Edit `.env` and configure your database:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=student_management
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 4. Generate Application Key

```bash
php artisan key:generate
```

### 5. Create Database

Create a new MySQL database:

```bash
mysql -u root -p
CREATE DATABASE student_management;
EXIT;
```

### 6. Run Migrations

```bash
php artisan migrate
```

### 7. Seed the Database

```bash
php artisan db:seed
```

This will create:
- 5 sample courses
- 3 users (Admin, CEO, Staff)
- 8 sample students

### 8. Start the Development Server

```bash
php artisan serve
```

The application will be available at `http://localhost:8000`

## Default Credentials

### Admin Account
- Email: `admin@example.com`
- Password: `password`
- Role: Admin (read-only access)

### CEO Account
- Email: `ceo@example.com`
- Password: `password`
- Role: CEO (read-only access)

### Staff Account
- Email: `staff@example.com`
- Password: `password`
- Role: Staff (full CRUD access)

### Or Register as Staff
- Visit `/register` to create a new Staff account

## Project Structure

```
student-management-system/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php      # Authentication logic
│   │   │   └── StudentController.php   # Student CRUD operations
│   │   └── Middleware/
│   │       ├── CheckRole.php           # Role-based access control
│   │       └── CheckStaffRole.php      # Staff-only access
│   └── Models/
│       ├── User.php                    # User model with role methods
│       ├── Student.php                 # Student model
│       └── Course.php                  # Course model
├── database/
│   ├── migrations/                     # Database schema
│   └── seeders/
│       └── DatabaseSeeder.php          # Sample data
├── resources/
│   └── views/
│       ├── auth/
│       │   ├── login.blade.php         # Login page
│       │   └── register.blade.php      # Registration page
│       ├── layouts/
│       │   └── app.blade.php           # Main layout with styling
│       ├── dashboards/
│       │   ├── staff.blade.php         # Staff dashboard
│       │   ├── admin.blade.php         # Admin dashboard
│       │   └── ceo.blade.php           # CEO dashboard
│       └── students/
│           ├── index.blade.php         # Student list
│           ├── create.blade.php        # Add student form
│           └── edit.blade.php          # Edit student form
├── routes/
│   └── web.php                         # Application routes
├── config/
│   └── auth.php                        # Authentication configuration
└── .env.example                        # Environment template
```

## Features Overview

### Authentication
- User registration (Staff only)
- User login with email and password
- Session-based authentication
- Logout functionality

### Role-Based Access Control
- **Staff**: Full CRUD access to students and courses
- **Admin**: Read-only access to all data
- **CEO**: Read-only access to all data

### Student Management
- View all students with pagination
- Search students by name
- Filter students by course
- Add new students (Staff only)
- Edit student details (Staff only)
- Delete students (Staff only)
- View student marks

### User Interface
- Responsive design
- Clean and modern styling
- Toast notifications for success/error messages
- Delete confirmation dialogs
- Form validation
- Search and filter functionality
- Pagination for large datasets

## Routes

### Public Routes
- `GET /` - Redirect to login
- `GET /login` - Login page
- `POST /login` - Handle login
- `GET /register` - Registration page
- `POST /register` - Handle registration

### Protected Routes (Authenticated Users)
- `GET /dashboard` - Redirect to role-specific dashboard
- `POST /logout` - Logout user

### Staff Routes
- `GET /staff-dashboard` - Staff dashboard
- `GET /students` - View all students
- `GET /students/create` - Add student form
- `POST /students` - Store new student
- `GET /students/{id}/edit` - Edit student form
- `PUT /students/{id}` - Update student
- `DELETE /students/{id}` - Delete student

### Admin Routes
- `GET /admin-dashboard` - Admin dashboard
- `GET /students` - View all students (read-only)

### CEO Routes
- `GET /ceo-dashboard` - CEO dashboard
- `GET /students` - View all students (read-only)

## Middleware

### CheckRole Middleware
Validates user role and redirects to dashboard if unauthorized.

Usage:
```php
Route::middleware('role:staff,admin')->group(function () {
    // Routes accessible to staff and admin
});
```

### CheckStaffRole Middleware
Restricts access to Staff users only for CRUD operations.

Usage:
```php
Route::middleware('staff')->group(function () {
    // Routes accessible to staff only
});
```

## Database Schema

### Users Table
```sql
- id (Primary Key)
- name (String)
- email (Unique String)
- password (String, hashed)
- role (Enum: staff, admin, ceo)
- created_at, updated_at (Timestamps)
```

### Courses Table
```sql
- id (Primary Key)
- course_name (String)
- description (Text, nullable)
- created_at, updated_at (Timestamps)
```

### Students Table
```sql
- id (Primary Key)
- name (String)
- email (Unique String)
- course_id (Foreign Key → courses.id)
- marks (Integer, 0-100)
- created_at, updated_at (Timestamps)
```

## Troubleshooting

### Database Connection Error
- Verify MySQL is running
- Check database credentials in `.env`
- Ensure database exists

### Migration Errors
```bash
# Reset migrations (caution: deletes all data)
php artisan migrate:reset

# Re-run migrations
php artisan migrate --seed
```

### Permission Denied Errors
```bash
# Fix storage permissions
chmod -R 775 storage bootstrap/cache
```

### Clear Cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

## Security Notes

- Passwords are hashed using bcrypt
- CSRF protection enabled on all forms
- SQL injection prevention through Eloquent ORM
- Role-based middleware protects sensitive routes
- Session-based authentication

## Future Enhancements

- Email verification
- Password reset functionality
- User profile management
- Export student data to CSV/PDF
- Advanced reporting and analytics
- Student performance charts
- Bulk student import
- Email notifications
- Two-factor authentication

## Support

For issues or questions, refer to the Laravel documentation:
- https://laravel.com/docs
- https://laravel.com/docs/authentication
- https://laravel.com/docs/authorization

## License

This project is open source and available under the MIT License.
