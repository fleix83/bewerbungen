<header class="landing-header">
  <div class="landing-header__logo">
    <img src="<?= url('assets/images/logo.png') ?>" alt="<?= $site->title() ?>">
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
  </nav>
</header>
