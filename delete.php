<?php

// データベースの接続情報
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'board');

date_default_timezone_set('Asia/Tokyo');

// 変数の初期化
$message_id = null;
$mysqli = null;
$sql = null;
$res = null;
$error_message = array();
$message_data = array();


session_start();


// 管理者としてログインしているか確認
if (empty($_SESSION['admin_login']) || $_SESSION['admin_login'] !== true) {

  // ログインページへリダイレクト
  header("Location: ./admin.php");
}

if (!empty($_GET['message_id']) && empty($_POST['message_id'])) {

  $message_id = (int)htmlspecialchars($_GET['message_id'], ENT_QUOTES);

  // データベースに接続
  $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

  // 接続エラーの確認
  if ($mysqli->connect_errno) {
    $error_message[] = 'データベースの接続に失敗しました。 エラー番号 ' . $mysqli->connect_errno . ' : ' . $mysqli->connect_error;
  } else {

    // データの読み込み
    $sql = "SELECT * FROM message WHERE id = $message_id";
    $res = $mysqli->query($sql);

    if ($res) {
      $message_data = $res->fetch_assoc();
    } else {

      // データが読み込めなかったら一覧に戻る
      header("Location: ./admin.php");
    }

    $mysqli->close();
  }
} elseif (!empty($_POST['message_id'])) {

  $message_id = (int)htmlspecialchars($_POST['message_id'], ENT_QUOTES);

  // データベースに接続
  $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

  // 接続エラーの確認
  if ($mysqli->connect_errno) {
    $error_message[] = 'データベースの接続に失敗しました。 エラー番号 ' . $mysqli->connect_errno . ' : ' . $mysqli->connect_error;
  } else {
    $sql = "DELETE FROM message WHERE id = $message_id";
    $res = $mysqli->query($sql);
  }

  $mysqli->close();

  // 更新に成功したら一覧に戻る
  if ($res) {
    header("Location: ./admin.php");
  }
}

?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
  <title>DEV7 管理ページ（投稿の削除）</title>
</head>

<body>

  <h1>DEV7 管理ページ（投稿の削除）</h1>

  <?php if (!empty($error_message)) : ?>
    <ul class="error_message">
      <?php foreach ($error_message as $value) : ?>
        <li>・<?php echo $value; ?></li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>

  <form method="post">
    <div>
      <label for="name">表示名</label>
      <input id="name" type="text" name="name" value="<?php if (!empty($message_data['name'])) {
                                                        echo $message_data['name'];
                                                      } ?>" disabled>
    </div>
    <div>
      <label for="message">本文</label>
      <textarea id="message" name="message" disabled><?php if (!empty($message_data['message'])) {
                                                        echo $message_data['message'];
                                                      } ?></textarea>
    </div>

    <p class="text-confirm">投稿を削除します。<br>よろしければ「削除」ボタンを押してください。</p>
    <a class="btn_cancel" href="admin.php">キャンセル</a>
    <input type="submit" name="btn_submit" value="削除">
    <input type="hidden" name="message_id" value="<?php echo $message_data['id']; ?>">
  </form>




</body>

</html>