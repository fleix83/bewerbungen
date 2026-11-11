<?php
// Seitenspezifischer Text, sonst der globale aus den Site-Einstellungen
$ctaText = $page->contact_cta_text()->isNotEmpty()
  ? $page->contact_cta_text()
  : $site->contact_cta_text();
?>
<!-- Pre-Footer: Kontakt, etwas heller als die Fusszeile -->
<section class="prefooter">
  <div class="prefooter__inner">
    <div class="contact-block">
      <p class="contact-block__intro">
        <svg class="contact-block__arrow" width="26" height="18" viewBox="0 0 26 18" fill="none" aria-hidden="true">
          <path d="M1 9h23M16 1.5 24 9l-8 7.5" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        <span><?= $ctaText ?></span>
      </p>

      <div class="contact-block__actions">
        <a href="<?= $site->termin_button_url() ?>" class="btn-termin"><?= $site->termin_button_label() ?></a>
        <a href="<?= $site->whatsapp_url() ?>" class="contact-block__link" target="_blank" rel="noopener">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M3.5 20.5l1.3-4.2A8.5 8.5 0 1 1 8 19.3z"/>
            <path d="M9 8.5c0 3.5 3 6.5 6.5 6.5l1-1.6-2-1-1 1c-1.2-.5-2.4-1.7-2.9-2.9l1-1-1-2z"/>
          </svg>
          <span>Per Whatsapp kontaktieren</span>
        </a>
        <a href="tel:<?= $site->telefon() ?>" class="contact-block__link">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M5 3h3.5l1.8 4.5-2.3 1.4a11 11 0 0 0 7.1 7.1l1.4-2.3L21 15.5V19a2 2 0 0 1-2 2A16 16 0 0 1 3 5a2 2 0 0 1 2-2z"/>
          </svg>
          <span>Anrufen&nbsp; <?= $site->telefon_display() ?></span>
        </a>
        <a href="mailto:<?= $site->footer_email() ?>" class="contact-block__link">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <rect x="3" y="5" width="18" height="14" rx="2"/>
            <path d="m3.5 6.5 8.5 7 8.5-7"/>
          </svg>
          <span>Email schreiben</span>
        </a>
      </div>
    </div>
  </div>
</section>
