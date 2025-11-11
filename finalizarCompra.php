<?php
session_start();
include "PHP/conexao.php";
include "PHP/Login.php";
include "PHP/protect.php";

$idUser = $_SESSION['LoginClienteID'];

$sqlCheck = "SELECT cpfCliente, telefoneCliente 
             FROM logincliente 
             WHERE LoginClienteID = $idUser";
$checkResult = mysqli_query($conn, $sqlCheck);
$userData = mysqli_fetch_assoc($checkResult);

$sqlEndereco = "SELECT e.enderecoID 
                FROM endereco e INNER JOIN logincliente l
                ON l.enderecoID = e.enderecoID
                WHERE l.LoginClienteID = $idUser
                LIMIT 1";
$resEndereco = mysqli_query($conn, $sqlEndereco);
$temEndereco = mysqli_num_rows($resEndereco) > 0;

if (
    empty($userData['cpfCliente']) || 
    empty($userData['telefoneCliente']) || 
    !$temEndereco
) {
    echo "<script>
        alert('Por favor, complete seu cadastro (CPF, telefone e endereço) antes de finalizar a compra.');
        window.location.href = 'dadospessoais.php';
    </script>";
    exit;
}

$sql = "SELECT p.*, c.* , t.*
        FROM carrinho c
        INNER JOIN produto p ON c.produtoID = p.ProdutoID
        INNER JOIN tamanho t ON c.tamanhoID = t.tamanhoID
        WHERE c.LoginclienteID = $idUser";
$resultado = mysqli_query($conn, $sql);

if (!$resultado || mysqli_num_rows($resultado) == 0) {
    echo "<script>alert('Seu carrinho está vazio!'); window.location.href='/TCCphpJoca/carrinho.php';</script>";
    exit;
}

$valorTotal = 0;
while ($item = mysqli_fetch_assoc($resultado)) {
    $valorTotal += $item['precoProduto'] * $item['quantidadeCarrinho'];
    
}

mysqli_data_seek($resultado, 0);

$data = date('Y-m-d');
$status = "Pedido Recebido";
$sqlPedido = "INSERT INTO pedido (dataPedido, valorTotalPedido, statusPedido, loginclienteIDFK)
              VALUES ('$data', $valorTotal, '$status', $idUser)";

if ($conn->query($sqlPedido) === TRUE) {
    $pedidoGerado = $conn->insert_id;

    while ($item = mysqli_fetch_assoc($resultado)) {
        $tamanho = $item['tamanhoID'];
        $ProdutoID = $item['ProdutoID'];
        $qtdProduto = $item['quantidadeCarrinho'];
        $insertItemPedido = "INSERT INTO itempedido (idPedido, idProduto, qtdProduto, tamanhoID) VALUES ($pedidoGerado, $ProdutoID, $qtdProduto, $tamanho)";
        if (!mysqli_query($conn, $insertItemPedido)) {
            error_log("Erro ao inserir item do pedido: " . mysqli_error($conn));
        }
    }

    $sqlLimpa = "DELETE FROM carrinho WHERE loginclienteID = $idUser";
    mysqli_query($conn, $sqlLimpa);

    header("Location: pedido.php?success=1&pedidoID={$pedidoGerado}");
    exit;

} else {
    echo "Erro ao finalizar o pedido: " . $conn->error;
}
?>
