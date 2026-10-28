<?php
// Pagina voor URL's die niet bestaan
http_response_code(404);
$paginaTitel = 'Pagina niet gevonden';
require 'includes/header.php';
?>

<section class="section">
    <div class="container narrow center">
        <p class="eyebrow">Fout 404</p>
        <h1>Deze pagina bestaat niet</h1>
        <p>Misschien is de link verkeerd overgetypt, of is de pagina verplaatst.</p>
        <div class="hero-buttons center">
            <a href="index.php" class="btn btn-primary">Naar de homepage</a>
            <a href="kamados.php" class="btn btn-outline">Bekijk de kamado's</a>
        </div>
    </div>
</section>

<?php require 'includes/footer.php'; ?>
