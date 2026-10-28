<?php
$configFile = __DIR__ . '/../database.config';

function isConfigIncomplete($filePath) {
    if (!file_exists($filePath)) return true;

    $config = parse_ini_file($filePath, true);
    if (!$config) return true;

    $requiredFields = ['DB_SERVER', 'DB_USERNAME', 'DB_DATABASE'];
    
    foreach ($config as $env => $settings) {
        foreach ($requiredFields as $field) {
            if (!isset($settings[$field]) || trim($settings[$field]) === '') {
                return true;
            }
        }
    }

    return false;
}

if (isConfigIncomplete($configFile)) {
    $baseUrl = dirname($_SERVER['SCRIPT_NAME']);
    header('Location: ' . $baseUrl . '/createDatabaseConfig.php');
    exit;
}
?>