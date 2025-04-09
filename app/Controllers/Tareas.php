<?php
  namespace App\Controllers;
  use App\Models\TaskModel;
  class Tareas extends BaseController{
    public function getIndex (){
      $data = [
        'titulo' => 'Mi tarea',
        'descripcion' => 'Tarea de prueba',
        'vencimiento' => date('d-m-y'),
      ];
      return view('tarea/index', $data);
    }

    public function getVerTarea(){
      echo '<h2> Datos de la tarea </h2>';
    }

    public function getNuevaTarea() {	
    $validation = service('validation');
      $rules = [
        'taskTitle' => 'required|min_length[5]|max_length[50]',
        'taskDesc' => 'required|min_length[10]|max_length[50]',
        'taskPriority' => 'required',
        'taskExpiry' => 'required|valid_date|after_date[' . date('Y-m-d') . ']',
        'taskColor' => 'required',
      ]; 

      if ($this->request->getPost('taskReminder')) {
        $rules['taskReminderDate'] = [
            'rules' => 'required|valid_date|future_datetime|before_datetime[taskExpiry]',
        ];
      }

      $validation->setRules($rules);
      
      if(!$validation->withRequest($this->request)->run()){
        return redirect()->back()->withInput()->with('errors', $validation->getErrors());
      }

      $taskModel = new taskModel();
      $data = [
        'task_title' => $this->request->getPost('taskTitle'),
        'task_desc' => $this->request->getPost('taskDesc'),
        'task_priority' => $this->request->getPost('taskPriority'),
        'task_state'=> 'En curso',
        'task_expiry' => $this->request->getPost('taskExpiry'),
        'task_color' => $this->request->getPost('taskColor'),
        'task_archived' => false,
        'user_id' => session('user_id'),
        'user_password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT)
      ];
      $taskModel->save($data);
  }
}
?>
