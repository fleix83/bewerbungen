<div class="contact-menu" id="contact-menu" hidden>
  <div class="contact-menu__overlay"></div>
  <div class="contact-menu__content">
    <button class="contact-menu__close" aria-label="Schliessen">&times;</button>

    <a href="tel:<?= $site->telefon() ?>" class="contact-menu__item">
      <i class="pi pi-phone"></i>
      <span>Anrufen</span>
    </a>

    <a href="<?= $site->whatsapp_url() ?>" class="contact-menu__item" target="_blank" rel="noopener">
      <i class="pi pi-whatsapp"></i>
      <span>WhatsApp</span>
    </a>

    <a href="mailto:<?= $site->footer_email() ?>" class="contact-menu__item">
      <i class="pi pi-envelope"></i>
      <span>E-Mail</span>
    </a>

    <button class="contact-menu__item" data-toggle-form>
      <i class="pi pi-comment"></i>
      <span>Nachricht senden</span>
    </button>

    <a href="<?= $site->termin_button_url() ?>" class="contact-menu__item contact-menu__item--cta">
      <i class="pi pi-calendar"></i>
      <span>Termin machen</span>
    </a>

    <form class="contact-menu__form" hidden>
      <input type="text" name="name" placeholder="Ihr Name" required>
      <input type="email" name="email" placeholder="Ihre E-Mail" required>
      <textarea name="message" placeholder="Ihre Nachricht" rows="4" required></textarea>
      <button type="submit" class="contact-menu__submit">Absenden</button>
    </form>
  </div>
</div>
