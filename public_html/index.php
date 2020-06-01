<?php
  require_once(__DIR__ . '/../config/config.php');
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>POST</title>
  <link rel="stylesheet" href="style/style.css">
</head>
<body>
  <div id="container">
    <form action="" method="post" id="post_form" enctype="multipart/form-data">
      <div>
        <input type="text" name="post_text">
      </div>
      <div>
        <input type="file" name="post_image" id="is_image">
      </div>
      <div>
        <input type="submit" value="post" class="btn">
      </div>
    </form>
  </div>
</body>
</html>