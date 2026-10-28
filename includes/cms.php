<?php
defined('KAMADO_BREDA') || exit;

// Koppeling met KBS.CMS (beheer op /admin).
// Alle inhoud van de site staat in het CMS:
// - Beheer > Front-end page content: de blokken van elke pagina (home, kamados, masterclasses, ...).
//   Elk blok heeft een layout uit assets/sections/kamado-*.php, met eigen velden (zie de uitleg bovenin die bestanden).
//   In de blokken staan ook de producten, accessoires, masterclasses en fotobronnen.
// - Beheer > SEO: beschrijving, zoekwoorden, robots en auteur per pagina.
// - Beheer > System settings: telefoonnummer en e-mailadres (footer).
// De naam van een pagina in het CMS is de URL: "index" (home), "kamados", "masterclasses", ...

// De kern van KBS.CMS laden (database, instellingen, pagina's en content). Eén keer per verzoek.
function cmsLaden()
{
    static $geladen = null;
    if ($geladen !== null) {
        return $geladen;
    }

    $geladen = false;
    $databaseConfig = __DIR__ . '/../database.config';
    if (!is_file($databaseConfig) || !parse_ini_file($databaseConfig, true)) {
        return false;
    }
    try {
        require_once __DIR__ . '/../php/cmsRoot.php';
        require_once CMS_ROOT . '/core/Database.php';
        require_once CMS_ROOT . '/core/defs.php';
        require_once CMS_ROOT . '/core/classFrontPages.php';
        require_once CMS_ROOT . '/core/classFrontendPagesContent.php';
        require_once __DIR__ . '/../php/content_methods.php';
        $geladen = true;
    } catch (Throwable $fout) {
        error_log('KBS.CMS niet bereikbaar: ' . $fout->getMessage());
    }
    return $geladen;
}

// ---------- Content-blokken ----------

// Alle blokken van de site: naam van de pagina => blokken op volgorde (sortOrder)
function cmsAlleBlokken()
{
    static $blokken = null;
    if ($blokken !== null) {
        return $blokken;
    }

    $blokken = [];
    if (!cmsLaden()) {
        return $blokken;
    }
    try {
        $paginas = [];
        foreach ((new FrontPages())->getFrontPagesList() as $pagina) {
            $paginas[$pagina->getFrontPageId()] = strtolower($pagina->getFrontPageName());
        }
        foreach ((new FrontendPagesContent())->getfrontendPagesContentList() as $blok) {
            if (isset($paginas[$blok->getFpname()])) {
                $blokken[$paginas[$blok->getFpname()]][] = $blok;
            }
        }
    } catch (Throwable $fout) {
        error_log('KBS.CMS: content niet geladen: ' . $fout->getMessage());
    }
    return $blokken;
}

// Blokken van één pagina
function cmsBlokken($pagina)
{
    return cmsAlleBlokken()[strtolower($pagina)] ?? [];
}

// Eerste blok met deze layout, op welke pagina dan ook (voor de producten, masterclasses, footer, ...)
function cmsBlokMetLayout($layout)
{
    foreach (cmsAlleBlokken() as $blokken) {
        foreach ($blokken as $blok) {
            if ($blok->getFplayout() === $layout) {
                return $blok;
            }
        }
    }
    return null;
}

// Extra instelling van een blok (de velden onder "Layout" in het CMS), of $standaard als die leeg is
function cmsVeld($blok, $sleutel, $standaard = '')
{
    if (!$blok) {
        return $standaard;
    }
    $waarde = cms_extra($blok, $sleutel, $standaard);
    return ($waarde === '' || $waarde === null) ? $standaard : $waarde;
}

// Lijst-instelling van een blok (bijv. de producten), altijd een array met arrays
function cmsLijst($blok, $sleutel)
{
    $lijst = cmsVeld($blok, $sleutel, []);
    return is_array($lijst) ? array_values(array_filter($lijst, 'is_array')) : [];
}

// ---------- Tekst uit het CMS ----------
// Titel, subtitel en content slaat het CMS ge-escaped op; de extra velden staan er letterlijk in.

// Titel of tekst met eenvoudige opmaak (bijv. <br> of <span class="text-fire">) als HTML
function cmsHtml($waarde)
{
    return trim(cms_html($waarde));
}

