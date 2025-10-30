<?php

define("SITE", "Nuenti");
define("SITE_URL", "127.0.0.1/");
define("SITE_EMAIL", "");

define("MySQL_IP", "127.0.0.1");
define("MySQL_USER", "root");
define("MySQL_PASS", "");
define("MySQL_BD", "nuenti");


// ==========================================
// SISTEMA ANTIGUO (mysqli procedural)
// ==========================================
$link = null;

function getOldConnection() {
    global $link;
    
    if ($link === null) {
        $link = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if (!$link) {
            die("Error al conectar con el MySQL (legacy)");
        }
        mysqli_set_charset($link, "utf8mb4");
    }
    
    return $link;
}

// ==========================================
// SISTEMA NUEVO (PDO)
// ==========================================
$pdo = null;

function getDB() {
    global $pdo;
    
    if ($pdo === null) {
        try {
            $pdo = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
                DB_USER,
                DB_PASS,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
                ]
            );
        } catch (PDOException $e) {
            die("Error de conexión PDO: " . $e->getMessage());
        }
    }
    
    return $pdo;
}

// Inicializar conexión antigua automáticamente (compatibilidad)
$link = getOldConnection();

// Configuracion idioma de PHP para fechas (usar con strftime)
setlocale(LC_ALL,"es_ES@euro","es_ES","esp");

// Configuracion idioma de MySql para fechas
mysqli_query($link, "SET lc_time_names = 'es_ES'");

// $ruta se usa para meter ../ cuando la peticion procede de ficheros en subdirectorios
require("inc/functions.php");
?>