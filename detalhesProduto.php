<?php
include "PHP/conexao.php";

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "SELECT ProdutoID, descricaoProduto, ImagemProduto, precoProduto, CategoriaID FROM produto WHERE ProdutoID = $id";
    $resultado = mysqli_query($conn, $sql);
    $tamanhos = mysqli_query($conn, "
    SELECT t.tamanhoId, t.tamanho, q.quantidade
    FROM tamanho t
    LEFT JOIN quantidade q ON q.tamanhoFKID = t.tamanhoId
    WHERE t.produtoFKID = $id
");

    if (mysqli_num_rows($resultado) > 0) {
        $produto = mysqli_fetch_assoc($resultado);
    } else {
        die("Produto não encontrado.");
    }
} else {
    die("Produto inválido.");
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($produto['descricaoProduto']) ?> - Loja Kaztor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #D9D9D9; }
        .product-container { background: #a3a3a3ff; border-radius: 10px; padding: 20px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
        .product-title { font-size: 1.6rem; font-weight: 600; }
        .product-price { font-size: 1.8rem; color: #28a745; font-weight: bold; }
        .btn-buy { font-size: 1rem; padding: 10px 20px; border-radius: 8px; flex: 1; }
        .stock { font-size: 0.95rem; }
        .attr-label { font-weight: bold; }
        .thumb-img { width: 65px; height: 65px; object-fit: cover; border-radius: 6px; cursor: pointer; border: 1px solid #ddd; }
        .thumb-img:hover { border-color: #aaa; }
        .main-img { max-height: 380px; object-fit: contain; }
        @media (max-width: 768px) {
            .product-title { font-size: 1.3rem; }
            .product-price { font-size: 1.5rem; }
            .btn-buy { font-size: 0.95rem; padding: 8px 14px; }
            .thumb-scroll { overflow-x: auto; }
        }

        .boxx{
            padding-top:50px;
        }
        .produto-card:hover {
            background: #ececec;
            transform: translateY(-3px);
            transition: 0.2s ease;
        }
    </style>
</head>
<body>
    <?php 
        include "header.php"; 
    ?> 

<div class="container py-4 boxx"  >
    <div class="row g-4 product-container ">
        <div class="col-12 col-md-6 text-center">
            <img id="mainImage" src="<?= "./" . $produto['ImagemProduto'] ?>" 
                 class="img-fluid rounded main-img shadow mb-3">
        </div>
        
        <form id="productForm" class="col-12 col-md-6" action="PHP/addCar.php" method="GET">
            <div class="mb-3">
                <h4 class='product-title'><?php echo htmlspecialchars($produto['descricaoProduto']) ?> </h4>
                <p class='display-6 fw-bold mb-0' style="color: #403232;">R$  <?php echo number_format($produto['precoProduto'], 2, ',', '.') ?></p>
            </div>
            <input type="hidden" name="id" value="<?= $produto['ProdutoID'] ?>">

            <div class="mb-3 mt-4">
                <label for="tamanho" class="attr-label d-block mb-1">Selecione o tamanho:</label>
                <select name="tamanhoID" id="tamanho" class="form-select w-75" required onchange="mostrarEstoque()">
                    <option value="">Escolha...</option>
                    <?php while ($t = mysqli_fetch_assoc($tamanhos)) { ?>
                        <option value="<?= $t['tamanhoId'] ?>" data-estoque="<?= $t['quantidade'] ?>">
                            <?= htmlspecialchars($t['tamanho']) ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div class="mb-3 stock">
                <span class="attr-label">Estoque disponível:</span> 
                <span id="estoque">Selecione um tamanho</span>
            </div>
            <div class="col-12 d-flex flex-column flex-sm-row gap-3 mt-4">
                <button type="submit"  class="btn btn-warning btn-buy" style="color:white;background: #403232;border:none;">Adicionar ao Carrinho</button>
                 <button type="button" id="comprarAgoraBtn" class="btn btn-success btn-buy" style="background: #403232;border:none;">Comprar Agora</button>
            </div>
        </form>
    </div>
        
    </div>
</div>

<div class="container py-5">
    <h2 class="mb-4 fw-bold">Outros produtos</h2>
    <div class="d-flex flex-wrap justify-content-start gap-4 pb-3">
    <div class="d-flex flex-wrap justify-content-center gap-4 pb-3">
        <?php
        $categoriaID = $produto['CategoriaID'];
        $sql_relacionados = "SELECT * FROM produto 
                             WHERE CategoriaID = $categoriaID 
                             AND ProdutoID != $id 
                             ORDER BY RAND() 
                             LIMIT 4";
        
        $resultado_relacionados = mysqli_query($conn, $sql_relacionados);
            $sql_count = "SELECT COUNT(*) as total FROM produto WHERE CategoriaID = $categoriaID";
            $count_result = mysqli_query($conn, $sql_count);
            $total_produtos = mysqli_fetch_assoc($count_result)['total'];
            $sql_cat = "SELECT * FROM categoria WHERE CategoriaID = $categoriaID ORDER BY NomeCategoria";
            $resultado_cat = mysqli_query($conn, $sql_cat);
            $categoria = mysqli_fetch_assoc($resultado_cat);
            $categoriaID = $categoria['CategoriaID'];
            $nomeCategoria = $categoria['NomeCategoria'];
        if (mysqli_num_rows($resultado_relacionados) > 0) {
            while ($p_relacionado = mysqli_fetch_assoc($resultado_relacionados)) {
                $imagem = !empty($p_relacionado['ImagemProduto']) 
                    ? "./" . htmlspecialchars($p_relacionado['ImagemProduto']) 
                    : "https://via.placeholder.com/300x200?text=Sem+Imagem";
                
                echo "
                <a href='detalhesProduto.php?id=" . $p_relacionado['ProdutoID'] . "' class='text-decoration-none text-dark'>
                    <div class='produto-card d-flex flex-column' 
                        style='width: 16rem; min-width: 16rem; height: 400px; 
                               background: #c3bfbf; border-radius: 8px; 
                               overflow: hidden; padding: 12px;'>
                        <div class='img-container' style='flex: 1; display: flex; align-items-center; justify-content: center;'>
                            <img src='$imagem' 
                                 style='width: 100%; height: 230px; object-fit: cover; border-radius: 6px;' 
                                 alt='Produto'>
                        </div>
                        <div class='mt-3'>
                            <h6 class='fw-normal text-truncate mb-1'>" . htmlspecialchars($p_relacionado['descricaoProduto']) . "</h6>
                        </div>
                        <div class='mt-auto'>
                            <p class='fw-bold text-dark fs-5 mb-0'>R$ " . number_format($p_relacionado['precoProduto'], 2, ',', '.') . "</p>
                        </div>
                    </div>
                </a>";
            }
            if ($total_produtos > 4) {
                    echo "
                    <div class='text-center mb-5'>
                        <a href='/TCCphpJoca/PHP/listaProdutos.php?categoria={$categoriaID}' class='btn btn-outline-dark'>Ver mais em {$nomeCategoria}</a>
                    </div>";
                }
        } else {
            echo "<div class='alert alert-warning w-100'>Nenhum produto relacionado encontrado.</div>";
        }
        mysqli_close($conn);
        ?>
    </div>
</div>

</body>
<script>
    function mostrarEstoque() {
        const select = document.getElementById('tamanho');
        const estoqueSpan = document.getElementById('estoque');
        const selectedOption = select.options[select.selectedIndex];
        const estoque = selectedOption.dataset.estoque;
        estoqueSpan.textContent = estoque ? `${estoque} unidades` : 'Selecione um tamanho';
    }

    document.getElementById('comprarAgoraBtn').addEventListener('click', function() {
        const form = document.getElementById('productForm');
        form.action = 'PHP/compraragora.php';
        form.submit();
    });
</script>
</html>