<?php
defined('KAMADO_BREDA') || exit;

// Levende stijlgids: alle voorbeelden gebruiken dezelfde CSS en includes als de echte site.
// De tekstversie staat in docs/stijlgids.md.

$paginaTitel = 'Stijlgids';
$beschrijving = 'Stijlgids van de website van Kamado Breda: kleuren, typografie, componenten, afstanden en breakpoints.';
$geenIndex = true;
require __DIR__ . '/../includes/header.php';

$kleuren = [
    ['Houtskool',    '#16191f', 'Achtergrond header, footer, donkere secties'],
    ['Antraciet',    '#232833', 'Kaarten op donkere achtergrond'],
    ['Kamado blauw', '#1f3f8f', 'Accenten, links, labels (kleur van de kamado\'s)'],
    ['Blauw licht',  '#3a62c4', 'Hover van blauwe elementen'],
    ['Vuur oranje',  '#e8862a', 'Knoppen (call to action), prijzen'],
    ['Oranje donker', '#c96d17', 'Hover van knoppen'],
    ['Crème',        '#f4ede3', 'Lichte achtergrond, tekst op donker'],
    ['Tekst donker', '#1d1f24', 'Lopende tekst op licht'],
    ['Grijs',        '#6b6f78', 'Kleine/secundaire tekst'],
    ['Fout rood',    '#c0392b', 'Foutmeldingen formulier'],
    ['Succes groen', '#2e8b57', 'Bevestiging'],
];

$typografie = [
    ['h1',    '3.6rem', '2.2rem', 'Oswald 700, hoofdletters'],
    ['h2',    '2.4rem', '1.7rem', 'Oswald 700, hoofdletters'],
    ['h3',    '1.35rem', '1.2rem', 'Oswald 500'],
    ['tekst', '1.075rem', '1rem', 'Source Sans 3 400/600, regelhoogte 1.65'],
];
?>

<section class="page-hero">
    <div class="container">
        <p class="eyebrow">Ontwerp</p>
        <h1>Stijlgids</h1>
        <p>Kamado Breda is een stoere BBQ-speciaalzaak. De site moet voelen als "vuur, gietijzer en hout": donker, warm en ambachtelijk. Niet te druk.
           De kleuren komen uit het logo van Joris en de foto van zijn winkel.</p>
    </div>
</section>

<nav class="section-tight styleguide-nav" aria-label="Onderdelen stijlgids">
    <div class="container">
        <a href="stijlgids#kleuren">Kleuren</a>
        <a href="stijlgids#typografie">Typografie</a>
        <a href="stijlgids#knoppen">Knoppen</a>
        <a href="stijlgids#kaarten">Kaarten</a>
        <a href="stijlgids#labels">Labels &amp; meldingen</a>
        <a href="stijlgids#formulieren">Formulieren</a>
        <a href="stijlgids#layout">Afstanden &amp; breakpoints</a>
        <a href="stijlgids#afbeeldingen">Afbeeldingen</a>
        <a href="docs/wireframes.html">Wireframes &rarr;</a>
    </div>
</nav>

<!-- ---------- Kleuren ---------- -->
<section class="section section-tight" id="kleuren">
    <div class="container">
        <div class="section-head">
            <p class="eyebrow">Basis</p>
            <h2>Kleuren</h2>
            <p>Staan als CSS-variabelen bovenaan <code>css/kamado.css</code>, bijv. <code>var(--oranje)</code>.</p>
        </div>
        <div class="swatches">
            <?php foreach ($kleuren as [$naam, $hex, $gebruik]): ?>
                <div class="swatch">
                    <span class="swatch-color" style="background: <?= e($hex) ?>"></span>
                    <strong><?= e($naam) ?></strong>
                    <code><?= e($hex) ?></code>
                    <small><?= e($gebruik) ?></small>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ---------- Typografie ---------- -->
