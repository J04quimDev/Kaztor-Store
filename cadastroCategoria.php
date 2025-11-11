<?php
include "PHP/conexao.php";
include "PHP/protect.php";  
include "protectadmin.php";

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/perfil.css">
 
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous" />
    <link rel="stylesheet" href="css/style.css">
    
</head>
<body>
    <?php 
        include "header.php"; 
    ?>
    <div class="container rounded mt-5 mb-5">
    <div class="row">
        <div class="col-md-3 border-right">
            <div class="d-flex flex-column align-items-center text-center p-3 py-5"><img class="rounded-circle mt-5" width="150px" src="img/kaztor_logo_v2.png"><span class="font-weight-bold">Bem-vindo à </span><span class="text-black-50"><h1>KAZTOR STORE</h1></span><span> </span></div>
        </div>
        <div class="col-md-5 border-right">
            <div class="p-3 py-5">
                <div class="d-flex justify-content-between align-items-center mb-3">
                          </div>
            <form class="needs-validation" novalidate="" action="PHP/cadastroCAT.php" name="myForm" method="POST">
                <div class="row mt-2">
                    <div class="col-md-12">
                        <label class="labels" for="nomeCliente">Nome</label>
                        <input type="text" class="form-control" name="nomeCat" placeholder="Nome da Categoria">
                    </div>
                </div>

                <hr class="my-4">
                <input class="w-100 btn btn-success btn-lg" type="submit"></input>
            </form>
</body>
</html>