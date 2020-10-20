<?php
// Se llama a funciones.inc.php
require_once 'funciones.inc.php';
$error = ""; // Mensaje de error
$fondo = ""; // Color de fonfo
$mensaje = ""; // Mensaje
$caracteres = "/^[A-z\sñáéíóúäëïöü]*[0-9]*$/"; // caracteres que se pueden usar

// Si se pulsa guardar se guardan todos los campos
if (isset($_POST['guardar'])) {
    $usuario = $_POST['usuario'];
    $password = $_POST['password'];
    $passwordRepe = $_POST['passwordRepe'];
    $email = $_POST['email'];

    // Si hay algun campo vacío, te avisa
    if (empty($usuario) || empty($password) || empty($passwordRepe) || empty($email)) {
        $error = "Debes rellenar todo los campos";
    } else {
        // Si no hay vacíos, comprueba que no tienen caracteres especiales
        if (preg_match($caracteres, $usuario) && preg_match($caracteres, $password)) {
            // Comprueba que las dos contraseñas sean iguales
            if ($password != $passwordRepe) {
                $error = "Las contraseñas no coinciden";
            } else {
                // Si pasa todos los filtros, se guarda el usuario y te muestra un mensaje
                guardarUsu($usuario, $password, $email);
                $mensaje = "<h3>Usuario guardado</h3>";
            }
        } else {
            $error = 'No se admiten caracteres especiales';
        }
    }
}

// Si se pulsa volver, te manda a index.php
if (isset($_POST['volver'])) {
    header("Location: index.php");
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
        <title>Registro</title>
    </head>
    <body <?php echo $fondo ?>> <!-- Si hay cookie sobre ello, se cambia el color de fondo -->
        <div>
            <!-- Crea un formulario para registrar un usuario -->
            <form action='<?php echo $_SERVER['PHP_SELF']; ?>' method='post'>
                <fieldset >
                    <legend>Registro</legend>
                    <div><span class='error' style="color:#FF0000"><?php echo $error; ?></span></div>
                    <div><?php echo $mensaje; ?></div>
                    <div class='campo'>
                        <label for='usuario' >Usuario:</label><br/>
                        <input type='text' name='usuario' id='usuario' maxlength="20" 
                               placeholder="Nombre de usuario" autofocus="autofocus" value="<?php
                               if (isset($_POST['usuario'])) {
                                   echo $_POST['usuario'];
                               }
                               ?>"/><br/>
                    </div>
                    <div class='campo'>
                        <label for='password' >Contraseña:</label><br/>
                        <input type='password' name='password' id='password' maxlength="50" 
                               placeholder="Contraseña"/><br/>
                    </div>
                    <div class='campo'>
                        <label for='passwordRepe' >Repetir contraseña:</label><br/>
                        <input type='password' name='passwordRepe' id='passwordRepe' maxlength="50" 
                               placeholder="Repite contraseña"/><br/>
                    </div>
                    <div class='campo'>
                        <label for='email' >E-mail:</label><br/>
                        <input type='email' name='email' id='email' maxlength="50" 
                               placeholder="Correo electronico" value="<?php
                               if (isset($_POST['email'])) {
                                   echo $_POST['email'];
                               }
                               ?>"/><br/>
                    </div>
                    <div class='campo'>
                        <input type='submit' name='guardar' value='Guardar' />
                    </div>
                    <div class='campo'>
                        <input type='submit' name='volver' value='Volver' />
                    </div>
                </fieldset>
            </form>
        </div>
    </body>
</html>