<section class="section section-light" id="typografie">
    <div class="container">
        <div class="section-head">
            <p class="eyebrow">Basis</p>
            <h2>Typografie</h2>
            <p><strong>Oswald</strong> voor koppen (past bij de "stempel"-letters van het logo) en <strong>Source Sans 3</strong> voor tekst.
               Beide staan op onze eigen server in <code>fonts/</code>. Groottes schalen vloeiend mee met de schermbreedte (<code>clamp()</code>).</p>
        </div>

        <div class="type-samples">
            <p class="eyebrow">Eyebrow – klein label boven een kop</p>
            <h1>H1 · Blauwe kamado's</h1>
            <h2>H2 · Komende masterclasses</h2>
            <h3>H3 · Kamado Breda Blue L</h3>
            <p class="lead">Lead – grotere introtekst. Onze bestseller voor het hele gezin.</p>
            <p>Lopende tekst – Lorem ipsum dolor sit amet, consectetur adipiscing elit. <a href="stijlgids#typografie">Dit is een link</a>.
               Integer posuere erat a ante venenatis dapibus posuere velit aliquet.</p>
            <p class="note">Notitie – kleine grijze tekst, bijv. "* verplicht veld".</p>
            <p class="price">€ 1.299,- <small>incl. btw</small></p>
        </div>

        <div class="table-scroll">
        <table class="specs">
            <thead>
                <tr><th>Element</th><th>Desktop</th><th>Mobiel</th><th>Lettertype</th></tr>
            </thead>
            <tbody>
                <?php foreach ($typografie as [$element, $desktop, $mobiel, $font]): ?>
                    <tr><td><?= e($element) ?></td><td><?= e($desktop) ?></td><td><?= e($mobiel) ?></td><td><?= e($font) ?></td></tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
    </div>
</section>

<!-- ---------- Knoppen ---------- -->
<section class="section" id="knoppen">
    <div class="container">
        <div class="section-head">
            <p class="eyebrow">Componenten</p>
            <h2>Knoppen</h2>
            <p>Hoofdletters in Oswald, <code>border-radius: 10px</code>. Bij hover schuift een knop 2px omhoog.</p>
        </div>

        <div class="sg-row">
            <a href="stijlgids#knoppen" class="btn btn-primary">Primair</a>
            <a href="stijlgids#knoppen" class="btn btn-outline">Secundair</a>
            <a href="stijlgids#knoppen" class="btn btn-primary btn-small">Klein</a>
            <a href="stijlgids#knoppen" class="btn btn-primary btn-large">Groot</a>
            <a href="stijlgids#knoppen" class="link-arrow">Tekstlink met pijl</a>
        </div>
        <div class="sg-row sg-dark">
            <a href="stijlgids#knoppen" class="btn btn-primary">Primair</a>
            <a href="stijlgids#knoppen" class="btn btn-ghost">Ghost (op donker)</a>
            <a href="stijlgids#knoppen" class="link-arrow">Tekstlink met pijl</a>
        </div>
        <div class="sg-row sg-orange">
            <a href="stijlgids#knoppen" class="btn btn-dark">Donker (op oranje)</a>
        </div>

        <ul class="sg-notes">
            <li><strong>Primair (oranje):</strong> de belangrijkste actie, bijv. Bestellen, Afrekenen, Inschrijven. Maximaal één per blok.</li>
            <li><strong>Secundair (rand):</strong> andere acties, bijv. Details, Verder winkelen.</li>
            <li><strong>Ghost:</strong> secundaire knop op een donkere foto of achtergrond.</li>
        </ul>
    </div>
</section>

<!-- ---------- Kaarten ---------- -->
<section class="section section-light" id="kaarten">
    <div class="container">
        <div class="section-head">
            <p class="eyebrow">Componenten</p>
            <h2>Kaarten</h2>
            <p>Witte kaart met foto boven, <code>border-radius: 16px</code>, dunne rand en zachte schaduw. Komt omhoog bij hover.
               Dit zijn de echte includes van de site.</p>
        </div>
        <div class="grid grid-3">
            <?php
            // Voorbeelden uit het CMS: het tweede product (of het eerste als er maar één is)
            $id = array_keys($kamados)[1] ?? array_key_first($kamados);
            if ($id !== null) {
                $kamado = $kamados[$id];
                include __DIR__ . '/../includes/kamado-kaart.php';
            }

            $id = array_keys($accessoires)[1] ?? array_key_first($accessoires);
            if ($id !== null) {
                $item = $accessoires[$id];
                include __DIR__ . '/../includes/accessoire-kaart.php';
            }

            $eersteClass = array_key_first($masterclasses);
            if ($eersteClass !== null) {
                $id = $eersteClass;
                $class = $masterclasses[$id];
                include __DIR__ . '/../includes/masterclass-kaart.php';
            }
            ?>
        </div>
        <ul class="sg-notes">
            <li><strong>Productkaart</strong> (<code>includes/kamado-kaart.php</code>): badge, naam, korte tekst, prijs, knoppen Bestellen + Details.</li>
            <li><strong>Accessoirekaart</strong> (<code>includes/accessoire-kaart.php</code>): zonder detailpagina, alleen Bestellen.</li>
            <li><strong>Masterclasskaart</strong> (<code>includes/masterclass-kaart.php</code>): oranje datumblok, niveau, plekken en Inschrijven.</li>
        </ul>
    </div>
