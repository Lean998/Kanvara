<?php
  namespace App\Controllers;
  use App\Models\SubTaskModel;
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
        return redirect()->back()->withInput()->with('errors', $validation->getErrors());
      }

      $taskId = $this->request->getPost('task_id');
      $data = [
        'subtask_desc' => $this->request->getPost('subtaskDesc'),
        'subtask_priority' => $this->request->getPost('subtaskPriority'),
        'subtask_state'=> $this->request->getPost('subtaskState'),
        'subtask_expiry' => $this->request->getPost('subtaskExpiry'),
        'subtask_comment' => $this->request->getPost('subtaskComment'),
        'user_id' => $this->request->getPost('subtaskResponsible'),
        'task_id' => $taskId,
      ];
      
      $subtaskModel = new SubTaskModel();
      $subtaskModel->save($data);
      
      return redirect()->to(base_url() . 'tareas/ver/'.$taskId)->with('success', 'Subtarea creada correctamente.');
    }
  }

?>