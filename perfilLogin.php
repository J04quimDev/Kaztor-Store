<?php
include "PHP/Login.php";  

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
        if(isset($_SESSION['LoginClienteID'])){
            header("Location: /TCCphpJoca/index.php");
            exit; 
        }
    ?>
    <div class="container rounded mt-5 mb-5">
    <div class="row">
        <div class="col-md-3 border-right">
            <div class="d-flex flex-column align-items-center text-center p-3 py-5"><img class="rounded-circle mt-5" width="150px" src="img/kaztor_logo_v2.png"><span class="font-weight-bold">Bem-vindo à </span><span class="text-black-50"><h1>KAZTOR STORE</h1></span></div>
        </div>
        <div class="col-md-5 border-right">
            <div class="p-3 py-5">
                <div class="d-flex justify-content-between align-items-center mb-3">
                          </div>
            <form class="needs-validation" novalidate="" action="" name="myForm" method="POST">
                <span class="text-black-50" style="text-align:center;">
  <h1>LOG-IN</h1>
</span>
                <div class="row mt-2">
                    <div class="col-md-12">
                        <label class="labels" for="nomeCliente">Nome</label>
                        <input type="text" class="form-control" name="nomeCliente" placeholder="Nome do Usuário" >
                    </div>
                </div>


                <div class="row mt-3">
                    <div class="col-md-12">
                        <label class="labels" for="emailCliente">Email</label>
                        <input type="text" class="form-control" name="emailCliente" placeholder="Email" >
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-12">
                        <label class="labels" for="senhaCliente">Senha</label>
                        <input type="password" class="form-control" name="senhaCliente" placeholder="Senha" >
                    </div>
                </div>
                <hr class="my-4">
                    <input class="w-100 btn btn-success btn-lg" type="submit" value="Entrar" style= "background: #403232; border:none;" name="Entrar"></input>
            </form>
            <h7 style="color:#D9D9D9;">a</h7>
 <p style="text-align: center;">
  Não tem uma conta?
  <a href="perfil.php" style="text-decoration: none;color:#403232;font-weight: bold;">
   Cadastre-se
  </a>
</body>
</html>