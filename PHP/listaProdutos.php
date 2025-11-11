<?php
include "conexao.php"; 

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kaztor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous" />
 
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php include "../header.php"; ?>
    <section class="py-5">
  <div class="container-fluid px-4">
    <div class="d-flex flex-column flex-md-row gap-4 align-items-start">      
      <div class="feature" style="width: 300px;">
        <div class="d-flex align-items-center gap-2 mb-2">
          <h3 class="fs-2 text-body-emphasis m-0">KAZTOR</h3>
          <p class="m-0 small text-muted">A loja do Futuro</p> 
        </div>
        <a href="#" style="text-decoration: none; color: inherit">
          <div class="botaocompra d-flex align-items-center gap-2 border rounded p-2 mt-2">
            Comprar
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
              class="bi bi-arrow-right" viewBox="0 0 16 16">
              <path fill-rule="evenodd"
                d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8" />
            </svg>
          </div>
        </a>
      </div>

<div class="flex-grow-1">
    <h2 class="mb-4 fw-bold" style="font-family: serif;">NOSSOS PRODUTOS</h2>

    <form action="index.php" method="GET" class="mb-4 p-3 rounded" style="background-color: #c3bfbf;">
        <div class="row g-3 align-items-center">
            <div class="col-md-8">
                <label for="categoria" class="form-label fw-bold">Filtrar por Categoria</label>
                <select name="categoria" id="categoria" class="form-select">
                    <option value="">Todas</option>
                    <?php
                    $cat_result = mysqli_query($conn, "SELECT * FROM categoria ORDER BY NomeCategoria");
                    $selected_cat = $_GET['categoria'] ?? '';
                    while ($cat = mysqli_fetch_assoc($cat_result)) {
                        $selected = ($cat['CategoriaID'] == $selected_cat) ? 'selected' : '';
                        echo "<option value='{$cat['CategoriaID']}' {$selected}>" . htmlspecialchars($cat['NomeCategoria']) . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <input type="hidden" name="q" value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
                <button type="submit" class="btn btn-dark w-100 mt-2 mt-md-0">Aplicar Filtro</button>
            </div>
        </div>
    </form>

    <div class="d-flex gap-4 overflow-auto flex-nowrap pb-3" style="overflow-wrap: break-word;">
    <div class="d-flex flex-wrap justify-content-start gap-4 pb-3">

        <?php
        $sql = "SELECT * FROM produto ORDER BY descricaoProduto ASC"; 
        $search = $_GET['q'] ?? '';
        $category = $_GET['categoria'] ?? '';

        $sql = "SELECT * FROM produto WHERE 1=1";
        if (!empty($search)) {
            $sql .= " AND descricaoProduto LIKE '%" . mysqli_real_escape_string($conn, $search) . "%'";
        }
        if (!empty($category)) {
            $sql .= " AND CategoriaID = " . intval($category);
        }
        $sql .= " ORDER BY descricaoProduto ASC";

        $resultado = mysqli_query($conn, $sql);

        if (mysqli_num_rows($resultado) > 0) {
            while ($produto = mysqli_fetch_assoc($resultado)) {
                
                $imagem = !empty($produto['ImagemProduto']) 
                    ? "./" . htmlspecialchars($produto['ImagemProduto']) 
                    : "https://via.placeholder.com/300x200?text=Sem+Imagem";
                
                echo "
                <a href='/TCCphpJoca/detalhesProduto.php?id=" .  $produto['ProdutoID'] . "' class='text-decoration-none text-dark'>
                    <div class='produto-card d-flex flex-column' 
                        style='width: 16rem; min-width: 16rem; height: 400px; 
                               background: #c3bfbf; border-radius: 8px;                                overflow: hidden; padding: 12px; 
                               transition: 0.2s ease;'>
                        <div class='img-container' style='flex: 1; display: flex; align-items-center; justify-content: center;'>
                            <img src='/TCCphpJoca/$imagem' 
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
                    </div>
                </a>
                ";
            }
        } else {
            echo "<div class='alert alert-warning w-100'>Nenhum produto cadastrado ainda.</div>";
            echo "<div class='alert alert-warning w-100'>Nenhum produto encontrado com os filtros aplicados.</div>";
        }
        mysqli_close($conn);
        ?>

    </div>
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
</body>
</html>         