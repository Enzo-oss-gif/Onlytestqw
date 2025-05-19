<?php
session_start();
require_once('db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit(); 
}

$user_id = intval($_SESSION['user_id']); 

$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
    die("Ошибка подключения к БД: " . mysqli_connect_error());
}

$stmt = $conn->prepare("SELECT name, phone, email FROM user WHERE iduser = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    session_destroy(); 
    header("Location: login.php");
    exit();
}

$user = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (!empty($password)) {
        $update_sql = "UPDATE user SET name = ?, phone = ?, email = ?, password = ? WHERE iduser = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("ssssi", $name, $phone, $email, $password, $user_id);
    } else {
        $update_sql = "UPDATE user SET name = ?, phone = ?, email = ? WHERE iduser = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("sssi", $name, $phone, $email, $user_id);
    }

    if ($stmt->execute()) {
       echo '<script>alert("Данные успешно обновлены.");</script>';
        echo '<script>window.location.href = "profile.php";</script>';
        exit(); 
    } else {
        echo "Ошибка обновления: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>OnlyTest</title>
    <link rel="stylesheet" href="Av.css">
</head>
<body>
    <header>
        <h1>OnlyTest.</h1>
    </header>

    <form method="post">
        <h2>Добро пожаловать</h2>

        <div>
            <label>Имя:</label><br>
            <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required><br>

            <label>Телефон:</label><br>
            <input type="text" name="phone" value="<?= htmlspecialchars($user['phone']) ?>" required><br>

            <label>Email:</label><br>
            <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required><br>

            <label>Пароль:</label><br>
            <input type="password" name="password" placeholder="Оставьте пустым, если не хотите менять"><br>

            <input type="submit" value="Сохранить изменения">
            <a href="logout.php" class="logout-btn">Выйти</a>
        </div>
    </form>
</body>
</html>