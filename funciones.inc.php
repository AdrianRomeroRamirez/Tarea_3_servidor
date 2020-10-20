<?php

/*
 * Función que crea un objeto con la conexión a la base de datos
 */
function conexionBD() {
    try {
        // Array con opciones
        $arrOptions = array(
            PDO::ATTR_EMULATE_PREPARES => FALSE,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"
        );
        // se crea la conexión
        $con = new PDO('mysql:host=localhost;dbname=morosos', 'dwes', 'abc123', $arrOptions);
    } catch (Exception $e) { // Se controla las excepciones
        print "<h2>¡Error!: " . $e->getMessage() . "</h2><br/>";
        die();
    }
    return $con;
}

/*
 * Función que comprueba si un usuario existe en la base de datos con su contraseña y devuelve un boolean
 */

function comprobarUsuario($usuario, $password) {
    try {
        // Inicia en false
        $usuarioEncontrado = false;
        // se crea la conexión
        $con = conexionBD();
        // Se guarda la consulta
        $sql = "SELECT login, password FROM anunciantes WHERE login = ?";
        // Se prepara la consulta
        $resultado = $con->prepare($sql);
        $resultado->execute(array($usuario));
        //Si se encuentra el usuario, se compara la contraseña con password_verify()
        if ($registro = $resultado->fetch()) {
            if ($registro['login'] == $usuario && password_verify($password, $registro['password'])) {
                // Si coincide, se borran los intentos fallidos que tuviera
                borrarIntentosFallidos($usuario);
                $usuarioEncontrado = true;
            } else {
                // Si la contraseña no coincide y el usuario no es dwes, se le suma un fallo
                // Al usuario dwes no se le suman fallos, ya que es el único que puede desbloquear
                if ($usuario != "dwes") {
                    sumarIntentoFallido($usuario);
                }
            }
        }
        // Se cierra la consulta
        $resultado->closeCursor();
    } catch (Exception $e) { // Se controla las excepciones
        print "<h2>¡Error!: " . $e->getMessage() . "</h2><br/>";
        die();
    }
    // Devuelve si ha encontrado al usuario o no
    return $usuarioEncontrado;
}

/*
 * Función que guarda a un usuario en la base de datos, por defecto está bloqueado
 */

function guardarUsu($usuario, $password, $email) {
    try {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        // Se guarda la consulta
        $sql = "INSERT INTO `anunciantes` (`login`, `password`, `email`, `bloqueado`)"
                . " VALUES (?, ?, ?, 3);";
        // Se crea la conexión
        $con = conexionBD();
        // Se prepara la consulta en la conexión
        $consulta = $con->prepare($sql);
        // Se introducen los parametros
        $consulta->bindParam(1, $usuario);
        $consulta->bindParam(2, $hash);
        $consulta->bindParam(3, $email);
        // Se ejecuta
        $consulta->execute();
    } catch (Exception $e) { // Se controla las excepciones
        print "<h2>Ya existe ese usuario.</h2><br/>";
        die();
    }
}

/**
 * Función que suma un fallo al contador del usuario
 * @param type $usuario usuario al que se le suma el fallo
 */
function sumarIntentoFallido($usuario) {
    try {
        // Se guarda la consulta
        $sql = "UPDATE anunciantes SET bloqueado = bloqueado + 1 WHERE login = ?";
        // Se crea la conexión
        $con = conexionBD();
        // Se prepara la consulta en la conexión
        $consulta = $con->prepare($sql);
        // Se introducen los parametros
        $consulta->bindParam(1, $usuario);
        // Se ejecuta
        $consulta->execute();
    } catch (Exception $ex) {
        print "<h2>¡Error!: " . $e->getMessage() . "</h2><br/>";
        die();
    }
}

/**
 * Función que borra los intentos fallidos de acceso
 * @param type $usuario usuario al que se le borran los intentos fallidos
 */
function borrarIntentosFallidos($usuario) {
    try {
        // Se guarda la consulta
        $sql = "UPDATE anunciantes SET bloqueado = 0 WHERE login = ?";
        // Se crea la conexión
        $con = conexionBD();
        // Se prepara la consulta en la conexión
        $consulta = $con->prepare($sql);
        // Se introducen los parametros
        $consulta->bindParam(1, $usuario);
        // Se ejecuta
        $consulta->execute();
    } catch (Exception $ex) {
        print "<h2>¡Error!: " . $e->getMessage() . "</h2><br/>";
        die();
    }
}

/**
 * Función que comprueba si un usuario está bloqueado
 * @param type $usuario usuario que se comprueba
 * @return boolean si está bloqueado o no
 */
