<?php
session_set_cookie_params(['httponly' => true, 'samesite' => 'Lax']);
session_start();

// Erros inesperados (ex.: banco fora do ar) viram uma mensagem amigável em vez do erro técnico do PHP.
ini_set('display_errors', '0');
set_exception_handler(function ($ex) {
    error_log((string)$ex);
    http_response_code(500);
    echo '<!doctype html><meta charset="utf-8"><p style="font-family:sans-serif;text-align:center;margin-top:20vh">'
       . '🧁 Ops! Algo deu errado. Tente novamente em instantes.</p>';
});

require __DIR__ . '/db.php';
require __DIR__ . '/helpers.php';
require __DIR__ . '/auth.php';
