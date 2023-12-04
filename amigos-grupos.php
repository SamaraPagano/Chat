<?php
$amigos = [];
$grupos = [];

// Conexão com o banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "chat";

try 
{
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Recuperar lista de amigos
    $friendsQuery = "SELECT id, nome FROM usuarios";
    $friendsStatement = $conn->prepare($friendsQuery);
    $friendsStatement->execute();
    $amigos = $friendsStatement->fetchAll(PDO::FETCH_ASSOC);

    // Recuperar lista de grupos
    $groupsQuery = "SELECT id, nome_grupo FROM grupo";
    $groupsStatement = $conn->prepare($groupsQuery);
    $groupsStatement->execute();
    $grupos = $groupsStatement->fetchAll(PDO::FETCH_ASSOC);

} 
catch (PDOException $e) 
{
    echo 'Erro no banco de dados: ' . $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="friends-groups-style.css">
    <title>Amigos e Grupos - WhatsApp Clone</title>
</head>
<body>
    <header>
        <div class="logo">
            <img src="whatsapp.png" alt="WhatsApp">
        </div>
        <h1>WhatsApp Clone</h1>
        <nav>
            <ul>
                <li><a href="logout.php">Sair</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <div class="friends-groups-container">
            <section class="friends-section">
                <h2>Amigos</h2>
                <ul class="friends-list">
                    <!-- Exibir amigos dinamicamente -->
                    <?php foreach ($amigos as $amigo) : ?>
                        <li><a href="chat.html?friendId=<?php echo $amigo['id']; ?>"><?php echo $amigo['nome']; ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </section>

            <section class="groups-section">
                <h2>Grupos</h2>
                <ul class="groups-list">
                    <!-- Exibir grupos dinamicamente -->
                    <?php foreach ($grupos as $grupo) : ?>
                        <li><a href="chat.html?groupId=<?php echo $grupo['id']; ?>"><?php echo $grupo['nome_grupo']; ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </section>
        </div>

        <div class="create-group-section">
        <h2>Criar Grupo</h2>
        <form id="create-group-form" action="criar-grupo.php" method="post">
            <label for="nome-grupo">Nome do Grupo:</label>
            <input type="text" name="nome-grupo" id="nome-grupo" required>
            <button type="submit">Criar Grupo</button>
        </form>
        <div id="create-group-message"></div>
    </div>
    </main>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</body>
</html>
