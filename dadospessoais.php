<?php
include "PHP/conexao.php"; 

if(session_status() === PHP_SESSION_NONE){
    session_start();
}

$iduser = $_SESSION['LoginClienteID'] ?? null;

if (!$iduser) {
    die("Usuário não logado.");
}

$sqlUser = "SELECT * FROM logincliente WHERE LoginClienteID = $iduser;";
$resultadoUser = mysqli_query($conn, $sqlUser);
if (mysqli_num_rows($resultadoUser) > 0) {
    $user = mysqli_fetch_assoc($resultadoUser);
    $nomeusuario = $user['nomeCliente'];
    $senhausuario = $user['senhaCliente'];
    $emailusuario = $user['emailCliente'];
    $cpf = $user['CPFCliente']?? '';
    $telefone = $user['telefoneCliente'] ?? '';
    $nascimento = $user['dataNascimentoCliente'] ?? '';
} else {
    die("Usuário não encontrado.");
}

$sqlEnd = "SELECT e.*, lc.*  FROM endereco e INNER JOIN logincliente lc 
ON lc.enderecoID = e.EnderecoID
WHERE lc.LoginClienteID = $iduser;";
$resultadoEnd = mysqli_query($conn, $sqlEnd);
$endereco = mysqli_fetch_assoc($resultadoEnd);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dados Pessoais</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .form-control {
            background: #d2d1d1; 
            border: none; 
            border-bottom: 1px solid #403232;
        }
        .endereco-card {
            background: #f4f4f4; 
            border-radius: 10px; 
            padding: 15px;
            margin-top: 15px;
        }
        #formNovoEndereco {
            display: none;
        }
        .password-toggle-icon {
            position: absolute;
            top: 50%;
            right: 15px;
            transform: translateY(-15%);
            cursor: pointer;
            color: #495057;
        }
    </style>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
<?php include "header.php"; ?>    

