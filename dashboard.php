<?php
session_start();
include "PHP/conexao.php";
include "PHP/protect.php";
include "PHP/Login.php";

if (!isset($_SESSION['LoginClienteID'])) {
    header("Location: index.php");
    exit;
}

$sqlUser = "SELECT tipoID FROM logincliente WHERE LoginClienteID = ?";
$stmt = $conn->prepare($sqlUser);
$stmt->bind_param("i", $_SESSION['LoginClienteID']);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if (!$user || $user['tipoID'] != 2) {
    header("Location: index.php");
    exit;
}

if (isset($_POST['descricaoProduto']) && !empty($_POST['descricaoProduto'])) {
    $descricao = $_POST['descricaoProduto'];
    $cor = $_POST['corProduto'];
    $preco = $_POST['precoProduto'];
    $categoria = $_POST['categoriaProduto'];

    $imagem = "";
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == 0) {
        $imagemPath = "img/" . basename($_FILES["imagem"]["name"]);
        move_uploaded_file($_FILES["imagem"]["tmp_name"], $imagemPath);
        $imagem = $imagemPath;
    }

    $sql = "INSERT INTO produto (descricaoProduto, corProduto, precoProduto, ImagemProduto, CategoriaID)
            VALUES ('$descricao', '$cor', '$preco', '$imagem', '$categoria')";
    mysqli_query($conn, $sql);
    echo "<div class='alert alert-success text-center'>Produto cadastrado com sucesso!</div>";
}

if (isset($_POST['produtoID']) && isset($_POST['tamanho']) && isset($_POST['quantidade'])) {
    $produtoID = $_POST['produtoID'];
    $tamanho = $_POST['tamanho'];
    $quantidade = $_POST['quantidade'];

    mysqli_query($conn, "INSERT INTO tamanho (tamanho, produtoFKID) VALUES ('$tamanho', $produtoID)");
    $tamanhoID = mysqli_insert_id($conn);
    mysqli_query($conn, "INSERT INTO quantidade (quantidade, tamanhoFKID) VALUES ($quantidade, $tamanhoID)");

    echo "<div class='alert alert-success text-center'>Tamanho e quantidade registrados!</div>";
}

if (isset($_POST['pedidoID']) && isset($_POST['novoStatus'])) {
    $pedidoID = $_POST['pedidoID'];
    $novoStatus = $_POST['novoStatus'];

    $update = $conn->prepare("UPDATE pedido SET statusPedido = ? WHERE PedidoID = ?");
    $update->bind_param("si", $novoStatus, $pedidoID);
    $update->execute();
    echo "<div class='alert alert-info text-center'>Status do pedido atualizado!</div>";
}

