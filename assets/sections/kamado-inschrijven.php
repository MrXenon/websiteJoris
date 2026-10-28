<?php
/**
 * Kamado Breda – inschrijfformulier voor de masterclasses, met een overzicht van de prijs ernaast.
 * Werkt alleen op de pagina masterclasses (daar worden de inschrijvingen verwerkt). Gebruik deze layout één keer.
 * Subtitle = klein label boven de titel. Title = titel. Content = intro.
 * Links naar het formulier: masterclasses#inschrijven (of het anker dat je hieronder invult).
 * Preview: contact
 * Setting: maxPersonen | number | Maximaal aantal personen per inschrijving
 * Setting: akkoord | textarea | Tekst bij het vinkje voor akkoord
 * Setting: knop | text | Tekst op de verzendknop
 * Setting: anchor | text advanced | Anker | Leeg = "inschrijven", voor links als masterclasses#inschrijven
 */
defined('KAMADO_BREDA') || exit;
$inschrijfAnker = cms_extra($fpc_obj, 'anchor') ?: 'inschrijven';
?>
<section class="section" id="<?= e($inschrijfAnker) ?>">
    <div class="container">
        <?php include __DIR__ . '/../../includes/sectie-kop.php'; ?>

        <div class="form-layout">
            <form action="masterclasses#<?= e($inschrijfAnker) ?>" method="post" class="form" id="inschrijfformulier" novalidate>
                <?= csrfVeld() ?>

                <?php if (!empty($fouten)): ?>
                    <div class="alert alert-error" role="alert">
                        <strong>Niet alles is goed ingevuld.</strong> Controleer de rode velden hieronder.
                        <?= isset($fouten['algemeen']) ? '<br>' . e($fouten['algemeen']) : '' ?>
                    </div>
                <?php endif; ?>

                <div class="field <?= isset($fouten['masterclass']) ? 'has-error' : '' ?>">
                    <label for="masterclass">Masterclass *</label>
                    <select name="masterclass" id="masterclass" required>
                        <option value="">-- Kies een masterclass --</option>
                        <?php foreach ($masterclasses as $id => $class): ?>
                            <option value="<?= e($id) ?>"
                                    data-prijs="<?= $class['prijs'] ?>"
                                    data-plekken="<?= $class['plekken'] ?>"
                                    data-datum="<?= e(datumNL($class['datum'])) ?>"
                                    <?= $invoer['masterclass'] === $id ? 'selected' : '' ?>>
                                <?= e($class['titel']) ?> – <?= datumNL($class['datum']) ?> (<?= prijs($class['prijs']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?= fout('masterclass', $fouten) ?>
                </div>

                <div class="field-row">
                    <div class="field <?= isset($fouten['voornaam']) ? 'has-error' : '' ?>">
                        <label for="voornaam">Voornaam *</label>
                        <input type="text" name="voornaam" id="voornaam" value="<?= e($invoer['voornaam']) ?>" maxlength="50" autocomplete="given-name" required>
                        <?= fout('voornaam', $fouten) ?>
                    </div>
                    <div class="field <?= isset($fouten['achternaam']) ? 'has-error' : '' ?>">
                        <label for="achternaam">Achternaam *</label>
                        <input type="text" name="achternaam" id="achternaam" value="<?= e($invoer['achternaam']) ?>" maxlength="80" autocomplete="family-name" required>
                        <?= fout('achternaam', $fouten) ?>
                    </div>
                </div>

                <div class="field-row">
                    <div class="field <?= isset($fouten['email']) ? 'has-error' : '' ?>">
                        <label for="email">E-mailadres *</label>
                        <input type="email" name="email" id="email" value="<?= e($invoer['email']) ?>" autocomplete="email" required>
                        <?= fout('email', $fouten) ?>
                    </div>
                    <div class="field <?= isset($fouten['telefoon']) ? 'has-error' : '' ?>">
                        <label for="telefoon">Telefoon <small>(optioneel)</small></label>
                        <input type="tel" name="telefoon" id="telefoon" value="<?= e($invoer['telefoon']) ?>" autocomplete="tel">
                        <?= fout('telefoon', $fouten) ?>
                    </div>
                </div>

                <div class="field <?= isset($fouten['personen']) ? 'has-error' : '' ?>">
                    <label for="personen">Aantal personen *</label>
                    <select name="personen" id="personen">
                        <?php for ($i = 1; $i <= $maxPersonen; $i++): ?>
                            <option value="<?= $i ?>" <?= (int) $invoer['personen'] === $i ? 'selected' : '' ?>>
                                <?= $i ?> <?= $i === 1 ? 'persoon' : 'personen' ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                    <?= fout('personen', $fouten) ?>
                </div>

                <fieldset class="field <?= isset($fouten['ervaring']) ? 'has-error' : '' ?>">
                    <legend>Hoeveel ervaring heb je met een kamado? *</legend>
                    <div class="radio-group">
                        <?php foreach (['geen' => 'Geen', 'beetje' => 'Een beetje', 'veel' => 'Veel'] as $waarde => $tekst): ?>
                            <label class="radio">
                                <input type="radio" name="ervaring" value="<?= $waarde ?>" <?= $invoer['ervaring'] === $waarde ? 'checked' : '' ?>>
                                <?= $tekst ?>
                            </label>
                        <?php endforeach; ?>
                    </div>
                    <?= fout('ervaring', $fouten) ?>
                </fieldset>

                <div class="field <?= isset($fouten['wensen']) ? 'has-error' : '' ?>">
                    <label for="wensen">Dieetwensen of opmerkingen <small>(optioneel)</small></label>
                    <textarea name="wensen" id="wensen" rows="4" maxlength="500"><?= e($invoer['wensen']) ?></textarea>
                    <p class="char-count"><span id="tekens">0</span> / 500 tekens</p>
                    <?= fout('wensen', $fouten) ?>
                </div>

                <!-- Honeypot tegen spam: niet zichtbaar voor bezoekers -->
                <div class="hp" aria-hidden="true">
                    <label for="website">Laat dit veld leeg</label>
                    <input type="text" name="website" id="website" tabindex="-1" autocomplete="off">
                </div>

                <div class="field <?= isset($fouten['akkoord']) ? 'has-error' : '' ?>">
                    <label class="checkbox">
                        <input type="checkbox" name="akkoord" value="ja" <?= $invoer['akkoord'] === 'ja' ? 'checked' : '' ?>>
                        <?= e(cmsVeld($fpc_obj, 'akkoord', 'Ik ga akkoord dat Kamado Breda mijn gegevens gebruikt om deze inschrijving te verwerken.')) ?> *
                    </label>
                    <?= fout('akkoord', $fouten) ?>
                </div>

                <button type="submit" class="btn btn-primary btn-large"><?= e(cmsVeld($fpc_obj, 'knop', 'Inschrijven')) ?></button>
                <p class="note">* verplicht veld</p>
            </form>

            <aside class="summary" aria-live="polite">
                <h3>Jouw inschrijving</h3>
                <dl>
                    <dt>Masterclass</dt>
                    <dd id="sum-class">Nog niet gekozen</dd>
                    <dt>Datum</dt>
                    <dd id="sum-datum">–</dd>
                    <dt>Aantal personen</dt>
                    <dd id="sum-personen">1</dd>
                </dl>
                <p class="summary-total">Totaal <span id="sum-totaal">€ 0,-</span></p>
                <p class="note" id="sum-plekken"></p>
            </aside>
        </div>
    </div>
</section>
