<?php
defined('KAMADO_BREDA') || exit;

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !mollieActief()) {
    http_response_code(405);
    exit;
}

$betaalId = (string) ($_POST['id'] ?? '');
$betaling = preg_match('/^tr_[A-Za-z0-9]+$/', $betaalId) ? mollie('GET', 'payments/' . $betaalId) : null;

if ($betaling === null) {
    http_response_code(404);
    exit;
}

$bestelling = laadBestelling((string) ($betaling['metadata']['bestelnummer'] ?? ''));
if ($bestelling !== null && ($bestelling['mollie_id'] ?? '') === $betaalId) {
    werkMollieStatusBij($bestelling);
}

http_response_code(200);
