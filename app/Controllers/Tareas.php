<?php
  namespace App\Controllers;

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
  }
?>
