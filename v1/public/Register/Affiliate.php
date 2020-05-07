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

    $encryptedPassword = encryptIt(trim($_POST['password']));
    
    $insertUser = "INSERT INTO `users` SET
        `firstName`     = '".$mysql->real_escape_string(trim($_POST['firstName']))."',
        `lastName`      = '".$mysql->real_escape_string(trim($_POST['lastName']))."',
        `emailAddress`  = '".$mysql->real_escape_string(trim($_POST['emailAddress']))."',
        `password`      = '".$encryptedPassword."',
        `zipCode`       = '".$mysql->real_escape_string(trim($_POST['zipCode']))."',
        `mobilePhone`   = '".$mysql->real_escape_string(trim($_POST['mobilePhone']))."',
        `statusDate`    = NOW()";
    if ($mysql->query($insertUser)) {
        
        // CREATE SECURITY QUESTION RECORD
        $insertSecurity = "";
        $securityResult = $mysql->query($insertSecurity);

        // CREATE AFFILIATE
        $insertAffiliate = "";
        if ($mysql->query($insertAffiliate) && $securityResult) {
            http_response_code(200);
            $return['message'] = "A user and affiliate were created";
        } else {
            http_response_code(500);
            $return['message'] = "An error occurred creating an affiliate";    
        }

    } else {
        http_response_code(500);
        $return['message'] = "An error occurred creating a user";
    }

    echo json_encode($return, JSON_HEX_APOS);
    exit();

}
?>