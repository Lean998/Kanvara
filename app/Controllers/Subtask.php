<?php
  namespace App\Controllers;
  use App\Models\CollaborationModel;
  use App\Models\SubTaskModel;
  use App\Models\TaskModel;
use App\Models\UserModel;
use CodeIgniter\Controller;

  class Subtask extends Controller{
    public function postCrearSubtarea(){
      $validation = service('validation');
      $rules = [
        'subtaskDesc'    => 'required|min_length[5]',
        'subtaskPriority' => 'permit_empty|in_list[Baja,Normal,Alta]',
        'subtaskState' => 'required|in_list[Definida,En proceso,Completada]',
        'subtaskExpiry'    => 'required|valid_date|future_date',
        'subtaskComment' => 'permit_empty|min_length[5]',
        'user_id' => 'permit_empty',
      ]; 

      $validation->setRules($rules);
      
      if(!$validation->withRequest($this->request)->run()){
        return redirect()->back()->withInput()->with('errors', $validation->getErrors())->with('error', 'Ocurrio un error, revise los datos ingresados.');
      }

      $responsable = $this->request->getPost('subtaskResponsible');
      if(empty($responsable)){
        $data['user_id'] = -1;
      } else {
        $data['user_id'] = $responsable;
      } 
      $taskId = $this->request->getPost('task_id');
      $data = [
        'subtask_desc' => $this->request->getPost('subtaskDesc'),
        'subtask_priority' => $this->request->getPost('subtaskPriority'),
        'subtask_state'=> $this->request->getPost('subtaskState'),
        'subtask_expiry' => $this->request->getPost('subtaskExpiry'),
        'subtask_comment' => $this->request->getPost('subtaskComment'),
        'task_id' => $taskId,
      ];
      
      $subtaskModel = new SubTaskModel();
      $subtaskModel->save($data);
      
      return redirect()->to(base_url() . 'tareas/ver/'.$taskId)->with('success', 'Subtarea creada correctamente.');
    }

    public function postCambiarEstado(){
      $subtaskModel = new SubTaskModel();
      $subtaskId = $this->request->getPost('subtask_id');
      $nuevoEstado = $this->request->getPost('nuevo_estado');
      $userId = session('user_id');

      $subtask = $subtaskModel->where('subtask_id', $subtaskId)->first();

      if (!$subtask) {
        return redirect()->back()->with('error', 'Subtarea no encontrada: '. $subtaskId );
      }


      if ($subtask['user_id'] != $userId) {
        return redirect()->back()->with('error', 'No tienes permiso para modificar esta subtarea.');
      }

      $subtaskModel->update($subtaskId, ['subtask_state' => $nuevoEstado]);
      return redirect()->back()->with('success', 'Estado actualizado correctamente.');
    }

    public function getEditarSubtarea(){
      $subtaskModel = new SubTaskModel();
      $subtask = $subtaskModel->obtenerSubtarea($this->request->getGet('subtask'));
      $taskModel = new TaskModel();
      $task = $taskModel->obtenerTarea($subtask['task_id']);
      if(session('user_id') != $subtask['user_id'] AND session('user_id') != $task['user_id']){
        return redirect()->back()->withInput()->with('error', 'No tienes permisos para realizar esta acción.');
      }
      $collab = new CollaborationModel();
      $data = [
        'titulo' => 'Editar Tarea',
        'subtask' => $subtask,
        'colab' => $collab->getColaboradores($subtask['task_id']),
      ];
      return view('subtarea/editar', $data);
    }

    public function postEditarSubtarea(){
      $subtaskModel = new SubTaskModel();

      $validation = \Config\Services::validation();
      $rules = [
        'subtaskDesc'    => 'required|min_length[5]',
        'subtaskPriority' => 'permit_empty|in_list[Baja,Normal,Alta]',
        'subtaskState' => 'required|in_list[Definida,En proceso,Completada]',
        'subtaskExpiry'    => 'required|valid_date|future_date',
        'subtaskComment' => 'permit_empty|min_length[5]',
        'user_id' => 'permit_empty',
      ]; 

      if (!$this->validate($rules)) {
          return redirect()->back()->withInput()->with('errors', $validation->getErrors());
      }

      $data = [
          'subtask_desc' => $this->request->getPost('subtaskDesc'),
          'subtask_state' => $this->request->getPost('subtaskState'),
          'subtask_priority' => $this->request->getPost('subtaskPriority'),
          'subtask_expiry' => $this->request->getPost('subtaskExpiry'),
          'subtask_comment' => $this->request->getPost('subtaskComment'),
          'user_id' => $this->request->getPost('subtaskResponsible'),
      ];

      $subtaskId = $this->request->getPost('subtask_id');
      

      if ($subtaskModel->update($subtaskId, $data)) {
          $taskId = $this->request->getPost('task_id');
          return redirect()->to('tareas/ver/' . $taskId )->with('success', 'Subtarea actualizada correctamente.');
      } else {
          return redirect()->back()->withInput()->with('error', 'Hubo un problema al actualizar la subtarea.');
      }
    }

    public function postEliminarSubtarea(){
      $subtaskToDelete = $this->request->getPost('subtask_id');
      $subtaskModel = new SubTaskModel();

      $subtarea = $subtaskModel->find($subtaskToDelete);
      if (!$subtarea) {
          return redirect()->back()->with('error', 'La subtarea no fue encontrada.');
      }

      $taskId = $subtarea['task_id'];
      if ($subtaskModel->delete($subtaskToDelete)) {
        return redirect()->to('/tareas/ver/' . $taskId)->with('success', 'Subtarea eliminada correctamente.');
    } else {
        return redirect()->back()->with('error', 'No se pudo eliminar la subtarea.');
    }
    }
    public function getAgregarColaborador(){
      $subtaskModel = new SubTaskModel();
      $subtask = $subtaskModel->obtenerSubtarea($this->request->getGet('subtask'));
      $taskModel = new TaskModel();
      $task = $taskModel->obtenerTarea($subtask['task_id']);
      if(session('user_id') != $subtask['user_id'] AND session('user_id') != $task['user_id']){
        return redirect()->back()->withInput()->with('error', 'No tienes permisos para realizar esta acción.');
      }

      $subtaskCollaboratorIds = array_column($subtask['colaboradores'], 'user_id');
      $collaboratorDisp = array_filter($task['colaboradores'], 
            function ($collaborator) use ($subtaskCollaboratorIds) {
                // Convertir user_id a string para evitar problemas de tipo
                $collaboratorId = (string) $collaborator['user_id'];
                $isInSubtask = in_array($collaboratorId, array_map('strval', $subtaskCollaboratorIds), true);
                log_message('debug', "Collaborator ID {$collaboratorId} in Subtask: " . ($isInSubtask ? 'Yes' : 'No'));
                return !$isInSubtask;
            }
        );

      $userModel = new UserModel();
      foreach ($collaboratorDisp as &$collaborator) {
        $user = $userModel->where('user_id', $collaborator['user_id'])->first();
        $collaborator['user_name'] = $user ? $user['user_name'] : 'Desconocido';
      }
      unset($collaborator); 

      $collaboratorDisp = array_values($collaboratorDisp);

      $data = [
        'titulo' => 'Agregar Colaborador',
        'subtask' => $subtask,
        'colab' => $collaboratorDisp
      ];


      return view('subtarea/agregar_colaborador', $data);
    }
    public function postAgregarColaborador(){
      $validation = $validation = \Config\Services::validation();
      $subtaskModel = new SubTaskModel();
      $subtaskId = $this->request->getPost('subtask_id');
      $rules = [
      'subtaskCollaborator' => 'required|valid_email|exist_user_email[subtaskCollaborator]',
      ];

      if(!$this->validate($rules)){
        return redirect()->back()->withInput()->with('errors', $validation->getErrors());
      }
      
      $subtask = $subtaskModel->obtenerSubtarea($subtaskId);
      if (!$subtask) {
        return redirect()->back()->with('error', 'Subtarea no encontrada: '. $subtaskId );
      }
      
      $taskId = $subtask['task_id'];
      //logica para enviar el correo y almacenar la invitacion.
      return redirect()->to('tareas/ver/' . $taskId )->with('success', 'Se ha enviado el correo de invitacion correctamente.');
    }
  }

?>