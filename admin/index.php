
<?php
require_once __DIR__ . '/../config/database.php';

$students = $pdo->query("SELECT COUNT(*) FROM Students")->fetchColumn();
$subjects = $pdo->query("SELECT COUNT(*) FROM Subjects")->fetchColumn();
$exams = $pdo->query("SELECT COUNT(*) FROM Exams")->fetchColumn();
$marks = $pdo->query("SELECT COUNT(*) FROM Marks")->fetchColumn();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Portal | XYZ College</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
          rel="stylesheet">
</head>

<body class="bg-light">

<nav class="navbar navbar-dark bg-primary px-4 py-3">
    <a class="navbar-brand fw-bold" href="index.php">
        <i class="bi bi-mortarboard-fill"></i> XYZ College Admin
    </a>

    <a href="../index.php" class="btn btn-light btn-sm">
        <i class="bi bi-house"></i> Home
    </a>
</nav>

<div class="container py-5">

    <div class="mb-5">
        <h2 class="fw-bold">Admin Dashboard</h2>
        <p class="text-muted">
            Manage students, subjects, examinations and academic results.
        </p>
    </div>

    <!-- Statistics -->

    <div class="row g-3 mb-5">

        <div class="col-md-3 col-6">
            <div class="card shadow-sm border-0 p-3">
                <i class="bi bi-people text-primary fs-2"></i>
                <h3><?= $students ?></h3>
                <p class="text-muted mb-0">Total Students</p>
            </div>
        </div>

        <div class="col-md-3 col-6">
            <div class="card shadow-sm border-0 p-3">
                <i class="bi bi-book text-success fs-2"></i>
                <h3><?= $subjects ?></h3>
                <p class="text-muted mb-0">Total Subjects</p>
            </div>
        </div>

        <div class="col-md-3 col-6">
            <div class="card shadow-sm border-0 p-3">
                <i class="bi bi-calendar-event text-warning fs-2"></i>
                <h3><?= $exams ?></h3>
                <p class="text-muted mb-0">Total Exams</p>
            </div>
        </div>

        <div class="col-md-3 col-6">
            <div class="card shadow-sm border-0 p-3">
                <i class="bi bi-clipboard-data text-danger fs-2"></i>
                <h3><?= $marks ?></h3>
                <p class="text-muted mb-0">Marks Records</p>
            </div>
        </div>

    </div>

    <!-- Management Modules -->

    <h4 class="fw-bold mb-3">Academic Management</h4>

    <div class="row g-4">

        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100 p-3">
                <i class="bi bi-people-fill text-primary fs-1"></i>
                <h5 class="mt-3">Student Management</h5>
                <p class="text-muted">
                    Add, view, update and delete student records.
                </p>
                <a href="../students/" class="btn btn-primary mt-auto">
                    Manage Students
                </a>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100 p-3">
                <i class="bi bi-book-half text-success fs-1"></i>
                <h5 class="mt-3">Subject Management</h5>
                <p class="text-muted">
                    Manage subjects, subject codes and maximum marks.
                </p>
                <a href="../subjects/" class="btn btn-success mt-auto">
                    Manage Subjects
                </a>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100 p-3">
                <i class="bi bi-calendar-check text-warning fs-1"></i>
                <h5 class="mt-3">Exam Management</h5>
                <p class="text-muted">
                    Create and manage semester and other examinations.
                </p>
                <a href="../exams/" class="btn btn-warning mt-auto">
                    Manage Exams
                </a>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100 p-3">
                <i class="bi bi-pencil-square text-danger fs-1"></i>
                <h5 class="mt-3">Marks Entry</h5>
                <p class="text-muted">
                    Enter, edit and manage student examination marks.
                </p>
                <a href="../marks/" class="btn btn-danger mt-auto">
                    Manage Marks
                </a>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100 p-3">
                <i class="bi bi-bar-chart-fill text-info fs-1"></i>
                <h5 class="mt-3">Examination Results</h5>
                <p class="text-muted">
                    View student-wise and subject-wise performance.
                </p>
                <a href="../results/" class="btn btn-info mt-auto">
                    View Results
                </a>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100 p-3">
                <i class="bi bi-person-badge text-secondary fs-1"></i>
                <h5 class="mt-3">Student Result Portal</h5>
                <p class="text-muted">
                    Open the student-facing result search portal.
                </p>
                <a href="../student_portal.php" class="btn btn-secondary mt-auto">
                    Open Student Portal
                </a>
            </div>
        </div>

    </div>

</div>

<footer class="text-center text-muted py-4">
    XYZ College | School Examination Results Management System
</footer>

</body>
</html>
