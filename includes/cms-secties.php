<?php
defined('KAMADO_BREDA') || exit;

// Toont de blokken van een pagina uit het CMS, op volgorde (Beheer > Front-end page content).
// Verwacht $cmsPagina: de naam van de pagina in het CMS, bijv. 'index' of 'kamados'.
// Elk blok wordt getoond met zijn layout uit assets/sections/kamado-*.php. Een blok zonder layout
// ("default") wordt als gewone tekst getoond. De algemene KBS-layouts horen niet bij deze vormgeving.
foreach (cmsBlokken($cmsPagina) as $fpc_obj) {
    $layout = basename((string) $fpc_obj->getFplayout()) ?: 'kamado-tekst';
    $sectie = __DIR__ . '/../assets/sections/' . $layout . '.php';
    // De footer staat op elke pagina onderaan (includes/footer.php), niet tussen de blokken
    if (str_starts_with($layout, 'kamado-') && $layout !== 'kamado-footer' && is_file($sectie)) {
        include $sectie;
    }
}
