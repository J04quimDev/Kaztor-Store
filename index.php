<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kaztor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous" />
 
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php 
        include "header.php"; 
    ?>

    <section>
        <div id="carouselExample" class="carousel slide carrossel " >
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="img/carrossel1.jpg" class="d-block w-100" alt="...">
                </div>
                <div class="carousel-item">
                    <img src="img/carrossel2.jpg" class="d-block w-100" alt="...">
                </div>
                <div class="carousel-item">
                    <img src="img/carrossel3.jpg" class="d-block w-100" alt="...">
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </section>


<section class="py-5">
  <div class="container-fluid px-4">
    <div class="d-flex flex-column flex-md-row gap-4 align-items-start">
      <div class="feature" style="width: 300px;">
        <div class="d-flex align-items-center gap-2 mb-2">
          <h3 class="fs-2 text-body-emphasis m-0">KAZTOR</h3>
          <p class="m-0 small text-muted">A loja do Futuro</p>
        </div>
        <a href="/TCCphpJoca/PHP/listaProdutos.php" style="text-decoration: none; color: inherit">
          <div class="botaocompra d-flex align-items-center gap-2 border rounded p-2 mt-2">
            Ver Catálogo Completo
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
              class="bi bi-arrow-right" viewBox="0 0 16 16">
              <path fill-rule="evenodd"
                d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8" />
            </svg>
          </div>
        </a>
      </div>

<div class="flex-grow-1">
    <?php
    include "PHP/conexao.php";

    $categorias_result = mysqli_query($conn, "SELECT * FROM categoria ORDER BY NomeCategoria");

    if (mysqli_num_rows($categorias_result) > 0) {
        while ($categoria = mysqli_fetch_assoc($categorias_result)) {
            $categoriaID = $categoria['CategoriaID'];
            $nomeCategoria = htmlspecialchars($categoria['NomeCategoria']);

            $sql_count = "SELECT COUNT(*) as total FROM produto WHERE CategoriaID = $categoriaID";
            $count_result = mysqli_query($conn, $sql_count);
            $total_produtos = mysqli_fetch_assoc($count_result)['total'];

            $sql_produtos = "SELECT * FROM produto WHERE CategoriaID = $categoriaID ORDER BY descricaoProduto ASC LIMIT 5";
            $produtos_result = mysqli_query($conn, $sql_produtos);

            if (mysqli_num_rows($produtos_result) > 0) {
                echo "<h2 class='mb-3 fw-bold' style='font-family: arial; font-weight: 600'>{$nomeCategoria}</h2>";
                echo "<div class='d-flex flex-wrap justify-content-start gap-4 pb-4 mb-3'>"; 
                while ($produto = mysqli_fetch_assoc($produtos_result)) {
                    $imagem = !empty($produto['ImagemProduto'])
                        ? "./" . htmlspecialchars($produto['ImagemProduto'])
                        : "https://via.placeholder.com/300x200?text=Sem+Imagem";

                    echo "
                    <a href='detalhesProduto.php?id=" . $produto['ProdutoID'] . "' class='text-decoration-none text-dark'>
                        <div class='produto-card d-flex flex-column' 
                            style='width: 16rem; min-width: 16rem; height: 400px; 
                                   background: #c3bfbf; border-radius: 8px; 
                                   overflow: hidden; padding: 12px; 
                                   transition: 0.2s ease;'>
                            
                            <div class='img-container' style='flex: 1; display: flex; align-items: center; justify-content: center;'>
                                <img src='$imagem' style='width: 100%; height: 230px; object-fit: cover; border-radius: 6px;' alt='Produto'>
                            </div>
                            
                            <div class='mt-3'>
                                <h6 class='fw-normal text-truncate mb-1'>" . htmlspecialchars($produto['descricaoProduto']) . "</h6>
                            </div>
                            
                            <div class='mt-auto'>
                                <p class='fw-bold text-dark fs-5 mb-0'>
                                    R$ " . number_format($produto['precoProduto'], 2, ',', '.') . "
                                </p>
                            </div>
                        </div>
                    </a>
                    ";
                }
                echo "</div>";

                if ($total_produtos > 4) {
                    echo "
                    <div class='text-center mb-5'>
                        <a href='/TCCphpJoca/PHP/listaProdutos.php?categoria={$categoriaID}' class='btn btn-outline-dark'>Ver mais em {$nomeCategoria}</a>
                    </div>";
                }
            }
        }
    } else {
        echo "<div class='alert alert-info w-100'>Nenhuma categoria de produto foi encontrada.</div>";
    }
    mysqli_close($conn);
    ?>
</div>

<style>
    .produto-card:hover {
        background: #ececec;
        transform: translateY(-3px);
    }
</style>


        </div>
      </div>

    </div>
  </div>
</section>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q"
        crossorigin="anonymous"></script>



    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="js/app.js"></script>
</body>
</html>