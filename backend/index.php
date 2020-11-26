<?php
// Set HttpOnly
ini_set('session.cookie_httponly', 1);

/**
 * Setting CORS
 */
// Setting CORS Header - If "*" is not allowed, you must set "X-Requested-With", "Accept", "Content-Type", "Cookie".
header('Access-Control-Allow-Headers: *');
// Setting CORS Origin Domain - Usually not allowed "*"
header('Access-Control-Allow-Origin: https://frontend.dev.local');
// Setting CORS Methods
header('Access-Control-Allow-Methods: *');
// Setting CORS XHR Credentials - for Cookie support
header('Access-Control-Allow-Credentials: true');

/**
 * Session start
 */
SESSION_START();

/**
 * Output json
 */
// Setting Content Type: JSON
header('Content-Type: application/json; charset=utf-8');

// Build output information
$result = [
    'env' => 'Backend',
    'session_id' => session_id(),
];

// Output json
echo json_encode($result);
