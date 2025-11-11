<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kaztor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous" />
 
    <link rel="stylesheet" href="css/style.css"> <style>

    body {
      background-color: #f8f9fa;
    }

    .cart-container {
      margin-top: 50px;
    }

    .photo-thumbnail {
      width: 80px;
      height: 80px;
      object-fit: cover;
      border-radius: 5px;
    }

    .cart-summary {
      background-color: #fff;
      border-radius: 8px;
      padding: 25px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
    }

    .btn-remove {
      color: #dc3545;
      font-size: 0.9rem;
    }

    .cart-item {
      border-bottom: 1px solid #dee2e6;
      padding: 15px 0;
    }

    .cart-total {
      font-size: 1.2rem;
      font-weight: bold;
    }

    .btn-checkout {
      background-color: #e60023;
      border: none;
    }

    .btn-checkout:hover {
      background-color: #c2001d;
    }
  </style>
</head>
<body>



<?php
include "header.php";
?>


<div class="container cart-container">
  <div class="row">
    <!-- Coluna da lista de fotos -->
    <div class="col-lg-8">
      <h4 class="mb-4">Meu Carrinho (4 itens)</h4>

      <!-- Item do carrinho -->
      <div class="cart-item d-flex align-items-center">
        <img src="https://via.placeholder.com/80" class="photo-thumbnail me-3" alt="Foto">
        <div class="flex-grow-1">
          <div><strong>1º Território Race</strong></div>
          <div class="text-muted small">Código da foto: 901027</div>
          <button class="btn btn-link btn-remove p-0 mt-1">🗑 Remover</button>
        </div>
        <div class="text-end"><strong>R$ 12,00</strong></div>
      </div>

      <!-- Repetir para cada foto -->
      <div class="cart-item d-flex align-items-center">
        <img src="https://via.placeholder.com/80" class="photo-thumbnail me-3" alt="Foto">
        <div class="flex-grow-1">
          <div><strong>1º Território Race</strong></div>
          <div class="text-muted small">Código da foto: 901052</div>
          <button class="btn btn-link btn-remove p-0 mt-1">🗑 Remover</button>
        </div>
        <div class="text-end"><strong>R$ 12,00</strong></div>
      </div>

      <!-- Adicione mais itens conforme necessário... -->

    </div>

    <!-- Coluna do resumo -->
    <div class="col-lg-4">
      <div class="cart-summary">
        <h5>Resumo do Carrinho</h5>
        <div class="d-flex justify-content-between mt-3">
          <span>Itens (4)</span>
          <span>R$ 48,00</span>
        </div>
        <div class="d-flex justify-content-between mt-2 mb-3">
          <span>Desconto</span>
          <span>-</span>
        </div>
        <div class="d-flex justify-content-between cart-total border-top pt-3">
          <span>Total a pagar</span>
          <span>R$ 48,00</span>
        </div>
        <button class="btn btn-checkout w-100 mt-4 text-white py-2">Continuar compra →</button>
      </div>
    </div>
  </div>
</div>


</body>
</html>

    <?php
        include "PHP/conexao.php";
        include "header.php";
        include "PHP/Login.php";
        include "PHP/protect.php";

        $sql = "SELECT * FROM logincliente l inner join carrinho c on l.LoginClienteID = c.loginclienteID inner join produto p on c.produtoID = p.ProdutoID where c.loginclienteID = " . $_SESSION['LoginClienteID'] . ";"; 
        $resultado = mysqli_query($conn, $sql);
        if (mysqli_num_rows($resultado) > 0) {
            while ($produto = mysqli_fetch_assoc($resultado)) {
                
                $imagem = !empty($produto['ImagemProduto']) 
                    ? "./PHP/" . htmlspecialchars($produto['ImagemProduto']) 
                    : "https://via.placeholder.com/300x200?text=Sem+Imagem";
                
                echo "
                <a href='detalhesProduto.php?id=" .  $produto['ProdutoID'] . "' class='text-decoration-none text-dark'>
                    <div class='produto-card d-flex flex-column' 
                        style='width: 16rem; min-width: 16rem; height: 400px; 
                               background: #f9f9f9; border-radius: 8px; 
                               overflow: hidden; padding: 12px; 
                               transition: 0.2s ease;'>
                        
                        <div class='img-container' 
                            style='flex: 1; display: flex; align-items: center; justify-content: center;'>
                            <img src='$imagem' 
                                 style='width: 100%; height: 230px; object-fit: cover; border-radius: 6px;' 
                                 alt='Produto'>
                        </div>
                        
                        <div class='mt-3'>
                            <h6 class='fw-normal text-truncate mb-1'>" . htmlspecialchars($produto['descricaoProduto']) . "</h6>
                        </div>
                        
                        <div class='mt-auto'>
                            <p class='fw-bold text-dark fs-5 mb-0'>
                                R$ " . number_format($produto['precoProduto'], 2, ',', '.') . "
                            </p>
                        </div>
                        <div class='mt-3'>
                            <h6 class='fw-normal text-truncate mb-1'> Q: " . number_format($produto['quantidadeCarrinho']) . "</h6>
                        </div>
                    </div>
                </a>
                <hr class='my-4'>
                ";
            }
        } else {
            echo "<div class='alert alert-warning w-100'>Nenhum Produto Adicionado ao Carrinho.</div>";
        }
        mysqli_close($conn);
    ?>