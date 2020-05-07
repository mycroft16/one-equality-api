<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, HEAD, OPTIONS, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Authorization, Origin, X-Requested-With, Content-Type, Accept");
header("Access-Control-Expose-Headers: Access-Control-Allow-Headers, Origin, Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers");

ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] != "OPTIONS") {

    require_once($_SERVER['DOCUMENT_ROOT'].'/v1/config.inc');

    $query = "SELECT `id` FROM `users` WHERE `emailAddress` = '".$mysql->real_escape_string(trim($_GET['emailAddress']))."'";
    if ($mysql->query($query)) {
        http_response_code(200);
        $res = $mysql->query($query);
        echo ($res->num_rows > 0) ? 1 : 0;
        exit();
    }
    http_response_code(500);
    $return['errorMessage'] = "An error occurred looking up the email address";
    echo json_encode($return, JSON_HEX_APOS);
    exit();

}
?>