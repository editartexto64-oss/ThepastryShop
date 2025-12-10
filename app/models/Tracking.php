<?php

/**
 * Modelo: Tracking
 *
 * Gestiona:
 * - Obtención de tracking por token
 * - Obtención por ID de pedido
 * - Actualización del estado
 * - Registro inicial del tracking
 */

class Tracking extends Model
{
    private $table= 'tracking';

   public function obtenerPorId(int $id_tracking)
    {
        try {
            $sql = "SELECT * FROM tracking WHERE id_tracking = :id LIMIT 1";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id_tracking, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("Tracking::obtenerPorId → " . $e->getMessage());
            return false;
        }
    }

    // ============================================================
    //  OBTENER TRACKING POR TOKEN PÚBLICO
    // ============================================================

    public function obtenerPorToken(string $token)
    {
        try {
            $sql = "SELECT t.*, p.nombre_cliente, p.total, p.fecha_registro
                    FROM tracking t
                    INNER JOIN pedidos p ON p.id_pedido = t.id_pedido
                    WHERE t.token = :token AND t.activo = 1
                    LIMIT 1";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':token', $token, PDO::PARAM_STR);

            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("Error obtenerPorToken: " . $e->getMessage());
            return false;
        }
    }


    // ============================================================
    //  OBTENER TRACKING POR ID DE PEDIDO (ADMIN)
    // ============================================================

    public function obtenerPorPedido(int $id_pedido)
    {
        try {
            $sql = "SELECT * FROM tracking
                    WHERE id_pedido = :id_pedido
                    LIMIT 1";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id_pedido', $id_pedido, PDO::PARAM_INT);

            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("Error obtenerPorPedido: " . $e->getMessage());
            return false;
        }
    }


    // ============================================================
    //  ACTUALIZAR ESTADO DEL TRACKING (ADMIN)
    // ============================================================

    public function actualizarEstado(int $id_pedido, string $estado)
    {
        try {
            $sql = "UPDATE tracking
                    SET estado = :estado,
                        fecha_actualizacion = NOW()
                    WHERE id_pedido = :id_pedido";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':estado', $estado, PDO::PARAM_STR);
            $stmt->bindParam(':id_pedido', $id_pedido, PDO::PARAM_INT);

            return $stmt->execute();

        } catch (PDOException $e) {
            error_log("Error actualizarEstado: " . $e->getMessage());
            return false;
        }
    }


    // ============================================================
    //  REGISTRAR TRACKING AL CREAR EL PEDIDO
    // ============================================================

    public function crearTracking(int $id_pedido, string $token)
    {
        try {
            $sql = "INSERT INTO tracking 
                    (id_pedido, token, estado, fecha_registro, activo)
                    VALUES (:id_pedido, :token, 'Recibido', NOW(), 1)";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id_pedido', $id_pedido, PDO::PARAM_INT);
            $stmt->bindParam(':token', $token, PDO::PARAM_STR);

            return $stmt->execute();

        } catch (PDOException $e) {
            error_log("Error crearTracking: " . $e->getMessage());
            return false;
        }
    }
}

