<?php
class Token_CSRF{
    public function __construct(){

    }
public function check_csrf() : bool{
        if (empty($_POST['token_csrf']) || $_POST['token_csrf'] !== $_SESSION['token']) {
            return false;
        }
        return true;
    }

}


?>