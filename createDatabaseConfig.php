<?php
require_once __DIR__ . '/php/cmsRoot.php';
include CMS_ROOT . '/core/srcModel/createDatabaseValues.php';
require_once __DIR__ . '/php/content_methods.php';
$configFile = 'database.config';
// Parse the config
$config = parse_ini_file($configFile, true) ?: [
    'localhost' => [],
    'live' => []
];

// Default to step 1
$step = isset($_GET['step']) ? (int) $_GET['step'] : 1;

// Step 1: Display the welcome message
if ($step == 1) {
    // Do nothing in this block, just display step 1's content in HTML
}

// Step 2: Show the database config form
elseif ($step == 2) {
    // Required fields (DB_PASSWORD is optional)
    $fields = ['DB_SERVER', 'DB_USERNAME', 'DB_DATABASE'];
    $allFields = ['DB_SERVER', 'DB_USERNAME', 'DB_PASSWORD', 'DB_DATABASE'];

    // Default server URL, based on the current request
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $defaultServerPath = $protocol . '://' . $_SERVER['HTTP_HOST'] . rtrim(str_replace('\\', '/', dirname($_SERVER['PHP_SELF'])), '/');

    $showForm = false;
    foreach ($config as $env => $settings) {
        foreach ($fields as $field) {
            if (empty($settings[$field])) {
                $showForm = true;
                break 2;
            }
        }
    }

    // Save form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Site-specific values, used to fill in the insert_settings call from setup.sql
        $siteSettings = [
            'sitename'    => $_POST['site_sitename'] ?? 'A new website',
            'serverpath'  => $_POST['site_serverpath'] ?? $defaultServerPath,
            'admin_email' => $_POST['site_admin_email'] ?? '',
        ];

        foreach ($config as $env => &$settings) {
            foreach ($allFields as $field) {
                $key = "{$env}_{$field}";
                if (isset($_POST[$key])) {
                    $settings[$field] = $_POST[$key];
                }
            }
            $settings['VERSION'] = '1';
        }

        // Write back to config file
        $output = '';
        foreach ($config as $env => $settings) {
            $output .= "[$env]\n";
            foreach ($settings as $key => $value) {
                $output .= "$key=\"" . addslashes($value) . "\"\n";
            }
            $output .= "\n";
        }
        file_put_contents($configFile, $output);

        // Execute SQL scripts after successful config
        foreach ($config as $env => $settings) {
            $server = $settings['DB_SERVER'];
            $username = $settings['DB_USERNAME'];
            $password = $settings['DB_PASSWORD'] ?? '';
            $database = $settings['DB_DATABASE'];

            // Try connecting
            $mysqli = @new mysqli($server, $username, $password, $database);

            if ($mysqli->connect_error) {
                error_log("[$env] DB connection failed: " . $mysqli->connect_error);
                continue; // skip if connection fails
            }

            // Run SQL file (adjust path as needed)
            $sqlFile = CMS_ROOT . '/core/procedures/setup.sql';
            if (file_exists($sqlFile)) {
                $sqlContent = file_get_contents($sqlFile);

                // Fill in the site-specific values for the insert_settings call
                $sqlContent = preg_replace_callback(
                    '/CALL insert_settings\(\s*\'[^\']*\'\s*,\s*\'[^\']*\'\s*,\s*(\d+)\s*,\s*(\'[^\']*\')\s*,\s*(\'[^\']*\')\s*,\s*(\'[^\']*\')\s*,\s*(\'[^\']*\')\s*,\s*(\'[^\']*\')\s*,\s*\'[^\']*\'\s*,\s*(NULL|\'[^\']*\')\s*,\s*(\'[^\']*\')\s*,\s*(\'[^\']*\')\s*\)/',
                    function ($m) use ($mysqli, $siteSettings) {
                        return "CALL insert_settings("
                            . "'" . $mysqli->real_escape_string($siteSettings['sitename']) . "', "
                            . "'" . $mysqli->real_escape_string($siteSettings['serverpath']) . "', "
                            . "{$m[1]}, {$m[2]}, {$m[3]}, {$m[4]}, {$m[5]}, {$m[6]}, "
                            . "'" . $mysqli->real_escape_string($siteSettings['admin_email']) . "', "
                            . "{$m[7]}, {$m[8]}, {$m[9]})";
                    },
                    $sqlContent
                );

                if ($mysqli->multi_query($sqlContent)) {
                    do {
                        /* store first result set */
                        if ($result = $mysqli->store_result()) {
                            $result->free();
                        }
                    } while ($mysqli->next_result());
                } else {
                    error_log("[$env] SQL execution error: " . $mysqli->error);
                }
            }

            $mysqli->close();
        }

        // Proceed to super user creation
        header("Location: createDatabaseConfig.php?step=3");
        exit;
    }
}