// Titel of tekst als platte tekst (nog door e() halen bij het tonen)
function cmsTekst($waarde)
{
    return trim(html_entity_decode(strip_tags(cms_html($waarde)), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
}

// Regels van een tekstveld, lege regels weg ("Eén per regel")
function cmsRegels($tekst)
{
    $regels = preg_split('/\R/', (string) $tekst);
    return array_values(array_filter(array_map('trim', $regels), 'strlen'));
}

// Regels "links | rechts" als paren, bijv. specificaties of foto's met omschrijving
function cmsParen($tekst)
{
    $paren = [];
    foreach (cmsRegels($tekst) as $regel) {
        $delen = array_map('trim', explode('|', $regel, 2));
        $paren[] = [$delen[0], $delen[1] ?? ''];
    }
    return $paren;
}

// Bedrag uit een veld ("1299", "59,95") of 0
function cmsBedrag($waarde)
{
    $waarde = str_replace(',', '.', trim((string) $waarde));
    return is_numeric($waarde) ? round((float) $waarde, 2) : 0;
}

// Link uit het CMS: een pagina van de site ("kamados", "masterclasses#inschrijven") of een volledig adres
function cmsLink($url)
{
    $url = trim((string) $url);
    if ($url === '' || preg_match('/^\s*(javascript|data|vbscript):/i', $url)) {
        return './';
    }
    if (preg_match('#^(https?:|mailto:|tel:|\#)#i', $url)) {
        return $url;
    }
    // Relatief aan <base href>, zodat het ook in een submap werkt
    $url = ltrim($url, '/');
    return $url === '' ? './' : $url;
}

// Klassen van een <section> met de instelling "achtergrond" (wit, licht of donker)
function cmsSectieKlasse($blok, $extra = '')
{
    $achtergronden = ['licht' => ' section-light', 'donker' => ' section-dark'];
    return 'section' . ($achtergronden[cmsVeld($blok, 'achtergrond')] ?? '') . ($extra !== '' ? ' ' . $extra : '');
}

// ---------- Foto's ----------
// Foto's van de content staan in images/frontendPagesContent/ (daar zet het CMS ook nieuwe uploads neer).
// Van de meegeleverde foto's staat er een .webp naast en een kleine versie in thumbs/.

function cmsFotoPad($bestand, $thumb = false)
{
    $bestand = basename(html_entity_decode((string) $bestand, ENT_QUOTES | ENT_HTML5, 'UTF-8'));
    if ($bestand === '') {
        return '';
    }
    $map = 'frontendPagesContent/';
    if ($thumb && is_file(__DIR__ . '/../images/' . $map . 'thumbs/' . $bestand)) {
        $map .= 'thumbs/';
    }
    return $map . $bestand;
}

// Foto als <picture> (zie foto() in functions.php)
function cmsFoto($bestand, $alt, $thumb = false, $extra = [])
{
    $pad = cmsFotoPad($bestand, $thumb);
    return $pad === '' ? '' : foto($pad, $alt, $extra);
}

// Url van een foto, plus de WebP-versie als die er is (voor achtergronden en de galerij)
function cmsFotoUrl($bestand, $webp = false)
{
    $pad = cmsFotoPad($bestand);
    if ($pad === '') {
        return '';
    }
    if ($webp) {
        $webpPad = preg_replace('/\.jpe?g$/i', '.webp', $pad);
        return ($webpPad !== $pad && is_file(__DIR__ . '/../images/' . $webpPad)) ? 'images/' . $webpPad : '';
    }
    return 'images/' . $pad;
}

// CSS-achtergrond van een foto (WebP als die er is)
function cmsAchtergrond($bestand)
{
    $jpg = cmsFotoUrl($bestand);
    if ($jpg === '') {
        return '';
    }
    $webp = cmsFotoUrl($bestand, true);
    $css = "background-image: url('" . $jpg . "')";
    if ($webp !== '') {
        $css .= "; background-image: image-set(url('" . $webp . "') type('image/webp'), url('" . $jpg . "') type('image/jpeg'))";
    }
    return $css;
}

// ---------- Systeeminstellingen ----------

// Eerste waarde van een JSON-instelling, bijv. het telefoonnummer of e-mailadres
function cmsInstelling($constante)
{
    if (!defined($constante)) {
        return '';
    }
    $waarden = json_decode((string) constant($constante), true);
    return is_array($waarden) && $waarden ? trim((string) reset($waarden)) : '';
}

// ---------- SEO ----------

// SEO-gegevens van een pagina uit het CMS, of null als er niets is ingevuld
function cmsSeo($route)
{
    static $cache = [];

    // Alleen vaste pagina's, niet bijv. kamados/blue-l (die hebben een eigen beschrijving)
    if (!preg_match('#^[a-z-]*$#', (string) $route)) {
        return null;
    }
    $naam = $route === '' ? 'Index' : ucfirst($route);
    if (array_key_exists($naam, $cache)) {
        return $cache[$naam];
    }

    $cache[$naam] = null;
    if (!cmsLaden()) {
        return null;
    }
    try {
        $frontPages = new FrontPages();
        foreach ($frontPages->getMetaTagsByTitle($naam) as $meta) {
            $cache[$naam] = [
                'beschrijving' => trim((string) $meta->getFrontPageDescription()),
                'zoekwoorden'  => trim((string) $meta->getFrontPageKeywords()),
                'robots'       => implode(', ', array_filter([trim((string) $meta->getFrontPageIndex()), trim((string) $meta->getFrontPageRobots())])),
                'auteur'       => trim((string) $meta->getFrontPageAuthor()),
            ];
        }
    } catch (Throwable $fout) {
        error_log('KBS.CMS: SEO van "' . $naam . '" niet geladen: ' . $fout->getMessage());
    }
    return $cache[$naam];
}
