<?php snippet('head') ?>
<body class="page-impressum">
  <div class="page-container">
    <?php snippet('header/page') ?>

    <main class="page-main">
      <h1 class="page-title"><?= $page->title() ?></h1>

      <div class="impressum">
        <p class="impressum__company"><?= $site->footer_company() ?></p>

        <address class="impressum__address">
          <?= $site->footer_name() ?><br>
          <?= $site->footer_street() ?><br>
          <?= $site->footer_city() ?>
        </address>

        <div class="impressum__contact">
          <a href="mailto:<?= $site->footer_email() ?>" class="impressum__link">
            <?php snippet('icons/email') ?>
            <span><?= $site->footer_email() ?></span>
          </a>
          <a href="tel:<?= $site->telefon() ?>" class="impressum__link">
            <?php snippet('icons/phone') ?>
            <span><?= $site->telefon_display() ?></span>
          </a>
        </div>
      </div>

      <?php snippet('components/contact-cta') ?>
    </main>
  </div>
</body>
</html>