// Step 3: Create super user account
elseif ($step == 3) {
    $su_errors = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $su_fullname = trim($_POST['su_fullname'] ?? '');
        $su_username = trim($_POST['su_username'] ?? '');
        $su_email    = trim($_POST['su_email'] ?? '');
        $su_password = $_POST['su_password'] ?? '';
        $su_password_confirm = $_POST['su_password_confirm'] ?? '';

        if (empty($su_fullname))                                    $su_errors[] = 'Naam is verplicht.';
        if (empty($su_username))                                    $su_errors[] = 'Gebruikersnaam is verplicht.';
        if (empty($su_email) || !filter_var($su_email, FILTER_VALIDATE_EMAIL))
                                                                    $su_errors[] = 'Voer een geldig e-mailadres in.';
        if (strlen($su_password) < 8)                               $su_errors[] = 'Wachtwoord moet minimaal 8 tekens zijn.';
        if ($su_password !== $su_password_confirm)                  $su_errors[] = 'Wachtwoorden komen niet overeen.';

        if (empty($su_errors)) {
            $host      = $_SERVER['HTTP_HOST'];
            $dbSettings = $config[$host] ?? $config['live'] ?? reset($config);

            $mysqli = @new mysqli(
                $dbSettings['DB_SERVER'],
                $dbSettings['DB_USERNAME'],
                $dbSettings['DB_PASSWORD'] ?? '',
                $dbSettings['DB_DATABASE']
            );

            if ($mysqli->connect_error) {
                $su_errors[] = 'Kan geen verbinding maken met de database. Controleer de database configuratie.';
            } else {
                $hash  = password_hash($su_password, PASSWORD_DEFAULT);
                $stmt  = $mysqli->prepare(
                    "INSERT INTO `users` (`uname`, `upass`, `fullname`, `uemail`, `rol`, `srole`) VALUES (?, ?, ?, ?, 1, 0)"
                );
                $stmt->bind_param('ssss', $su_username, $hash, $su_fullname, $su_email);

                if ($stmt->execute()) {
                    $stmt->close();
                    $mysqli->close();
                    header("Location: createDatabaseConfig.php?step=4");
                    exit;
                } else {
                    $su_errors[] = 'Gebruikersnaam of e-mailadres bestaat al.';
                    $stmt->close();
                }
                $mysqli->close();
            }
        }
    }
}
?>

