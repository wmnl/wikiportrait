<?php
    require_once 'connect.php';
    
    function generatesalt(){
        return sha1(rand());
    }
    
    function generatepassword($password, $salt) {
	return sha1($password . $salt);
    }
?>