<?php

namespace App\Controllers;


use App\Models\UserModel;
class Usuarios extends BaseController {
    public function getMisDatos(){
      $usuarioModel = new UserModel();
      $usuario = $usuarioModel->obtenerUsuario(session('user_id'));
      $data = [
        'titulo' => 'Mis datos',
        'usuario' => $usuario,
      ];
      
      session()->set('opcion', 'usuarios/mis-datos');
      return view('usuario/mis_datos', $data);
    }

    public function getCambiarPassword(){
      $usuarioModel = new UserModel();
      $usuario = $usuarioModel->obtenerUsuario(session('user_id'));
      $data = [
        'titulo' => 'Actualizar contraseña',
        'usuario' => $usuario
      ];
      return view('usuario/cambiar_contraseña', $data);
    }

    public function postCambiarPassword(){
      $usuarioModel = new UserModel();
      $user = $usuarioModel->obtenerUsuario(session('user_id'));
      $validation = \Config\Services::validation();
      $validation->setRules([
        'user_password' => 'required|min_length[6]',
        'new_password' => 'required|min_length[6]|matches[confirm_password]',
        'confirm_password' => 'required|matches[new_password]'
      ]); 

      if (!$validation->withRequest($this->request)->run()) {
        return redirect()->back()->withInput()->with('errors', $validation->getErrors());
    }

      $password = $this->request->getPost('user_password');
      $new_password = $this->request->getPost('new_password');

      if ($user == null || !$user || !password_verify($password, $user['user_password'])) {
        return redirect()->back()->with('error', 'Contraseña incorrecta.');
      }

      $new_password = password_hash($new_password, PASSWORD_DEFAULT);

      if(!$usuarioModel->update(session('user_id'), ['user_password' => $new_password])){
        return redirect()->back()->with('error', 'Ocurrio un error al actualizar su contraseña.');
      } 
      return redirect()->back()->with('success', 'Contraseña actualizada con exito.');
    }

    public function postActualizarDatos(){
      $validation = service('validation');
      $userModel = new UserModel();
      $user = $userModel->obtenerUsuario(session('user_id'));

      $data = [
        'user_name' => $this->request->getPost('username'),
        'user_email' => $this->request->getPost('user_email'),
      ];

      if($user['user_name'] === $data['user_name'] && $user['user_email'] === $data['user_email']){
        return redirect()->back()->with('error', 'No se ha modificado ningun campo.');
      }

      $userId = session('user_id');
      $validation->setRule('username', 'Nombre de usuario', "required|min_length[3]|max_length[50]|is_unique[users.user_name,user_id,{$userId}]");
      $validation->setRule('user_email', 'Email', "required|valid_email|is_unique[users.user_email,user_id,{$userId}]");

      if(!$validation->withRequest($this->request)->run()){
        return redirect()->back()->withInput()->with('errors', $validation->getErrors());
      } 
      
      $data['user_name'] = $data['user_name'] == $user['user_name'] ? $user['user_name'] : $data['user_name'];
      $data['user_email'] = $data['user_email'] == $user['user_email'] ? $user['user_email'] : $data['user_email'];

      $userModel->update(session('user_id') ,$data);

      return redirect()->back()->with('success', 'Datos actualizados con exito.');
    }

    public function getEliminarUsuario(){
      $userModel = new UserModel();
      if(!$userModel->delete(session('user_id'))){
        return redirect()->back()->with('error', 'Ocurrio un error al eliminar su cuenta.');
      }
      return redirect()->to(base_url('auth/log-out'));
    }
}
