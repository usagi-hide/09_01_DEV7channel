<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
  <title>DEV7ちゃんねるログイン画面</title>
</head>

<body>
  <form action="login.php" method="POST">
    <fieldset>
      <legend style="padding: 20px;">DEV7ちゃんねるログイン画面</legend>
      <div>
        username: <input type="text" name="username">
      </div>
      <div>
        password: <input type="text" name="password">
      </div>
      <div>
        <button style="margin-bottom: 30px; width:110px; height: 33px; background-color: #37a1e5; color:white; border-radius: 5px; border: none;">ログイン</button>
      </div>
      <a href="signup.php">or 新規登録</a>
    </fieldset>
  </form>

</body>

</html>