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

    $nomeProduto = $_POST["descricaoProduto"];
    $categoriaProduto = $_POST["categoriaProduto"];
    $catProduto = substr($categoriaProduto[0], 0, 1);
    $tamanhoProduto = $_POST["tamanhoProduto"];
    $corProduto  = $_POST['corProduto'];
    $quantidadeProduto = $_POST['quantidadeProduto'];
    $precoProduto = $_POST['precoProduto'];
    $imagem = "";

    if(isset($_FILES["imagem"] ) && !empty($_FILES["imagem"]))
    {
        $imagem ="./img/" .  $_FILES["imagem"]["name"];
        move_uploaded_file($_FILES["imagem"]["tmp_name"], $imagem);
    }else{
        $imagem = "";
    }

    $sql = "INSERT INTO produto
    (descricaoProduto, corProduto, tamanhoProduto, precoProduto, quantidadeEstoqueProduto, ImagemProduto , CategoriaID) 
    VALUES 
    ('$nomeProduto','$corProduto','$tamanhoProduto','$precoProduto','$quantidadeProduto','$imagem',$catProduto)";
    if (mysqli_query($conn,$sql)){
        echo "<h1>Produto cadastrado</h1><hr class='my-4'> <button class='w-100 btn btn-success btn-lg' type='submit'><a href='../index.php'>Voltar à Lista</a></button>";
        header("Location: ../index.php");
        exit;
    }
    else{ 
        echo "Produto não cadastrado";
    }
  ?>
    </div>
</div>
</body>
</html>