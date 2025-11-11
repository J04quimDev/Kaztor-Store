<?php

if(!isset($_SESSION)) {
    session_start();
}

if(!isset($_SESSION['LoginClienteID'])){
    header("Location: perfilLogin.php");
    exit;
}

?>