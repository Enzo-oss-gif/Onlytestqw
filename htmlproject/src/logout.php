<?php
session_start();
session_destroy(); // Удаляем сессию
header("Location: login.php"); // Перенаправляем на вход
exit();
?>