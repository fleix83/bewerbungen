<?php $closeToHome = in_array($page->template()->name(), ['ueber-mich', 'ablauf', 'preise']) ?>
<header class="page-header">
  <a href="<?= page('menu')->url() ?>" class="btn-close<?= $closeToHome ? ' btn-close--mobile' : '' ?>" aria-label="Zurueck zum Menue">
    <?php snippet('icons/close') ?>
  </a>
  <?php if ($closeToHome): ?>
  <a href="<?= $site->homePage()->url() ?>" class="btn-close btn-close--desktop" aria-label="Zurueck zur Startseite">
    <?php snippet('icons/close') ?>
  </a>
  <?php endif ?>
</header>
