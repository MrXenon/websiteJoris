<?php
// Alle inhoud van de site staat hier op één plek.
// Zo hoef ik een prijs of datum maar op één plek aan te passen (DRY).

// ---------- Kamado's ----------
$kamados = [
    'blue-m' => [
        'naam'      => 'Kamado Breda Blue M',
        'kort'      => 'Compact, ideaal voor balkon en kleine tuin.',
        'prijs'     => 899,
        'badge'     => 'Starter',
        'foto'      => 'kamado-blue-m.jpg',
        'tekst'     => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Donec ullamcorper nulla non metus auctor fringilla. Cras mattis consectetur purus sit amet fermentum.',
        'kenmerken' => [
            'Lorem ipsum dolor sit amet',
            'Consectetur adipiscing elit',
            'Sed do eiusmod tempor incididunt',
            'Ut labore et dolore magna aliqua',
        ],
        'specs' => [
            'Roosterdiameter' => '40 cm',
            'Hoogte (incl. onderstel)' => '105 cm',
            'Gewicht' => '62 kg',
            'Geschikt voor' => '2 – 4 personen',
            'Max. temperatuur' => '400 °C',
            'Garantie keramiek' => '10 jaar',
        ],
        'galerij' => [
            ['kamado-blue-m.jpg', 'Kamado Breda Blue M op onderstel'],
            ['detail-thermometer.jpg', 'Thermometer op het blauwe deksel'],
            ['actie-kipspiesjes.jpg', 'Kipspiesjes op het rooster boven gloeiende kolen'],
            ['winkel-kamado-breda.jpg', 'De Blue M in de winkel van Kamado Breda'],
        ],
    ],
    'blue-l' => [
        'naam'      => 'Kamado Breda Blue L',
        'kort'      => 'Onze bestseller voor het hele gezin.',
        'prijs'     => 1299,
        'badge'     => 'Bestseller',
        'foto'      => 'kamado-blue-l.jpg',
        'tekst'     => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum id ligula porta felis euismod semper. Maecenas faucibus mollis interdum. Nullam quis risus eget urna mollis ornare vel eu leo. Aenean lacinia bibendum nulla sed consectetur.',
        'kenmerken' => [
            'Lorem ipsum dolor sit amet',
            'Nulla vitae elit libero',
            'Praesent commodo cursus magna',
            'Vel scelerisque nisl consectetur',
            'Etiam porta sem malesuada magna',
        ],
        'specs' => [
            'Roosterdiameter' => '53 cm',
            'Hoogte (incl. onderstel)' => '118 cm',
            'Gewicht' => '96 kg',
            'Geschikt voor' => '4 – 8 personen',
            'Max. temperatuur' => '400 °C',
            'Garantie keramiek' => '10 jaar',
        ],
        'galerij' => [
            ['kamado-blue-l.jpg', 'Kamado Breda Blue L met houten zijtafels'],
            ['detail-deksel-handvat.jpg', 'Houten handvat met Kamado Breda logo'],
            ['detail-thermometer.jpg', 'Thermometer op het blauwe deksel'],
            ['actie-ribs.jpg', 'Spareribs op het rooster van een kamado'],
            ['actie-steak.jpg', 'Steak en asperges op de grill'],
        ],
    ],
    'blue-xl' => [
        'naam'      => 'Kamado Breda Blue XL',
        'kort'      => 'Voor de echte pitmaster en grote groepen.',
        'prijs'     => 1849,
        'badge'     => 'Nieuw',
        'foto'      => 'kamado-blue-xl.jpg',
        'tekst'     => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur blandit tempus porttitor. Donec sed odio dui. Morbi leo risus, porta ac consectetur ac, vestibulum at eros. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh.',
        'kenmerken' => [
            'Lorem ipsum dolor sit amet',
            'Cras justo odio dapibus ac facilisis',
            'Duis mollis est non commodo luctus',
            'Nisi erat porttitor ligula',
        ],
        'specs' => [
            'Roosterdiameter' => '61 cm',
            'Hoogte (incl. onderstel)' => '126 cm',
            'Gewicht' => '138 kg',
            'Geschikt voor' => '8 – 14 personen',
            'Max. temperatuur' => '400 °C',
            'Garantie keramiek' => 'Levenslang',
        ],
        'galerij' => [
            ['kamado-blue-xl.jpg', 'Kamado Breda Blue XL met houten handvat'],
            ['actie-ribs.jpg', 'Spareribs op het rooster van een kamado'],
            ['masterclass-brisket.jpg', 'Gesneden brisket met mooie rookring'],
            ['joris.jpg', 'Joris naast de Blue XL voor zijn winkel'],
        ],
    ],
];

