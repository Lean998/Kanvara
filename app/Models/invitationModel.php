<?php
  namespace App\Models;
  use CodeIgniter\Model;
  class invitationModel extends Model {
    protected $table = 'invitations';
    protected $primaryKey = 'invitation_id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = [
      'invitation_id',
      'task_id',
      'invitation_email',
      'invitation_code',
      'invitation_created_at',
      'invitation_expires_at',
      'invitation_used',
    ];
    protected $createdField = 'invitation_created_at';
    protected $updatedField  = 'invitation_updated_at';
    protected $deletedField = 'invitation_deleted_at';
    protected $expiresField = 'invitation_expires_at';
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;

    
  }
?>