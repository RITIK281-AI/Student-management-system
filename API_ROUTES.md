# API Routes Documentation

## Authentication Routes

### Login
- **Route:** `POST /login`
- **Access:** Public (Guest only)
- **Parameters:**
  - `email` (required, email)
  - `password` (required, min:6)
- **Response:** Redirects to dashboard on success
- **Example:**
  ```
  POST /login
  email=admin@example.com
  password=password
  ```

### Register
- **Route:** `POST /register`
- **Access:** Public (Guest only)
- **Parameters:**
  - `name` (required, string, max:255)
  - `email` (required, email, unique)
  - `password` (required, min:6, confirmed)
  - `password_confirmation` (required)
- **Response:** Redirects to dashboard on success
- **Note:** Only creates Staff accounts
- **Example:**
  ```
  POST /register
  name=John Doe
  email=john@example.com
  password=password123
  password_confirmation=password123
  ```

### Logout
- **Route:** `POST /logout`
- **Access:** Authenticated users only
- **Response:** Redirects to login page
- **Example:**
  ```
  POST /logout
  ```

## Dashboard Routes

### Dashboard (Role-based redirect)
- **Route:** `GET /dashboard`
- **Access:** Authenticated users only
- **Response:** Redirects to role-specific dashboard
- **Redirect Logic:**
  - Staff → `/staff-dashboard`
  - Admin → `/admin-dashboard`
  - CEO → `/ceo-dashboard`

### Staff Dashboard
- **Route:** `GET /staff-dashboard`
- **Access:** Staff only
- **Response:** Staff dashboard view with statistics
- **Data Returned:**
  - Total students count
  - Total courses count
  - Recent 5 students

### Admin Dashboard
- **Route:** `GET /admin-dashboard`
- **Access:** Admin only
- **Response:** Admin dashboard with student list
- **Data Returned:**
  - Total students count
  - Total courses count
  - Paginated student list (10 per page)

### CEO Dashboard
- **Route:** `GET /ceo-dashboard`
- **Access:** CEO only
- **Response:** CEO dashboard with student list
- **Data Returned:**
  - Total students count
  - Total courses count
  - Paginated student list (10 per page)

## Student Routes

### List Students
- **Route:** `GET /students`
- **Access:** All authenticated users
- **Query Parameters:**
  - `search` (optional) - Search by student name
  - `course_id` (optional) - Filter by course ID
  - `page` (optional) - Page number for pagination
- **Response:** Student list view with pagination
- **Example:**
  ```
  GET /students?search=John&course_id=1&page=1
  ```

### Create Student Form
- **Route:** `GET /students/create`
- **Access:** Staff only
- **Response:** Create student form view
- **Middleware:** `staff`

### Store Student
- **Route:** `POST /students`
- **Access:** Staff only
- **Parameters:**
  - `name` (required, string, max:255)
  - `email` (required, email, unique)
  - `course_id` (required, exists in courses table)
  - `marks` (required, integer, 0-100)
- **Response:** Redirects to student list with success message
- **Middleware:** `staff`
- **Example:**
  ```
  POST /students
  name=Jane Doe
  email=jane@example.com
  course_id=1
  marks=85
  ```

### Edit Student Form
- **Route:** `GET /students/{id}/edit`
- **Access:** Staff only
- **URL Parameters:**
  - `id` (required) - Student ID
- **Response:** Edit student form view with pre-filled data
- **Middleware:** `staff`
- **Example:**
  ```
  GET /students/1/edit
  ```

### Update Student
- **Route:** `PUT /students/{id}`
- **Access:** Staff only
- **URL Parameters:**
  - `id` (required) - Student ID
- **Parameters:**
  - `name` (required, string, max:255)
  - `email` (required, email, unique except current)
  - `course_id` (required, exists in courses table)
  - `marks` (required, integer, 0-100)
- **Response:** Redirects to student list with success message
- **Middleware:** `staff`
- **Example:**
  ```
  PUT /students/1
  name=Jane Doe Updated
  email=jane.updated@example.com
  course_id=2
  marks=90
  ```

### Delete Student
- **Route:** `DELETE /students/{id}`
- **Access:** Staff only
- **URL Parameters:**
  - `id` (required) - Student ID
