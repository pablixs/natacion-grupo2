<?php
// app/config/db.php

/**
 * Usamos getenv para mayor seguridad y flexibilidad.
 * Si no encuentra la variable de entorno, usamos un valor por defecto (fallback).
 */

$host    = getenv('DB_HOST')    ?: 'localhost';
$db      = getenv('DB_NAME')    ?: ''; 
$user    = getenv('DB_USER')    ?: 'root';
$pass    = getenv('DB_PASS')    ?: '';
$charset = getenv('DB_CHARSET') ?: 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

/* try {
     $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
     // En producción, es mejor loguear el error y mostrar un mensaje genérico
     error_log($e->getMessage());
     die("Error de conexión a la base de datos.");
} */

     try {
     $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
     // Esto nos va a decir exactamente qué falla:
     die("Error real: " . $e->getMessage() . " | DSN usado: " . $dsn);
}