<?php
  namespace App\Controllers;
  use App\Models\CollaborationModel;
  use App\Models\CollaborationSubtaskModel;
  use App\Models\SubTaskModel;
  use App\Models\CommentsModel;
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
        $data['user_id'] = null;
      } else {
        $data['user_id'] = $responsable;
      } 
      $taskId = $this->request->getPost('task_id');
      $data = [
        'subtask_desc' => $this->request->getPost('subtaskDesc'),
        'subtask_priority' => $this->request->getPost('subtaskPriority'),
        'subtask_state'=> $this->request->getPost('subtaskState'),
        'subtask_expiry' => $this->request->getPost('subtaskExpiry'),
        'task_id' => $taskId,
      ];
      
      $subtaskModel = new SubTaskModel();
      $newSubtaskId = 0;
      if(!$subtaskModel->save($data)){
        return redirect()->to(base_url() . 'tareas/ver/'.$taskId)->with('error', 'Ocurrio un error al crear la subtarea.');
      } else{
        $newSubtaskId = $subtaskModel->insertID() ;
      }
      
      $commentsModel = new CommentsModel();
      
      $dataComments = [
        'comments_comment' => $this->request->getPost('subtaskComment') ,
        'user_id' => session('user_id'),
        'subtask_id' =>  $newSubtaskId,
      ];

      $commentsModel->save($dataComments);
      return redirect()->to(base_url() . 'tareas/ver/'.$taskId)->with('success', 'Subtarea creada correctamente.');
    }
    public function postEliminarColaborador(){
      $collaborationSubtaskModel = new CollaborationSubtaskModel();
      $colaborador = $this->request->getPost('id');
      if(!$collaborationSubtaskModel->where('user_id', $colaborador)->delete()){
        return redirect()->back()->with('error', 'Ocurrio un errro al eliminar al colaborador.');
      }
      return redirect()->back()->with('success', 'Colaborador eliminado correctamente.');
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
      if(!$subtaskModel->isResponsable($subtask['subtask_id'], session('user_id')) AND session('user_id') != $task['user_id']){
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
      $taskModel = new TaskModel();
      $validation = \Config\Services::validation();
      $rules = [
        'subtaskDesc'    => 'required|min_length[5]',
        'subtaskPriority' => 'permit_empty|in_list[Baja,Normal,Alta]',
        'subtaskState' => 'required|in_list[Definida,En proceso,Completada]',
        'subtaskExpiry'    => 'required|valid_date|future_date',
        'user_id' => 'permit_empty',
      ]; 

      if (!$this->validate($rules)) {
          return redirect()->back()->withInput()->with('errors', $validation->getErrors());
      }
      $estado = $this->request->getPost('subtaskState');
      $data = [
          'subtask_desc' => $this->request->getPost('subtaskDesc'),
          'subtask_state' => $estado,
          'subtask_priority' => $this->request->getPost('subtaskPriority'),
          'subtask_expiry' => $this->request->getPost('subtaskExpiry'),
      ];

      $subtaskId = $this->request->getPost('subtask_id');
      
      $user = $this->request->getPost('subtaskResponsible');

      if($user !== ""){
        $data['user_id'] = $user;
      } else{
        $data['user_id'] = null;
      }

      if ($subtaskModel->update($subtaskId, $data)) {
          $taskId = $this->request->getPost('task_id');
          if($estado == 'Completada'){
              $taskModel->update($taskId, ['task_state' => 'En proceso']);
          }

          return redirect()->to('tareas/ver/' . $taskId )->with('success', 'Subtarea actualizada correctamente.');
      } else {
          return redirect()->back()->withInput()->with('error', 'Hubo un problema al actualizar la subtarea.');
      }
    }
    public function postEliminarSubtarea(){
      $subtaskToDelete = $this->request->getPost('subtask_id');
      $subtaskModel = new SubTaskModel();
      $taskModel = new TaskModel();
      $subtask = $subtaskModel->obtenerSubtarea($subtaskToDelete);
      $task = $taskModel->obtenerTarea($subtask['task_id']);
      if(/*!$subtaskModel->isResponsable($subtaskToDelete, session('user_id'))AND*/ session('user_id') != $task['user_id']){
        return redirect()->back()->withInput()->with('error', 'No tienes permisos para realizar esta acción.');
      }
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
      
      if(!$subtaskModel->isResponsable($subtask['subtask_id'], session('user_id')) AND session('user_id') != $task['user_id']){
        return redirect()->back()->withInput()->with('error', 'No tienes permisos para realizar esta acción.');
      }

      $subtaskCollaboratorIds = array_column($subtask['colaboradores'], 'user_id');
      $collaboratorDisp = array_filter($task['colaboradores'], 
            function ($collaborator) use ($subtaskCollaboratorIds) {
                // Convertir user_id a string para evitar problemas de tipo
                $collaboratorId = (string) $collaborator['user_id'];
                $isInSubtask = in_array($collaboratorId, array_map('strval', $subtaskCollaboratorIds), true);
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
      $colaborationSubtaskModel = new CollaborationSubtaskModel();
      $userColaborator = $this->request->getPost('subtaskResponsible');
      $subtaskId = $this->request->getPost('subtask_id');
      $taskId = $this->request->getPost('task_id');

      $data = [
        'user_id' => $userColaborator,
        'subtask_id' => $subtaskId,
      ];

      if(!$colaborationSubtaskModel->save($data)){
        return redirect()->back()->withInput()->with('error', 'Ocurrio un error al momento de agregar al colaborador.');
      }
      return redirect()->to('tareas/ver/' . $taskId )->with('success', 'Se ha agregado al colaborador con exito.');
    }
  }
?>