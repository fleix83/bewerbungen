<header class="landing-header">
  <div class="landing-header__address">
    <span class="landing-header__title"><?= $site->title() ?></span>
    <a href="tel:<?= $site->telefon() ?>" class="landing-header__phone"><?= $site->telefon_display() ?></a>
    <div class="landing-header__details">
      <?= $site->owner_name() ?><br>
    </div>
  </div>
  <nav class="landing-header__nav">
    <a href="<?= page('preise')->url() ?>" class="landing-header__nav-link">Preise &amp; Angebote</a>
    <a href="<?= page('ueber-mich')->url() ?>" class="landing-header__nav-link">Über mich</a>
    <a href="<?= page('ablauf')->url() ?>" class="landing-header__nav-link">Wie läuft das?</a>
    <a href="tel:<?= $site->telefon() ?>" class="landing-header__nav-phone"><?= $site->telefon_display() ?></a>
    <a href="<?= page('menu')->url() ?>" class="landing-header__mehr">
      <img src="<?= url('assets/images/plus.svg') ?>"
           alt="<?= $page->mehr_link_text() ?>"
           class="landing-header__plus"
           width="50"
           height="50">
    </a>
  </nav>
</header>
