<!DOCTYPE html>
<html lang="de-CH">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title><?= $page->seo_title()->or($page->title()) ?></title>
  <meta name="description" content="<?= $page->seo_description()->or($site->default_description()) ?>">

  <?php if ($page->isHomePage()): ?>
  <meta name="robots" content="index, follow">
  <?php elseif ($page->template() == 'menu'): ?>
  <meta name="robots" content="noindex">
  <?php else: ?>
  <meta name="robots" content="index, follow">
  <?php endif ?>
  <link rel="canonical" href="<?= $site->canonical_base() ?><?= $page->isHomePage() ? '/' : '/' . $page->slug() ?>">

  <?php if ($page->template() != 'menu'): ?>
  <!-- Open Graph fuer Social Media -->
  <meta property="og:title" content="<?= $page->seo_title()->or($page->title()) ?>">
  <meta property="og:description" content="<?= $page->seo_description()->or($site->default_description()) ?>">
  <meta property="og:type" content="website">
  <meta property="og:url" content="<?= $site->canonical_base() ?><?= $page->isHomePage() ? '/' : '/' . $page->slug() ?>">
  <meta property="og:locale" content="de_CH">
  <?php endif ?>

  <?php if ($page->template() == 'ueber-mich'): ?>
  <!-- Person Schema -->
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "Person",
    "name": "<?= $site->owner_name() ?>",
    "jobTitle": "Bewerbungsexperte",
    "worksFor": {
      "@type": "LocalBusiness",
      "name": "Bewerbungen & Mehr"
    },
    "address": {
      "@type": "PostalAddress",
      "addressLocality": "Basel",
      "addressCountry": "CH"
    }
  }
  </script>
  <?php else: ?>
  <!-- Schema Markup -->
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "LocalBusiness",
    "name": "Bewerbungen & Mehr – <?= $site->owner_name() ?>",
    "description": "<?= $site->default_description() ?>",
    "url": "<?= $site->canonical_base() ?>",
    "telephone": "<?= $site->telefon() ?>",
    "address": {
      "@type": "PostalAddress",
      "streetAddress": "Luftgaesslein 3",
      "addressLocality": "Basel",
      "postalCode": "4051",
      "addressCountry": "CH"
    },
    "geo": {
      "@type": "GeoCoordinates",
      "latitude": "47.5547",
      "longitude": "7.5906"
    },
    "priceRange": "CHF 30",
    "areaServed": {
      "@type": "City",
      "name": "Basel"
    },
    "founder": {
      "@type": "Person",
      "name": "<?= $site->owner_name() ?>"
    }
  }
  </script>
  <?php endif ?>

  <link rel="icon" type="image/x-icon" href="/favicon.ico">
  <link rel="icon" type="image/png" sizes="48x48" href="/favicon-48-48.png">
  <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
  <link rel="apple-touch-icon" sizes="180x180" href="/favicon-48-48.png">

  <!-- Google Font: Quicksand (geladen zum manuellen Testen in den Dev Tools, nicht auf Elemente angewandt) -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;600;700&display=swap">

  <link rel="stylesheet" href="<?= url('css/global.css') ?>">
</head>
