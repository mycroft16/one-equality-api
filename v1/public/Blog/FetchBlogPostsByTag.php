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

    $query = "SELECT * FROM `blogPosts` WHERE `tags` LIKE '%".$_GET['tag']."%' ORDER BY `createdOn` DESC";
    $res = $mysql->query($query);
    if ($res->num_rows > 0) {

        http_response_code(200);
        while($row = $res->fetch_assoc()) {
            $return['posts'][] = $row;
        }

    } else {

        http_response_code(500);
        $return['error'] = "No posts matching ".$_GET['tag']." found";

    }

    echo json_encode($return, JSON_HEX_APOS);

}
?>