<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=utf-8');
// header(HTTP/1.1 405 Method not allowed);

require_once('fncsbackendsuall.php');

$link = conectDB();
$djson = array();

foreach ($_GET as $key => $value)
        $var[$key] = mysqli_real_escape_string($link, $value);

switch ($var['f']) {
    case 'ping':
        $djson['std'] = 200;
        $djson['msg'] = "echo from server";
        $djson['dat']['date'] = date(DATE_RFC2822);
        $djson['dat']['time'] = time();
        break;

    case 'svr':
        $djson['std'] = 200;
        $djson['msg'] = "Datos del servidor";
        $djson['dat'] = $_SERVER;
        break;

    default:
        $djson['std'] = 10;
        $djson['msg'] = "Unsupported get request";
        break;
}

echo json_encode($djson);
// http_response_code(405);
// exit();
