<?php
  namespace App\Controllers;
  use App\Models\CollaborationModel;
  use App\Models\SubTaskModel;
  use App\Models\TaskModel;
  use App\Models\InvitationModel;
  use App\Models\UserModel;
  use CodeIgniter\Config\Services;

  class Tareas extends BaseController{

    public function getVer($taskId){
      $taskModel = new TaskModel();
      $task = $taskModel->obtenerTarea($taskId);
      if(!$task || $task == null){
        return view('errors/html/error_404', ['message' => 'Ocurrio un error al mostrar la tarea o la tarea no existe.']);
      }

      $sesion = session();
      if (!$sesion->get("user_id")) {
          return redirect()->to(base_url('auth/login'));
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
        return view('errors/html/error_404', ['message' => 'Ocurrio un error al mostrar la tarea o la tarea no existe.']);
      }

      return $this->response->setJSON($task); 
    }

    public function getTareasCompartidas(){
      $taskModel = new TaskModel();
      $ordenar = $this->request->getGet('ordenar');
      if(!empty($ordenar)){
        $orden = ($ordenar === 'task_priority') ? 'DESC' : 'ASC';
        $task = $taskModel->obtenerTareasCompartidas(session('user_id'), $ordenar, $orden);
      } else {
        $task = $taskModel->obtenerTareasCompartidas(session('user_id'));
      }

      $data = [
        'titulo' => 'Tarea',
        'tasks' => $task,
        'subtitulo' => 'Tareas compartidas contigo',
      ];
      session()->set('opcion', 'tareas/tareas-compartidas');
      helper('form');
      return view('index', $data);
    }

    public function getTareasArchivadas(){
      $taskModel = new TaskModel();
      $ordenar = $this->request->getGet('ordenar');
      if(!empty($ordenar)){
        $orden = ($ordenar === 'task_priority') ? 'DESC' : 'ASC';
        $task = $taskModel->obtenerTareasArchivadas(session('user_id'), $ordenar, $orden);
      } else {
        $task = $taskModel->obtenerTareasArchivadas(session('user_id'));
      }

      $data = [
        'titulo' => 'Tarea',
        'tasks' => $task,
        'subtitulo' => 'Tus tareas archivadas',
      ];
      session()->set('opcion','tareas/tareas-archivadas');
      helper('form');
      return view('index', $data);
    }

    public function getTareasEliminadas(){
      $taskModel = new TaskModel();
      $ordenar = $this->request->getGet('ordenar');
      if(!empty($ordenar)){
        $orden = ($ordenar === 'task_priority') ? 'DESC' : 'ASC';
        $task = $taskModel->obtenerTareasEliminadas(session('user_id'), $ordenar, $orden);
      } else{
        $task = $taskModel->obtenerTareasEliminadas(session('user_id'));
      }
      $data = [
        'titulo' => 'Tarea',
        'tasks' => $task,
        'subtitulo' => 'Tus tareas eliminadas',
      ];
      session()->set('opcion','tareas/tareas-eliminadas');
      helper('form');
      return view('index', $data);
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

    public function postEliminarColaborador(){
      $collaborationModel = new CollaborationModel();
      $colaborador = $this->request->getPost('id');
      if(!$collaborationModel->where('user_id', $colaborador)->delete()){
        return redirect()->back()->with('error', 'Ocurrio un errro al eliminar al colaborador.');
      }
      return redirect()->back()->with('success', 'Colaborador eliminado correctamente.');
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

    public function postFinalizar(){
      $subtaskModel = new SubTaskModel();
      $taskModel = new TaskModel();
      $taskId = $this->request->getPost('task_id');
      $subtareas = $subtaskModel->obtenerSubtareas($taskId);
      $cont = 0;
      if(empty($subtareas)){
        if(!$taskModel->update($taskId, ['task_state' => 'Completada'])){
          return $this->response->setJSON(['success' => false, 'message' => 'Ocurrio un error al momento de finalizar la tarea.']);
        } else{
          return $this->response->setJSON(['success' => false, 'warning' => true, 'message' => 'Esta tarea no contenia subtareas, pero se finalizara igualmente.']);
        }
      }
      foreach($subtareas as $sub){
        if($sub['subtask_state'] == "Completada"){
          $cont++;
        }
      }
      if($cont != sizeof($subtareas)){
        return $this->response->setJSON(['success' => false, 'message' => 'Debe completar todas las subtareas antes de finalizar la tarea.']);
      }

      if(!$taskModel->update($taskId, ['task_state' => 'Completada'])){
        return $this->response->setJSON(['success' => false, 'message' => 'Ocurrio un error al momento de finalizar la tarea.']);
      } else{
        return $this->response->setJSON(['success' => true, 'message' => 'Tarea finalizada con exito.']);
      }
    }
    
    public function getNuevaTarea(){
      return view('tarea/newTask', ['titulo' => 'Nueva Tarea']);
    }

    public function getAceptarInvitacion($taskId){
      $taskModel = new TaskModel();
      $tarea = $taskModel->obtenerTarea($taskId);
            if (!$tarea) {
                $data['errors']['task_id'] = 'La tarea especificada no existe.';
            }
            $data['task_id'] = $taskId;
            $data['titulo'] = 'Aceptar Invitacion';
            $data['task_title'] = $tarea['task_title'] ?? '';
            return view('tarea/aceptar_invitacion', $data);
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
      $invitationModel = new InvitationModel();
      $userModel = new UserModel();
      $collaborationModel = new CollaborationModel();
      $tarea = $taskModel->obtenerTarea($taskId);

      if (!$tarea) {
        return redirect()->back()->with('error', 'Ocurrio un error al obtener la tarea.');
      }
      $usuario = $this->request->getPost('taskCollaborator');

      $usuarioInvitado = $userModel->obtenerUsuarioEmail($usuario);
      if($usuarioInvitado){
        if ($collaborationModel->isCollaborator($taskId, $usuarioInvitado['user_id'])) {
          return redirect()->to(base_url() . 'tareas/ver/' . $taskId)->with('error', 'El usuario ingresado ya es un colaborador de esta tarea.');
        }
      }
      

      $existingInvitation = $invitationModel->getInvitacion($taskId, $usuario);
      if ($existingInvitation) {
          return redirect()->to(base_url() . 'tareas/ver/' . $taskId)->with('error', 'Ya existe una invitación activa para este usuario.');
      }

      $email = Services::email();
      $email->setTo($usuario);
      $email->setSubject('Invitacion a colaborar en '. $tarea['task_title']);
      do {
        $codigo = strtoupper(bin2hex(random_bytes(5)));
      } while ($invitationModel->existingCode($codigo));
      $codigo = strtoupper(bin2hex(random_bytes(5)));
      $message = view('emails/invitation', [
        'task_title' => $tarea['task_title'],
        'code' => $codigo,
        'accept_url' => base_url('tareas/aceptar-invitacion/' . $taskId),
      ]);
      $email->setMessage($message);

      if ($email->send()) {
        $datos = [
          'task_id' => $taskId,
          'invitation_email' => $usuario,
          'invitation_code' => $codigo,
          'invitation_used' => 0,
          'invitation_expires_at' => date('Y-m-d H:i:s', strtotime('+5 minutes')),
        ];
        $invitationModel->save($datos);
        return redirect()->to(base_url() . 'tareas/ver/'.$taskId)->with('success', 'Se ha enviado el correo de invitacion correctamente.');
      } else {
        return redirect()->to(base_url() . 'tareas/ver/'.$taskId)->with('error', 'Ocurrio un error al momento de enviar la invitacion, intente de nuevo mas tarde.');
      }
      
    }

    public function postAceptarInvitacion(){

      helper('form');

      $invitationModel = new InvitationModel();
      $taskModel = new TaskModel();
      $collaborationModel = new CollaborationModel(); 
      $validation = \Config\Services::validation();
      $taskId = null;
      
      $data = [
        'titulo' => 'Aceptar invitacion',
        'task_id' => null,
        'errors' => [],
        'success' => null,
      ];

      $rules = [
        'invitation_code' => 'required|min_length[10]|max_length[10]',
      ];

      if (!$this->validate($rules)) {
        $data['errors'] = $validation->getErrors();
        return view('tarea/aceptar_invitacion', $data);
      }

      
      $invitationCode = $this->request->getPost('invitation_code');
      $invitation = $invitationModel->isInvitationValid($invitationCode);

      if(!$invitation){
        $data['errors']['invitation_code'] = 'El código es inválido, ha expirado o ya fue usado.';
        return view('tarea/aceptar_invitacion', $data);
      }

      if ($invitation['invitation_expires_at'] < date('Y-m-d H:i:s')) {
          $invitationModel->deleteExpiredInvitations();
          $data['errors']['invitation_code'] = 'La invitación ha expirado.';
          return view('tarea/aceptar_invitacion', $data);
      }

      $invitationModel->deleteExpiredInvitations();
      
      $taskId = $invitation['task_id'];

      $userEmail = session('user_email'); 
        if ($invitation['invitation_email'] !== $userEmail) {
          $data['errors']['invitation_code'] = 'Este código no está asociado a tu cuenta.';
          return view('tarea/aceptar_invitacion', $data);
      }

      $tarea = $taskModel->obtenerTarea($taskId);
        if (!$tarea) {
          $data['errors']['invitation_code'] = 'La tarea asociada no existe.';
          return view('tarea/aceptar_invitacion', $data);
      }

      $userId = session('user_id');
      if ($collaborationModel->isCollaborator($taskId, $userId)) {
          $data['errors']['invitation_code'] = 'Ya eres colaborador de esta tarea.';
          $invitationModel->delete($invitation['invitation_id']);
          return view('tarea/aceptar_invitacion', $data);
      }

      try {
        $db = \Config\Database::connect();
        $db->transStart();

        $invitationModel->delete($invitation['invitation_id']);
        $collaboratorData = [
            'task_id' => $taskId,
            'user_id' => $userId,
        ];
        $collaborationModel->save($collaboratorData);
      } catch (\Exception $e) {
        $db->transRollback();
        $data['errors']['invitation_code'] = 'Ocurrió un error al procesar la invitación. Intenta de nuevo.';
        return view('tarea/aceptar_invitacion', $data);
      }
      
      $db->transComplete();

      if ($db->transStatus() === false) {
        $data['errors']['invitation_code'] = 'Ocurrió un error al procesar la invitación. Intenta de nuevo.';
        return view('tarea/aceptar_invitacion', $data);
      }
      $db->close;
      return redirect()->to(base_url() . 'tareas/ver/' . $taskId)->with('success', '¡Has sido añadido como colaborador en la tarea!');

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
