<?php

    $server = "localhost";
    $user = "root";
    $pass = "";
    $bd = "tccccccccc"

    if( $conn - mysqli_connect($server,$user,$pass,$bd)){
        echo "cONECTADO"
    }
    else{
        echo "ERROO"
    }

?>