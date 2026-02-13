<?php
function heroKeyword(string $word): string {
  $colors = ['#855CD0', '#5781D6'];
  $out = '';
  $len = mb_strlen($word);
  for ($j = 0; $j < $len; $j++) {
    $ch = mb_substr($word, $j, 1);
    $out .= '<span style="color:' . $colors[$j % 2] . '">' . $ch . '</span>';
  }
  return $out;
}
?>
<figure class="landing-hero-image hero-image-overlay">
  <img src="<?= url('assets/images/buero.jpg') ?>" alt="Modernes Büro - Bewerbungshilfe Basel">
  <div class="hero-lead-wrapper">
    <p class="hero-lead"><span class="hero-lead__bg"><em>Deine Unterstützung bei</em></span> <span class="hero-lead__bg"><?= heroKeyword('BEWERBUNGEN') ?></span><br><span class="hero-lead__bg"><?= heroKeyword('MOTIVATIONSSCHREIBEN') ?></span><br><span class="hero-lead__bg"><?= heroKeyword('LEBENSLAUF') ?></span> <span class="hero-lead__bg"><em>und mehr</em></span><br><span class="hero-lead__bg hero-lead--red">IN BASEL</span></p>
  </div>
</figure>
