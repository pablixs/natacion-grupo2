<?php
require_once __DIR__ . '/../core/BaseController.php';
require_once __DIR__ . '/../models/Statics.php';

class LandingController extends BaseController
{
    private $staticsModel;
    private $pdo;

    public function __construct()
    {
        /** * Usamos 'global $pdo' porque la conexión se crea en otro archivo ( ej. index o database ).
         * Sin esto, el controlador no tendría acceso al objeto de conexión PDO para pasárselo a los modelos.
         * Es una forma de 'inyectar' la base de datos sin volver a conectarse en cada clase.
         */
        global $pdo;
        $this->pdo = $pdo;

        $this->staticsModel = new Statics($pdo);
    }


    public function home()
    {
        $swimmers = $this->staticsModel->getCountActivesSwimmers();
        $coaches = $this->staticsModel->getCountActivesCoaches();
        $activeYears = $this->staticsModel->getActiveYears();

        $this->render('landing.view', ['swimmers' => $swimmers, 'coaches' => $coaches, 'activeYears' => $activeYears]);
    }
}