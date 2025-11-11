<?php
include "PHP/conexao.php";
include "header.php";
include "PHP/Login.php";
include "PHP/protect.php";
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kaztor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous" />
 
    <link rel="stylesheet" href="../css/style.css"> <style>

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
      background-color: #403232;
      border: none;
    }

    .btn-checkout:hover {
      background-color: #6c5555ff;
    }
  </style>
</head>
<body>



<div class="container cart-container">
  <div class="row">
    <div class="col-lg-8">
      <h4 class="mb-4">Meu Carrinho</h4>

      <?php
$sql = "SELECT l.*, c.*, p.* FROM logincliente l 
inner join carrinho c on l.LoginClienteID = c.loginclienteID 
inner join produto p on c.produtoID = p.ProdutoID 
where c.loginclienteID = " . $_SESSION['LoginClienteID'] . ";"; 

$resultado = mysqli_query($conn, $sql); 
$totalItens = 0;
$valorTotal = 0.0;

if (mysqli_num_rows($resultado) > 0) 
  { while ($produto = mysqli_fetch_assoc($resultado)) 
    { $imagem = !empty($produto['ImagemProduto']) ? "./" . htmlspecialchars($produto['ImagemProduto']) : "https://via.placeholder.com/300x200?text=Sem+Imagem";
  $verTamanho = "SELECT * FROM tamanho t INNER JOIN carrinho c on t.tamanhoID = c.tamanhoID
  WHERE c.produtoID = " . $produto['ProdutoID'] . " AND c.loginclienteID = " . $_SESSION['LoginClienteID'] . " AND t.tamanhoID = " . $produto['tamanhoID'];
  $resTamanho = mysqli_query($conn, $verTamanho);
  $tamanho = mysqli_fetch_assoc($resTamanho);
   $totalItens += $produto['quantidadeCarrinho'];
            $valorTotal += $produto['quantidadeCarrinho'] * $produto['precoProduto'];
            echo '
              <div class="cart-item d-flex align-items-center">
                  <img src="' . $imagem . '" class="photo-thumbnail me-3" alt="Foto do produto">
                  <div class="flex-grow-1">
                      <div><strong>' . htmlspecialchars($produto['descricaoProduto']) . '</strong></div>
                      <div class="text-muted small">Tamanho Escolhido: ' . htmlspecialchars($tamanho['tamanho']) . '</div>
                      <div class="text-muted small">Quantidade: ' . number_format($produto['quantidadeCarrinho']) . '</div>
                      <button class="btn btn-link btn-remove p-0 mt-1">
                <a href= "removerCarrinho.php?id=' . $produto['ProdutoID'] . '" class="text-danger text-decoration-none btn btn-link btn-remove p-0 mt-1">
                    🗑 Remover
                </a>
                        </button>

                  </div>
                  <div class="text-end">
                      <strong>R$ ' . number_format($produto['precoProduto'], 2, ',', '.') . '</strong>
                  </div>
              </div>';
          }
        } else {
          echo '<div class="alert alert-warning w-100">Nenhum Produto Adicionado ao Carrinho.</div>';
        }
        

    ?>
    </div>
    
          <div class="col-lg-4 ">
      <div class="cart-summary">
        <h5>Resumo do Carrinho</h5>
        <div class="d-flex justify-content-between mt-3">
          <span>Itens (<?php echo $totalItens; ?>)</span>
          <span>R$ <?php echo number_format($valorTotal, 2, ',', '.'); ?></span>
        </div>

        <div class="d-flex justify-content-between mt-2 mb-3">
          <span>Desconto</span>
          <span>- R$ 0,00</span>
        </div>

        <div class="d-flex justify-content-between cart-total border-top pt-3">
          <span>Total a pagar</span>
          <span>R$ <?php echo number_format($valorTotal, 2, ',', '.'); ?></span>
        </div>
<a href='finalizarCompra.php' 
   class='btn btn-checkout w-100 mt-4 text-white py-2 d-block text-center'>
  Continuar compra →
</a>
      </div>
    </div>
  </div>
</div>

        </body>
        </html>