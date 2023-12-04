<?php
// Conexão com o banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "chat";

try 
{
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Iniciar ou retomar a sessão
    session_start();

    // Verificar se o usuário está autenticado
    if (!isset($_SESSION['user_id'])) 
    {
        $response['success'] = false;
        $response['message'] = 'Usuário não autenticado.';
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }

    // Obter dados do formulário
    $id_destinatario = $_GET['id_destinatario'];
    $id_grupo = $_GET['id_grupo'];

    // Obter as mensagens mais recentes do banco de dados
    $stmt = $conn->prepare("SELECT * FROM mensagens WHERE (id_remetente = :user_id AND id_destinatario = :id_destinatario) OR (id_remetente = :id_destinatario AND id_destinatario = :user_id) OR id_grupo = :id_grupo ORDER BY enviado DESC LIMIT 10");
    $stmt->bindParam(':user_id', $_SESSION['user_id']);
    $stmt->bindParam(':id_destinatario', $id_destinatario);
    $stmt->bindParam(':id_grupo', $id_grupo);
    $stmt->execute();
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
