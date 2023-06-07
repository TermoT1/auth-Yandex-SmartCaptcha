<?php
session_start();
if (isset($_SESSION['user'])) {
    header('Location: profile.php');
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Регистрация</title>
    <link rel="stylesheet" href="assets/css/main.css">
</head>
<body>



<form action="signup.php" method="post">
    <label>Имя</label>
    <input type="text" name="name" placeholder="Введите свое полное имя">
    <label>Телефон</label>
    <input type="text" name="phone" placeholder="Введите свой телефон" pattern="[789][0-9]{10}">
    <label>Почта</label>
    <input type="email" name="email" placeholder="Введите адрес своей почты">
    <label>Пароль</label>
    <input type="password" name="password" placeholder="Введите пароль">
    <label>Подтверждение пароля</label>
    <input type="password" name="confirm_password" placeholder="Подтвердите пароль">
    <button type="submit">Зарегистрироваться</button>
    <p>
        У вас уже есть аккаунт? - <a href="/">Авторизируйтесь</a>!
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