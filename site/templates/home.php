<?php snippet('head') ?>
<body class="page-landing">
  <div class="page-container">
    <?php snippet('header/landing') ?>

    <main class="landing-main">
      <h1 class="sr-only">Bewerbungshilfe Basel – Lebenslauf & Bewerbungsschreiben</h1>

      <?php snippet('components/hero-image') ?>

      <?php snippet('components/hero') ?>

      <div class="landing-cta">
        <a href="<?= $site->termin_button_url() ?>" class="btn--cta-violet">
          <span class="btn--cta-violet__label"><?= $site->termin_button_label() ?></span>
          <span class="btn--cta-violet__price"><?= $site->termin_button_price() ?></span>
        </a>
        <span class="cta-subtitle"><?= $page->cta_subtitle() ?></span>
      </div>
    </main>
  </div>

  <?php snippet('components/about-section') ?>
  <?php snippet('footer/landing') ?>
</body>
</html>
