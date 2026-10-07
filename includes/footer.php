</main>

<footer class="site-footer">
    <div class="container footer-grid">
        <div>
            <img src="images/logo.png" alt="Logo Kamado Breda" width="110" height="107" class="footer-logo">
            <p>Speciaalzaak in blauwe kamado's, gietijzer en masterclasses voor beginnende pitmasters.</p>
        </div>

        <div>
            <h3>Winkel</h3>
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
            <h3>Contact</h3>
            <ul>
                <li><a href="tel:+31760000000">076 – 000 00 00</a></li>
                <li><a href="mailto:info@kamadobreda.nl">info@kamadobreda.nl</a></li>
            </ul>
            <h3>Menu</h3>
            <ul>
                <?php foreach ($menu as $link => $tekst): ?>
                    <li><a href="<?= $link ?>"><?= e($tekst) ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <div class="footer-bottom">
        <div class="container">
            <p>&copy; <?= date('Y') ?> Kamado Breda &middot; <a href="fotobronnen.php">Fotobronnen</a></p>
        </div>
    </div>
</footer>

<script src="js/menu.js"></script>
<?php
// Extra scripts per pagina, bijv. $scripts = ['gallery.js'];
foreach ($scripts ?? [] as $script): ?>
    <script src="js/<?= e($script) ?>"></script>
<?php endforeach; ?>
</body>
</html>
