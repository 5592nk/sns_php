<?php

namespace MyApp\Controller;

class Index extends \MyApp\Controller
{
  private $_imageFileName;
  private $_imageType;

  public function getAll()
  {
    $this->_getImage();
    $postModel = new \MyApp\Model\Post();
    return $postModel->index();
  }

  public function post()
  {
    $imageName = null;

    // try {
    if (!isset($_FILES['post_image']) || $_FILES['post_image']['error'] === 4) {
      // if (!isset($_FILES['post_image'])) {
      $this->_validatePostText();
    } else {
      $this->_validatePostImage();
      $image = $this->_validateImageType();
      list($savePath, $imageName) = $this->_saveImages($image);
      // print_r($savePath);
      $this->_createThumbnail($savePath);
      // }
      // } catch (\MyApp\Exception\EmptyPostTexts $e) {
      // } catch (\Exception $e) {
      // $this->setErrors('post_text', $e->getMessage());
      // echo $e->getMessage();
      // exit;
      // } catch (\MyApp\Exception\EmptyPostImage $e) {
      //   $this->setErrors('post_image', $e->getMessage());
    }

    $postModel = new \MyApp\Model\Post();
    $returnData = $postModel->create([
      'post_text' => $_POST['post_text'],
      'post_image' => $imageName
    ]);
    return $returnData;
  }

  private function _getImage()
  {
    $images = [];
    $files = [];
    $imageDir = opendir(IMAGES_DIR);
    while (false != ($file = readdir($imageDir))) {
      if ($file === '.' || $file === '..') {
        continue;
      }
      $files[] = $file;
      if (file_exists(THUMBNAIL_DIR) . '/' . $file) {
        $images[] = basename(THUMBNAIL_DIR) . '/' . $file;
      } else {
        $images[] = basename(IMAGES_DIR) . '/' . $file;
      }
    }
    // array_multisort($file, SORT_DESC, $images);
  }

  private function _createThumbnail($savePath)
  {
    $imageSize = getimagesize($savePath);
    $width = $imageSize[0];
    $height = $imageSize[1];
    if ($width > THUMBNAIL_WIDTH) {
      $this->_createThumbnailMain($savePath, $width, $height);
    }
  }

  private function _createThumbnailMain($savePath, $width, $height)
  {
    switch ($this->_imageType) {
      case IMAGETYPE_GIF:
        $srcImage = imagecreatefromgif($savePath);
        break;
      case IMAGETYPE_JPEG:
        $srcImage = imagecreatefromjpeg($savePath);
        break;
      case IMAGETYPE_PNG;
        $srcImage = imagecreatefrompng($savePath);
        break;
    }

    $thumbHeight = round($height * THUMBNAIL_WIDTH / $width);
    $thumbImage = imagecreatetruecolor(THUMBNAIL_WIDTH, $thumbHeight);
    imagecopyresampled($thumbImage, $srcImage, 0, 0, 0, 0, THUMBNAIL_WIDTH, $thumbHeight, $width, $height);

    switch ($this->_imageType) {
      case IMAGETYPE_GIF:
        imagegif($thumbImage, THUMBNAIL_DIR . '/' . $this->_imageFileName);
        break;
      case IMAGETYPE_JPEG:
        imagejpeg($thumbImage, THUMBNAIL_DIR . '/' . $this->_imageFileName);
        break;
      case IMAGETYPE_PNG;
        imagepng($thumbImage, THUMBNAIL_DIR . '/' . $this->_imageFileName);
        break;
    }
  }

  private function _validatePostText()
  {
    if (!isset($_POST['post_text']) || $_POST['post_text'] === '') {
      throw new \MyApp\Exception\EmptyPostTexts();
    }
  }

  private function _validatePostImage()
  {
    if (!isset($_FILES['post_image']['error'])) {
      throw new \MyApp\Exception\EmptyPostImage();
    }

    switch ($_FILES['post_image']['error']) {
      case UPLOAD_ERR_OK:
        return true;
      case UPLOAD_ERR_INI_SIZE:
      case UPLOAD_ERR_FORM_SIZE:
        throw new \Exception('File too large!');
      default:
        throw new \Exception('Err: ' . $_FILES['post_image']['error']);
    }
  }

  private function _validateImageType()
  {
    $this->_imageType = exif_imagetype($_FILES['post_image']['tmp_name']);
    switch ($this->_imageType) {
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

  private function _saveImages($ext)
  {
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
