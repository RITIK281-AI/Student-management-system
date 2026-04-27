# Code Documentation

## Overview

This document provides detailed explanations of the Student Management System codebase.

## Models

### User Model (`app/Models/User.php`)

The User model represents system users with role-based access control.

**Fields:**
- `id` - Primary key
- `name` - User's full name
- `email` - Unique email address
- `password` - Hashed password
- `role` - User role (staff, admin, ceo)
- `created_at`, `updated_at` - Timestamps

**Methods:**
- `isStaff()` - Check if user is staff
- `isAdmin()` - Check if user is admin
- `isCeo()` - Check if user is CEO

**Example Usage:**
```php
$user = User::find(1);
if ($user->isStaff()) {
    // User is staff
}
```

### Course Model (`app/Models/Course.php`)

Represents courses that students can enroll in.

**Fields:**
- `id` - Primary key
- `course_name` - Name of the course
- `description` - Course description
- `created_at`, `updated_at` - Timestamps

**Relationships:**
- `students()` - One-to-many relationship with Student model

**Example Usage:**
```php
$course = Course::find(1);
$students = $course->students; // Get all students in this course
```

### Student Model (`app/Models/Student.php`)

Represents students enrolled in courses.

**Fields:**
- `id` - Primary key
- `name` - Student's full name
- `email` - Unique email address
- `course_id` - Foreign key to courses table
- `marks` - Student's marks (0-100)
- `created_at`, `updated_at` - Timestamps

**Relationships:**
- `course()` - Belongs-to relationship with Course model

**Example Usage:**
```php
$student = Student::find(1);
echo $student->course->course_name; // Get student's course name
```

## Controllers

### AuthController (`app/Http/Controllers/AuthController.php`)

Handles authentication operations.

**Methods:**

#### `showLogin()`
Displays the login form.

```php
public function showLogin()
{
    return view('auth.login');
}
```

#### `login(Request $request)`
Authenticates user credentials and creates a session.

```php
public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required|min:6',
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect()->route('dashboard');
    }

    return back()->withErrors(['email' => 'Invalid credentials']);
}
```

#### `showRegister()`
Displays the registration form.

#### `register(Request $request)`
Creates a new staff user account.

```php
public function register(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:6|confirmed',
    ]);

    $user = User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
        'role' => 'staff', // Only staff can register
    ]);

    Auth::login($user);
    return redirect()->route('dashboard');
}
```

#### `logout(Request $request)`
Logs out the current user.

#### `dashboard()`
Redirects to role-specific dashboard.

```php
public function dashboard()
{
    $user = auth()->user();

    if ($user->isStaff()) {
        return redirect()->route('staff.dashboard');
    } elseif ($user->isAdmin()) {
        return redirect()->route('admin.dashboard');
    } elseif ($user->isCeo()) {
        return redirect()->route('ceo.dashboard');
    }
}
```

### StudentController (`app/Http/Controllers/StudentController.php`)

Handles student CRUD operations and dashboard displays.

**Methods:**

#### `index(Request $request)`
Displays list of students with search and filter.

```php
public function index(Request $request)
{
    $query = Student::with('course');

    // Search by name
    if ($request->filled('search')) {
        $query->where('name', 'like', '%' . $request->search . '%');
    }

    // Filter by course
    if ($request->filled('course_id')) {
        $query->where('course_id', $request->course_id);
    }

    $students = $query->paginate(10);
    $courses = Course::all();

    return view('students.index', compact('students', 'courses'));
}
```

#### `create()`
Shows form to create new student (Staff only).

#### `store(Request $request)`
Saves new student to database (Staff only).

```php
public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:students',
        'course_id' => 'required|exists:courses,id',
        'marks' => 'required|integer|min:0|max:100',
    ]);

    Student::create($validated);

    return redirect()->route('students.index')
        ->with('success', 'Student added successfully!');
}
```

#### `edit(Student $student)`
Shows form to edit student (Staff only).

#### `update(Request $request, Student $student)`
Updates student in database (Staff only).

