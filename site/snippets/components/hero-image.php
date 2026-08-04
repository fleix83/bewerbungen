<?php
$heroImage = $site->hero_image()->toFile();
?>
<figure class="landing-illustration">
  <?php if ($heroImage): ?>
  <svg width="256" height="290" viewBox="0 0 256 290" fill="none" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
    <defs>
      <clipPath id="organicOval">
        <path d="M137.776 10C109.985 10 78.362 15.1737 53.4629 33.2432C28.0567 51.6807 11.2406 82.371 11.1396 129.757C6.06516 190.383 17.9567 232.496 45.8691 256.695C73.732 280.852 114.432 284.411 160.215 275.62C188.381 270.212 212.993 257.079 228.615 232.463C244.024 208.183 249.653 174.338 243.609 129.491C243.429 100.571 231.997 71.1 213.525 48.7393C194.888 26.1783 168.311 10.0001 137.776 10Z"/>
      </clipPath>
    </defs>
    <image
      xlink:href="<?= $heroImage->url() ?>"
      width="256"
      height="290"
      preserveAspectRatio="xMidYMid slice"
      clip-path="url(#organicOval)"
    />
    <path
      d="M137.776 10C109.985 10 78.362 15.1737 53.4629 33.2432C28.0567 51.6807 11.2406 82.371 11.1396 129.757C6.06516 190.383 17.9567 232.496 45.8691 256.695C73.732 280.852 114.432 284.411 160.215 275.62C188.381 270.212 212.993 257.079 228.615 232.463C244.024 208.183 249.653 174.338 243.609 129.491C243.429 100.571 231.997 71.1 213.525 48.7393C194.888 26.1783 168.311 10.0001 137.776 10Z"
      fill="none"
      stroke="#ff7371"
      stroke-width="20"
    />
  </svg>
  <?php else: ?>
  <img src="<?= url('assets/images/image-violett.svg') ?>" alt="Schreibtisch mit Computer">
  <?php endif ?>
</figure>
<div class="landing-contact">
  <?php snippet('components/about-section') ?>
  <div class="landing-contact__brand">
    <span class="landing-contact__title"><?= $site->title() ?></span>
    <a href="tel:<?= $site->telefon() ?>" class="landing-contact__phone"><?= $site->telefon_display() ?></a>
  </div>
  <div class="landing-contact__details">
    <a href="<?= $site->google_maps_url() ?>" class="landing-contact__link landing-contact__address" target="_blank" rel="noopener">
      <?= $site->footer_street() ?>, <?= $site->footer_city() ?>
    </a>
    <a href="mailto:<?= $site->footer_email() ?>" class="landing-contact__link">
      <?= $site->footer_email() ?>
    </a>
    <a href="<?= $site->whatsapp_url() ?>" class="landing-contact__link" target="_blank" rel="noopener">
      WhatsApp
    </a>
  </div>
</div>
