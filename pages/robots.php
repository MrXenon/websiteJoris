<?php
defined('KAMADO_BREDA') || exit;

// robots.txt voor zoekmachines (met het volledige adres van de sitemap)
header('Content-Type: text/plain; charset=UTF-8');
?>
User-agent: *
Disallow: <?= BASIS ?>winkelwagen
Disallow: <?= BASIS ?>afrekenen
Disallow: <?= BASIS ?>bestelling/
Disallow: <?= BASIS ?>betalen/

Sitemap: <?= siteUrl('sitemap.xml') ?>

