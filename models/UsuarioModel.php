<?php

require_once '../config/database.php';

class UsuarioModel extends Database
{
    public function getUserById($id)
    {
        $pdo = self::connect();
        $stmt = $pdo->prepare("SELECT * From usuarios where id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_STR);
        $stmt->execute();
        $res = $stmt->fetch(PDO::FETCH_ASSOC);

        $stmt->closeCursor();
        $pdo = null;

        return $res;
    }
    public function getUserByEmail($email)
    {
        $pdo = self::connect();
        $stmt = $pdo->prepare("SELECT * From usuarios where email like :email");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $res = $stmt->fetch(PDO::FETCH_ASSOC);

        $stmt->closeCursor();
        $pdo = null;

        return $res;
    }
    public function create($nombre, $apellido, $telefono, $email, $hashedPassword)
    {
        $pdo = self::connect();
        $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, apellido,telefono,email, password,rol_id) VALUES (:nombre, :apellido, :telefono ,:email, :password, 2)");


        $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $stmt->bindParam(':apellido', $apellido, PDO::PARAM_STR);
        $stmt->bindParam(':telefono', $telefono, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);


        $res = $stmt->execute();
        $stmt->closeCursor();
        $pdo = null;

        return $res;
    }
}
