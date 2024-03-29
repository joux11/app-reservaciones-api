<?php
require_once '../models/TiposAlquilerModel.php';
class TipoAlquilerController
{
    public function getAll()
    {
        $model = new TiposAlquilerModel();
        $tipo = $model->getAll();
        if ($tipo) {
            return $tipo;
        }
    }

    public function get()
    {
        $model = new TiposAlquilerModel();
        $tipo = $model->getById($_POST['id']);
        if ($tipo) {
            return $tipo;
        }
    }
    public function insert()
    {
        $model = new TiposAlquilerModel();
        $model->insert($_POST['nombre'], $_POST['precio']);
        if ($model) {
            return array("status" => true, "msg" => "Tipo de alquiler creado");
        }
        return array("status" => false, "msg" => "Servicio no creado");
    }

    public function update()
    {
        $model = new TiposAlquilerModel();
        $model->update($_POST['id'], $_POST['nombre'], $_POST['precio']);
        if ($model) {
            return array("status" => true, "msg" => "Tipo de alquiler actualizado");
        }
        return array("status" => false, "msg" => "Servicio no actualizado");
    }

    public function delete()
    {
        $model = new TiposAlquilerModel();
        $model->delete($_POST['id']);
        if ($model) {
            return array("status" => true, "msg" => "Tipo de alquiler eliminado");
        }
        return array("status" => false, "msg" => "Servicio no eliminado");
    }
}
