# EduTrack — Student Management System

A web-based student management system built with core PHP (no framework) and MySQL. It was developed as the second term project for **INTECH 2201: Web Applications Development**.

EduTrack lets a school manage students, instructors, subjects, courses, enrollments, and grades through role-based dashboards, and can generate PDF reports of academic records.

## Features

- **Role-based access** for three user types: Super-admin, Admin, and Instructor, each with their own dashboard and permissions.
- **Student management** — create, view, edit, and deactivate/activate student records.
- **User management** — manage admin and instructor accounts, including password resets.
- **Courses & subjects** — create and maintain courses and the subjects under them.
- **Enrollment management** — enroll students into subjects and track their standing.
- **Grade management** — record and update student grades per subject, with grade summaries.
- **Reports** — generate summaries (student grades, students per course, subject-wise, instructor profile) and export them as PDF using FPDF.

## Tech Stack

- **Backend:** PHP (procedural + lightweight custom MVC-style models), PDO for database access
- **Database:** MySQL
- **Frontend:** HTML, CSS, Bootstrap
- **PDF Generation:** [FPDF](http://www.fpdf.org/)

## Project Structure

```
auth/         Login and logout
dashboard/    Role-specific dashboards (admin, instructor, super-admin)
students/     Student CRUD
users/        User (admin/instructor) CRUD and password management
courses/      Course CRUD
subjects/     Subject CRUD
enrollment/   Enrollment CRUD
grades/       Grade CRUD and summaries
reports/      Report generation (including PDF export)
models/       Data models (Student, User, Course, Subject, Enrollment, Grade)
database/     Database connection class
layout/       Shared header/footer includes
css/          Stylesheets and images
plugins/fpdf/ FPDF library used for PDF report generation
```

## Getting Started

### Requirements

- PHP 7.4+ with PDO MySQL extension
- MySQL / MariaDB
- A local server environment (e.g. XAMPP, WAMP, Laragon)

### Setup

1. Clone or copy the project into your server's document root (e.g. `htdocs/student-management-system`).
2. Start XAMPP (Apache + MySQL) and open phpMyAdmin.
3. Import [database/schema.sql](database/schema.sql) — it creates the `student_mngmt_db` database, all required tables (`users`, `students`, `courses`, `subjects`, `subject_enrollments`, `grades`), and a small set of seed/sample data.
4. Update the database credentials in [database/Database.php](database/Database.php) to match your local MySQL setup (host, username, password) if they differ from the defaults (`root` with no/blank password on XAMPP).
5. Start your local server and navigate to the project in your browser, e.g.:
   ```
   http://localhost/student-management-system
   ```
6. Log in with one of the seeded accounts (password for all of them is `password123`):

   | Role | Email |
   |---|---|
   | Super-admin | superadmin@edutrack.com |
   | Admin | admin@edutrack.com |
   | Instructor | instructor@edutrack.com |

## Notes

This project was built for coursework purposes to practice core PHP, PDO, session-based authentication, and CRUD operations without relying on a framework.
