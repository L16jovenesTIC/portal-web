<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=utf-8');

require_once('../fncsbackendsuall.php');

$link = conectDB();
$djson = array();

foreach ($_GET as $key => $value)
        $var[$key] = mysqli_real_escape_string($link, $value);

switch ($var['f']) {
    case 'new': // crea grupo y registra al usuario
        if (isset($var['uid']) && isset($var['ukey']) && isset($var['clan_nombre']) && isset($var['clan_psw'])) {
            $id_jug = $var['uid'];

            $sql = "SELECT id_clan FROM TBclan WHERE clan_nombre='".$var['clan_nombre']."' LIMIT 1";

            $result = mysqli_query($link, $sql);

            if (mysqli_errno($link)) :
                $djson['std'] = 1;
                $djson['msg'] = "Error al ejecutar la consulta";
                if (_DEBUG_SUALL_) {
                    $djson['dat']['error'] = mysqli_error($link);
                    $djson['dat']['sql'] = $sql;
                }
                logError(1, $sql, mysqli_error($link), $id_jug);
            elseif (!mysqli_num_rows($result)) :
                $sql =  "INSERT INTO TBclan SET ";
                $sql .= "clan_nombre ='".$var['clan_nombre']."', ";
                $sql .= "clan_psw ='".$var['clan_psw']."', ";
                $sql .= "clan_money ='10'";

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

                    $sql = "SELECT id_clan FROM TBclan WHERE clan_nombre='".$var['clan_nombre']."' LIMIT 1";
                    $result = mysqli_query($link, $sql);
                    $row = mysqli_fetch_assoc($result);

                    $id_clan = $row['id_clan'];

                    $sql = "INSERT INTO TBrel_jugclan SET id_jug='$id_jug', id_clan='$id_clan'";

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
                        $djson['std'] = 200;
                        $djson['msg'] = "Grupo creado y usuario registrado";
                        $djson['dat']['id_clan'] = $id_clan;
                    endif;
                endif;
            else :
                $djson['std'] = 30;
                $djson['msg'] = "El grupo ya esta registrado";
                if (_DEBUG_SUALL_) {
                    $djson['dat']['sql'] = $sql;
                }
            endif;

        } else {
            $djson['std'] = 2;
            $djson['msg'] = "Parametros insuficientes para completar la petición";
        }

        break;

    case 'reg': // registra al usuario en el grupo
        if (isset($var['uid']) && isset($var['ukey']) && isset($var['clan_nombre']) && isset($var['clan_psw'])) {
            $id_jug = $var['uid'];

            $sql = "SELECT id_clan FROM TBclan WHERE clan_nombre='".$var['clan_nombre']."' AND clan_psw='".$var['clan_psw']."' LIMIT 1";

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

                $id_clan = $row['id_clan'];

                $sql = "INSERT INTO TBrel_jugclan SET id_jug='$id_jug', id_clan='$id_clan'";

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
                    $djson['std'] = 200;
                    $djson['msg'] = "Usuario registrado en el grupo";
                    $djson['dat']['id_clan'] = $id_clan;
                endif;
            else :
                $djson['std'] = 31;
                $djson['msg'] = "El grupo no existe o la contraseña es incorecta";
                if (_DEBUG_SUALL_) {
                    $djson['dat']['sql'] = $sql;
                }
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
