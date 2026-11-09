
<?php
require_once __DIR__ . '/../config/database.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST["subject_name"] ?? "");
    $code = strtoupper(trim($_POST["subject_code"] ?? ""));
    $maxMarks = $_POST["max_marks"] ?? "";

    if ($name === "" || $code === "" || $maxMarks === "") {
        $error = "Please fill in all required fields.";
    } elseif (!ctype_digit((string)$maxMarks) || (int)$maxMarks < 1) {
        $error = "Maximum marks must be a positive whole number.";
    } else {
        try {
            $stmt = $pdo->prepare(
                "INSERT INTO Subjects (subject_name, subject_code, max_marks)
                 VALUES (?, ?, ?)"
            );

            $stmt->execute([$name, $code, (int)$maxMarks]);

            header("Location: index.php?success=added");
            exit;

        } catch (PDOException $e) {
            if ($e->getCode() === "23000") {
                $error = "This subject code already exists.";
            } else {
                $error = "Unable to save subject. Please try again.";
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
    <title>Add Subject | XYZ College</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>

<body class="bg-light">

<div class="container py-5">

    <h2>Add New Subject</h2>
    <p class="text-muted">Enter subject information below.</p>

    <div class="card shadow-sm">
        <div class="card-body p-4">

            <?php if ($error !== ""): ?>
                <div class="alert alert-danger">
                    <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php endif; ?>

            <form method="POST">

                <div class="mb-3">
                    <label class="form-label">Subject Name *</label>
                    <input type="text"
                           name="subject_name"
                           class="form-control"
                           maxlength="100"
                           placeholder="Example: Mathematics"
                           value="<?= htmlspecialchars($_POST['subject_name'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Subject Code *</label>
                    <input type="text"
                           name="subject_code"
                           class="form-control"
                           maxlength="20"
                           placeholder="Example: MATH101"
                           value="<?= htmlspecialchars($_POST['subject_code'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                           required>
                </div>

                <div class="mb-4">
                    <label class="form-label">Maximum Marks *</label>
                    <input type="number"
                           name="max_marks"
                           class="form-control"
                           min="1"
                           value="<?= htmlspecialchars($_POST['max_marks'] ?? '100', ENT_QUOTES, 'UTF-8') ?>"
                           required>
                </div>

                <button type="submit" class="btn btn-primary">
                    Save Subject
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