```php
public function update(Request $request, Student $student)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:students,email,' . $student->id,
        'course_id' => 'required|exists:courses,id',
        'marks' => 'required|integer|min:0|max:100',
    ]);

    $student->update($validated);

    return redirect()->route('students.index')
        ->with('success', 'Student updated successfully!');
}
```

#### `destroy(Student $student)`
Deletes student from database (Staff only).

```php
public function destroy(Student $student)
{
    $student->delete();

    return redirect()->route('students.index')
        ->with('success', 'Student deleted successfully!');
}
```

#### `staffDashboard()`
Displays staff dashboard with statistics.

#### `adminDashboard()`
Displays admin dashboard with read-only student list.

#### `ceoDashboard()`
Displays CEO dashboard with read-only student list.

## Middleware

### CheckRole Middleware (`app/Http/Middleware/CheckRole.php`)

Validates user role and restricts access based on allowed roles.

```php
public function handle(Request $request, Closure $next, ...$roles): Response
{
    if (!auth()->check()) {
        return redirect()->route('login');
    }

    if (in_array(auth()->user()->role, $roles)) {
        return $next($request);
    }

    return redirect()->route('dashboard');
}
```

**Usage in Routes:**
```php
Route::middleware('role:staff,admin')->group(function () {
    // Only staff and admin can access
});
```

### CheckStaffRole Middleware (`app/Http/Middleware/CheckStaffRole.php`)

Restricts access to staff users only.

```php
public function handle(Request $request, Closure $next): Response
{
    if (!auth()->check() || auth()->user()->role !== 'staff') {
        return redirect()->route('dashboard')
            ->with('error', 'Unauthorized access');
    }

    return $next($request);
}
```

**Usage in Routes:**
```php
Route::middleware('staff')->group(function () {
    // Only staff can access
});
```

## Routes (`routes/web.php`)

### Public Routes
```php
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
```

### Protected Routes
```php
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Role-specific dashboards
    Route::get('/staff-dashboard', [StudentController::class, 'staffDashboard'])
        ->middleware('role:staff')->name('staff.dashboard');
    
    // Student routes
    Route::get('/students', [StudentController::class, 'index'])->name('students.index');
    
    // Staff-only CRUD
    Route::middleware('staff')->group(function () {
        Route::get('/students/create', [StudentController::class, 'create'])->name('students.create');
        Route::post('/students', [StudentController::class, 'store'])->name('students.store');
        Route::get('/students/{student}/edit', [StudentController::class, 'edit'])->name('students.edit');
        Route::put('/students/{student}', [StudentController::class, 'update'])->name('students.update');
        Route::delete('/students/{student}', [StudentController::class, 'destroy'])->name('students.destroy');
    });
});
```

## Views

### Layout (`resources/views/layouts/app.blade.php`)

Main layout template with:
- Navigation bar
- Alert messages
- Validation error display
- Responsive CSS styling
- JavaScript for interactions

### Authentication Views

#### `auth/login.blade.php`
Login form with email and password fields.

#### `auth/register.blade.php`
Registration form for creating staff accounts.

### Dashboard Views

#### `dashboards/staff.blade.php`
Staff dashboard showing:
- Statistics (total students, courses)
- Quick actions (add student, view all)
- Recent students table

#### `dashboards/admin.blade.php`
Admin dashboard showing:
- Statistics (read-only)
- All students with pagination
- Permission overview

#### `dashboards/ceo.blade.php`
CEO dashboard showing:
- Statistics (read-only)
- All students with pagination
- Permission overview

### Student Views

#### `students/index.blade.php`
Student list with:
- Search by name
- Filter by course
- Pagination
- Edit/Delete buttons (staff only)

#### `students/create.blade.php`
Form to add new student with:
- Name input
- Email input
- Course selection
- Marks input
- Form validation

#### `students/edit.blade.php`
Form to edit student with:
- Pre-filled student data
- Course selection
- Marks update
- Student information display

## Database Migrations

### Users Table Migration
```php
Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('email')->unique();
    $table->string('password');
    $table->enum('role', ['staff', 'admin', 'ceo'])->default('staff');
    $table->timestamps();
});
```

