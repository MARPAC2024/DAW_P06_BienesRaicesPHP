<?php

require 'app.php';

function incluirTemplate( string $nombre, bool $inicio = false ) {
    include TEMPLATES_URL . "/{$nombre}.php";}

function estaAutenticado() : bool {
    session_start();
    $auth = $_SESSION['login'] ?? false;
    return $auth;
}

function s($html) : string {
    $s = htmlspecialchars($html, ENT_QUOTES, 'UTF-8');
    return $s;
}

/*
function url(string $ruta = '') : string {
    // Si la ruta no empieza con /, se la agregamos
    $ruta = '/' . ltrim($ruta, '/');
    return '/DAW_P-06_bienesRaicesPHP' . $ruta;
}
*/

function url(string $ruta = '') : string {
    $baseDir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
    // Si estamos dentro de subcarpetas como /admin o /admin/propiedades, extraemos la raíz del proyecto
    $pos = strpos($baseDir, '/admin');
    if ($pos !== false) {
        $baseDir = substr($baseDir, 0, $pos);
    }
    $ruta = '/' . ltrim($ruta, '/');
    return $baseDir . $ruta;
}