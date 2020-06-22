<?php

namespace MyApp;

class Controller
{
  private $_error;

  public function __construct()
  {
    $this->_error = new \stdClass();
  }

  protected function setErrors($key, $error)
  {
    $this->_error->$key = $error;
  }

  public function getErrors($key)
  {
    return isset($this->_error->$key) ? $this->_error->$key : '';
  }
}
