
<?php
require_once __DIR__ . '/../config/database.php';

$id = $_GET['id'] ?? '';

if (!ctype_digit((string)$id) || (int)$id < 1) {
    die("Invalid exam ID.");
}

$id = (int)$id;

$stmt = $pdo->prepare("SELECT * FROM Exams WHERE exam_id = ?");
$stmt->execute([$id]);
$exam = $stmt->fetch();

if (!$exam) {
    die("Exam not found.");
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST["exam_name"] ?? "");
    $date = $_POST["exam_date"] ?? "";
    $year = trim($_POST["academic_year"] ?? "");

    if ($name === "" || $date === "" || $year === "") {
        $error = "Please fill in all required fields.";
    } else {
        $parsedDate = DateTime::createFromFormat('Y-m-d', $date);

        if (!$parsedDate || $parsedDate->format('Y-m-d') !== $date) {
            $error = "Please enter a valid examination date.";
        } else {
            $stmt = $pdo->prepare(
                "UPDATE Exams
                 SET exam_name = ?, exam_date = ?, academic_year = ?
                 WHERE exam_id = ?"
            );

            $stmt->execute([$name, $date, $year, $id]);

            header("Location: index.php?success=updated");
            exit;
        }
    }

    $exam['exam_name'] = $name;
    $exam['exam_date'] = $date;
    $exam['academic_year'] = $year;
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
    <title>Edit Exam | XYZ College</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>

<body class="bg-light">

<div class="container py-5">

    <h2>Edit Examination</h2>
    <p class="text-muted">Update examination details.</p>

    <div class="card shadow-sm">
        <div class="card-body p-4">

            <?php if ($error !== ""): ?>
                <div class="alert alert-danger"><?= e($error) ?></div>
            <?php endif; ?>

            <form method="POST">

                <div class="mb-3">
                    <label class="form-label">Exam Name</label>
                    <input type="text" name="exam_name"
                           class="form-control" maxlength="100"
                           value="<?= e($exam['exam_name']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Exam Date</label>
                    <input type="date" name="exam_date"
                           class="form-control"
                           value="<?= e($exam['exam_date']) ?>" required>
                </div>

                <div class="mb-4">
                    <label class="form-label">Academic Year</label>
                    <input type="text" name="academic_year"
                           class="form-control" maxlength="20"
                           value="<?= e($exam['academic_year']) ?>" required>
                </div>

                <button type="submit" class="btn btn-primary">
                    Update Exam
                </button>

                <a href="index.php" class="btn btn-secondary">Cancel</a>

            </form>

        </div>
    </div>

</div>

</body>
</html>
