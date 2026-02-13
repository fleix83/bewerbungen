<?php snippet('head') ?>
<body class="page-landing">
  <div class="page-container">
    <?php snippet('header/landing') ?>

    <main class="landing-main">
      <h1 class="sr-only">Bewerbungshilfe Basel – Lebenslauf & Bewerbungsschreiben</h1>

      <?php snippet('components/hero-image-februar') ?>

      <section class="pitch-section">
        <div class="pitch-columns">
          <div class="pitch-col">
            <img src="<?= url('assets/images/icon_language.png') ?>" alt="" class="pitch-col__icon">
            <p class="pitch-col__text"><?= $page->section2_card1_text() ?></p>
          </div>
          <div class="pitch-col">
            <img src="<?= url('assets/images/icon_update.png') ?>" alt="" class="pitch-col__icon pitch-col__icon--round">
            <p class="pitch-col__text"><?= $page->section2_card2_text() ?></p>
          </div>
        </div>

        <p class="pitch-body"><?= $page->hero_cta_text() ?></p>

        <div class="pitch-cta">
          <button type="button" class="pitch-cta__btn" data-contact-menu>Anfragen oder Termin machen</button>
          <p class="pitch-cta__subtitle"><?= $page->cta_subtitle() ?></p>
        </div>
      </section>

      <?php snippet('components/services-section') ?>
    </main>

    <?php snippet('footer/landing') ?>
  </div>

  <script src="<?= url('js/mobile-nav.js') ?>"></script>
  <script src="<?= url('js/contact-menu.js') ?>"></script>
</body>
</html>
