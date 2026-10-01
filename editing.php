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
// $id = '';
$connection = connectDB();
$id = $_POST['id'] ?? '';

/* --------------------------------------------------
 * 値のバリデーションを行う
 *
 * 1.値が入力されているか
 * 2.データベースに対象IDのレコードが存在するか
 * -------------------------------------------------- */
// 1.値が入力されているか
if ($id === '' || !ctype_digit($id)) {
    redirect('/index.php');
}

$sql = "SELECT * FROM articles WHERE id = ?";
$stmt = $connection->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();

$article = $stmt->get_result()->fetch_assoc();


// 2.データベースに対象IDのレコードが存在するか
if (!$article) {
    redirect('/index.php');
}

/* --------------------------------------------------
 * 編集する投稿のデータ
 * -------------------------------------------------- */
$name = $article['name'];
$content = $article['content'];

/* --------------------------------------------------
 * 編集画面と編集完了画面で利用するトークンを発行する
 * 今回は時刻をトークンとする
 * -------------------------------------------------- */
$token = strval(time());

$_SESSION['id']= $id;

?>

<!-- 描画するHTML -->
<!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>投稿編集</title>
    <style>
        textarea {
            resize: vertical;
        }
        textarea, input[type=text] {
            border: solid 1px gray;
            box-sizing: border-box;
            padding: 4px;
            width: 100%;
        }
    </style>
</head>
<body>
    <header>
        <h1>投稿編集</h1>
    </header>
    <main>
        <form action="edit_complete.php" method="post">
            <input type="hidden" name="token" value="<?= $token ?>">
            <table>
                <tbody>
                <tr>
                    <th><label for="name">名前</label></th>
                    <td><input type="text" name="name" id="name" value="<?= htmlspecialchars($name ?? '', ENT_QUOTES, 'UTF-8') ?>" required></td>
                </tr>
                <tr>
                    <th><label for="content">投稿内容</label></th>
                    <td><textarea name="content" id="content" rows="4" required><?= htmlspecialchars($content ?? '', ENT_QUOTES, 'UTF-8') ?></textarea></td>
                </tr>
                </tbody>
            </table>
            <form action="edit_complete.php" method="post">
                <input type="hidden" name="token" value="<?= $token ?>">
                <button type="submit">編集</button>
            </form>
        </form>
    </main>
    <footer>
        <hr>
        <div>＿φ(・ω・　)</div>
    </footer>
</body>
</html>