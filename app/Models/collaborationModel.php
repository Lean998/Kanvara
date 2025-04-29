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

    protected $deletedField = 'collaboration_deleted_at';
    protected $createdField = 'collaboration_created_at';
    protected $updatedField = 'collaboration_updated_at';
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;

    public function getColaboradores($taskId){
      return $this->select('users.user_id, users.user_name')
        ->join('users', 'users.user_id = collaborations.user_id')
        ->where('collaborations.task_id', $taskId)
        ->findAll();
    }

    public function isCollaborator($taskId, $userId){
      return $this->where('task_id', $taskId)
      ->where('user_id', $userId)
      ->where('collaboration_deleted_at IS NULL')
      ->first();
    }
  }
?>