</section>

<!-- ---------- Labels & meldingen ---------- -->
<section class="section" id="labels">
    <div class="container">
        <div class="section-head">
            <p class="eyebrow">Componenten</p>
            <h2>Labels &amp; meldingen</h2>
        </div>

        <div class="sg-row">
            <span class="badge badge-inline">Bestseller</span>
            <span class="spots">Nog 7 plekken</span>
            <span class="spots spots-low">Nog 4 plekken</span>
            <span class="cart-count sg-static">2</span>
        </div>

        <div class="sg-split">
            <div>
                <div class="alert alert-succes">Kamado Breda Blue L is toegevoegd aan je winkelwagen.</div>
                <div class="alert alert-error"><strong>Niet alles is goed ingevuld.</strong> Controleer de rode velden hieronder.</div>
                <ul class="check-list">
                    <li>Checklist met oranje vinkjes</li>
                    <li>Voor kenmerken en voordelen</li>
                </ul>
                <blockquote>
                    “Citaat met oranje streep aan de linkerkant.”
                    <cite>— Joris, eigenaar</cite>
                </blockquote>
            </div>
            <div>
                <div class="toast show sg-static">
                    <p>Gietijzer set is toegevoegd aan je winkelwagen.</p>
                    <a href="winkelwagen" class="btn btn-primary btn-small">Naar winkelwagen</a>
                </div>
                <p class="note">Toast: verschijnt rechtsonder na "Bestellen" (js/app.js).</p>

                <div class="summary">
                    <h3>Overzicht</h3>
                    <dl class="totals">
                        <dt>Subtotaal</dt><dd>€ 1.358,-</dd>
                        <dt>Bezorgen</dt><dd>Gratis</dd>
                    </dl>
                    <p class="summary-total">Totaal <span>€ 1.358,-</span></p>
                    <?php include __DIR__ . '/../includes/betaalmethodes.php'; ?>
                </div>
                <p class="note">Samenvatting: donker blok naast winkelwagen, afrekenen en inschrijfformulier.</p>
            </div>
        </div>
    </div>
</section>

<!-- ---------- Formulieren ---------- -->
<section class="section section-light" id="formulieren">
    <div class="container">
        <div class="section-head">
            <p class="eyebrow">Componenten</p>
            <h2>Formulieren</h2>
            <p>Labels boven de velden, velden 100% breed, <code>border-radius: 10px</code> en een blauwe focus-ring.
               Bij een fout: rode rand en een melding onder het veld.</p>
        </div>

        <?php $stap = 2; include __DIR__ . '/../includes/checkout-stappen.php'; ?>

        <div class="form sg-form">
            <div class="field-row">
                <div class="field">
                    <label for="sg-naam">Voornaam *</label>
                    <input type="text" id="sg-naam" value="Joris">
                </div>
                <div class="field has-error">
                    <label for="sg-mail">E-mailadres *</label>
                    <input type="email" id="sg-mail" value="joris@" aria-invalid="true" aria-describedby="sg-fout">
                    <p class="field-error" id="sg-fout">Vul een geldig e-mailadres in.</p>
                </div>
            </div>
            <div class="choice-group">
                <label class="choice">
                    <input type="radio" name="sg-levering" checked>
                    <span><strong>Bezorgen</strong><small>Keuzeblok, gekozen</small></span>
                </label>
                <label class="choice">
                    <input type="radio" name="sg-levering">
                    <span><strong>Afhalen in de winkel</strong><small>Keuzeblok</small></span>
                </label>
            </div>
            <div class="field">
                <label class="checkbox">
                    <input type="checkbox">
                    <span>Checkbox met tekst ernaast</span>
                </label>
            </div>
            <div class="sg-row">
                <label class="qty"><span class="visually-hidden">Aantal</span><input type="number" value="1" min="1"></label>
                <button type="button" class="btn btn-primary">Bestellen</button>
            </div>
        </div>
    </div>
