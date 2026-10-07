<?php
require_once __DIR__ . '/data.php';
require_once __DIR__ . '/functions.php';

// Standaardwaarden als een pagina ze niet zelf instelt
$paginaTitel = $paginaTitel ?? 'Kamado Breda';
$actief = $actief ?? '';

// Map waarin de website staat, bijv. "/" of "/kamado/" op de server.
// Via <base> worden css/, images/ en js/ altijd vanaf die map geladen,
// ook als iemand een rare URL intypt zoals /docs/iets.html
$basis = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/') . '/';

// Menu-items: bestand => tekst
$menu = [
    'index.php'       => 'Home',
    'kamados.php'     => "Kamado's",
    'masterclass.php' => 'Masterclasses',
];
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <base href="<?= e($basis) ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($paginaTitel) ?> | Kamado Breda</title>
    <meta name="description" content="Kamado Breda – speciaalzaak in blauwe kamado-barbecues, gietijzeren accessoires en BBQ-masterclasses in Breda.">
    <link rel="icon" type="image/png" href="images/favicon.png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@500;700&family=Source+Sans+3:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<header class="site-header">
    <div class="container header-inner">
        <a href="index.php" class="logo">
            <img src="images/logo.png" alt="Logo Kamado Breda" width="56" height="56">
            <span>Kamado <strong>Breda</strong></span>
        </a>

        <button class="menu-toggle" aria-expanded="false" aria-controls="hoofdmenu" aria-label="Menu openen">
            <span></span><span></span><span></span>
        </button>

        <nav id="hoofdmenu" class="main-nav">
            <ul>
                <?php foreach ($menu as $link => $tekst): ?>
                    <li>
                        <a href="<?= $link ?>" <?= $actief === $link ? 'class="active" aria-current="page"' : '' ?>>
                            <?= e($tekst) ?>
                        </a>
                    </li>
                <?php endforeach; ?>
                <li><a href="masterclass.php#inschrijven" class="btn btn-primary btn-small">Inschrijven</a></li>
            </ul>
        </nav>
    </div>
</header>

<main>
