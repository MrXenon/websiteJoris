<?php
defined('KAMADO_BREDA') || exit;

// Pagina voor URL's die niet bestaan
http_response_code(404);
$paginaTitel = 'Pagina niet gevonden';
$geenIndex = true;
require __DIR__ . '/../includes/header.php';
?>

<section class="section">
    <div class="container narrow center">
        <p class="eyebrow">Fout 404</p>
        <h1>Deze pagina bestaat niet</h1>
        <p>Misschien is de link verkeerd overgetypt, of is de pagina verplaatst.</p>
        <div class="hero-buttons center">
            <a href="./" class="btn btn-primary">Naar de homepage</a>
            <a href="kamados" class="btn btn-outline">Bekijk de kamado's</a>
        </div>
    </div>
</section>

<?php require __DIR__ . '/../includes/footer.php'; ?>
