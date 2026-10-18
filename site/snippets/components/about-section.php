<section class="about-section">
  <div class="about-section__content">
    <div class="about-section__cards">
      <div class="about-card">
        <div class="about-card__icon">
          <img src="<?= $kirby->url('assets') ?>/images/icon-sprache.svg" alt="" width="40" height="40">
        </div>
        <p class="about-card__text"><?= $page->section2_card1_text() ?></p>
        <a href="tel:<?= $site->telefon() ?>" class="about-card__button">Kontaktieren</a>
      </div>

      <div class="about-card">
        <div class="about-card__icon">
          <img src="<?= $kirby->url('assets') ?>/images/icon-asterisk.svg" alt="" width="40" height="40">
        </div>
        <p class="about-card__text"><?= $page->section2_card2_text() ?></p>
        <a href="tel:<?= $site->telefon() ?>" class="about-card__button">Kontaktieren</a>
      </div>

      <div class="about-card">
        <div class="about-card__icon">
          <img src="<?= $kirby->url('assets') ?>/images/icon-form.svg" alt="" width="40" height="40">
        </div>
        <p class="about-card__text"><?= $page->section2_card3_text() ?></p>
        <a href="tel:<?= $site->telefon() ?>" class="about-card__button">Kontaktieren</a>
      </div>
    </div>

    <div class="about-section__answer">
      <?php if ($page->section2_answer()->isNotEmpty()): ?>
      <p class="about-section__answer-title"><?= $page->section2_answer() ?></p>
      <?php endif ?>

      <div class="about-section__answer-body">
        <p class="about-section__intro">
          <?= $page->section2_intro() ?>
        </p>

        <p class="about-section__cta-text">
          <?= $page->section2_cta_text() ?>
        </p>
      </div>
    </div>

    <div class="about-section__links">
      <a href="<?= $site->termin_button_url() ?>" class="about-section__link"><?= $page->section2_link1_text() ?></a>
      <a href="<?= $site->whatsapp_url() ?>" class="about-section__link"><?= $page->section2_link2_text() ?></a>
      <a href="tel:<?= $site->telefon() ?>" class="about-section__link"><?= $page->section2_link3_text() ?></a>
    </div>
  </div>
</section>
<script>
  // Dezentes Einblenden der Angebots-Sektion beim ersten Sichtkontakt
  (function () {
    var section = document.currentScript.previousElementSibling;
    if (!section || !('IntersectionObserver' in window)) return;
    section.classList.add('reveal-ready');
    var observer = new IntersectionObserver(function (entries) {
      if (entries[0].isIntersecting) {
        section.classList.add('is-visible');
        observer.disconnect();
      }
    }, { threshold: 0.2 });
    observer.observe(section);
  })();
</script>
