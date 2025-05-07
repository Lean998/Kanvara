<?php 
  namespace App\Models;
  use CodeIgniter\Model;

  class TaskModel extends Model{
    protected $table = 'tasks';
    protected $primaryKey = 'task_id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $createdField = 'task_created_at';
    protected $updatedField  = 'task_updated_at';
    protected $deletedField  = 'task_deleted_at'; 

    protected $validationRules = [
      'task_title'          => 'required|min_length[5]',
      'task_desc'    => 'required|min_length[10]|max_length[70]',
      'task_priority' => 'required|in_list[Baja,Normal,Alta]',
      'task_state' => 'required|in_list[Definida,En proceso,Completada]',
      'task_expiry'    => 'required|valid_date',
      'task_reminder'  => 'permit_empty|valid_date',
      'task_archived' => 'permit_empty',
      'task_color'          => 'required',
    ];
    protected $allowedFields = [
      'task_title', 
      'task_desc',
      'task_priority' ,
      'task_state',
      'task_expiry', 
      'task_reminder', 
      'task_color', 
      'task_archived',
      'user_id'
    ];
    protected $dateFormat = 'datetime';
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;

    public function obtenerTarea($tareaId){
      $task = $this-> where ('task_id', $tareaId) -> first();

      if(!$task){
        return null;
      }

      $subTaskModel = new SubTaskModel();
      $collaborationModel = new CollaborationModel();
      $collaborationSubtaskModel = new collaborationSubtaskModel();
      $task['subtasks'] = $subTaskModel->obtenerSubtareas($tareaId); 
      if ($task['subtasks']) {
        foreach ($task['subtasks'] as &$subtask) { 
            $subtask['colaboradores'] = $collaborationSubtaskModel->getColaboradores($subtask['subtask_id']);
        }
        unset($subtask);
      }
      $task['colaboradores'] = $collaborationModel -> where ('task_id', $tareaId) -> findAll();
      return $task;
    }
    public function obtenerTareas($userId, $ordenar = "", $orden = "ASC"){
      if($ordenar === "task_priority"){
        $tasks = $this->where('user_id', $userId) 
        -> where ('task_archived',0) 
        ->where('task_deleted_at IS NULL')
        ->orderBy("FIELD(task_priority, 'Alta', 'Normal', 'Baja')")
        -> findAll();
      } else if(!empty($ordenar)) {
        $tasks = $this->where('user_id', $userId) 
        -> where ('task_archived',0) 
        ->where('task_deleted_at IS NULL')
        ->orderBy($ordenar, $orden)
        -> findAll();
      } else{
        $tasks = $this->where('user_id', $userId) 
        -> where ('task_archived',0) 
        ->where('task_deleted_at IS NULL')
        -> findAll();
      }
      
      $subTaskModel = new SubTaskModel();

      foreach ($tasks as $index => $task) {
        $tasks[$index]['subtasks'] = $subTaskModel -> where('task_id', $task['task_id']) -> findAll();
      }

      return $tasks;
    }

      public function obtenerTareasEliminadas($userId, $ordenar = "", $orden = "ASC"){
        if($ordenar === 'task_priority'){
          $tasks = $this->onlyDeleted()
          ->where('user_id', $userId) 
          -> where ('task_archived',0) 
          -> orderBy ("FIELD(task_priority, 'Alta', 'Normal', 'Baja')")
          -> findAll();
        } else if(!empty($ordenar)) {
          $tasks = $this->onlyDeleted()
          ->where('user_id', $userId) 
          -> where ('task_archived',0) 
          -> orderBy ($ordenar, $orden)
          -> findAll();
        } else{
          $tasks = $this->onlyDeleted()
          ->where('user_id', $userId) 
          -> where ('task_archived',0) 
          -> findAll();
        }
        $subTaskModel = new SubTaskModel();
        foreach ($tasks as $index => $task) {
          $tasks[$index]['subtasks'] = $subTaskModel -> where('task_id', $task['task_id']) -> findAll();
        }
        return $tasks;
      }
    public function obtenerTareasArchivadas($userId, $ordenar = "", $orden = "ASC"){
      if($ordenar === "task_priority"){
        $tasks = $this->where('user_id', $userId)
          ->where('task_archived', 1)
          ->orderBy("FIELD(task_priority, 'Alta', 'Normal', 'Baja')")
          ->findAll();
      } else if(!empty($ordenar)) {
        $tasks = $this->where('user_id', $userId)
          ->where('task_archived', 1)
          ->orderBy($ordenar, $orden)
          ->findAll();
      } else{
        $tasks = $this->where('user_id', $userId)
          ->where('task_archived', 1)
          ->findAll();
      }

      $subTaskModel = new SubTaskModel();
      foreach ($tasks as $index => $task) {
          $subtasks = $subTaskModel->where('task_id', $task['task_id'])->findAll();
          $tasks[$index]['subtasks'] = $subtasks;
      }
      return $tasks;
    }
    public function obtenerTareasCompartidas($userId, $ordenar = "", $orden="ASC"){
      if($ordenar === 'task_priority'){
        $tasks = $this->select('tasks.*')
        ->join('collaborations', 'collaborations.task_id = tasks.task_id', 'left')
        ->where('collaborations.user_id', $userId)
        ->where('tasks.task_deleted_at IS NULL')
        ->orderBy("FIELD(task_priority, 'Alta', 'Normal', 'Baja')")
        ->findAll();
      } else if(!empty($ordenar)){
        $tasks = $this->select('tasks.*')
        ->join('collaborations', 'collaborations.task_id = tasks.task_id', 'left')
        ->where('collaborations.user_id', $userId)
        ->where('tasks.task_deleted_at IS NULL')
        ->orderBy($ordenar, $orden)
        ->findAll();
      }
      else {
        $tasks = $this->select('tasks.*')
        ->join('collaborations', 'collaborations.task_id = tasks.task_id', 'left')
        ->where('collaborations.user_id', $userId)
        ->where('tasks.task_deleted_at IS NULL')
        ->findAll();
      } 
      $subTaskModel = new SubTaskModel();
      foreach ($tasks as $index => $task) {
        $tasks[$index]['subtasks'] = $subTaskModel -> where('task_id', $task['task_id']) -> findAll();
      }
      return $tasks;
  }
}
?>