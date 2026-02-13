<?php snippet('head') ?>
<body class="page-landing">
  <div class="page-container">
    <?php snippet('header/landing') ?>

    <main class="landing-main">
      <h1 class="sr-only">Bewerbungshilfe Basel – Lebenslauf & Bewerbungsschreiben</h1>

      <?php snippet('components/hero-image-februar') ?>

      <section class="hero-section">
        <div class="hero-section__top">
        </div>

        <div class="hero-body-icons">
          <span class="hero-body-icon hero-body-icon--blue"></span>
          <span class="hero-body-icon hero-body-icon--coral"></span>
        </div>
        <div class="hero-body-columns">
          <div class="hero-body-columns__col">
            <p class="hero-body-columns__text"><?= $page->section2_card1_text() ?></p>
          </div>
          <div class="hero-body-columns__col">
            <p class="hero-body-columns__text"><?= $page->section2_card2_text() ?></p>
          </div>
        </div>

        <p class="hero-body"><?= $page->hero_cta_text() ?></p>

        <div class="hero-cta">
          <button type="button" class="hero-cta__btn" data-contact-menu>Anfragen oder Termin machen</button>
          <p class="hero-cta__subtitle"><?= $page->cta_subtitle() ?></p>
        </div>
      </section>

      <?php snippet('components/services-section') ?>
    </main>

    <?php snippet('footer/landing') ?>
  </div>

  <script src="<?= url('js/contact-menu.js') ?>"></script>
</body>
</html>
