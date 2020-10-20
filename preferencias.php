<?php
// Se inicia sesión
session_start();
$usuario = ""; // Nombre del usuario
$visita = ""; // Hora de visita
$fondo = ""; // Color de fondo
$mensaje = ''; // Mensaje

// Si se ha iniciado sesión, se guarda el usuario y la hora de visita
if (!empty($_SESSION['usuario'])) {
    $usuario = $_SESSION['usuario'];
    $visita = date('H:i:s', $_SESSION['visita']);
}

// Si se pulsa enviar, se comprueba que recibe datos y crea una cookie con ese dato
if (isset($_POST['enviar'])) {
    if (isset($_POST['color'])) {
        setcookie('fondo', $_POST['color'], time() + 3600);
        $mensaje = "<h4>Preferencia guardada, recarga la pagina para mostrar los cambios.</h4>";
    }
}

// Si se pulsa restablecer, se borra la cookie
if (isset($_POST['restablecer'])) {
    setcookie('fondo', 0);
    $mensaje = "<h4>Preferencia guardada, recarga la pagina para mostrar los cambios.</h4>";
}

// Si existe la cookie "fondo", se guarda el estilo en $fondo
if (isset($_COOKIE['fondo'])) {
    $fondo = 'style = "background-color:' . $_COOKIE['fondo'] . ';"';
}

// Si se pulsa volver, te manda a usuario.php
if (isset($_POST['volver'])) {
    header("Location: usuario.php");
}
?>

<!DOCTYPE html>

<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Escaparate</title>
        <!-- Estilo para las letras de los colores disponibles -->
        <style>
            .colores p {
                display: inline;
                font-size: 22px;
                text-shadow: 1px  0px 0px black,
                    0px  1px 0px black,
                    -1px  0px 0px black,
                    0px -1px 0px black;
            }
        </style>
    </head>
    <body <?php echo $fondo ?>> <!-- Si hay cookie sobre ello, se cambia el color de fondo -->
        <header>
            <!-- Se muestra los datos de la sesión -->
            <?php echo '<p>Login: ' . $usuario . ' || Hora de visita: ' . $visita ?>
        </header>
        <!-- Lista de colores para elegir -->
        <form action='<?php echo $_SERVER['PHP_SELF']; ?>' method='post'>
            <div class="colores">
                <h2>Elige el color de fondo de las paginas.</h2>
                <?php echo $mensaje ?>
                <input type='radio' name='color' value='aquamarine'/><p style="color: aquamarine">Agua marina</p>
                <br/>
                <input type='radio' name='color' value='blue'/><p style="color: blue">Azul</p>
                <br/>
                <input type='radio' name='color' value='violet'/><p style="color: violet">Violeta</p>
                <br/>
                <input type='radio' name='color' value='chocolate'/><p style="color: chocolate">Chocolate</p>
                <br/>
                <input type='radio' name='color' value='green'/><p style="color: green">Verde</p>
                <br/><br/>
                <input type='submit' name='enviar' value='Enviar'/>
                <br/>
                <input type='submit' name='restablecer' value='Restablecer preferencias'/>
                <br/>
                <input type='submit' name='volver' value='Volver'/>
            </div>
        </form>
    </body>
</html>

