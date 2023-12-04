$(document).ready(function() 
{
    // Função para obter o valor de um parâmetro da URL
    function getUrlParameter(name) 
    {
        name = name.replace(/[[]/, "\\[").replace(/[]]/, "\\]");
        var regex = new RegExp("[\\?&]" + name + "=([^&#]*)");
        var results = regex.exec(location.search);
        return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
    }

    // Função para enviar mensagem
    $('#send-message-form').submit(function(event) 
    {
        event.preventDefault();

        var conteudo = $('#conteudo').val();
        var id_destinatario = getUrlParameter('friendId');
        var id_grupo = getUrlParameter('groupId');

        $.ajax({
            type: 'POST',
            url: 'sendMessage.php',
            data: { conteudo: conteudo, id_destinatario: id_destinatario, id_grupo: id_grupo },
            dataType: 'json',
            success: function(response) 
            {
                if (response.success) 
                {
                    // Limpar o campo de entrada após o envio bem-sucedido
                    $('#conteudo').val('');
                }
                else 
                {
                    console.error('Erro ao enviar mensagem:', response.message);
                }
            },
            error: function() 
            {
                console.error('Erro de conexão ao enviar mensagem.');
            }
        });
    });

    // Função para obter e exibir mensagens em tempo real
    function getMessages() 
    {
        var id_destinatario = getUrlParameter('friendId');
        var id_grupo = getUrlParameter('groupId');

        $.ajax({
            type: 'GET',
            url: 'getMessages.php',
            data: { id_destinatario: id_destinatario, id_grupo: id_grupo },
            dataType: 'json',
            success: function(response) 
            {
                if (response.success) 
                {
                    displayMessages(response.messages);
                } 
                else 
                {
                    console.error('Erro ao obter mensagens:', response.message);
                }
            },
            error: function() 
            {
                console.error('Erro de conexão ao obter mensagens.');
            },
            complete: function() 
            {
                // Chamar recursivamente após um intervalo de tempo
                setTimeout(getMessages, 5000); 
            }
        });
    }

    // Função para exibir mensagens dinamicamente
    function displayMessages(messages) 
    {
        var chatMessages = $('#chat-messages');
        chatMessages.empty();
        messages.forEach(function(message) 
        {
            var messageTime = new Date(message.enviado);
            var formattedTime = messageTime.toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit' });
    
            var messageItem = $('<div class="message">' + message.conteudo + '<span class="message-time">' + formattedTime + '</span></div>');
            chatMessages.append(messageItem);
        });
    }

    // Chamar a função para obter e exibir mensagens em tempo real
    getMessages();
});
