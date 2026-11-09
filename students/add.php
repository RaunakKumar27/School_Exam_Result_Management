
<?php
require_once __DIR__ . '/../config/database.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST["student_name"] ?? "");
    $class = trim($_POST["class_name"] ?? "");
    $roll = trim($_POST["roll_number"] ?? "");
    $gender = $_POST["gender"] ?? "";
    $dob = $_POST["date_of_birth"] ?? "";

    if ($name === "" || $class === "" || $roll === "") {
        $error = "Please fill in all required fields.";
    } elseif (!ctype_digit($roll) || (int)$roll < 1) {
        $error = "Roll number must be a positive whole number.";
    } elseif (!in_array($gender, ["Male", "Female", "Other", ""], true)) {
        $error = "Please select a valid gender.";
    } else {
        try {
            $sql = "INSERT INTO Students
                    (student_name, class_name, roll_number, gender, date_of_birth)
                    VALUES (?, ?, ?, ?, ?)";

            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                $name,
                $class,
                (int)$roll,
                $gender !== "" ? $gender : null,
                $dob !== "" ? $dob : null
            ]);

            header("Location: index.php?success=added");
            exit;

        } catch (PDOException $e) {
            if ($e->getCode() === "23000") {
                $error = "This roll number already exists in this class.";
            } else {
                $error = "Unable to save student. Please try again.";
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
    <title>Add Student | XYZ College</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>

<body class="bg-light">

<div class="container py-5">

    <div class="mb-4">
        <h2>Add New Student</h2>
        <p class="text-muted">Enter the student's information below.</p>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-4">

            <?php if ($error !== ""): ?>
                <div class="alert alert-danger">
                    <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php endif; ?>

            <form method="POST">

                <div class="mb-3">
                    <label class="form-label">Student Name *</label>
                    <input type="text"
                           name="student_name"
                           class="form-control"
                           maxlength="100"
                           value="<?= htmlspecialchars($_POST['student_name'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Class *</label>
                    <input type="text"
                           name="class_name"
                           class="form-control"
                           placeholder="Example: 10A"
                           maxlength="20"
                           value="<?= htmlspecialchars($_POST['class_name'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Roll Number *</label>
                    <input type="number"
                           name="roll_number"
                           class="form-control"
                           min="1"
                           value="<?= htmlspecialchars($_POST['roll_number'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Gender</label>
                    <select name="gender" class="form-select">
                        <option value="">Select Gender</option>
                        <?php foreach (["Male", "Female", "Other"] as $option): ?>
                            <option value="<?= $option ?>"
                                <?= (($_POST['gender'] ?? '') === $option) ? 'selected' : '' ?>>
                                <?= $option ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="form-label">Date of Birth</label>
                    <input type="date"
                           name="date_of_birth"
                           class="form-control"
                           value="<?= htmlspecialchars($_POST['date_of_birth'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                </div>

                <button type="submit" class="btn btn-primary">
                    Save Student
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
