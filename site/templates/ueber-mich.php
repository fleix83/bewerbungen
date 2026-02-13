<?php snippet('head') ?>
<body class="page-about">
  <div class="header-wrapper">
    <?php snippet('header/landing') ?>
  </div>

  <figure class="about-hero-image">
    <img src="<?= url('assets/images/felix_weissheimer_1500.png') ?>"
         alt="<?= $site->owner_name() ?> – Bewerbungsexperte Basel"
         loading="eager">
  </figure>

  <div class="page-container">
    <main class="page-main about-main">
      <h1 class="about-heading"><?= $page->title() ?></h1>

      <article class="about-block">
        <?php if ($page->bio_paragraph_1()->isNotEmpty()): ?>
        <p class="about-text"><?= $page->bio_paragraph_1() ?> <?= $page->bio_paragraph_2() ?></p>
        <?php endif ?>
      </article>

      <?php foreach ($page->sections()->toStructure() as $section): ?>
      <section class="about-block">
        <h2 class="about-heading"><?= $section->section_title() ?></h2>
        <?php
          $highlights = $section->section_highlights()->split();
          $paragraphs = [];
          foreach ($section->section_paragraphs()->toStructure() as $p):
            $text = $p->paragraph()->value();
            foreach ($highlights as $highlight) {
              $text = str_replace($highlight, '<span class="language-highlight">' . $highlight . '</span>', $text);
            }
            $paragraphs[] = $text;
          endforeach;
        ?>
        <p class="about-text"><?= implode(' ', $paragraphs) ?></p>
      </section>
      <?php endforeach ?>

    </main>

    <?php snippet('components/contact-menu') ?>
  </div>

  <?php snippet('footer/landing') ?>

  <script src="<?= url('js/mobile-nav.js') ?>"></script>
  <script src="<?= url('js/contact-menu.js') ?>"></script>
</body>
</html>
