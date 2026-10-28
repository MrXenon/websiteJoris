<?php
require __DIR__ . '/form-handler.php';

$name = $_POST['name'] ?? '';
handle_form(
    SITENAME . ' contact verzoek | ' . $name,
    [
        'Naam'           => $name,
        'E-mail'         => $_POST['email'] ?? '',
        'Telefoonnummer' => $_POST['phone'] ?? '',
        'Bericht'        => $_POST['comment'] ?? '',
    ],
    [
        'captcha' => 'Your message could not been sent due to invalid reCaptcha!',
        'success' => 'Your message has been sent successfully!',
        'fail'    => 'Your message could not been sent!',
    ]
);
