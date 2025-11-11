<?php
if(session_status() === PHP_SESSION_NONE){
    session_start();
}
include "conexao.php";  

if(isset($_POST['emailCliente']) && isset($_POST['senhaCliente'])){
    
    if(empty($_POST['emailCliente'])){
        echo "Preencha seu email";
    } else if(empty($_POST['senhaCliente'])){
        echo "Preencha sua senha";
    } else {
        $emailCliente = $conn->real_escape_string($_POST['emailCliente']);
        $senhaCliente = $conn->real_escape_string($_POST['senhaCliente']);

        $sql_code = "SELECT * FROM logincliente WHERE emailCliente = '$emailCliente' AND senhaCliente = '$senhaCliente'";
        $sql_query = $conn->query($sql_code) or die("Falha na execução do código SQL: " . $conn->error);

        if($sql_query->num_rows == 1){
            $usuario = $sql_query->fetch_assoc();
            $_SESSION['LoginClienteID'] = $usuario['LoginClienteID'];
            $_SESSION['nomeCliente'] = $usuario['nomeCliente'];
            header("Location: /TCCphpJoca/index.php");
            exit;
        } else {
            echo "Falha ao logar! E-mail ou senha incorretos";
        }
    }
}
?>