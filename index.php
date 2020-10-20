<?php
// Se llama a funciones.inc.php, se inicia la sesión
require_once 'funciones.inc.php';
$error = ""; // Mensaje de error
$fondo = ""; // Color de fondo

// Comprobamos si ya se ha enviado el formulario
if (isset($_POST['entrar'])) {
    $usuario = $_POST['usuario'];
    $password = $_POST['password'];

    // Se comprueba que ningún campo esté vacío
    if (empty($usuario) || empty($password))
        $error = "Debes introducir un nombre de usuario y una contraseña";
    else {
        // Conectamos a la base de datos
        $con = conexionBD();
        // Se comprueba que el usuario no esté bloqueado
        if (ComprobarBloqueo($usuario)) {
            $error = "Usuario bloqueado";
        } else {
            // Se comprueba que usuario y contraseña sean correcto y se crea sesión y se redirige a usuario.php
            if (comprobarUsuario($usuario, $password)) {
                session_start();
                $_SESSION['usuario'] = $usuario;
                $_SESSION['visita'] = mktime();
                header("Location: usuario.php");
            } else {
                // Si las credenciales no son válidas, se vuelven a pedir
                $error = "Usuario o contraseña no válidos!";
            }
            unset($con);
        }
    }
}

// Si existe la cookie "fondo", se guarda el estilo en $fondo
if (isset($_COOKIE['fondo'])) {
    $fondo = 'style = "background-color:' . $_COOKIE['fondo'] . ';"';
}

// Si se pulsa en registrarse, te manda a registro.php
if (isset($_POST['registrarse'])) {
    header("Location: registro.php");
}

// Si se pulsa invitado, se crea una sesión 'Invitado' y te manda a invitado.php
if (isset($_POST['invitado'])) {
    session_start();
    $_SESSION['usuario'] = 'Invitado';
    $_SESSION['visita'] = mktime();
    header("Location: invitado.php");
}
?>
<!DOCTYPE html>

<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Login</title>
    </head>
    <body <?php echo $fondo ?>> <!-- Si hay cookie sobre ello, se cambia el color de fondo -->
        <div id='login'>
            <!-- Se crea un formulario para el login -->
            <form action='<?php echo $_SERVER['PHP_SELF']; ?>' method='post'>
                <fieldset >
                    <legend>Login</legend>
                    <!-- Se muestra el error en caso de que lo hubiera -->
                    <div><span class='error' style="color:#FF0000"><?php echo $error; ?></span></div>
                    <div class='campo'>
                        <label for='usuario' >Usuario:</label><br/>
                        <input type='text' name='usuario' id='usuario' maxlength="50" 
                               placeholder="Nombre de usuario" autofocus="autofocus" value="<?php
                               if(isset($_POST['usuario'])){
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
                        <input type='submit' name='entrar' value='Entrar' />
                    </div>
                    <div class='campo'>
                        <input type='submit' name='invitado' value='Entrar como invitado' />
                    </div>
                    <div class='campo'>
                        <input type='submit' name='registrarse' value='Registrarse' />
                    </div>
                </fieldset>
            </form>
        </div>
    </body>
</html>
