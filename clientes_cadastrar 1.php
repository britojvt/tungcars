<?php
require_once 'conexao.php';

$mensagem = '';
$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $cpf = trim($_POST['cpf'] ?? '');
    $cnh = trim($_POST['cnh'] ?? '');
    $telefone = trim($_POST['telefone'] ?? '');

    // Limpa pontuações para validação de tamanho
    $cpf_limpo = preg_replace('/[^0-9]/', '', $cpf);
    $cnh_limpo = preg_replace('/[^0-9]/', '', $cnh);

    // Validações no PHP
    if (empty($nome) || empty($cpf) || empty($cnh) || empty($telefone)) {
        $erro = "Todos os campos são obrigatórios.";
    } elseif (strlen($cpf_limpo) !== 11) {
        $erro = "O CPF deve conter exatamente 11 dígitos numéricos.";
    } elseif (strlen($cnh_limpo) !== 11) {
        $erro = "A CNH deve conter exatamente 11 dígitos numéricos.";
    } else {
        try {
            // Insere o cliente no banco de dados usando PDO
            $stmt = $pdo->prepare("INSERT INTO tb_cliente (nome, cpf, cnh, telefone) VALUES (:nome, :cpf, :cnh, :telefone)");
            $stmt->execute([
                ':nome' => $nome,
                ':cpf' => $cpf_limpo,
                ':cnh' => $cnh_limpo,
                ':telefone' => $telefone
            ]);
            $mensagem = "Cliente cadastrado com sucesso!";
        } catch (PDOException $e) {
            // Trata duplicidade de CPF ou CNH
            if ($e->getCode() == 23000) {
                $erro = "Já existe um cliente cadastrado com este CPF ou CNH.";
            } else {
                $erro = "Erro ao cadastrar cliente: " . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>TungCars - Cadastrar Cliente</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
    <header class="header-index">
    <nav class="nave">
        <a href="index.php">Início</a>
        <a href="veiculos_listar.php">Listar Veículos</a>
        <a href="veiculos_cadastrar.php">Cadastrar Veículo</a>
        <a href="clientes_cadastrar.php">Cadastrar Cliente</a>
        <a href="locacoes_cadastrar.php">Nova Locação</a>
    </nav>
</header>

    <div class="container-cadc">
        <h2>Cadastrar Novo Cliente</h2>

        <?php if ($mensagem): ?>
            <div class="alert alert-sucesso-cadc"><?= $mensagem ?></div>
        <?php endif; ?>

        <?php if ($erro): ?>
            <div class="alert alert-erro-cadc"><?= $erro ?></div>
        <?php endif; ?>

        <form method="POST" action="clientes_cadastrar.php">
            <div class="form-group-cadc">
                <label for="nome">Nome Completo:</label>
                <input type="text" id="nome" name="nome" required placeholder="Ex: João da Silva">
            </div>

            <div class="form-group-cadc">
                <label for="cpf">CPF (somente números):</label>
                <input type="text" id="cpf" name="cpf" maxlength="14" required placeholder="Ex: 12345678901">
            </div>

            <div class="form-group-cadc">
                <label for="cnh">CNH (somente números):</label>
                <input type="text" id="cnh" name="cnh" maxlength="11" required placeholder="Ex: 12345678901">
            </div>

            <div class="form-group-cadc">
                <label for="telefone">Telefone:</label>
                <input type="text" id="telefone" name="telefone" required placeholder="Ex: (19) 99999-9999">
            </div>

            <button type="submit" class="btn-cadc">Salvar Cliente</button>
            <a href="veiculos_listar.php" class="btn btn-secondary-cadc">Voltar</a>
        </form>
    </div>
</body>
</html>