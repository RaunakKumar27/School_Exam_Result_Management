
<?php
require_once __DIR__ . '/../config/database.php';

$id = $_GET['id'] ?? '';

if (!ctype_digit((string)$id) || (int)$id < 1) {
    die("Invalid marks ID.");
}

$id = (int)$id;

$stmt = $pdo->prepare("SELECT * FROM Marks WHERE mark_id = ?");
$stmt->execute([$id]);
$mark = $stmt->fetch();

if (!$mark) {
    die("Marks record not found.");
}

$students = $pdo->query(
    "SELECT student_id, student_name, class_name, roll_number
     FROM Students ORDER BY student_name"
)->fetchAll();

$subjects = $pdo->query(
    "SELECT subject_id, subject_name, max_marks
     FROM Subjects ORDER BY subject_name"
)->fetchAll();

$exams = $pdo->query(
    "SELECT exam_id, exam_name, academic_year
     FROM Exams ORDER BY exam_date DESC"
)->fetchAll();

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $studentId = $_POST["student_id"] ?? "";
    $subjectId = $_POST["subject_id"] ?? "";
    $examId = $_POST["exam_id"] ?? "";
    $marks = trim($_POST["marks_obtained"] ?? "");

    if (
        !ctype_digit((string)$studentId) ||
        !ctype_digit((string)$subjectId) ||
        !ctype_digit((string)$examId)
    ) {
        $error = "Please select valid student, subject and exam.";

    } elseif (
        $marks === "" ||
        !is_numeric($marks) ||
        !is_finite((float)$marks) ||
        (float)$marks < 0
    ) {
        $error = "Please enter valid marks.";

    } else {

        $stmt = $pdo->prepare(
            "SELECT max_marks FROM Subjects WHERE subject_id = ?"
        );
        $stmt->execute([(int)$subjectId]);
        $subject = $stmt->fetch();

        $stmt = $pdo->prepare(
            "SELECT COUNT(*) FROM Students WHERE student_id = ?"
        );
        $stmt->execute([(int)$studentId]);
        $studentExists = $stmt->fetchColumn();

        $stmt = $pdo->prepare(
            "SELECT COUNT(*) FROM Exams WHERE exam_id = ?"
        );
        $stmt->execute([(int)$examId]);
        $examExists = $stmt->fetchColumn();

        if (!$subject || !$studentExists || !$examExists) {
            $error = "Invalid student, subject or exam.";

        } elseif ((float)$marks > (float)$subject['max_marks']) {
            $error = "Marks cannot exceed maximum marks (" .
                     $subject['max_marks'] . ").";

        } else {
            try {
                $stmt = $pdo->prepare(
                    "UPDATE Marks
                     SET student_id = ?,
                         subject_id = ?,
                         exam_id = ?,
                         marks_obtained = ?
                     WHERE mark_id = ?"
                );

                $stmt->execute([
                    (int)$studentId,
                    (int)$subjectId,
                    (int)$examId,
                    $marks,
                    $id
                ]);

                header("Location: index.php?success=updated");
                exit;

            } catch (PDOException $e) {
                $error = $e->getCode() === "23000"
                    ? "This student already has marks for this subject and exam."
                    : "Unable to update marks.";
            }
        }
    }

    $mark['student_id'] = $studentId;
    $mark['subject_id'] = $subjectId;
    $mark['exam_id'] = $examId;
    $mark['marks_obtained'] = $marks;
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
    <title>Edit Marks | XYZ College</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>

<body class="bg-light">

<div class="container py-5">

    <h2>Edit Student Marks</h2>
    <p class="text-muted">Update examination marks.</p>

    <div class="card shadow-sm">
        <div class="card-body p-4">

            <?php if ($error !== ""): ?>
                <div class="alert alert-danger"><?= e($error) ?></div>
            <?php endif; ?>

            <form method="POST">

                <div class="mb-3">
                    <label class="form-label">Student</label>
                    <select name="student_id" class="form-select" required>
                        <?php foreach ($students as $student): ?>
                            <option value="<?= (int)$student['student_id'] ?>"
                                <?= (string)$mark['student_id'] === (string)$student['student_id'] ? 'selected' : '' ?>>
                                <?= e($student['student_name'] . ' - ' .
                                    $student['class_name'] . ' - Roll ' .
                                    $student['roll_number']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Subject</label>
                    <select name="subject_id" class="form-select" required>
                        <?php foreach ($subjects as $subject): ?>
                            <option value="<?= (int)$subject['subject_id'] ?>"
                                <?= (string)$mark['subject_id'] === (string)$subject['subject_id'] ? 'selected' : '' ?>>
                                <?= e($subject['subject_name'] . ' (Maximum: ' .
                                    $subject['max_marks'] . ')') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Examination</label>
                    <select name="exam_id" class="form-select" required>
                        <?php foreach ($exams as $exam): ?>
                            <option value="<?= (int)$exam['exam_id'] ?>"
                                <?= (string)$mark['exam_id'] === (string)$exam['exam_id'] ? 'selected' : '' ?>>
                                <?= e($exam['exam_name'] . ' (' .
                                    $exam['academic_year'] . ')') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="form-label">Marks Obtained</label>
                    <input type="number"
                           name="marks_obtained"
                           class="form-control"
                           min="0"
                           step="0.01"
                           value="<?= e($mark['marks_obtained']) ?>"
                           required>
                </div>

                <button type="submit" class="btn btn-primary">
                    Update Marks
                </button>

                <a href="index.php" class="btn btn-secondary">Cancel</a>

            </form>

        </div>
    </div>

</div>

</body>
</html>
