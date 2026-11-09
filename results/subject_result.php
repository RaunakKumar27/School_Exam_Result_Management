
<?php
require_once __DIR__ . '/../config/database.php';

$subjectId = $_GET['subject_id'] ?? '';

if (!ctype_digit((string)$subjectId) || (int)$subjectId < 1) {
    die("Invalid subject ID.");
}

$subjectId = (int)$subjectId;

// Fetch subject information
$stmt = $pdo->prepare(
    "SELECT * FROM Subjects WHERE subject_id = ?"
);
$stmt->execute([$subjectId]);
$subject = $stmt->fetch();

if (!$subject) {
    die("Subject not found.");
}

// Fetch all students who have marks in this subject
$sql = "
    SELECT
        s.student_name,
        s.class_name,
        s.roll_number,
        e.exam_name,
        e.academic_year,
        m.marks_obtained,
        sub.max_marks,
        CASE
            WHEN m.marks_obtained < 33 THEN 'FAIL'
            ELSE 'PASS'
        END AS result_status
    FROM Marks m
    INNER JOIN Students s
        ON m.student_id = s.student_id
    INNER JOIN Subjects sub
        ON m.subject_id = sub.subject_id
    INNER JOIN Exams e
        ON m.exam_id = e.exam_id
    WHERE sub.subject_id = ?
    ORDER BY e.exam_date DESC, s.class_name, s.roll_number
";

$stmt = $pdo->prepare($sql);
$stmt->execute([$subjectId]);
$results = $stmt->fetchAll();

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Subject Results | XYZ College</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>

<body class="bg-light">

<div class="container py-5">

    <div class="mb-4">
        <h2><?= e($subject['subject_name']) ?> - Results</h2>

        <p class="text-muted">
            Subject Code: <?= e($subject['subject_code']) ?>
            | Maximum Marks: <?= e($subject['max_marks']) ?>
        </p>
    </div>

    <div class="card shadow-sm">

        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Student-wise Subject Marks</h5>
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover">

                    <thead class="table-dark">
                        <tr>
                            <th>Student Name</th>
                            <th>Class</th>
                            <th>Roll Number</th>
                            <th>Examination</th>
                            <th>Academic Year</th>
                            <th>Marks Obtained</th>
                            <th>Maximum Marks</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php if (count($results) > 0): ?>

                        <?php foreach ($results as $result): ?>

                            <tr>
                                <td><?= e($result['student_name']) ?></td>
                                <td><?= e($result['class_name']) ?></td>
                                <td><?= e($result['roll_number']) ?></td>
                                <td><?= e($result['exam_name']) ?></td>
                                <td><?= e($result['academic_year']) ?></td>
                                <td><?= e($result['marks_obtained']) ?></td>
                                <td><?= e($result['max_marks']) ?></td>

                                <td>
                                    <?php if ($result['result_status'] === 'FAIL'): ?>
                                        <span class="badge bg-danger">FAIL</span>
                                    <?php else: ?>
                                        <span class="badge bg-success">PASS</span>
                                    <?php endif; ?>
                                </td>
                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="8" class="text-center text-muted">
                                No students have marks in this subject.
                            </td>
                        </tr>

                    <?php endif; ?>

                    </tbody>

                </table>

            </div>

            <a href="index.php" class="btn btn-secondary">
                Back to Results
            </a>

        </div>
    </div>

</div>

</body>
</html>
