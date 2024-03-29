<?php
require_once 'config.php';
class Database
{
    public function connect()
    {
        $db = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
        return $db;
    }
}
