<?php
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

    // Obter friendId ou groupId da requisição
    $friendId = $_GET['friendId'] ?? null;
    $groupId = $_GET['groupId'] ?? null;

    // Verificar se friendId ou groupId está presente
    if ($friendId) 
    {
        // Verificar se o usuário está autenticado
        if (!isset($_SESSION['user_id'])) 
        {
            $response['success'] = false;
            $response['message'] = 'Usuário não autenticado.';
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }
    
        // Carregar mensagens para um amigo específico
        $query = "SELECT conteudo FROM mensagens WHERE (id_remetente = :userId AND id_destinatario = :friendId) OR (id_remetente = :friendId AND id_destinatario = :userId)";
        $statement = $conn->prepare($query);
        $statement->bindParam(':userId', $_SESSION['user_id']);
        $statement->bindParam(':friendId', $friendId);
        $statement->execute();
        $messages = $statement->fetchAll(PDO::FETCH_ASSOC);
    } 
    elseif ($groupId) 
    {
        // Carregar mensagens para um grupo específico
        $query = "SELECT conteudo FROM mensagens WHERE id_grupo = :groupId";
        $statement = $conn->prepare($query);
        $statement->bindParam(':groupId', $groupId);
        $statement->execute();
        $messages = $statement->fetchAll(PDO::FETCH_ASSOC);
    } 
    else 
    {
        // Se nenhum friendId ou groupId estiver presente, retorne uma resposta de erro
        $response['success'] = false;
        $response['message'] = 'Nenhum friendId ou groupId fornecido na requisição.';
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
    

    $response['success'] = true;
    $response['messages'] = $messages;
} 
catch (PDOException $e) 
{
    $response['success'] = false;
    $response['message'] = 'Erro no banco de dados: ' . $e->getMessage();
}

header('Content-Type: application/json');
echo json_encode($response);
?>
