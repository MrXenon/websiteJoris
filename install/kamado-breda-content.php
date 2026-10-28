<?php
// =========================================================
// Eenmalige import: alle inhoud van de website van Kamado Breda in het CMS zetten.
//
//   php install/kamado-breda-content.php [sectie] [--opnieuw]
//
// sectie     = sectie uit database.config (standaard "localhost"), bijv. "live"
// --opnieuw  = bestaande blokken van deze pagina's eerst verwijderen (anders stopt het script als er al blokken zijn)
//
// Wat het doet:
// 1. Kopieert de foto's van images/ naar images/frontendPagesContent/ (de uploadmap van het CMS), met .webp en thumbs/.
// 2. Maakt de pagina's aan in het CMS (Front Pages): index, kamados, masterclasses, voorwaarden, fotobronnen, footer.
// 3. Zet de blokken met teksten, producten, masterclasses, fotobronnen en de footer in Front-end page content.
// =========================================================
if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}

$front = realpath(__DIR__ . '/..');
$sectie = 'localhost';
$opnieuw = false;
foreach (array_slice($argv, 1) as $argument) {
    if ($argument === '--opnieuw') {
        $opnieuw = true;
    } else {
        $sectie = $argument;
    }
}

$config = parse_ini_file($front . '/database.config', true);
if (!isset($config[$sectie])) {
    exit("Sectie [$sectie] staat niet in database.config\n");
}
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$db = new mysqli($config[$sectie]['DB_SERVER'], $config[$sectie]['DB_USERNAME'], $config[$sectie]['DB_PASSWORD'], $config[$sectie]['DB_DATABASE']);
$db->set_charset('utf8mb4');

// ---------- 1. Foto's ----------
$doel = $front . '/images/frontendPagesContent';
@mkdir($doel . '/thumbs', 0775, true);
$aantalFotos = 0;
foreach (glob($front . '/images/*.{jpg,webp}', GLOB_BRACE) as $bestand) {
    if (preg_match('/^(logo|favicon|apple-touch)/', basename($bestand))) {
        continue;
    }
    copy($bestand, $doel . '/' . basename($bestand));
    $aantalFotos++;
}
foreach (glob($front . '/images/thumbs/*.{jpg,webp}', GLOB_BRACE) as $bestand) {
    copy($bestand, $doel . '/thumbs/' . basename($bestand));
}
echo "Foto's gekopieerd: $aantalFotos\n";

