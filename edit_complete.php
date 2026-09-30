<?php
/* --------------------------------------------------
 * 必要なファイルを読み込む
 * -------------------------------------------------- */
require_once 'private/bootstrap.php';
require_once 'private/database.php';

/* --------------------------------------------------
 * 送られてきた値を取得する
 * -------------------------------------------------- */
$token = $_POST['token'] ?? '';
$name    = $_POST['name'] ?? '';
$content = $_POST['content'] ?? '';

$id = $_SESSION['edit'][$token] ?? '';
/* --------------------------------------------------
 * 送られてきたトークンのバリデーション
 *
 * セッションに保存されているトークンと比較し、
 * 一致していなかった場合はトップ画面にリダイレクトする
 * -------------------------------------------------- */
if ($token === '' || !isset($_SESSION['edit'][$token])) {
    unset($_SESSION['edit'][$token]);
    header('Location: index.php');
    exit;
}

/* --------------------------------------------------
 * 値のバリデーションを行う
 * -------------------------------------------------- */
if ($id === '' || !ctype_digit($id) || $name === '' || $content === '') {
    header('Location: index.php');
    exit;
}

/* --------------------------------------------------
 * セッション内に保存したIDを取得する
 * -------------------------------------------------- */
// $id = '';
$id = $_SESSION['edit'][$token] ?? '';

/* --------------------------------------------------
 * データの更新処理
 * -------------------------------------------------- */
$connection = connectDB();

$sql = "UPDATE articles SET name = ?, content = ? WHERE id = ?";
$stmt = $connection->prepare($sql);
$stmt->bind_param("ssi", $name, $content, $id);
$stmt->execute();
/* --------------------------------------------------
 * セッション内のデータを削除する
 * -------------------------------------------------- */
unset($_SESSION['edit'][$token]);


?>

<!-- 描画するHTML -->
<!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>編集成功</title>
</head>
<body>
    <header>
        <h1>編集成功</h1>
    </header>
    <main>
        <a href="index.php">戻る</a>
    </main>
    <footer>
        <hr>
        <div>＿d(・ω・　)</div>
    </footer>
</body>
</html>
