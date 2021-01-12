<?php

// データベースの接続情報
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'board');

date_default_timezone_set('Asia/Tokyo');

// 変数の初期化
$data = null;
$now_date = null;
$file = null;
$split_data = null;
$message = array();
$message_array = array();
$success_message = null;
$error_message = array();
$clean = array();

session_start();
include("functions.php");
check_session_id();


if (!empty($_POST['btn_submit'])) {

  if (empty($_POST['name'])) {
    $error_message[] = '表示名が入力されていません'; //error処理
  } else {
    $clean['name'] = htmlspecialchars($_POST['name'], ENT_QUOTES); //サニタイズ処理

    $_SESSION['name'] = $clean['name']; // セッションに名前の保存
  }

  if (empty($_POST['message'])) {
    $error_message[] = '本文が入力されていません'; //error処理
  } else {
    $clean['message'] = htmlspecialchars($_POST['message'], ENT_QUOTES); //サニタイズ処理

  }

  if (empty($error_message)) {

    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME); // データベースに接続
    if ($mysqli->connect_errno) {
      $error_message[] = '書き込みに失敗しました。 エラー番号 ' . $mysqli->connect_errno . ' : ' . $mysqli->connect_error;
    } else {

      $mysqli->set_charset('utf8'); //文字コード取得

      $now_date = date("Y-m-d H:i:s"); //日時取得

      //insert sql
      $sql = "INSERT INTO message (name, message, post_date) VALUES ( '$clean[name]', '$clean[message]', '$now_date')";

      $res = $mysqli->query($sql); //データを登録

      if ($res) {
        $_SESSION['$success_message'] = 'メッセージを書き込みました。';
      } else {
        $error_message[] = '書き込みに失敗しました。';
      }

      $mysqli->close(); //dbをclose
    }
    header('Location: ./');
  }
}

$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME); //データベース接続

if ($mysqli->connect_errno) { // 接続エラーの確認
  $error_message[] = 'データの読み込みに失敗しました。 エラー番号 ' . $mysqli->connect_errno . ' : ' . $mysqli->connect_error;
} else {
  $sql = "SELECT name,message,post_date FROM message ORDER BY post_date DESC";
  $res = $mysqli->query($sql);

  if ($res) {
    $message_array = $res->fetch_all(MYSQLI_ASSOC);
  }

  $mysqli->close();
}


?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
  <title>DEV7ちゃんねる</title>
</head>

<body>

  <h1>DEV7ちゃんねる</h1>
  <?php if (empty($_POST['btn_submit']) && !empty($_SESSION['success_message'])) : ?>
    <p class="success_message"><?php echo $_SESSION['success_message']; ?></p>
    <?php unset($_SESSION['success_message']); ?>
  <?php endif; ?>
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
      <input id="name" type="text" name="name" value="<?php if (!empty($_SESSION['name'])) {
                                                        echo $_SESSION['name'];
                                                      } ?>">
    </div>
    <div>
      <label for="message">本文</label>
      <textarea id="message" name="message"></textarea>
    </div>
    <div>
      <input type="submit" name="btn_submit" value="投稿">
      <input type="button" value="管理画面" onClick="location.href='admin.php'">
      <input type="button" value="ログアウト" onClick="location.href='logout.php'">

    </div>

    <section>
      <?php if (!empty($message_array)) { ?>
        <?php foreach ($message_array as $value) { ?>
          <article>
            <div class="info">
              <h2><?php echo $value['name']; ?></h2>
              <time><?php echo date('Y年m月d日 H:i', strtotime($value['post_date'])); ?></time>
            </div>
            <p><?php echo nl2br($value['message']); ?></p>
          </article>
        <?php } ?>
      <?php } ?>
    </section>
  </form>




</body>

</html>