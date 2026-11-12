<?php
session_start();

// 1. Empty the session data (user_id, user_name)
$_SESSION = [];

// 2. Delete the session cookie in the browser
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params['path'], $params['domain'],
        $params['secure'], $params['httponly']
    );
}

// 3. Destroy the session on the server
session_destroy();

// 4. Send the user back to the login page
header('Location: login.php');
exit;