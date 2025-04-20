<?php 
  namespace App\Models;
  use CodeIgniter\Model;

  class UserModel extends Model{
    protected $table = 'users';
    protected $primaryKey = 'user_id';
    protected $returnType = 'array';
    protected $useAutoIncrement = true;
    protected $allowedFields = ['user_name', 'user_email', 'user_password'];

    public function obtenerUsuarioEmail($email){
      $usuario = $this->where('user_email', $email)->first();
      
      if(!$usuario){
        return null;
      }

      return $usuario;
    }

    public function obtenerUsuario($userId){
      $usuario = $this-> where ('user_id', $userId) -> first();

      if(!$usuario){
        return null;
      }

      return $usuario;
    }

  }
?>