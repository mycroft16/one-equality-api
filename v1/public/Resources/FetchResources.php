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

    $query = "SELECT 
        * 
    FROM 
        `resources`
    WHERE
        `category` LIKE '%".$_GET['category']."%' AND 
        `tags` LIKE '%".$_GET['tags']."%'
    ORDER BY
        `state` ASC,
        `name` ASC";
    $res = $mysql->query($query);

    $return = [];
    while($row = $res->fetch_assoc()) {
        $return[] = $row;
    }

    http_response_code(200);
    echo json_encode($return, JSON_HEX_APOS);

}
?>