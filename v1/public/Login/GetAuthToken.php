<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, HEAD, OPTIONS, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Authorization, Origin, X-Requested-With, Content-Type, Accept");
header("Access-Control-Expose-Headers: Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers");

ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] != "OPTIONS") {

    require_once($_SERVER['DOCUMENT_ROOT'].'/v1/config.inc');
    
    $encryptedPassword = trim($_GET['password']);
    
    $query = "SELECT * FROM `users` WHERE `emailAddress` = '".$mysql->real_escape_string(trim($_GET['username']))."' AND `password` = '".$encryptedPassword."'";
    $res = $mysql->query($query);
    if($res->num_rows > 0) {
        $row = $res->fetch_assoc();

        if ($row['status'] == 'Active') {
        
            $authToken = base64_encode(date('Y-m-d H:i:s')."||".$row['id']."||".$row['account']."||".$row['emailAddress']);
            
            http_response_code(200);
            $return['status']       = "success";
            $return['authToken']    = "Bearer ".$authToken;
            $return['expiration']   = 86400;
        } else {
            http_response_code(401);
            $return['status']       = $row['status'];
            switch($row['status']) {
                case 'Pending':
                    $return['message'] = 'You must authenticate your account first. Please check your email and click the link.';
                    break;
                case 'Suspended':
                    $return['message'] = 'Your account has been suspended for violations of the terms of service or code of conduct.';
                    break;
                case 'Inactive':
                    $return['message'] = 'Your account has been deactivated. You may reactivate it at any time.';
                    break;
                case 'Deleted':
                    $return['message'] = 'No account matching that email was found.';
            }
        }
        
    } else {
        http_response_code(200);
        $return['status']   = "failed";
        $return['message']  = "No account matching that email was found.";
    }
    
    echo json_encode($return, JSON_HEX_APOS);
    
}
    
?>