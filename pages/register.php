<?php
    require __DIR__ . '/../helpers/auth_helpers.php';
    
    generate_csrf_in_session();

    $errors = $_SESSION['form_errors'] ?? [];
    $inputs = $_SESSION['form_inputs'] ?? [];
    unset($_SESSION['form_errors'], $_SESSION['form_inputs']);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация</title>
    <link rel="stylesheet" href="../styles/styles.css">
    <script src="../js/checkbox_toggler.js"></script>
</head>
<body>
    <h1>Регистрация</h1>
    
    <form method="post" action="../services/validate_registration.php">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
        
        <div class="form-group">
            <label for="username">Потребителско име:</label>
            <input type="text" id="username" name="username" 
                   value="<?= htmlspecialchars($inputs['username'] ?? '') ?>"
                   class="<?= isset($errors['username']) ? 'error-border' : '' ?>">
            <?php if (isset($errors['username'])): ?>
                <div class="error"><?= htmlspecialchars($errors['username']) ?></div>
            <?php endif; ?>
        </div>
        
        <div class="form-group">
            <label for="email">Имейл:</label>
            <input type="email" id="email" name="email" 
                   value="<?= htmlspecialchars($inputs['email'] ?? '') ?>"
                   class="<?= isset($errors['email']) ? 'error-border' : '' ?>">
            <?php if (isset($errors['email'])): ?>
                <div class="error"><?= htmlspecialchars($errors['email']) ?></div>
            <?php endif; ?>
        </div>
        
        <div class="form-group">
            <label for="password">Парола:</label>
            <input type="password" id="password" name="password"
                   class="<?= isset($errors['password']) ? 'error-border' : '' ?>">
            <?php if (isset($errors['password'])): ?>
                <div class="error"><?= htmlspecialchars($errors['password']) ?></div>
            <?php endif; ?>
        </div>
        
        <div class="form-group">
            <label for="password_confirm">Потвърди парола:</label>
            <input type="password" id="password_confirm" name="password_confirm"
                   class="<?= isset($errors['password_confirm']) ? 'error-border' : '' ?>">
            <?php if (isset($errors['password_confirm'])): ?>
                <div class="error"><?= htmlspecialchars($errors['password_confirm']) ?></div>
            <?php endif; ?>
        </div>
        
        <div class="form-group">
            <label>Роля:</label>
            <div>
                <label>
                    <input type="checkbox" id="role-student" name="roles[]" value="student"
                        <?= (isset($inputs['roles']) && in_array('student', $inputs['roles'])) ? 'checked' : '' ?>
                        onchange="toggleAdminCheckbox(this)">
                    Студент
                </label>
                <label>
                    <input type="checkbox" id="role-teacher" name="roles[]" value="teacher"
                        <?= (isset($inputs['roles']) && in_array('teacher', $inputs['roles'])) ? 'checked' : '' ?>
                        onchange="toggleAdminCheckbox(this)">
                    Преподавател
                </label>
                <label>
                    <input type="checkbox" id="role-admin" name="roles[]" value="admin"
                        <?= (isset($inputs['roles']) && in_array('admin', $inputs['roles'])) ? 'checked' : '' ?>
                        onchange="toggleOtherCheckboxes(this)">
                    Админ
                </label>
            </div>
            <?php if (isset($errors['roles'])): ?>
                <div class="error"><?= htmlspecialchars($errors['roles']) ?></div>
            <?php endif; ?>
        </div>

        <button type="submit">Регистрирай се</button>
    </form>
    
    <?php if (isset($errors['final'])): ?>
        <div class="error"><?= htmlspecialchars($errors['final']) ?></div>
    <?php endif; ?>

    <p>Имаш акаунт? <a href="login.php">Влез тук</a></p>
</body>
</html>