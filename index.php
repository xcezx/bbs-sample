<?php
try {
    // DB へ接続する
    $pdo = new PDO('mysql:host=localhost;dbname=bbs', 'root');
    // DB への操作に失敗したときなどにエラーを表示させるためのおまじない
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    // DB への接続が失敗したらエラーを表示する
    throw $e;
}

if ( ! empty($_POST) ) {
    /*
     * POST データが "空でなかったら" DB への登録処理を行う
     */

    // 登録 (INSERT) を行う SQL のひな形を作る
    $query = 'INSERT INTO post ( name, address, title, body, posted_at ) VALUES ( ?, ?, ?, ?, ? )';
    $stmt = $pdo->prepare($query);

    try {
        // ひな形の "?" に対して、値を当てはめて実行する
        $result = $stmt->execute(array(
                $_POST['name'],
                $_POST['address'],
                $_POST['title'],
                $_POST['body'],
                date('Y-m-d H:i:s', time()),
            ));
    } catch (Exception $e) {
        // INSERT が失敗したらエラーを表示する
        throw $e;
    }
}
?>
<!doctype html>
<html lang="ja">
  <head>
    <meta charset="utf-8" />
    <title>BBS</title>
    <link rel="stylesheet" href="./bootstrap/css/bootstrap.css" />
    <link href="http://fonts.googleapis.com/css?family=Open+Sans+Condensed:300" rel="stylesheet">
    <style>
      body { margin-top: 4em; font-family: 'Open Sans Condensed', sans-serif; }
      input, .btn { font-family: 'Open Sans Condensed', sans-serif; }
      .footer { margin-top: 4em; padding: 2em 0; border-top: 1px solid #e5e5e5; background-color: #f5f5f5; }
    </style>
  </head>
  <body>
    <div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="brand">BBS</a>
        </div>
      </div>
    </div>

    <div class="container">

      <form class="well form-horizontal" action="index.php" method="post">
        <div class="control-group">
          <label class="control-label" for="name">Name</label>
          <div class="controls">
            <input class="input-xlarge" type="text" value="" id="name" name="name" />
          </div>
        </div>

        <div class="control-group">
          <label class="control-label" for="address">Address</label>
          <div class="controls">
            <input class="input-xlarge" type="email" value="" id="address" name="name" />
          </div>
        </div>

        <div class="control-group">
          <label class="control-label" for="title">Title</label>
          <div class="controls">
            <input class="input-xlarge" type="text" value="" id="title" name="title" />
          </div>
        </div>

        <div class="control-group">
          <label class="control-label" for="body">Body</label>
          <div class="controls">
            <textarea class="span6" rows="5" id="body" name="body"></textarea>
          </div>
        </div>

        <div class="form-actions">
          <button type="submit" class="btn btn-primary btn-large">Post</button>
          <button type="reset" class="btn btn-large">Reset</button>
        </div>
      </form>

      <hr />

      <?php
         /*
          * DB から投稿データを取得して表示する
          */
         $query = 'SELECT * FROM post ORDER BY posted_at DESC';
         foreach ( $pdo->query($query) as $row ):
      ?>

      <article>
        <header>
          <h1><?php echo $row['id']; ?>: <?php echo $row['title']; ?></h1>
        </header>
        <p><?php echo $row['body']; ?></p>
        <footer>
          <p>Posted on <?php echo $row['posted_at']; ?></p>
          <address><?php echo $row['name']; ?> &lt;<?php echo $row['address']; ?>&gt;</address>
        </footer>
      </article>

      <?php endforeach; ?>

    </div>

    <footer class="footer">
      <div class="container">
        <p>Powered by <a href="http://twitter.github.com/bootstrap/">Twitter Bootstrap</a></p>
      </div>
    </footer>
  </body>
</html>
