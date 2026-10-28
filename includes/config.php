<?php
defined('KAMADO_BREDA') || exit;

// Instellingen van de webshop.
// Geheime waarden (zoals de Mollie API-sleutel) NIET hier invullen,
// maar in includes/config.local.php (staat in .gitignore). Zie config.local.example.php.

$config = [
    // Mollie API-sleutel (test_... of live_...). Leeg = demo:
    // de betaling wordt dan gesimuleerd en er wordt niet echt betaald.
    'mollie_api_key' => getenv('MOLLIE_API_KEY') ?: '',

    // Volledige adres van de site, bijv. https://www.kamadobreda.nl
    // Leeg = automatisch bepalen. Nodig voor de Mollie-webhook en de sitemap.
    'site_url' => '',

    // Verzendkosten bij bezorgen (in euro's)
    'verzendkosten'           => 6.95,
    'gratis_verzending_vanaf' => 100,

    // Maximaal aantal stuks per product in de winkelwagen
    'max_aantal' => 10,

    // Hier komt een melding naartoe bij een nieuwe bestelling (als de server mail kan versturen)
    'winkel_email' => 'info@kamadobreda.nl',
];

if (file_exists(__DIR__ . '/config.local.php')) {
    require __DIR__ . '/config.local.php';
}
