<header class="landing-header">
  <div class="landing-header__logo">
    <img src="<?= url('assets/images/b2.png') ?>" alt="<?= $site->title() ?>">
    <span class="landing-header__brand-name">Bewerbungen & Mehr</span>
  </div>
  <div class="landing-header__contact">
    <a href="tel:<?= $site->telefon() ?>" class="landing-header__contact-link">
      <?php snippet('icons/phone') ?>
      <span>Anrufen <?= $site->telefon_display() ?></span>
    </a>
    <a href="<?= $site->whatsapp_url() ?>" class="landing-header__contact-link">
      <?php snippet('icons/whatsapp') ?>
      <span>Per Whatsapp kontaktieren</span>
    </a>
  </div>
  <nav class="landing-header__nav">
    <a href="<?= page('preise')->url() ?>" class="landing-header__nav-link">Angebote & Preise</a>
    <a href="<?= page('ueber-mich')->url() ?>" class="landing-header__nav-link">Über Mich</a>
    <div class="landing-header__cta">
      <a href="<?= $site->termin_button_url() ?>" class="landing-header__nav-link landing-header__nav-link--cta">
        <?= $site->termin_button_label() ?>
      </a>
      <span class="landing-header__cta-subtitle">auch kurzfristig</span>
    </div>
  </nav>
</header>
