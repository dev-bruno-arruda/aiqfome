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
    ],
    'validation' => [
        'required' => 'O campo :field é obrigatório',
        'email' => 'O campo :field deve ser um email válido',
        'password' => 'O campo :field é obrigatório',
    ],
    'errors' => [
        'server_error' => 'Ocorreu um erro no servidor',
        'unauthorized' => 'Acesso não autorizado',
        'forbidden' => 'Acesso proibido',
        'not_found' => 'Recurso não encontrado',
    ],
];
