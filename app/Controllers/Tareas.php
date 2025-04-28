<?php
  namespace App\Controllers;
  use App\Models\CollaborationModel;
  use App\Models\TaskModel;
  use App\Models\invitationModel;
  use CodeIgniter\Config\Services;

  class Tareas extends BaseController{
    public function getIndex (){
      
    }

    public function getVer($taskId){
      $taskModel = new TaskModel();
      $task = $taskModel->obtenerTarea($taskId);

      if(!$task || $task == null){
        return $this->response->setJSON(['success' => false, 'message' => 'Tarea no encontrada']);
      }

      $collab = new CollaborationModel();
      

      $data = [
        'titulo' => 'Tarea',
        'task' => $task,
        'colab' => $collab->getColaboradores($taskId),
      ];

      return view('tarea/task', $data);
    }

    public function getTarea($taskId){
      $taskModel = new TaskModel();
      $task = $taskModel->obtenerTarea($taskId);

      if(!$task || $task == null){
        return $this->response->setJSON(['success' => false, 'message' => 'Tarea no encontrada']);
      }

      return $this->response->setJSON($task); 
    }
    public function postArchivar(){
      $id = $this->request->getPost('task_id');
      
      if (!$id) {
        return $this->response->setJSON(['success' => false, 'message' => 'ID inválido']);
      }

      $taskModel = new TaskModel();
      $actualizado = $taskModel->update($id, [
          'task_archived' => '1'
      ]);
  
      return $this->response->setJSON(['success' => $actualizado, 'message' => $actualizado ? 'Tarea archivada con éxito' : 'No se pudo archivar']);
    }
    public function postEditar(){
      $id = $this->request->getPost('task_id');
      $titulo = $this->request->getPost('task_title');
      $fecha = $this->request->getPost('task_expiry');
      $color = $this->request->getPost('task_color');

      $taskModel = new TaskModel();
      $actualizado = $taskModel->update($id, [
          'task_title' => $titulo,
          'task_expiry' => $fecha,
          'task_color' => $color
      ]);
  
      return $this->response->setJSON([
          'success' => $actualizado,
          'message' => $actualizado ? 'Tarea actualizada con éxito refresque para ver los cambios.' : 'No se pudo actualizar'
      ]);
    }

    public function postEliminar(){

      $taskId = $this->request->getPost('task_id');

      if (!$taskId || !is_numeric($taskId)) {
        return $this->response->setJSON(['success' => false, 'message' => 'Error al encontrar la tarea.']);
      }

      $taskModel = new TaskModel();

      $deleted = $taskModel->delete($taskId);
      return $this->response->setJSON(['success' => $deleted]);
    }
    
    public function getNuevaTarea(){
      return view('tarea/newTask', ['titulo' => 'Nueva Tarea']);
    }
    
    public function postAgregarColaborador(){
      $validation = \Config\Services::validation();
      $taskId = $this->request->getPost('task_id');
      
      $rules = [
      'taskCollaborator' => 'required|valid_email|exist_user_email[taskCollaborator]',  
      ];

      if(!$this->validate($rules)){
        return redirect()->back()->withInput()->with('errors', $validation->getErrors())->with('error', 'Ocurrio un error al enviar la invitacion, revise los datos ingresados.');
      }
      $taskModel = new TaskModel();
      $invitationModel = new invitationModel();
      $tarea = $taskModel->obtenerTarea($taskId);
      $usuario = $this->request->getPost('taskCollaborator');

      $email = Services::email();
      $email->setTo($usuario);
      $email->setSubject('Invitacion a colaborar en '. $tarea['task_title']);
      $codigo = strtoupper(bin2hex(random_bytes(5)));
      $email->setMessage('Para aceptar la invitacion ingresa el siguiente codigo: ' . $codigo);

      if ($email->send()) {
        
        $expire = new \DateTime(); 
        $expire->add(new \DateInterval('PT5M'));  
        $datos = [
          'task_id' => $taskId,
          'invitation_email' => $usuario,
          'invitation_code' => $codigo,
          'invitation_used' => false,
          'invitation_expires_at' => $expire->format('Y-m-d H:i:s'),
        ];
        $invitationModel->save($datos);
        return redirect()->to(base_url() . 'tareas/ver/'.$taskId)->with('success', 'Se ha enviado el correo de invitacion correctamente.');
      } else {
        return redirect()->to(base_url() . 'tareas/ver/'.$taskId)->with('error', 'Ocurrio un error al momento de enviar la invitacion, intente de nuevo mas tarde.');
      }
      
    }

    public function postCrearTarea() {	
    $validation = service('validation');
      $rules = [
        'taskTitle' => 'required|min_length[5]',
        'taskDesc' => 'required|min_length[10]|max_length[70]',
        'taskPriority' => 'required|in_list[Baja,Normal,Alta]',
        'taskState' => 'required|in_list[Definida,En proceso,Completada]',
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
        'task_state'=> $this->request->getPost('taskState'),
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

      return redirect()->to('')->with('success', 'Tarea creada correctamente.');
  }
}
?>
