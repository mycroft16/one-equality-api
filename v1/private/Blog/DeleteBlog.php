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

    $query = "SELECT `id` FROM `blogPosts` WHERE `user` = '".$userId."'";
    $res = $mysql->query($query);
    while($row = $res->fetch_assoc()) {
        $deleteComments = "DELETE FROM `blogComments` WHERE `post` = '".$row['id']."'";
        $mysql->query($deleteComments);
    }

    $delete = "DELETE FROM `blogPosts` WHERE `user` = '".$userId."'";
    $mysql->query($delete);

    $deleteBlog = "DELETE FROM `blogs` WHERE `user` = '".$userId."'";
    $mysql->query($deleteBlog);

    http_response_code(200);
    $return true;

    echo json_encode($return, JSON_HEX_APOS);

}
?>