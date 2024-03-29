<?php
require_once '../models/ReservacionModel.php';
class ReservacionController
{
    public function getServiciosByReservacion()
    {
        $reservacionModel = new ReservacionModel();
        $servicios = $reservacionModel->getServiciosByReservacion($_POST['idReservacion']);
        if ($servicios) {
            return array('status' => true, 'data' => $servicios);
        }
        return array('status' => false, 'msg' => 'No se encontraron reservaciones');
    }
    public function getReservacionById()
    {
        $reservacionModel = new ReservacionModel();
        $dates = $reservacionModel->getReservacionById($_POST['idReservacion']);
        if ($dates) {
            return array('status' => true, 'data' => $dates);
        }
        return array('status' => false, 'msg' => 'No se encontraron reservaciones');
    }
    public function getAllReservaciones()
    {
        $reservacionModel = new ReservacionModel();
        $dates = $reservacionModel->getAllReservaciones();
        if ($dates) {
            return array('status' => true, 'data' => $dates);
        }
        return array('status' => false, 'msg' => 'No se encontraron reservaciones');
    }
    public function getReservacionByIdUsuario()
    {
        $reservacionModel = new ReservacionModel();
        $dates = $reservacionModel->getReservacionesbyIdUsuario($_POST['idUsuario']);
        if ($dates) {
            return array('status' => true, 'data' => $dates);
        }
        return array('status' => false, 'msg' => 'No se encontraron reservaciones');
    }

    public function getTipoAlquiler()
    {
        $reservacionModel = new ReservacionModel();
        $tipos = $reservacionModel->getTipoAlquiler();
        if ($tipos) {
            return $tipos;
        }
        return array('status' => false, 'msg' => 'No se encontraron tipo de alquiler');
    }
    public function getAllServicios()
    {
        $reservacionModel = new ReservacionModel();
        $servicios = $reservacionModel->getAllServices();
        if ($servicios) {
            return $servicios;
        }
        return array('status' => false, 'msg' => 'No se encontraron tipo de alquiler');
    }
    public function insertReservacion()
    {
        $reservacionModel = new ReservacionModel();
        $idReservacion = $reservacionModel->create($_POST['fecha_inicio'], $_POST['fecha_fin'], $_POST['hora_inicio'], $_POST['hora_fin'], $_POST['total'], $_POST['usuario_id'], $_POST['tipo_alquiler_id']);
        if ($idReservacion) {
            for ($i = 0; $i < count($_POST['serviciosSeleccionados']); $i++) {
                $id = $reservacionModel->insertReservationService($idReservacion, $_POST['serviciosSeleccionados'][$i]['id'], $_POST['serviciosSeleccionados'][$i]['cantidad']);
            }

            if ($id) {
                return array('status' => true, 'msg' => 'Reservacion creada correctamente', 'data' => $id);
            }
        }
    }
    public function updateEstadoReservacion()
    {
        $reservacionModel = new ReservacionModel();
        $res = $reservacionModel->updateEstadoReservacion($_POST['idReservacion'], $_POST['estado']);
        if (!$res) {
            return array('status' => false, 'msg' => 'No se pudo cancelar la reservacion');
        }
        if ($_POST['estado'] == 'Cancelada') {
            return array('status' => true, 'msg' => 'Reservacion cancelada correctamente');
        }
        return array('status' => true, 'msg' => 'Reservacion aceptada correctamente');
    }

    public function getReservacionesCanceladas()
    {
        $reservacionModel = new ReservacionModel();
        $res = $reservacionModel->getReservacionCancelada($_POST['idUsuario']);
        if ($res) {
            return array('status' => true, 'data' => $res);
        }
        return array('status' => false, 'msg' => 'No se encontraron reservaciones');
    }
    public function getReservacionesByEstado()
    {
        $reservacionModel = new ReservacionModel();
        $res = $reservacionModel->getAllReservacionesByEstado();
        if ($res) {
            return array('status' => true, 'data' => $res);
        }
        return array('status' => false, 'msg' => 'No se encontraron reservaciones');
    }
}
