$(document).ready(function() 
{
    $('#login-form').submit(function(event) 
    {
        event.preventDefault();

        var email = $('#email').val();
        var senha = $('#senha').val();

        $.ajax({
            type: 'POST',
            url: 'login.php',
            data: { email: email, senha: senha },
            dataType: 'json',
            success: function(response) 
            {
                if (response.success) 
                {
                    $('#mensagem').html('<p class="success">Login realizado com sucesso!</p>');

                    // Redirecionar para a tela principal após 2 segundos
                    setTimeout(function() 
                    {
                        window.location.href = 'amigos-grupos.php'; 
                    }, 2000);
                }
                else 
                {
                    $('#mensagem').html('<p class="error">' + response.message + '</p>');
                }
            },
            error: function() 
            {
                $('#mensagem').html('<p class="error">Erro ao processar o login. Tente novamente mais tarde.</p>');
            }
        });
    });
});
