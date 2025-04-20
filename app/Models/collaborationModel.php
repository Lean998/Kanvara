<?php
  namespace App\Models;
  use CodeIgniter\Model;
  class CollaborationModel extends Model {
    protected $table = 'collaborations';
    protected $primaryKey = 'collaboration_id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = [
      'collaboration_id',
      'user_id',
      'task_id',
    ];

    protected $createdField = 'collaboration_created';
    protected $useTimestamps = true;

    public function getColaboradores($taskId){
      return $this->select('users.user_id, users.user_name')
        ->join('users', 'users.user_id = collaborations.user_id')
        ->where('collaborations.task_id', $taskId)
        ->findAll();
    }
  }
?>