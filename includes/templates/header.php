<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienes Raices</title>
    
    <!-- Estilos CSS -->
    <link rel="stylesheet" href="<?php echo url('/build/css/app.css'); ?>">
</head>
<body>
    <header class="header <?php echo $inicio ? 'inicio' : ''; ?>">
        <div class="contenedor contenido-header">
            <div class="barra">
                <!-- Logotipo -->
                <a href="<?php echo url('/index.php'); ?>">
                    <img src="<?php echo url('/build/img/logo.svg'); ?>" alt="Logotipo de Bienes Raices">
                </a>

                <div class="mobile-menu">
                    <img src="<?php echo url('/build/img/barras.svg'); ?>" alt="icono menu responsive">
                </div>

                <div class="derecha">
                    <img class="dark-mode-boton" src="<?php echo url('/build/img/dark-mode.svg'); ?>" alt="icono dark mode">
                    <nav class="navegacion">
                        <a href="<?php echo url('/nosotros.php'); ?>">Nosotros</a>
                        <a href="<?php echo url('/anuncios.php'); ?>">Anuncios</a>
                        <a href="<?php echo url('/blog.php'); ?>">Blog</a>
                        <a href="<?php echo url('/contacto.php'); ?>">Contacto</a>
                        <?php if (isset($auth) && $auth): ?>
                            <a href="<?php echo url('/cerrar-sesion.php'); ?>">Cerrar Sesión</a>
                        <?php endif; ?>
                    </nav>
                </div>
            </div> <!-- .barra -->

            <?php if ($inicio) { ?>
                <h1>Venta de Casas y Departamentos Exclusivos de Lujo</h1>
            <?php } ?>
        </div>
    </header>