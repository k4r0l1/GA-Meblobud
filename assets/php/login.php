<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/../private/config.php';

if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
    $_SESSION['lockout_time'] = 0;
}

if (!isset($_SESSION['captcha_answer'])) {
    $num1 = rand(1, 10);
    $num2 = rand(1, 10);
    $_SESSION['captcha_answer'] = $num1 + $num2;
    $_SESSION['captcha_question'] = "Ile wynosi $num1 + $num2?";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_SESSION['login_attempts'] >= 3 && time() - $_SESSION['lockout_time'] < 300) {
        $error = "Zbyt wiele prób logowania. Spróbuj ponownie za " . (300 - (time() - $_SESSION['lockout_time'])) . " sekund.";
    } else {
        $inputPassword = $_POST["password"] ?? '';
        $captchaInput = $_POST["captcha"] ?? '';

        if ((int)$captchaInput !== $_SESSION['captcha_answer']) {
            $error = "Nieprawidłowa odpowiedź CAPTCHA.";
            $_SESSION['login_attempts']++;
            if ($_SESSION['login_attempts'] >= 3) {
                $_SESSION['lockout_time'] = time();
            }
        } elseif (password_verify($inputPassword, ADMIN_PASSWORD_HASH)) {
            $_SESSION["loggedin"] = true;
            $_SESSION['login_attempts'] = 0; 
            unset($_SESSION['captcha_answer']); 
            unset($_SESSION['captcha_question']);
            header("Location: /admin.php");
            exit();
        } else {
            $error = "Nieprawidłowe hasło.";
            $_SESSION['login_attempts']++;
            if ($_SESSION['login_attempts'] >= 3) {
                $_SESSION['lockout_time'] = time();
            }
        }
        $num1 = rand(1, 10);
        $num2 = rand(1, 10);
        $_SESSION['captcha_answer'] = $num1 + $num2;
        $_SESSION['captcha_question'] = "Ile wynosi $num1 + $num2?";
    }
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Logowanie</title>
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/css/styles.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Logowanie</h2>
        <form method="POST">
            <div class="mb-3">
                <label for="password" class="form-label">Hasło:</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="captcha" class="form-label"><?php echo $_SESSION['captcha_question']; ?></label>
                <input type="number" name="captcha" id="captcha" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Zaloguj</button>
            <?php if (isset($error)) echo "<p class='text-danger'>$error</p>"; ?>
        </form>
    </div>
</body>
</html>