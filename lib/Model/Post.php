<?php

namespace MyApp\Model;

class Post extends \MyApp\Model {

  public function index() {
    $stmt = $this->db->query('select * from posts order by id desc');
    return $stmt->fetchAll(\PDO::FETCH_OBJ);
  }

  public function create($values) {
   $stmt = $this->db->prepare('insert into posts (text, image, created, deleted) values (:text, :image, now(), :deleted)');
   $stmt->execute([
     ':text' => trim($values['post_text']),
     ':image' => $values['post_image'],
     ':deleted' => null,
     ]);

     $created = $this->_getCreated();

   return [
    'id' => $this->db->lastInsertId(),
    'created' => $created
   ];
  }

  private function _getCreated() {
    $created = $this->db->query('select created from posts order by id desc limit 1');
    $fetchCreated = $created->fetch(\PDO::FETCH_ORI_FIRST);
    return $fetchCreated['created'];
  }
}