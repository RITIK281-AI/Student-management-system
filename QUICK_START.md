# Quick Start Guide

## 5-Minute Setup

### Step 1: Install Dependencies
```bash
composer install
```

### Step 2: Setup Environment
```bash
cp .env.example .env
php artisan key:generate
```

### Step 3: Configure Database
Edit `.env` and set your database credentials:
```
DB_DATABASE=student_management
DB_USERNAME=root
DB_PASSWORD=your_password
```

### Step 4: Run Migrations & Seed
```bash
php artisan migrate --seed
```

### Step 5: Start Server
```bash
php artisan serve
```

Visit: `http://localhost:8000`

## Login Credentials

| Role  | Email                | Password |
|-------|----------------------|----------|
| Admin | admin@example.com    | password |
| CEO   | ceo@example.com      | password |
| Staff | staff@example.com    | password |

Or register a new Staff account at `/register`

## What You Can Do

### As Staff
✅ Add students  
✅ Edit student details  
✅ Delete students  
✅ Assign courses  
✅ Update marks  

### As Admin/CEO
👁️ View all students  
👁️ Search students  
👁️ Filter by course  
👁️ View marks  

## Key Features

- 🔐 Role-based authentication
- 📚 Student management system
- 🎓 Course assignment
- 🔍 Search and filter
- 📄 Pagination
- 📱 Responsive design
- ✨ Clean UI with toast notifications

## File Structure

```
app/
├── Http/Controllers/
│   ├── AuthController.php
│   └── StudentController.php
├── Http/Middleware/
│   ├── CheckRole.php
│   └── CheckStaffRole.php
└── Models/
    ├── User.php
    ├── Student.php
    └── Course.php

resources/views/
├── auth/
│   ├── login.blade.php
│   └── register.blade.php
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

database/
├── migrations/
└── seeders/
    └── DatabaseSeeder.php
```

## Common Commands

```bash
# Start development server
php artisan serve

# Run migrations
php artisan migrate

# Seed database
php artisan db:seed

# Reset database
php artisan migrate:reset

# Clear cache
php artisan cache:clear

# Create new migration
php artisan make:migration create_table_name

# Create new model
php artisan make:model ModelName

# Create new controller
php artisan make:controller ControllerName
```

## Troubleshooting

**Database connection error?**
- Check MySQL is running
- Verify `.env` database credentials
- Ensure database exists

**Migrations failed?**
```bash
php artisan migrate:reset
php artisan migrate --seed
```

**Permission errors?**
```bash
chmod -R 775 storage bootstrap/cache
```

## Next Steps

1. Login with provided credentials
2. Explore the dashboard
3. Add some students
4. Try searching and filtering
5. Test different roles

Enjoy! 🎉
