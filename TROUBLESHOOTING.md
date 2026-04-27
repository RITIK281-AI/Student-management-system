# Troubleshooting Guide

## Common Issues and Solutions

### Installation Issues

#### 1. Composer Install Fails

**Problem:** `composer install` command fails

**Solutions:**
```bash
# Clear composer cache
composer clear-cache

# Update composer
composer self-update

# Try installing again
composer install

# If still failing, check PHP version
php -v  # Should be 8.1 or higher

# Check if required extensions are installed
php -m  # Should include: json, pdo, pdo_mysql, mbstring, tokenizer, xml
```

#### 2. PHP Version Error

**Problem:** "PHP version does not satisfy requirements"

**Solution:**
```bash
# Check current PHP version
php -v

# If version is too old, update PHP
# On macOS with Homebrew:
brew install php@8.1
brew link php@8.1

# On Linux:
sudo apt-get install php8.1

# On Windows:
# Download from https://www.php.net/downloads
```

#### 3. Missing PHP Extensions

**Problem:** "Call to undefined function" or extension errors

**Solution:**
```bash
# Check installed extensions
php -m

# Install missing extensions
# On macOS:
brew install php@8.1-pdo
brew install php@8.1-mysql

# On Linux:
sudo apt-get install php8.1-pdo
sudo apt-get install php8.1-mysql
```

### Database Issues

#### 1. Database Connection Error

**Problem:** "SQLSTATE[HY000] [2002] Connection refused"

**Solutions:**
```bash
# Check if MySQL is running
# On macOS:
brew services list

# Start MySQL if not running
brew services start mysql

# On Linux:
sudo systemctl start mysql

# On Windows:
# Start MySQL from Services or use:
net start MySQL80
```

#### 2. Database Not Found

**Problem:** "SQLSTATE[HY000] [1049] Unknown database"

**Solutions:**
```bash
# Create database manually
mysql -u root -p
CREATE DATABASE student_management;
EXIT;

# Or use Laravel command
php artisan db:create
```

#### 3. Wrong Database Credentials

**Problem:** "SQLSTATE [28000]: Invalid authorization specification"

**Solutions:**
```bash
# Check .env file
cat .env | grep DB_

# Update .env with correct credentials
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=student_management
DB_USERNAME=root
DB_PASSWORD=your_password

# Test connection
php artisan tinker
>>> DB::connection()->getPdo();
```

#### 4. Migration Errors

**Problem:** "SQLSTATE[42S01]: Table already exists"

**Solutions:**
```bash
# Reset all migrations (WARNING: deletes all data)
php artisan migrate:reset

# Refresh migrations (reset + migrate)
php artisan migrate:refresh

# Refresh with seed
php artisan migrate:refresh --seed

# Rollback last migration
php artisan migrate:rollback

# Rollback specific steps
php artisan migrate:rollback --step=1
```

#### 5. Foreign Key Constraint Error

**Problem:** "SQLSTATE[HY000]: General error: 1215 Cannot add foreign key constraint"

**Solutions:**
```bash
# Check if referenced table exists
php artisan tinker
>>> DB::table('courses')->count();

# Ensure migrations run in correct order
php artisan migrate:reset
php artisan migrate --seed

# Check MySQL strict mode
php artisan tinker
>>> DB::select('SELECT @@sql_mode');
```

### Authentication Issues

#### 1. Login Not Working

**Problem:** "The provided credentials do not match our records"

**Solutions:**
```bash
# Check if user exists
php artisan tinker
>>> App\Models\User::where('email', 'admin@example.com')->first();

# Verify password
>>> $user = App\Models\User::find(1);
>>> Hash::check('password', $user->password);

# Reset password
>>> $user->update(['password' => Hash::make('newpassword')]);

# Reseed database
php artisan db:seed
```

#### 2. Session Not Persisting

**Problem:** User logs out immediately after login

**Solutions:**
```bash
# Check session configuration
cat config/session.php

# Clear session files
rm -rf storage/framework/sessions/*

# Clear cache
php artisan cache:clear
php artisan config:clear

# Check storage permissions
chmod -R 775 storage bootstrap/cache
```

#### 3. CSRF Token Mismatch

**Problem:** "CSRF token mismatch" error

