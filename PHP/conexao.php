<?php
    $server = "localhost";
    $user = "root";
    $pass = "";
    $bd = "tccccccccc";

    if ( $conn = mysqli_connect($server,$user,$pass,$bd)){
    }
    else{
      echo "Falha na conexao";
    }
?>