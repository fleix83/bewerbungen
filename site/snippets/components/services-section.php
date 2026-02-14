<?php $home = page('home'); ?>
<section class="services-section">
  <h2 class="services-section__title"><?= $home->services_section_title()->or('Womit kann ich Ihnen helfen?') ?></h2>

  <ul class="services-cards">
    <?php foreach ($home->service_cards()->toStructure() as $card): ?>
    <li class="service-card">
      <span class="service-card__name"><?= $card->name() ?></span>
      <p class="service-card__body"><?= $card->description() ?></p>
      <div class="service-card__meta">
        <span class="service-card__meta-item"><strong>Zeit:</strong> <?= $card->time() ?></span>
        <span class="service-card__meta-item"><strong>Kosten:</strong> <?= $card->cost() ?><?php if ($card->cost_note()->isNotEmpty()): ?><br><span class="service-card__meta-indent"><?= $card->cost_note() ?></span><?php endif ?></span>
        <span class="service-card__meta-item"><strong>Online:</strong> <?= $card->online() ?></span>
      </div>
      <button type="button" class="service-card__btn" data-contact-menu>Anfragen</button>
    </li>
    <?php endforeach ?>
  </ul>

  <div class="location-section">
    <div class="location-section__content">
      <h2 class="services-section__title"><?= $home->location_title()->or('Vorbeikommen oder online?') ?></h2>

      <div class="services-section__intro">
        <div class="services-section__intro-block">
          <h3 class="services-section__subtitle"><?= $home->location_block1_title() ?></h3>
          <p class="services-section__text"><?= $home->location_block1_text() ?></p>
        </div>
        <div class="services-section__intro-block">
          <h3 class="services-section__subtitle"><?= $home->location_block2_title() ?></h3>
          <p class="services-section__text"><?= $home->location_block2_text() ?></p>
        </div>
      </div>
    </div>
  </div>

  <?php snippet('components/contact-menu') ?>
</section>
