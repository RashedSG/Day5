<?php
declare(strict_types=1);

function auth_user(): ?string
{
    $user = $_SESSION['auth_user'] ?? null;
    return is_string($user) && $user !== '' ? $user : null;
}

function auth_check(): void
{
    $script = basename($_SERVER['SCRIPT_NAME'] ?? '');
    if (in_array($script, ['login.php'], true)) {
        return;
    }
    if (auth_user() === null) {
        $next = $script !== '' ? $script : 'index.php';
        if (!empty($_SERVER['QUERY_STRING'])) {
            $next .= '?' . $_SERVER['QUERY_STRING'];
        }
        redirect('login.php?next=' . rawurlencode($next));
    }
}

function auth_attempt(string $user, string $pass): bool
{
    if ($user !== AUTH_USER) {
        return false;
    }
    if (!password_verify($pass, AUTH_PASS_HASH)) {
        return false;
    }
    session_regenerate_id(true);
    $_SESSION['auth_user'] = $user;
    return true;
}

function auth_logout(): void
{
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $p['path'] ?? '/', $p['domain'] ?? '', (bool)$p['secure'], (bool)$p['httponly']);
    }
    session_destroy();
}

auth_check();
