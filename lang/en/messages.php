<?php

return [
    'auth' => [
        'login' => [
            'success' => 'Login successful',
            'failed' => 'Login failed',
            'invalid_credentials' => 'Invalid credentials provided',
        ],
        'logout' => [
            'success' => 'Logged out successfully',
        ],
        'profile' => [
            'retrieved' => 'User profile retrieved successfully',
        ],
        'admin' => [
            'access_granted' => 'Admin access granted',
            'access_denied' => 'Access denied',
        ],
    ],
    'validation' => [
        'required' => 'The :field field is required',
        'email' => 'The :field must be a valid email address',
        'password' => 'The :field field is required',
    ],
    'errors' => [
        'server_error' => 'An error occurred on the server',
        'unauthorized' => 'Unauthorized access',
        'forbidden' => 'Access forbidden',
        'not_found' => 'Resource not found',
    ],
];
