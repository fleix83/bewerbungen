<?php snippet('head') ?>
<body class="page-landing">
  <div class="page-container">
    <?php snippet('header/landing') ?>

    <main class="landing-main">
      <h1 class="sr-only">Bewerbungshilfe Basel – Lebenslauf & Bewerbungsschreiben</h1>

      <?php snippet('components/hero-image-februar') ?>

      <section class="hero-section">
        <div class="hero-section__top">
          <div class="hero-section__content">
            <p class="hero-lead"><span class="hero-lead-line"><?= $page->hero_lead() ?></span></p>
          </div>
          <div class="hero-section__cta">
            <div class="landing-cta">
              <a href="<?= $site->termin_button_url() ?>" class="btn--cta-februar">
                <span class="btn--cta-februar__label"><?= $site->termin_button_label() ?></span>
                <span class="btn--cta-februar__subtitle"><?= $page->cta_subtitle() ?></span>
              </a>
            </div>
          </div>
        </div>

        <p class="hero-body"><?= $page->hero_body() ?></p>
      </section>

      <?php snippet('components/services-section') ?>
    </main>
  </div>
</body>
</html>
