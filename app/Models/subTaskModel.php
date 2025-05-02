<?php
  namespace App\Models;
  use CodeIgniter\Model;

  class SubTaskModel extends Model {
    protected $table = 'subtasks';
    protected $primaryKey = 'subtask_id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $dateFormat = 'datetime';

    protected $allowedFields = [
      'subtask_desc',
      'subtask_state',
      'subtask_priority',
      'subtask_expiry',
      'subtask_comment',
      'user_id',
      'task_id',
    ];

    protected $validationRules = [
      'subtask_desc'    => 'required|min_length[5]',
      'subtask_priority' => 'permit_empty|in_list[Baja,Normal,Alta]',
      'subtask_state' => 'required|in_list[Definida,En proceso,Completada]',
      'subtask_expiry'    => 'required|valid_date',
      'subtask_comment' => 'permit_empty|min_length[5]',
      'user_id' => 'permit_empty',
    ];
    protected $createdField = 'subtask_created';
    protected $updatedField  = 'subtask_updated_at';
    protected $deletedField  = 'subtask_deleted_at';  
    protected $useSoftDeletes = true;
    protected $useTimestamps = true;

    public function obtenerSubtareas($taskId){
      return $this->select('subtasks.*, users.user_name')
        ->join('users', 'users.user_id = subtasks.user_id', "left")
        ->where('task_id', $taskId)
        ->where('subtask_deleted_at IS NULL')
        ->findAll();
    }

    public function obtenerSubtarea($subtaskId){
      $subtask = $this->select('subtasks.*, users.user_name')
      ->join('users', 'users.user_id = subtasks.user_id', 'left')
      ->where('subtask_deleted_at IS NULL')
      ->where('subtask_id', $subtaskId)
      ->first();
      $collaborationSubtaskModel = new collaborationSubtaskModel();
      $subtask['colaboradores'] = $collaborationSubtaskModel->getColaboradores($subtask['subtask_id']);
      return $subtask;
    }
  }
?>