<?php snippet('head') ?>
<body class="page-preise">
  <div class="page-container">
    <?php snippet('header/page') ?>

    <main class="page-main">
      <h1 class="page-title"><?= $page->title() ?></h1>

      <section class="price-list" aria-label="Preisliste">
        <ul style="list-style: none;">
          <?php foreach ($page->price_items()->toStructure() as $item): ?>
          <li class="price-item">
            <div class="price-item__content">
              <span class="price-item__name"><?= $item->name() ?></span>
              <span class="price-item__price"><?= $item->price() ?></span>
            </div>
          </li>
          <?php endforeach ?>
        </ul>

        <?php if ($page->price_note()->isNotEmpty()): ?>
        <p class="price-note">
          <span><?= $page->price_note() ?></span>
        </p>
        <?php endif ?>
      </section>

      <?php if ($page->additional_services()->toStructure()->count() > 0): ?>
      <section class="additional-services">
        <ul style="list-style: none;">
          <?php foreach ($page->additional_services()->toStructure() as $service): ?>
          <li class="price-item">
            <div class="price-item__content">
              <span class="price-item__name"><?= $service->name() ?></span>
            </div>
          </li>
          <?php endforeach ?>
        </ul>

        <?php if ($page->service_note()->isNotEmpty()): ?>
        <p class="service-note">
          <span><?= $page->service_note() ?><?php if ($page->service_note_price()->isNotEmpty()): ?> <span class="language-highlight"><?= $page->service_note_price() ?></span><?php endif ?></span>
        </p>
        <?php endif ?>
      </section>
      <?php endif ?>

      <section class="payment-options">
        <h2 class="section-title"><?= $page->payment_title() ?></h2>
        <p><?php
          $text = $page->payment_text()->value();
          $highlights = $page->payment_methods()->split();
          foreach ($highlights as $highlight) {
            $text = str_replace($highlight, '<span class="language-highlight">' . $highlight . '</span>', $text);
          }
          echo $text;
        ?></p>
      </section>

      <?php snippet('components/contact-cta') ?>
      <?php snippet('footer/page-actions') ?>
    </main>
  </div>
</body>
</html>
