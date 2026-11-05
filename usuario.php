<?php 
    // Importar conexiones y funciones
    require 'includes/funciones.php';
    require 'includes/config/database.php';
    $db = conectarDB();

    // Crear un email y password
    $email = "correo@correo.com";
    $pasword = "123456";

    // Hashear la contraseña
    $passwordHash = password_hash($pasword, PASSWORD_BCRYPT);

    $mensaje = '';
    $tipoAlerta = '';

    // 1. Comprobar si el usuario ya existe en la base de datos
    $queryExiste = "SELECT * FROM usuarios WHERE email = '{$email}'";
    $resultadoExiste = mysqli_query($db, $queryExiste);

    if($resultadoExiste && $resultadoExiste->num_rows > 0) {
        $mensaje = "El usuario admin ya existe";
        $tipoAlerta = "error";
    } else {
        // 2. Si no existe, insertar el usuario
        $queryInsertar = "INSERT INTO usuarios (email, password) VALUES ('{$email}', '{$passwordHash}')";
        $resultadoInsertar = mysqli_query($db, $queryInsertar);

        if($resultadoInsertar) {
            $mensaje = "Se genero el usuario admin";
            $tipoAlerta = "exito";
        } else {
            $mensaje = "Hubo un error al generar el usuario";
            $tipoAlerta = "error";
        }
    }

    // Estructura de base.php
    incluirTemplate('header');
?>

    <main class="contenedor seccion contenido-centrado">
        <h1>Administrador</h1>

        <?php if(!empty($mensaje)): ?>
            <p class="alerta <?php echo $tipoAlerta; ?>">
                <?php echo $mensaje; ?>
            </p>
        <?php endif; ?>
    </main>

<?php 
    incluirTemplate('footer'); 
?>