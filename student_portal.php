
<?php
session_start();
require_once __DIR__ . '/config/database.php';

$error = "";

// Clear previous student search
if (isset($_GET['reset'])) {
    unset($_SESSION['portal_student_id']);
    header("Location: student_portal.php");
    exit;
}

// Search student using name and roll number
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST['student_name'] ?? '');
    $roll = trim($_POST['roll_number'] ?? '');

    unset($_SESSION['portal_student_id']);

    if ($name === "" || !ctype_digit($roll) || (int)$roll < 1) {
        $error = "Please enter a valid student name and roll number.";
    } else {

        $stmt = $pdo->prepare(
            "SELECT student_id, student_name, class_name, roll_number
             FROM Students
             WHERE LOWER(TRIM(student_name)) = LOWER(TRIM(?))
             AND roll_number = ?
             LIMIT 1"
        );

        $stmt->execute([$name, (int)$roll]);
        $student = $stmt->fetch();

        if ($student) {
            $_SESSION['portal_student_id'] = $student['student_id'];
        } else {
            $error = "No student found. Please check your name and roll number.";
        }
    }
}

// Fetch student details after successful search
$student = null;
$exams = [];

if (!empty($_SESSION['portal_student_id'])) {

    $stmt = $pdo->prepare(
        "SELECT student_id, student_name, class_name, roll_number
         FROM Students
         WHERE student_id = ?"
    );

    $stmt->execute([$_SESSION['portal_student_id']]);
    $student = $stmt->fetch();

    if ($student) {

        // Show exams for which this student has marks
        $stmt = $pdo->prepare(
            "SELECT
                e.exam_id,
                e.exam_name,
                e.exam_date,
                e.academic_year,
                COUNT(m.mark_id) AS subjects_count
             FROM Exams e
             INNER JOIN Marks m
                ON e.exam_id = m.exam_id
             WHERE m.student_id = ?
             GROUP BY
                e.exam_id,
                e.exam_name,
                e.exam_date,
                e.academic_year
             ORDER BY e.exam_date DESC, e.exam_id DESC"
        );

        $stmt->execute([$student['student_id']]);
        $exams = $stmt->fetchAll();
    } else {
        unset($_SESSION['portal_student_id']);
    }
}

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Student Result Portal | XYZ College</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>

<body class="bg-light">

<div class="container py-5">

    <div class="text-center mb-5">
        <h1 class="fw-bold">XYZ College</h1>
        <p class="text-muted">
            Search and view your examination results.
        </p>
    </div>

    <?php if (!$student): ?>

        <div class="row justify-content-center">
            <div class="col-md-6">

                <div class="card shadow-sm">
                    <div class="card-body p-4">

                        <h4 class="mb-4">Find Your Result</h4>

                        <?php if ($error !== ""): ?>
                            <div class="alert alert-danger">
                                <?= e($error) ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST">

                            <div class="mb-3">
                                <label class="form-label">
                                    Student Name
                                </label>

                                <input type="text"
                                       name="student_name"
                                       class="form-control"
                                       placeholder="Enter your full name"
                                       required>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">
                                    Roll Number
                                </label>

                                <input type="number"
                                       name="roll_number"
                                       class="form-control"
                                       min="1"
                                       placeholder="Enter your roll number"
                                       required>
                            </div>

                            <button type="submit"
                                    class="btn btn-primary w-100">
                                Search My Result
                            </button>

                        </form>

                    </div>
                </div>

            </div>
        </div>

    <?php else: ?>

        <!-- Student information -->

        <div class="card shadow-sm mb-4">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

                    <div>
                        <h4><?= e($student['student_name']) ?></h4>

                        <p class="mb-1">
                            Class: <?= e($student['class_name']) ?>
                        </p>

                        <p class="mb-0">
                            Roll Number: <?= e($student['roll_number']) ?>
                        </p>
                    </div>

                    <a href="student_portal.php?reset=1"
                       class="btn btn-outline-danger">
                        Search Another Student
                    </a>

                </div>

            </div>
        </div>

        <!-- Available examinations -->

        <div class="card shadow-sm">

            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Available Examination Results</h5>
            </div>

            <div class="card-body">

                <?php if (count($exams) > 0): ?>

                    <div class="table-responsive">

                        <table class="table table-bordered table-hover">

                            <thead class="table-dark">
                                <tr>
                                    <th>Examination</th>
                                    <th>Academic Year</th>
                                    <th>Exam Date</th>
                                    <th>Subjects</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>

                            <?php foreach ($exams as $exam): ?>

                                <tr>
                                    <td><?= e($exam['exam_name']) ?></td>

                                    <td><?= e($exam['academic_year']) ?></td>

                                    <td><?= e($exam['exam_date']) ?></td>

                                    <td><?= e($exam['subjects_count']) ?></td>

                                    <td>
                                        <a href="student_exam_result.php?exam_id=<?= (int)$exam['exam_id'] ?>"
                                           class="btn btn-sm btn-primary">
                                            View Result
                                        </a>
                                    </td>
                                </tr>

                            <?php endforeach; ?>

                            </tbody>

                        </table>

                    </div>

                <?php else: ?>

                    <div class="alert alert-info mb-0">
                        No examination results are available for this student yet.
                    </div>

                <?php endif; ?>

            </div>
        </div>

    <?php endif; ?>

    <div class="text-center mt-4">
        <a href="index.php" class="text-decoration-none">
            Back to Main Dashboard
        </a>
    </div>

</div>

</body>
</html>
