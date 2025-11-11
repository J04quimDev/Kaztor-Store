<?php
include "../header.php";
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Carrinho - Fotto</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
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
      background-color: #e60023;
      border: none;
    }

    .btn-checkout:hover {
      background-color: #c2001d;
    }
  </style>
</head>
<body>

<div class="container cart-container">
  <div class="row">
    <!-- Coluna da lista de fotos -->
    <div class="col-lg-8">
      <h4 class="mb-4">Meu Carrinho (4 itens)</h4>

      <!-- Item do carrinho -->
      <div class="cart-item d-flex align-items-center">
        <img src="https://via.placeholder.com/80" class="photo-thumbnail me-3" alt="Foto">
        <div class="flex-grow-1">
          <div><strong>1º Território Race</strong></div>
          <div class="text-muted small">Código da foto: 901027</div>
          <button class="btn btn-link btn-remove p-0 mt-1">🗑 Remover</button>
        </div>
        <div class="text-end"><strong>R$ 12,00</strong></div>
      </div>

      <!-- Repetir para cada foto -->
      <div class="cart-item d-flex align-items-center">
        <img src="https://via.placeholder.com/80" class="photo-thumbnail me-3" alt="Foto">
        <div class="flex-grow-1">
          <div><strong>1º Território Race</strong></div>
          <div class="text-muted small">Código da foto: 901052</div>
          <button class="btn btn-link btn-remove p-0 mt-1">🗑 Remover</button>
        </div>
        <div class="text-end"><strong>R$ 12,00</strong></div>
      </div>

      <!-- Adicione mais itens conforme necessário... -->

    </div>

    <!-- Coluna do resumo -->
    <div class="col-lg-4">
      <div class="cart-summary">
        <h5>Resumo do Carrinho</h5>
        <div class="d-flex justify-content-between mt-3">
          <span>Itens (4)</span>
          <span>R$ 48,00</span>
        </div>
        <div class="d-flex justify-content-between mt-2 mb-3">
          <span>Desconto</span>
          <span>-</span>
        </div>
        <div class="d-flex justify-content-between cart-total border-top pt-3">
          <span>Total a pagar</span>
          <span>R$ 48,00</span>
        </div>
        <button class="btn btn-checkout w-100 mt-4 text-white py-2">Continuar compra →</button>
      </div>
    </div>
  </div>
</div>

</body>
</html>
