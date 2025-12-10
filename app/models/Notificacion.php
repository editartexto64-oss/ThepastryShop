<?php

use PHPMailer\PHPMailer\Exception;

class Notificacion extends Model
{
    protected $tabla = "notificaciones";

    /**
     * Crear una notificación en la base de datos.
     * $data = [
     *   'id_usuario' => int|null,
     *   'id_pedido'  => int|null,
     *   'asunto'     => string,
     *   'mensaje'    => string,
     *   'tipo'       => 'confirmación'|'actualización'|'entrega'
     * ]
     */
    public function crear(array $data)
    {
        try {
            $sql = "INSERT INTO {$this->tabla} 
                    (id_usuario, id_pedido, fecha_envio, asunto, mensaje, tipo, activo)
                    VALUES (:id_usuario, :id_pedido, NOW(), :asunto, :mensaje, :tipo, 1)";

            $params = [
                'id_usuario' => $data['id_usuario'] ?? null,
                'id_pedido'  => $data['id_pedido'] ?? null,
                'asunto'     => $data['asunto'],
                'mensaje'    => $data['mensaje'],
                'tipo'       => $data['tipo'] ?? 'actualización'
            ];

            $this->query($sql, $params);
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            // Loguear el error según tu sistema de logs
            error_log("Notificacion::crear - " . $e->getMessage());
            return false;
        }
    }

    /**
     * Listar notificaciones activas de un usuario
     */
    public function listarPorUsuario(int $id_usuario)
    {
        try {
            $sql = "SELECT * FROM {$this->tabla}
                    WHERE id_usuario = :id_usuario AND activo = 1
                    ORDER BY fecha_envio DESC";
            return $this->query($sql, ['id_usuario' => $id_usuario])->fetchAll();
        } catch (PDOException $e) {
            error_log("Notificacion::listarPorUsuario - " . $e->getMessage());
            return [];
        }
    }

    /**
     * Listar notificaciones asociadas a un pedido
     */
    public function listarPorPedido(int $id_pedido)
    {
        try {
            $sql = "SELECT * FROM {$this->tabla}
                    WHERE id_pedido = :id_pedido AND activo = 1
                    ORDER BY fecha_envio DESC";
            return $this->query($sql, ['id_pedido' => $id_pedido])->fetchAll();
        } catch (PDOException $e) {
            error_log("Notificacion::listarPorPedido - " . $e->getMessage());
            return [];
        }
    }

    /**
     * Marcar una notificación como inactiva (soft delete) o leída
     */
    public function marcarInactiva(int $id_notificacion)
    {
        try {
            $sql = "UPDATE {$this->tabla} SET activo = 0 WHERE id_notificacion = :id";
            $this->query($sql, ['id' => $id_notificacion]);
            return true;
        } catch (PDOException $e) {
            error_log("Notificacion::marcarInactiva - " . $e->getMessage());
            return false;
        }
    }

    /**
     * Enviar correo de notificación (usa mailer() en /app/config/mailer.php).
     * Devuelve true si el correo fue enviado, false en caso contrario.
     */
    public function enviarCorreo(string $toEmail, string $toName, string $asunto, string $mensaje): bool
    {
        try {
            // Obtener instancia PHPMailer desde la función helper mailer()
            $mail = mailer(); // asume que existe la función mailer() que devuelve PHPMailer configurado

            $mail->addAddress($toEmail, $toName);
            $mail->Subject = $asunto;
            // Enviar en HTML y texto plano
            $mail->isHTML(true);
            $mail->Body    = $mensaje;
            $mail->AltBody = strip_tags($mensaje);

            $mail->send();
            return true;
        } catch (Exception $e) {
            // Registrar error de envío
            error_log("Notificacion::enviarCorreo - " . $e->getMessage());
            return false;
        }
    }

    /**
     * Registrar notificación en BD y (opcional) enviar correo.
     * $data = [
     *   'id_usuario' => int|null,
     *   'id_pedido'  => int|null,
     *   'asunto'     => string,
     *   'mensaje'    => string,
     *   'tipo'       => string,
     *   'enviar_email' => bool,
     *   'email'      => string|null,
     *   'nombre'     => string|null
     * ]
     */
    public function registrarYEnviar(array $data)
    {
        try {
            $idNotificacion = $this->crear($data);
            $enviado = true;

            if (!empty($data['enviar_email']) && !empty($data['email'])) {
                $enviado = $this->enviarCorreo(
                    $data['email'],
                    $data['nombre'] ?? '',
                    $data['asunto'],
                    $data['mensaje']
                );
            }

            return [
                'id' => $idNotificacion,
                'email_enviado' => $enviado
            ];
        } catch (Exception $e) {
            error_log("Notificacion::registrarYEnviar - " . $e->getMessage());
            return false;
        }
    }
}
