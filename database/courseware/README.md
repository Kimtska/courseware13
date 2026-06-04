# Courseware Test Data

This folder contains test data and seeders for the VirtualArm Courseware system.

## Sample Test Accounts

The following test accounts are automatically created when running seeders:

### 1. Student Account
- **Name:** Juan Dela Cruz
- **Email:** student@courseware.local
- **Password:** password123
- **Role:** student
- **Dashboard:** `/student/dashboard`

### 2. Instructor Account
- **Name:** Maria Reyes
- **Email:** instructor@courseware.local
- **Password:** password123
- **Role:** instructor
- **Dashboard:** `/instructor/dashboard`

### 3. Department Head Account
- **Name:** Admin User
- **Email:** admin@courseware.local
- **Password:** password123
- **Role:** department_head
- **Dashboard:** `/department-head/dashboard`

## To Create Test Users

Run the seeder command in your terminal:

```bash
php artisan db:seed
```

Or specifically run the Courseware seeder:

```bash
php artisan db:seed --class=CoursewareSeeder
```

## Testing Navigation

1. Start your Laravel development server:
   ```bash
   php artisan serve
   ```

2. Login with any of the three accounts above

3. You should be redirected to the appropriate dashboard based on your role

4. Try logging in with each account to test:
   - Student: Modules, Progress tracking
   - Instructor: Student performance, Live sessions
   - Department Head: System overview, User management

## Resetting Test Data

To reset the database and recreate test users:

```bash
php artisan migrate:refresh --seed
```

This will drop all tables, recreate them, and seed the test data.
