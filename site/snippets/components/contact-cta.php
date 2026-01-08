<?php
// Use page-specific CTA text if set, otherwise use global
$ctaText = $page->contact_cta_text()->isNotEmpty()
  ? $page->contact_cta_text()
  : $site->contact_cta_text();
?>
<section class="contact-cta">
  <a href="<?= $site->whatsapp_url() ?>" class="cta-link">
    <?php if ($page->template() == 'ueber-mich'): ?>
    <img src="<?= url('assets/images/arrow-mehr.png') ?>" alt="" class="arrow-icon" width="20" height="20">
    <?php else: ?>
    &rarr;
    <?php endif ?>
    <span class="cta-link__text"><?= $ctaText ?></span>
  </a>
</section>
