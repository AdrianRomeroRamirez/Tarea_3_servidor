<?php
// Se llama a funciones.inc.php, se inicia la sesión
require_once 'funciones.inc.php';
session_start();
$usuario = ""; // Nombre del usuario
$visita = ""; // Hora de visita
$error = ""; // Mensaje de error
$fondo = ""; // Color de fondo

// Si se ha iniciado sesión, se guarda el usuario y la hora de visita
if (!empty($_SESSION['usuario'])) {
    $usuario = $_SESSION['usuario'];
    $visita = date('H:i:s', $_SESSION['visita']);
}

// Si se pulsa listado, te manda a escaparate.php
if (isset($_POST['listado'])) {
    header("Location: escaparate.php");
}

// Si se pulsa volver, te manda a index.php
if (isset($_POST['volver'])) {
    session_unset();
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
        <title>Invitado</title>
    </head>
    <body <?php echo $fondo ?>> <!-- Si hay cookie sobre ello, se cambia el color de fondo -->
        <header>
            <!-- Se muestra los datos de la sesión y un mensaje de bienvenida -->
            <?php echo '<p>Login: ' . $usuario . ' || Hora de visita: ' . $visita 
                    . '</p><p>Bienvenido a nuestra pagina Okupa2.com'?>
        </header>
        <div>
            <!-- Las opciones que tiene el invitado -->
            <form action='<?php echo $_SERVER['PHP_SELF']; ?>' method='post'>
                <fieldset >
                    <legend>Opciones</legend>
                    <div class='campo'>
                        <input type='submit' name='listado' value='Listado de anuncios' />
                    </div>
                    <div class='campo'>
                        <input type='submit' name='volver' value='Volver' />
                    </div>
                </fieldset>
            </form>
        </div>
    </body>
</html>