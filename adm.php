<?php
// estatisticas.php

// Certifique-se de verificar se o usuário está autenticado e é um ADM antes de permitir o acesso
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['tipo'] !== 'ADM') {
    // Redirecionar para a página de login ou exibir uma mensagem de acesso negado
    header("Location: login.php");
    exit();
}

// Conexão com o banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "chat";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Obter estatísticas do banco de dados
    $stmt = $conn->prepare("SELECT COUNT(*) AS total_usuarios FROM usuarios");
    $stmt->execute();
    $totalUsuarios = $stmt->fetch(PDO::FETCH_ASSOC)['total_usuarios'];

    $stmt = $conn->prepare("SELECT COUNT(*) AS total_mensagens FROM mensagens");
    $stmt->execute();
    $totalMensagens = $stmt->fetch(PDO::FETCH_ASSOC)['total_mensagens'];

    // Estatísticas adicionais
    $stmt = $conn->prepare("SELECT COUNT(*) AS total_grupos FROM grupo");
    $stmt->execute();
    $totalGrupos = $stmt->fetch(PDO::FETCH_ASSOC)['total_grupos'];

    $stmt = $conn->prepare("SELECT DATE(enviado) AS data, COUNT(*) AS total_mensagens_por_dia FROM mensagens GROUP BY data");
    $stmt->execute();
    $mensagensPorDia = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Usuários que mais utilizam
    $stmt = $conn->prepare("SELECT nome, COUNT(*) AS total_mensagens FROM mensagens JOIN usuarios ON mensagens.id_remetente = usuarios.id GROUP BY id_remetente ORDER BY total_mensagens DESC LIMIT 5");
    $stmt->execute();
    $usuariosMaisAtivos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Usuários que menos acessam
    $stmt = $conn->prepare("SELECT nome, COUNT(*) AS total_mensagens FROM mensagens JOIN usuarios ON mensagens.id_remetente = usuarios.id GROUP BY id_remetente ORDER BY total_mensagens ASC LIMIT 5");
    $stmt->execute();
    $usuariosMenosAtivos = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Erro no banco de dados: " . $e->getMessage();
    die();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estatísticas ADM</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <h1>Estatísticas ADM</h1>

    <p>Total de Usuários: <?php echo $totalUsuarios; ?></p>
    <p>Total de Mensagens: <?php echo $totalMensagens; ?></p>
    <p>Total de Grupos: <?php echo $totalGrupos; ?></p>

    <h2>Mensagens Enviadas por Dia</h2>
    <ul>
        <?php foreach ($mensagensPorDia as $mensagemDia): ?>
            <li><?php echo $mensagemDia['data'] . ': ' . $mensagemDia['total_mensagens_por_dia']; ?></li>
        <?php endforeach; ?>
    </ul>

    <h2>Usuários que Mais Utilizam</h2>
    <ul>
        <?php foreach ($usuariosMaisAtivos as $usuario): ?>
            <li><?php echo $usuario['nome'] . ': ' . $usuario['total_mensagens'] . ' mensagens'; ?></li>
        <?php endforeach; ?>
    </ul>

    <h2>Usuários que Menos Acessam</h2>
    <ul>
        <?php foreach ($usuariosMenosAtivos as $usuario): ?>
            <li><?php echo $usuario['nome'] . ': ' . $usuario['total_mensagens'] . ' mensagens'; ?></li>
        <?php endforeach; ?>
    </ul>

    <h2>Gráfico: Usuários Mais Ativos</h2>
    <canvas id="usuariosMaisAtivosChart" width="400" height="400"></canvas>

    <script>
        // Configurar dados para o gráfico de usuários mais ativos
        var usuariosMaisAtivosData = {
            labels: <?php echo json_encode(array_column($usuariosMaisAtivos, 'nome')); ?>,
            datasets: [{
                label: 'Total de Mensagens',
                data: <?php echo json_encode(array_column($usuariosMaisAtivos, 'total_mensagens')); ?>,
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        };

        // Configurar opções do gráfico
        var usuariosMaisAtivosOptions = {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        };

        // Criar gráfico de barras para usuários mais ativos
        var usuariosMaisAtivosCtx = document.getElementById('usuariosMaisAtivosChart').getContext('2d');
        var usuariosMaisAtivosChart = new Chart(usuariosMaisAtivosCtx, {
            type: 'bar',
            data: usuariosMaisAtivosData,
            options: usuariosMaisAtivosOptions
        });
    </script>
</body>
</html>
