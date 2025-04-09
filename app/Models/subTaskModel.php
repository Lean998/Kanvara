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

    protected $createdField = 'subtask_created';
    protected $useTimestamps = true;
  }
?>