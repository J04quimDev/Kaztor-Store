<?php
session_start();
include "PHP/conexao.php";
include "header.php";
include "PHP/Login.php";
include "PHP/protect.php";

$id_usuario = $_SESSION['LoginClienteID'];

$sql = "SELECT * FROM pedido WHERE loginclienteIDFK = ?
        ORDER BY dataPedido DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$pedidos_result = $stmt->get_result(); // Renomeado para evitar conflito e clareza
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Andamento do Pedido - Loja Kaztor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .pedido-card {
            background: #fff;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }
        .pedido-titulo {
            font-size: 1.3rem;
            font-weight: 600;
        }
        .progress {
            height: 18px;
            border-radius: 10px;
        }
        .status-text {
            font-weight: 500;
            margin-top: 10px;
        }
        .status-etapas {
            display: flex;
            justify-content: space-between;
            font-size: 0.9rem;
            color: #777;
        }
        @media (max-width: 768px) {
            .pedido-titulo { font-size: 1.1rem; }
        }
    </style>
</head>
<body>

<div class="container py-5">
    <h2 class="text-center mb-5"> Meus Pedidos </h2>

<?php if ($pedidos_result->num_rows > 0): ?>
        <?php 
        while ($pedido = $pedidos_result->fetch_assoc()): 
        $status = strtolower($pedido['statusPedido']);
        $progress = 0;

        if ($status == 'pedido recebido') $progress = 25;
        elseif ($status == 'em preparação') $progress = 50;
        elseif ($status == 'saiu para entrega') $progress = 75;
        elseif ($status == 'entregue') $progress = 100;
    ?>
        <div class='pedido-card'>
            <div class="mb-4">
                <?php
                $sqlselectitempedido = "SELECT ip.*, p.*, t.tamanho, pe.*
                                        FROM pedido pe
                                        INNER JOIN itempedido ip on ip.idPedido = pe.PedidoID
                                        INNER JOIN produto p ON ip.idProduto = p.ProdutoID
                                        INNER JOIN tamanho t ON ip.tamanhoID = t.tamanhoID
                                        WHERE ip.idPedido = ?";
                $stmt_items = $conn->prepare($sqlselectitempedido);
                $stmt_items->bind_param("i", $pedido['PedidoID']);
                $stmt_items->execute();
                $resselectitempedido = $stmt_items->get_result();
                while($item = $resselectitempedido->fetch_assoc()){
                    $data = date('d/m/Y', strtotime($item['dataPedido']));
                    echo "
                    <div class='d-flex align-items-center mb-3'>
                        <img src='./" . htmlspecialchars($item['ImagemProduto']) . "' class='produto-img me-3' alt='Produto' style='width: 80px; height: 80px; object-fit: cover; border-radius: 5px;'>
                        <div class='flex-grow-1'>
                            <h6 class='mb-1'>" . htmlspecialchars($item['descricaoProduto']) . "</h6>
                            <p class='text-muted small mb-0'>Tamanho: " . htmlspecialchars($item['tamanho']) . "</p>
                            <p class='text-muted small mb-0'>Quantidade: " . htmlspecialchars($item['qtdProduto']) . "</p>
                            <p class='fw-bold mb-0 text-success'>R$ " . number_format($item['precoProduto'] * $item['qtdProduto'], 2, ',', '.') . "</p>
                        </div>
                    </div>";
                }
                
                ?>
            </div>
            <div class="mt-4">
                <?php echo "
                    <p class='text-muted mb-2'>Data do pedido: " . $data . " </p>
                    <p class='fw-bold mb-0 text-success'> Valor Total do Pedido: R$".number_format($pedido['valorTotalPedido'], 2, ',', '.')." </p>";
                    $stmt_items->close();
                ?>
                <div class="progress">
                    <div class="progress-bar bg-success" style="width: <?= $progress ?>%;"></div>
                </div>
                <div class="status-etapas mt-2">
                    <span>Recebido</span>
                    <span>Preparação</span>
                    <span>Entrega</span>
                    <span>Finalizado</span>
                </div>
                <p class="status-text text-center mt-3 text-success"><?= ucfirst($pedido['statusPedido']) ?></p>
            </div>
        </div>
        
    <?php endwhile; ?> 
<?php else: ?>
    <div class="alert alert-warning text-center">Você ainda não comprou nenhum produto.</div>
<?php endif; ?>
</div>

</body>
</html>