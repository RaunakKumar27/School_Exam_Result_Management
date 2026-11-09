
<?php
require_once __DIR__ . '/../config/database.php';

$error = "";
$id = $_GET['id'] ?? '';

if (!ctype_digit((string)$id) || (int)$id < 1) {
    die("Invalid student ID. Please provide a valid student ID.");
}

$id = (int)$id ;


// Fetch existing student
$stmt = $pdo->prepare("SELECT * FROM Students WHERE student_id = ?");
$stmt->execute([$id]);
$student = $stmt->fetch();

if (!$student) {
    die("Student not found.");
}

// Update student when form is submitted
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
            $sql = "UPDATE Students
                    SET student_name = ?,
                        class_name = ?,
                        roll_number = ?,
                        gender = ?,
                        date_of_birth = ?
                    WHERE student_id = ?";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $name,
                $class,
                (int)$roll,
                $gender !== "" ? $gender : null,
                $dob !== "" ? $dob : null,
                $id
            ]);

            header("Location: index.php?success=updated");
            exit;

        } catch (PDOException $e) {
            if ($e->getCode() === "23000") {
                $error = "This roll number already exists in this class.";
            } else {
                $error = "Unable to update student. Please try again.";
            }
        }
    }

    // Keep submitted values in the form
    $student["student_name"] = $name;
    $student["class_name"] = $class;
    $student["roll_number"] = $roll;
    $student["gender"] = $gender;
    $student["date_of_birth"] = $dob;
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
    <title>Edit Student | XYZ College</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>

<body class="bg-light">

<div class="container py-5">

    <h2>Edit Student</h2>
    <p class="text-muted">Update the student's information.</p>

    <div class="card shadow-sm">
        <div class="card-body p-4">

            <?php if ($error !== ""): ?>
                <div class="alert alert-danger"><?= e($error) ?></div>
            <?php endif; ?>

            <form method="POST">

                <div class="mb-3">
                    <label class="form-label">Student Name *</label>
                    <input type="text" name="student_name"
                           class="form-control" maxlength="100"
                           value="<?= e($student['student_name']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Class *</label>
                    <input type="text" name="class_name"
                           class="form-control" maxlength="20"
                           value="<?= e($student['class_name']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Roll Number *</label>
                    <input type="number" name="roll_number"
                           class="form-control" min="1"
                           value="<?= e($student['roll_number']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Gender</label>
                    <select name="gender" class="form-select">
                        <option value="">Select Gender</option>
                        <?php foreach (["Male", "Female", "Other"] as $option): ?>
                            <option value="<?= e($option) ?>"
                                <?= $student['gender'] === $option ? 'selected' : '' ?>>
                                <?= e($option) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="form-label">Date of Birth</label>
                    <input type="date" name="date_of_birth"
                           class="form-control"
                           value="<?= e($student['date_of_birth']) ?>">
                </div>

                <button type="submit" class="btn btn-primary">
                    Update Student
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
