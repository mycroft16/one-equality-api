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

    $post = json_decode(file_get_contents('php://input'));

    $encryptedPassword = encryptIt(trim($post->password));
    
    $insert = "INSERT INTO `users` SET
        `displayName`   = '".$mysql->real_escape_string(trim($post->displayName))."',
        `firstName`     = '".$mysql->real_escape_string(trim($post->firstName))."',
        `lastName`      = '".$mysql->real_escape_string(trim($post->lastName))."',
        `emailAddress`  = '".$mysql->real_escape_string(trim($post->emailAddress))."',
        `password`      = '".$encryptedPassword."',
        `zipCode`       = '".$mysql->real_escape_string(trim($post->zipCode))."',
        `mobilePhone`   = '".$mysql->real_escape_string(trim($post->mobilePhone))."',
        `statusDate`    = NOW()";
    if ($mysql->query($insert)) {

        // INSERT TO SECURITY QUESTION TABLE HERE
        $userId = $mysql->insert_id;
        $insertSecurity = "INSERT INTO `securityQuestions` 
        SET 
            `user`      = '".$userId."', 
            `question`  = '".$mysql->real_escape_string(trim($post->securityQuestion))."', 
            `answer`    = '".$mysql->real_escape_string(trim($post->securityAnswer))."',
            `hint`      = '".$mysql->real_escape_string(trim($post->securityHint))."'";
        $mysql->query($insertSecurity);

        include_once('../../helpers/send-welcome-email.php');
        // make send-welcome-email a class
        include_once('../../helpers/Class.ValidationToken.php');
        $validationToken = new ValidationToken($userId);
        
        sendWelcomeEmail($post->emailAddress, $post->firstName, $validationToken->generateToken());

        http_response_code(200);
        $return['emailAddress'] = trim($post->emailAddress);
    } else {
        http_response_code(500);
        $return['error'] = "An error occurred creating the user";
    }

    echo json_encode($return, JSON_HEX_APOS);
    exit();

}
?>