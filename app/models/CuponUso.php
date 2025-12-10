<?php

class CuponUso extends Model
{
    private $table = "cupon_uso";

    public function registrarUso($idPedido, $idCupon, $descuento)
    {
        try {
            $sql = "INSERT INTO {$this->table}
                    (pedido_id, cupon_id, descuento_aplicado, fecha_uso)
                    VALUES (:pedido, :cupon, :descuento, NOW())";

            return $this->query($sql, [
                'pedido' => $idPedido,
                'cupon'  => $idCupon,
                'descuento' => $descuento
            ]);

        } catch (PDOException $e) {
            return false;
        }
    }
}
