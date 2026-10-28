<?php defined('KAMADO_BREDA') || exit; ?>
</main>

<footer class="site-footer">
    <div class="container footer-grid">
        <div class="footer-brand">
            <picture>
                <source srcset="images/logo-md.webp" type="image/webp">
                <img src="images/logo-md.png" alt="Logo Kamado Breda" width="110" height="109" class="footer-logo" loading="lazy">
            </picture>
            <p>Speciaalzaak in blauwe kamado's, gietijzer en masterclasses voor beginnende pitmasters.</p>
        </div>

        <div>
            <h2 class="footer-title">Winkel</h2>
            <address>
                Voorbeeldstraat 12<br>
                4811 AB Breda
            </address>
            <ul class="openingstijden">
                <li><span>di – vr</span> 10:00 – 18:00</li>
                <li><span>zaterdag</span> 09:30 – 17:00</li>
                <li><span>zo &amp; ma</span> gesloten</li>
            </ul>
        </div>

        <div>
            <h2 class="footer-title">Contact</h2>
            <ul>
                <li><a href="tel:+31760000000">076 – 000 00 00</a></li>
                <li><a href="mailto:info@kamadobreda.nl">info@kamadobreda.nl</a></li>
            </ul>
        </div>

        <div>
            <h2 class="footer-title">Webshop</h2>
            <ul>
                <li><a href="kamados">Kamado's</a></li>
                <li><a href="kamados#accessoires">Gietijzer &amp; accessoires</a></li>
                <li><a href="winkelwagen">Winkelwagen</a></li>
                <li><a href="voorwaarden">Bezorgen &amp; retourneren</a></li>
            </ul>
        </div>
    </div>

    <div class="footer-bottom">
        <div class="container footer-bottom-inner">
            <p>&copy; <?= date('Y') ?> Kamado Breda</p>
            <p>
                <a href="voorwaarden">Voorwaarden</a> &middot;
                <a href="fotobronnen">Fotobronnen</a> &middot;
                <a href="stijlgids">Stijlgids</a> &middot;
                <a href="docs/wireframes.html">Wireframes</a>
            </p>
            <p class="payment-note">Veilig betalen met iDEAL, creditcard en meer</p>
        </div>
    </div>
</footer>

<div class="toast" data-toast role="status" aria-live="polite" hidden></div>
</body>
</html>
