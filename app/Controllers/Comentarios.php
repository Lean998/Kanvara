<?php

namespace App\Controllers;


use App\Models\CommentsModel;

class Comentarios extends BaseController {
    public function postEliminar(){
      $commentModel = new CommentsModel();
      $comentario = $this->request->getPost('id');
      if(!$commentModel->delete($comentario)){
        return redirect()->back()->with('error', 'Ocurrio un errro al eliminar el comentario.');
      }
      return redirect()->back()->with('success', 'Comentario eliminado correctamente.');
    }
}
