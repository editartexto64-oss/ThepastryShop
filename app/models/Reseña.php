<?php

class Reseña extends Model
{
    private $table = "resenas";

    public function crear($data)
    {
        try {
            $sql = "INSERT INTO {$this->table}
                    (usuario_id, producto_id, calificacion, comentario, fecha)
                    VALUES (:usuario_id, :producto_id, :calificacion, :comentario, NOW())";

            $this->query($sql, $data);
            return true;

        } catch (PDOException $e) {
            return false;
        }
    }

    public function listarPorProducto($id)
    {
        try {
            return $this->query(
                "SELECT r.*, u.nombres 
                 FROM resenas r
                 JOIN usuarios u ON u.id = r.usuario_id
                 WHERE producto_id = :id",
                ['id' => $id]
            )->fetchAll();

        } catch (PDOException $e) {
            return [];
        }
    }
}
