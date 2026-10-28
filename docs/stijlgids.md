# Stijlgids – Kamado Breda

De stijlgids is ook als pagina te bekijken op de site: **/stijlgids** (link in de footer). Daar staan alle kleuren en componenten live, met de echte CSS.

## Sfeer / doelgroep
Kamado Breda is een stoere BBQ-speciaalzaak. De doelgroep is vooral mannen en vrouwen van 30–60 jaar
die al barbecueën en een stap verder willen (kamado, low & slow, masterclass). De site moet voelen als
"vuur, gietijzer en hout": donker, warm en ambachtelijk. Niet te druk.

Ik heb de kleuren gehaald uit het logo van Joris en uit de foto van zijn winkel.

## Kleuren

| Naam            | Hex       | Waar gebruikt                                   |
|-----------------|-----------|-------------------------------------------------|
| Houtskool       | `#16191f` | Achtergrond header, footer, donkere secties     |
| Antraciet       | `#232833` | Kaarten op donkere achtergrond                  |
| Kamado blauw    | `#1f3f8f` | Accenten, links, labels (kleur van de kamado's) |
| Blauw licht     | `#3a62c4` | Hover van blauwe elementen                      |
| Vuur oranje     | `#e8862a` | Knoppen (call to action), prijzen               |
| Oranje donker   | `#c96d17` | Hover van knoppen                               |
| Crème           | `#f4ede3` | Lichte achtergrond, tekst op donker             |
| Tekst donker    | `#1d1f24` | Lopende tekst op licht                          |
| Grijs           | `#6b6f78` | Kleine/secundaire tekst                         |
| Fout rood       | `#c0392b` | Foutmeldingen formulier                         |
| Succes groen    | `#2e8b57` | Bevestiging                                     |

## Typografie
- **Koppen:** `Oswald` (zelf gehost in `fonts/`), 500/700, hoofdletters bij h1/h2, beetje letterafstand.
  Past bij de "stempel"-letters van het logo.
- **Tekst:** `Source Sans 3` (zelf gehost in `fonts/`), 400/600, regelhoogte 1.65.

| Element | Desktop | Mobiel |
|---------|---------|--------|
| h1      | 3.6rem  | 2.2rem |
| h2      | 2.4rem  | 1.7rem |
| h3      | 1.35rem | 1.2rem |
| tekst   | 1.075rem| 1rem   |

De groottes schalen vloeiend mee met de schermbreedte (`clamp()`). De kop in de hero op de homepage is groter (tot 5.2rem).

## Componenten
- **Knop primair:** oranje achtergrond, donkere tekst, hoofdletters, `border-radius: 10px`, oranje gloed als schaduw.
- **Knop secundair:** transparant met crème rand (op donker) of blauwe rand (op licht).
- **Kaart:** witte/antraciet achtergrond, foto boven, `border-radius: 16px`, dunne rand + zachte schaduw, komt omhoog bij hover.
- **Productkaart:** prijs + knoppen "Bestellen" (oranje) en "Details" (rand).
- **Header:** half doorzichtig houtskool met blur, winkelwagen-icoon met oranje teller.
- **Samenvatting (winkelwagen/afrekenen):** donker blok, `border-radius: 24px`, totaal in oranje.
- **Label/badge:** blauwe pil (`border-radius: 999px`) met witte hoofdletters (bijv. "Nieuw", "Nog 4 plekken").
- **Formulier:** labels boven velden, velden 100% breed, `border-radius: 10px`, blauwe focus-ring, rode rand + melding bij fout.
- **Keuzeblok:** groot klikbaar vlak met radio (bezorgen/afhalen), blauwe rand als gekozen.
- **Toast:** donkere melding rechtsonder na "Bestellen", met knop naar de winkelwagen.

## Afstanden
Basis is 8px. Secties hebben `padding: 96px 0` (desktop) en `64px 0` (mobiel).
Container is max. 1200px breed met 20px padding aan de zijkant.

## Breakpoints
- Mobiel eerst (mobile-first)
- `min-width: 700px`  → tablet (2 kolommen)
- `min-width: 1000px` → desktop (3 kolommen, menu naast logo)

## Afbeeldingen
- Grote foto's max. 1200px breed (hero 1920px), JPEG kwaliteit 80.
- Thumbnails 480px breed in `images/thumbs/`.
- Van elke JPG staat er ook een `.webp` naast; de functie `foto()` in `includes/functions.php` toont ze via `<picture>`.
- Altijd een `alt`-tekst.
