<?php

class AuthController extends Controller
{
    // Mostrar formulario de login
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->procesarLogin();
            return;
        }

        return $this->view("auth/login");
    }

    // Procesar login
    private function procesarLogin()
    {
        $correo = trim($_POST['correo'] ?? '');
        $contrasena = trim($_POST['contrasena'] ?? '');

        // Cargar modelo usuario
        $usuarioModel = $this->model("Usuario");
        $usuario = $usuarioModel->buscarPorCorreo($correo);

        if (!$usuario) {
            $_SESSION['error'] = "Correo no registrado";
            header("Location: " . BASE_URL . "login");
            return;
        }

        if (!password_verify($contrasena, $usuario['contrasena'])) {
            $_SESSION['error'] = "Contraseña incorrecta";
            header("Location: " . BASE_URL . "login");
            return;
        }

        // Iniciar sesión
        $_SESSION['usuario'] = $usuario;

        header("Location: " . BASE_URL);
    }

    // Mostrar formulario de registro
    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->procesarRegistro();
            return;
        }

        return $this->view("auth/register");
    }

    // Procesar registro
    private function procesarRegistro()
    {
        $usuarioModel = $this->model("Usuario");

        // Sanitización
        $data = [
            'nombres' => trim($_POST['nombres']),
            'apellidos' => trim($_POST['apellidos']),
            'correo' => trim($_POST['correo']),
            'contrasena' => password_hash($_POST['contrasena'], PASSWORD_BCRYPT),
            'telefono' => trim($_POST['telefono']),
            'direccion' => trim($_POST['direccion']),
            'activo' => 1
        ];

        $id = $usuarioModel->crear($data);

        if (!$id) {
            $_SESSION['error'] = "Ocurrió un error al registrar tu cuenta";
            header("Location: " . BASE_URL . "register");
            return;
        }

        $_SESSION['success'] = "Cuenta creada correctamente, ahora puedes iniciar sesión.";
        header("Location: " . BASE_URL . "login");
    }

    // Cerrar sesión
    public function logout()
    {
        session_destroy();
        header("Location: " . BASE_URL);
    }
}
