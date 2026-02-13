<?php snippet('head') ?>
<body class="page-preise">
  <div class="header-wrapper">
    <?php snippet('header/landing') ?>
  </div>

  <div class="preise-hero">
    <h1 class="preise-hero__title"><?= $page->title() ?></h1>
  </div>

  <div class="preise-content">
    <main class="preise-main">
      <section class="preise-block">
        <h2 class="preise-heading">Das kostet es</h2>
        <ul class="preise-table">
          <?php foreach ($page->price_items()->toStructure() as $item): ?>
          <li class="preise-row">
            <span class="preise-row__name"><?= $item->name() ?></span>
            <span class="preise-row__price"><?= $item->price() ?></span>
          </li>
          <?php endforeach ?>
        </ul>

        <?php if ($page->price_note()->isNotEmpty()): ?>
        <p class="preise-note"><?= $page->price_note() ?></p>
        <?php endif ?>
      </section>

      <section class="preise-block">
        <h2 class="preise-heading"><?= $page->payment_title() ?></h2>
        <p class="preise-note"><?= $page->payment_text() ?></p>
      </section>
    </main>

    <?php snippet('components/contact-menu') ?>
  </div>

  <?php snippet('footer/landing') ?>

  <script src="<?= url('js/mobile-nav.js') ?>"></script>
  <script src="<?= url('js/contact-menu.js') ?>"></script>
</body>
</html>
