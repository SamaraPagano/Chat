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

    if ($_SERVER["REQUEST_METHOD"] == "POST") 
    {
        $email = $_POST["email"];
        $senha = $_POST["senha"];

        $query = "SELECT id, email, senha FROM usuarios WHERE email = ?";
        $statement = $conn->prepare($query);
        $statement->execute([$email]);
        $user = $statement->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($senha, $user["senha"])) 
        {
            // Login bem-sucedido, armazene informações do usuário na sessão
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["user_email"] = $user["email"];

            // Responda com um JSON indicando o sucesso
            $response['success'] = true;
            $response['message'] = 'Login realizado com sucesso!';
        } 
        else 
        {
            // Responda com um JSON indicando credenciais inválidas
            $response['success'] = false;
            $response['message'] = 'Credenciais inválidas.';
        }
    }
} 
catch (PDOException $e) 
{
    // Responda com um JSON indicando erro no banco de dados
    $response['success'] = false;
    $response['message'] = 'Erro no banco de dados: ' . $e->getMessage();
}

// Saída do JSON
echo json_encode($response);
?>