**Solutions:**
```bash
# Clear cache
php artisan cache:clear

# Regenerate app key
php artisan key:generate

# Check if form includes CSRF token
<!-- In Blade template -->
@csrf

<!-- Or -->
<input type="hidden" name="_token" value="{{ csrf_token() }}">
```

### Route Issues

#### 1. Route Not Found (404)

**Problem:** "Route not found" or 404 error

**Solutions:**
```bash
# List all routes
php artisan route:list

# Check if route exists
php artisan route:list | grep students

# Clear route cache
php artisan route:clear

# Verify route in routes/web.php
cat routes/web.php
```

#### 2. Middleware Not Working

**Problem:** Unauthorized access to protected routes

**Solutions:**
```bash
# Check middleware registration
cat app/Http/Kernel.php

# Verify middleware in route
php artisan route:list

# Test middleware
php artisan tinker
>>> auth()->check();
>>> auth()->user()->role;
```

#### 3. Redirect Loop

**Problem:** Infinite redirect between pages

**Solutions:**
```bash
# Check middleware order
cat app/Http/Kernel.php

# Check route redirects
php artisan route:list

# Clear cache
php artisan cache:clear
php artisan route:clear

# Check authentication logic
cat app/Http/Controllers/AuthController.php
```

### View/Template Issues

#### 1. Blade Template Error

**Problem:** "Undefined variable" or template error

**Solutions:**
```bash
# Check if variable is passed from controller
cat app/Http/Controllers/StudentController.php

# Verify view exists
ls resources/views/students/

# Clear view cache
php artisan view:clear

# Check Blade syntax
<!-- Correct -->
{{ $variable }}
@if($condition)
@endif

<!-- Incorrect -->
{$variable}
```

#### 2. CSS/JavaScript Not Loading

**Problem:** Styling or JavaScript not working

**Solutions:**
```bash
# Check if assets are in public folder
ls public/

# Clear browser cache
# Ctrl+Shift+Delete (Chrome/Firefox)
# Cmd+Shift+Delete (Safari)

# Hard refresh
# Ctrl+F5 (Windows)
# Cmd+Shift+R (Mac)

# Check CSS/JS paths in Blade
<!-- Correct -->
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
<script src="{{ asset('js/script.js') }}"></script>

<!-- Incorrect -->
<link rel="stylesheet" href="css/style.css">
```

#### 3. Form Validation Not Showing

**Problem:** Validation errors not displayed

**Solutions:**
```bash
# Check if errors are passed to view
@if($errors->any())
    @foreach($errors->all() as $error)
        <div>{{ $error }}</div>
    @endforeach
@endif

# Check form method
<form method="POST" action="{{ route('students.store') }}">
    @csrf
    <!-- form fields -->
</form>

# Verify validation in controller
$validated = $request->validate([
    'name' => 'required|string|max:255',
]);
```

### Performance Issues

#### 1. Slow Page Load

**Problem:** Pages loading slowly

**Solutions:**
```bash
# Enable query logging
php artisan tinker
>>> DB::enableQueryLog();
>>> // Run your code
>>> DB::getQueryLog();

# Use eager loading
// Slow
$students = Student::all();
foreach ($students as $student) {
    echo $student->course->course_name;
}

// Fast
$students = Student::with('course')->get();

# Clear cache
php artisan cache:clear
php artisan config:clear
```

#### 2. High Memory Usage

**Problem:** "Allowed memory size exhausted"

**Solutions:**
```bash
# Increase PHP memory limit
php -d memory_limit=512M artisan serve

# Or edit php.ini
memory_limit = 512M

# Use pagination instead of loading all
$students = Student::paginate(10); // Good
$students = Student::all(); // Bad for large datasets
```

#### 3. Database Queries Too Slow

**Problem:** Slow database queries

**Solutions:**
```bash
# Add indexes to frequently searched columns
// In migration
$table->index('name');
$table->index('email');

# Use select() to fetch only needed columns
$students = Student::select('id', 'name', 'email')->get();

# Use pagination
$students = Student::paginate(10);

# Avoid N+1 queries
$students = Student::with('course')->get(); // Good
$students = Student::all(); // Bad - causes N+1
```

### File Permission Issues

#### 1. Permission Denied Errors

**Problem:** "Permission denied" when writing files

