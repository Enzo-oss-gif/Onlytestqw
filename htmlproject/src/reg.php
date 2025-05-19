<?php
session_start();
 require_once ('db.php');
$name = $_POST['name'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$password = $_POST['password'];
$repeatpassword = $_POST['repeatpassword'];

if(empty($name) || empty($phone) || empty($email) || empty($password)){
    echo '<script>alert("Заполнены не все обязательные поля.");</script>';
    include 'index.html';
}
else{
    $sql = "SELECT * FROM user WHERE name = '$name'";
    $result = $conn->query($sql);
    if($result->num_rows > 0){
        echo '<script>alert("Имя уже используется.");</script>';
        include 'index.html';
    }
    else{
    $sql = "SELECT * FROM user WHERE phone = '$phone'";
    $result = $conn->query($sql);
    if($result->num_rows > 0){
        echo '<script>alert("Телефон уже используется.");</script>';
        include 'index.html';
    }
    else {
        $sql = "SELECT * FROM user WHERE email = '$email'";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            echo '<script>alert("Почта уже используется.");</script>';
            include 'index.html';
        }
        else{
    if($password != $repeatpassword){
        echo '<script>alert("Пароли не совпадают.");</script>';
        include 'index.html';
    }
    else {
        $sql = "INSERT INTO user (name, phone, email, password) 
              VALUES ('$name', '$phone', '$email', '$password')";
             if($conn->query($sql) === TRUE){
                echo '<script>alert("Регистрация прошла успешно!");</script>';
                include 'Avtoriz.html';
             }
        }
    }
  }
 }
}
?>
