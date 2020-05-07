<?php
class ValidationToken {

    private $userId;

    public function __construct($userId = null) {
        $this->userId = $userId;
    }

    public function generateToken() {
        $time = strtotime('now') + 86400;
        return base64_encode($this->userId."||Pending||".$time);
    }

    public function deconstructToken($token) {
        return explode("||", base64_decode($token));
    }

}
?>