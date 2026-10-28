<?php
// =========================================================
// Front controller (router)
// Elke URL komt hier binnen: op Herd automatisch, op Apache via .htaccess.
// Zo werken nette URL's zonder .php, bijvoorbeeld /kamados of /kamados/blue-l.
// De echte pagina's staan in de map pages/.
// =========================================================
// Met "php -S localhost:8000 index.php": bestaande bestanden (CSS, foto's) gewoon laten serveren
if (PHP_SAPI === 'cli-server' && is_file(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))) {
    return false;
}

define('KAMADO_BREDA', true);
require __DIR__ . '/includes/bootstrap.php';

// Het pad ná de map van de site, zonder slashes aan begin/eind.
// "/kamados/blue-l" -> "kamados/blue-l", "/" -> ""
$pad = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$pad = rawurldecode(substr($pad, strlen(BASIS)));
$route = trim($pad, '/');

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
