<section class="about-section">
  <div class="about-section__content">
    <div class="about-section__cards">
      <div class="about-card">
        <div class="about-card__icon">
          <img src="<?= $kirby->url('assets') ?>/images/icon-sprache.svg" alt="" width="40" height="40">
        </div>
        <p class="about-card__text"><?= $page->section2_card1_text() ?></p>
      </div>

      <div class="about-card">
        <div class="about-card__icon">
          <img src="<?= $kirby->url('assets') ?>/images/icon-asterisk.svg" alt="" width="40" height="40">
        </div>
        <p class="about-card__text"><?= $page->section2_card2_text() ?></p>
      </div>

      <div class="about-card">
        <div class="about-card__icon">
          <img src="<?= $kirby->url('assets') ?>/images/icon-form.svg" alt="" width="40" height="40">
        </div>
        <p class="about-card__text"><?= $page->section2_card3_text() ?></p>
      </div>
    </div>

    <p class="about-section__intro">
      <?= $page->section2_intro() ?>
    </p>

    <p class="about-section__cta-text">
      <?= $page->section2_cta_text() ?>
    </p>

    <div class="about-section__links">
      <a href="<?= $site->termin_button_url() ?>" class="about-section__link"><?= $page->section2_link1_text() ?></a>
      <a href="<?= $site->whatsapp_url() ?>" class="about-section__link"><?= $page->section2_link2_text() ?></a>
      <a href="tel:<?= $site->telefon() ?>" class="about-section__link"><?= $page->section2_link3_text() ?></a>
    </div>
  </div>
</section>
