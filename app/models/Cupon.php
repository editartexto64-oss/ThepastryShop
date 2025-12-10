<?php

class Cupon extends Model
{
    private $table = "cupones";

    public function validar($codigo)
    {
        try {
            return $this->query(
                "SELECT * FROM {$this->table}
                 WHERE codigo = :c AND activo = 1",
                ['c' => $codigo]
            )->fetch();

        } catch (PDOException $e) {
            return null;
        }
    }
      public function listarTodos()
    {
        try {
            return $this->query("SELECT * FROM {$this->table}")
                        ->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }

    public function editar($id, $data)
    {
        try {
            $sql = "UPDATE {$this->table}
                    SET codigo = :codigo,
                        descripcion = :descripcion,
                        descuento_porcentaje = :descuento_porcentaje,
                        fecha_inicio = :fecha_inicio,
                        fecha_fin = :fecha_fin,
                        uso_maximo = :uso_maximo,
                        activo = :activo
                    WHERE id_cupon = :id";

            $data['id'] = $id;

            return $this->query($sql, $data);

        } catch (PDOException $e) {
            return false;
        }
    }

    public function obtenerPorId($id)
    {
        try {
            return $this->query(
                "SELECT * FROM {$this->table} WHERE id_cupon = :id",
                ['id' => $id]
            )->fetch();
        } catch (PDOException $e) {
            return null;
        }
    }

    public function incrementarUso($id)
    {
        try {
            return $this->query(
                "UPDATE {$this->table}
                 SET uso_actual = uso_actual + 1
                 WHERE id_cupon = :id",
                ['id' => $id]
            );
        } catch (PDOException $e) {
            return false;
        }
    }
}
