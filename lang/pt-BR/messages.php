<?php

return [
    'auth' => [
        'login' => [
            'success' => 'Login realizado com sucesso',
            'failed' => 'Falha no login',
            'invalid_credentials' => 'Credenciais inválidas fornecidas',
        ],
        'logout' => [
            'success' => 'Logout realizado com sucesso',
        ],
        'profile' => [
            'retrieved' => 'Perfil do usuário recuperado com sucesso',
        ],
        'admin' => [
            'access_granted' => 'Acesso de administrador concedido',
            'access_denied' => 'Acesso negado',
        ],
        'token' => [
            'status' => 'Status do token verificado com sucesso',
        ],
    ],
    'customers' => [
        'list' => [
            'success' => 'Clientes listados com sucesso',
            'error' => 'Erro ao listar clientes',
        ],
        'create' => [
            'success' => 'Cliente criado com sucesso',
            'error' => 'Erro ao criar cliente',
        ],
        'show' => [
            'success' => 'Cliente encontrado com sucesso',
            'error' => 'Erro ao buscar cliente',
            'not_found' => 'Cliente não encontrado',
        ],
        'update' => [
            'success' => 'Cliente atualizado com sucesso',
            'error' => 'Erro ao atualizar cliente',
            'not_found' => 'Cliente não encontrado',
        ],
        'delete' => [
            'success' => 'Cliente removido com sucesso',
            'error' => 'Erro ao remover cliente',
            'not_found' => 'Cliente não encontrado',
        ],
        'email_already_exists' => 'E-mail já está em uso por outro cliente',
    ],
    'favorites' => [
        'list' => [
            'success' => 'Favoritos listados com sucesso',
            'error' => 'Erro ao listar favoritos',
        ],
        'create' => [
            'success' => 'Produto adicionado aos favoritos com sucesso',
            'error' => 'Erro ao adicionar favorito',
            'already_exists' => 'Produto já está nos favoritos deste cliente',
        ],
        'show' => [
            'success' => 'Favorito encontrado com sucesso',
            'error' => 'Erro ao buscar favorito',
            'not_found' => 'Favorito não encontrado',
        ],
        'delete' => [
            'success' => 'Produto removido dos favoritos com sucesso',
            'error' => 'Erro ao remover favorito',
            'not_found' => 'Favorito não encontrado',
        ],
        'check' => [
            'success' => 'Status do favorito verificado com sucesso',
            'error' => 'Erro ao verificar favorito',
        ],
        'customer_not_found' => 'Cliente não encontrado',
        'product_not_found' => 'Produto não encontrado na API externa',
    ],
    'validation' => [
        'required' => 'O campo :field é obrigatório',
        'email' => 'O campo :field deve ser um email válido',
        'password' => 'O campo :field é obrigatório',
        'string' => 'O campo :field deve ser uma string',
        'max' => 'O campo :field não pode ter mais de :max caracteres',
        'min' => 'O campo :field deve ter pelo menos :min caracteres',
        'integer' => 'O campo :field deve ser um número inteiro',
        'unique' => 'O campo :field já está em uso',
        'failed' => 'Os dados fornecidos são inválidos',
    ],
    'errors' => [
        'server_error' => 'Ocorreu um erro no servidor',
        'unauthorized' => 'Acesso não autorizado',
        'forbidden' => 'Acesso proibido',
        'not_found' => 'Recurso não encontrado',
    ],
];
