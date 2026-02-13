<header class="landing-header">
  <a href="<?= $site->url() ?>" class="landing-header__logo">
    <img src="<?= url('assets/images/logo_color.png') ?>" alt="Bewerbungen & Mehr" class="landing-header__logo-img">
  </a>
  <button type="button" class="landing-header__hamburger" aria-expanded="false" aria-controls="nav-panel" aria-label="Navigation öffnen">
    <span></span>
    <span></span>
    <span></span>
  </button>
  <nav id="nav-panel" class="landing-header__nav">
    <a href="<?= page('preise')->url() ?>" class="landing-header__nav-link">Angebote & Preise</a>
    <a href="<?= page('ueber-mich')->url() ?>" class="landing-header__nav-link">Über Mich</a>
    <button type="button" class="landing-header__nav-link landing-header__nav-link--cta" data-contact-menu>
      <?= $site->termin_button_label() ?>
    </button>
  </nav>
</header>