- **Response:** Redirects to student list with success message
- **Middleware:** `staff`
- **Example:**
  ```
  DELETE /students/1
  ```

## Route Summary Table

| Method | Route | Access | Purpose |
|--------|-------|--------|---------|
| GET | / | Public | Redirect to login |
| GET | /login | Guest | Show login form |
| POST | /login | Guest | Handle login |
| GET | /register | Guest | Show register form |
| POST | /register | Guest | Handle registration |
| POST | /logout | Auth | Logout user |
| GET | /dashboard | Auth | Redirect to role dashboard |
| GET | /staff-dashboard | Staff | Staff dashboard |
| GET | /admin-dashboard | Admin | Admin dashboard |
| GET | /ceo-dashboard | CEO | CEO dashboard |
| GET | /students | Auth | List students |
| GET | /students/create | Staff | Create form |
| POST | /students | Staff | Store student |
| GET | /students/{id}/edit | Staff | Edit form |
| PUT | /students/{id} | Staff | Update student |
| DELETE | /students/{id} | Staff | Delete student |

## Middleware

### Authentication Middleware
- Checks if user is logged in
- Redirects to login if not authenticated

### Role Middleware (`role:staff,admin,ceo`)
- Checks if user has specified role
- Redirects to dashboard if unauthorized

### Staff Middleware (`staff`)
- Checks if user is staff
- Redirects to dashboard with error if not staff

## Response Codes

### Success Responses
- **200 OK** - Request successful
- **302 Found** - Redirect (after form submission)

### Error Responses
- **401 Unauthorized** - User not authenticated
- **403 Forbidden** - User not authorized for this action
- **404 Not Found** - Resource not found
- **422 Unprocessable Entity** - Validation errors

## Validation Rules

### Login
```
email: required|email
password: required|min:6
```

### Register
```
name: required|string|max:255
email: required|email|unique:users
password: required|min:6|confirmed
```

### Create/Update Student
```
name: required|string|max:255
email: required|email|unique:students[,email,{id}]
course_id: required|exists:courses,id
marks: required|integer|min:0|max:100
```

## Error Messages

### Authentication Errors
- "The provided credentials do not match our records." - Invalid login
- "Email has already been taken." - Email already registered
- "The password confirmation does not match." - Password mismatch

### Validation Errors
- "The name field is required." - Missing name
- "The email must be a valid email address." - Invalid email format
- "The email has already been taken." - Duplicate email
- "The course id field is required." - Missing course
- "The marks must be between 0 and 100." - Invalid marks

### Authorization Errors
- "Unauthorized access" - User doesn't have permission

## Success Messages

- "Registration successful!" - After registration
- "Student added successfully!" - After creating student
- "Student updated successfully!" - After updating student
- "Student deleted successfully!" - After deleting student
- "Logged out successfully!" - After logout

## Pagination

Student list uses pagination with 10 items per page.

**Pagination Links:**
- First page: `?page=1`
- Next page: `?page=2`
- Previous page: `?page=1`

**Pagination Info:**
- Total items
- Current page
- Items per page
- Total pages

## Search and Filter

### Search by Name
```
GET /students?search=John
```
Searches for students with "John" in their name (case-insensitive).

### Filter by Course
```
GET /students?course_id=1
```
Shows only students enrolled in course with ID 1.

### Combined Search and Filter
```
GET /students?search=John&course_id=1
```
Shows students with "John" in name enrolled in course 1.

## CSRF Protection

All POST, PUT, DELETE requests require CSRF token:
```html
<input type="hidden" name="_token" value="{{ csrf_token() }}">
```

## Session Management

- Session driver: File-based
- Session lifetime: 120 minutes
- Session regenerated after login
- Session invalidated after logout

## Rate Limiting

No rate limiting implemented in this version.

## API Versioning

This is version 1.0 of the API. No versioning prefix is used.

## Future Enhancements

- [ ] JSON API endpoints
- [ ] API authentication tokens
- [ ] Rate limiting
- [ ] API versioning
- [ ] Webhook support
- [ ] Export to CSV/PDF
- [ ] Bulk operations
- [ ] Advanced filtering
- [ ] Sorting options
- [ ] API documentation (Swagger/OpenAPI)
