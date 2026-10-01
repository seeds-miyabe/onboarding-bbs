<?php
/* --------------------------------------------------
 * 必要なファイルを読み込む
 * -------------------------------------------------- */
require_once 'private/bootstrap.php';
require_once 'private/database.php';

/* --------------------------------------------------
 * セッション開始
 * -------------------------------------------------- */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
/* --------------------------------------------------
 * 送られてきた値を取得する
 * -------------------------------------------------- */
// $name = $_POST['name'] ?? '';
// $content = $_POST['content'] ?? '';

$token = $_POST['token'] ?? '';

// $name    = $_SESSION['post'][$token]['name'] ?? '';
// $content = $_SESSION['post'][$token]['content'] ?? '';

/* --------------------------------------------------
 * 送られてきたトークンのバリデーション
 *
 * セッションに保存されているトークンと比較し、
 * 一致していなかった場合はトップ画面にリダイレクトする
 * -------------------------------------------------- */
// if(true) {
//     unset($_SESSION['token']);
//     redirect('/index.php');
// }

if ($token === '' || !isset($_SESSION['post'][$token])) {
    unset($_SESSION['post'][$token]);
    header('Location: index.php');
    exit;
}

/* --------------------------------------------------
 * セッション内に保存した投稿内容を取得する
 * -------------------------------------------------- */
$name    = $_SESSION['post'][$token]['name'] ?? '';
$content = $_SESSION['post'][$token]['content'] ?? '';

/* --------------------------------------------------
 * データベース接続
 * -------------------------------------------------- */
$connection = connectDB();
/* --------------------------------------------------
 * データのインサート処理
 * -------------------------------------------------- */
$sql = "INSERT INTO articles (name, content) VALUES (?, ?)";
$stmt = $connection->prepare($sql);
$stmt->bind_param("ss", $name, $content);
$stmt->execute();
/* --------------------------------------------------
 * セッション内のデータを削除する
 * -------------------------------------------------- */
unset($_SESSION['post'][$token]);

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
    <title>登録成功</title>
</head>
<body>
    <header>
        <h1>登録成功</h1>
    </header>
    <main>
        <a href="index.php">戻る</a>
    </main>
    <footer>
        <hr>
        <div>o(・ω・k)</div>
    </footer>
</body>
</html>
