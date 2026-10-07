# Procesverslag – Kamado Breda (Challenge "Je eerste klant", sprint 2)

Opdrachtgever: Joris (Kamado Breda) · Werkwijze: individueel · Lokale omgeving: Herd (PHP 8.5) + VS Code

Hieronder staan per taak de stappen die ik heb gedaan, wat ik precies gedaan heb en waarom.

---

## Taak 1 – Opdracht en materiaal van Joris analyseren
1. De challenge-PDF gelezen en de eisen op een rij gezet:
   - homepagina, productpagina (kamado's) en masterclass-pagina
   - `header.php` en `footer.php` dynamisch inladen (DRY)
   - interactieve fotogalerij met JavaScript, zonder de pagina te herladen
   - inschrijfformulier met `POST`, verwerken met `$_POST`, daarna een dynamische bevestigingspagina
   - livegang via FTP (doet Joris/docent zelf, niet in deze opdracht)
2. Het aangeleverde materiaal bekeken: het logo (donker, oranje vuur, blauwe kamado) en één foto van Joris voor zijn winkel.
3. Alles wat Joris aanleverde in de map `aangeleverd/` gezet, zodat het apart staat van de website
   en ik het niet per ongeluk mee upload.

## Taak 2 – Stijlgids en wireframes (desktop + mobiel)
1. Kleuren uit het logo en de foto gehaald: houtskool-zwart, kamado-blauw, vuur-oranje en crème.
2. Lettertypes gekozen: **Oswald** voor koppen (stoer, lijkt op de letters van het logo) en **Source Sans 3** voor tekst (goed leesbaar).
3. Afspraken over knoppen, kaarten, formulieren, afstanden en breakpoints vastgelegd in [docs/stijlgids.md](docs/stijlgids.md).
4. Navigatiestructuur bedacht: *Home – Kamado's – Masterclasses* + een opvallende knop *Inschrijven*.
   Productdetails via `product.php?id=...`, zodat ik maar één bestand nodig heb voor alle kamado's.
5. Wireframes getekend voor alle pagina's, desktop (1200 px) én mobiel (375 px), in grijstinten:
   kruisjes zijn afbeeldingen, grijze balkjes zijn tekst. Blauwe nummertjes verwijzen naar de toelichting ernaast
   (bijv. wat de JavaScript doet of welk PHP-bestand het onderdeel maakt).
   De wireframes staan als afbeelding in [docs/wireframes/](docs/wireframes/):
   - `01-home-desktop.png`
   - `02-home-mobiel.png` (inclusief het opengeklapte hamburgermenu)
   - `03-kamados-desktop.png` (met de interactieve galerij)
   - `04-product.png` (productdetail, desktop + mobiel)
   - `05-masterclass-desktop.png` (agenda + inschrijfformulier)
   - `06-masterclass-mobiel.png` (formulier → bevestigingspagina)

   Ik heb ze getekend in HTML/CSS ([docs/wireframes.html](docs/wireframes.html), daar staan ze ook allemaal onder elkaar) en daarna als PNG geëxporteerd.
   Zo kon ik makkelijk dingen verschuiven en alles even breed maken als de echte site.
6. De **relatie tussen de wireframes** vastgelegd in `00-relaties-wireframes.png`:
   - een stroomdiagram met alle pagina's als miniatuur en pijlen voor elke verbinding
   - drie soorten pijlen: **zwart** = klik op een link/knop, **blauw** = formulier versturen (POST),
     **grijs gestippeld** = PHP stuurt automatisch door (redirect of 404)
   - header en footer staan apart, omdat die op elke pagina zitten (via het menu is elke pagina overal bereikbaar)
   - daaronder een tabel met alle 13 verbindingen: van welke wireframe, via welk element, naar welke wireframe, en wat er gebeurt

   Hierdoor zag ik vóór het bouwen al welke links en parameters ik nodig had
   (bijv. `?id=` voor de productpagina en `?class=` om de juiste masterclass alvast te kiezen),
   en dat de bevestigingspagina een "uitgang" nodig heeft als iemand hem direct opent.

## Taak 3 – Foto's zoeken en optimaliseren
1. Gezocht naar rechtenvrije foto's op **Wikimedia Commons** (Creative Commons / publiek domein).
   Blauwe kamado's waren niet te vinden (alleen groene en rode), daarom heb ik:
   - de **blauwe kamado's uitgesneden uit de foto van Joris** zelf (Blue M, Blue L, Blue XL, details van thermometer en handvat),
   - ook Joris zelf, het schap met gietijzer en het terras uitgesneden voor andere secties,
   - van Commons foto's gebruikt van vlees op de grill (ribs, kipspiesjes, steak, brisket) en accessoires (gietijzer, houtskool).
   Foto's met groene/rode barbecues heb ik bewust **niet** gebruikt, omdat Joris alleen blauwe verkoopt.
2. Foto's geoptimaliseerd met een klein script (PowerShell / System.Drawing):
   - grote foto's max. 1200 px breed (hero 1920 px), JPEG-kwaliteit 80
   - aparte thumbnails van 480 px in `images/thumbs/` voor kaarten en de galerij
   - logo verkleind van 1,5 MB naar ±265 KB, plus een favicon
   - resultaat: de originele foto van 3,3 MB is nu 350 KB als hero
3. Per foto de maker en licentie genoteerd. Die staan op de pagina [fotobronnen.php](fotobronnen.php)
   (verplicht bij CC BY / CC BY-SA licenties).

**Probleem dat ik tegenkwam:** het opslaan van de JPEG's gaf de fout "Ongeldige parameter".
Oorzaak: ik maakte de `EncoderParameter` (voor de kwaliteit) op een verkeerde manier aan. Na een losse test met één plaatje
vond ik de juiste schrijfwijze en werkte het.

## Taak 4 – Mappenstructuur en PHP-templates (DRY)
1. Mappenstructuur opgezet:
   ```
   index.php, kamados.php, product.php, masterclass.php, bevestiging.php, fotobronnen.php
   includes/   header.php, footer.php, data.php, functions.php, gallery.php, kamado-kaart.php, masterclass-kaart.php
   css/        style.css
   js/         menu.js, gallery.js, form.js
   images/     + images/thumbs/
   data/       hier komen de aanmeldingen (afgeschermd)
   docs/       stijlgids en wireframes
   ```
2. `header.php` gemaakt met de `<head>`, het logo en het menu. Elke pagina zet eerst `$paginaTitel` en `$actief`,
   zodat de titel klopt en het juiste menu-item oranje onderstreept wordt.
3. `footer.php` gemaakt met adres, openingstijden, contact en het jaartal via `date('Y')`.
   Pagina-specifieke scripts laad ik via een array `$scripts`, zodat `gallery.js` alleen op pagina's met een galerij geladen wordt.
4. Alle inhoud (kamado's, prijzen, specificaties, masterclasses, data) in **één bestand** gezet: `includes/data.php`.
   De pagina's lopen met `foreach` door deze arrays. Als Joris een prijs wil aanpassen, hoeft dat maar op één plek.
5. Onderdelen die vaker terugkomen als include gemaakt: productkaart, masterclass-kaart en de galerij.
6. `functions.php` met kleine hulpfuncties: `e()` (veilig tekst tonen), `prijs()` (€ 1.299,-) en `datumNL()` (zaterdag 24 oktober 2026).

## Taak 5 – HTML/CSS bouwen (mobile first)
1. Eerst de mobiele stijl geschreven, daarna met `@media (min-width: 700px)` en `(min-width: 1000px)` uitgebreid naar tablet en desktop.
2. Kleuren en lettertypes als CSS-variabelen (`:root`) uit de stijlgids, zodat alles consistent is.
3. Homepagina: hero met de winkelfoto + donkere overlay, USP-balk, kamado-kaarten, masterclass-agenda, "Over Joris" en een call-to-action.
4. Kamado's-pagina: galerij, de drie modellen en de accessoires.
5. Productdetail: kruimelpad, galerij, prijs, kenmerken, specificatietabel en "andere kamado's".
   Bestaat een product niet (bijv. `product.php?id=abc`)? Dan krijg je een nette **404**-pagina.
6. Alle teksten die nog van Joris moeten komen zijn gevuld met **Lorem Ipsum**.

## Taak 6 – JavaScript-interactie
1. **`menu.js`** – hamburgermenu voor mobiel (opent/sluit, `aria-expanded` voor schermlezers) en een schaduw onder de header bij scrollen.
2. **`gallery.js`** – de interactieve fotogalerij:
   - klikken op een thumbnail wisselt de grote foto zonder dat de pagina herlaadt
   - vorige/volgende-knoppen, na de laatste foto weer naar de eerste
   - teller "3 / 5"
   - pijltjestoetsen op het toetsenbord en vegen (swipe) op de telefoon
   - vergroten in een lightbox, sluiten met kruisje, klik ernaast of Escape
   - de code werkt voor elke `[data-gallery]` op de pagina, dus ik kan hem hergebruiken
3. **`form.js`** – bij het inschrijfformulier:
   - live samenvatting met de gekozen masterclass, datum en **totaalprijs** (prijs × aantal personen)
   - tekenteller bij het opmerkingenveld
   - controle vóór het versturen, met foutmeldingen bij de velden en scrollen naar de eerste fout

**Bugs die ik bij het testen vond en heb opgelost:**
- Als je op de thumbnail klikte die al actief was, bleef de foto onzichtbaar. Dat kwam doordat het `load`-event niet afging
  omdat de `src` niet veranderde. Oplossing: als het dezelfde foto is, stopt de functie meteen.
- Met de pijltjestoetsen in de lightbox veranderde alleen de foto áchter de lightbox. Nu wordt de lightbox-foto ook bijgewerkt.

## Taak 7 – Formulier en PHP-verwerking
1. Formulier gemaakt met `method="post"`: masterclass, voornaam, achternaam, e-mail, telefoon (optioneel), aantal personen,
   ervaring (radio), dieetwensen en een akkoord-vinkje.
2. Via een link als `masterclass.php?class=low-slow` staat de juiste masterclass alvast geselecteerd.
3. PHP-verwerking bovenaan `masterclass.php`:
   - `$_SERVER['REQUEST_METHOD'] === 'POST'` controleren
   - alle waarden uit `$_POST` halen en `trim()` gebruiken
   - elk veld controleren (verplicht, geldig e-mailadres met `filter_var`, aantal personen 1–4 en niet meer dan er plekken zijn, enz.)
   - bij fouten: formulier opnieuw tonen, **ingevulde waarden blijven staan** en bij elk fout veld een rode melding
4. Bij succes:
   - de inschrijving opslaan in `data/aanmeldingen.csv` (zodat Joris een lijst heeft die hij in Excel kan openen)
   - gegevens in de `$_SESSION` zetten en doorsturen naar `bevestiging.php` met `header('Location: ...')`
     (zo wordt het formulier niet dubbel verstuurd als iemand op F5 drukt)
5. `bevestiging.php` laat dynamisch zien: "Bedankt, {voornaam}!", de masterclass, datum, tijd, aantal personen en het totaalbedrag.
   Wie de pagina direct opent zonder in te schrijven, wordt teruggestuurd naar het formulier.

**Problemen die ik bij het testen vond en heb opgelost:**
- De tijd in de CSV was 2 uur te vroeg (UTC). Opgelost met `date_default_timezone_set('Europe/Amsterdam')`.
- In Excel werd "–" raar weergegeven. Opgelost door een BOM aan het begin van het CSV-bestand te zetten.

## Taak 8 – Veilig en verantwoord (VV)
- **XSS:** alles wat een bezoeker invult, toon ik via `htmlspecialchars()` (functie `e()`). Getest met `<script>alert(1)</script>` als naam: dit wordt gewoon tekst.
- **Persoonsgegevens:** de map `data/` is afgeschermd met een `.htaccess` (`Require all denied`) en een `index.php` die doorstuurt.
  Het CSV-bestand staat in `.gitignore`, zodat het nooit in Git komt. Na het tonen van de bevestiging wordt de sessie weer leeggemaakt.
- **Alleen wat nodig is:** telefoon is optioneel en de bezoeker moet akkoord geven voor het gebruik van de gegevens.
- **Spam:** een onzichtbaar "honeypot"-veld. Vult een bot dat in, dan wordt de inschrijving geweigerd.
- **CSV/Excel:** waarden die met `=`, `+`, `-` of `@` beginnen krijgen een `'` ervoor, zodat Excel ze niet als formule uitvoert.
- **Dubbele controle:** JavaScript controleert in de browser (snel voor de gebruiker), maar PHP controleert altijd opnieuw,
  want JavaScript kan je uitzetten of omzeilen.

## Taak 9 – Testen
| Test | Verwacht | Resultaat |
|------|----------|-----------|
| Alle pagina's openen | status 200, geen PHP-warnings | ✅ |
| `product.php?id=bestaatniet` | 404-pagina | ✅ |
| Niet-bestaande URL, bijv. `/docs/bestaatniet.html` | 404-pagina mét opmaak | ✅ (eerst ❌, zie hieronder) |
| `bevestiging.php` direct openen | doorsturen naar formulier | ✅ |
| Formulier leeg/fout versturen | foutmeldingen, waarden blijven staan | ✅ |
| `<script>` in naamveld | wordt tekst, wordt niet uitgevoerd | ✅ |
| Formulier goed invullen | bevestiging met juiste gegevens + regel in CSV | ✅ |
| Honeypot ingevuld | inschrijving geweigerd | ✅ |
| Galerij: volgende/vorige/thumbnail/lightbox/toetsen | foto en teller wisselen zonder herladen | ✅ |
| Formulier: totaalprijs 3 × € 95 | € 285,- | ✅ |
| Mobiel (390 px) | hamburgermenu, alles onder elkaar, niets valt buiten beeld | ✅ |
| `php -l` op alle bestanden | geen syntaxfouten | ✅ |

**Bug gevonden bij het openen van een verkeerde URL** (de wireframes-pagina stond eerst in `docs/wireframes/`, ik typte `/docs/wireframes.html`):
- Je zag de homepage, maar zonder opmaak.
- Oorzaak 1: Herd stuurt elke URL die niet bestaat door naar `index.php`.
- Oorzaak 2: mijn paden waren relatief (`css/style.css`). Vanaf `/docs/...` zocht de browser dus `/docs/css/style.css`,
  en dat bestaat niet.
- Oplossing:
  - `<base href="...">` in `header.php`. De map wordt met PHP uitgerekend (`dirname($_SERVER['SCRIPT_NAME'])`),
    zodat het ook werkt als de site op de server in een submap staat.
  - `index.php` controleert of je echt de homepage opvraagt, anders toont hij de nieuwe `404.php` (met statuscode 404).
  - `.htaccess` met `ErrorDocument 404` voor Apache-hosting.

Na het bekijken van screenshots nog aangepast:
- de hero werd op hoge schermen veel te hoog → maximaal 720 px
- de staande kamado-foto's werden in de kaarten afgesneden → productkaarten hebben nu een staand formaat (4:5)

## Taak 10 – Voorbereiding livegang (FTP)
Dit doet Joris/de docent zelf, maar de site is er klaar voor:
- alleen relatieve paden (`css/style.css`, `images/...`), dus het werkt in elke map op de server
- uploaden: alles **behalve** `aangeleverd/`, `docs/`, `PROCESVERSLAG.md`, `README.md` en `.gitignore`
- de map `data/` moet op de server **schrijfbaar** zijn voor PHP (anders kan de CSV niet opgeslagen worden)
- na het uploaden testen: alle pagina's, de galerij en één testinschrijving, en die testregel daarna weer uit de CSV halen

---

## Terugblik (PP1.4)
**Wat ging goed:**
Door direct met includes en een data-bestand te werken, kon ik heel snel nieuwe pagina's maken. De productdetailpagina is
één bestand voor alle drie de kamado's.

**Wat kan beter:**
- Ik heb de foto's één keer verkeerd bijgesneden en moest het script opnieuw draaien. Volgende keer eerst één foto testen.
- De aanmeldingen staan nu in een CSV-bestand. Beter is een database (MySQL) en een e-mail naar Joris bij elke inschrijving.
  Dat heb ik nog niet geleerd.
- Het aantal vrije plekken wordt nog niet automatisch minder na een inschrijving.

**Wat heb ik nodig:**
Uitleg over databases (PDO/MySQL) en over het versturen van e-mail vanuit PHP. Daarnaast de echte teksten en meer foto's van Joris.
