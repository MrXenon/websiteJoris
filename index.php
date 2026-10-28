<?php
// =========================================================
// Front controller (router) van Kamado Breda op KBS.FRONT
// Elke URL die geen bestaand bestand is komt hier binnen: op Herd automatisch, op Apache via .htaccess.
// 1. /admin/...            -> beheer, geserveerd vanuit KBS.CMS (php/cmsAdmin.php)
// 2. /login, /register ... -> pagina's van KBS.FRONT en pagina's die het CMS aanmaakt (bestand.php)
// 3. de rest               -> de website/webshop van Kamado Breda (pages/)
// =========================================================
// Met "php -S localhost:8000 index.php": bestaande bestanden (CSS, foto's) gewoon laten serveren
if (PHP_SAPI === 'cli-server' && is_file(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))) {
    return false;
}

// Het pad ná de map van de site, zonder slashes aan begin/eind.
// "/kamados/blue-l" -> "kamados/blue-l", "/" -> ""
$basis = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/') . '/';
$pad = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$pad = rawurldecode(substr($pad, strlen($basis)));
$route = trim($pad, '/');

// 1. Beheer (KBS.CMS)
if (preg_match('#^admin(?:/(.*))?$#', $route, $match)) {
    $adminPath = trim($match[1] ?? '', '/');
    require __DIR__ . '/php/cmsAdmin.php';
}

// 2. Pagina's van KBS.FRONT zelf (login.php, register.php, 404.php, door het CMS aangemaakte pagina's, ...)
//    Niet de mappen van de webshop: die bestanden zijn alleen bedoeld om via deze router te laden.
$kbsBestand = preg_replace('/\.php$/', '', $route);
if ($kbsBestand !== '' && $kbsBestand !== 'index'
    && preg_match('#^[A-Za-z0-9_-]+(?:/[A-Za-z0-9_-]+)*$#', $kbsBestand)
    && !preg_match('#^(includes|pages|data)(/|$)|^php/cms(Admin|Root)$#', $kbsBestand)
    && is_file(__DIR__ . '/' . $kbsBestand . '.php')) {
    chdir(dirname(__DIR__ . '/' . $kbsBestand . '.php'));
    require __DIR__ . '/' . $kbsBestand . '.php';
    exit;
}

// 3. Website van Kamado Breda
define('KAMADO_BREDA', true);
require __DIR__ . '/includes/bootstrap.php';

// Oude links met .php (en een slash aan het eind) netjes doorsturen.
// Alleen bij GET, anders gaan formuliergegevens verloren.
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $oud = [
        'index.php'       => '',
        'kamados.php'     => 'kamados',
        'masterclass.php' => 'masterclasses',
        'masterclass'     => 'masterclasses',
        'bevestiging.php' => 'bevestiging',
        'fotobronnen.php' => 'fotobronnen',
    ];
    $query = $_GET;

    if ($route === 'product.php' || $route === 'product') {
        $nieuw = 'kamados' . (isset($query['id']) ? '/' . $query['id'] : '');
        unset($query['id']);
    } elseif (isset($oud[$route])) {
        $nieuw = $oud[$route];
    } elseif (str_ends_with($route, '.php')) {
        $nieuw = substr($route, 0, -4);
    } elseif ($route !== '' && str_ends_with($pad, '/')) {
        $nieuw = $route;
    }

    if (isset($nieuw)) {
        stuurDoor(url($nieuw) . ($query ? '?' . http_build_query($query) : ''), 301);
    }
}

// Vaste routes: URL => bestand in pages/
$routes = [
    ''                => 'home.php',
    'kamados'         => 'kamados.php',
    'masterclasses'   => 'masterclasses.php',
    'bevestiging'     => 'bevestiging.php',
    'fotobronnen'     => 'fotobronnen.php',
    'winkelwagen'     => 'winkelwagen.php',
    'afrekenen'       => 'afrekenen.php',
    'voorwaarden'     => 'voorwaarden.php',
    'stijlgids'       => 'stijlgids.php',
    'mollie-webhook'  => 'mollie-webhook.php',
    'sitemap.xml'     => 'sitemap.php',
    'robots.txt'      => 'robots.php',
];

// Routes met een variabel deel, bijv. kamados/blue-l
$parameter = null;
if (preg_match('#^kamados/([a-z0-9-]+)$#', $route, $match)) {
    $bestand = 'product.php';
    $parameter = $match[1];
} elseif (preg_match('#^bestelling/([A-Z0-9-]+)$#', $route, $match)) {
    $bestand = 'bestelling.php';
    $parameter = $match[1];
} elseif (preg_match('#^betalen/([A-Z0-9-]+)$#', $route, $match)) {
    $bestand = 'betalen.php';
    $parameter = $match[1];
} else {
    $bestand = $routes[$route] ?? '404.php';
}

require __DIR__ . '/pages/' . $bestand;