<!doctype html>
<html class="no-js" lang="en">
    <head>
        <title>KBS.CMS – Database Configuration</title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="author" content="ThemeZaa">
        <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1" />
        <meta name="description" content="KBS CMS Database Configuration">
        <?php include_once 'assets/includes/headscripts.php'; ?>
        <style>
            /* General container styling for the welcome message */
            .welcome-section,.success-section,.database-config-section {
                display: flex;
                justify-content: center;
                align-items: center;
                padding: 20px;
                margin: 20px;
            }

            .config-box {
                background-color: #f9f9f9;
                border: 1px solid #ccc;
                border-radius: 8px;
                padding: 30px;
                width: 80%;
                max-width: 600px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            }

            .config-title {
                font-size: 24px;
                font-weight: bold;
                margin-bottom: 15px;
            }

            .config-description {
                font-size: 16px;
                margin-bottom: 10px;
            }

            .config-list {
                list-style-type: disc;
                margin-left: 20px;
                font-size: 16px;
                margin-bottom: 20px;
            }

            .config-info {
                font-size: 16px;
                margin-bottom: 20px;
            }

            .btn {
                text-decoration: none;
                background-color: #0073aa;
                color: white;
                padding: 12px 25px;
                border-radius: 5px;
                font-size: 16px;
                text-align: center;
            }

            .btn:hover {
                background-color: #FFBF00;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
                color: #FFFFFF;
            }

            /* Form fieldset styling */
            form fieldset {
                border: 1px solid #ccc;
                padding: 20px;
                margin-bottom: 20px;
                border-radius: 8px;
                background-color: #f9f9f9;
            }

            /* Fieldset legend styling */
            form fieldset legend {
                font-size: 18px;
                font-weight: bold;
            }

            /* Input fields styling */
            form label {
                display: block;
                font-size: 16px;
                margin-bottom: 10px;
            }

            form input[type="text"],
            form input[type="email"],
            form input[type="password"] {
                width: 100%;
                padding: 10px;
                font-size: 16px;
                border: 1px solid #ccc;
                border-radius: 4px;
                margin-top: 5px;
            }

            form input[type="submit"] {
                background-color: #0073aa;
                color: white;
                padding: 12px 25px;
                border-radius: 5px;
                font-size: 16px;
                text-align: center;
                cursor: pointer;
                border: none;
            }

            form input[type="submit"]:hover {
                background-color: #FFBF00;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
                color: #FFFFFF;
            }

            .alert-danger {
                background-color: #f8d7da;
                border: 1px solid #f5c6cb;
                border-radius: 4px;
                color: #721c24;
                padding: 12px 16px;
                margin-bottom: 16px;
            }

            .alert-danger p {
                margin: 0 0 4px 0;
            }
        </style>
    </head>
    <body>
        <?php //include_once 'assets/includes/header.php'; ?>

        <!-- Main Content -->
        <main class="min-vh-100">
            <section class="p-0" >
                <div class="container-fluid position-relative">
                    <div class="row">
                        <div class="swiper-container primary-border-bottom  small-screen p-0 md-h-500px sm-h-500px white-move swiper-light-pagination" >
                            <div class="swiper-wrapper">
                                <div class="swiper-slide cover-background" style="background-image:url('images/banners/banner.png');">
                                    <div class="opacity-extra-medium bg-extra-dark-gray"></div>

                                    <div class="container h-100">
                                        <div class="row justify-content-center h-100">
                                            <div class="col-12 col-xl-6 col-sm-7 position-relative d-flex flex-column justify-content-center h-100 text-center">
                                                <?php if ($step == 1): ?>
                                                <h3 class="alt-font font-weight-700 text-white  sm-margin-30px-bottom md-no-text-shadow xs-w-85 mx-auto mx-sm-0">Welkom bij KBS CMS!</h3>
                                                <?php elseif ($step == 2): ?>
                                                <h3 class="alt-font font-weight-700 text-white  sm-margin-30px-bottom md-no-text-shadow xs-w-85 mx-auto mx-sm-0">Database Configuratie</h3>
                                                <?php elseif ($step == 3): ?>
                                                <h3 class="alt-font font-weight-700 text-white  sm-margin-30px-bottom md-no-text-shadow xs-w-85 mx-auto mx-sm-0">Super User Aanmaken</h3>
                                                <?php elseif ($step == 4): ?>
                                                <h3 class="alt-font font-weight-700 text-white  sm-margin-30px-bottom md-no-text-shadow xs-w-85 mx-auto mx-sm-0">Installatie Voltooid</h3>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <div id="container" class="container">
                <div class="row align-items-stretch error-page">
                    <div class="col-12 d-flex  flex-column">
                        <?php if ($step == 1): ?>
                        <section class="welcome-section m-5">
                            <div class="config-box">
                                <h2 class="config-title">Voordat je begint</h2>
                                <p class="config-description">
                                    Voordat je aan de slag gaat, moet je de volgende items weten:
                                </p>
                                <ul class="config-list">
                                    <li>- Naam van de database</li>
                                    <li>- Gebruikersnaam van de database</li>
                                    <li>- Wachtwoord van de database</li>
                                    <li>- Host van de database</li>
                                </ul>
                                <p class="config-info">
                                    Deze informatie wordt gebruikt om een configuratiebestand aan te maken. Als het bestand automatisch niet wordt aangemaakt, kun je dit handmatig doen door de gegevens in wp-config-sample.php te plaatsen.
                                </p>
                                <p class="config-info">
                                    De bovenstaande gegevens werden waarschijnlijk door je hostingprovider verstrekt. Als je deze niet hebt, neem dan contact met hen op voor je verder kunt gaan.
                                </p>
                                <a href="createDatabaseConfig.php?step=2" class="btn btn-large btn-round-edge-small btn-box-shadow primary-bg secondary btn-box-shadow align-self-center">Start Configuratie</a>
                            </div>
                        </section>
                        <?php elseif ($step == 2): ?>
                        <section class="database-config-section">
                            <div class="config-box">
                                <form method="post">
                                    <fieldset>
                                        <legend>Site instellingen</legend>
                                        <label>Site naam: <input type="text" name="site_sitename" value="<?= htmlspecialchars($_POST['site_sitename'] ?? 'A new website') ?>" /></label><br/>
                                        <label>Server URL: <input type="text" name="site_serverpath" value="<?= htmlspecialchars($_POST['site_serverpath'] ?? $defaultServerPath) ?>" /></label><br/>
                                        <label>Admin e-mail: <input type="text" name="site_admin_email" value="<?= htmlspecialchars($_POST['site_admin_email'] ?? '') ?>" /></label><br/>
                                    </fieldset><br/>
                                    <?php
                                        foreach ($config as $env => $settings) {
                                            echo "<fieldset><legend>$env</legend>";
                                            foreach ($allFields as $field) {
                                                $name = "{$env}_{$field}";
                                                $value = isset($settings[$field]) ? htmlspecialchars($settings[$field]) : '';
                                                $inputType = ($field === 'DB_PASSWORD') ? 'password' : 'text';
                                                echo "<label>$field for $env: <input type='$inputType' name='$name' value='$value' /></label><br/>";
                                            }
                                            echo "</fieldset><br/>";
                                        }
                                    ?>
                                    <input type="submit" value="Save Config" class="btn btn-large btn-round-edge-small btn-box-shadow primary-bg secondary btn-box-shadow align-self-center">
                                </form>
                            </div>
                        </section>
                        <?php elseif ($step == 3): ?>
                        <section class="database-config-section">
                            <div class="config-box">
                                <?php if (!empty($su_errors)): ?>
                                <div class="alert-danger">
                                    <?php foreach ($su_errors as $e): ?>
                                    <p><?= htmlspecialchars($e) ?></p>
                                    <?php endforeach; ?>
                                </div>
                                <?php endif; ?>
                                <form method="post">
                                    <fieldset>
                                        <legend>Super User account</legend>
                                        <label>Naam:
                                            <input type="text" name="su_fullname" value="<?= htmlspecialchars($_POST['su_fullname'] ?? '') ?>" required />
                                        </label><br/>
                                        <label>Gebruikersnaam:
                                            <input type="text" name="su_username" value="<?= htmlspecialchars($_POST['su_username'] ?? '') ?>" required />
                                        </label><br/>
                                        <label>E-mailadres:
                                            <input type="email" name="su_email" value="<?= htmlspecialchars($_POST['su_email'] ?? '') ?>" required />
                                        </label><br/>
                                        <label>Wachtwoord (min. 8 tekens):
                                            <input type="password" name="su_password" required />
                                        </label><br/>
                                        <label>Bevestig wachtwoord:
                                            <input type="password" name="su_password_confirm" required />
                                        </label>
                                    </fieldset><br/>
                                    <input type="submit" value="Account aanmaken" class="btn btn-large btn-round-edge-small btn-box-shadow primary-bg secondary btn-box-shadow align-self-center">
                                </form>
                            </div>
                        </section>
                        <?php elseif ($step == 4): ?>
                            <section class="success-section m-5">
                                 <div class="config-box">
                                    <p>De installatie is succesvol afgerond. Je kunt nu inloggen met het aangemaakte super user account.</p>
                                    <a href="<?= SERVERPATH?>/login" class="btn btn-large btn-round-edge-small btn-box-shadow primary-bg secondary btn-box-shadow align-self-center">Ga naar inlogpagina</a>
                                </div>
                            </section>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </main>

        <?php include_once 'assets/includes/footer.php'; ?>
        <?php include_once 'assets/includes/scripts.php'; ?>
    </body>
</html>
