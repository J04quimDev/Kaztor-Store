<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div>
        <header class="p-2 pl-0 text-bg-dark">
            <div class="container-fluid" style="margin-left: 0; margin-right: 0; width: 100%;">
                <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start" style="width: 100%;">
                    <a href="/TCCphpJoca/index.php"
                        class="d-flex align-items-center mx-3 mb-2 mb-lg-0 text-white text-decoration-none">
                        <img src="/TCCphpJoca/img/kaztor_logo_v2.png" alt="" height="30px" style="border-radius: 5px" />
                    </a>
                    <ul class='nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0'>
                        <li><a href='/TCCphpJoca/PHP/listaProdutos.php' class='nav-link px-3 text-light'>Catálogo</a></li>
                         <li><a href='/TCCphpJoca/pedido.php' class='nav-link px-3 text-white'>Meus pedidos</a></li>
                    <?php
                        include "PHP/Login.php";
                    if(isset($_SESSION['LoginClienteID'])) {
                        $sql = "SELECT * FROM logincliente l 
                            LEFT JOIN carrinho c ON l.LoginClienteID = c.loginclienteID 
                            LEFT JOIN produto p ON c.produtoID = p.ProdutoID 
                            WHERE l.LoginClienteID = " . $_SESSION['LoginClienteID'];
                            $resultado = mysqli_query($conn, $sql); 
                            $tipoUser = mysqli_fetch_assoc($resultado);

                            if($tipoUser['tipoID'] == 2){
                            echo
                            "<li><a href='/TCCphpJoca/dashboard.php' class='nav-link px-3 text-white'>DashBoard</a></li>
                            <li><a href='/TCCphpJoca/cadastroCategoria.php' class='nav-link px-3 text-white'>Categoria</a></li>";
                        
                        }else {
                            echo 
                            "";
                        }
                    }
                    ?>
                    </ul>
                    <form action="/TCCphpJoca/PHP/listaProdutos.php" method="GET" class="col-12 col-lg-auto mb-3 mb-lg-0 me-lg-3" role="search">
                        <input type="search" name="q" class="form-control form-control-dark text-bg-dark" 
                               placeholder="Pesquisar produtos..." aria-label="Search" style="height: 31px;" value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
                    </form>

                    <div class="text-end pr-0">
                        <a href="/TCCphpJoca/carrinho.php" style='text-decoration:none;'>
                            <img src="/TCCphpJoca/img/carrinho.png" alt="" style="height: 30px; padding-right: 2px; "> 
                        </a>
                        <?php
                        include "PHP/Login.php";
                        if(isset($_SESSION['LoginClienteID'])) {
                            echo
                            "<a href=\"/TCCphpJoca/dadospessoais.php\" style='text-decoration:none;'>                                
                                <img src=\"/TCCphpJoca/img/iconedeperfil.png\" alt=\"\" height=\"30px\" width=\"30px\">                                
                            </a>
                            
                            <a href=\"/TCCphpJoca/logout.php\" style='text-decoration:none;'>
                                <button type=\"button\" class=\"btn btn-danger btn-sm\">Logout</button>
                            </a>";
                            
                        }else {
                            echo "<a href=\"/TCCphpJoca/perfilLogin.php\" style='text-decoration:none;'>
                                <button type=\"button\" class=\"btn btn-outline-light me-2 btn-sm\">
                                    Login
                                </button>
                            </a>
                            <a href=\"/TCCphpJoca/perfil.php\" style='text-decoration:none;'>
                                <button type=\"button\" class=\"btn btn-warning btn-sm\">Sign-up</button>
                            </a>";
                        }
                        
                        ?>
                    </div>
                </div>
            </div>
        </header>
    </div>
</body>
</html>