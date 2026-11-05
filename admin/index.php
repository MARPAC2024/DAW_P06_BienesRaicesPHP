<?php 
    // Incluir funciones
    require '../includes/funciones.php';

    // 1. Verificar autenticación antes de imprimir cualquier HTML
    $auth = estaAutenticado();
    
/*
    echo "<pro>";
    var_dump($_GET);
    $mensaje = $_GET;
    echo "</pro>";
    exit;
*/
    
    if(!$auth) { 
        header('Location: ' . url('/index.php'));
        exit;
    }

    // 2. Conexión a la BD
    require '../includes/config/database.php';
    $db = conectarDB();

    // Escribir query 
    $query = "SELECT * FROM propiedades";

    // Consulta de propiedades en la BD
    $resultadoConsulta = mysqli_query($db, $query);

    // Muestra mensaje condicional
    $resultado = $_GET['resultado'] ?? null;

    // Eliminar registro
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = $_POST['id'];
        $id = filter_var($id, FILTER_VALIDATE_INT);

        if($id) {
            // Eliminar el archivo de la imagen
            $queryImagen = "SELECT imagen FROM propiedades WHERE id = {$id}";
            $resultadoImagen = mysqli_query($db, $queryImagen);
            $propiedad = mysqli_fetch_assoc($resultadoImagen);

            if($propiedad && file_exists('../imagenes/' . $propiedad['imagen'])) {
                unlink('../imagenes/' . $propiedad['imagen']);
            }

            // Eliminar la propiedad
            $queryEliminar = "DELETE FROM propiedades WHERE id = {$id}";
            $resultadoEliminar = mysqli_query($db, $queryEliminar);

            if($resultadoEliminar) {
                //header('Location: /admin?resultado=3');
                header('Location: ' . url('/admin/index.php?resultado=3'));
                exit;
            }
        }
    }

    // 3. Incluir template del header (DESPUÉS de las validaciones y redirecciones)
    incluirTemplate('header');
?>

<main class="contenedor seccion">
    <h1>Administrador de Bienes Raíces</h1>
    
    <?php if(intval($resultado) === 1): ?>
        <p class="alerta exito">Anuncio Creado Correctamente</p>
    <?php elseif(intval($resultado) === 2): ?>
        <p class="alerta exito">Anuncio Actualizado Correctamente</p>
    <?php elseif(intval($resultado) === 3): ?>
        <p class="alerta exito">Anuncio Eliminado Correctamente</p>
    <?php endif; ?>

    <a href="<?php echo url('/admin/propiedades/crear.php'); ?>" class="boton boton-verde">Nueva Propiedad</a>

    <table class="propiedades">
        <thead>
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Imagen</th>
                <th>Precio</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <!-- Mostrar los resultados -->
        <tbody>
            <?php while($propiedad = mysqli_fetch_assoc($resultadoConsulta)): ?>
            <tr>
                <td><?php echo $propiedad['id']; ?></td>
                <td><?php echo $propiedad['titulo']; ?></td>
                <td><img src="<?php echo url('/imagenes/' . $propiedad['imagen']); ?>" class="imagen-tabla" alt="Imagen"></td>
                <td>$ <?php echo number_format($propiedad['precio']); ?></td>
                <td>
                    <form method="POST" class="w-100">
                        <input type="hidden" name="id" value="<?php echo $propiedad['id']; ?>">
                        
                        <input type="submit" class="boton-rojo-block" value="Eliminar">
                        
                        <a href="<?php echo url('/admin/propiedades/actualizar.php?id=' . $propiedad['id']); ?>" class="boton-amarillo-block">Actualizar</a>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</main>

<?php
    // Cerrar la conexión
    mysqli_close($db);

    incluirTemplate('footer');
?>