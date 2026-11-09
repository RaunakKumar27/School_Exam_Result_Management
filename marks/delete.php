
<?php
require_once __DIR__ . '/../config/database.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php");
    exit;
}

$id = $_POST['mark_id'] ?? '';

if (!ctype_digit((string)$id) || (int)$id < 1) {
    header("Location: index.php?error=invalid");
    exit;
}

$id = (int)$id;

try {
    $stmt = $pdo->prepare(
        "DELETE FROM Marks WHERE mark_id = ?"
    );

    $stmt->execute([$id]);

    header("Location: index.php?success=deleted");
    exit;

} catch (PDOException $e) {
    header("Location: index.php?error=failed");
    exit;
}
?>
