# Kamado Breda – website + webshop

Website voor Kamado Breda: speciaalzaak in blauwe kamado-barbecues, gietijzeren accessoires en BBQ-masterclasses.
Schoolopdracht Software Developer, level 1, challenge "Je eerste klant", sprint 2.

## Lokaal draaien
- **Met Herd:** de map staat in `~/Herd/websiteJoris` → open <http://websitejoris.test>
- **Zonder Herd:** `php -S localhost:8000 index.php` in deze map → open <http://localhost:8000>

## Nette URL's (zonder .php)
Elke URL komt binnen in `index.php` (de router). Die kiest het juiste bestand uit `pages/`.
Op Herd gebeurt dat automatisch, op Apache via `.htaccess`.
Oude links zoals `kamados.php` of `product.php?id=blue-l` worden met een 301 doorgestuurd naar de nieuwe URL.

| URL | Bestand | Inhoud |
|-----|---------|--------|
| `/` | `pages/home.php` | Home |
| `/kamados` | `pages/kamados.php` | Fotogalerij, de drie kamado's en accessoires (met bestelknoppen) |
| `/kamados/blue-m`, `/blue-l`, `/blue-xl` | `pages/product.php` | Productdetailpagina |
| `/masterclasses` | `pages/masterclasses.php` | Agenda + inschrijfformulier |
| `/bevestiging` | `pages/bevestiging.php` | Bevestiging na inschrijven |
| `/winkelwagen` | `pages/winkelwagen.php` | Winkelwagen |
| `/afrekenen` | `pages/afrekenen.php` | Gegevens, bezorgen/afhalen, betaalwijze |
| `/bestelling/KB-...` | `pages/bestelling.php` | Status/bevestiging van een bestelling |
| `/voorwaarden` | `pages/voorwaarden.php` | Bezorgen, retourneren & voorwaarden (**concept, laten controleren**) |
| `/fotobronnen` | `pages/fotobronnen.php` | Licenties van de gebruikte foto's |
| `/stijlgids` | `pages/stijlgids.php` | Stijlgids met live kleuren en componenten |
| `/docs/wireframes.html` | `docs/wireframes.html` | Wireframes + relatiediagram (statisch bestand) |
| `/sitemap.xml`, `/robots.txt` | `pages/sitemap.php`, `pages/robots.php` | Voor zoekmachines |

Inhoud (prijzen, data, specificaties) aanpassen: `includes/data.php`.
Masterclasses waarvan de datum voorbij is, verdwijnen automatisch van de site.

## Webshop
- De winkelwagen staat in de sessie. Prijzen komen altijd uit `includes/data.php`, nooit uit het formulier.
- Elke bestelling wordt opgeslagen als `data/bestellingen/KB-....json`.
  Betaalde bestellingen (en "betalen bij afhalen") komen ook in `data/bestellingen.csv` (te openen in Excel).
- Instellingen (verzendkosten, gratis bezorging vanaf, max. aantal): `includes/config.php`.

### Online betalen met Mollie
Zonder API-sleutel draait de shop als **demo**: de klant komt op een eigen betaalstap (`/betalen/{nr}`) die eruitziet als een gewone betaalpagina, maar er wordt niet echt betaald.
Op de site staan geen testmeldingen, zodat de klant een echte indruk krijgt. In `data/bestellingen.csv` krijgen zulke bestellingen de status **"betaald (test)"**, zodat ze niet met echte betalingen verward worden.
**Zet de Mollie-sleutel dus altijd aan voordat de shop echt live gaat.**

Echt betalen aanzetten:
1. Maak een account op <https://www.mollie.com> en kopieer de API-sleutel (`test_...` om te testen, `live_...` voor echt).
2. Kopieer `includes/config.local.example.php` naar `includes/config.local.php` en vul de sleutel en het adres van de site in.
3. Klaar. Mollie meldt betalingen via `/mollie-webhook`; dat werkt alleen op een echte server (niet op localhost/.test).

`config.local.php` staat in `.gitignore`, zodat de sleutel niet in git komt.

## Snelheid
- Alle foto's hebben een WebP-versie (30–50% kleiner), met JPG als reserve via `<picture>`.
- Logo in de header is verkleind van 272 KB naar 8 KB (`images/logo-sm.*`).
- Lettertypes staan op de eigen server (`fonts/`): sneller en geen gegevens naar Google.
- CSS/JS krijgen een versienummer (`?v=...`), zodat `.htaccess` ze een jaar laat cachen.

## Uploaden via FTP
Upload alles **behalve** `aangeleverd/`, `README.md`, `PROCESVERSLAG.md` en `.gitignore`.
Uit `docs/` zijn alleen `wireframes.html` en de map `wireframes/` nodig (de footer linkt ernaar); `.md`-bestanden worden door `.htaccess` afgeschermd.
Zorg dat de map `data/` schrijfbaar is voor PHP. Daar komen `aanmeldingen.csv`, `bestellingen.csv` en `bestellingen/` in.
De server moet Apache met `mod_rewrite` zijn (staat bij vrijwel elke hosting aan).

## Documentatie
- [Procesverslag](PROCESVERSLAG.md): stappen, keuzes, tests en terugblik
- [Stijlgids](docs/stijlgids.md), ook als pagina op de site: `/stijlgids`
- [Wireframes](docs/wireframes/): `00-relaties-wireframes.png` (sitemap en gebruikersstroom) + 9 wireframes (desktop + mobiel, inclusief winkelwagen, afrekenen en betalen). Alles samen bekijken: [docs/wireframes.html](docs/wireframes.html) (lokaal: <http://websitejoris.test/docs/wireframes.html>).
