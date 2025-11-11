<?php
include "PHP/conexao.php";
include "PHP/Login.php";
include "PHP/protect.php";

if (!isset($_GET['id'])) {
    die("ID inválido.");
}

$idProduto = intval($_GET['id']);
$idUser = $_SESSION['LoginClienteID'];

$sql = "SELECT quantidadeCarrinho FROM carrinho WHERE loginclienteID = $idUser AND produtoID = $idProduto";
$resultado = mysqli_query($conn, $sql);

if ($resultado && mysqli_num_rows($resultado) > 0) {
    $row = mysqli_fetch_assoc($resultado);
    $quantidade = $row['quantidadeCarrinho'];

    $sqlEstoque = "UPDATE produto 
                   SET quantidadeEstoqueProduto = quantidadeEstoqueProduto + $quantidade 
                   WHERE ProdutoID = $idProduto";
    mysqli_query($conn, $sqlEstoque);

    $sqlDelete = "DELETE FROM carrinho WHERE loginclienteID = $idUser AND produtoID = $idProduto";
    if (mysqli_query($conn, $sqlDelete)) {
        header("Location: carrinho.php");
        exit;
    } else {
        echo "Erro ao remover o produto.";
    }
} else {
    echo "Produto não encontrado no carrinho.";
}
?>
