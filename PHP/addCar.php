<?php
include "conexao.php";
include "Login.php";

$iduser = $_SESSION['LoginClienteID'];

if (!isset($_GET['id']) || !isset($_GET['tamanhoID'])) {
    die("Parâmetros inválidos.");
}

$produtoID = intval($_GET['id']);
$tamanhoID = intval($_GET['tamanhoID']);

$sqlVerifica = "SELECT t.tamanho, q.quantidade 
                FROM tamanho t
                LEFT JOIN quantidade q ON q.tamanhoFKID = t.tamanhoId
                WHERE t.tamanhoId = $tamanhoID AND t.produtoFKID = $produtoID";
$resVerifica = mysqli_query($conn, $sqlVerifica);

if (!$resVerifica || mysqli_num_rows($resVerifica) == 0) {
    die("Tamanho inválido para este produto.");
}

$dados = mysqli_fetch_assoc($resVerifica);
$estoqueAtual = intval($dados['quantidade']);

if ($estoqueAtual <= 0) {
    die("Este tamanho está sem estoque.");
}
$sqlCarrinho = "SELECT quantidadeCarrinho 
                FROM carrinho 
                WHERE ProdutoID = $produtoID 
                AND LoginClienteID = $iduser 
                AND tamanhoID = $tamanhoID";
$resCarrinho = mysqli_query($conn, $sqlCarrinho);

if ($resCarrinho && mysqli_num_rows($resCarrinho) > 0) {
    $carrinho = mysqli_fetch_assoc($resCarrinho);
    $novaQuantidade = $carrinho['quantidadeCarrinho'] + 1;

    $sqlUpdateCarrinho = "UPDATE carrinho 
                          SET quantidadeCarrinho = $novaQuantidade 
                          WHERE ProdutoID = $produtoID 
                          AND LoginClienteID = $iduser 
                          AND tamanhoID = $tamanhoID";
    mysqli_query($conn, $sqlUpdateCarrinho);

} else {
    $sqlInsertCarrinho = "INSERT INTO carrinho (LoginClienteID, ProdutoID, tamanhoId, quantidadeCarrinho)
                          VALUES ($iduser, $produtoID, $tamanhoID, 1)";
    mysqli_query($conn, $sqlInsertCarrinho);
}
$novoEstoque = $estoqueAtual - 1;
$sqlUpdateEstoque = "UPDATE quantidade 
                     SET quantidade = $novoEstoque 
                     WHERE tamanhoFKID = $tamanhoID";
mysqli_query($conn, $sqlUpdateEstoque);
header("Location: /TCCphpJoca/carrinho.php");
exit;
?>
