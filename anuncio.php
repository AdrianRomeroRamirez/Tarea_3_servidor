<?php
// Se llama a funciones.inc.php, se inicia la sesión
require_once 'funciones.inc.php';
session_start();
$usuario = ""; // Nombre del usuario
$visita = ""; // Hora de visita
$error = ""; // Mensaje de error
$fondo = ""; // Color de fondo
$mensaje = ''; // Mensaje

// Si no hay ningun usuario guardado en la sesión, avisa con un mensaje de error
if (empty($_SESSION['usuario'])) {
    $error = "Debes iniciar sesión";
} else {
    // Si hay usuario, se guarda el nombre, la hora de visita y la fecha
    $usuario = $_SESSION['usuario'];
    $visita = date('H:i:s', $_SESSION['visita']);
    $fecha = date('Y-m-d', $_SESSION['visita']);
}

// Si se pulsa el botón de publicar, se guardan los datos recibidos
if (isset($_POST['publicar'])) {
    $autor = $_POST['autor'];
    $moroso = $_POST['moroso'];
    $localidad = $_POST['localidad'];
    $descripcion = $_POST['descripcion'];
    $fecha = $_POST['fecha'];

    // Si hay campos vacios, se avisa
    if (empty($autor) || empty($moroso) || empty($localidad) || empty($descripcion) || empty($fecha)) {
        $error = "Debes rellenar todos los datos";
    } else {
        // Se comprueba que no haya caracteres especiales
        if (preg_match("/^[A-z\sñáéíóúäëïöü]*[0-9]*$/", $moroso) && preg_match("/^[A-z\sñáéíóúäëïöü]*[0-9]*$/", $localidad)
                && preg_match("/^[A-z\sñáéíóúäëïöü]*[0-9]*$/", $descripcion)) {
            // Si está todo correcto, se guarda el anuncio y se muestra un mensjae
            guardarAnuncio($autor, $moroso, $localidad, $descripcion, $fecha);
            $mensaje = '<h3>Anuncio guardado</h3>';
        }else{
            $error = 'No se admiten caracteres especiales';
        }
    }
}

// Si se pulsa el botón de volver, te redirige a usuario.php
if (isset($_POST['volver'])) {
    header("Location: usuario.php");
}

// Si existe la cookie "fondo", se guarda el estilo en $fondo
if (isset($_COOKIE['fondo'])) {
    $fondo = 'style = "background-color:' . $_COOKIE['fondo'] . ';"';
}
?>

<!DOCTYPE html>

<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Usuario</title>
    </head>
    <body <?php echo $fondo ?>> <!-- Si hay cookie sobre ello, se cambia el color de fondo -->
        <header>
            <!-- Se muestra los datos de la sesión -->
            <?php echo '<p>Login: ' . $usuario . ' || Hora de visita: ' . $visita ?>
            <!-- Se muestra el error en caso de que lo hubiera -->
            <div><span class='error' style="color:#FF0000"><?php echo $error; ?></span></div>
            <!-- Se muestra un mensaje en caso de que lo hubiera -->
            <div><?php echo $mensaje; ?></div>
        </header>
        <div>
            <!-- Se crea un formulario con todos los campos para crear un anuncio -->
            <form action='<?php echo $_SERVER['PHP_SELF']; ?>' method='post'>
                <fieldset >
                    <legend>Anuncio</legend>
                    <div class='campo'>
                        <label for='autor' >Autor:</label><br/>
                        <input type='text' name='autor' id='autor' maxlength="20" placeholder="Nombre del autor"
                               readonly="readonly" value="<?php
                                if (!empty($_SESSION['usuario'])) {
                                    echo $usuario;
                                }
                                ?>"/><br/>
                    </div>
                    <div class='campo'>
                        <label for='moroso' >Moroso:</label><br/>
                        <input type='text' name='moroso' id='moroso' maxlength="60" 
                               placeholder="Nombre del moroso" autofocus="autofocus" value="<?php
                               if (isset($_POST['moroso'])) {
                                   echo $_POST['moroso'];
                               }
                                ?>"/><br/>
                    </div>
                    <div class='campo'>
                        <label for='localidad' >Localidad:</label><br/>
                        <input type='text' name='localidad' id='localidad' maxlength="60" 
                               placeholder="Localidad del la vivienda" value="<?php
                               if (isset($_POST['localidad'])) {
                                   echo $_POST['localidad'];
                               }
                                ?>"/><br/>
                    </div>
                    <div class='campo'>
                        <label for='descripcion' >Descripción:</label><br/>
                        <textarea name='descripcion' id='descripcion' maxlength="500"><?php
                               if (isset($_POST['descripcion'])) {
                                   echo $_POST['descripcion'];
                               } else {
                                   echo 'Descripción del anuncio';
                               }
                                ?></textarea><br/>
                    </div>
                    <div class='campo'>
                        <label for='fecha' >Fecha:</label><br/>
                        <input type='date' name='fecha' id='fecha'  value="<?php
                            if (!empty($_SESSION['usuario'])) {
                                echo $fecha;
                            }
                            ?>"/><br/>
                    </div>
                    <div class='campo'>
                        <input type='submit' name='publicar' value='Publicar'/>
                    </div>
                    <div class='campo'>
                        <input type='submit' name='volver' value='Volver'/>
                    </div>
                </fieldset>
            </form>
        </div>
    </body>
</html>

