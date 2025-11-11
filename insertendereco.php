<?php
include "PHP/conexao.php";
session_start();

if (!isset($_SESSION['LoginClienteID'])) {
    die("Usuário não logado.");
}

$idUser = $_SESSION['LoginClienteID'];

$estado = $_POST['estadoEndereco'] ?? '';
$cidade = $_POST['cidadeEndereco'] ?? '';
$bairro = $_POST['bairroEndereco'] ?? '';
$rua = $_POST['ruaEndereco'] ?? '';
$numero = $_POST['numeroEndereco'] ?? '';
$complemento = $_POST['complementoEndereco'] ?? '';

$sql = "INSERT INTO endereco (
            estadoEndereco, 
            cidadeEndereco, 
            bairroEndereco, 
            ruaEndereco, 
            numeroEndereco, 
            complementoEndereco, 
            loginClienteFK
        ) VALUES (
            '$estado', 
            '$cidade', 
            '$bairro', 
            '$rua', 
            '$numero', 
            '$complemento', 
            $idUser
        )";

if (mysqli_query($conn, $sql)) {
    echo "<script>
            alert('Endereço salvo com sucesso!');
            window.location.href='dadospessoais.php';
          </script>";
} else {
    echo "Erro ao salvar endereço: " . mysqli_error($conn);
}
?>
