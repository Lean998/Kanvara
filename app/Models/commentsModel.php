<?php
  namespace App\Models;
  use CodeIgniter\Model;
  class CommentsModel extends Model {
    protected $table = 'comments';
    protected $primaryKey = 'comments_id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = [
      'comments_id',
      'comments_comment',
      'user_id',
      'subtask_id',
    ];

    public function getComentario($commentId){
      return $this ->where('comments_id', $commentId)
      ->find();
    }

    public function getComentariosSubtarea($subtaskId){
      return $this ->where('subtask_id', $subtaskId)
      ->findAll();
    }
  }
?>