
<?php
require_once __DIR__ . '/../config/database.php';

$id = $_GET['id'] ?? '';

if (!ctype_digit((string)$id) || (int)$id < 1) {
    die("Invalid subject ID.");
}

$id = (int)$id;

$stmt = $pdo->prepare("SELECT * FROM Subjects WHERE subject_id = ?");
$stmt->execute([$id]);
$subject = $stmt->fetch();

if (!$subject) {
    die("Subject not found.");
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST["subject_name"] ?? "");
    $code = strtoupper(trim($_POST["subject_code"] ?? ""));
    $maxMarks = $_POST["max_marks"] ?? "";

    if ($name === "" || $code === "" || $maxMarks === "") {
        $error = "Please fill in all fields.";
    } elseif (!ctype_digit((string)$maxMarks) || (int)$maxMarks < 1) {
        $error = "Maximum marks must be a positive whole number.";
    } else {
        try {
            $stmt = $pdo->prepare(
                "UPDATE Subjects
                 SET subject_name = ?, subject_code = ?, max_marks = ?
                 WHERE subject_id = ?"
            );

            $stmt->execute([$name, $code, (int)$maxMarks, $id]);

            header("Location: index.php?success=updated");
            exit;

        } catch (PDOException $e) {
            $error = $e->getCode() === "23000"
                ? "This subject code already exists."
                : "Unable to update subject.";
        }
    }

    $subject['subject_name'] = $name;
    $subject['subject_code'] = $code;
    $subject['max_marks'] = $maxMarks;
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
    <title>Edit Subject | XYZ College</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>

<body class="bg-light">

<div class="container py-5">

    <h2>Edit Subject</h2>
    <p class="text-muted">Update subject information.</p>

    <div class="card shadow-sm">
        <div class="card-body p-4">

            <?php if ($error !== ""): ?>
                <div class="alert alert-danger"><?= e($error) ?></div>
            <?php endif; ?>

            <form method="POST">

                <div class="mb-3">
                    <label class="form-label">Subject Name</label>
                    <input type="text" name="subject_name"
                           class="form-control" maxlength="100"
                           value="<?= e($subject['subject_name']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Subject Code</label>
                    <input type="text" name="subject_code"
                           class="form-control" maxlength="20"
                           value="<?= e($subject['subject_code']) ?>" required>
                </div>

                <div class="mb-4">
                    <label class="form-label">Maximum Marks</label>
                    <input type="number" name="max_marks"
                           class="form-control" min="1"
                           value="<?= e($subject['max_marks']) ?>" required>
                </div>

                <button type="submit" class="btn btn-primary">
                    Update Subject
                </button>

                <a href="index.php" class="btn btn-secondary">Cancel</a>

            </form>

        </div>
    </div>

</div>

</body>
</html>
