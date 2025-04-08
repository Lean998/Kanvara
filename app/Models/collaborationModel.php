<?php
  namespace App\Models;
  use CodeIgniter\Model;
  class CollaborationModel extends Model {
    protected $table = 'collaborations';
    protected $primaryKey = 'collaboration_id';
    protected $allowedFields = [
      'user_id',
      'task_id',
    ];

  }
?>