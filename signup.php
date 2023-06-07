<?php
session_start();
require('connect.php'); // файл с подключением к базе данных

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Проверка на уникальность почты, логина и телефона
    $query = "SELECT * FROM users WHERE email='$email' OR phone='$phone' OR name='$name'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
        // Если пользователь с такой почтой, логином или телефоном уже есть в базе
        echo 'Пользователь с такой почтой, именем или телефоном уже зарегистрирован';
        exit;
    }

    // Проверка совпадения паролей
    if ($password !== $confirm_password) {
        $_SESSION['error'] = 'Пароли не совпадают!';
        header('Location: ../register.php');
        exit;
    }

    // Хэширование пароля
    $password_hash = md5($password);

    // Сохранение данных в базу
    $query = "INSERT INTO users (name, phone, email, password) VALUES ('$name', '$phone', '$email', '$password_hash')";
    //Заносим данные в сессию
    if (mysqli_query($conn, $query)) {
        //id записи при запросе insert
        $lastInsertedId = mysqli_insert_id($conn);
        $_SESSION['id'] = $lastInsertedId;
        $_SESSION['authorized'] = true;
        $_SESSION['name'] = $name;
        $_SESSION['phone'] = $phone;
        $_SESSION['email'] = $email;
        header('Location: profile.php');
        exit;
    } else {
        echo 'Ошибка: ' . mysqli_error($conn);
        exit;
    }
}


