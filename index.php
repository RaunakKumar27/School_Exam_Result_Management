
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>XYZ College | School Examination System</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
          rel="stylesheet">

    <style>
        body {
            background: #f4f7fb;
            min-height: 100vh;
        }

        .hero {
            padding: 65px 15px 35px;
            text-align: center;
        }

        .portal-card {
            border: 0;
            border-radius: 18px;
            transition: 0.25s;
            height: 100%;
        }

        .portal-card:hover {
            transform: translateY(-7px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.10);
        }

        .portal-icon {
            font-size: 55px;
        }

        .portal-button {
            padding: 12px;
            font-weight: 600;
            border-radius: 9px;
        }
    </style>

</head>

<body>

<nav class="navbar navbar-dark bg-primary px-4 py-3">
    <span class="navbar-brand fw-bold">
        <i class="bi bi-mortarboard-fill"></i>
        XYZ College
    </span>
</nav>

<div class="container">

    <div class="hero">

        <h1 class="fw-bold display-5">
            School Examination Results Management System
        </h1>

        <p class="text-muted fs-5 mt-3">
            A centralized platform for academic administration
            and student examination results.
        </p>

    </div>

    <div class="row justify-content-center g-4 pb-5">

        <!-- Admin Portal -->

        <div class="col-md-5">

            <div class="card portal-card shadow-sm">

                <div class="card-body text-center p-5">

                    <i class="bi bi-shield-lock-fill portal-icon text-primary"></i>

                    <h3 class="fw-bold mt-4">
                        Admin Portal
                    </h3>

                    <p class="text-muted my-3">
                        Manage student information, subjects,
                        examinations, marks and academic results.
                    </p>

                    <ul class="list-unstyled text-muted text-start mb-4">
                        <li class="mb-2">
                            <i class="bi bi-check-circle-fill text-success"></i>
                            Student Management
                        </li>

                        <li class="mb-2">
                            <i class="bi bi-check-circle-fill text-success"></i>
                            Subject and Exam Management
                        </li>

                        <li class="mb-2">
                            <i class="bi bi-check-circle-fill text-success"></i>
                            Marks Entry and Results
                        </li>
                    </ul>

                    <a href="admin/" class="btn btn-primary w-100 portal-button">
                        Enter Admin Portal
                        <i class="bi bi-arrow-right"></i>
                    </a>

                </div>
            </div>

        </div>

        <!-- Student Portal -->

        <div class="col-md-5">

            <div class="card portal-card shadow-sm">

                <div class="card-body text-center p-5">

                    <i class="bi bi-mortarboard-fill portal-icon text-success"></i>

                    <h3 class="fw-bold mt-4">
                        Student Portal
                    </h3>

                    <p class="text-muted my-3">
                        Search your examination results and
                        view your academic performance.
                    </p>

                    <ul class="list-unstyled text-muted text-start mb-4">
                        <li class="mb-2">
                            <i class="bi bi-check-circle-fill text-success"></i>
                            Search using Name and Roll Number
                        </li>

                        <li class="mb-2">
                            <i class="bi bi-check-circle-fill text-success"></i>
                            Semester-wise Results
                        </li>

                        <li class="mb-2">
                            <i class="bi bi-check-circle-fill text-success"></i>
                            Subject Marks and CGPA
                        </li>
                    </ul>

                    <a href="student_portal.php"
                       class="btn btn-success w-100 portal-button">

                        Enter Student Portal
                        <i class="bi bi-arrow-right"></i>

                    </a>

                </div>
            </div>

        </div>

    </div>

</div>

<footer class="text-center text-muted py-4">
    © XYZ College | School Examination Results Management System
</footer>

</body>
</html>
