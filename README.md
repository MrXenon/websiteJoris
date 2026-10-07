# Kamado Breda – website

Website voor Kamado Breda: speciaalzaak in blauwe kamado-barbecues, gietijzeren accessoires en BBQ-masterclasses.
Schoolopdracht Software Developer, level 1, challenge "Je eerste klant", sprint 2.

## Lokaal draaien
- **Met Herd:** de map staat in `~/Herd/websiteJoris` → open <http://websitejoris.test>
- **Zonder Herd:** `php -S localhost:8000` in deze map → open <http://localhost:8000>

## Pagina's
| Bestand | Inhoud |
|---------|--------|
| `index.php` | Home |
| `kamados.php` | Fotogalerij, de drie kamado's en accessoires |
| `product.php?id=blue-m` / `blue-l` / `blue-xl` | Productdetailpagina |
| `masterclass.php` | Agenda + inschrijfformulier (POST) |
| `bevestiging.php` | Dynamische bevestiging na inschrijven |
| `fotobronnen.php` | Licenties van de gebruikte foto's |

Inhoud (prijzen, data, specificaties) aanpassen: `includes/data.php`.

## Uploaden via FTP
Upload alles **behalve** `aangeleverd/`, `docs/`, `README.md`, `PROCESVERSLAG.md` en `.gitignore`.
Zorg dat de map `data/` schrijfbaar is voor PHP. Daar komt `aanmeldingen.csv` in.

## Documentatie
- [Procesverslag](PROCESVERSLAG.md): stappen, keuzes, tests en terugblik
- [Stijlgids](docs/stijlgids.md)
- [Wireframes](docs/wireframes/): `00-relaties-wireframes.png` (sitemap en gebruikersstroom) + 6 wireframes (desktop + mobiel). Alles samen bekijken: [docs/wireframes.html](docs/wireframes.html) (lokaal: <http://websitejoris.test/docs/wireframes.html>).