</section>

<!-- ---------- Afstanden & breakpoints ---------- -->
<section class="section" id="layout">
    <div class="container">
        <div class="section-head">
            <p class="eyebrow">Layout</p>
            <h2>Afstanden &amp; breakpoints</h2>
        </div>
        <div class="sg-split">
            <div>
                <h3>Afstanden</h3>
                <p>Basis is 8px. Container is max. 1200px breed met 20px padding aan de zijkant.</p>
                <div class="spacing-scale">
                    <?php foreach ([8, 16, 24, 32, 48, 64, 96] as $px): ?>
                        <div><span style="width: <?= $px ?>px"></span><?= $px ?>px</div>
                    <?php endforeach; ?>
                </div>
                <table class="specs">
                    <tr><th scope="row">Sectie desktop</th><td><code>padding: 96px 0</code></td></tr>
                    <tr><th scope="row">Sectie mobiel</th><td><code>padding: 64px 0</code></td></tr>
                    <tr><th scope="row">Ruimte tussen kaarten</th><td>24px (desktop 28px)</td></tr>
                    <tr><th scope="row">Hoeken</th><td>10px (knoppen, velden) · 16px (kaarten) · 24px (grote blokken)</td></tr>
                </table>
            </div>
            <div>
                <h3>Breakpoints</h3>
                <p>Mobile-first: eerst de stijl voor mobiel, daarna met <code>@media</code> uitbreiden.</p>
                <table class="specs">
                    <tr><th scope="row">Mobiel</th><td>&lt; 700px · 1 kolom, hamburgermenu</td></tr>
                    <tr><th scope="row">Tablet</th><td><code>min-width: 700px</code> · 2 kolommen</td></tr>
                    <tr><th scope="row">Desktop</th><td><code>min-width: 1000px</code> · 3–4 kolommen, menu naast logo</td></tr>
                </table>
                <h3>Beweging</h3>
                <p>Kaarten verschijnen rustig bij scrollen en knoppen/kaarten bewegen bij hover.
                   Wie in het besturingssysteem "minder beweging" heeft aangezet, krijgt geen animaties.</p>
            </div>
        </div>
    </div>
</section>

<!-- ---------- Afbeeldingen ---------- -->
<section class="section section-light" id="afbeeldingen">
    <div class="container">
        <div class="section-head">
            <p class="eyebrow">Layout</p>
            <h2>Afbeeldingen</h2>
        </div>
        <div class="sg-split">
            <ul class="check-list">
                <li>Grote foto's max. 1200px breed (hero 1920px), JPEG kwaliteit 80.</li>
                <li>Foto's van de content staan in <code>images/frontendPagesContent/</code> (de uploadmap van het CMS), thumbnails van 480px breed in <code>images/frontendPagesContent/thumbs/</code>.</li>
                <li>Van elke JPG staat er ook een <code>.webp</code> naast; de functie <code>foto()</code> in <code>includes/functions.php</code> toont ze via <code>&lt;picture&gt;</code>, met breedte en hoogte.</li>
                <li>Altijd een <code>alt</code>-tekst.</li>
                <li>Logo in de header: <code>images/logo-sm.webp</code> (8 KB), in de footer <code>logo-md.webp</code>.</li>
            </ul>
            <div class="sg-logo">
                <picture>
                    <source srcset="images/logo-md.webp" type="image/webp">
                    <img src="images/logo-md.png" alt="Logo Kamado Breda" width="160" height="159" loading="lazy">
                </picture>
            </div>
        </div>
    </div>
</section>

<?php require __DIR__ . '/../includes/footer.php'; ?>
