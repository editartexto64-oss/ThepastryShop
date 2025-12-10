<?php
/**
 * CuponController
 * Valida y aplica cupones durante el checkout.
 */

class CuponController extends Controller
{
    // Valida un código recibido por AJAX/POST
    public function validar()
    {
        $codigo = trim($_POST['codigo'] ?? '');
        if (!$codigo) {
            echo json_encode(['ok' => false, 'msg' => 'Ingrese un cupón.']);
            return;
        }

        try {
            $cuponModel = $this->model('Cupon');
            $cupon = $cuponModel->validar($codigo);

            if (!$cupon) {
                echo json_encode(['ok' => false, 'msg' => 'Cupón inválido o expirado.']);
                return;
            }

            // Verificar fechas y usos
            $hoy = date('Y-m-d');
            if ($hoy < $cupon['fecha_inicio'] || $hoy > $cupon['fecha_fin']) {
                echo json_encode(['ok' => false, 'msg' => 'Cupón fuera de periodo.']);
                return;
            }

            if ($cupon['uso_maximo'] > 0 && $cupon['uso_actual'] >= $cupon['uso_maximo']) {
                echo json_encode(['ok' => false, 'msg' => 'Cupón ya alcanzó su uso máximo.']);
                return;
            }

            // Responder con datos del cupón
            echo json_encode([
                'ok' => true,
                'cupon' => [
                    'id' => $cupon['id_cupon'],
                    'codigo' => $cupon['codigo'],
                    'descuento' => $cupon['descuento_porcentaje']
                ]
            ]);
            return;

        } catch (Exception $e) {
            error_log("Error CuponController::validar - " . $e->getMessage());
            echo json_encode(['ok' => false, 'msg' => 'Error validando cupón.']);
            return;
        }
    }

    // Aplicar cupón en la creación del pedido (ejemplo de uso)
    public function aplicarEnPedido($idPedido, $idCupon, $total)
    {
        try {
            $cuponUsoModel = $this->model('CuponUso');
            $descuento = ($total * floatval($this->model('Cupon')->obtenerPorId($idCupon)['descuento_porcentaje']) ) / 100.0;
            $cuponUsoModel->registrarUso($idPedido, $idCupon, $descuento);
            $this->model('Cupon')->incrementarUso($idCupon);
            return $descuento;
        } catch (Exception $e) {
            error_log("Error al aplicar cupón: " . $e->getMessage());
            return 0;
        }
    }
}
