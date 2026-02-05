<header class="landing-header">
  <img src="<?= url('assets/images/arrow-termin.svg') ?>" alt="" class="landing-header__arrow" aria-hidden="true">
  <div class="landing-header__logo">
    <img src="<?= url('assets/images/bewerbungenundmehr.png') ?>" alt="<?= $site->title() ?>">
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
      <button type="button" class="landing-header__nav-link landing-header__nav-link--cta" data-contact-menu>
        <?= $site->termin_button_label() ?>
      </button>
      <span class="landing-header__cta-subtitle">auch kurzfristig</span>
    </div>
  </nav>
</header>
