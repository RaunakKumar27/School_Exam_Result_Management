
<?php
require_once __DIR__ . '/../config/database.php';

// Fetch marks with student, subject and exam details
$sql = "SELECT
            m.mark_id,
            s.student_name,
            s.class_name,
            s.roll_number,
            sub.subject_name,
            e.exam_name,
            m.marks_obtained,
            sub.max_marks
        FROM Marks m
        INNER JOIN Students s
            ON m.student_id = s.student_id
        INNER JOIN Subjects sub
            ON m.subject_id = sub.subject_id
        INNER JOIN Exams e
            ON m.exam_id = e.exam_id
        ORDER BY m.mark_id DESC";

$stmt = $pdo->query($sql);
$marks = $stmt->fetchAll();

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Manage Marks | XYZ College</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>

<body class="bg-light">

<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Manage Marks</h2>
            <p class="text-muted">
                Manage student examination marks.
            </p>
        </div>

        <a href="add.php" class="btn btn-primary">
            + Add Marks
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover">

                    <thead class="table-dark">
                        <tr>
                            <th>Mark ID</th>
                            <th>Student Name</th>
                            <th>Class</th>
                            <th>Roll No.</th>
                            <th>Subject</th>
                            <th>Examination</th>
                            <th>Marks Obtained</th>
                            <th>Maximum Marks</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php if (count($marks) > 0): ?>

                        <?php foreach ($marks as $mark): ?>

                            <tr>
                                <td><?= e($mark['mark_id']) ?></td>
                                <td><?= e($mark['student_name']) ?></td>
                                <td><?= e($mark['class_name']) ?></td>
                                <td><?= e($mark['roll_number']) ?></td>
                                <td><?= e($mark['subject_name']) ?></td>
                                <td><?= e($mark['exam_name']) ?></td>
                                <td><?= e($mark['marks_obtained']) ?></td>
                                <td><?= e($mark['max_marks']) ?></td>
                                
                                <td>
    <a href="edit.php?id=<?= (int)$mark['mark_id'] ?>"
       class="btn btn-sm btn-primary">
        Edit
    </a>

    <form action="delete.php" method="POST" class="d-inline"
          onsubmit="return confirm('Are you sure you want to delete these marks?');">

        <input type="hidden" name="mark_id"
               value="<?= (int)$mark['mark_id'] ?>">

        <button type="submit" class="btn btn-sm btn-danger">
            Delete
        </button>
    </form>
</td>

                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="9"
                                class="text-center text-muted py-4">
                                No marks records found.
                                Add your first marks record.
                            </td>
                        </tr>

                    <?php endif; ?>

                    </tbody>

                </table>

            </div>

            <a href="../admin/index.php">Back to Dashboard</a>

        </div>
    </div>

</div>

</body>
</html>
