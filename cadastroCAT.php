<?php
  include "conexao.php";

    $catnome = $_POST["nomeCat"];
    
    $sql = "INSERT INTO categoria
    (CategoriaID, NomeCategoria)
     VALUES (DEFAULT,'$catnome')";

    if (mysqli_query($mysqli,$sql)){
        header("Location: ../index.php");
        exit;
    }
    else{
    }
?>