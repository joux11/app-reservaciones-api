<?php
require_once '../config/config.php';

class TiposAlquilerModel extends Database
{
    public function getAll()
    {
        try {
            $pdo = self::connect();
            $query = $pdo->prepare("SELECT * FROM tipo_alquiler");
            $query->execute();
            $result = $query->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }
    public function getById($id)
    {
        try {
            $pdo = self::connect();
            $stmt = $pdo->prepare("SELECT * FROM tipo_alquiler WHERE id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }
    public function insert($nombre, $precio)
    {
        try {
            $pdo = self::connect();
            $stmt = $pdo->prepare("INSERT INTO tipo_alquiler (nombre, precio) VALUES (:nombre,:precio)");
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':precio', $precio);
            $stmt->execute();
            return $stmt->rowCount();
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }
    public function update($id, $nombre, $precio)
    {
        try {
            $pdo = self::connect();
            $stmt = $pdo->prepare("UPDATE tipo_alquiler SET nombre = :nombre, precio = :precio WHERE id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':precio', $precio);
            $stmt->execute();
            return $stmt->rowCount();
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }
    public function delete($id)
    {
        try {
            $pdo = self::connect();
            $stmt = $pdo->prepare("DELETE FROM tipo_alquiler WHERE id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->rowCount();
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }
}
