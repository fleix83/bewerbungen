<footer class="footer-februar">
  <div class="footer-februar__inner">
    <div class="footer-februar__brand">
      <img src="<?= url('assets/images/b2.png') ?>" alt="<?= $site->title() ?>" class="footer-februar__logo">
      <span class="footer-februar__title">Bewerbungen & Mehr</span>
    </div>

    <div class="footer-februar__info">
      <div class="footer-februar__address">
        <p>Luftgässlein 3</p>
        <p>4051 Basel</p>
      </div>

      <div class="footer-februar__contact">
        <a href="tel:<?= $site->telefon() ?>" class="footer-februar__link">
          <?php snippet('icons/phone') ?>
          <span><?= $site->telefon_display() ?></span>
        </a>
        <a href="<?= $site->whatsapp_url() ?>" class="footer-februar__link" target="_blank" rel="noopener">
          <?php snippet('icons/whatsapp') ?>
          <span>WhatsApp</span>
        </a>
      </div>
    </div>

    <a href="<?= $site->termin_button_url() ?>" class="footer-februar__cta">
      Termin machen
    </a>
  </div>
</footer>