### Courses Table Migration
```php
Schema::create('courses', function (Blueprint $table) {
    $table->id();
    $table->string('course_name');
    $table->text('description')->nullable();
    $table->timestamps();
});
```

### Students Table Migration
```php
Schema::create('students', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('email')->unique();
    $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
    $table->integer('marks')->default(0);
    $table->timestamps();
});
```

## Database Seeder

The `DatabaseSeeder` creates:
- 5 sample courses
- 3 users (Admin, CEO, Staff)
- 8 sample students

```php
public function run(): void
{
    // Create courses
    Course::create(['course_name' => 'Web Development']);
    
    // Create users
    User::create([
        'name' => 'Admin User',
        'email' => 'admin@example.com',
        'password' => Hash::make('password'),
        'role' => 'admin',
    ]);
    
    // Create students
    Student::create([
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'course_id' => 1,
        'marks' => 85,
    ]);
}
```

## Key Features Explained

### Role-Based Access Control

The system uses three roles:

1. **Staff** - Full CRUD access
   - Can create, read, update, delete students
   - Can assign courses
   - Can update marks

2. **Admin** - Read-only access
   - Can view all students
   - Can search and filter
   - Cannot modify data

3. **CEO** - Read-only access
   - Same as Admin
   - For reporting purposes

### Authentication Flow

1. User visits `/login`
2. Enters email and password
3. System validates credentials
4. If valid, creates session and redirects to dashboard
5. Dashboard redirects to role-specific page

### Student Management Flow

1. Staff visits `/students/create`
2. Fills in student form
3. System validates data
4. Student is saved to database
5. User is redirected with success message

### Search and Filter

Students can be searched by name and filtered by course:

```php
$query = Student::with('course');

if ($request->filled('search')) {
    $query->where('name', 'like', '%' . $request->search . '%');
}

if ($request->filled('course_id')) {
    $query->where('course_id', $request->course_id);
}

$students = $query->paginate(10);
```

## Security Considerations

1. **Password Hashing** - Passwords are hashed using bcrypt
2. **CSRF Protection** - All forms include CSRF tokens
3. **SQL Injection Prevention** - Eloquent ORM prevents SQL injection
4. **Role-Based Middleware** - Routes are protected by role middleware
5. **Session Management** - Sessions are regenerated after login

## Best Practices Used

1. **MVC Architecture** - Separation of concerns
2. **Eloquent ORM** - Database abstraction
3. **Blade Templating** - Secure template rendering
4. **Validation** - Input validation on all forms
5. **Error Handling** - Graceful error messages
6. **Code Comments** - Clear documentation
7. **Responsive Design** - Mobile-friendly UI
8. **Accessibility** - Semantic HTML

## Common Tasks

### Add a New Role

1. Update migration to add new role to enum
2. Add method to User model
3. Create new middleware if needed
4. Add routes for new role
5. Create dashboard view

### Add a New Feature

1. Create migration if database changes needed
2. Create/update model
3. Create controller method
4. Add routes
5. Create/update views
6. Add validation

### Debug Issues

1. Check Laravel logs: `storage/logs/laravel.log`
2. Enable debug mode in `.env`: `APP_DEBUG=true`
3. Use `dd()` function to dump variables
4. Check database with `php artisan tinker`

## Performance Tips

1. Use eager loading: `Student::with('course')`
2. Paginate large datasets: `->paginate(10)`
3. Index frequently searched columns
4. Cache static data
5. Use database indexes on foreign keys

## Testing

To test the application:

1. Login with different roles
2. Try accessing restricted routes
3. Test search and filter functionality
4. Test form validation
5. Test CRUD operations
6. Test pagination

## Deployment Checklist

- [ ] Set `APP_DEBUG=false` in production
- [ ] Generate strong `APP_KEY`
- [ ] Configure production database
- [ ] Run migrations: `php artisan migrate --force`
- [ ] Seed data if needed: `php artisan db:seed --force`
- [ ] Set proper file permissions
- [ ] Configure email settings
- [ ] Set up SSL certificate
- [ ] Configure backup strategy
- [ ] Monitor logs and errors
