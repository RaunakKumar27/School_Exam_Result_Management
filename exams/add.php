
<?php
require_once __DIR__ . '/../config/database.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $examName = trim($_POST["exam_name"] ?? "");
    $examDate = $_POST["exam_date"] ?? "";
    $academicYear = trim($_POST["academic_year"] ?? "");

    if ($examName === "" || $examDate === "" || $academicYear === "") {
        $error = "Please fill in all required fields.";
    } else {
        $date = DateTime::createFromFormat('Y-m-d', $examDate);

        if (!$date || $date->format('Y-m-d') !== $examDate) {
            $error = "Please enter a valid examination date.";
        } else {
            try {
                $stmt = $pdo->prepare(
                    "INSERT INTO Exams (exam_name, exam_date, academic_year)
                     VALUES (?, ?, ?)"
                );

                $stmt->execute([
                    $examName,
                    $examDate,
                    $academicYear
                ]);

                header("Location: index.php?success=added");
                exit;

            } catch (PDOException $e) {
                $error = "Unable to save exam. Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Exam | XYZ College</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>

<body class="bg-light">

<div class="container py-5">

    <h2>Add New Exam</h2>
    <p class="text-muted">Enter examination details below.</p>

    <div class="card shadow-sm">
        <div class="card-body p-4">

            <?php if ($error !== ""): ?>
                <div class="alert alert-danger">
                    <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php endif; ?>

            <form method="POST">

                <div class="mb-3">
                    <label class="form-label">Exam Name *</label>
                    <input type="text"
                           name="exam_name"
                           class="form-control"
                           maxlength="100"
                           placeholder="Example: Mid Term Examination"
                           value="<?= htmlspecialchars($_POST['exam_name'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Exam Date *</label>
                    <input type="date"
                           name="exam_date"
                           class="form-control"
                           value="<?= htmlspecialchars($_POST['exam_date'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                           required>
                </div>

                <div class="mb-4">
                    <label class="form-label">Academic Year *</label>
                    <input type="text"
                           name="academic_year"
                           class="form-control"
                           maxlength="20"
                           placeholder="Example: 2026-2027"
                           value="<?= htmlspecialchars($_POST['academic_year'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                           required>
                </div>

                <button type="submit" class="btn btn-primary">
                    Save Exam
                </button>

                <a href="index.php" class="btn btn-secondary">
                    Cancel
                </a>

            </form>

        </div>
    </div>

</div>

</body>
</html>
