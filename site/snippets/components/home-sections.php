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
      <a href="<?= $site->termin_button_url() ?>" class="btn-ask"><?= $page->offer1_button() ?></a>
    </div>
  </section>

  <!-- Angebot 2: Unterlagen auffrischen -->
  <section class="offer offer--auffrischen">
    <div class="offer__text">
      <h2 class="home-title"><?= $page->offer2_title() ?></h2>
      <p class="home-body"><?= $page->offer2_text() ?></p>
      <a href="<?= $askUrl ?>" class="btn-ask" target="_blank" rel="noopener"><?= $page->offer2_button() ?></a>
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
      <a href="<?= $askUrl ?>" class="btn-ask" target="_blank" rel="noopener"><?= $page->offer3_button() ?></a>
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

  <!-- Was kostet das -->
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
  </section>

</div>
