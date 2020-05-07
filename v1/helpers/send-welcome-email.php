<?php
function sendWelcomeEmail($to, $name, $token) {
    $headers = "From: registration@one-equality.com"."\r\n";
    $headers .= "Reply-To: registration@one-equality.com"."\r\n";
    $headers .= "X-Mailer: PHP/".phpversion();

    $subject = 'Welcome to One Equality';
    $message = 'Welcome to One Equality '.$name.'!';
    $message .= "\r\n\r\n";
    $message .= 'In order to activate your account, please click on the link below within 24 hours.';
    $message .= "\r\n\r\n";
    $message .= 'http://localhost:4200/account-validation/'.$token.'';
    $message .= "\r\n\r\n";
    $message .= 'Sincerely.'."\r\n";
    $message .= 'One Equality';

    mail($to, $subject, $message, $headers, "-f registration@one-equality.com");
}
?>