<?php
$about  = page('ueber-mich');
$ablauf = page('ablauf');
$preise = page('preise');
$portrait = $about ? $about->images()->first() : null;
$askUrl = $site->whatsapp_url();
?>
<div class="home-sections">

  <!-- Angebot 1: Deutsch nicht Muttersprache -->
  <section class="offer offer--deutsch">
    <figure class="offer__illustration">
      <img src="<?= url('assets/images/deutsch.svg') ?>" alt="" width="642" height="686" loading="lazy">
    </figure>
    <div class="offer__text">
      <h2 class="home-title"><?= $page->offer1_title() ?></h2>
      <p class="home-body"><?= $page->offer1_text() ?></p>
      <a href="<?= $askUrl ?>" class="btn-ask" target="_blank" rel="noopener"><?= $page->offer_button_text() ?></a>
    </div>
  </section>

  <!-- Angebot 2: Unterlagen auffrischen -->
  <section class="offer offer--auffrischen">
    <div class="offer__text">
      <h2 class="home-title"><?= $page->offer2_title() ?></h2>
      <p class="home-body"><?= $page->offer2_text() ?></p>
      <a href="<?= $askUrl ?>" class="btn-ask" target="_blank" rel="noopener"><?= $page->offer_button_text() ?></a>
    </div>
    <figure class="offer__illustration">
      <img src="<?= url('assets/images/auffrischen.svg') ?>" alt="" width="456" height="1043" loading="lazy">
    </figure>
  </section>

  <!-- Angebot 3: Formulare -->
  <section class="offer offer--formular">
    <figure class="offer__illustration">
      <img src="<?= url('assets/images/formular.svg') ?>" alt="" width="685" height="829" loading="lazy">
    </figure>
    <div class="offer__text">
      <h2 class="home-title"><?= $page->offer3_title() ?></h2>
      <p class="home-body"><?= $page->offer3_text() ?></p>
      <a href="<?= $askUrl ?>" class="btn-ask" target="_blank" rel="noopener"><?= $page->offer_button_text() ?></a>
    </div>
  </section>

  <?php if ($about): ?>
  <!-- Ueber mich: Text ragt ins Portrait hinein -->
  <section class="home-about">
    <?php if ($portrait): ?>
    <figure class="home-about__photo">
      <img src="<?= $portrait->url() ?>" alt="<?= $portrait->alt()->or($site->footer_name()) ?>" width="1331" height="800" loading="lazy">
    </figure>
    <?php endif ?>
    <div class="home-about__text">
      <h2 class="home-title"><a href="<?= $about->url() ?>"><?= $about->title() ?></a></h2>
      <p class="home-body">
        <?= $about->bio_paragraph_1() ?><br>
        <?= $about->bio_paragraph_2() ?>
      </p>
    </div>
  </section>
  <?php endif ?>

  <?php if ($ablauf): ?>
  <!-- Wie laeuft das -->
  <section class="home-ablauf">
    <h2 class="home-title home-ablauf__title"><?= $ablauf->title() ?></h2>
    <ol class="home-ablauf__steps">
      <?php foreach ($ablauf->process_steps()->toStructure() as $step): ?>
      <li class="home-ablauf__step">
        <span class="home-ablauf__dot" aria-hidden="true"></span>
        <span class="home-body"><?= $step->text() ?></span>
      </li>
      <?php endforeach ?>
    </ol>
  </section>
  <?php endif ?>

  <!-- Was kostet das + Kontakt -->
  <section class="home-preise">
    <div class="home-preise__text">
      <h2 class="home-title"><?= $page->price_title() ?></h2>
      <p class="home-body home-preise__body">
        <?= $page->price_text() ?>
        <?php if ($preise): ?>
        <a href="<?= $preise->url() ?>" class="home-preise__link"><?= $page->price_link_text() ?></a>
        <?php endif ?>
      </p>
    </div>

    <div class="home-contact">
      <a href="<?= $site->whatsapp_url() ?>" class="home-contact__intro" target="_blank" rel="noopener">
        <svg class="home-contact__arrow" width="26" height="18" viewBox="0 0 26 18" fill="none" aria-hidden="true">
          <path d="M1 9h23M16 1.5 24 9l-8 7.5" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        <span><?= $site->contact_cta_text() ?></span>
      </a>

      <div class="home-contact__actions">
        <a href="<?= $site->termin_button_url() ?>" class="btn-termin"><?= $site->termin_button_label() ?></a>
        <a href="<?= $site->whatsapp_url() ?>" class="home-contact__link" target="_blank" rel="noopener">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M3.5 20.5l1.3-4.2A8.5 8.5 0 1 1 8 19.3z"/>
            <path d="M9 8.5c0 3.5 3 6.5 6.5 6.5l1-1.6-2-1-1 1c-1.2-.5-2.4-1.7-2.9-2.9l1-1-1-2z"/>
          </svg>
          <span>Per Whatsapp kontaktieren</span>
        </a>
        <a href="tel:<?= $site->telefon() ?>" class="home-contact__link">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M5 3h3.5l1.8 4.5-2.3 1.4a11 11 0 0 0 7.1 7.1l1.4-2.3L21 15.5V19a2 2 0 0 1-2 2A16 16 0 0 1 3 5a2 2 0 0 1 2-2z"/>
          </svg>
          <span>Anrufen&nbsp; <?= $site->telefon_display() ?></span>
        </a>
      </div>
    </div>
  </section>

</div>
