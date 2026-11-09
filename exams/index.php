
<?php
require_once __DIR__ . '/../config/database.php';

$stmt = $pdo->query("SELECT * FROM Exams ORDER BY exam_id DESC");
$exams = $stmt->fetchAll();

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Exams | XYZ College</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>

<body class="bg-light">

<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Manage Exams</h2>
            <p class="text-muted">View and manage examination records.</p>
        </div>

        <a href="add.php" class="btn btn-primary">+ Add Exam</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-bordered table-hover">

                    <thead class="table-dark">
                        <tr>
                            <th>Exam ID</th>
                            <th>Exam Name</th>
                            <th>Exam Date</th>
                            <th>Academic Year</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php if (count($exams) > 0): ?>

                        <?php foreach ($exams as $exam): ?>
                            <tr>
                                <td><?= e($exam['exam_id']) ?></td>
                                <td><?= e($exam['exam_name']) ?></td>
                                <td><?= e($exam['exam_date']) ?></td>
                                <td><?= e($exam['academic_year']) ?></td>
                                
                                <td>
    <a href="edit.php?id=<?= (int)$exam['exam_id'] ?>"
       class="btn btn-sm btn-primary">
        Edit
    </a>

    <form action="delete.php" method="POST" class="d-inline"
          onsubmit="return confirm('Are you sure you want to delete this exam?');">

        <input type="hidden" name="exam_id"
               value="<?= (int)$exam['exam_id'] ?>">

        <button type="submit" class="btn btn-sm btn-danger">
            Delete
        </button>
    </form>
</td>

                            </tr>
                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                No exams found. Add your first exam.
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
