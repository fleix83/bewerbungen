<?php snippet('head') ?>
<body class="page-menu">
  <div class="page-container">
    <?php snippet('header/menu') ?>

    <main class="menu-main">
      <nav class="menu-nav" role="navigation" aria-label="Hauptnavigation">
        <ul class="menu-list">
          <?php foreach ($page->menu_items()->toStructure() as $item): ?>
          <?php
            $linkedPage = $item->link()->toPage();
            $url = $linkedPage ? $linkedPage->url() : '#';
          ?>
          <li><a href="<?= $url ?>" class="menu-link"><?= $item->label() ?></a></li>
          <?php endforeach ?>
        </ul>
      </nav>

      <div class="menu-bottom">
        <a href="<?= $site->termin_button_url() ?>" class="btn btn--primary"><?= $site->termin_button_label() ?></a>
        <?php snippet('footer/contact-links') ?>
      </div>
    </main>
  </div>
</body>
</html>