// ---------- Accessoires ----------
$accessoires = [
    ['naam' => 'Gietijzeren skillet', 'prijs' => 59,  'foto' => 'accessoire-skillets.jpg',        'tekst' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.'],
    ['naam' => 'Gietijzer set',       'prijs' => 129, 'foto' => 'accessoire-gietijzer.jpg',       'tekst' => 'Sed posuere consectetur est at lobortis.'],
    ['naam' => 'Plancha / grillpan',  'prijs' => 79,  'foto' => 'accessoire-skillet-gebruik.jpg', 'tekst' => 'Vestibulum id ligula porta felis euismod semper.'],
    ['naam' => 'Houtskool 10 kg',     'prijs' => 24,  'foto' => 'accessoire-houtskool.jpg',       'tekst' => 'Maecenas sed diam eget risus varius blandit.'],
];

// ---------- Masterclasses ----------
$masterclasses = [
    'basics' => [
        'titel'   => 'Kamado Basics',
        'datum'   => '2026-10-24',
        'tijd'    => '10:00 – 15:00',
        'prijs'   => 95,
        'plekken' => 4,
        'niveau'  => 'Beginner',
        'foto'    => 'actie-kipspiesjes.jpg',
        'tekst'   => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean eu leo quam. Pellentesque ornare sem lacinia quam venenatis vestibulum.',
    ],
    'low-slow' => [
        'titel'   => 'Low & Slow: Brisket & Ribs',
        'datum'   => '2026-11-14',
        'tijd'    => '09:00 – 17:00',
        'prijs'   => 149,
        'plekken' => 7,
        'niveau'  => 'Gevorderd',
        'foto'    => 'masterclass-brisket.jpg',
        'tekst'   => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies.',
    ],
    'winter' => [
        'titel'   => 'Winter BBQ & Gietijzer',
        'datum'   => '2026-12-12',
        'tijd'    => '12:00 – 17:00',
        'prijs'   => 119,
        'plekken' => 10,
        'niveau'  => 'Alle niveaus',
        'foto'    => 'accessoire-skillet-gebruik.jpg',
        'tekst'   => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras mattis consectetur purus sit amet fermentum. Maecenas faucibus mollis interdum.',
    ],
];

// Maximaal aantal personen per inschrijving
$maxPersonen = 4;

// ---------- Fotobronnen (licenties) ----------
$fotobronnen = [
    ['bestand' => 'winkel-kamado-breda.jpg, kamado-blue-*.jpg, detail-*.jpg, joris.jpg, winkel-*.jpg', 'maker' => 'Kamado Breda (aangeleverd door Joris)', 'licentie' => 'Eigendom klant', 'url' => ''],
    ['bestand' => 'actie-ribs.jpg',                 'maker' => 'Corvair Owner',  'licentie' => 'CC BY-SA 2.0', 'url' => 'https://commons.wikimedia.org/wiki/File:Smoking_ribs_on_the_Big_Green_Egg.jpg'],
    ['bestand' => 'actie-kipspiesjes.jpg',          'maker' => 'Corvair Owner',  'licentie' => 'CC BY-SA 2.0', 'url' => 'https://commons.wikimedia.org/wiki/File:Chicken_Shis-ka-bob_on_the_Big_Green_Egg.jpg'],
    ['bestand' => 'actie-steak.jpg',                'maker' => 'Corvair Owner',  'licentie' => 'CC BY-SA 2.0', 'url' => 'https://commons.wikimedia.org/wiki/File:Steak_and_asparagus_on_the_grill.jpg'],
    ['bestand' => 'masterclass-brisket-folie.jpg',  'maker' => 'Corvair Owner',  'licentie' => 'CC BY-SA 2.0', 'url' => 'https://commons.wikimedia.org/wiki/File:Barbequed_brisket_on_the_Big_Green_Egg.jpg'],
    ['bestand' => 'masterclass-brisket.jpg',        'maker' => 'Johnsparks75',   'licentie' => 'CC BY-SA 4.0', 'url' => 'https://commons.wikimedia.org/wiki/File:Smoked_Brisket_with_smoke_ring.jpg'],
    ['bestand' => 'masterclass-bbq-borden.jpg',     'maker' => 'Kent Wang',      'licentie' => 'CC BY-SA 2.0', 'url' => 'https://commons.wikimedia.org/wiki/File:Plates_of_food_from_Black%27s_Barbecue.jpg'],
    ['bestand' => 'accessoire-gietijzer.jpg',       'maker' => 'Edsel Little',   'licentie' => 'CC BY-SA 2.0', 'url' => 'https://commons.wikimedia.org/wiki/File:Cast_Iron_Gathering_(19817120091).jpg'],
    ['bestand' => 'accessoire-skillets.jpg',        'maker' => 'FiveRings (en.wikipedia)', 'licentie' => 'Publiek domein', 'url' => 'https://commons.wikimedia.org/wiki/File:Castiron-skillets.jpg'],
    ['bestand' => 'accessoire-skillet-gebruik.jpg', 'maker' => 'Taylorderek',    'licentie' => 'CC BY 4.0',    'url' => 'https://commons.wikimedia.org/wiki/File:Seared_and_roasted_Pork_Tenderloin_in_Cast_Iron_Skillet.jpg'],
    ['bestand' => 'accessoire-houtskool.jpg',       'maker' => 'Annelieke B',    'licentie' => 'CC BY-SA 2.0', 'url' => 'https://commons.wikimedia.org/wiki/File:Charcoal_lump.jpg'],
];
