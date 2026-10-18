<?php snippet('head') ?>
<body class="page-anfahrt">
  <div class="page-container">
    <?php snippet('header/page') ?>

    <main class="page-main">
      <h1 class="page-title"><?= $page->title() ?></h1>

      <?php $plan = 'assets/images/plan.png' ?>
      <figure class="anfahrt-plan">
        <img src="<?= url($plan) ?>?v=<?= filemtime(kirby()->root('index') . '/' . $plan) ?>"
             alt="Plan: Weg von den Tramstationen Bankverein und Kunstmuseum zum Luftgässlein 3 in Basel"
             width="1986"
             height="1303">
      </figure>

      <?php if ($page->address()->isNotEmpty()): ?>
      <section class="content-section anfahrt-address">
        <h2 class="section-title"><?= $page->address_title()->or('Adresse') ?></h2>
        <div class="anfahrt-address__body">
          <p>
            <a href="<?= $site->google_maps_url() ?>" target="_blank" rel="noopener"><?= nl2br($page->address()->escape()) ?></a>
          </p>
          <?php if ($page->address_note()->isNotEmpty()): ?>
          <p class="anfahrt-address__note"><?php snippet('icons/bell') ?> <?= nl2br($page->address_note()->escape()) ?></p>
          <?php endif ?>
        </div>
      </section>
      <?php endif ?>

      <?php foreach ($page->sections()->toStructure() as $section): ?>
      <section class="content-section">
        <h2 class="section-title"><?= $section->section_title() ?></h2>
        <p><?= nl2br($section->section_text()->escape()) ?></p>
      </section>
      <?php endforeach ?>

      <?php snippet('footer/page-actions') ?>
    </main>
  </div>
</body>
</html>
