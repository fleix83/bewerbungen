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
        </div>

        <p class="hero-body"><?= $page->hero_body() ?></p>
      </section>

      <?php snippet('components/services-section') ?>
    </main>

    <?php snippet('footer/landing') ?>
  </div>

  <script src="<?= url('js/contact-menu.js') ?>"></script>
</body>
</html>
