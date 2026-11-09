
<?php
require_once __DIR__ . '/../config/database.php';

// Student-wise examination results
$studentSql = "
    SELECT
        s.student_id,
        s.student_name,
        s.class_name,
        e.exam_name,
        e.academic_year,
        SUM(m.marks_obtained) AS total_obtained,
        SUM(sub.max_marks) AS total_maximum,
        ROUND(
    SUM(m.marks_obtained) / NULLIF(SUM(sub.max_marks), 0) * 10,
    2
) AS cgpa,
        COUNT(m.mark_id) AS subjects_count,

SUM(
    CASE
        WHEN m.marks_obtained < 33 THEN 1
        ELSE 0
    END
) AS failed_subjects
    FROM Marks m
    INNER JOIN Students s
        ON m.student_id = s.student_id
    INNER JOIN Subjects sub
        ON m.subject_id = sub.subject_id
    INNER JOIN Exams e
        ON m.exam_id = e.exam_id
    GROUP BY
        s.student_id,
        s.student_name,
        s.class_name,
        e.exam_id,
        e.exam_name,
        e.academic_year
    ORDER BY s.student_name
";

$studentResults = $pdo->query($studentSql)->fetchAll();

// Subject-wise marks

$subjectListSql = "
    SELECT
        sub.subject_id,
        sub.subject_name,
        sub.subject_code,
        COUNT(DISTINCT m.student_id) AS total_students
    FROM Subjects sub
    INNER JOIN Marks m
        ON sub.subject_id = m.subject_id
    GROUP BY
        sub.subject_id,
        sub.subject_name,
        sub.subject_code
    ORDER BY sub.subject_name
";

$subjectList = $pdo->query($subjectListSql)->fetchAll();


function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Student Results | XYZ College</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>

<body class="bg-light">

<div class="container py-5">

    <h2>Examination Results</h2>
    <p class="text-muted">
        Student-wise and subject-wise examination performance.
    </p>

    <!-- Student-wise results -->

    <div class="card shadow-sm mb-5">

        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Student-wise Results</h5>
        </div>

        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-bordered table-hover">

                    <thead class="table-dark">
                        <tr>
                            <th>Student Name</th>
                            <th>Class</th>
                            <th>Examination</th>
                            <th>Academic Year</th>
                            <th>Subjects</th>
                            <th>Total Marks</th>
                            <th>Maximum Marks</th>
                            <th>CGPA</th>
                            <th>Result Status</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php if (count($studentResults) > 0): ?>

                        <?php foreach ($studentResults as $result): ?>
                            <tr>
                                <td><?= e($result['student_name']) ?></td>
                                <td><?= e($result['class_name']) ?></td>
                                <td><?= e($result['exam_name']) ?></td>
                                <td><?= e($result['academic_year']) ?></td>
                                <td><?= e($result['subjects_count']) ?></td>
                                <td><?= e($result['total_obtained']) ?></td>
                                <td><?= e($result['total_maximum']) ?></td>
                                <td>
    <strong><?= e($result['cgpa']) ?></strong>
</td>

<td>
    <?php if ((int)$result['failed_subjects'] > 0): ?>

        <span class="badge bg-warning text-dark">
            SUPPLEMENTARY
        </span>

    <?php elseif ((float)$result['cgpa'] < 4): ?>

        <span class="badge bg-danger">
            FAIL
        </span>

    <?php else: ?>

        <span class="badge bg-success">
            PASS
        </span>

    <?php endif; ?>
</td>
                            </tr>
                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">
                                No examination results available yet.
                            </td>
                        </tr>

                    <?php endif; ?>

                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <!-- Subject-wise results -->

    
<!-- Subject-wise Results -->

<div class="card shadow-sm mb-4">

    <div class="card-header bg-dark text-white">
        <h5 class="mb-0">Subject-wise Results</h5>
    </div>

    <div class="card-body">

        <div class="table-responsive">

            <table class="table table-bordered table-hover">

                <thead class="table-dark">
                    <tr>
                        <th>Subject Name</th>
                        <th>Subject Code</th>
                        <th>Total Students</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>

                <?php if (count($subjectList) > 0): ?>

                    <?php foreach ($subjectList as $subject): ?>

                        <tr>
                            <td>
                                <?= e($subject['subject_name']) ?>
                            </td>

                            <td>
                                <?= e($subject['subject_code']) ?>
                            </td>

                            <td>
                                <?= e($subject['total_students']) ?>
                            </td>

                            <td>
                                <a href="subject_result.php?subject_id=<?= (int)$subject['subject_id'] ?>"
                                   class="btn btn-sm btn-primary">
                                    Check Result
                                </a>
                            </td>
                        </tr>

                    <?php endforeach; ?>

                <?php else: ?>

                    <tr>
                        <td colspan="4" class="text-center text-muted">
                            No subject results available.
                        </td>
                    </tr>

                <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>
</div>


    <a href="../admin/index.php">Back to Dashboard</a>

</div>

</body>
</html>
