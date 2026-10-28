        <!-- start footer -->
        <footer class="footer-dark bg-slate-blue">
            <div class="footer-top border-bottom border-color-white-transparent" style="padding-top: 20px;">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-12 col-md-3 text-center text-md-start sm-margin-20px-bottom">
                            <a href="<?= SERVERPATH ?>" class="footer-logo"><img src="<?= SERVERPATH ?>/images/logo/<?= LOGO_WHITE ?>" data-at2x="<?= SERVERPATH ?>/images/logo/<?= LOGO_WHITE ?>" alt=""></a>
                        </div>
                        <div class="col-12 col-md-6 text-center sm-margin-20px-bottom">
                        <p>&copy; Copyright <?= date('Y')?> <a href="<?= SERVERPATH ?>" class="text-decoration-line-bottom text-white"><?= SITENAME ?></a></p>
                        </div>
                        <div class="col-12 col-md-3 text-center text-md-end">
                            <div class="social-icon-style-12">
                                <ul class="extra-small-icon light">
                                    <?php foreach (($socials ?? []) as $platform => $socialUrl): ?>
                                    <li><a class="<?= htmlspecialchars(strtolower(preg_replace('/\W+/', '', $platform))) ?>" href="<?= cms_url($socialUrl) ?>" target="_blank"><i class="<?= cms_social_icon($platform) ?>"></i></a></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!-- end footer -->
        <!-- start scroll to top -->
        <a class="scroll-top-arrow" href="javascript:void(0);"><i class="feather icon-feather-arrow-up"></i></a>
        <!-- end scroll to top -->
