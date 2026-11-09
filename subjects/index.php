
<?php
require_once __DIR__ . '/../config/database.php';

$stmt = $pdo->query("SELECT * FROM Subjects ORDER BY subject_id DESC");
$subjects = $stmt->fetchAll();

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Subjects | XYZ College</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>

<body class="bg-light">

<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Manage Subjects</h2>
            <p class="text-muted">View and manage examination subjects.</p>
        </div>

        <a href="add.php" class="btn btn-primary">+ Add Subject</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-bordered table-hover">

                    <thead class="table-dark">
                        <tr>
                            <th>Subject ID</th>
                            <th>Subject Name</th>
                            <th>Subject Code</th>
                            <th>Maximum Marks</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                    <?php if (count($subjects) > 0): ?>

                        <?php foreach ($subjects as $subject): ?>
                            <tr>
                                <td><?= e($subject['subject_id']) ?></td>
                                <td><?= e($subject['subject_name']) ?></td>
                                <td><?= e($subject['subject_code']) ?></td>
                                <td><?= e($subject['max_marks']) ?></td>
                                
                                <td>
    <a href="edit.php?id=<?= (int)$subject['subject_id'] ?>"
       class="btn btn-sm btn-primary">
        Edit
    </a>

    <form action="delete.php" method="POST" class="d-inline"
          onsubmit="return confirm('Are you sure you want to delete this subject?');">

        <input type="hidden" name="subject_id"
               value="<?= (int)$subject['subject_id'] ?>">

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
                                No subjects found. Add your first subject.
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
