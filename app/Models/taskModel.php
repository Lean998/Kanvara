<?php 
  namespace App\Models;
  use CodeIgniter\Model;

  class TaskModel extends Model{
    protected $table = 'tasks';
    protected $primaryKey = 'task_id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $createdField = 'task_created_at';
    protected $updatedField  = 'task_updated_at';
    protected $deletedField  = 'task_deleted_at'; 

    protected $validationRules = [
      'task_title'          => 'required|min_length[5]',
      'task_desc'    => 'required|min_length[5]',
      'task_priority' => 'required|in_list[baja,normal,alta]',
      'task_state' => 'permit_empty',
      'task_expiry'    => 'required|valid_date',
      'task_reminder'  => 'permit_empty|valid_date',
      'task_archived' => 'permit_empty',
      'task_color'          => 'required',
    ];
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
    protected $dateFormat = 'datetime';
    protected $useTimestamps = true;
  }
?>