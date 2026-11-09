# College Examination Results Management System

A PHP and MySQL web application for managing student records, subjects, examinations, marks, and examination results. The project includes an administrative dashboard and a student portal for looking up results.

## Features

- **Admin dashboard** — view counts for students, subjects, exams, and marks.
- **Student management** — add, edit, list, and delete student records.
- **Subject management** — maintain subjects and their maximum marks.
- **Examination management** — record exams, dates, and academic years.
- **Marks management** — enter and update marks for students by subject and exam.
- **Results and reports** — review examination results and subject-specific results.
- **Student portal** — find available examination results using a student's name and roll number.

## Project structure

```text
school_exam/
├── admin/                   # Admin dashboard
├── assets/
│   └── css/                 # Stylesheets
├── config/
│   └── database.php         # PDO database connection
├── exams/                   # Exam management
├── marks/                   # Marks management
├── results/                 # Results and reports
├── sql/
│   └── project_queries.sql  # Example SQL queries
├── students/                # Student management
├── subjects/                # Subject management
├── index.php                # Main landing page
├── student_portal.php       # Student result lookup
└── student_exam_result.php  # Student examination result details
```

## Requirements

- PHP with the **PDO MySQL** extension enabled
- MySQL or MariaDB
- Apache or another PHP-capable web server
- A web browser
- Internet access to load the Bootstrap and Bootstrap Icons stylesheets from their CDNs

XAMPP provides Apache, PHP, and MariaDB/MySQL for local development.

## Setup with XAMPP

1. Copy the project folder into XAMPP's `htdocs` directory. For example:

   ```text
   C:\xampp\htdocs\school_exam
   ```

2. Start **Apache** and **MySQL** from the XAMPP Control Panel.

3. Create a database named `school_exam_db` in phpMyAdmin or MySQL.

4. Configure the database connection in [`config/database.php`](config/database.php). The current defaults are:

   ```php
   $host = "127.0.0.1";
   $port = "3307";
   $dbname = "school_exam_db";
   $username = "root";
   $password = "";
   ```

   Update the host, port, database name, username, and password to match your local MySQL configuration.

5. Create or import the database tables expected by the application before opening the site. **This project does not currently include a database schema or seed-data file.** The [`sql/project_queries.sql`](sql/project_queries.sql) file contains example queries and assumes the database and tables already exist; it does not create them.

6. Open the landing page:

   ```text
   http://localhost/school_exam/
   ```

## Database tables

The application expects these tables:

- `Students`
- `Subjects`
- `Exams`
- `Marks`

The PHP pages use these tables to store and retrieve student, subject, exam, and marks information. Review the queries in the application and in `sql/project_queries.sql` when creating a compatible schema.

## Main pages

| Page | URL |
|---|---|
| Main landing page | `/school_exam/` |
| Admin dashboard | `/school_exam/admin/` |
| Student result portal | `/school_exam/student_portal.php` |

## Development notes

- Database access uses PHP PDO.
- Configure credentials for your environment in `config/database.php`.
- The default database connection values are intended for local development. Do not use an empty database password or expose development credentials in a production deployment.
- `sql/project_queries.sql` is provided as a collection of example queries for the project.
