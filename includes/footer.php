<?php defined('KAMADO_BREDA') || exit; ?>
</main>

<?php
// De inhoud van de footer staat in het CMS: blok met de layout kamado-footer op de pagina "footer"
$fpc_obj = cmsBlokMetLayout('kamado-footer');
include __DIR__ . '/../assets/sections/kamado-footer.php';
?>

<div class="toast" data-toast role="status" aria-live="polite" hidden></div>
</body>
</html>
