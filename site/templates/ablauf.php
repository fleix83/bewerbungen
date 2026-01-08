<?php snippet('head') ?>
<body class="page-ablauf">
  <div class="page-container">
    <?php snippet('header/page') ?>

    <main class="page-main">
      <h1 class="page-title"><?= $page->title() ?></h1>

      <ol class="process-steps">
        <?php foreach ($page->process_steps()->toStructure() as $step): ?>
        <li class="process-step">
          <span class="dot dot--large dot--<?= $step->dot_color() ?>" aria-hidden="true"></span>
          <p><?= $step->text() ?></p>
        </li>
        <?php endforeach ?>
      </ol>

      <?php snippet('footer/page-actions') ?>
    </main>
  </div>
</body>
</html>
