<?php
/*
    ERRORES GENERALES DE BAJO NIVEL
    0: Error de conexion a la BD
    1: Error en la consulta
    2: Datos insuficientes para realizar la consulta
    3: -- resrevado
    4: Error al insertar LogQuery
    5: Error al insertar LogError

    10: Unsupported get request


    ERRORES DE USUARIO
    20: Usuario no registrado o invalido
    21: Error al traer info de usuario

    ERRORES DE GRUPO
    30: Ya existe un grupo con ese nombre
    31: La contraseña es inválida o el grupo no existe

 */
define("_DB_DATABASE_", "BDSUALL");

define("_DB_SERVER_", "localhost");
define("_DB_USER_", "root");
define("_DB_USER_PASS_", "trecetp");

// define("DB_SERVER", "aa1a7f4riehdtop.cmtb4tcsw9fm.us-west-1.rds.amazonaws.com");
// define("DB_USER", "rootaws");
// define("DB_USER_PASS", "psw2admAWSsql");

define("_DEBUG_SUALL_", true);

//Funcion para conectarse a las base de datos
function conectDB()
{
    $link = @mysqli_connect(_DB_SERVER_, _DB_USER_, _DB_USER_PASS_, _DB_DATABASE_);
    if (mysqli_connect_errno($link)) die('{"std":0,"msg":"Error en la conexión a la BD","error":"'.mysqli_connect_error().'"}');
    @mysqli_query($link, "SET NAMES 'utf8' COLLATE 'utf8_general_ci'");
    @mysqli_query($link, 'SET time_zone = "-05:00"');
    @mysqli_set_charset($link, "utf8");
    return $link;
}

function show2html($text)
{
    return nl2br(htmlspecialchars(stripslashes($text)));
}

function logQuery($querySQL, $id_jug)
{
    $link = conectDB();
    $querySQL = mysqli_real_escape_string($link, $querySQL);
    $sql ="INSERT INTO TBlogquery SET id_jug='$id_jug', query='$querySQL'";
    @mysqli_query($link, $sql);
    if (mysqli_errno($link))
        die('{"std":4,"msg":"Error al insertar LogQuery: '.mysqli_error($link).'","sql":"'.$sql.'"}');
    return 1;
}

function logError($cod, $querySQL, $errorSQL, $id_jug = 0)
{
    $link = conectDB();
    $querySQL = mysqli_real_escape_string($link, $querySQL);
    $errorSQL = mysqli_real_escape_string($link, $errorSQL);
    $sql ="INSERT INTO TBlogerror SET cod='$cod', id_jug='$id_jug', query='$querySQL', error='$errorSQL'";
    @mysqli_query($link, $sql);
    if (mysqli_errno($link))
        die('{"std":5,"msg":"Error al insertar LogError: '.mysqli_error($link).'","sql":"'.$sql.'"}');
    return 1;
}
