<?php
defined('KAMADO_BREDA') || exit;

// Sitemap voor zoekmachines: /sitemap.xml
$paginas = ['', 'kamados', 'masterclasses', 'voorwaarden', 'fotobronnen'];
foreach ($kamados as $id => $kamado) {
    $paginas[] = 'kamados/' . $id;
}

header('Content-Type: application/xml; charset=UTF-8');
echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<?php foreach ($paginas as $pagina): ?>
    <url><loc><?= e(siteUrl($pagina)) ?></loc></url>
<?php endforeach; ?>
</urlset>
