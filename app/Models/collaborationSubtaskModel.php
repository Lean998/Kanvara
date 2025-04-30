<?php
  namespace App\Models;
  use CodeIgniter\Model;
  class collaborationSubtaskModel extends Model {
    protected $table = 'collaborations_subtask';
    protected $primaryKey = 'collaboration_subtask_id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = [
      'collaboration_subtask_id',
      'user_id',
      'subtask_id',
      'collaboration_subtask_created_at',
      'collaboration_subtask_responsable',
      'collaboration_subtask_updated_at',
      'collaboration_subtask_deleted_at',
    ];

    protected $deletedField = 'collaboration_subtask_deleted_at';
    protected $createdField = 'collaboration_subtask_created_at';
    protected $updatedField = 'collaboration_subtask_updated_at';
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;

    public function getColaboradores($subtaskId){
      return $this->select('users.user_id, users.user_name')
        ->join('users', 'users.user_id = collaborations_subtask.user_id')
        ->where('collaboration_subtask_deleted_at IS NULL')
        ->where('collaborations_subtask.subtask_id', $subtaskId)
        ->findAll();
    }

    public function isCollaborator($subtaskId, $userId){
      return $this->where('subtask_id', $subtaskId)
      ->where('user_id', $userId)
      ->where('collaboration_subtask_deleted_at IS NULL')
      ->first();
    }

    public function isResponsable($subtaskId, $userId){
      return $this->where('subtask_id', $subtaskId)
      ->where('user_id', $userId)
      ->where('collaboration_subtask_responsable', 1)
      ->where('collaboration_subtask_deleted_at IS NULL')
      ->first();
    }
  }
?>