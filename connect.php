<?php
$conn = mysqli_connect('localhost', 'root', '', 'test');
if (!$conn) {
    die('Ошибка подключения: ' . mysqli_connect_error());
}