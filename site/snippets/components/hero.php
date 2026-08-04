<section class="hero-section">
  <p class="hero-text">
    <span class="hero-text__line"><?= $page->hero_line_1() ?></span>
    <?php if ($page->hero_line_2_prefix()->isNotEmpty() || $page->hero_line_2_highlight()->isNotEmpty()): ?>
    <span class="hero-text__line"><?= $page->hero_line_2_prefix() ?> <span class="hero-text__mark"><?= $page->hero_line_2_highlight() ?></span></span>
    <?php endif ?>
    <span class="hero-text__line"><span class="hero-text__mark"><?= $page->hero_line_3() ?></span></span>
    <span class="hero-text__line"><span class="hero-text__mark"><?= $page->hero_line_4_highlight() ?></span> <?= $page->hero_line_4_suffix() ?></span>
    <span class="hero-text__line"><?= $page->hero_line_5_prefix() ?> <span class="hero-text__mark"><?= $page->hero_line_5_highlight() ?></span><?= $page->hero_line_5_suffix() ?></span>
  </p>
</section>
