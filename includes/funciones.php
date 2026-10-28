<?php

require 'app.php';

function incluirTemplate( string $nombre, bool $inicio = false ) {
    include TEMPLATES_URL . "/${nombre}.php";}

function estaAutenticado() : bool {
    session_start();
    $auth = $_SESSION['login'] ?? false;
    return $auth;
}

function s($html) : string {
    $s = htmlspecialchars($html, ENT_QUOTES, 'UTF-8');
    return $s;
}