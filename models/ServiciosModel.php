<?php

require_once '../config/database.php';

class ServiciosModel extends Database
{
    public function create($nombre, $detalle, $precio)
    {
        try {
            $pdo = self::connect();

            $stmt = $pdo->prepare("INSERT INTO servicios (nombre, detalle, precio) VALUES (:nombre, :detalle, :precio)");

            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':detalle', $detalle);
            $stmt->bindParam(':precio', $precio);

            $stmt->execute();

            $id_insertado = $pdo->lastInsertId();

            $stmt->closeCursor();
            $pdo = null;

            return $id_insertado;
        } catch (PDOException $e) {
            echo "Error al insertar el servicio: " . $e->getMessage();
            return false;
        }
    }
    public function update($id, $nombre, $detalle, $precio)
    {
        try {
            $pdo = self::connect();
            $stmt = $pdo->prepare("UPDATE servicios SET nombre = :nombre, detalle = :detalle, precio = :precio WHERE id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':detalle', $detalle);
            $stmt->bindParam(':precio', $precio);
            $stmt->execute();
            $stmt->closeCursor();
            $pdo = null;
            return true;
        } catch (PDOException $e) {
            echo "Error al actualizar el servicio: " . $e->getMessage();
            return false;
        }
    }
    public function delete($id)
    {
        try {
            $pdo = self::connect();
            $stmt = $pdo->prepare("DELETE FROM servicios WHERE id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $stmt->closeCursor();
            $pdo = null;
            return true;
        } catch (PDOException $e) {
            echo "Error al eliminar el servicio: " . $e->getMessage();
            return false;
        }
    }
    public function getById($id)
    {
        try {
            $pdo = self::connect();
            $stmt = $pdo->prepare("SELECT * FROM servicios WHERE id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
            $pdo = null;
            return $result;
        } catch (PDOException $e) {
            echo "Error al obtener el servicio: " . $e->getMessage();
            return false;
        }
    }
}
