<?php
require_once 'conexao.php';

// Consulta para pegar o total de veículos disponíveis e o total de locações no sistema
$totalVeiculos = $pdo->query("SELECT COUNT(*) FROM tb_veiculo")->fetchColumn();
$totalClientes = $pdo->query("SELECT COUNT(*) FROM tb_cliente")->fetchColumn();
$totalLocacoes = $pdo->query("SELECT COUNT(*) FROM tb_locacao")->fetchColumn();

// Busca 3 veículos em destaque para a vitrine
$stmtDestaques = $pdo->query("SELECT * FROM tb_veiculo ORDER BY id_veiculo DESC LIMIT 3");
$destaques = $stmtDestaques->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TungCars - Sua Locadora de Confiança</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body class="index-body">
    <header>
    <nav class="nave">
        <a href="index.php">Início</a>
        <a href="veiculos_listar.php">Listar Veículos</a>
        <a href="veiculos_cadastrar.php">Cadastrar Veículo</a>
        <a href="clientes_cadastrar.php">Cadastrar Cliente</a>
        <a href="locacoes_cadastrar.php">Nova Locação</a>
    </nav>
</header>

    <div class="container-index">
        <!-- Banner Principal -->
        <section class="hero-banner">
            <div class="hero-text">
                <h1>Bem-vindo à <span class="highlight">TungCars</span></h1>
                <p>A melhor experiência em mobilidade, conforto e segurança. Encontre o veículo ideal para sua viagem, trabalho ou lazer com as melhores taxas do mercado.</p>
            </div>
        </section>

        <!-- Indicadores / Estatísticas -->
        <section class="stats-section">
            <div class="stat-card">
                <h3><?= $totalVeiculos ?></h3>
                <p>Veículos na Frota</p>
            </div>
            <div class="stat-card">
                <h3><?= $totalClientes ?></h3>
                <p>Clientes Cadastrados</p>
            </div>
            <div class="stat-card">
                <h3><?= $totalLocacoes ?></h3>
                <p>Locações Realizadas</p>
            </div>
        </section>

        <!-- Seção Sobre Nós -->
        <section class="about-section">
            <h2>Por que escolher a TungCars?</h2>
            <div class="cards-grid">
                <div class="info-card">
                    <img src="https://images.unsplash.com/photo-1503376780353-7e6692767b70?auto=format&fit=crop&w=500&q=80" alt="Frota Moderna">
                    <h3>Frota Diversificada</h3>
                    <p>Trabalhamos com carros hatch, sedãs e SUVs das principais marcas do mercado para atender todas as suas necessidades.</p>
                </div>
                <div class="info-card">
                    <img src="https://images.unsplash.com/photo-1563720223185-11003d516935?auto=format&fit=crop&w=500&q=80" alt="Revisão de Segurança">
                    <h3>Segurança Garantida</h3>
                    <p>Todos os nossos veículos passam por revisões periódicas rigorosas para garantir uma viagem tranquila e segura.</p>
                </div>
                <div class="info-card">
                    <img src="https://images.unsplash.com/photo-1552519507-da3b142c6e3d?auto=format&fit=crop&w=500&q=80" alt="Atendimento Ágil">
                    <h3>Agilidade na Locação</h3>
                    <p>Processo de cadastro e retirada simples, ágil e sem burocracias para você não perder tempo.</p>
                </div>
            </div>
        </section>

        <!-- Veículos em Destaque -->
        <?php if (count($destaques) > 0): ?>
        <section class="featured-section">
            <h2>Alguns Veículos da Nossa Frota</h2>
            <div class="cards-grid">
                <?php foreach ($destaques as $carro): ?>
                    <div class="car-card">
                        <div class="car-badge"><?= htmlspecialchars($carro['marca']) ?></div>
                        <h3><?= htmlspecialchars($carro['modelo']) ?></h3>
                        <p class="car-plate">Placa: <?= htmlspecialchars($carro['placa']) ?></p>
                        <p class="car-price">R$ <?= number_format($carro['valor_diaria'], 2, ',', '.') ?> <span>/ dia</span></p>
                        
                        <div>
                            <button type="button" class="car-bottom">
                        <a href="locacoes_cadastrar.php" class="btn-botao-index">Alugar este modelo</a>
                </button>
                </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>
    </div>
</body>
</html>