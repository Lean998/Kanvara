<?php 
  namespace App\Models;
  use CodeIgniter\Model;

  class TaskModel extends Model{
    protected $table = 'tasks';
    protected $primaryKey = 'task_id';
    protected $createdField = 'task_created';
    protected $allowedFields = [
      'task_title', 
      'task_desc',
      'task_priority' ,
      'task_state',
      'task_expiry', 
      'task_reminder', 
      'task_color', 
      'task_archived',
      'user_id'
    ];

    protected $useTimestamps = true;
  }
?>