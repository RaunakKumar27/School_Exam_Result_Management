
<?php
session_start();
require_once __DIR__ . '/config/database.php';

// Require a successful student search
if (empty($_SESSION['portal_student_id'])) {
    header("Location: student_portal.php");
    exit;
}

$studentId = (int)$_SESSION['portal_student_id'];

$examId = $_GET['exam_id'] ?? '';

if (!ctype_digit((string)$examId) || (int)$examId < 1) {
    die("Invalid examination ID.");
}

$examId = (int)$examId;

// Fetch student information
$stmt = $pdo->prepare(
    "SELECT * FROM Students WHERE student_id = ?"
);
$stmt->execute([$studentId]);
$student = $stmt->fetch();

if (!$student) {
    unset($_SESSION['portal_student_id']);
    header("Location: student_portal.php");
    exit;
}

// Fetch exam information
$stmt = $pdo->prepare(
    "SELECT e.*
     FROM Exams e
     INNER JOIN Marks m ON e.exam_id = m.exam_id
     WHERE e.exam_id = ?
       AND m.student_id = ?
     LIMIT 1"
);
$stmt->execute([$examId, $studentId]);
$exam = $stmt->fetch();

if (!$exam) {
    die("This examination result is not available.");
}

// Fetch subject-wise marks
$sql = "
    SELECT
        sub.subject_name,
        sub.subject_code,
        sub.max_marks,
        m.marks_obtained,
        CASE
            WHEN m.marks_obtained < 33 THEN 'FAIL'
            ELSE 'PASS'
        END AS subject_status
    FROM Marks m
    INNER JOIN Subjects sub
        ON m.subject_id = sub.subject_id
    WHERE m.student_id = ?
      AND m.exam_id = ?
    ORDER BY sub.subject_name
";

$stmt = $pdo->prepare($sql);
$stmt->execute([$studentId, $examId]);
$marks = $stmt->fetchAll();

// Calculate overall performance
$totalObtained = 0;
$totalMaximum = 0;
$failedSubjects = 0;

foreach ($marks as $mark) {

    $totalObtained += (float)$mark['marks_obtained'];
    $totalMaximum += (float)$mark['max_marks'];

    if ((float)$mark['marks_obtained'] < 33) {
        $failedSubjects++;
    }
}

$percentage = $totalMaximum > 0
    ? ($totalObtained / $totalMaximum) * 100
    : 0;

$cgpa = $percentage / 10;

// Overall result status
if (count($marks) === 0) {
    $status = "NO RESULT";
} elseif ($failedSubjects > 0) {
    $status = "SUPPLEMENTARY";
} elseif ($cgpa < 4) {
    $status = "FAIL";
} else {
    $status = "PASS";
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

    <title>My Examination Result | XYZ College</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>

<body class="bg-light">

<div class="container py-5">

    <div class="text-center mb-4">
        <h2>Examination Result</h2>
        <p class="text-muted">Official Student Performance Summary</p>
    </div>

    <!-- Student and examination details -->

    <div class="card shadow-sm mb-4">

        <div class="card-header bg-primary text-white">
            Student Information
        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-6 mb-3">
                    <strong>Student Name:</strong>
                    <?= e($student['student_name']) ?>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Roll Number:</strong>
                    <?= e($student['roll_number']) ?>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Class:</strong>
                    <?= e($student['class_name']) ?>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Examination:</strong>
                    <?= e($exam['exam_name']) ?>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Academic Year:</strong>
                    <?= e($exam['academic_year']) ?>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Exam Date:</strong>
                    <?= e($exam['exam_date']) ?>
                </div>

            </div>

        </div>
    </div>

    <!-- Subject-wise result -->

    <div class="card shadow-sm mb-4">

        <div class="card-header bg-dark text-white">
            Subject-wise Marks
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover">

                    <thead class="table-dark">
                        <tr>
                            <th>Subject</th>
                            <th>Subject Code</th>
                            <th>Maximum Marks</th>
                            <th>Marks Obtained</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php foreach ($marks as $mark): ?>

                        <tr>
                            <td><?= e($mark['subject_name']) ?></td>
                            <td><?= e($mark['subject_code']) ?></td>
                            <td><?= e($mark['max_marks']) ?></td>
                            <td><?= e($mark['marks_obtained']) ?></td>

                            <td>
                                <?php if ($mark['subject_status'] === 'FAIL'): ?>
                                    <span class="badge bg-danger">FAIL</span>
                                <?php else: ?>
                                    <span class="badge bg-success">PASS</span>
                                <?php endif; ?>
                            </td>
                        </tr>

                    <?php endforeach; ?>

                    </tbody>

                </table>

            </div>

        </div>
    </div>

    <!-- Overall result -->

    <div class="card shadow-sm mb-4">

        <div class="card-header bg-primary text-white">
            Overall Result Summary
        </div>

        <div class="card-body">

            <div class="row text-center">

                <div class="col-md-3 mb-3">
                    <h6 class="text-muted">Total Marks</h6>
                    <h3><?= number_format($totalObtained, 2) ?></h3>
                </div>

                <div class="col-md-3 mb-3">
                    <h6 class="text-muted">Maximum Marks</h6>
                    <h3><?= number_format($totalMaximum, 2) ?></h3>
                </div>

                <div class="col-md-3 mb-3">
                    <h6 class="text-muted">Percentage</h6>
                    <h3><?= number_format($percentage, 2) ?>%</h3>
                </div>

                <div class="col-md-3 mb-3">
                    <h6 class="text-muted">CGPA</h6>
                    <h3><?= number_format($cgpa, 2) ?>/10</h3>
                </div>

            </div>

            <hr>

            <div class="text-center">

                <h5>Final Result</h5>

                <?php if ($status === "PASS"): ?>
                    <span class="badge bg-success fs-5">PASS</span>

                <?php elseif ($status === "FAIL"): ?>
                    <span class="badge bg-danger fs-5">FAIL</span>

                <?php elseif ($status === "SUPPLEMENTARY"): ?>
                    <span class="badge bg-warning text-dark fs-5">
                        SUPPLEMENTARY
                    </span>

                <?php else: ?>
                    <span class="badge bg-secondary fs-5">
                        NO RESULT
                    </span>
                <?php endif; ?>

                <p class="mt-3 text-muted">
                    Failed Subjects: <?= $failedSubjects ?>
                </p>

            </div>

        </div>
    </div>

    <div class="d-flex justify-content-between flex-wrap gap-2">

        <a href="student_portal.php"
           class="btn btn-secondary">
            Back to My Examinations
        </a>

        <button onclick="window.print()"
                class="btn btn-primary">
            Print Result
        </button>

    </div>

</div>

</body>
</html>
