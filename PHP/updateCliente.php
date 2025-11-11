<?php
session_start();
include "conexao.php";
if (!isset($_SESSION['LoginClienteID'])) {
    die("Acesso negado. Faça o login.");
}
$LoginClienteID = $_SESSION['LoginClienteID'];

function validaCPF($cpf) {
    $cpf = preg_replace( '/[^0-9]/is', '', $cpf );
     
    if (strlen($cpf) != 11) {
        return false;
    }
    if (preg_match('/(\d)\1{10}/', $cpf)) {
        return false;
    }
    for ($t = 9; $t < 11; $t++) {
        for ($d = 0, $c = 0; $c < $t; $c++) {
            $d += $cpf[$c] * (($t + 1) - $c);
        }
        $d = ((10 * $d) % 11) % 10;
        if ($cpf[$c] != $d) {
            return false;
        }
    }
    return true;

}

$updates = [];
$params = [];
$types = '';

if (!empty($_POST['nomeCliente'])) {
    $updates[] = "nomeCliente = ?";
    $params[] = $_POST['nomeCliente'];
    $types .= 's';
}
if (!empty($_POST['emailCliente'])) {
    $updates[] = "emailCliente = ?";
    $params[] = $_POST['emailCliente'];
    $types .= 's';
}
if (!empty($_POST['senhaCliente'])) {
    $updates[] = "senhaCliente = ?";
    $params[] = $_POST['senhaCliente'];
    $types .= 's';
}
if (!empty($_POST['CPFCliente'])) {
    $cpf = $_POST['CPFCliente'];
    if (validaCPF($cpf)) {
        $cpfNumeros = preg_replace('/[^0-9]/', '', $cpf);
        $updates[] = "CPFCliente = ?";
        $params[] = $cpfNumeros;
        $types .= 's';
    } else {
        echo "<script>
                alert('CPF inválido! Por favor, verifique os dados e tente novamente.');
                window.location.href='../dadospessoais.php';
              </script>";
        exit;
    }
}
if (!empty($_POST['dataNascimentoCliente'])) {
    $updates[] = "dataNascimentoCliente = ?";
    $params[] = $_POST['dataNascimentoCliente'];
    $types .= 's';
}
if (!empty($_POST['telefoneCliente'])) {
    $telefoneNumeros = preg_replace('/[^0-9]/', '', $_POST['telefoneCliente']);
    $updates[] = "telefoneCliente = ?";
    $params[] = $telefoneNumeros;
    $types .= 's';
}

if (!empty($updates)) {
    $sql_code = "UPDATE logincliente SET " . implode(', ', $updates) . " WHERE LoginClienteID = ?";
    $types .= 'i';
    $params[] = $LoginClienteID;

    $stmt = $conn->prepare($sql_code);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
}

header("Location: ../dadospessoais.php?success=1");
exit;
?>
