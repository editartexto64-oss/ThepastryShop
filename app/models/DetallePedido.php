<?php

class DetallePedido extends Model
{
    private $table = "detalle_pedido";

    public function agregarProducto($data)
    {
        try {
            $sql = "INSERT INTO {$this->table}
                    (pedido_id, producto_id, cantidad, precio_unitario)
                    VALUES (:pedido_id, :producto_id, :cantidad, :precio_unitario)";

            $this->query($sql, $data);
            return true;

        } catch (PDOException $e) {
            return false;
        }
    }

    public function obtenerPorPedido($id)
    {
        try {
            return $this->query(
                "SELECT dp.*, p.nombre 
                 FROM detalle_pedido dp 
                 JOIN productos p ON p.id = dp.producto_id
                 WHERE dp.pedido_id = :id",
                ['id' => $id]
            )->fetchAll();

        } catch (PDOException $e) {
            return [];
        }
    }
}
