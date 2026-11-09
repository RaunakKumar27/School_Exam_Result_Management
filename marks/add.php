
<?php
require_once __DIR__ . '/../config/database.php';

$error = "";

// Fetch dropdown data
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

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $studentId = $_POST["student_id"] ?? "";
    $subjectId = $_POST["subject_id"] ?? "";
    $examId = $_POST["exam_id"] ?? "";
    $marks = trim($_POST["marks_obtained"] ?? "");

    if ($studentId === "" || $subjectId === "" ||
        $examId === "" || $marks === "") {

        $error = "Please fill in all required fields.";

    } elseif (
        !ctype_digit((string)$studentId) ||
        !ctype_digit((string)$subjectId) ||
        !ctype_digit((string)$examId)
    ) {
        $error = "Please select valid student, subject and exam.";

    } elseif (!is_numeric($marks) || !is_finite((float)$marks) || (float)$marks < 0) {
        $error = "Please enter valid marks.";

    } else {

        // Check selected student, subject and exam
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
            $error = "Invalid student, subject or exam selected.";

        } elseif ((float)$marks > (float)$subject['max_marks']) {
            $error = "Marks cannot exceed maximum marks (" .
                     $subject['max_marks'] . ").";

        } else {

            try {
                $stmt = $pdo->prepare(
                    "INSERT INTO Marks
                     (student_id, subject_id, exam_id, marks_obtained)
                     VALUES (?, ?, ?, ?)"
                );

                $stmt->execute([
                    (int)$studentId,
                    (int)$subjectId,
                    (int)$examId,
                    $marks
                ]);

                header("Location: index.php?success=added");
                exit;

            } catch (PDOException $e) {
                if ($e->getCode() === "23000") {
                    $error = "Marks for this student, subject and exam already exist.";
                } else {
                    $error = "Unable to save marks. Please try again.";
                }
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

    <title>Add Marks | XYZ College</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>

<body class="bg-light">

<div class="container py-5">

    <h2>Add Student Marks</h2>
    <p class="text-muted">
        Enter the examination marks for a student.
    </p>

    <div class="card shadow-sm">
        <div class="card-body p-4">

            <?php if ($error !== ""): ?>
                <div class="alert alert-danger">
                    <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php endif; ?>

            <?php if (empty($students) || empty($subjects) || empty($exams)): ?>

                <div class="alert alert-warning">
                    Please add at least one student, subject and exam
                    before entering marks.
                </div>

            <?php else: ?>

                <form method="POST">

                    <div class="mb-3">
                        <label class="form-label">Select Student *</label>

                        <select name="student_id" class="form-select" required>
                            <option value="">Choose Student</option>

                            <?php foreach ($students as $student): ?>
                                <option value="<?= (int)$student['student_id'] ?>"
                                    <?= (($_POST['student_id'] ?? '') == $student['student_id']) ? 'selected' : '' ?>>

                                    <?= htmlspecialchars(
                                        $student['student_name'] . ' - Class ' .
                                        $student['class_name'] . ' - Roll ' .
                                        $student['roll_number'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>

                                </option>
                            <?php endforeach; ?>

                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Select Subject *</label>

                        <select name="subject_id" class="form-select" required>
                            <option value="">Choose Subject</option>

                            <?php foreach ($subjects as $subject): ?>
                                <option value="<?= (int)$subject['subject_id'] ?>"
                                    <?= (($_POST['subject_id'] ?? '') == $subject['subject_id']) ? 'selected' : '' ?>>

                                    <?= htmlspecialchars(
                                        $subject['subject_name'] .
                                        ' (Maximum: ' . $subject['max_marks'] . ')',
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>

                                </option>
                            <?php endforeach; ?>

                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Select Examination *</label>

                        <select name="exam_id" class="form-select" required>
                            <option value="">Choose Examination</option>

                            <?php foreach ($exams as $exam): ?>
                                <option value="<?= (int)$exam['exam_id'] ?>"
                                    <?= (($_POST['exam_id'] ?? '') == $exam['exam_id']) ? 'selected' : '' ?>>

                                    <?= htmlspecialchars(
                                        $exam['exam_name'] . ' (' .
                                        $exam['academic_year'] . ')',
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>

                                </option>
                            <?php endforeach; ?>

                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Marks Obtained *</label>

                        <input type="number"
                               name="marks_obtained"
                               class="form-control"
                               min="0"
                               step="0.01"
                               value="<?= htmlspecialchars($_POST['marks_obtained'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                               placeholder="Example: 85"
                               required>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        Save Marks
                    </button>

                    <a href="index.php" class="btn btn-secondary">
                        Cancel
                    </a>

                </form>

            <?php endif; ?>

        </div>
    </div>

</div>

</body>
</html>
