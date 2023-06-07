<?php
session_start();
require('connect.php'); // файл с подключением к базе данных

// Проверка, авторизован ли пользователь
if (!$_SESSION['authorized']) {
    header('Location: profile.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
// Обновление личной информации
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $new_password = $_POST['new_password'];
    $confirm_new_password = $_POST['confirm_new_password'];
//Получаем id пользователя из сессии
    $user_id = $_SESSION['id'];

//Получение пользователя
    $query = "SELECT id FROM users WHERE id='$user_id'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
        // Если пользователь с такой почтой или телефоном уже есть в базе
        echo 'Пользователь с такой почтой или телефоном уже зарегистрирован';
        exit;
    }

// Проверка на уникальность почты и телефона (кроме текущего пользователя)
    $query = "SELECT * FROM users WHERE (email='$email' OR phone='$phone' OR name='$name') AND id != '$user_id'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
        // Если пользователь с такой почтой или телефоном уже есть в базе
        echo 'Пользователь с такой почтой или телефоном уже зарегистрирован';
        exit;
    }

    global $new_password_hash;
// Проверка совпадения новых паролей
    if (!empty($new_password)) {
        if ($new_password !== $confirm_new_password) {
            echo 'Новые пароли не совпадают';
            exit;
        }

        // Хэширование нового пароля
        $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);

        // Обновление данных в базе
        $query = "UPDATE users SET name='$name', phone='$phone', email='$email', password='$new_password_hash' WHERE id = '$user_id'";
        if (mysqli_query($conn, $query)) {
            //Обновляем данные в сессии
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
}

// Вывод личной информации
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Профиль</title>
    <link rel="stylesheet" href="assets/css/main.css">
</head>
<body>
<form action="profile.php" method="POST">
    <label for="name">Имя:</label>
    <input type="text" name="name" id="name" value="<?php echo $_SESSION['name'] ?>" required>
    <br><br>
    <label for="phone">Телефон:</label>
    <input type="text" name="phone" id="phone" value="<?php echo $_SESSION['phone'] ?>"
           pattern="[789][0-9]{10}" required>
    <br><br>
    <label for="email">Email:</label>
    <input type="email" name="email" id="email" value="<?php echo $_SESSION['email'] ?>" required>
    <br><br>
    <!--    <label for="password">Старый пароль:</label>-->
    <!--    <input type="password" name="password" id="password" required>-->
    <!--    <br><br>-->
    <label for="new_password">Новый пароль:</label>
    <input type="password" name="new_password" id="new_password">
    <br><br>
    <label for="confirm_new_password">Повторите новый пароль:</label>
    <input type="password" name="confirm_new_password" id="confirm_new_password">
    <br><br>
    <input type="submit" value="Сохранить изменения">
</form>
</body>
</html>