<?php
require_once __DIR__ . '/../models/Log.php';

class SaveActivityLog
{
    private $pdo;
    private $logModel;


    public function __construct()
    {
        global $pdo;
        $this->pdo = $pdo;
        $this->logModel = new Log($pdo);
    }

    public function getLast5ActivityLogC()
    {
        try {
            return $this->logModel->getLast5ActivityLog();
        } catch (\Exception $e) {
            return  $e->getMessage();
        }
    }

    private function buildLog(string $type, array $data)
{
    return match ($type) {
        'coach_registered'   => [$type, $data['email']],
        'swimmer_registered' => [$type, $data['email']],
        'profile_completed'  => [$type, $data['name']],
        'class_created'      => [$type, $data['class_name']],
        'class_deleted'      => [$type, $data['class_name']],
        'swimmer_enrolled'   => [$type, $data['name'] . '|' . $data['class_name']],
        'swimmer_self_registered' => [$type, $data['name']],
        default => throw new \InvalidArgumentException("Tipo desconocido: $type")
    };
}

    public  function newLog(string $type, array $data)
    {
        [$type, $subject] = $this->buildLog($type, $data);
        $this->logModel->newActivityLog($type, $subject);
    }
}
