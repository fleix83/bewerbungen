<footer class="footer-februar">
  <div class="footer-februar__inner">
    <div class="footer-februar__brand">
      <img src="<?= url('assets/images/Logo_sw.png') ?>" alt="<?= $site->title() ?>" class="footer-februar__logo">
      <span class="footer-februar__name">Felix Weissheimer</span>
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

    <button type="button" class="footer-februar__cta" data-contact-menu>
      Termin machen
    </button>
  </div>
</footer>
