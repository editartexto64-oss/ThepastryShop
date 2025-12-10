<?php

class Pedido extends Model
{
    private $table = "pedidos";

    public function crear($data)
    {
        try {
            $sql = "INSERT INTO {$this->table}
                    (usuario_id, total, fecha_pedido, estado, token_tracking)
                    VALUES (:usuario_id, :total, NOW(), :estado, :token_tracking)";

            $this->query($sql, $data);
            return $this->db->lastInsertId();

        } catch (PDOException $e) {
            return false;
        }
    }

    public function listarPorUsuario($usuario_id)
    {
        try {
            return $this->query(
                "SELECT * FROM {$this->table} WHERE usuario_id = :u",
                ['u' => $usuario_id]
            )->fetchAll();

        } catch (PDOException $e) {
            return [];
        }
    }
     // listar todos los pedidos para el panel admin
    public function listarTodos()
    {
        try {
            return $this->query("SELECT * FROM {$this->table} ORDER BY fecha_pedido DESC")
                        ->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }

     // Obtener pedido por ID
    public function obtenerPorId($id)
    {
        try {
            return $this->query(
                "SELECT * FROM {$this->table} WHERE id_pedido = :id",
                ['id' => $id]
            )->fetch();
        } catch (PDOException $e) {
            return null;
        }
    }
    // Buscar pedido por token tracking
    public function buscarPorToken($token)
    {
        try {
            return $this->query(
                "SELECT * FROM {$this->table} WHERE token_tracking = :t LIMIT 1",
                ['t' => $token]
            )->fetch();
        } catch (PDOException $e) {
            return null;
        }
    }

     // Actualizar contacto asociado al pedido
    public function actualizarContacto($id, $data)
    {
        try {
            $sql = "UPDATE {$this->table}
                    SET direccion_entrega = :direccion,
                        telefono_contacto = :telefono,
                        correo_contacto = :correo
                    WHERE id_pedido = :id";

            return $this->query($sql, [
                'direccion' => $data['direccion_entrega'],
                'telefono'  => $data['telefono_contacto'],
                'correo'    => $data['correo_contacto'],
                'id'        => $id
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }
}
