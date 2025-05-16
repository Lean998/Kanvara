<?php

namespace App\Controllers;


use App\Models\TaskModel;

class Home extends BaseController {
    public function getIndex() {
    helper('form');
    $task = new TaskModel();
    $ordenar = $this->request->getGet('ordenar');
      if(!empty($ordenar)){
        $tasks = $task->obtenerTareas(session('user_id'), $ordenar);
      } else {
        $tasks = $task->obtenerTareas(session('user_id'));
      }
    
    
    if($task == null){
      return $this -> response -> setJSON (['success' => false, 'message' => 'Tareas no encontradas']);
    }

    $data = [
      'titulo' => 'Inicio',
      'subtitulo' => 'Tus tareas',
      'tasks' => $tasks
    ];
    $sesion = session();
    if (!$sesion->get("user_id")) {
      return view("auth/login");
    }
    session()->set('opcion', '');
    return view('index', $data);
    }
}
