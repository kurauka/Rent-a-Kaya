<?php
// Simple admin auth helpers
require_once __DIR__ . '/db.php';

function is_admin_logged_in(){
    // permanent (cookie) or temporary (session)
    if (function_exists('is_logged_in_permanent') && is_logged_in_permanent()) return true;
    if (function_exists('is_logged_in_temporary') && is_logged_in_temporary()) return true;
    return false;
}

function require_admin(){
    // Allow temporary bypass when DISABLE_ADMIN_AUTH is set in environment (value '1' or 'true')
    $disable = getenv('DISABLE_ADMIN_AUTH');
    if (!empty($disable) && (strval($disable) === '1' || strtolower(strval($disable)) === 'true')) {
        return true;
    }

    if (!is_admin_logged_in()){
        // redirect to admin login
        header('Location: /rent-a-kaya/admin/login.php');
        exit;
    }
}
