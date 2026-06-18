<?php
// app/controllers/BaseController.php

class BaseController
{

    public function __construct()
    {
        // Iniciamos sesión en el constructor base para que esté disponible en todos lados
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    protected function checkAuth($rolNecesario = null)
    {
        if (!isset($_SESSION['user_id'])) {
            // Si es una petición Fetch, mandamos JSON
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                $this->json('error', 'Sesión expirada', '?url=login');
            } else {
                // Si es carga de página normal, redirección directa
                header('Location: ?url=landing');
                exit;
            }
        }
        
        // Si se necesita un rol para ingresar y no cuenta con el mismo, se redirecciona al home
        if(isset($rolNecesario) && $rolNecesario != $_SESSION['role_id'] ){
            header('Location: ?url=home');
        }

    }

    /**
     * @param string $view  Nombre del archivo ( ej: 'usuarios/register' )
     * @param array  $data  Diccionario de datos para la vista
     */
    protected function render($view, $data = [])
    {
        // Extraemos el array: [ 'alerta' => '...' ] se vuelve la variable $alerta
        extract($data);

        $path = __DIR__ . '/../views/' . $view . '.php';

        if (file_exists($path)) {
            require_once $path;
        } else {
            die("Error: La vista '{$view}' no existe. Verificá la carpeta views.");
        }
    }

    protected function json($status, $message, $redirect = null)
    {
        header('Content-Type: application/json');
        echo json_encode([
            'status'   => $status,
            'message'  => $message,
            'redirect' => $redirect ?? Env::get('APP_URL') // Sin redirect, va al home
        ]);
        exit;
        // Importante para cortar la ejecución aquí
    }
}
