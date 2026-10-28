<?php
/**
 * Kamado Breda – footer: de inhoud van de footer onderaan elke pagina.
 * Zet dit blok op de pagina "footer" (niet op een gewone pagina). Telefoon en e-mail komen uit System settings.
 * Content = korte tekst onder het logo.
 * Preview: generic
 * Setting: adres | textarea | Adres van de winkel | Elke regel komt op een eigen regel
 * Setting: openingstijden | list | Openingstijden
 * Setting: openingstijden[].dagen | text | Dag(en) | Bijv. "di – vr"
 * Setting: openingstijden[].tijden | text | Tijden | Bijv. "10:00 – 18:00" of "gesloten"
 * Setting: webshop | list | Links onder "Webshop"
 * Setting: webshop[].tekst | text | Tekst
 * Setting: webshop[].link | url | Link
 * Setting: onderaan | list | Links helemaal onderaan
 * Setting: onderaan[].tekst | text | Tekst
 * Setting: onderaan[].link | url | Link
 * Setting: betalen | text | Tekst over betalen (rechtsonder)
 */
defined('KAMADO_BREDA') || exit;
// Wordt geladen door includes/footer.php, met $fpc_obj = dit blok (of null als het blok er niet is)
$footerTelefoon = cmsInstelling('PHONE_NUMBERS_JSON');
$footerEmail = cmsInstelling('EMAILS_JSON');
$footerLinks = function ($links) {
    return implode(' &middot; ', array_map(function ($link) {
        return '<a href="' . e(cmsLink($link['link'] ?? '')) . '">' . e($link['tekst'] ?? '') . '</a>';
    }, $links));
};
?>
<footer class="site-footer">
    <div class="container footer-grid">
        <div class="footer-brand">
            <picture>
                <source srcset="images/logo-md.webp" type="image/webp">
                <img src="images/logo-md.png" alt="Logo Kamado Breda" width="110" height="109" class="footer-logo" loading="lazy">
            </picture>
            <?= $fpc_obj ? cms_content($fpc_obj->getFpcontent()) : '' ?>
        </div>

        <div>
            <h2 class="footer-title">Winkel</h2>
            <?php if (cmsRegels(cmsVeld($fpc_obj, 'adres'))): ?>
                <address><?= implode('<br>', array_map('e', cmsRegels(cmsVeld($fpc_obj, 'adres')))) ?></address>
            <?php endif; ?>
            <?php if (cmsLijst($fpc_obj, 'openingstijden')): ?>
                <ul class="openingstijden">
                    <?php foreach (cmsLijst($fpc_obj, 'openingstijden') as $openingstijd): ?>
                        <li><span><?= e($openingstijd['dagen'] ?? '') ?></span> <?= e($openingstijd['tijden'] ?? '') ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>

        <div>
            <h2 class="footer-title">Contact</h2>
            <ul>
                <?php if ($footerTelefoon !== ''): ?>
                    <li><a href="tel:<?= e(preg_replace('/^0/', '+31', preg_replace('/[^0-9+]/', '', $footerTelefoon))) ?>"><?= e($footerTelefoon) ?></a></li>
                <?php endif; ?>
                <?php if ($footerEmail !== ''): ?>
                    <li><a href="mailto:<?= e($footerEmail) ?>"><?= e($footerEmail) ?></a></li>
                <?php endif; ?>
            </ul>
        </div>

        <?php if (cmsLijst($fpc_obj, 'webshop')): ?>
            <div>
                <h2 class="footer-title">Webshop</h2>
                <ul>
                    <?php foreach (cmsLijst($fpc_obj, 'webshop') as $link): ?>
                        <li><a href="<?= e(cmsLink($link['link'] ?? '')) ?>"><?= e($link['tekst'] ?? '') ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
    </div>

    <div class="footer-bottom">
        <div class="container footer-bottom-inner">
            <p>&copy; <?= date('Y') ?> Kamado Breda</p>
            <p><?= $footerLinks(cmsLijst($fpc_obj, 'onderaan')) ?></p>
            <?php if (cmsVeld($fpc_obj, 'betalen') !== ''): ?>
                <p class="payment-note"><?= e(cmsVeld($fpc_obj, 'betalen')) ?></p>
            <?php endif; ?>
        </div>
    </div>
</footer>
