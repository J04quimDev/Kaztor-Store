<?php
    session_start();
    $LoginClienteID = $_SESSION['LoginClienteID'];
    include "PHP/conexao.php";

    $estado = $_POST['estadoEndereco'];
    $estadoendereco = substr($estado[0], 0, 1) . substr($estado[1], 0, 1);
    $cidade = $_POST['cidadeEndereco'];
    $bairro = $_POST['bairroEndereco'];
    $rua = $_POST['ruaEndereco'];
    $numeroEndereco = $_POST['numeroEndereco'];
    $complementoEndereco = $_POST['complementoEndereco'];

    $sql_code = "INSERT INTO endereco 
    (enderecoID, estadoEndereco, cidadeEndereco, bairroEndereco, ruaEndereco, numeroEndereco, complementoEndereco)
    VALUES (DEFAULT, '$estadoendereco', '$cidade', '$bairro', '$rua', '$numeroEndereco', '$complementoEndereco')";

    if (mysqli_query($conn,$sql_code)){
        echo "Registro Feito com Sucesso";
    }
    else{
        echo "Falha no SQL";
    }
    $sql = "UPDATE logincliente SET enderecoID = (SELECT MAX(enderecoID) FROM endereco) WHERE LoginClienteID = '$LoginClienteID'";
    if (mysqli_query($conn,$sql)) {
        echo "Endereço atualizado com sucesso";
        header("Location: /TCCphpJoca/index.php");
        exit;
    } else {
        echo "Falha ao atualizar endereço: " . mysqli_error($conn);
    }

?>
