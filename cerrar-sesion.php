<?php 
    
    // 1. Cargar funciones para poder usar url()
    require 'includes/funciones.php';

    // 2. Iniciar la sesión si no está iniciada
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // var_dump($_SESSION);
    
    // 3. Vaciar la sesión (cierra la sesión)
    $_SESSION = [];

    // 4. Redireccionar al inicio
    header('Location: ' . url('/index.php'));
    exit;