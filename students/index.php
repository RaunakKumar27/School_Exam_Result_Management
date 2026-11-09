
<?php
require_once __DIR__ . '/../config/database.php';

// Fetch all students
$stmt = $pdo->query(
    "SELECT * FROM Students ORDER BY student_id DESC"
);
$students = $stmt->fetchAll();

// Securely display text
function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Students | XYZ College</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>

<body class="bg-light">

<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Manage Students</h2>
            <p class="text-muted">View and manage student records.</p>
        </div>

        <a href="add.php" class="btn btn-primary">
            + Add Student
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-bordered table-hover">

                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Student Name</th>
                            <th>Class</th>
                            <th>Roll Number</th>
                            <th>Gender</th>
                            <th>Date of Birth</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php if (count($students) > 0): ?>

                        <?php foreach ($students as $student): ?>
                            <tr>
                                <td><?= e($student['student_id']) ?></td>
                                <td><?= e($student['student_name']) ?></td>
                                <td><?= e($student['class_name']) ?></td>
                                <td><?= e($student['roll_number']) ?></td>
                                <td><?= e($student['gender']) ?></td>
                                <td><?= e($student['date_of_birth']) ?></td>
                                
<td>
    <a href="edit.php?id=<?= (int)$student['student_id'] ?>"
       class="btn btn-sm btn-primary">
        Edit
    </a>

    <form action="delete.php" method="POST" class="d-inline"
          onsubmit="return confirm('Are you sure you want to delete this student?');">

        <input type="hidden" name="student_id"
               value="<?= (int)$student['student_id'] ?>">

        <button type="submit" class="btn btn-sm btn-danger">
            Delete
        </button>
    </form>
</td>

                            </tr>
                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                No students found. Add your first student.
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
