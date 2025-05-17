<?php
  namespace App\Models;
  use CodeIgniter\Model;
  class InvitationModel extends Model {
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
      'invitation_updated_at',
      'invitation_expires_at',
      'invitation_used',
    ];
    protected $createdField = 'invitation_created_at';
    protected $updatedField  = 'invitation_updated_at';
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';

    public function isInvitationValid($invitation_code){
      return $this->where('invitation_code', $invitation_code)
        ->where('invitation_used', 0)
        ->first();
    }
    public function getInvitacion($taskId, $email){
      return $this->where('task_id', $taskId)
      ->where('invitation_email', $email)
      ->where('invitation_expires_at >', date('Y-m-d H:i:s'))
      ->where('invitation_used', 0)
      ->first();
    }

    public function getInvitacionByCode($taskId, $invitationCode){
      return $this->where('task_id', $taskId)
      ->like('invitation_code', $invitationCode)
      ->where('invitation_used', 0)
      ->where('invitation_expires_at >', date('Y-m-d H:i:s'))
      ->first();
    }

    public function existingCode($codigo){
      return $this->where('invitation_code', $codigo)->first();
    }

    public function deleteExpiredInvitations(){
      $this->where('invitation_expires_at <=', date('Y-m-d H:i:s'))
      ->where('invitation_used', 0)
      ->delete();
    }

    
  }
?>