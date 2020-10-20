<?php
// Se llama a funciones.inc.php, se inicia la sesión
require_once 'funciones.inc.php';
session_start();
$usuario = ""; // Nombre del usuario
$visita = ""; // Hora de visita
$error = ""; // Mensaje de error
$administrador = false; // Guarda si se accede con el usuario administrador
$fondo = ""; // Color de fondo


// Si no se ha iniciado sesión, te avisa
if (empty($_SESSION['usuario'])) {
    $error = "Debes iniciar sesión";
// De lo contrario, se guarda el usuario y la fecha de conexión
} else {
    $usuario = $_SESSION['usuario'];
    $visita = date('H:i:s', $_SESSION['visita']);
}

// Si accedes con el usuario dwes, se activa $administrador
if ($usuario == "dwes") {
    $administrador = true;
}

// Si pinchas en anuncio, te manda a anuncio.php
if (isset($_POST['anuncio'])) {
    header("Location: anuncio.php");
}

// Si pinchas en listado, te manda a escaparate.php
if (isset($_POST['listado'])) {
    header("Location: escaparate.php");
}

// Si pinchas en desconectar, te manda a desconectar.php
if (isset($_POST['desconectar'])) {
    session_unset();
    header("Location: index.php");
}

// Si pinchas en preferencias, te manda a preferencias.php
if (isset($_POST['preferencias'])) {
    header("Location: preferencias.php");
}

// Si pinchas en desbloquear, llama a la función desbloquearnunciantes()
if (isset($_POST['desbloquear'])){
    desbloquearAnunciantes($_POST['usuariosDesblo']);
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
        </header>
        <div>
            <!-- Formulario con distintas opciones -->
            <form action='<?php echo $_SERVER['PHP_SELF']; ?>' method='post'>
                <fieldset >
                    <legend>Opciones</legend>
                    <div class='campo'>
                        <input type='submit' name='anuncio' value='Crear un anuncio' <?php
                        // Si no se ha iniciado sesión, este boton está bloqueado
                        if (empty($_SESSION['usuario'])) {
                            echo 'disabled="disabled"';
                        }
                        ?>/>
                    </div>
                    <div class='campo'>
                        <input type='submit' name='listado' value='Listado de anuncios' <?php
                        // Si no se ha iniciado sesión, este boton está bloqueado
                        if (empty($_SESSION['usuario'])) {
                            echo 'disabled="disabled"';
                        }
                        ?>/>
                    </div>
                    <div class='campo'>
                        <input type='submit' name='preferencias' value='Preferencias' <?php
                        // Si no se ha iniciado sesión, este boton está bloqueado
                        if (empty($_SESSION['usuario'])) {
                            echo 'disabled="disabled"';
                        }
                        ?>/>
                    </div>
                    <div class='campo'>
                        <input type='submit' name='desconectar' value='Desconectar' />
                    </div>
                </fieldset>
            </form>
        </div>
        <div>
            <?php
            // Si se ha iniciado sesión con "dwes", aparece la opción de desbloquear usuarios
            if ($administrador) {
                echo "<form action=' " . $_SERVER['PHP_SELF'] . "' method='post'> 
                <fieldset >
                    <legend>Usuarios bloqueados</legend>";
                listaBloqueados();
                echo "<input type='submit' name='desbloquear' value='Desbloquear'/>
                    </fieldset>
            </form>";
            }
            ?>
        </div>
    </body>
</html>

