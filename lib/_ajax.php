<?php

require_once(__DIR__ . '/../config/config.php');
require_once(__DIR__ . '/Controller/Index.php');

$postApp = new MyApp\Controller\Index();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  try {
    $res = $postApp->post();
    header('Content-type: application/json');
    echo json_encode($res);
    exit;
  } catch(\Exception $e) {
    header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true. 500);
    echo $e->getMessage();
    exit;
  }
}