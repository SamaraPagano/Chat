<?php
header('Content-Type: application/json');

// Conexão com o banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "chat";

try 
{
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Recebendo dados do formulário
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = password_hash($_POST["senha"], PASSWORD_DEFAULT);

    // Inserindo dados no banco
    $stmt = $conn->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (:nome, :email, :senha)");
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':senha', $senha);
    $stmt->execute();

    $response['success'] = true;
    $response['message'] = 'Cadastro realizado com sucesso!';
} 
catch (PDOException $e) 
{
    $response['success'] = false;
    $response['message'] = 'Erro no banco de dados: ' . $e->getMessage();
}

echo json_encode($response);
?>
