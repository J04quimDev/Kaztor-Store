<?php
    include "conexao.php";

    $nomeCliente = $_POST["nomeCliente"];
    $emailCliente = $_POST["emailCliente"];
    $senhaCliente = $_POST["senhaCliente"];
    $sql =" INSERT INTO logincliente
    (LoginClienteID, nomeCliente, emailCliente, senhaCliente, tipoID)
     VALUES (DEFAULT,'$nomeCliente','$emailCliente','$senhaCliente', 1)";
    if (mysqli_query($conn,$sql)){
        echo "Registro Feito com Sucesso";
    }
    else{
        echo "Falha no SQL";
    }
    
    include "Login.php";
    ?>