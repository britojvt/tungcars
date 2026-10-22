<?php
require_once 'conexao.php';

$mensagem = '';
$erro = '';

// Buscar clientes para preencher o <select>
$stmtClientes = $pdo->query("SELECT id_cliente, nome, cpf FROM tb_cliente ORDER BY nome");
$clientes = $stmtClientes->fetchAll();

// Buscar veículos para preencher o <select>
$stmtVeiculos = $pdo->query("SELECT id_veiculo, modelo, marca, placa FROM tb_veiculo ORDER BY marca, modelo");
$veiculos = $stmtVeiculos->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_cliente = $_POST['id_cliente'] ?? '';
    $id_veiculo = $_POST['id_veiculo'] ?? '';
    $data_inicio = $_POST['data_inicio'] ?? '';
    $data_fim = $_POST['data_fim'] ?? '';

    // Validação
    if (empty($id_cliente) || empty($id_veiculo) || empty($data_inicio) || empty($data_fim)) {
        $erro = "Todos os campos são obrigatórios.";
    } elseif ($data_fim < $data_inicio) {
        $erro = "A data final deve ser posterior ou igual à data de início.";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO tb_locacao (id_cliente, id_veiculo, data_inicio, data_fim) VALUES (:id_cliente, :id_veiculo, :data_inicio, :data_fim)");
            $stmt->execute([
                ':id_cliente' => $id_cliente,
                ':id_veiculo' => $id_veiculo,
                ':data_inicio' => $data_inicio,
                ':data_fim' => $data_fim
            ]);
            $mensagem = "Locação registrada com sucesso!";
        } catch (PDOException $e) {
            $erro = "Erro ao registrar locação: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>TungCars - Nova Locação</title>
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

    <div class="container">
        <h2>Cadastrar Nova Locação</h2>

        <?php if ($mensagem): ?>
            <div class="alert alert-sucesso"><?= $mensagem ?></div>
        <?php endif; ?>

        <?php if ($erro): ?>
            <div class="alert alert-erro"><?= $erro ?></div>
        <?php endif; ?>

        <form method="POST" action="locacoes_cadastrar.php">
            <div class="form-group">
                <label for="id_cliente">Cliente:</label>
                <select name="id_cliente" id="id_cliente" required>
                    <option value="">-- Selecione o Cliente --</option>
                    <?php foreach ($clientes as $c): ?>
                        <option value="<?= $c['id_cliente'] ?>">
                            <?= htmlspecialchars($c['nome']) ?> (CPF: <?= htmlspecialchars($c['cpf']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="id_veiculo">Veículo:</label>
                <select name="id_veiculo" id="id_veiculo" required>
                    <option value="">-- Selecione o Veículo --</option>
                    <?php foreach ($veiculos as $v): ?>
                        <option value="<?= $v['id_veiculo'] ?>">
                            <?= htmlspecialchars($v['marca']) ?> <?= htmlspecialchars($v['modelo']) ?> (Placa: <?= htmlspecialchars($v['placa']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="data_inicio">Data de Início:</label>
                <input type="date" id="data_inicio" name="data_inicio" required>
            </div>

            <div class="form-group">
                <label for="data_fim">Data de Fim:</label>
                <input type="date" id="data_fim" name="data_fim" required>
            </div>

            <button type="submit" class="btn">Registrar Locação</button>
        </form>
    </div>
</body>
</html>