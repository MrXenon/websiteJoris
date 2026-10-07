<?php
// Herd stuurt elke onbekende URL naar index.php.
// Is dit niet echt de homepage? Dan de 404-pagina tonen.
$map = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/') . '/';
$pad = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
if ($pad !== $map && $pad !== $map . 'index.php') {
    require '404.php';
    exit;
}

$paginaTitel = 'Blauwe kamado\'s & BBQ-masterclasses';
$actief = 'index.php';
require 'includes/header.php';
?>

<section class="hero">
    <div class="container hero-content">
        <p class="eyebrow">Speciaalzaak in Breda</p>
        <h1>Blauwe kamado's.<br>Echt vuur.</h1>
        <p class="hero-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
        <div class="hero-buttons">
            <a href="kamados.php" class="btn btn-primary">Bekijk de kamado's</a>
            <a href="masterclass.php" class="btn btn-ghost">Masterclasses</a>
        </div>
    </div>
</section>

<section class="usps">
    <div class="container usp-grid">
        <div class="usp">
            <span class="usp-icon" aria-hidden="true">&#9874;</span>
            <h3>Op maat gemaakt</h3>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
        </div>
        <div class="usp">
            <span class="usp-icon" aria-hidden="true">&#9832;</span>
            <h3>Gietijzeren accessoires</h3>
            <p>Ut enim ad minim veniam, quis nostrud exercitation.</p>
        </div>
        <div class="usp">
            <span class="usp-icon" aria-hidden="true">&#9733;</span>
            <h3>Masterclasses</h3>
            <p>Duis aute irure dolor in reprehenderit in voluptate.</p>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-head">
            <p class="eyebrow">Het assortiment</p>
            <h2>Onze kamado's</h2>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam quis risus eget urna mollis ornare vel eu leo.</p>
        </div>

        <div class="grid grid-3">
            <?php foreach ($kamados as $id => $kamado) {
                include 'includes/kamado-kaart.php';
            } ?>
        </div>
    </div>
</section>

<section class="section section-dark">
    <div class="container">
        <div class="section-head">
            <p class="eyebrow">Leren van de pitmaster</p>
            <h2>Komende masterclasses</h2>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas sed diam eget risus varius blandit sit amet non magna.</p>
        </div>

        <div class="grid grid-3">
            <?php foreach ($masterclasses as $id => $class) {
                include 'includes/masterclass-kaart.php';
            } ?>
        </div>
    </div>
</section>

<section class="section">
    <div class="container split">
        <div class="split-image">
            <img src="images/joris.jpg" alt="Joris, eigenaar van Kamado Breda, voor zijn winkel" loading="lazy">
        </div>
        <div class="split-text">
            <p class="eyebrow">Over ons</p>
            <h2>Hoi, ik ben Joris</h2>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Aenean eu leo quam. Pellentesque ornare sem lacinia quam venenatis vestibulum.</p>
            <p>Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec ullamcorper nulla non metus auctor fringilla. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor.</p>
            <blockquote>
                “Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor.”
                <cite>— Joris, eigenaar</cite>
            </blockquote>
            <a href="kamados.php" class="btn btn-outline">Kom langs in de winkel</a>
        </div>
    </div>
</section>

<section class="cta">
    <div class="container cta-inner">
        <h2>Klaar voor je eerste low &amp; slow?</h2>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
        <a href="masterclass.php#inschrijven" class="btn btn-primary">Schrijf je in</a>
    </div>
</section>

<?php require 'includes/footer.php'; ?>
