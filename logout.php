<?php
session_start();
session_destroy(); // уничтожение сессии
header('Location: 2.php');
exit;