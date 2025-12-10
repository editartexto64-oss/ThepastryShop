<?php
/**
 * PedidoController
 * Maneja el proceso de checkout, creación de pedido, almacenamiento del detalle
 * y envío de confirmación por correo (token de tracking).
 */

class PedidoController extends Controller
{
    public function checkout()
    {
        // Mostrar página de checkout
        // Validar que haya items en el carrito
        if (empty($_SESSION['carrito'] ?? [])) {
            $_SESSION['error'] = "Tu carrito está vacío.";
            header("Location: " . BASE_URL . "carrito");
            return;
        }

        // Si es POST, procesar pedido
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->procesarPedido();
            return;
        }

        // Mostrar checkout con carrito actual
        return $this->view('pedidos/checkout', [
            'carrito' => $_SESSION['carrito']
        ]);
    }

    /**
     * procesarPedido
     * Reúne datos de formulario, crea pedido, inserta detalle, actualiza stock
     * y envía correo con token de seguimiento.
     */
    private function procesarPedido()
    {
        try {
            // Validaciones mínimas del formulario
            $nombre = trim($_POST['nombre'] ?? '');
            $correo = trim($_POST['correo'] ?? '');
            $telefono = trim($_POST['telefono'] ?? '');
            $direccion = trim($_POST['direccion'] ?? '');

            if (!$nombre || !$correo || !$direccion) {
                $_SESSION['error'] = "Complete los datos obligatorios.";
                header("Location: " . BASE_URL . "checkout");
                return;
            }

            // Calcular total desde carrito
            $carrito = $_SESSION['carrito'] ?? [];
            $total = 0.0;
            foreach ($carrito as $item) {
                $total += floatval($item['precio']) * intval($item['cantidad']);
            }

            // Generar token único
            $token = $this->generarToken();

            // Crear pedido (modelo Pedido)
            $pedidoModel = $this->model('Pedido');
            $pedidoData = [
                'usuario_id' => $_SESSION['usuario']['id'] ?? null,
                'total' => $total,
                'estado' => 'Recibido',
                'token_tracking' => $token
            ];

            $idPedido = $pedidoModel->crear($pedidoData);
            if (!$idPedido) throw new Exception("No se pudo crear el pedido.");

            // Insertar detalle de pedido (modelo DetallePedido) y reducir stock
            $detalleModel = $this->model('DetallePedido');
            $productoModel = $this->model('Producto');

            foreach ($carrito as $item) {
                $detalle = [
                    'pedido_id' => $idPedido,
                    'producto_id' => $item['id'],
                    'cantidad' => $item['cantidad'],
                    'precio_unitario' => $item['precio'],
                    'precio_total' => $item['precio'] * $item['cantidad'],
                    'fecha_entrega' => $_POST['fecha_entrega'] ?? null,
                    'hora_entrega' => $_POST['hora_entrega'] ?? null,
                    'personalizacion' => $item['personalizacion'] ?? null
                ];

                $ok = $detalleModel->agregarProducto($detalle);
                if (!$ok) throw new Exception("Error al guardar el detalle del pedido.");

                // Reducir stock
                $productoModel->reducirStock($item['id'], $item['cantidad']);
            }

            // Guardar datos de contacto en tabla pedidos (si el modelo admite actualización)
            // algunos diseños guardan contacto en la tabla pedidos originalmente;
            // si tu modelo Pedido tiene un método actualizarContacto, llámalo aquí.
            if (method_exists($pedidoModel, 'actualizarContacto')) {
                $pedidoModel->actualizarContacto($idPedido, [
                    'direccion_entrega' => $direccion,
                    'telefono_contacto' => $telefono,
                    'correo_contacto' => $correo,
                ]);
            }

            // Enviar correo de confirmación con token (PHPMailer)
            if (function_exists('mailer')) {
                $mail = mailer();
                try {
                    $mail->addAddress($correo, $nombre);
                    $mail->isHTML(true);
                    $mail->Subject = "Confirmación de pedido - The PastryShop";
                    $body = "<p>Hola {$nombre},</p>";
                    $body .= "<p>Hemos recibido tu pedido. Tu código de seguimiento es: <strong>{$token}</strong></p>";
                    $body .= "<p>Puedes consultar el estado en: <a href='" . BASE_URL . "tracking'>Tracking</a></p>";
                    $mail->Body = $body;
                    $mail->send();
                } catch (Exception $e) {
                    // Registrar error pero no abortar el flujo
                    error_log("Error enviando correo de confirmación: " . $e->getMessage());
                }
            }

            // Limpiar carrito y redirigir a confirmación
            unset($_SESSION['carrito']);
            $_SESSION['success'] = "Pedido registrado correctamente. Revisa tu correo para el token de tracking.";
            header("Location: " . BASE_URL . "confirmar?pedido=" . $idPedido);
            return;

        } catch (Exception $e) {
            error_log("Error en procesarPedido: " . $e->getMessage());
            $_SESSION['error'] = "Ocurrió un error procesando el pedido. Intenta nuevamente.";
            header("Location: " . BASE_URL . "checkout");
            return;
        }
    }

    // Página de confirmación simple
    public function confirmar()
    {
        $pedidoId = intval($_GET['pedido'] ?? 0);
        return $this->view('pedidos/confirmacion', ['id_pedido' => $pedidoId]);
    }

    // Genera token alfanumérico
    private function generarToken($length = 12)
    {
        return substr(bin2hex(random_bytes(ceil($length/2))), 0, $length);
    }
}
