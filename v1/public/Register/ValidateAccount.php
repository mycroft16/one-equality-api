<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, HEAD, OPTIONS, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Authorization, Origin, X-Requested-With, Content-Type, Accept");
header("Access-Control-Expose-Headers: Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers");

ini_set('display_errors', 1);
error_reporting(E_ALL);

if($_SERVER['REQUEST_METHOD'] != "OPTIONS") {

    require_once($_SERVER['DOCUMENT_ROOT'].'/v1/config.inc');
    
    include_once('../../helpers/Class.ValidationToken.php');
    $validationToken = new ValidationToken();

    $tokenParts = $validationToken->deconstructToken($_GET['token']);

    if (strtotime('now') < $tokenParts[2]) {
        $update = "UPDATE `users` SET `status` = 'Active' WHERE `id` = '".$tokenParts[0]."'";
        $mysql->query($update);
        http_response_code(200);
        $return['validToken'] = true;
        $return['validTokenMessage'] = "Your account is now active.";

    } else {
        http_response_code(401);
        $return = "Your link has expired.";
    }

    echo json_encode($return, JSON_HEX_APOS);
    exit();
    
}
    
?>