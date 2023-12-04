<?php
// Inicie a sessão
session_start();

// Conexão com o banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "chat";

try 
{
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Verificar se o usuário está autenticado
    if (!isset($_SESSION['user_id'])) 
    {
        $response['success'] = false;
        $response['message'] = 'Usuário não autenticado.';
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }

    // Recuperar dados do formulário
    $nomeGrupo = $_POST['nome-grupo'];
    $userId = $_SESSION['user_id'];

    // Inserir dados do grupo no banco de dados
    $stmt = $conn->prepare("INSERT INTO grupo (nome_grupo, criado) VALUES (:nomeGrupo, CURRENT_TIMESTAMP)");
    $stmt->bindParam(':nomeGrupo', $nomeGrupo);
    $stmt->execute();

    // Obter o ID do grupo recém-criado
    $groupId = $conn->lastInsertId();

    // Associar o usuário ao grupo
    $stmt = $conn->prepare("INSERT INTO usuarios_grupos (id_usuario, id_grupo) VALUES (:userId, :groupId)");
    $stmt->bindParam(':userId', $userId);
    $stmt->bindParam(':groupId', $groupId);
    $stmt->execute();

    $response['success'] = true;
    $response['message'] = 'Grupo criado com sucesso!';
} 
catch (PDOException $e) 
{
    $response['success'] = false;
    $response['message'] = 'Erro no banco de dados: ' . $e->getMessage();
}

header('Content-Type: application/json');
echo json_encode($response);
?>