function ComprobarBloqueo($usuario) {
    $bloqueado = false;
    // Se guarda la consulta
    $sql = "SELECT bloqueado FROM anunciantes WHERE login = ?";
    // se crea la conexión
    $con = conexionBD();
    // Se prepara la consulta
    $resultado = $con->prepare($sql);
    // Se ejecuta
    $resultado->execute(array($usuario));
    if ($registro = $resultado->fetch()) {
        // Si tiene 3 intentos fallidos, está bloqueado
        if ($registro['bloqueado'] == 3) {
            $bloqueado = true;
        }
    }
    return $bloqueado;
}

/**
 * Lista que muestra los usuarios bloqueados
 */
function listaBloqueados() {
    // Se guarda la consulta
    $sql = "SELECT login, bloqueado FROM anunciantes WHERE bloqueado = 3";
    // se crea la conexión
    $con = conexionBD();
    // Se prepara la consulta
    $resultado = $con->prepare($sql);
    // Se ejecuta
    $resultado->execute();
    // se crea un checkbox con todos los bloqueados
    while ($registro = $resultado->fetch()) {
        echo "<label><input type='checkbox' name='usuariosDesblo[]' value='" . $registro['login'] . "'><p>" . $registro['login'] . "</p></input></label>";
    }
}

/**
 * Función que recibe un array con todos los anunciantes que se quieren desbloquear 
 * y los desbloquea boorando los intentos fallidos
 * @param type $anunciantes lista e anunciantes a desbloquear
 */
function desbloquearAnunciantes($anunciantes) {
    // Por cada registro de la lista, se le aplica la función borrarIntentosFallidos()
    foreach ($anunciantes as $login) {
        borrarIntentosFallidos($login);
    }
}

/**
 * Función que guarda un anuncio en la BD
 * @param type $autor autor del anuncio
 * @param type $moroso nombre del moroso
 * @param type $localidad localidad de la vivienda
 * @param type $descripcion el anuncio en sí
 * @param type $fecha fecha de publicación
 */
function guardarAnuncio($autor, $moroso, $localidad, $descripcion, $fecha) {
    try {
        // Se guarda la consulta
        $sql = "INSERT INTO `anuncios` (`autor`, `moroso`, `localidad`, `descripcion`, `fecha`)"
                . " VALUES (?, ?, ?, ?, ?);";
        // Se crea la conexión
        $con = conexionBD();
        // Se prepara la consulta en la conexión
        $consulta = $con->prepare($sql);
        // Se introducen los parametros
        $consulta->bindParam(1, $autor);
        $consulta->bindParam(2, $moroso);
        $consulta->bindParam(3, $localidad);
        $consulta->bindParam(4, $descripcion);
        $consulta->bindParam(5, $fecha);
        // Se ejecuta
        $consulta->execute();
    } catch (Exception $e) { // Se controla las excepciones
        print "<h2>¡Error!: " . $e->getMessage() . "</h2><br/>";
        die();
    }
}

/**
 * Función que crea una tabla con todos los anuncios, si el anuncio es de menos de una semana, 
 * se muestra de otro color
 */
function crearTablaAnuncios() {
    // Se guarda la fecha actual
    $fecha = date('Y-m-d');
    // Se guarda la fecha de la semana pasada en un String con strtotime()
    $strSemanaPasada = strtotime('-7 day', strtotime($fecha));
    // El String anterior se pasa a un formato que nos interesa
    $semanaPasada = date('Y-m-d', $strSemanaPasada);
    try {
        // Se guarda la consulta
        $sql = "SELECT autor, moroso, descripcion, fecha FROM anuncios ORDER BY fecha desc;";
        // Se crea la conexión
        $con = conexionBD();
        // Se prepara la consulta en la conexión
        $resultado = $con->prepare($sql);
        // Se ejecuta
        $resultado->execute();
        // Se escriben las etiquetas de apertura de la tabla y la cabecera
        echo '<table border = "1"><thead><tr><th>Autor</th><th>Fecha</th><th>Moroso</th><th>Descripción</th></th></thead><tbody>';
        // Mientras haya registros, se crea una fila nueva con el anuncio
        while ($registro = $resultado->fetch()) {
            // Si es anterior de la ultima semana no tiene estilo, y si es mas nuevo, se pone con el fondo en rojo
            if ($registro['fecha'] < $semanaPasada) {
                echo '<tr><td>' . $registro["autor"] . '</td><td>' . $registro["fecha"] . '</td><td>' . $registro["moroso"] . '</td><td>' . $registro["descripcion"] . '</td></tr>';
            } else {
                echo '<tr bgcolor="red"><td>' . $registro["autor"] . '</td><td>' . $registro["fecha"] . '</td><td>' . $registro["moroso"] . '</td><td>' . $registro["descripcion"] . '</td></tr>';
            }
        }
        echo '</tbody></table>';
    } catch (Exception $e) { // Se controla las excepciones
        print "<h2>¡Error!: " . $e->getMessage() . "</h2><br/>";
        die();
    }
}
