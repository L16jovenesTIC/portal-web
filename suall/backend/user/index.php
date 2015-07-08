<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=utf-8');

require_once('../fncsbackendsuall.php');

$link = conectDB();
$djson = array();

foreach ($_GET as $key => $value)
        $var[$key] = mysqli_real_escape_string($link, $value);

switch ($var['f']) {
    case 'ver': // Verifica y Retorna el estado del usuario
        if (isset($var['uid']) && isset($var['email'])) {
            $id_jug = $var['uid'];
            $jug_email = $var['email'];

            $sql = "SELECT jug_key FROM TBjug WHERE id_jug='$id_jug' AND jug_email = '$jug_email' LIMIT 1";

            $result = mysqli_query($link, $sql);

            if (mysqli_errno($link)) :
                $djson['std'] = 1;
                $djson['msg'] = "Error al ejecutar la consulta";
                if (_DEBUG_SUALL_) {
                    $djson['dat']['error'] = mysqli_error($link);
                    $djson['dat']['sql'] = $sql;
                }
                logError(1, $sql, mysqli_error($link), $id_jug);
            elseif (mysqli_num_rows($result)) :
                $djson['std'] = 200;
                $djson['msg'] = "El usuario ya esta registrado";

                $sql = "SELECT TBjug.*, TBencuesta.id_jug AS 'encuesta', TBclan.id_clan as clan FROM TBjug
                        LEFT JOIN TBencuesta
                               ON TBjug.id_jug = TBencuesta.id_jug
                        LEFT JOIN TBrel_jugclan
                               ON TBjug.id_jug = TBrel_jugclan.id_jug
                        LEFT JOIN TBclan
                               ON TBrel_jugclan.id_clan = TBclan.id_clan
                        WHERE TBjug.id_jug='$id_jug'";
                $result = mysqli_query($link, $sql);

                if (mysqli_errno($link)) :
                    $djson['std'] = 1;
                    $djson['msg'] = "Error al ejecutar la consulta";
                    if (_DEBUG_SUALL_) {
                        $djson['dat']['error'] = mysqli_error($link);
                        $djson['dat']['sql'] = $sql;
                    }
                    logError(1, $sql, mysqli_error($link), $id_jug);
                elseif (mysqli_num_rows($result)) :
                    $row = mysqli_fetch_assoc($result);
                    $djson['std'] = 200;
                    $djson['msg'] = "Consulta Exitosa";
                    $djson['dat'][] = $row;
                else :
                    $djson['std'] = 21;
                    $djson['msg'] = "Error al traer info de usuario";
                    if (_DEBUG_SUALL_) {
                        $djson['dat']['sql'] = $sql;
                    }
                    logError(21, $sql, "Error al traer info de usuario", $id_jug);
                endif;
            else :
                $djson['std'] = 20;
                $djson['msg'] = "Usuario no registrado";
                if (_DEBUG_SUALL_) {
                    $djson['dat']['sql'] = $sql;
                }
            endif;

        } else {
            $djson['std'] = 2;
            $djson['msg'] = "Parametros insuficientes para completar la petición";
        }

        break;

    case 'reg': // registra un nuevo usuario
        if (isset($var['uid']) && isset($var['email'])) {
            $id_jug = $var['uid'];
            $sql = "SELECT jug_key FROM TBjug WHERE id_jug='$id_jug' LIMIT 1";

            $result = mysqli_query($link, $sql);

            if (mysqli_errno($link)) :
                $djson['std'] = 1;
                $djson['msg'] = "Error al ejecutar la consulta";
                if (_DEBUG_SUALL_) {
                    $djson['dat']['error'] = mysqli_error($link);
                    $djson['dat']['sql'] = $sql;
                }
                logError(1, $sql, mysqli_error($link), $id_jug);
            elseif (mysqli_num_rows($result)) :
                $sql = "SELECT TBjug.*, TBencuesta.id_jug AS 'encuesta', TBclan.id_clan as clan FROM TBjug
                        LEFT JOIN TBencuesta
                               ON TBjug.id_jug = TBencuesta.id_jug
                        LEFT JOIN TBrel_jugclan
                               ON TBjug.id_jug = TBrel_jugclan.id_jug
                        LEFT JOIN TBclan
                               ON TBrel_jugclan.id_clan = TBclan.id_clan
                        WHERE TBjug.id_jug='$id_jug'";

                $result = mysqli_query($link, $sql);

                if (mysqli_errno($link)) :
                    $djson['std'] = 1;
                    $djson['msg'] = "Error al ejecutar la consulta";
                    if (_DEBUG_SUALL_) {
                        $djson['dat']['error'] = mysqli_error($link);
                        $djson['dat']['sql'] = $sql;
                    }
                    logError(1, $sql, mysqli_error($link), $id_jug);
                elseif (mysqli_num_rows($result)) :
                    $row = mysqli_fetch_assoc($result);
                    $djson['std'] = 200;
                    $djson['msg'] = "El usuario ya esta registrado";
                    $djson['dat'][] = $row;
                else :
                    $djson['std'] = 21;
                    $djson['msg'] = "Error al traer info de usuario";
                    if (_DEBUG_SUALL_) {
                        $djson['dat']['sql'] = $sql;
                    }
                    logError(21, $sql, "Error al traer info de usuario", $id_jug);
                endif;
            else :
                $id_jug = $var['uid'];
                $jug_email = $var['email'];

                $sql = "INSERT INTO TBjug SET ";
                $sql.= "id_jug='".$var['uid']."', ";
                $sql.= "jug_nombre='".$var['name']."', ";
                $sql.= "jug_email='".$var['email']."', ";
                $sql.= "jug_link_fb='".$var['linkfb']."', ";
                $sql.= "jug_pic_square='".$var['picsqr']."', ";
                $sql.= "jug_pic_normal='".$var['picnrl']."', ";
                $sql.= "jug_pic_large='".$var['piclgr']."', ";
                $sql.= "jug_pic_big='".$var['picbig']."', ";
                $sql.= "jug_gender='".$var['gender']."', ";
                $sql.= "jug_key='".md5($var['uid'].$var['email'])."' ";

                @mysqli_query($link, $sql);

                if (mysqli_errno($link)) :
                    $djson['std'] = 1;
                    $djson['msg'] = "Error al ejecutar la consulta";
                    if (_DEBUG_SUALL_) {
                        $djson['dat']['error'] = mysqli_error($link);
                        $djson['dat']['sql'] = $sql;
                    }
                    logError(1, $sql, mysqli_error($link), $id_jug);
                else :
                    logQuery($sql, $id_jug);
                    $sql = "SELECT TBjug.*, TBencuesta.id_jug AS 'encuesta', TBclan.id_clan as clan FROM TBjug
                            LEFT JOIN TBencuesta
                                   ON TBjug.id_jug = TBencuesta.id_jug
                            LEFT JOIN TBrel_jugclan
                                   ON TBjug.id_jug = TBrel_jugclan.id_jug
                            LEFT JOIN TBclan
                                   ON TBrel_jugclan.id_clan = TBclan.id_clan
                            WHERE TBjug.id_jug='$id_jug'";
                    $result = mysqli_query($link, $sql);
                    if (mysqli_errno($link)) :
                        $djson['std'] = 1;
                        $djson['msg'] = "Error al ejecutar la consulta";
                        if (_DEBUG_SUALL_) {
                            $djson['dat']['error'] = mysqli_error($link);
                            $djson['dat']['sql'] = $sql;
                        }
                        logError(1, $sql, mysqli_error($link), $id_jug);
                    else :
                        $row = mysqli_fetch_assoc($result);
                        $djson['std'] = 200;
                        $djson['msg'] = "Registro Exitoso";
                        $djson['dat'][] = $row;
                    endif;

                endif;

            endif;

        } else {
            $djson['std'] = 2;
            $djson['msg'] = "Parametros insuficientes para completar la petición";
        }

        break;

    default:
        $djson['std'] = 10;
        $djson['msg'] = "Unsupported get request";
        break;
}

echo json_encode($djson);
