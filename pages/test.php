<?php
    require __DIR__ . '/../database/db.php';
    require __DIR__ . '/../services/logout.php';
    require __DIR__ . '/../helpers/message_visualizer.php';

    check_auth_get(['id']);
    validate_user_roles(['student', 'admin']);

    $test_id = $_GET['id'];

    $questions = $pdo->prepare("SELECT * FROM questions WHERE test_id = ?");
    $questions->execute([$test_id]);

    if ($questions->rowCount() === 0) {
        header("Location: ../pages/main.php?message=error&error=bad_request");
        exit;
    }

    // Load previous form inputs if available
    $form_inputs = $_SESSION['form_inputs'] ?? [];
    unset($_SESSION['form_inputs']);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Попълване на тест</title>
    <link rel="stylesheet" href="../styles/styles.css">
    <script src="../js/form_warning.js"></script>
</head>
<body>
    <?php add_logout_button(); ?>
    <h2>Попълни теста</h2>
    <p><a href="main.php">← Начална страница</a></p>
    <?php visualize_message(); ?>

    <form method="post" action="../services/save_answers.php">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
        <input type="hidden" name="test_id" value="<?= $test_id ?>">
        <hr>

        <?php foreach ($questions as $q): ?>
            <p><strong><?= htmlspecialchars($q['question']) ?></strong></p>
            <?php if ($q['type'] == 'closed'): ?>
                <?php foreach (explode(',', $q['answers']) as $ans): ?>
                    <label>
                        <input type="radio" name="answers[<?= $q['id'] ?>]" value="<?= htmlspecialchars($ans) ?>" required
                            <?php if (($form_inputs['answers'][$q['id']] ?? '') === $ans) echo 'checked'; ?>>
                        <?= htmlspecialchars($ans) ?>
                    </label><br>
                <?php endforeach; ?>
            <?php else: ?>
                <textarea name="answers[<?= $q['id'] ?>]" rows="3" cols="40" required><?= htmlspecialchars($form_inputs['answers'][$q['id']] ?? '') ?></textarea>
            <?php endif; ?>
            <hr>
        <?php endforeach; ?>

        <button type="submit">Изпрати</button>
    </form>
</body>
</html>
