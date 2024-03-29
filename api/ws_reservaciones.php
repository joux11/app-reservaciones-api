<?php

require_once 'controllers_import.php';

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: *');
header('Access-Control-Allow-Headers: Origin, Content-Type, Authorization, Accept, X-Requested-With, x-xsrf-token');
header('Content-Type: application/json; charset=utf-8');

$_POST = json_decode(file_get_contents('php://input'), true);

$user = new UsuarioController();
$reservaciones = new ReservacionController();
$servicios = new ServicioController();
$tipoAlquiler = new TipoAlquilerController();

$recovery = new RecoveryDatabase();

if (isset($_POST['accion'])) {

    if ($_POST['accion'] == "login") {
        $response = $user->login();
        echo json_encode($response);
    }
    if ($_POST['accion'] == "register") {
        $response = $user->register();
        echo json_encode($response);
    }
    if ($_POST['accion'] == "getUser") {
        $response = $user->getAllById();
        echo json_encode($response);
    }

    /* RESERVACIONES */
    if ($_POST['accion'] == "getReservacion") {
        $response = $reservaciones->getReservacionById();
        echo json_encode($response);
    }
    if ($_POST['accion'] == "getServiciosByReservacion") {
        $response = $reservaciones->getServiciosByReservacion();
        echo json_encode($response);
    }

    if ($_POST['accion'] == "getReservacionesUsuario") {
        $response = $reservaciones->getReservacionByIdUsuario();
        echo json_encode($response);
    }
    if ($_POST['accion'] == "getReservaciones") {
        $response = $reservaciones->getAllReservaciones();
        echo json_encode($response);
    }
    if ($_POST['accion'] == "getTiposAlquiler") {
        $response = $reservaciones->getTipoAlquiler();
        echo json_encode($response);
    }

    if ($_POST['accion'] == "createReservacion") {
        $response = $reservaciones->insertReservacion();
        echo json_encode($response);
    }
    if ($_POST['accion'] == "updateEstadoReservacion") {
        $response = $reservaciones->updateEstadoReservacion();
        echo json_encode($response);
    }
    if ($_POST['accion'] == "getCanceladasReservaciones") {
        $response = $reservaciones->getReservacionesCanceladas();
        echo json_encode($response);
    }
    if ($_POST['accion'] == "getReservacionesByEstado") {
        $response = $reservaciones->getReservacionesByEstado();
        echo json_encode($response);
    }

    /* SERVICIOS */
    if ($_POST['accion'] == "insertServicio") {
        $response = $servicios->insertServicio();
        echo json_encode($response);
    }
    if ($_POST['accion'] == "getAllServicios") {
        $response = $reservaciones->getAllServicios();
        echo json_encode($response);
    }
    if ($_POST['accion'] == "deleteServicio") {
        $response = $servicios->deleteServicio();
        echo json_encode($response);
    }
    if ($_POST['accion'] == "updateServicio") {
        $response = $servicios->updateServicio();
        echo json_encode($response);
    }
    if ($_POST['accion'] == "getServicioById") {
        $response = $servicios->getServicioById();
        echo json_encode($response);
    }

    /* TIPO DE ALQUILER */
    if ($_POST['accion'] == "insertTipoAlquiler") {
        $response = $tipoAlquiler->insert();
        echo json_encode($response);
    }
    if ($_POST['accion'] == "deleteTipoAlquiler") {
        $response = $tipoAlquiler->delete();
        echo json_encode($response);
    }
    if ($_POST['accion'] == "updateTipoAlquiler") {
        $response = $tipoAlquiler->update();
        echo json_encode($response);
    }
    if ($_POST['accion'] == "getTipoAlquiler") {
        $response = $tipoAlquiler->get();
        echo json_encode($response);
    }
    if ($_POST['accion'] == "getAllTipoAlquiler") {
        $response = $tipoAlquiler->getAll();
        echo json_encode($response);
    }

    /* backup base de datos */
    if ($_POST['accion'] == "backup") {
        $recovery->backupDatabase();
    }
} else {
    echo json_encode(array());
}
