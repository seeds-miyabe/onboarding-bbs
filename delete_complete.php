<?php
require_once 'private/bootstrap.php';
require_once 'private/database.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$token = $_POST['token'] ?? '';

$id = $_SESSION['delete'][$token] ?? '';

if ($id === '' || !ctype_digit($id)) {
    header('Location: index.php');
    exit;
}

$connection = connectDB();

$sql = "DELETE FROM articles WHERE id = ?";
$stmt = $connection->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();

unset($_SESSION['delete'][$token]);

// header('Location: index.php');
// exit;

?>

<!-- 描画するHTML -->
<!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>削除成功</title>
</head>
<body>
    <header>
        <h1>削除成功</h1>
    </header>
    <main>
        <a href="index.php">戻る</a>
    </main>
    <footer>
        <hr>
        <div>(　・ω・)ノ</div>
    </footer>
</body>
</html>
