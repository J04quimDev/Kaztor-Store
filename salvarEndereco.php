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

$sql_insert = "INSERT INTO endereco (
                estadoEndereco, cidadeEndereco, bairroEndereco, ruaEndereco, 
                numeroEndereco, complementoEndereco, LoginClienteFK)
             VALUES 
                ('$estado', '$cidade', '$bairro', '$rua', $numero, '$complemento', $idUser)";

if ($conn->query($sql_insert) === TRUE) {

    // pega o id do endereço recém cadastrado
    $novoEnderecoID = $conn->insert_id;

    // seta como endereço principal do usuário
    $sql_update_user = "UPDATE logincliente 
                        SET enderecoID = $novoEnderecoID 
                        WHERE LoginClienteID = $idUser";

    $conn->query($sql_update_user);

    echo "<script>
            alert('Endereço salvo e definido como principal!');
            window.location.href='dadospessoais.php';
          </script>";

} else {
    echo "Erro ao salvar endereço: " . $conn->error;
}

$conn->close();
?>
