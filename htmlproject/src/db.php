<?php
$servername = "mysql"; 
$username = "user";
$password = "userpass";
$dbname = "mydb";

// Создаем подключение
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Проверяем подключение
if (!$conn) {
 die("Connection failed: " . mysqli_connect_error());
}
else{
}
?>