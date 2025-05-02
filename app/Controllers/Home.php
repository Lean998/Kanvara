<?php

namespace App\Controllers;


use App\Models\TaskModel;

class Home extends BaseController {
    public function getIndex() {
    helper('form');
    $task = new TaskModel();
    $tasks = $task->obtenerTareas(session('user_id'));
    
    if($task == null){
      return $this -> response -> setJSON (['success' => false, 'message' => 'Tareas no encontradas']);
    }

    $data = [
      'titulo' => 'Inicio',
      'subtitulo' => 'Tus tareas',
      'tasks' => $tasks
    ];
      
    session()->set('opcion', '');
    return view('index', $data);
    }
}
