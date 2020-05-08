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
    
    $query = "SELECT * FROM `accountUsers` WHERE `id` = '".$userId."'";
    $res = $mysql->query($query);
    if($res->num_rows > 0) {
        $row = $res->fetch_assoc();
        
        http_response_code(200);
        $return = $row;
        
    } else {
        http_response_code(400);
        $return['message']  = "User not found.";
    }
    
    echo json_encode($return, JSON_HEX_APOS);
    
}
    
?>