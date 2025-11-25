<?php
include "PHP/conexao.php";
session_start();

if (!isset($_SESSION['LoginClienteID'])) {
    die("Usuário não logado.");
}

$enderecoID = $_POST['enderecoID'] ?? null;
$estado = $_POST['estadoEndereco'] ?? '';
$cidade = $_POST['cidadeEndereco'] ?? '';
$bairro = $_POST['bairroEndereco'] ?? '';
$rua = $_POST['ruaEndereco'] ?? '';
$numero = $_POST['numeroEndereco'] ?? '';
$complemento = $_POST['complementoEndereco'] ?? '';


    $sql_insert = "INSERT INTO endereco (enderecoID,
                estadoEndereco, cidadeEndereco, bairroEndereco, ruaEndereco, 
                numeroEndereco, complementoEndereco)
             VALUES (DEFAULT, '$estado', '$cidade', '$bairro','$rua', $numero, '$complemento');";

    $stmt = $conn->prepare($sql_insert);

    if ($stmt->execute()) {
        $novoEnderecoID = $conn->insert_id;
        echo "<script>
                alert('Endereço salvo com sucesso!');
                window.location.href='dadospessoais.php';
              </script>";
    } else {
        echo "Erro ao salvar endereço: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();
?>
