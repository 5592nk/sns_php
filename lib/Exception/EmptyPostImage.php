<?php

namespace MyApp\Exception;

class EmptyPostImage extends \Exception {
  protected $message = '画像がアップロードできませんでした';
}