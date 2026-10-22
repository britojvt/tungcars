<?php
require_once 'conexao.php';

$erro = '';
$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: veiculos_listar.php");
    exit;
}

// Busca o registro existente
$stmt = $pdo->prepare("SELECT * FROM tb_veiculo WHERE id_veiculo = :id");
$stmt->execute([':id' => $id]);
$veiculo = $stmt->fetch();

if (!$veiculo) {
    die("<div class='container'><div class='alert alert-erro'>Registro não encontrado!</div><a href='veiculos_listar.php' class='btn'>Voltar</a></div>");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $placa = trim($_POST['placa'] ?? '');
    $modelo = trim($_POST['modelo'] ?? '');
    $marca = trim($_POST['marca'] ?? '');
    $valor_diaria = $_POST['valor_diaria'] ?? '';

    if (empty($placa) || empty($modelo) || empty($marca) || empty($valor_diaria)) {
        $erro = "Todos os campos são obrigatórios.";
    } elseif (!is_numeric($valor_diaria) || $valor_diaria <= 0) {
        $erro = "O valor da diária deve ser um número positivo maior que zero.";
    } else {
        try {
            $stmt = $pdo->prepare("UPDATE tb_veiculo SET placa = :placa, modelo = :modelo, marca = :marca, valor_diaria = :valor_diaria WHERE id_veiculo = :id");
            $stmt->execute([
                ':placa' => $placa,
                ':modelo' => $modelo,
                ':marca' => $marca,
                ':valor_diaria' => $valor_diaria,
                ':id' => $id
            ]);
            header("Location: veiculos_listar.php?msg=editado");
            exit;
        } catch (PDOException $e) {
            $erro = "Erro ao atualizar: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>TungCars - Editar Veículo</title>
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
        <h2>Editar Veículo</h2>

        <?php if ($erro): ?>
            <div class="alert alert-erro"><?= $erro ?></div>
        <?php endif; ?>

        <form method="POST" action="veiculos_editar.php?id=<?= $id ?>">
            <div class="form-group">
                <label for="placa">Placa:</label>
                <input type="text" id="placa" name="placa" value="<?= htmlspecialchars($veiculo['placa']) ?>" maxlength="7" required>
            </div>
            <div class="form-group">
                <label for="modelo">Modelo:</label>
                <input type="text" id="modelo" name="modelo" value="<?= htmlspecialchars($veiculo['modelo']) ?>" required>
            </div>
            <div class="form-group">
                <label for="marca">Marca:</label>
                <input type="text" id="marca" name="marca" value="<?= htmlspecialchars($veiculo['marca']) ?>" required>
            </div>
            <div class="form-group">
                <label for="valor_diaria">Valor da Diária (R$):</label>
                <input type="number" step="0.01" id="valor_diaria" name="valor_diaria" value="<?= $veiculo['valor_diaria'] ?>" required>
            </div>
            <button type="submit" class="btn">Atualizar</button>
            <a href="veiculos_listar.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</body>
</html>