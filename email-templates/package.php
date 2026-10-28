<?php
require __DIR__ . '/form-handler.php';

$name = $_POST['name'] ?? '';
handle_form(
    'Hosting pakket aanvraag | ' . $name,
    [
        'Naam'        => $name,
        'E-mail'      => $_POST['email'] ?? '',
        'Pakket plan' => $_POST['plan'] ?? '',
        'Postcode'    => $_POST['postcode'] ?? '',
        'Woonplaats'  => $_POST['woonplaats'] ?? '',
        'Bedrijf'     => $_POST['bedrijf'] ?? '',
    ],
    [
        'captcha' => 'Aanvraag kon niet worden ingedient door een ongeldige Recaptcha!',
        'success' => 'Aanvraag is succesvol ingedient.',
        'fail'    => 'Aanvraag kon niet worden ingedient!',
    ]
);
