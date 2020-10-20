<?php
// Se llama a funciones.inc.php, se inicia la sesión
require_once 'funciones.inc.php';
session_start();
$usuario = ""; // Nombre del usuario
$visita = ""; // Hora de visita
$error = ""; // Mensaje de error
$fondo = ""; // Color de fondo

// si hay datos en la sesión, se guarda el usuario y la hora de visita
if (!empty($_SESSION['usuario'])) {
    $usuario = $_SESSION['usuario'];
    $visita = date('H:i:s', $_SESSION['visita']);
}

// Si se pulsa el botón de salir, te manda a index.php
if (isset($_POST['salir'])) {
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
        <title>Escaparate</title>
    </head>
    <body <?php echo $fondo ?>> <!-- Si hay cookie sobre ello, se cambia el color de fondo -->
        <header>
            <!-- Se muestra los datos de la sesión -->
            <?php echo '<p>Login: ' . $usuario . ' || Hora de visita: ' . $visita ?>
        </header>
        <div>
            <!-- Se crea la tabla con los anuncios -->
            <?php crearTablaAnuncios() ?>
        </div>
        <form action='<?php echo $_SERVER['PHP_SELF']; ?>' method='post'>
            <div class='campo'>
                <input type='submit' name='salir' value='Salir'/> <!-- Botón de salir -->
            </div>
        </form>
    </body>
</html>

