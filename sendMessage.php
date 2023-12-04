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
    $conteudo = $_POST['conteudo'];
    $id_destinatario = $_POST['id_destinatario'];
    $id_grupo = $_POST['id_grupo'];

    // Inserir a mensagem no banco de dados
    $stmt = $conn->prepare("INSERT INTO mensagens (id_remetente, id_destinatario, id_grupo, conteudo) VALUES (:id_remetente, :id_destinatario, :id_grupo, :conteudo)");
    $stmt->bindParam(':id_remetente', $_SESSION['user_id']);
    $stmt->bindParam(':id_destinatario', $id_destinatario);
    $stmt->bindParam(':id_grupo', $id_grupo);
    $stmt->bindParam(':conteudo', $conteudo);
    $stmt->execute();

    $response['success'] = true;
    $response['message'] = 'Mensagem enviada com sucesso.';
} 
catch (PDOException $e) 
{
    $response['success'] = false;
    $response['message'] = 'Erro no banco de dados: ' . $e->getMessage();
}

header('Content-Type: application/json');
echo json_encode($response);
?>
