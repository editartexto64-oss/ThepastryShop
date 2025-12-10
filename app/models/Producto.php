<?php

class Producto extends Model
{
    private $table = "productos";

    public function crear($data)
    {
        try {
            $sql = "INSERT INTO {$this->table}
                    (categoria_id, nombre, descripcion, precio, imagen, stock, activo)
                    VALUES (:categoria_id, :nombre, :descripcion, :precio, :imagen, :stock, :activo)";
            
            $this->query($sql, $data);
            return true;

        } catch (PDOException $e) {
            return false;
        }
    }

    public function listarActivos()
    {
        try {
            return $this->query("SELECT * FROM {$this->table} WHERE activo = 1")->fetchAll();

        } catch (PDOException $e) {
            return [];
        }
    }
   
    public function obtener($id)
    {
        try {
            return $this->query(
                "SELECT * FROM {$this->table} WHERE id = :id",
                ['id' => $id]
            )->fetch();

        } catch (PDOException $e) {
            return null;
        }
    }
    // Listar para admin (incluye inactivos)
    public function listarTodos()
    {
        try {
            return $this->query("SELECT * FROM {$this->table}")
                        ->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }

    // Editar producto
    public function editar($id, $data)
    {
        try {
            $sql = "UPDATE {$this->table}
                    SET categoria_id = :categoria_id,
                        nombre = :nombre,
                        descripcion = :descripcion,
                        precio = :precio,
                        imagen = :imagen,
                        stock = :stock,
                        activo = :activo
                    WHERE id = :id";

            $data['id'] = $id;

            return $this->query($sql, $data);
        } catch (PDOException $e) {
            return false;
        }
    }

    // Activar/inactivar producto
    public function setActivo($id, $estado)
    {
        try {
            return $this->query(
                "UPDATE {$this->table} SET activo = :a WHERE id = :id",
                ['a' => $estado, 'id' => $id]
            );
        } catch (PDOException $e) {
            return false;
        }
    }

    // Reducir stock al comprar
    public function reducirStock($idProducto, $cantidad)
    {
        try {
            return $this->query(
                "UPDATE {$this->table}
                 SET stock = stock - :c
                 WHERE id = :id AND stock >= :c",
                [
                    'c' => $cantidad,
                    'id' => $idProducto
                ]
            );
        } catch (PDOException $e) {
            return false;
        }
    }


}
