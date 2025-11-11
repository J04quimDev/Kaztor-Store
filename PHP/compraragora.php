<?php
include "conexao.php"; 
include "Login.php";
include "protect.php";

$iduser = $_SESSION['LoginClienteID'];
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sqlCheck = "SELECT CPFCliente, enderecoID FROM logincliente WHERE LoginClienteID = $iduser";
    $res = mysqli_query($conn, $sqlCheck);
    $dados = mysqli_fetch_assoc($res);
if (empty($dados['CPFCliente']) || empty($dados['enderecoID'])) {
    echo "<script>
        alert('Por favor, complete seu cadastro (CPF, telefone e endereço) antes de finalizar a compra.');
        window.location.href = '../dadospessoais.php';
    </script>";
    exit;
}else {
    
$produtoID = intval($_GET['id']);

$sqlProduto = "SELECT * FROM produto WHERE ProdutoID = $produtoID";
$resProduto = mysqli_query($conn, $sqlProduto);

if (mysqli_num_rows($resProduto) == 0) {
    die("Produto não encontrado.");
}
$produto = mysqli_fetch_assoc($resProduto);
$data = date('Y-m-d');
$valorTotal = $produto['precoProduto'];
$status = "Pedido Recebido";

$sqlPedido = "INSERT INTO pedido (dataPedido, valorTotalPedido, statusPedido, loginclienteIDFK)
              VALUES ('$data', $valorTotal, '$status', $iduser)";

if (mysqli_query($conn, $sqlPedido)) {
    $novoPedidoID = $conn->insert_id;
    $sqlItem = "INSERT INTO itempedido (idPedido, idProduto, qtdProduto) VALUES ($novoPedidoID, $produtoID, 1)";
    mysqli_query($conn, $sqlItem);
    header("Location: /TCCphpJoca/pedido.php");
    exit;
} else {
    echo "Erro ao criar o pedido: " . mysqli_error($conn);
}

mysqli_close($conn);
}
}else{
    die("Usuário não logado.");
}
?>