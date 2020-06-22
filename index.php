<?php
require_once(__DIR__ . '/config/config.php');

$app = new MyApp\Controller\Index();

// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//   $app->post();
// }

$posts = $app->getAll();

?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>POST</title>
  <link rel="stylesheet" href="public/style/style.css">
</head>

<body>
  <div id="container">
    <form action="" method="post" id="post_form" enctype="multipart/form-data">
      <div class="err"></div>
      <div>
        <input type="text" name="post_text" id="post_text">
        <!-- <input type="text" id="post_text"> -->
      </div>
      <div>
        <!-- <input type="file" id="post_image"> -->
        <input type="hidden" name="MAX_FILE_SIZE" value="<?= h(MAX_FILE_SIZE); ?>">
        <input type="file" name="post_image" id="post_image">
      </div>
      <div>
        <!-- <input type="submit" value="post" class="btn"> -->
        <button type="button" class="btn" id="post_btn">post</button>
      </div>
    </form>

    <div id="post_content">
      <ul id="post_parent">
        <?php foreach ($posts as $post) : ?>
          <li id="post_<?= h($post->id); ?>" data-id="<?= h($post->id); ?>">
            <div>
              <?php if (($post->image) !== null) : ?>
                <div class="post_image">
                  <a href="<?= basename(dirname(THUMBNAIL_DIR)) . '/' . h(basename(IMAGES_DIR) . '/' . h(basename($post->image))); ?>">
                    <img src="<?= file_exists(basename(dirname(THUMBNAIL_DIR)) . '/' . h(basename(THUMBNAIL_DIR)) . '/' . h(basename($post->image)))
                    ? basename(dirname(THUMBNAIL_DIR)) . '/' . h(basename(THUMBNAIL_DIR)) . '/' . h(basename($post->image))
                    : basename(dirname(IMAGES_DIR)) . '/' . h(basename(IMAGES_DIR)) . '/' . h(basename($post->image)) ; ?>"
                    alt="">
                  </a>
                </div>
              <?php endif; ?>
              <div class="post_title">
                <?= h($post->text); ?>
              </div>
              <span class="post_created"><?= h($post->created); ?></span>
            </div>
          </li>
        <?php endforeach; ?>
        <li id="post_template" data-id="">
          <div>
            <div class="post_image">
              <a href="">
                <img src="" alt="">
              </a>
            </div>
            <div class="post_title">
            </div>
            <span class="post_created"></span>
          </div>
        </li>
      </ul>
    </div>
  </div>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
  <script src="public/js/_ajax.js"></script>
</body>

</html>