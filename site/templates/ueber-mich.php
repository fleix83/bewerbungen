<?php snippet('head') ?>
<body class="page-about">
  <div class="page-container">
    <?php snippet('header/page') ?>

    <main class="page-main">
      <h1 class="page-title"><?= $page->title() ?></h1>

      <?php if ($image = $page->images()->first()): ?>
      <figure class="about-photo">
        <img src="<?= $image->url() ?>"
             alt="<?= $image->alt()->or($site->owner_name() . ' - Bewerbungsexperte Basel') ?>"
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

      <section class="languages-section">
        <h2 class="section-title"><?= $page->languages_title() ?></h2>
        <p><?php
          $text = $page->languages_text()->value();
          $highlights = $page->languages_highlights()->split();
          foreach ($highlights as $highlight) {
            $text = str_replace($highlight, '<span class="language-highlight">' . $highlight . '</span>', $text);
          }
          echo $text;
        ?></p>
      </section>

      <section class="ki-section">
        <h2 class="section-title"><?= $page->ki_title() ?></h2>
        <?php if ($page->ki_paragraph_1()->isNotEmpty()): ?>
        <p><?= $page->ki_paragraph_1() ?></p>
        <?php endif ?>
        <?php if ($page->ki_paragraph_2()->isNotEmpty()): ?>
        <p><?= $page->ki_paragraph_2() ?></p>
        <?php endif ?>
      </section>

      <?php snippet('components/contact-cta') ?>
      <?php snippet('footer/page-actions') ?>
    </main>
  </div>
</body>
</html>
