<?php
defined('KAMADO_BREDA') || exit;

// ---------- Acties: toevoegen, aantal wijzigen, verwijderen ----------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Komt het verzoek van js/app.js? Dan antwoorden we met JSON in plaats van een pagina.
    $viaJs = str_contains($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json');
    $producten = alleProducten();
    $productId = (string) ($_POST['product'] ?? '');

    if (!csrfOk()) {
        $fout = 'Je sessie is verlopen. Probeer het opnieuw.';
    } elseif (!isset($producten[$productId]) && ($_POST['actie'] ?? '') !== 'bijwerken') {
        $fout = 'Dit product bestaat niet.';
    }

    if (isset($fout)) {
        if ($viaJs) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(['ok' => false, 'melding' => $fout]);
            exit;
        }
        zetMelding($fout, 'error');
        stuurDoor('winkelwagen');
    }

    switch ($_POST['actie'] ?? '') {
        case 'toevoegen':
            $aantal = max(1, (int) ($_POST['aantal'] ?? 1));
            voegToeAanWinkelwagen($productId, $aantal);
            $melding = $producten[$productId]['naam'] . ' is toegevoegd aan je winkelwagen.';
            break;

        case 'bijwerken':
            // Alle aantallen in één keer: aantal[blue-l] = 2
            foreach ((array) ($_POST['aantal'] ?? []) as $id => $aantal) {
                zetInWinkelwagen((string) $id, (int) $aantal);
            }
            $melding = 'Je winkelwagen is bijgewerkt.';
            break;

        case 'verwijderen':
            zetInWinkelwagen($productId, 0);
            $melding = $producten[$productId]['naam'] . ' is verwijderd.';
            break;

        default:
            $melding = '';
    }

    if ($viaJs) {
        header('Content-Type: application/json');
        echo json_encode(['ok' => true, 'melding' => $melding, 'aantal' => aantalInWinkelwagen()]);
        exit;
    }

    // Zonder JavaScript: melding tonen op de winkelwagenpagina (Post/Redirect/Get)
    zetMelding($melding);
    stuurDoor('winkelwagen');
}

// ---------- Winkelwagen tonen ----------
$wagen = berekenWinkelwagen(true);
$nogVoorGratis = $config['gratis_verzending_vanaf'] - $wagen['subtotaal'];

$paginaTitel = 'Winkelwagen';
$actief = 'winkelwagen';
$geenIndex = true;
require __DIR__ . '/../includes/header.php';
?>

<section class="section section-tight">
    <div class="container">
        <?php $stap = 1; include __DIR__ . '/../includes/checkout-stappen.php'; ?>
        <h1>Winkelwagen</h1>

        <?php if (!$wagen['regels']): ?>
            <div class="empty-state">
                <svg viewBox="0 0 24 24" width="56" height="56" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                <h2>Je winkelwagen is nog leeg</h2>
                <p>Kies een kamado of een mooi stuk gietijzer om te beginnen.</p>
                <div class="hero-buttons center">
                    <a href="kamados" class="btn btn-primary">Bekijk de kamado's</a>
                    <a href="kamados#accessoires" class="btn btn-outline">Accessoires</a>
                </div>
            </div>
        <?php else: ?>
            <div class="checkout-layout">
                <form action="winkelwagen" method="post" class="cart" data-cart-form>
                    <?= csrfVeld() ?>
                    <input type="hidden" name="actie" value="bijwerken">

                    <ul class="cart-list">
                        <?php foreach ($wagen['regels'] as $regel): ?>
                            <li class="cart-item">
                                <a href="<?= e($regel['link']) ?>" class="cart-item-image">
                                    <?= foto('thumbs/' . $regel['foto'], '') ?>
                                </a>
                                <div class="cart-item-info">
                                    <h2><a href="<?= e($regel['link']) ?>"><?= e($regel['naam']) ?></a></h2>
                                    <p class="note"><?= prijs($regel['prijs']) ?> per stuk</p>
                                </div>
                                <label class="qty">
                                    <span class="visually-hidden">Aantal <?= e($regel['naam']) ?></span>
                                    <input type="number" name="aantal[<?= e($regel['id']) ?>]" value="<?= $regel['aantal'] ?>"
                                           min="0" max="<?= (int) $config['max_aantal'] ?>" inputmode="numeric" data-auto-submit>
                                </label>
                                <p class="cart-item-total"><?= prijs($regel['totaal']) ?></p>
                                <button type="submit" form="verwijder-<?= e($regel['id']) ?>" class="icon-btn" aria-label="<?= e($regel['naam']) ?> verwijderen">
                                    <svg viewBox="0 0 24 24" width="20" height="20" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M3 6h18"/><path d="M8 6V4h8v2"/><path d="M19 6l-1 14H6L5 6"/></svg>
                                </button>
                            </li>
                        <?php endforeach; ?>
                    </ul>

                    <div class="cart-actions">
                        <a href="kamados" class="link-arrow link-back">Verder winkelen</a>
                        <button type="submit" class="btn btn-outline btn-small" data-hide-with-js>Aantallen bijwerken</button>
                    </div>
                </form>

                <?php // Losse formulieren voor de verwijderknoppen (formulieren mogen niet in elkaar zitten)
                foreach ($wagen['regels'] as $regel): ?>
                    <form action="winkelwagen" method="post" id="verwijder-<?= e($regel['id']) ?>" hidden>
                        <?= csrfVeld() ?>
                        <input type="hidden" name="actie" value="verwijderen">
                        <input type="hidden" name="product" value="<?= e($regel['id']) ?>">
                    </form>
                <?php endforeach; ?>

                <aside class="summary">
                    <h2>Overzicht</h2>
                    <dl class="totals">
                        <dt>Subtotaal</dt>
                        <dd><?= prijs($wagen['subtotaal']) ?></dd>
                        <dt>Bezorgen</dt>
                        <dd><?= $wagen['verzendkosten'] > 0 ? prijs($wagen['verzendkosten']) : 'Gratis' ?></dd>
                        <dt>Ophalen in de winkel</dt>
                        <dd>Gratis</dd>
                    </dl>
                    <?php if ($nogVoorGratis > 0): ?>
                        <p class="free-shipping">Nog <?= prijs($nogVoorGratis) ?> tot gratis bezorging</p>
                    <?php endif; ?>
                    <p class="summary-total">Totaal <span><?= prijs($wagen['subtotaal']) ?></span></p>
                    <p class="note">Incl. btw. Verzendkosten worden berekend bij het afrekenen.</p>
                    <a href="afrekenen" class="btn btn-primary btn-large btn-block">Afrekenen</a>
                    <?php include __DIR__ . '/../includes/betaalmethodes.php'; ?>
                </aside>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require __DIR__ . '/../includes/footer.php'; ?>
