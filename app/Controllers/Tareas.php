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

    
    public function getNuevaTarea(){
      return view('tarea/newTask', ['titulo' => 'Nueva Tarea']);
    }
    

    public function postCrearTarea() {	
    $validation = service('validation');
      $rules = [
        'taskTitle' => 'required|min_length[5]|max_length[50]',
        'taskDesc' => 'required|min_length[10]|max_length[50]',
        'taskPriority' => 'required',
        'taskExpiry' => 'required|valid_date|future_date',
        'taskColor' => 'required',
        'taskReminderDate' => 'permit_empty|valid_date|future_date|before_date[taskExpiry]',
      ]; 

      $validation->setRules($rules);
      
      if(!$validation->withRequest($this->request)->run()){
        return redirect()->back()->withInput()->with('errors', $validation->getErrors());
      }
      
      $data = [
        'task_title' => $this->request->getPost('taskTitle'),
        'task_desc' => $this->request->getPost('taskDesc'),
        'task_priority' => $this->request->getPost('taskPriority'),
        'task_state'=> 'En curso',
        'task_expiry' => $this->request->getPost('taskExpiry'),
        'task_color' => $this->request->getPost('taskColor'),
        'task_archived' => 0,
        'user_id' => session('user_id')
      ];

      $reminder = $this->request->getPost('taskReminderDate');

      if (!empty($reminder)) {
        $data['task_reminder'] = $reminder;
      }

      $taskModel = new TaskModel();
      $taskModel->save($data);

      return redirect()->to('/tareas')->with('success', 'Tarea creada correctamente.');
  }
}
?>
