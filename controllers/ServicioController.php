<?php
require_once '../models/ServiciosModel.php';

class ServicioController
{
    public function insertServicio()
    {
        $servicio = new ServiciosModel();
        $servicio->create($_POST['nombre'], $_POST['detalle'], $_POST['precio']);
        if ($servicio) {
            return array("status" => true, "msg" => "Servicio creado");
        }
        return array("status" => false, "msg" => "Servicio no creado");
    }
    public function updateServicio()
    {

        $servicio = new ServiciosModel();
        $servicio->update($_POST['id'], $_POST['nombre'], $_POST['detalle'], $_POST['precio']);
        if ($servicio) {
            return array("status" => true, "msg" => "Servicio actualizado");
        }
        return array("status" => false, "msg" => "Servicio no actualizado");
    }
    public function deleteServicio()
    {
        $servicio = new ServiciosModel();
        $servicio->delete($_POST['id']);
        if ($servicio) {
            return array("status" => true, "msg" => "Servicio eliminado");
        }
        return array("status" => false, "msg" => "Servicio no eliminado");
    }
    public function getServicioById()
    {
        $servicio = new ServiciosModel();
        $servicio = $servicio->getById($_POST['id']);
        if ($servicio) {
            return array("status" => true, "data" => $servicio);
        }
        return array("status" => false, "msg" => "Servicio no encontrado");
    }
}
