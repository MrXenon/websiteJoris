<?php
// /admin/... is served from the KBS.CMS admin, the url stays /admin/...
// Used by index.php, so it works with Apache (.htaccess) and without it (Herd/nginx).
// FRONT_ROOT is set here, so the CMS uses the database.config and uploads of this front-end.
// Expects $adminPath: the part after "admin/", e.g. "pages/dashboard" or "css/style.css".
require_once __DIR__ . '/cmsRoot.php';

$adminRoot = realpath(CMS_ROOT . '/admin');

if ($adminPath === '') {
    header('Location: ' . rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\') . '/admin/pages/dashboard', true, 302);
    exit;
}

// Only files inside the CMS admin folder, never secrets (Google OAuth) or config/data files
$file = realpath($adminRoot . '/' . $adminPath . '.php') ?: realpath($adminRoot . '/' . $adminPath);
$isSecret = $file && (in_array(basename($file), ['client_secret.json', 'token.json'], true)
    || preg_match('/\.(config|ini|env|sql|md|log|lock)$/i', $file));
if (!$file || !is_file($file) || $isSecret || strpos($file, $adminRoot . DIRECTORY_SEPARATOR) !== 0) {
    http_response_code(404);
    require FRONT_ROOT . '/404.php';
    exit;
}

// PHP: run it from its own folder, the admin pages include the core with relative paths (../../core/...)
if (strtolower(pathinfo($file, PATHINFO_EXTENSION)) === 'php') {
    chdir(dirname($file));
    require $file;
    exit;
}

// Static files of the admin (css, js, images, fonts)
$mimeTypes = [
    'css' => 'text/css', 'js' => 'application/javascript', 'json' => 'application/json', 'map' => 'application/json',
    'png' => 'image/png', 'jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'gif' => 'image/gif', 'webp' => 'image/webp',
    'svg' => 'image/svg+xml', 'ico' => 'image/x-icon', 'pdf' => 'application/pdf',
    'woff' => 'font/woff', 'woff2' => 'font/woff2', 'ttf' => 'font/ttf', 'eot' => 'application/vnd.ms-fontobject',
];
$extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
header('Content-Type: ' . ($mimeTypes[$extension] ?? (mime_content_type($file) ?: 'application/octet-stream')));
header('Content-Length: ' . filesize($file));
readfile($file);
exit;
