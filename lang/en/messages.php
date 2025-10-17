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
        'token' => [
            'status' => 'Token status verified successfully',
        ],
    ],
    'customers' => [
        'list' => [
            'success' => 'Customers listed successfully',
            'error' => 'Error listing customers',
        ],
        'create' => [
            'success' => 'Customer created successfully',
            'error' => 'Error creating customer',
        ],
        'show' => [
            'success' => 'Customer found successfully',
            'error' => 'Error finding customer',
            'not_found' => 'Customer not found',
        ],
        'update' => [
            'success' => 'Customer updated successfully',
            'error' => 'Error updating customer',
            'not_found' => 'Customer not found',
        ],
        'delete' => [
            'success' => 'Customer removed successfully',
            'error' => 'Error removing customer',
            'not_found' => 'Customer not found',
        ],
        'email_already_exists' => 'Email is already in use by another customer',
    ],
    'favorites' => [
        'list' => [
            'success' => 'Favorites listed successfully',
            'error' => 'Error listing favorites',
        ],
        'create' => [
            'success' => 'Product added to favorites successfully',
            'error' => 'Error adding favorite',
            'already_exists' => 'Product is already in this customer\'s favorites',
        ],
        'show' => [
            'success' => 'Favorite found successfully',
            'error' => 'Error finding favorite',
            'not_found' => 'Favorite not found',
        ],
        'delete' => [
            'success' => 'Product removed from favorites successfully',
            'error' => 'Error removing favorite',
            'not_found' => 'Favorite not found',
        ],
        'check' => [
            'success' => 'Favorite status verified successfully',
            'error' => 'Error checking favorite',
        ],
        'customer_not_found' => 'Customer not found',
        'product_not_found' => 'Product not found in external API',
    ],
    'validation' => [
        'required' => 'The :field field is required',
        'email' => 'The :field must be a valid email address',
        'password' => 'The :field field is required',
        'string' => 'The :field must be a string',
        'max' => 'The :field may not be greater than :max characters',
        'min' => 'The :field must be at least :min characters',
        'integer' => 'The :field must be an integer',
        'unique' => 'The :field has already been taken',
    ],
    'errors' => [
        'server_error' => 'An error occurred on the server',
        'unauthorized' => 'Unauthorized access',
        'forbidden' => 'Access forbidden',
        'not_found' => 'Resource not found',
    ],
];