**Solutions:**
```bash
# Fix storage permissions
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Or more permissive (less secure)
chmod -R 777 storage
chmod -R 777 bootstrap/cache

# Check current permissions
ls -la storage/
ls -la bootstrap/cache/
```

#### 2. Cannot Write to Log File

**Problem:** "Failed to open stream" or log errors

**Solutions:**
```bash
# Check log file permissions
ls -la storage/logs/

# Fix permissions
chmod 666 storage/logs/laravel.log

# Or recreate log file
rm storage/logs/laravel.log
touch storage/logs/laravel.log
chmod 666 storage/logs/laravel.log
```

### Development Server Issues

#### 1. Port Already in Use

**Problem:** "Address already in use" when starting server

**Solutions:**
```bash
# Use different port
php artisan serve --port=8001

# Find process using port 8000
lsof -i :8000

# Kill process
kill -9 <PID>

# On Windows
netstat -ano | findstr :8000
taskkill /PID <PID> /F
```

#### 2. Server Won't Start

**Problem:** Development server fails to start

**Solutions:**
```bash
# Check for syntax errors
php -l app/Http/Controllers/StudentController.php

# Clear cache
php artisan cache:clear
php artisan config:clear

# Check for port conflicts
php artisan serve --port=8001

# Restart with verbose output
php artisan serve --verbose
```

### Seeding Issues

#### 1. Seeder Not Running

**Problem:** `php artisan db:seed` doesn't create data

**Solutions:**
```bash
# Check if seeder exists
ls database/seeders/

# Run specific seeder
php artisan db:seed --class=DatabaseSeeder

# Check seeder code
cat database/seeders/DatabaseSeeder.php

# Refresh with seed
php artisan migrate:refresh --seed
```

#### 2. Duplicate Entry Error

**Problem:** "Duplicate entry" when seeding

**Solutions:**
```bash
# Reset database
php artisan migrate:reset

# Then seed
php artisan migrate --seed

# Or refresh
php artisan migrate:refresh --seed
```

### Email Issues

#### 1. Email Not Sending

**Problem:** Emails not being sent

**Solutions:**
```bash
# Check mail configuration
cat .env | grep MAIL_

# Set to log driver for testing
MAIL_MAILER=log

# Check log file
tail -f storage/logs/laravel.log

# Test email
php artisan tinker
>>> Mail::raw('Test', function($message) { $message->to('test@example.com'); });
```

## Debugging Tips

### 1. Use dd() Function
```php
// Dump and die
dd($variable);

// Dump multiple variables
dd($var1, $var2, $var3);
```

### 2. Use Log
```php
// Log to file
Log::info('User logged in', ['user_id' => $user->id]);
Log::error('Error occurred', ['error' => $exception]);

// Check logs
tail -f storage/logs/laravel.log
```

### 3. Use Tinker
```bash
php artisan tinker

# Query database
>>> App\Models\Student::all();
>>> App\Models\User::where('role', 'staff')->get();

# Test functions
>>> Hash::check('password', $hashedPassword);
>>> Auth::check();
```

### 4. Enable Query Logging
```php
DB::enableQueryLog();
// Run your code
dd(DB::getQueryLog());
```

### 5. Check Configuration
```bash
# View all config
php artisan config:show

# View specific config
php artisan config:show app
php artisan config:show database
```

## Getting Help

1. **Check Documentation**
   - README.md
   - SETUP.md
   - CODE_DOCUMENTATION.md

2. **Check Laravel Docs**
   - https://laravel.com/docs

3. **Search Error Message**
   - Google the exact error message
   - Check Stack Overflow

4. **Check Logs**
   - `storage/logs/laravel.log`
   - Browser console (F12)

5. **Use Debugging Tools**
   - `dd()` function
   - `Log::` functions
   - `php artisan tinker`

## Quick Reference

```bash
# Clear everything
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear

# Reset database
php artisan migrate:reset
php artisan migrate --seed

# Check status
php artisan route:list
php artisan config:show

# Debug
php artisan tinker
tail -f storage/logs/laravel.log
```

## Still Having Issues?

1. Check all documentation files
2. Review error messages carefully
3. Check Laravel logs
4. Use debugging tools
5. Search online for similar issues
6. Ask for help with specific error message

Remember: Most issues have been encountered before. The error message usually contains the solution!
