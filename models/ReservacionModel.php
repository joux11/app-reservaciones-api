<?php

require_once '../config/database.php';

class ReservacionModel extends Database
{
    public function getAllReservaciones()
    {
        $pdo = self::connect();
        $stmt = $pdo->prepare("SELECT r.id,r.fecha_registro ,r.fecha_inicio, r.fecha_fin, r.estado, ta.nombre AS tipo_alquiler FROM reservaciones r INNER JOIN tipo_alquiler ta ON r.tipo_alquiler_id = ta.id where r.estado != 'Cancelada'");

        $stmt->execute();
        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt->closeCursor();
        $pdo = null;
        return $res;
    }
    public function getDateRerservacionesById($idUsuario)
    {
        $pdo = self::connect();
        $stmt = $pdo->prepare("SELECT r.id, r.fecha_inicio, r.fecha_fin AS tipo_alquiler FROM reservaciones r INNER JOIN tipo_alquiler ta ON r.tipo_alquiler_id = ta.id WHERE r.usuario_id = :idUsuario;");
        $stmt->bindParam(':idUsuario', $idUsuario);
        $stmt->execute();
        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt->closeCursor();
        $pdo = null;
        return $res;
    }
    public function getReservacionesbyIdUsuario($idUsuario)
    {
        $pdo = self::connect();
        $stmt = $pdo->prepare("SELECT r.id,r.fecha_registro ,r.fecha_inicio, r.fecha_fin, r.estado, ta.nombre AS tipo_alquiler FROM reservaciones r INNER JOIN tipo_alquiler ta ON r.tipo_alquiler_id = ta.id WHERE r.usuario_id = :idUsuario  and r.estado != 'Cancelada'; ");
        $stmt->bindParam(':idUsuario', $idUsuario);
        $stmt->execute();
        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt->closeCursor();
        $pdo = null;
        return $res;
    }

