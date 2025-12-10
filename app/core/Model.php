<?php

class Model
{
    protected $db;

    public function __construct()
    {
        require '../app/config/database.php';
        $this->db = $pdo;
    }

    protected function query($sql, $params = [])
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
}
