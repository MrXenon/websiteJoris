<?php
/*
 * Shared handler for the front-end forms (contact form, package request).
 * Receiver, reCAPTCHA secret and logo come from the CMS system settings.
 * Responds with JSON for the theme form script (alert + message).
 */
// This front-end and the KBS.CMS that serves it (FRONT_ROOT, CMS_ROOT)
require_once __DIR__ . '/../php/cmsRoot.php';
require CMS_ROOT . '/core/Database.php';
require CMS_ROOT . '/core/defs.php';

function form_json($alert, $message) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['alert' => $alert, 'message' => $message]);
    exit;
}

/**
 * @param string $subject  E-mail subject
 * @param array  $fields   Label => posted value, shown as table rows in the e-mail
 * @param array  $messages ['captcha' => ..., 'success' => ..., 'fail' => ...]
 */
function handle_form($subject, array $fields, array $messages) {
    global $emails;

    if (empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        form_json('alert-danger', 'Please add an email address!');
    }

    // Google reCAPTCHA check (secret key from the system settings)
    if (RECAPTCHA_SECRET_KEY !== '') {
        $token = $_POST['g-recaptcha-response'] ?? '';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://www.google.com/recaptcha/api/siteverify');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(['secret' => RECAPTCHA_SECRET_KEY, 'response' => $token]));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $response = json_decode((string)curl_exec($ch), true);
        curl_close($ch);

        if (empty($response['success'])) {
            form_json('alert-danger', $messages['captcha']);
        }
    }

    $receiver = !empty($emails['Algemeen']) ? $emails['Algemeen'] : ADMIN_EMAIL;
    $name     = trim((string)($_POST['name'] ?? ''));

    $rows = '';
    $fieldIndex = 0;
    foreach ($fields as $label => $value) {
        $isLast = (++$fieldIndex === count($fields));
        $border = 'border-top:1px solid #dfdfdf;' . ($isLast ? ' border-bottom:1px solid #dfdfdf;' : '');
        $rows .= '<tr>
            <td align="right" valign="top" style="' . $border . ' font-family:Arial, Helvetica, sans-serif; font-size:13px; color:#000; padding:7px 5px 7px 0;">' . htmlspecialchars($label) . ':</td>
            <td align="left" valign="top" style="' . $border . ' font-family:Arial, Helvetica, sans-serif; font-size:13px; color:#000; padding:7px 0 7px 5px;">' . nl2br(htmlspecialchars((string)$value)) . '</td>
        </tr>';
    }

    $message = '<html><head><title>' . htmlspecialchars($subject) . '</title></head><body>
        <table width="50%" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr><td colspan="2" align="center" valign="top"><img style="margin-top: 15px;" src="' . SERVERPATH . '/images/logo/' . rawurlencode(LOGO) . '"></td></tr>
        <tr><td width="50%" align="right">&nbsp;</td><td align="left">&nbsp;</td></tr>
        ' . $rows . '
        </table></body></html>';

    $headers  = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8\r\n";
    $headers .= 'From: ' . str_replace(["\r", "\n"], '', $name ?: SITENAME) . ' <' . $receiver . ">\r\n";
    $headers .= 'Reply-To: ' . str_replace(["\r", "\n"], '', $_POST['email']) . "\r\n";

    if (@mail($receiver, $subject, $message, $headers)) {
        if (!empty($_POST['redirect'])) {
            header('Location: ' . $_POST['redirect']);
            exit;
        }
        form_json('alert-success', $messages['success']);
    }
    form_json('alert-danger', $messages['fail']);
}
