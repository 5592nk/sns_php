<?php

namespace MyApp\Exception;

class EmptyPostTexts extends \Exception {
  protected $message = '入力してください';
}