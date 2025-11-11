<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous" />
    <link rel="stylesheet" href="style.css">
</head>

<body>
<div class="container">
  <div class="row">

  <?php

  include "conexao.php";

    $nomeProduto = $_POST['nomeProduto'];
    $categoriaProduto = $_POST['categoriaProduto'];
    $tamanhoProduto = $_POST['tamanhoProduto'];
    $corProduto  = $_POST['corProduto'];
    $quantidadeProduto = $_POST['quantidadeProduto'];
    $precoProduto = $_POST['precoProduto'];

    $sql =" INSERT INTO produto
    (descricaoProduto, corProduto, tamanhoProduto, precoProduto, quantidadeEstoqueProduto, CategoriaIDFK) 
    VALUES 
    ('$nomeProduto','$corProduto','$tamanhoProduto','$precoProduto','$quantidadeProduto','$categoriaProduto')";

    if (mysqli_query($conn,$sql)){
        echo "Produto cadastrado";
    }
    else{
        echo "Produto não cadastrado"
    }


  ?>
     
    </div>
</div>

</body>

</html>