<div class="container rounded mt-5 mb-5">
    <div class="row">
        <div class="col-md-3 border-right">
            <div class="d-flex flex-column align-items-center text-center p-3 py-5">
                <img class="rounded-circle mt-5" width="150px" src="img/kaztor_logo_v2.png">
                <span class="font-weight-bold">Bem-vindo à</span>
                <h1 class="text-black-50">KAZTOR STORE</h1>
            </div>
        </div>

        <div class="col-md-8 border-right">
            <div class="p-3 py-5">
                <form action="PHP/updateCliente.php" method="POST">
                    <h4 class="mb-3">Dados do Usuário</h4>
                    <div class="mb-3">
                        <label class="labels">Nome</label>
                        <input type="text" class="form-control" name="nomeCliente" value="<?= htmlspecialchars($nomeusuario) ?>">
                    </div>
                    <div class="mb-3">
                        <label class="labels">Email</label>
                        <input type="email" class="form-control" name="emailCliente" value="<?= htmlspecialchars($emailusuario) ?>">
                    </div>
                    <div class="mb-3 position-relative">
                        <label class="labels">Senha</label>
                        <input type="password" id="senhaInput" class="form-control pe-5" name="senhaCliente" value="<?= htmlspecialchars($senhausuario) ?>">
                        <i class="bi bi-eye-slash password-toggle-icon" id="togglePassword"></i>
                    </div>

                    <hr class="my-4">
                    <h4 class="mb-3">Dados Pessoais</h4>
                     <div class="mb-3">
                         <label class="labels">CPF</label>
                         <input type="text" class="form-control" name="CPFCliente" id="cpf" value="<?= htmlspecialchars($cpf) ?>" placeholder="000.000.000-00" maxlength="14">
                     </div>
                     <div class="mb-3">
                         <label class="labels">Telefone</label>
                         <input type="text" class="form-control" name="telefoneCliente" id="telefone" value="<?= htmlspecialchars($telefone) ?>" placeholder="(xx) xxxxx-xxxx" maxlength="15">
                     </div>
                     <div class="mb-3">
                         <label class="labels">Data de Nascimento</label>
                         <input type="date" class="form-control" name="dataNascimentoCliente" value="<?= htmlspecialchars($nascimento) ?>">
                     </div>
                    <button class="btn btn-dark w-100 mt-4" type="submit">Salvar Dados</button>
                </form>

                <hr class="my-5">

                <h4>Endereço</h4>
                <?php if ($endereco): ?>
                    <div class="endereco-card">
                        <p><strong>Estado:</strong> <?= htmlspecialchars($endereco['estadoEndereco']) ?></p>
                        <p><strong>Cidade:</strong> <?= htmlspecialchars($endereco['cidadeEndereco']) ?></p>
                        <p><strong>Bairro:</strong> <?= htmlspecialchars($endereco['bairroEndereco']) ?></p>
                        <p><strong>Rua:</strong> <?= htmlspecialchars($endereco['ruaEndereco']) ?></p>
                        <p><strong>Número:</strong> <?= htmlspecialchars($endereco['numeroEndereco']) ?></p>
                        <p><strong>Complemento:</strong> <?= htmlspecialchars($endereco['complementoEndereco']) ?></p>
                    </div>
                <?php else: ?>
                    <button id="btnNovoEndereco" class="btn btn-dark">Cadastrar Endereço</button>🐒
                <?php endif; ?>
                <form id="formNovoEndereco" class="mt-4" action="CadastroEndereco.php" method="POST"> 
                    <h5>Novo Endereço</h5>
                    <input type="hidden" name="enderecoID" value="<?= $endereco['enderecoID'] ?? '' ?>">
                    <div class="mb-3">
                        <label>Estado</label>
                        <input type="text" name="estadoEndereco" class="form-control" value="<?= htmlspecialchars($endereco['estadoEndereco'] ?? '') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label>Cidade</label>
                        <input type="text" name="cidadeEndereco" class="form-control" value="<?= htmlspecialchars($endereco['cidadeEndereco'] ?? '') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label>Bairro</label>
                        <input type="text" name="bairroEndereco" class="form-control" value="<?= htmlspecialchars($endereco['bairroEndereco'] ?? '') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label>Rua</label>
                        <input type="text" name="ruaEndereco" class="form-control" value="<?= htmlspecialchars($endereco['ruaEndereco'] ?? '') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label>Número</label>
                        <input type="number" name="numeroEndereco" class="form-control" value="<?= htmlspecialchars($endereco['numeroEndereco'] ?? '') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label>Complemento</label>
                        <input type="text" name="complementoEndereco" class="form-control" value="<?= htmlspecialchars($endereco['complementoEndereco'] ?? '') ?>">
                    </div>
                    <input type="hidden" name="loginClienteFK" value="<?= $iduser ?>">
                    <button type="submit" class="btn btn-dark btn-success w-100">Salvar Endereço</button>
                </form>

            </div>
        </div>
    </div>
</div>

<script>
const btnNovoEndereco = document.getElementById('btnNovoEndereco');
if (btnNovoEndereco) {
    btnNovoEndereco.addEventListener('click', function() {
        const form = document.getElementById('formNovoEndereco');
        form.style.display = form.style.display === 'none' || form.style.display === '' ? 'block' : 'none';
    });
}

const cpfInput = document.getElementById('cpf');
const telefoneInput = document.getElementById('telefone');

cpfInput.addEventListener('input', (e) => {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length > 11) {
        value = value.slice(0, 11);
    }
    value = value.replace(/(\d{3})(\d)/, '$1.$2');
    value = value.replace(/(\d{3})(\d)/, '$1.$2');
    value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
    e.target.value = value;
});

telefoneInput.addEventListener('input', (e) => {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length > 11) {
        value = value.slice(0, 11);
    }
    if (value.length > 2) {
        value = `(${value.substring(0, 2)}) ${value.substring(2)}`;
    }
    if (value.length > 9) {
        value = `${value.substring(0, 10)}-${value.substring(10)}`;
    }
    e.target.value = value;
});

document.getElementById('togglePassword').addEventListener('click', function () {
    const passwordInput = document.getElementById('senhaInput');
    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordInput.setAttribute('type', type);
    
    if (type === 'password') {
        this.classList.remove('bi-eye');
        this.classList.add('bi-eye-slash');
    } else {
        this.classList.remove('bi-eye-slash');
        this.classList.add('bi-eye');
    }
});
</script>

</body>
</html>
