<?php
  namespace App\Controllers;
  use CodeIgniter\Controller;

  class Auth extends Controller {
    public function getRegister (){
      return view('auth/register');
    }

    public function postUserUp(){
      
      $validation = \Config\Services::validation();
      $rules = [
        'username' => 'required|min_length[3]|max_length[50]|is_unique[users.user_name]',
        'email' => 'required|valid_email|is_unique[users.user_email]',
        'password' => 'required|min_length[6]',
        'confirm_password' => 'required|matches[password]'
      ];

      if(!$this->validate($rules)){
        return redirect()->back()->withInput()->with('errors', $validation->getErrors());
      }

      $userModel = new \App\Models\UserModel();
      $data = [
        'user_name' => $this->request->getPost('username'),
        'user_email' => $this->request->getPost('email'),
        'user_password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT)
      ];

      $userModel->save($data);

      return redirect()->to('/auth/login')->with('success', 'Registro exitoso');
    }

    public function getLogin(){
      return view('auth/login');
    }

    public function postUserAuthenticate (){
      $validation = \Config\Services::validation();
      $rules = [
        'email' => 'required|valid_email',
        'password' => 'required|min_length[6]'
      ];

      if(!$this->validate($rules)){
        return redirect()->back()->withInput()->with('errors', $validation->getErrors());
      }
      $email = $this->request->getPost('email');
      $password = $this->request->getPost('password');

      $userModel = new \App\Models\UserModel();
      $user = $userModel->where('user_email', $email)->first();

      if (!$user || !password_verify($password, $user['user_password'])) {
        return redirect()->back()->withInput()->with('error', 'Email o contraseña incorrectos.');
      }

      session()->set([
        'user_id' => $user['user_id'],
        'user_name' => $user['user_name'],
        'isLoggedIn' => true,
      ]);

      return view('welcome_message', ['titulo' => 'Home']);
      //return redirect()->to('/home/inicio');
      
    }

    public function getLogOut (){
      session()->destroy();
      $_SESSION = null;
      return view('auth/login');
      //return redirect()->to('/home/inicio');
    }
  } 
?>