    public function getTipoAlquiler()
    {
        $pdo = self::connect();
        $stmt = $pdo->prepare("SELECT * FROM tipo_alquiler;");
        $stmt->execute();
        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        $pdo = null;
        return $res;
    }
    public function getAllServices()
    {

        $pdo = self::connect();
        $stmt = $pdo->prepare("SELECT * FROM servicios");
        $stmt->execute();
        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        $pdo = null;
        return $res;
    }
    public function create($fecha_inicio, $fecha_fin, $hora_inicio, $hora_fin, $total, $usuario_id, $tipo_alquiler_id)
    {
        try {
            $pdo = self::connect();


            $stmt = $pdo->prepare("INSERT INTO reservaciones (fecha_inicio, fecha_fin, hora_inicio, hora_fin, estado, total, usuario_id, tipo_alquiler_id) 
                               VALUES (:fecha_inicio, :fecha_fin, :hora_inicio, :hora_fin, 'Pendiente', :total, :usuario_id, :tipo_alquiler_id)");


            $stmt->bindParam(':fecha_inicio', $fecha_inicio);
            $stmt->bindParam(':fecha_fin', $fecha_fin);
            $stmt->bindParam(':hora_inicio', $hora_inicio);
            $stmt->bindParam(':hora_fin', $hora_fin);
            $stmt->bindParam(':total', $total);
            $stmt->bindParam(':usuario_id', $usuario_id);
            $stmt->bindParam(':tipo_alquiler_id', $tipo_alquiler_id);

            $stmt->execute();


            $reservacion_id = $pdo->lastInsertId();




            $stmt->closeCursor();
            $pdo = null;

            return $reservacion_id;
        } catch (PDOException $e) {
            // Manejar errores de base de datos
            echo "Error al insertar la reservación: " . $e->getMessage();
            return false;
        }
    }

    public function insertReservationService($reservacion_id, $servicio_id, $cantidad)
    {
        try {
            $pdo = self::connect();

            $stmt = $pdo->prepare("INSERT INTO reservaciones_servicios (reservacion_id, servicio_id, cantidad) 
                               VALUES (:reservacion_id, :servicio_id, :cantidad)");

            $stmt->bindParam(':reservacion_id', $reservacion_id);
            $stmt->bindParam(':servicio_id', $servicio_id);
            $stmt->bindParam(':cantidad', $cantidad);

            $stmt->execute();

            $servicio_reservacion_id = $pdo->lastInsertId();

            $stmt->closeCursor();
            $pdo = null;

            return $servicio_reservacion_id;
        } catch (PDOException $e) {
            echo "Error al insertar el servicio asociado a la reservación: " . $e->getMessage();
            return false;
        }
    }

    public function getReservacionById($reservacion_id)
    {
        try {
            $pdo = self::connect();

            $stmt = $pdo->prepare("SELECT r.*, ta.nombre AS nombre_tipo_alquiler, ta.precio FROM reservaciones r INNER JOIN tipo_alquiler ta ON r.tipo_alquiler_id = ta.id where r.id = :reservacion_id");


            $stmt->bindParam(':reservacion_id', $reservacion_id);

            $stmt->execute();

            $reservacion = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $stmt->closeCursor();
            $pdo = null;

            return $reservacion;
        } catch (PDOException $e) {
            echo "Error al obtener los datos de la reservación: " . $e->getMessage();
            return false;
        }
    }

    public function getServiciosByReservacion($reservacion_id)
    {
        try {
            $pdo = self::connect();
            $stmt = $pdo->prepare("SELECT rs.*, s.* FROM reservaciones_servicios rs JOIN servicios s ON rs.servicio_id = s.id WHERE rs.reservacion_id = :reservacion_id");
            $stmt->bindParam(':reservacion_id', $reservacion_id);
            $stmt->execute();
            $servicios = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
            $pdo = null;
            return $servicios;
        } catch (PDOException $e) {
            echo "Error al obtener los datos de la reservación: " . $e->getMessage();
            return false;
        }
    }

    public function updateEstadoReservacion($reservacion_id, $estado)
    {
        try {
            $pdo = self::connect();
            $stmt = $pdo->prepare("UPDATE reservaciones SET estado = :estado WHERE id = :reservacion_id");
            $stmt->bindParam(':reservacion_id', $reservacion_id);
            $stmt->bindParam(':estado', $estado);
            $stmt->execute();
            $stmt->closeCursor();
            $pdo = null;


            return true;
        } catch (PDOException $e) {

            return false;
        }
    }
    public function getReservacionCancelada($idUsuario)
    {
        $pdo = self::connect();
        $stmt = $pdo->prepare("SELECT r.id,r.fecha_registro ,r.fecha_inicio, r.fecha_fin, r.estado, ta.nombre  AS nombre_tipo_alquiler FROM reservaciones r INNER JOIN tipo_alquiler ta ON r.tipo_alquiler_id = ta.id WHERE r.usuario_id = :idUsuario  and r.estado = 'Cancelada'; ");
        $stmt->bindParam(':idUsuario', $idUsuario);
        $stmt->execute();
        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt->closeCursor();
        $pdo = null;
        return $res;
    }

    public function getAllReservacionesByEstado()
    {

        $pdo = self::connect();
        $stmt = $pdo->prepare("SELECT r.id, r.fecha_registro, r.fecha_inicio, r.fecha_fin, r.estado, ta.nombre AS tipo_alquiler, CONCAT(u.nombre,' ',u.apellido) as nombreUsuario FROM reservaciones r INNER JOIN tipo_alquiler ta ON r.tipo_alquiler_id = ta.id INNER JOIN usuarios u ON r.usuario_id = u.id WHERE r.estado != 'Cancelada' ORDER BY r.fecha_registro ASC;");

        $stmt->execute();
        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt->closeCursor();
        $pdo = null;
        return $res;
    }
}
