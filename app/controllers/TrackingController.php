<?php

/**
 * Controlador: TrackingController
 *
 * Controla:
 * - Consulta pública por token
 * - Vista y edición del tracking en zona admin
 * - Actualización de estado del pedido
 *
 * Requiere:
 * Modelos: Tracking, Pedido
 */

class TrackingController extends Controller
{
    private $trackingModel;
    private $pedidoModel;

    public function __construct()
    {
        $this->trackingModel = new Tracking();
        $this->pedidoModel   = new Pedido();
    }


    // ============================================================
    //  VISTA PÚBLICA DE CONSULTA DE TRACKING
    // ============================================================

    public function index()
    {
        $data = [
            "token" => "",
            "trackingData" => null,
            "error" => ""
        ];

        return $this->view("tracking/consultar", $data);
    }


    // ============================================================
    //  BÚSQUEDA DE TRACKING POR TOKEN (PÚBLICO)
    // ============================================================

    public function buscar()
    {
        try {

            $token = trim($_POST['token'] ?? "");

            if ($token === "") {
                return $this->view("tracking/consultar", [
                    "error" => "Debes ingresar un código de seguimiento."
                ]);
            }

            // Sanitizar token
            $token = htmlspecialchars($token);

            $tracking = $this->trackingModel->obtenerPorToken($token);

            if (!$tracking) {
                return $this->view("tracking/consultar", [
                    "token"        => $token,
                    "error"        => "No se encontró ningún pedido con ese código.",
                    "trackingData" => null
                ]);
            }

            return $this->view("tracking/consultar", [
                "token"        => $token,
                "trackingData" => $tracking,
                "error"        => ""
            ]);

        } catch (Exception $e) {

            ErrorHandler::mostrar500();
        }
    }


    // ============================================================
    //  ADMIN: VER TRACKING DE UN PEDIDO
    // ============================================================

    public function adminVer($id_pedido)
    {
        try {

            $id = (int)$id_pedido;

            $pedido = $this->pedidoModel->obtenerPorId($id);

            if (!$pedido) {
                ErrorHandler::mostrar404();
                return;
            }

            $tracking = $this->trackingModel->obtenerPorPedido($id);

            if (!$tracking) {
                return $this->view("errores/500", [
                    "mensaje" => "El pedido no tiene tracking asociado."
                ]);
            }

            return $this->view("pedidos/admin_tracking", [
                "pedido"   => $pedido,
                "tracking" => $tracking
            ]);

        } catch (Exception $e) {
            ErrorHandler::mostrar500();
        }
    }


    // ============================================================
    //  ADMIN: ACTUALIZAR ESTADO DEL TRACKING
    // ============================================================

    public function actualizar()
    {
        try {

            if ($_SERVER['REQUEST_METHOD'] !== "POST") {
                ErrorHandler::mostrar404();
                return;
            }

            $id_pedido = (int)($_POST['id_pedido'] ?? 0);
            $estado    = trim($_POST['estado'] ?? "");

            if (!$id_pedido || $estado === "") {
                return $this->redirect("pedidos/admin?error=datos_invalidos");
            }

            // Validar estado permitido
            $estadosValidos = [
                "Recibido",
                "En preparación",
                "En camino",
                "Entregado"
            ];

            if (!in_array($estado, $estadosValidos)) {
                return $this->redirect("pedidos/admin?error=estado_invalido");
            }

            // Actualizar en BD
            $this->trackingModel->actualizarEstado($id_pedido, $estado);

            return $this->redirect("tracking/adminVer/" .$id_pedido. "?ok=1");

        } catch (Exception $e) {
            ErrorHandler::mostrar500();
        }
    }
}
