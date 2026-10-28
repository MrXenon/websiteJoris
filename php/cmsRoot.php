<?php
// This front-end (database.config, uploads, sections) and the KBS.CMS that serves it.
// KBS.CMS is searched next to this front-end and one folder higher, so the front-end can also
// live inside a project folder (e.g. Herd/websiteJoris/KBS.FRONT with Herd/KBS.CMS).
if (!defined('FRONT_ROOT')) {
    define('FRONT_ROOT', realpath(__DIR__ . '/..'));
}
if (!defined('CMS_ROOT')) {
    foreach ([FRONT_ROOT . '/../KBS.CMS', FRONT_ROOT . '/../../KBS.CMS'] as $cmsCandidate) {
        if (is_file($cmsCandidate . '/core/Database.php')) {
            define('CMS_ROOT', realpath($cmsCandidate));
            break;
        }
    }
    unset($cmsCandidate);
    if (!defined('CMS_ROOT')) {
        http_response_code(500);
        exit('KBS.CMS not found next to this front-end.');
    }
}
