<footer class="landing-footer">
  <div class="landing-footer__content">
    <div class="landing-footer__address">
      <p class="landing-footer__company">Bewerbungen & Mehr</p>
      <p class="landing-footer__name">Felix Weissheimer</p>
      <p class="landing-footer__street">Luftgässlein 3</p>
      <p class="landing-footer__city">4051 Basel</p>
    </div>

    <div class="landing-footer__contact">
      <a href="mailto:felix@bewerbungenundmehr.ch" class="landing-footer__link">
        <?php snippet('icons/email') ?>
        <span>Email</span>
      </a>
      <a href="<?= $site->whatsapp_url() ?>" class="landing-footer__link" target="_blank" rel="noopener">
        <?php snippet('icons/whatsapp') ?>
        <span>Whatsapp</span>
      </a>
      <a href="tel:<?= $site->telefon() ?>" class="landing-footer__link">
        <?php snippet('icons/phone') ?>
        <span>076 575 60 52</span>
      </a>
    </div>

    <div class="landing-footer__cta">
      <a href="<?= $site->termin_button_url() ?>" class="btn--cta-footer">
        <span class="btn--cta-footer__label">Termin machen</span>
      </a>
      <span class="landing-footer__subtitle">auch kurzfristig</span>
    </div>
  </div>
</footer>
