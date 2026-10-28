<?php
defined('KAMADO_BREDA') || exit;

// Wordt als eerste geladen door index.php (de router).
// Zet alles klaar wat elke pagina nodig heeft.

// Nederlandse tijd gebruiken (anders is het UTC, 1 of 2 uur te vroeg)
date_default_timezone_set('Europe/Amsterdam');

// Map waarin de website staat, bijv. "/" of "/kamado/" op de server.
// Wordt gebruikt voor <base href> en om nette URL's te maken.
define('BASIS', rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/') . '/');

// Sessie voor winkelwagen, formulieren en bevestigingen.
// httponly: JavaScript kan het cookie niet lezen. Lax: niet meesturen bij formulieren van andere sites.
session_set_cookie_params([
    'path'     => BASIS,
    'httponly' => true,
    'secure'   => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
    'samesite' => 'Lax',
]);
session_start();

// Beveiligingsheaders (werken zowel op Herd als op Apache)
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('Referrer-Policy: strict-origin-when-cross-origin');
header('Permissions-Policy: camera=(), microphone=(), geolocation=()');

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/data.php';
require_once __DIR__ . '/shop.php';
