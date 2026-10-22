<?php
require_once 'conexao.php';

$mensagem = '';
if (isset($_GET['msg'])) {
    if ($_GET['msg'] == 'cadastrado') {
        $mensagem = "<div class='alert alert-sucesso'>Veículo cadastrado com sucesso!</div>";
    } elseif ($_GET['msg'] == 'editado') {
        $mensagem = "<div class='alert alert-sucesso'>Veículo atualizado com sucesso!</div>";
    }
}

// Consulta todos os veículos
$stmt = $pdo->prepare("SELECT * FROM tb_veiculo ORDER BY id_veiculo DESC");
$stmt->execute();
$veiculos = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>TungCars - Lista de Veículos</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
    <header>
    <nav class="nave">
        <a href="index.php">Início</a>
        <a href="veiculos_listar.php">Listar Veículos</a>
        <a href="veiculos_cadastrar.php">Cadastrar Veículo</a>
        <a href="clientes_cadastrar.php">Cadastrar Cliente</a>
        <a href="locacoes_cadastrar.php">Nova Locação</a>
    </nav>
</header>

    <div class="container-listarv">
        <h1>Veículos Cadastrados</h1>
        <?= $mensagem ?>
    </div>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Placa</th>
                    <th>Modelo</th>
                    <th>Marca</th>
                    <th>Diária (R$)</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($veiculos) > 0): ?>
                    <?php foreach ($veiculos as $v): ?>
                        <tr>
                            <td><?= $v['id_veiculo'] ?></td>
                            <td><?= htmlspecialchars($v['placa']) ?></td>
                            <td><?= htmlspecialchars($v['modelo']) ?></td>
                            <td><?= htmlspecialchars($v['marca']) ?></td>
                            <td>R$ <?= number_format($v['valor_diaria'], 2, ',', '.') ?></td>
                            <td>
                                <a href="veiculos_editar.php?id=<?= $v['id_veiculo'] ?>" class="btn-editar-listar">Editar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">Nenhum veículo encontrado.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    
</body>
</html>