<?php snippet('head') ?>
<body class="page-landing">
  <div class="page-container">
    <?php snippet('header/landing') ?>

    <main class="landing-main">
      <h1 class="sr-only">Bewerbungshilfe Basel – Lebenslauf & Bewerbungsschreiben</h1>

      <figure class="landing-illustration">
        <img src="<?= url('assets/images/muenster.png') ?>" alt="Basel Muenster Illustration" width="280" height="180">
      </figure>

      <?php snippet('components/hero') ?>
      <?php snippet('components/address') ?>

      <div class="landing-cta">
        <a href="<?= $site->termin_button_url() ?>" class="btn btn--primary btn--large"><?= $site->termin_button_label() ?></a>
        <span class="cta-subtitle"><img src="<?= url('assets/images/arrow-kurzfristig.png') ?>" alt="" class="arrow-icon" width="20" height="20"> <?= $page->cta_subtitle() ?></span>
      </div>
    </main>
  </div>
</body>
</html>