$produtos = mysqli_query($conn, "SELECT p.*, c.NomeCategoria 
                                 FROM produto p 
                                 INNER JOIN categoria c ON c.CategoriaID = p.CategoriaID
                                 ORDER BY p.ProdutoID DESC");

$pedidos = mysqli_query($conn, "
    SELECT p.*, 
           l.nomeCliente, l.emailCliente, l.telefoneCliente, 
           e.ruaEndereco, e.numeroEndereco, e.cidadeEndereco, e.estadoEndereco 
    FROM pedido p
    JOIN logincliente l ON l.LoginClienteID = p.loginclienteIDFK
    LEFT JOIN endereco e ON l.enderecoID = e.EnderecoID
    ORDER BY p.dataPedido DESC
");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Administrativo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f5f8f8; font-family: "Inter", sans-serif; }
        .sidebar {
            width: 230px;
            height: 100vh;
            background-color: #03484C;
            position: fixed;
            top: 0; left: 0;
            padding-top: 60px;
            color: #fff;
        }
        .sidebar a {
            display: block;
            color: #fff;
            padding: 15px 25px;
            text-decoration: none;
            transition: 0.3s;
        }
        .sidebar a:hover, .sidebar a.active { background-color: #04666d; }
        .content { margin-left: 250px; padding: 40px; }
        .card { border-radius: 15px; box-shadow: 0 3px 8px rgba(0,0,0,0.1); }

        .pedido-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.08);
            padding: 20px;
            margin-bottom: 20px;
        }
        .pedido-card:hover { transform: translateY(-4px); }
        .pedido-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #E4E4E4;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }
        .pedido-header h5 { color: #03484C; font-weight: 600; }
        .pedido-info p { margin: 3px 0; color: #333; font-size: 15px; }
        .btn-atualizar {
            background-color: #03484C; border: none; padding: 6px 14px;
            border-radius: 6px; color: #fff; font-weight: 500;
            transition: background 0.2s ease;
        }
        .btn-atualizar:hover { background-color: #04666d; }
        .status-select { border-radius: 6px; padding: 4px 8px; }
        .valor { font-weight: bold; color: #04666d; }
    </style>
</head>
<body>

<div class="sidebar">
     <a href="index.php"
                        class="d-flex align-items-center mx-3 mb-2 mb-lg-0 text-white text-decoration-none">
                        <img src="img/kaztor_logo_v2.png" alt="" height="50px" style="border-radius: 5px" />
                    </a>
    <a href="#" class="active" onclick="showTab('produtos')">🛒 Produtos</a>
    <a href="#" onclick="showTab('pedidos')">📦 Pedidos</a>
</div>

<div class="content">
    <div id="produtos" class="tab-content">
        <h2 class="mb-4 text-primary">Gerenciar Produtos</h2>
        <div class="row">
            <div class="col-md-5">
                <div class="card p-4 mb-4">
                    <h5 class="mb-3">Cadastrar Novo Produto</h5>
                    <form method="POST" enctype="multipart/form-data">
                        <input type="text" class="form-control mb-2" name="descricaoProduto" placeholder="Descrição" required>
                        <input type="text" class="form-control mb-2" name="corProduto" placeholder="Cor" required>
                        <input type="number" step="0.01" class="form-control mb-2" name="precoProduto" placeholder="Preço" required>
                        <select class="form-select mb-2" name="categoriaProduto" required>
                            <option value="">Categoria</option>
                            <?php
                            $cat = mysqli_query($conn, "SELECT * FROM categoria ORDER BY NomeCategoria");
                            while ($c = mysqli_fetch_assoc($cat)) {
                                echo "<option value='{$c['CategoriaID']}'>{$c['NomeCategoria']}</option>";
                            }
                            ?>
                        </select>
                        <input type="file" class="form-control mb-3" name="imagem">
                        <button class="btn btn-success w-100">Cadastrar</button>
                    </form>
                </div>
            </div>

            <div class="col-md-7">
                <div class="card p-4">
                    <h5>Adicionar Tamanho / Quantidade</h5>
                    <div class="table-responsive mt-3">
                        <table class="table table-striped">
                            <thead><tr><th>ID</th><th>Produto</th><th>Categoria</th><th>Preço</th><th>Ação</th></tr></thead>
                            <tbody>
                            <?php while ($p = mysqli_fetch_assoc($produtos)) { ?>
                                <tr>
                                    <td><?= $p['ProdutoID'] ?></td>
                                    <td><?= htmlspecialchars($p['descricaoProduto']) ?></td>
                                    <td><?= htmlspecialchars($p['NomeCategoria']) ?></td>
                                    <td>R$ <?= number_format($p['precoProduto'], 2, ',', '.') ?></td>
                                    <td>
                                        <form method="POST" class="d-flex gap-2">
                                            <input type="hidden" name="produtoID" value="<?= $p['ProdutoID'] ?>">
                                            <input type="text" name="tamanho" placeholder="Tam" class="form-control form-control-sm" style="width:70px">
                                            <input type="number" name="quantidade" placeholder="Qtd" class="form-control form-control-sm" style="width:70px">
                                            <button class="btn btn-success btn-sm">+</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="pedidos" class="tab-content" style="display:none;">
        <h2 class="mb-4 text-primary">Gerenciar Pedidos</h2>

        <?php if (mysqli_num_rows($pedidos) > 0): ?>
            <?php while ($pedido = mysqli_fetch_assoc($pedidos)): ?>
                <div class="pedido-card">
                    <div class="pedido-header">
                        <h5>Pedido #<?= $pedido['PedidoID'] ?></h5>
                        <span class="text-muted"><?= date('d/m/Y', strtotime($pedido['dataPedido'])) ?></span>
                    </div>

                    <div class="pedido-info">
                        <p><strong>Cliente:</strong> <?= htmlspecialchars($pedido['nomeCliente']) ?></p>
                        <p><strong>Email:</strong> <?= htmlspecialchars($pedido['emailCliente']) ?></p>
                        <p><strong>Telefone:</strong> <?= htmlspecialchars($pedido['telefoneCliente']) ?></p>
                             <p><strong>Endereço:</strong> <?= "{$pedido['ruaEndereco']}, {$pedido['numeroEndereco']} - {$pedido['cidadeEndereco']}/{$pedido['estadoEndereco']} " ?></p>
              <p><strong>Valor Total:</strong> <span class="valor">R$ <?= number_format($pedido['valorTotalPedido'], 2, ',', '.') ?></span></p>
                        <p><strong>Status Atual:</strong> <?= ucfirst($pedido['statusPedido']) ?></p>
                    </div>

                    <form method="POST" class="d-flex align-items-center gap-2 mt-3">
                        <input type="hidden" name="pedidoID" value="<?= $pedido['PedidoID'] ?>">
                        <select name="novoStatus" class="form-select status-select w-auto">
                            <option value="Pedido Recebido">Pedido Recebido</option>
                            <option value="Saiu para entrega">Saiu para entrega</option>
                            <option value="Entregue">Entregue</option>
                            <option value="Cancelado">Cancelado</option>
                        </select>
                        <button type="submit" class="btn-atualizar">Atualizar</button>
                    </form>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="alert alert-warning">Nenhum pedido encontrado.</div>
        <?php endif; ?>
    </div>
</div>

<script>
function showTab(tabName) {
    document.querySelectorAll('.tab-content').forEach(el => el.style.display = 'none');
    document.getElementById(tabName).style.display = 'block';
    document.querySelectorAll('.sidebar a').forEach(a => a.classList.remove('active'));
    event.target.classList.add('active');
}
</script>

</body>
</html>
