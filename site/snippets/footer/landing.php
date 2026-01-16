<footer class="landing-footer">
  <div class="landing-footer__content">
    <div class="landing-footer__address">
      <p class="landing-footer__company"><?= $site->footer_company() ?></p>
      <p class="landing-footer__name"><?= $site->footer_name() ?></p>
      <p class="landing-footer__street"><?= $site->footer_street() ?></p>
      <p class="landing-footer__city"><?= $site->footer_city() ?></p>
    </div>

    <div class="landing-footer__contact">
      <a href="mailto:<?= $site->footer_email() ?>" class="landing-footer__link">
        <?php snippet('icons/email') ?>
        <span>Email</span>
      </a>
      <a href="<?= $site->whatsapp_url() ?>" class="landing-footer__link" target="_blank" rel="noopener">
        <?php snippet('icons/whatsapp') ?>
        <span>Whatsapp</span>
      </a>
      <a href="tel:<?= $site->telefon() ?>" class="landing-footer__link">
        <?php snippet('icons/phone') ?>
        <span><?= $site->telefon_display() ?></span>
      </a>
    </div>

    <div class="landing-footer__cta">
      <a href="<?= $site->termin_button_url() ?>" class="btn--cta-footer">
        <span class="btn--cta-footer__label">Termin machen</span>
      </a>
      <span class="landing-footer__subtitle">auch kurzfristig</span>
    </div>

    <nav class="landing-footer__nav">
      <a href="<?= page('preise')->url() ?>" class="landing-footer__nav-link">Preise & Angebote</a>
      <a href="<?= page('ueber-mich')->url() ?>" class="landing-footer__nav-link">Über mich</a>
      <a href="<?= page('ablauf')->url() ?>" class="landing-footer__nav-link">Wie läuft das?</a>
    </nav>
  </div>
</footer>
