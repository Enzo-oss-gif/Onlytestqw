<?php
session_start();
define('SMARTCAPTCHA_SERVER_KEY', 'ysc2_ST7eEgUw1JgS4s7w1w0LVjT6CTX4bWeXC8zbhWmy1ffd76e8');

function check_captcha($token) {
    $ch = curl_init();
    $args = http_build_query([
        "secret" => SMARTCAPTCHA_SERVER_KEY,
        "token" => $token,
        "ip" => $_SERVER['REMOTE_ADDR'],
    ]);
    curl_setopt($ch, CURLOPT_URL, "https://smartcaptcha.yandexcloud.net/validate? $args");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 1);

    $server_output = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpcode !== 200) {
        return true; 
    }

    $resp = json_decode($server_output);
    return $resp->status === "ok";
}

require_once('db.php');

$email_or_phone = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$token = $_POST['smart-token'] ?? '';

if (empty($email_or_phone) || empty($password)) {
    echo '<script>alert("Заполнены не все обязательные поля.");</script>';
    include 'Avtoriz.html';
} else {
    if (empty($token)) {
        echo '<script>alert("Не пройдена капча.");</script>';
        include 'Avtoriz.html';
    } else {
        if (!check_captcha($token)) {
            echo '<script>alert("Капча не пройдена.");</script>';
            include 'Avtoriz.html';
        } else {
            $sql = "SELECT * FROM user WHERE (email = '$email_or_phone' OR phone = '$email_or_phone') AND password = '$password'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $_SESSION['user_id'] = $row['iduser']; 
                header("Location: /profile.php");
                exit();
            } else {
                echo '<script>alert("Неверный логин или пароль.");</script>';
                include 'Avtoriz.html';
            }
        }
    }
}
?>

 