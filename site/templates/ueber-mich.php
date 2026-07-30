<?php snippet('head') ?>
<body class="page-about">
  <div class="page-container">
    <?php snippet('header/page') ?>

    <main class="page-main">
      <h1 class="page-title"><?= $page->title() ?></h1>

      <?php if ($image = $page->images()->first()): ?>
      <figure class="about-photo">
        <img src="<?= $image->url() ?>"
             alt="<?= $image->alt()->or($site->owner_name() . ' - Bewerbungshilfe Basel') ?>"
             width="340"
             height="220"
             loading="lazy">
      </figure>
      <?php endif ?>

      <article class="about-content">
        <?php if ($page->bio_paragraph_1()->isNotEmpty()): ?>
        <p><?= $page->bio_paragraph_1() ?></p>
        <?php endif ?>

        <?php if ($page->bio_paragraph_2()->isNotEmpty()): ?>
        <p><?= $page->bio_paragraph_2() ?></p>
        <?php endif ?>
      </article>

      <?php foreach ($page->sections()->toStructure() as $section): ?>
      <section class="content-section">
        <h2 class="section-title"><?= $section->section_title() ?></h2>
        <?php
          $highlights = $section->section_highlights()->split();
          foreach ($section->section_paragraphs()->toStructure() as $p):
            $text = $p->paragraph()->value();
            foreach ($highlights as $highlight) {
              $text = str_replace($highlight, '<span class="language-highlight">' . $highlight . '</span>', $text);
            }
        ?>
        <p><?= $text ?></p>
        <?php endforeach ?>
      </section>
      <?php endforeach ?>

      <?php snippet('components/contact-cta') ?>
      <?php snippet('footer/page-actions') ?>
    </main>
  </div>
</body>
</html>
