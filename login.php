<?php
include 'API_KEY.php';
session_start();
require('connect.php'); // файл с подключением к базе данных

function check_captcha($token) {
    $ch = curl_init();
    $args = http_build_query([
        "secret" => SMARTCAPTCHA_SERVER_KEY,
        "token" => $token,
        "ip" => $_SERVER['REMOTE_ADDR'],
    ]);
    curl_setopt($ch, CURLOPT_URL, "https://smartcaptcha.yandexcloud.net/validate?$args");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 1);

    $server_output = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpcode !== 200) {
        echo "Allow access due to an error: code=$httpcode; message=$server_output\n";
        return true;
    }
    $resp = json_decode($server_output);
    return $resp->status === "ok";
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['smart-token'];
    if (!check_captcha($token))
    {
        $_SESSION['error'] = 'Robot!';
        header('Location: ../');
        exit;
    }

    $login = $_POST['login'];
    $password = $_POST['password'];

    // Поиск пользователя в базе
    $query = "SELECT * FROM users WHERE email='$login' OR phone='$login'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        //Хеширование полученного пароля
        $password_hash = md5($password);
        // Проверка пароля
        if ($password_hash == $row['password']) {
            $_SESSION['authorized'] = true;
            $_SESSION['id'] = $row['id'];
            $_SESSION['name'] = $row['name'];
            $_SESSION['phone'] = $row['phone'];
            $_SESSION['email'] = $row['email'];
            header('Location: profile.php');
            exit;
        } else {
            echo 'Неверный пароль';
            exit;
        }
    } else {
        echo 'Пользователь с такой почтой или телефоном не найден';
        exit;
    }
}