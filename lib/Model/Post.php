<?php

namespace MyApp\Model;

class Post extends \MyApp\Model {

  public function index() {
    $stmt = $this->db->query('select * from posts order by id desc limit 10');
    return $stmt->fetchAll(\PDO::FETCH_OBJ);
  }

  public function create($values) {
   $stmt = $this->db->prepare('insert into posts (text, image, created, deleted) values (:text, :image, now(), :deleted)');
   $stmt->execute([
     ':text' => trim($values['post_text']),
     ':image' => $values['post_image'],
     ':deleted' => null,
     ]);

     $params = $this->_getParams();

   return [
    'id' => $this->db->lastInsertId(),
    'image' => $params['image'],
    'created' => $params['created']
   ];
  }

  private function _getParams() {
    $params = $this->db->query('select image, created from posts order by id desc limit 1');
    $fetchParams = $params->fetch(\PDO::FETCH_ORI_FIRST);
    return $fetchParams;
  }
}