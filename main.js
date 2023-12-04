$(document).ready(function() 
{
    // Função para carregar amigos e grupos do servidor
    function loadFriendsAndGroups() 
    {
        $.ajax({
            type: 'GET',
            url: 'amigos-grupos.php',
            dataType: 'json',
            success: function(response) 
            {
                if (response.success) 
                {
                    displayFriends(response.friends);
                    displayGroups(response.groups);
                } 
                else 
                {
                    console.error('Erro ao carregar amigos e grupos:', response.message);
                }
            },
            error: function() 
            {
                console.error('Erro de conexão ao carregar amigos e grupos.');
            }
        });
    }

    // Função para exibir amigos dinamicamente
    function displayFriends(friends) 
    {
        var friendsList = $('.friends-list');
        friendsList.empty();
        friends.forEach(function(friend) {
            var listItem = $('<li><a href="chat.html?friendId=' + friend.id + '">' + friend.nome + '</a></li>');
            friendsList.append(listItem);
        });
    }

    // Função para exibir grupos dinamicamente
    function displayGroups(groups) 
    {
        var groupsList = $('.groups-list');
        groupsList.empty();
        groups.forEach(function(group) 
        {
            var listItem = $('<li><a href="chat.html?groupId=' + group.id + '">' + group.nome_grupo + '</a></li>');
            groupsList.append(listItem);
        });
    }

    // Carregar amigos e grupos ao iniciar a página
    loadFriendsAndGroups();
});

$(document).ready(function() {
    $('#create-group-form').submit(function(event) {
        event.preventDefault();

        var nomeGrupo = $('#nome-grupo').val();

        $.ajax({
            type: 'POST',
            url: 'criar-grupo.php',
            data: { 'nome-grupo': nomeGrupo },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#create-group-message').html('<p class="success">' + response.message + '</p>');
                } else {
                    $('#create-group-message').html('<p class="error">' + response.message + '</p>');
                }
            },
            error: function() {
                $('#create-group-message').html('<p class="error">Erro ao criar grupo. Tente novamente mais tarde.</p>');
            }
        });
    });
});
