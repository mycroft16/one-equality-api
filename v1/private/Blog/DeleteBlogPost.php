<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, HEAD, OPTIONS, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Authorization, Origin, X-Requested-With, Content-Type, Accept");
header("Access-Control-Expose-Headers: Access-Control-Allow-Headers, Origin, Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers");

ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] != "OPTIONS") {

    require_once('../config.inc');

    $delete = "DELETE FROM `blogPosts` WHERE `id` = '".$_GET['postId']."'";
    if ($mysql->query($delete)) {

        $deleteComments = "DELETE FROM `blogComments` WHERE `post` = '".$_GET['postId']."'";
        if ($mysql->query($deleteComments)) {

            http_response_code(200);
            $return true;

        } else {

            http_response_code(500);
            $return['error'] = "Error deleting comments: ".$mysql->error;

        }

    } else {
        
        http_response_code(500);
        $return['error'] = "Error deleting blog: ".$mysql->error;

    }
    
    echo json_encode($return, JSON_HEX_APOS);

}
?>