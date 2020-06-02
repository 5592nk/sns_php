<?php

namespace MyApp\Model;

class Post extends \MyApp\Model {

  public function create($values) {
   $stmt = $this->db->prepare('insert into posts (text, image, created, deleted) values (:text, :image, now(), :deleted)');
   $stmt->execute([
     ':text' => $values['post_text'],
     ':image' => $values['post_image'],
     ':deleted' => null,
   ]);
  }
}