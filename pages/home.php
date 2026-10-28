<?php
defined('KAMADO_BREDA') || exit;

$paginaTitel = 'Blauwe kamado\'s & BBQ-masterclasses';
$actief = '';
$preloadFoto = 'winkel-kamado-breda.webp';
require __DIR__ . '/../includes/header.php';
?>

<section class="hero">
    <div class="container hero-content">
        <p class="eyebrow">Speciaalzaak in Breda</p>
        <h1>Blauwe kamado's.<br><span class="text-fire">Echt vuur.</span></h1>
        <p class="hero-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
        <div class="hero-buttons">
            <a href="kamados" class="btn btn-primary btn-large">Shop de kamado's</a>
            <a href="masterclasses" class="btn btn-ghost btn-large">Masterclasses</a>
        </div>
        <ul class="hero-trust">
            <li>Tot levenslang garantie op keramiek</li>
            <li>Bezorging in regio Breda</li>
            <li>Veilig betalen met iDEAL</li>
        </ul>
    </div>
</section>

<section class="usps">
    <div class="container usp-grid">
        <div class="usp reveal">
            <span class="usp-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 12-8.5 8.5a2.12 2.12 0 1 1-3-3L12 9"/><path d="M17.64 15 22 10.64"/><path d="m20.91 11.7-1.25-1.25a2 2 0 0 1-.58-1.42V7.5L15.5 4l-1.4.03a3 3 0 0 0-2.11.9L11 6l4.5 4.5"/></svg>
            </span>
            <h3>Op maat gemaakt</h3>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
        </div>
        <div class="usp reveal">
            <span class="usp-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.07-2.14-.22-4.05 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.15.43-2.29 1-3a2.5 2.5 0 0 0 2.5 2.5z"/></svg>
            </span>
            <h3>Gietijzeren accessoires</h3>
            <p>Ut enim ad minim veniam, quis nostrud exercitation.</p>
        </div>
        <div class="usp reveal">
            <span class="usp-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2 15.09 8.26 22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
            </span>
            <h3>Masterclasses</h3>
            <p>Duis aute irure dolor in reprehenderit in voluptate.</p>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-head section-head-row">
            <div>
                <p class="eyebrow">Het assortiment</p>
                <h2>Onze kamado's</h2>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam quis risus eget urna mollis ornare vel eu leo.</p>
            </div>
            <a href="kamados" class="link-arrow">Alles bekijken</a>
        </div>

        <div class="grid grid-3">
            <?php foreach ($kamados as $id => $kamado) {
                include __DIR__ . '/../includes/kamado-kaart.php';
            } ?>
        </div>
    </div>
</section>

<?php if ($masterclasses): ?>
<section class="section section-dark">
    <div class="container">
        <div class="section-head section-head-row">
            <div>
                <p class="eyebrow">Leren van de pitmaster</p>
                <h2>Komende masterclasses</h2>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas sed diam eget risus varius blandit sit amet non magna.</p>
            </div>
            <a href="masterclasses" class="link-arrow">Hele agenda</a>
        </div>

        <div class="grid grid-3">
            <?php foreach ($masterclasses as $id => $class) {
                include __DIR__ . '/../includes/masterclass-kaart.php';
            } ?>
        </div>
    </div>
</section>
<?php endif; ?>

<section class="section">
    <div class="container split">
        <div class="split-image reveal">
            <?= foto('joris.jpg', 'Joris, eigenaar van Kamado Breda, voor zijn winkel') ?>
        </div>
        <div class="split-text reveal">
            <p class="eyebrow">Over ons</p>
            <h2>Hoi, ik ben Joris</h2>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Aenean eu leo quam. Pellentesque ornare sem lacinia quam venenatis vestibulum.</p>
            <p>Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec ullamcorper nulla non metus auctor fringilla. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor.</p>
            <blockquote>
                “Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor.”
                <cite>— Joris, eigenaar</cite>
            </blockquote>
            <a href="kamados" class="btn btn-outline">Kom langs in de winkel</a>
        </div>
    </div>
</section>

<section class="cta">
    <div class="container cta-inner">
        <h2>Klaar voor je eerste low &amp; slow?</h2>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
        <a href="masterclasses#inschrijven" class="btn btn-dark btn-large">Schrijf je in</a>
    </div>
</section>

<?php require __DIR__ . '/../includes/footer.php'; ?>
