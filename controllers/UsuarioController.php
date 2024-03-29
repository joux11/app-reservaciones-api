<?php
require_once '../models/UsuarioModel.php';
class UsuarioController
{
    public function login()
    {
        if (empty($_POST['email'])) {
            return array('status' => false, 'msg' => 'El email es requerido');
        }
        if (empty($_POST['password'])) {
            return array('status' => false, 'msg' => 'La password es requerida');
        }


        $usuarioModel = new UsuarioModel();
        $hashedPassword = hash('sha256', $_POST['password']);
        $user = $usuarioModel->getUserByEmail($_POST['email']);

        if ($user && $hashedPassword == $user['password']) {
            unset($user['password']);
            return array('status' => true, 'msg' => 'Login correcto', 'data' => $user);
        }

        return array('status' => false, 'msg' => 'Usuario/Passworrd incorrecto');
    }

    public function register()
    {
        $usuarioModel = new UsuarioModel();
        $user = $usuarioModel->getUserByEmail($_POST['email']);
        if ($user) {
            return array('status' => false, 'msg' => 'El correo electronico ya se encuentra registrado');
        }
        $hashedPassword = hash('sha256', $_POST['password']);
        $user = $usuarioModel->create($_POST['nombre'], $_POST['apellido'], $_POST['telefono'], $_POST['email'], $hashedPassword);
        return array('status' => true, 'msg' => 'Usuario creado');
    }
    public function getAllById()
    {
        $usuarioModel = new UsuarioModel();
        $user = $usuarioModel->getUserById($_POST['idUsuario']);
        if ($user) {
            unset($user['password']);
            return array('status' => true, 'data' => $user);
        }
        return array('status' => false, 'msg' => "No hay usuario");
    }
}
