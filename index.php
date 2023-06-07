<?php
include 'API_KEY.php';
session_start();
if (isset($_SESSION['user'])) {
    header('Location: profile.php');
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Quest</title>
    <link rel="stylesheet" href="assets/css/main.css">
    <script src="https://smartcaptcha.yandexcloud.net/captcha.js" defer></script>
</head>
<body>
<form action="login.php" method="POST">
    <h2>Войдите в свой аккаунт:</h2>
    <label for="login">Email или телефон:</label>
    <input type="text" name="login" id="login" required>
    <br><br>
    <label for="password">Пароль:</label>
    <input type="password" name="password" id="password" required>
    <br><br>
    <input type="submit" value="Войти">
    <br><br>
    <div id="captcha-container"
         class="smart-captcha"

         data-sitekey="<?php echo DATA_SITEKEY ?>"
    >
        <input type="hidden" name="smart-token" value="smart-token">
    </div>
    <p>
        У вас нет аккаунта? - <a href="/register.php">Зарегистрируйтесь</a>!
    </p>
    <?php
    if (isset($_SESSION['error'])) {
        echo '<p class="error"> ' . $_SESSION['error'] . ' </p>';
    }
    unset($_SESSION['error']);
    ?>
</form>
</body>
</html>