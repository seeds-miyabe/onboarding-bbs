<?php
/* --------------------------------------------------
 * 必要なファイルを読み込む
 * -------------------------------------------------- */
require_once 'private/bootstrap.php';
require_once 'private/database.php';


/* --------------------------------------------------
 * 送られてきた値を取得する
 * セッションにも保存しておく
 * -------------------------------------------------- */
$connection = connectDB();
$id = $_POST['id'] ?? '';

/* --------------------------------------------------
 * 値のバリデーションを行う
 *
 * 1.値が入力されているか
 * 2.データベースに対象IDのレコードが存在するか
 * -------------------------------------------------- */
if ($id === '' || !ctype_digit($id)) {
    header('Location: index.php');
    exit;
}

$sql = "SELECT * FROM articles WHERE id = ?";
$stmt = $connection->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();

$article = $stmt->get_result()->fetch_assoc();

if (!$article) {
    header('Location: index.php');
    exit;
}

/* --------------------------------------------------
 * 削除する投稿のデータ
 * -------------------------------------------------- */
$name = $article['name'];
$content = $article['content'];

/* --------------------------------------------------
 * 確認画面と削除画面で利用するトークンを発行する
 * 今回は時刻をトークンとする
 * -------------------------------------------------- */
$token = strval(time());

$_SESSION['delete'][$token] = $id;

?>

<!-- 描画するHTML -->
<!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>削除確認</title>
</head>
<body>
    <header>
        <h1>確認</h1>
    </header>
    <main>
        <div>下記の内容を削除しますがよろしいですか?</div>
        <table>
            <tbody>
            <tr><th>名前</th><td><?= $name ?></td></tr>
            <tr><th>投稿内容</th><td><?= $content ?></td></tr>
            </tbody>
        </table>
        <form action="delete_complete.php" method="post">
            <input type="hidden" name="token" value="<?= $token ?>">
            <button type="submit">削除</button>
        </form>
    </main>
    <footer>
        <hr>
        <div>(　・ω・)ノ⌒■</div>
    </footer>
</body>
</html>
