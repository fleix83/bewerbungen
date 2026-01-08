<section class="hero-section">
  <p class="hero-text">
    <span class="hero-text__line"><?= $page->hero_line_1() ?></span>
    <span class="hero-text__line"><?= $page->hero_line_2() ?></span>
    <span class="hero-text__line hero-text__line--spaced"><mark class="highlight" style="background-color: <?= $page->highlight_bg_color() ?>"><?= $page->hero_line_3() ?></mark></span>
    <span class="hero-text__line"><mark class="highlight" style="background-color: <?= $page->highlight_bg_color() ?>"><?= $page->hero_line_4_highlight() ?></mark> <?= $page->hero_line_4_suffix() ?></span>
    <span class="hero-text__line"><?= $page->hero_line_5() ?></span>
  </p>
</section>
