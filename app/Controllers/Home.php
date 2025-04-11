<?php

namespace App\Controllers;


use App\Models\TaskModel;

class Home extends BaseController {
    public function getIndex() {

    $task = new TaskModel();
    $tasks = $task->obtenerTareas(session('user_id'));
    
    if($task == null){
      return $this -> response -> setJSON (['success' => false, 'message' => 'Tareas no encontradas']);
    }

    $data = [
      'titulo' => 'Mi tarea',
      'descripcion' => 'Tarea de prueba',
      'vencimiento' => date('d-m-y'),
      'tasks' => $tasks
    ];
      
    return view('index', $data);
    }
}
