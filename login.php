<?php

    // Conexión a la BD
    require 'includes/funciones.php';
    require 'includes/config/database.php';
    $db = conectarDB();

    //Autenticar usuario

    $errores = [];

    if($_SERVER["REQUEST_METHOD"] === "POST") {
        /*
        echo "<pre>";
        var_dump($_POST);
        echo "</pre>";
        */

        $email = mysqli_real_escape_string( $db, filter_var( $_POST["email"], FILTER_VALIDATE_EMAIL) );
        //var_dump($email);

        $pasword = mysqli_real_escape_string( $db, $_POST["password"] );

        if(!$email) {
            $errores[] = "El email es obligatorio o no es válido";
        }

        if(!$pasword) {
            $errores[] = "El pasword es obligatorio";
        }
        
        /*
        echo "<pre>";
        var_dump($errores);
        echo "</pre>";
        */

        if(empty($errores)) {
            // Revisar si el usuario existe
            $query = "SELECT * FROM USUARIOS where EMAIL = '{$email}' ";
            $resultado = mysqli_query( $db, $query );
            
            // var_dump($resultado);

            if( $resultado ->num_rows ) {
                // Revisar si el password es correcto
                $usuario = mysqli_fetch_assoc( $resultado );
                // var_dump($usuario);

                // Verificar si el password es correcto
                $auth = password_verify( $pasword, $usuario['password']);
                if($auth) { 
                    // El password esta autenticado
                    session_start();

                    //Llenar el arreglo de la sesión
                    $_SESSION['usuario'] = $usuario['email'];
                    $_SESSION['login'] = true;
                    
                    /*
                    echo "<pre>";
                    var_dump($_SESSION);
                    echo "</pre>";
                    */

                    // Redireccionar al panel de administración
                    header('Location: ' . url('/admin/index.php'));
                    exit;

                } else { 
                    $errores[] = 'El password es incorrecto';
                }

            } else {
                $errores[] = "El Usuario no existe";
            }
        }

    }

    // Incluye header (Debe ir después del POST para no bloquear la redirección)
    incluirTemplate('header');
?>

    <main class="contenedor seccion contenido-centrado">
        <h1>Iniciar Sesión</h1>

        <?php foreach($errores as $error): ?>
            <div class="alerta error">
                <?php echo $error; ?>
            </div>
        <?php endforeach; ?>
        
        <form method="POST" class="formulario" novalidate>
            <fieldset>
                <legend>Email y Password</legend>

                <label for="email">E-mail</label>
                <input type="email" name= "email" placeholder="Tu Email" id="email" required>

                <label for="password">Password</label>
                <input type="password" name="password" placeholder="Password" id="password" required>

            </fieldset>

            <input type="submit" value="Iniciar Sesión" class="boton-verde">
        </form>

    </main>

<?php incluirTemplate('footer'); ?>