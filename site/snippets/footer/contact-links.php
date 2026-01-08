<div class="contact-links">
  <a href="<?= $site->whatsapp_url() ?>" class="contact-link" target="_blank" rel="noopener">
    <?php snippet('icons/whatsapp') ?>
    Per Whatsapp kontaktieren
  </a>
  <a href="tel:<?= $site->telefon() ?>" class="contact-link">
    <?php snippet('icons/phone') ?>
    Anrufen <?= $site->telefon_display() ?>
  </a>
</div>
