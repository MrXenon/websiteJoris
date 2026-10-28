
        <!-- start header -->
        <header class="header-with-topbar">
            <!-- start top bar -->
            <div class="top-bar bg-transparent-extra-dark-gray2 d-none d-md-inline-block padding-35px-lr md-no-padding-lr">
                <div class="container-fluid nav-header-container">
                    <div class="d-flex flex-wrap align-items-center">
                        <div class="col-12 text-center text-sm-start col-sm-auto me-auto ps-lg-0">
                            <ul class="social-icon padding-5px-tb">
                                <?php foreach (($socials ?? []) as $platform => $socialUrl): ?>
                                <li><a class="text-white" href="<?= cms_url($socialUrl) ?>" target="_blank"><i class="<?= cms_social_icon($platform) ?>"></i></a></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <div class="col-auto d-none d-sm-block text-end px-lg-0 font-size-0">
                            <div class="top-bar-contact">
                                <span class="top-bar-contact-list border-0">
                                    <?php if (!empty($phoneNumbers['Algemeen'])): ?>
                                    <i class="feather icon-feather-phone-call text-white"></i>
                                    <a href="tel:<?= cms_tel($phoneNumbers['Algemeen']) ?>"><?= htmlspecialchars($phoneNumbers['Algemeen']) ?></a>
                                    <?php endif; ?>
                                    <?php if (!empty($emails['Algemeen'])): ?>
                                    <i class="feather icon-feather-mail text-white"></i>
                                    <a href="mailto:<?= htmlspecialchars($emails['Algemeen']) ?>"><?= htmlspecialchars($emails['Algemeen']) ?></a>
                                    <?php endif; ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end top bar -->
            <!-- start navigation -->
            <nav class="navbar navbar-expand-lg navbar-dark bg-transparent border-bottom border-color-white-transparent header-light navbar-boxed fixed-top header-reverse-scroll">
                <div class="container-fluid nav-header-container">
                    <div class="col-6 col-lg-2 me-auto ps-lg-0">
                        <a class="navbar-brand" href="<?= SERVERPATH ?>">
                            <img src="<?= SERVERPATH ?>/images/logo/<?= LOGO_WHITE ?>" data-at2x="<?= SERVERPATH ?>/images/logo/<?= LOGO_WHITE ?>" class="default-logo" alt="">
                            <img src="<?= SERVERPATH ?>/images/logo/<?= LOGO ?>" data-at2x="<?= SERVERPATH ?>/images/logo/<?= LOGO ?>" class="alt-logo" alt="">
                            <img src="<?= SERVERPATH ?>/images/logo/<?= LOGO ?>" data-at2x="<?= SERVERPATH ?>/images/logo/<?= LOGO ?>" class="mobile-logo" alt="">
                        </a>
                    </div>
                    <div class="col-auto col-lg-10 menu-order px-lg-0">
                        <button class="navbar-toggler float-end" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-label="Toggle navigation">
                            <span class="navbar-toggler-line"></span>
                            <span class="navbar-toggler-line"></span>
                            <span class="navbar-toggler-line"></span>
                            <span class="navbar-toggler-line"></span>
                        </button>
                        <div class=" collapse navbar-collapse justify-content" id="navbarNav">
                            <ul class="navbar-nav alt-font">
                                <?php foreach ($menuPageList as $menuPage): ?>
                                <li class="nav-item dropdown megamenu">
                                    <a href="<?= cms_page_url($menuPage->getFrontPageName()) ?>" class="nav-link font-weight-700"><?= cms_page_label($menuPage->getFrontPageName()) ?></a>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>
            <!-- end navigation -->
        </header>
        <!-- end header -->
