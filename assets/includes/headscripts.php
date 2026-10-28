        <!-- favicon icon -->
        <link rel="shortcut icon" href="<?= SERVERPATH ?>/images/logo/<?= FAVICON ?>">
        <link rel="apple-touch-icon" href="<?= SERVERPATH ?>/images/logo/<?= FAVICON_57 ?>">
        <link rel="apple-touch-icon" sizes="72x72" href="<?= SERVERPATH ?>/images/logo/<?= FAVICON_72 ?>">
        <link rel="apple-touch-icon" sizes="114x114" href="<?= SERVERPATH ?>/images/logo/<?= FAVICON_114 ?>">
        <!-- style sheets and font icons  -->
        <link rel="stylesheet" type="text/css" href="<?= SERVERPATH ?>/css/font-icons.min.css?v=<?= SITE_VERSION ?>">
        <link rel="stylesheet" type="text/css" href="<?= SERVERPATH ?>/css/theme-vendors.min.css?v=<?= SITE_VERSION ?>">
        <link rel="stylesheet" type="text/css" href="<?= SERVERPATH ?>/css/style.css?v=<?= SITE_VERSION ?>" />
        <link rel="stylesheet" type="text/css" href="<?= SERVERPATH ?>/css/responsive.css?v=<?= SITE_VERSION ?>" />
        <link rel="manifest" href="<?= SERVERPATH ?>/manifest.json">
        <?php if (defined('RECAPTCHA_SITE_KEY') && RECAPTCHA_SITE_KEY !== ''): ?>
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
        <?php endif; ?>
        <script>
            if ('serviceWorker' in navigator) {
                navigator.serviceWorker.register('<?= SERVERPATH ?>/serviceworker.js')
            }
        </script>