// ---------- 2. Pagina's ----------
$zoekwoorden = 'kamado, blauwe kamado, kamado bbq, kamado breda, barbecue breda, gietijzer, bbq masterclass, low and slow';
$paginas = ['index' => 'index', 'kamados' => 'index', 'masterclasses' => 'index', 'voorwaarden' => 'index', 'fotobronnen' => 'index', 'footer' => 'noindex'];
$paginaIds = [];
foreach ($paginas as $naam => $index) {
    $zoek = $db->prepare('SELECT pid FROM front_pages WHERE pname = ?');
    $zoek->bind_param('s', $naam);
    $zoek->execute();
    $rij = $zoek->get_result()->fetch_assoc();
    if (!$rij) {
        $robots = $index === 'index' ? 'follow' : 'nofollow';
        $sleutels = $index === 'index' ? $zoekwoorden : '';
        $nieuw = $db->prepare("INSERT INTO front_pages (pname, pcache, pstore, prevalidate, pexpires, pindex, probots, pauthor, pkeywords, pdescription, pmenu)
                               VALUES (?, 'no-cache', 'no-store', 'must-revalidate', 0, ?, ?, 'Kamado Breda', ?, '', 0)");
        $nieuw->bind_param('ssss', $naam, $index, $robots, $sleutels);
        $nieuw->execute();
        $paginaIds[$naam] = $db->insert_id;
        echo "Pagina aangemaakt: $naam\n";
    } else {
        $paginaIds[$naam] = (int) $rij['pid'];
    }
}

$ids = implode(',', $paginaIds);
$bestaand = (int) $db->query("SELECT COUNT(*) FROM front_pages_content WHERE fpname IN ($ids)")->fetch_row()[0];
if ($bestaand > 0 && !$opnieuw) {
    exit("Er staan al $bestaand blokken op deze pagina's. Gebruik --opnieuw om ze te vervangen.\n");
}
$db->query("DELETE FROM front_pages_content WHERE fpname IN ($ids)");

// Telefoon en e-mail van de winkel (System settings, gebruikt in de footer).
// De tabel settings is latin1: het streepje (–) staat daarom als – in de JSON.
$db->query("UPDATE settings SET phone_number = '{\"Algemeen\":\"076 \\\\u2013 000 00 00\"}', email = '{\"Algemeen\":\"info@kamadobreda.nl\"}'");

// ---------- 3. Blokken ----------
// [pagina, layout, subtitel, titel, content (HTML), foto, extra instellingen]
$lorem = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.';
$blokken = [
    // ----- Home -----
    ['index', 'kamado-hero', 'Speciaalzaak in Breda', 'Blauwe kamado\'s.<br><span class="text-fire">Echt vuur.</span>',
        '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>',
        'winkel-kamado-breda.jpg', [
            'knoppen'   => [
                ['tekst' => 'Shop de kamado\'s', 'link' => 'kamados', 'stijl' => 'primary'],
                ['tekst' => 'Masterclasses', 'link' => 'masterclasses', 'stijl' => 'ghost'],
            ],
            'voordelen' => "Tot levenslang garantie op keramiek\nBezorging in regio Breda\nVeilig betalen met iDEAL",
        ]],
    ['index', 'kamado-voordelen', '', 'Voordelen', '', '', [
        'voordelen' => [
            ['icoon' => 'gereedschap', 'titel' => 'Op maat gemaakt', 'tekst' => $lorem],
            ['icoon' => 'vlam', 'titel' => 'Gietijzeren accessoires', 'tekst' => 'Ut enim ad minim veniam, quis nostrud exercitation.'],
            ['icoon' => 'ster', 'titel' => 'Masterclasses', 'tekst' => 'Duis aute irure dolor in reprehenderit in voluptate.'],
        ],
    ]],
    ['index', 'kamado-kamados-uitgelicht', 'Het assortiment', 'Onze kamado\'s',
        '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam quis risus eget urna mollis ornare vel eu leo.</p>', '',
        ['link' => ['tekst' => 'Alles bekijken', 'link' => 'kamados']]],
    ['index', 'kamado-masterclasses-uitgelicht', 'Leren van de pitmaster', 'Komende masterclasses',
        '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas sed diam eget risus varius blandit sit amet non magna.</p>', '',
        ['link' => ['tekst' => 'Hele agenda', 'link' => 'masterclasses'], 'achtergrond' => 'donker']],
    ['index', 'kamado-foto-tekst', 'Over ons', 'Hoi, ik ben Joris',
        '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Aenean eu leo quam. Pellentesque ornare sem lacinia quam venenatis vestibulum.</p>'
        . '<p>Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec ullamcorper nulla non metus auctor fringilla. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor.</p>'
        . '<blockquote>“Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor.” <cite>— Joris, eigenaar</cite></blockquote>',
        'joris.jpg', [
            'fotoAlt'     => 'Joris, eigenaar van Kamado Breda, voor zijn winkel',
            'fotoPositie' => 'links',
            'knop'        => ['tekst' => 'Kom langs in de winkel', 'link' => 'kamados'],
        ]],
    ['index', 'kamado-oproep', '', 'Klaar voor je eerste low & slow?', '<p>' . $lorem . '</p>', '',
        ['knop' => ['tekst' => 'Schrijf je in', 'link' => 'masterclasses#inschrijven']]],

    // ----- Kamado's -----
    ['kamados', 'kamado-paginatitel', 'Het assortiment', 'Onze blauwe kamado\'s',
        '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec sed odio dui. Cum sociis natoque penatibus et magnis dis parturient montes.</p>', '', []],
    ['kamados', 'kamado-galerij', '', 'Fotogalerij', '', '', [
        'fotos' => [
            ['foto' => 'kamado-blue-l.jpg', 'alt' => 'Kamado Breda Blue L met houten zijtafels'],
            ['foto' => 'kamado-blue-xl.jpg', 'alt' => 'Kamado Breda Blue XL'],
            ['foto' => 'kamado-blue-m.jpg', 'alt' => 'Kamado Breda Blue M'],
            ['foto' => 'detail-thermometer.jpg', 'alt' => 'Thermometer op het blauwe deksel'],
            ['foto' => 'detail-deksel-handvat.jpg', 'alt' => 'Houten handvat met Kamado Breda logo'],
            ['foto' => 'actie-ribs.jpg', 'alt' => 'Spareribs op het rooster van een kamado'],
            ['foto' => 'actie-kipspiesjes.jpg', 'alt' => 'Kipspiesjes boven gloeiende kolen'],
            ['foto' => 'winkel-gietijzer-schap.jpg', 'alt' => 'Schap met gietijzeren pannen in de winkel'],
        ],
    ]],
    ['kamados', 'kamado-kamados', '', 'Kies jouw formaat',
        '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam porta sem malesuada magna mollis euismod.</p>', '', [
            'anchor'      => 'kamados',
            'achtergrond' => 'licht',
            'voordelen'   => "Op voorraad in de winkel\nGratis bezorging in regio Breda of ophalen in de winkel\nVeilig betalen met iDEAL, creditcard en meer",
            'producten'   => [
                [
                    'id' => 'blue-m', 'naam' => 'Kamado Breda Blue M', 'kort' => 'Compact, ideaal voor balkon en kleine tuin.',
                    'prijs' => '899', 'badge' => 'Starter', 'foto' => 'kamado-blue-m.jpg',
                    'tekst' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Donec ullamcorper nulla non metus auctor fringilla. Cras mattis consectetur purus sit amet fermentum.',
                    'kenmerken' => "Lorem ipsum dolor sit amet\nConsectetur adipiscing elit\nSed do eiusmod tempor incididunt\nUt labore et dolore magna aliqua",
                    'specs' => "Roosterdiameter | 40 cm\nHoogte (incl. onderstel) | 105 cm\nGewicht | 62 kg\nGeschikt voor | 2 – 4 personen\nMax. temperatuur | 400 °C\nGarantie keramiek | 10 jaar",
                    'galerij' => "kamado-blue-m.jpg | Kamado Breda Blue M op onderstel\ndetail-thermometer.jpg | Thermometer op het blauwe deksel\nactie-kipspiesjes.jpg | Kipspiesjes op het rooster boven gloeiende kolen\nwinkel-kamado-breda.jpg | De Blue M in de winkel van Kamado Breda",
                ],
                [
                    'id' => 'blue-l', 'naam' => 'Kamado Breda Blue L', 'kort' => 'Onze bestseller voor het hele gezin.',
                    'prijs' => '1299', 'badge' => 'Bestseller', 'foto' => 'kamado-blue-l.jpg',
                    'tekst' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum id ligula porta felis euismod semper. Maecenas faucibus mollis interdum. Nullam quis risus eget urna mollis ornare vel eu leo. Aenean lacinia bibendum nulla sed consectetur.',
                    'kenmerken' => "Lorem ipsum dolor sit amet\nNulla vitae elit libero\nPraesent commodo cursus magna\nVel scelerisque nisl consectetur\nEtiam porta sem malesuada magna",
                    'specs' => "Roosterdiameter | 53 cm\nHoogte (incl. onderstel) | 118 cm\nGewicht | 96 kg\nGeschikt voor | 4 – 8 personen\nMax. temperatuur | 400 °C\nGarantie keramiek | 10 jaar",
                    'galerij' => "kamado-blue-l.jpg | Kamado Breda Blue L met houten zijtafels\ndetail-deksel-handvat.jpg | Houten handvat met Kamado Breda logo\ndetail-thermometer.jpg | Thermometer op het blauwe deksel\nactie-ribs.jpg | Spareribs op het rooster van een kamado\nactie-steak.jpg | Steak en asperges op de grill",
                ],
                [
                    'id' => 'blue-xl', 'naam' => 'Kamado Breda Blue XL', 'kort' => 'Voor de echte pitmaster en grote groepen.',
                    'prijs' => '1849', 'badge' => 'Nieuw', 'foto' => 'kamado-blue-xl.jpg',
                    'tekst' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur blandit tempus porttitor. Donec sed odio dui. Morbi leo risus, porta ac consectetur ac, vestibulum at eros. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh.',
                    'kenmerken' => "Lorem ipsum dolor sit amet\nCras justo odio dapibus ac facilisis\nDuis mollis est non commodo luctus\nNisi erat porttitor ligula",
                    'specs' => "Roosterdiameter | 61 cm\nHoogte (incl. onderstel) | 126 cm\nGewicht | 138 kg\nGeschikt voor | 8 – 14 personen\nMax. temperatuur | 400 °C\nGarantie keramiek | Levenslang",
                    'galerij' => "kamado-blue-xl.jpg | Kamado Breda Blue XL met houten handvat\nactie-ribs.jpg | Spareribs op het rooster van een kamado\nmasterclass-brisket.jpg | Gesneden brisket met mooie rookring\njoris.jpg | Joris naast de Blue XL voor zijn winkel",
                ],
            ],
        ]],
    ['kamados', 'kamado-accessoires', 'Maak het compleet', 'Gietijzer & accessoires',
        '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum id ligula porta felis euismod semper.</p>', '', [
            'anchor'    => 'accessoires',
            'opmerking' => 'Bestel online en laat het bezorgen, of haal je bestelling gratis op in de winkel aan de Voorbeeldstraat in Breda.',
            'producten' => [
                ['id' => 'skillet', 'naam' => 'Gietijzeren skillet', 'prijs' => '59', 'foto' => 'accessoire-skillets.jpg', 'tekst' => $lorem],
                ['id' => 'gietijzer-set', 'naam' => 'Gietijzer set', 'prijs' => '129', 'foto' => 'accessoire-gietijzer.jpg', 'tekst' => 'Sed posuere consectetur est at lobortis.'],
                ['id' => 'plancha', 'naam' => 'Plancha / grillpan', 'prijs' => '79', 'foto' => 'accessoire-skillet-gebruik.jpg', 'tekst' => 'Vestibulum id ligula porta felis euismod semper.'],
                ['id' => 'houtskool-10kg', 'naam' => 'Houtskool 10 kg', 'prijs' => '24', 'foto' => 'accessoire-houtskool.jpg', 'tekst' => 'Maecenas sed diam eget risus varius blandit.'],
            ],
        ]],

    // ----- Masterclasses -----
    ['masterclasses', 'kamado-paginatitel', 'Leer het van Joris', 'BBQ-masterclasses',
        '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed posuere consectetur est at lobortis. Morbi leo risus, porta ac consectetur ac, vestibulum at eros.</p>',
        'masterclass-brisket-folie.jpg', []],
    ['masterclasses', 'kamado-masterclasses', '', 'Agenda',
        '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Alle masterclasses vinden plaats in de winkel en op het terras in Breda.</p>', '', [
            'geenMasterclasses' => 'Er staan op dit moment geen masterclasses gepland. Kijk binnenkort nog eens!',
            'masterclasses'     => [
                ['id' => 'basics', 'titel' => 'Kamado Basics', 'datum' => '2026-10-24', 'tijd' => '10:00 – 15:00', 'prijs' => '95', 'plekken' => '4', 'niveau' => 'Beginner', 'foto' => 'actie-kipspiesjes.jpg',
                 'tekst' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean eu leo quam. Pellentesque ornare sem lacinia quam venenatis vestibulum.'],
                ['id' => 'low-slow', 'titel' => 'Low & Slow: Brisket & Ribs', 'datum' => '2026-11-14', 'tijd' => '09:00 – 17:00', 'prijs' => '149', 'plekken' => '7', 'niveau' => 'Gevorderd', 'foto' => 'masterclass-brisket.jpg',
                 'tekst' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies.'],
                ['id' => 'winter', 'titel' => 'Winter BBQ & Gietijzer', 'datum' => '2026-12-12', 'tijd' => '12:00 – 17:00', 'prijs' => '119', 'plekken' => '10', 'niveau' => 'Alle niveaus', 'foto' => 'accessoire-skillet-gebruik.jpg',
                 'tekst' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras mattis consectetur purus sit amet fermentum. Maecenas faucibus mollis interdum.'],
            ],
        ]],
    ['masterclasses', 'kamado-foto-tekst', 'Wat kun je verwachten?', 'Een dag vol vuur en vlees',
        '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec ullamcorper nulla non metus auctor fringilla. Nullam id dolor id nibh ultricies vehicula ut id elit.</p>'
        . '<ul><li>Lorem ipsum dolor sit amet</li><li>Consectetur adipiscing elit</li><li>Inclusief eten en drinken</li><li>Recepten om mee naar huis te nemen</li></ul>',
        'masterclass-bbq-borden.jpg', [
            'fotoAlt'     => 'Borden met brisket, worst en bijgerechten',
            'fotoPositie' => 'rechts',
            'achtergrond' => 'licht',
        ]],
    ['masterclasses', 'kamado-inschrijven', 'Doe mee', 'Inschrijven',
        '<p>Vul het formulier in. Je krijgt direct een bevestiging te zien.</p>', '', [
            'maxPersonen' => '4',
            'akkoord'     => 'Ik ga akkoord dat Kamado Breda mijn gegevens gebruikt om deze inschrijving te verwerken.',
            'knop'        => 'Inschrijven',
        ]],

    // ----- Voorwaarden -----
    ['voorwaarden', 'kamado-paginatitel', 'Webshop', 'Bezorgen, retourneren & voorwaarden', '', '', []],
    ['voorwaarden', 'kamado-tekst', '', '',
        '<h2>Bezorgen en afhalen</h2>'
        . '<p>Kamado\'s bezorgen we zelf in de regio Breda. Na je bestelling bellen we je binnen 2 werkdagen om een afspraak te maken. Accessoires versturen we door heel Nederland voor {verzendkosten}; vanaf {gratis_bezorging_vanaf} is bezorgen gratis.</p>'
        . '<p>Liever ophalen? Dat is altijd gratis. Je bestelling ligt vanaf de volgende werkdag klaar in de winkel aan de Voorbeeldstraat 12 in Breda.</p>'
        . '<h2>Betalen</h2>'
        . '<p>Je betaalt veilig online via Mollie met iDEAL, Bancontact of creditcard. Haal je je bestelling op in de winkel, dan kun je ook daar betalen met pin of contant. Alle prijzen zijn inclusief 21% btw.</p>'
        . '<h2>Herroepingsrecht en retourneren</h2>'
        . '<p>Je hebt 14 dagen bedenktijd vanaf het moment dat je je bestelling hebt ontvangen. Wil je iets terugsturen of terugbrengen? Mail ons via <a href="mailto:info@kamadobreda.nl">info@kamadobreda.nl</a>. We betalen het aankoopbedrag binnen 14 dagen terug. Het product moet ongebruikt zijn en in de originele verpakking zitten.</p>'
        . '<h2>Garantie</h2>'
        . '<p>Op het keramiek van de Blue M en Blue L zit 10 jaar garantie, op de Blue XL levenslang. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>'
        . '<h2>Privacy</h2>'
        . '<p>We gebruiken je gegevens alleen om je bestelling te verwerken en te bezorgen. We delen ze niet met anderen, behalve met onze betaalprovider om je betaling af te handelen.</p>'
        . '<h2>Contact</h2>'
        . '<p>Kamado Breda &middot; Voorbeeldstraat 12, 4811 AB Breda &middot; <a href="tel:+31760000000">076 – 000 00 00</a> &middot; <a href="mailto:info@kamadobreda.nl">info@kamadobreda.nl</a></p>',
        '', []],

    // ----- Fotobronnen -----
    ['fotobronnen', 'kamado-fotobronnen', '', 'Fotobronnen',
        '<p>Een deel van de foto\'s op deze website komt van Wikimedia Commons. Ze zijn bijgesneden en verkleind. Hieronder staat per foto wie hem gemaakt heeft en onder welke licentie.</p>', '', [
            'bronnen' => [
                ['bestand' => 'winkel-kamado-breda.jpg, kamado-blue-*.jpg, detail-*.jpg, joris.jpg, winkel-*.jpg', 'maker' => 'Kamado Breda (aangeleverd door Joris)', 'licentie' => 'Eigendom klant', 'url' => ''],
                ['bestand' => 'actie-ribs.jpg', 'maker' => 'Corvair Owner', 'licentie' => 'CC BY-SA 2.0', 'url' => 'https://commons.wikimedia.org/wiki/File:Smoking_ribs_on_the_Big_Green_Egg.jpg'],
                ['bestand' => 'actie-kipspiesjes.jpg', 'maker' => 'Corvair Owner', 'licentie' => 'CC BY-SA 2.0', 'url' => 'https://commons.wikimedia.org/wiki/File:Chicken_Shis-ka-bob_on_the_Big_Green_Egg.jpg'],
                ['bestand' => 'actie-steak.jpg', 'maker' => 'Corvair Owner', 'licentie' => 'CC BY-SA 2.0', 'url' => 'https://commons.wikimedia.org/wiki/File:Steak_and_asparagus_on_the_grill.jpg'],
                ['bestand' => 'masterclass-brisket-folie.jpg', 'maker' => 'Corvair Owner', 'licentie' => 'CC BY-SA 2.0', 'url' => 'https://commons.wikimedia.org/wiki/File:Barbequed_brisket_on_the_Big_Green_Egg.jpg'],
                ['bestand' => 'masterclass-brisket.jpg', 'maker' => 'Johnsparks75', 'licentie' => 'CC BY-SA 4.0', 'url' => 'https://commons.wikimedia.org/wiki/File:Smoked_Brisket_with_smoke_ring.jpg'],
                ['bestand' => 'masterclass-bbq-borden.jpg', 'maker' => 'Kent Wang', 'licentie' => 'CC BY-SA 2.0', 'url' => 'https://commons.wikimedia.org/wiki/File:Plates_of_food_from_Black%27s_Barbecue.jpg'],
                ['bestand' => 'accessoire-gietijzer.jpg', 'maker' => 'Edsel Little', 'licentie' => 'CC BY-SA 2.0', 'url' => 'https://commons.wikimedia.org/wiki/File:Cast_Iron_Gathering_(19817120091).jpg'],
                ['bestand' => 'accessoire-skillets.jpg', 'maker' => 'FiveRings (en.wikipedia)', 'licentie' => 'Publiek domein', 'url' => 'https://commons.wikimedia.org/wiki/File:Castiron-skillets.jpg'],
                ['bestand' => 'accessoire-skillet-gebruik.jpg', 'maker' => 'Taylorderek', 'licentie' => 'CC BY 4.0', 'url' => 'https://commons.wikimedia.org/wiki/File:Seared_and_roasted_Pork_Tenderloin_in_Cast_Iron_Skillet.jpg'],
                ['bestand' => 'accessoire-houtskool.jpg', 'maker' => 'Annelieke B', 'licentie' => 'CC BY-SA 2.0', 'url' => 'https://commons.wikimedia.org/wiki/File:Charcoal_lump.jpg'],
            ],
        ]],

    // ----- Footer (op elke pagina) -----
    ['footer', 'kamado-footer', '', 'Footer',
        '<p>Speciaalzaak in blauwe kamado\'s, gietijzer en masterclasses voor beginnende pitmasters.</p>', '', [
            'adres'          => "Voorbeeldstraat 12\n4811 AB Breda",
            'openingstijden' => [
                ['dagen' => 'di – vr', 'tijden' => '10:00 – 18:00'],
                ['dagen' => 'zaterdag', 'tijden' => '09:30 – 17:00'],
                ['dagen' => 'zo & ma', 'tijden' => 'gesloten'],
            ],
            'webshop'        => [
                ['tekst' => 'Kamado\'s', 'link' => 'kamados'],
                ['tekst' => 'Gietijzer & accessoires', 'link' => 'kamados#accessoires'],
                ['tekst' => 'Winkelwagen', 'link' => 'winkelwagen'],
                ['tekst' => 'Bezorgen & retourneren', 'link' => 'voorwaarden'],
            ],
            'onderaan'       => [
                ['tekst' => 'Voorwaarden', 'link' => 'voorwaarden'],
                ['tekst' => 'Fotobronnen', 'link' => 'fotobronnen'],
                ['tekst' => 'Stijlgids', 'link' => 'stijlgids'],
                ['tekst' => 'Wireframes', 'link' => 'docs/wireframes.html'],
            ],
            'betalen'        => 'Veilig betalen met iDEAL, creditcard en meer',
        ]],
];

// Zo slaat het CMS titels en teksten op (FILTER_SANITIZE_SPECIAL_CHARS); de extra instellingen als JSON
$cmsTekst = function ($tekst) {
    return htmlspecialchars($tekst, ENT_QUOTES | ENT_HTML5, 'UTF-8');
};
$invoegen = $db->prepare('INSERT INTO front_pages_content (fpname, fptitle, fpcontent, fpimage, numberOfColumn, sortOrder, columnOrder, fplayout, fpsubtitle, fpextra)
                          VALUES (?, ?, ?, ?, 1, ?, 1, ?, ?, ?)');
$volgorde = [];
foreach ($blokken as [$pagina, $layout, $subtitel, $titel, $content, $foto, $extra]) {
    $volgorde[$pagina] = ($volgorde[$pagina] ?? 0) + 10;
    $pid = $paginaIds[$pagina];
    $titelOpslag = $cmsTekst($titel);
    $contentOpslag = $cmsTekst($content);
    $subtitelOpslag = $cmsTekst($subtitel);
    $extraOpslag = $extra ? json_encode($extra, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) : '';
    $invoegen->bind_param('isssisss', $pid, $titelOpslag, $contentOpslag, $foto, $volgorde[$pagina], $layout, $subtitelOpslag, $extraOpslag);
    $invoegen->execute();
}
echo 'Blokken toegevoegd: ' . count($blokken) . "\n";
