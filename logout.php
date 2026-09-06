<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
auth_logout();
header('Location: login.php', true, 303);
exit;
