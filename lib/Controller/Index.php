<?php

namespace MyApp\Controller;

class Index {
  private $_imageFileName;

  public function post() {
    $imageName = null;

    try {
      if (!isset($_FILES['post_image']) || $_FILES['post_image']['error'] === 4) {
        $this->_validatePostText();
      } else {
        $this->_validatePostImage();
        $image = $this->_validateImageType();
        list($savePath, $imageName) = $this->_saveImages($image);
      }
      $postModel = new \MyApp\Model\Post();
      $postModel->create([
        'post_text' => $_POST['post_text'],
        'post_image' => $imageName
      ]);
    } catch(\Exception $e) {
      echo $e->getMessage();
      exit;
    }
  }

  private function _validatePostText() {
    if (!isset($_POST['post_text']) || $_POST['post_text'] === '') {
        throw new \Exception('入力してください');
    }
  }

  private function _validatePostImage() {
    if (!isset($_FILES['post_image']['error'])) {
      throw new \Exception('Upload Error!');
    }

    switch($_FILES['post_image']['error']) {
      case UPLOAD_ERR_OK:
        return true;
      case UPLOAD_ERR_INI_SIZE:
      case UPLOAD_ERR_FORM_SIZE:
        throw new \Exception('File too large!');
      default:
        throw new \Exception('Err: ' . $_FILES['post_image']['error']);
    }
  }

  private function _validateImageType() {
    $this->_imageType = exif_imagetype($_FILES['post_image']['tmp_name']);
    switch($this->_imageType) {
      case IMAGETYPE_GIF:
        return 'gif';
      case IMAGETYPE_JPEG:
        return 'jpeg';
      case IMAGETYPE_PNG:
        return 'png';
      default:
        throw new \Exception('PNG/JPEG/GIF only!');
    }
  }

  private function _saveImages($ext) {
    $this->_imageFileName = sprintf(
      '%s_%s.%s',
      time(),
      sha1(uniqid(mt_rand(), true)),
      $ext
    );
    $savePath = IMAGES_DIR . '/' . $this->_imageFileName;
    $res = move_uploaded_file($_FILES['post_image']['tmp_name'], $savePath);
    if ($res === false) {
      throw new \Exception('Could not upload!');
    }
    return [$savePath, $this->_imageFileName];
  }
}