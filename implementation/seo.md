# SEO-Implementierung: Bewerbungen & Mehr

## Übersicht

Diese Anleitung enthält alle SEO-Massnahmen für die Website bewerbungenundmehr.ch. Die Massnahmen sind nach Priorität geordnet.

---

## 1. Title-Tags (HÖCHSTE PRIORITÄT)

Jede Seite benötigt einen einzigartigen Title-Tag im `<head>`-Bereich.

### Startseite (index.html)
```html
<title>Lebenslauf & Bewerbungsschreiben Basel – Felix Weissheimer</title>
```

### Preise & Dienstleistungen (preise.html)
```html
<title>Preise Bewerbungshilfe Basel – CHF 30 pro Dokument</title>
```

### Über Felix Weissheimer (ueber-mich.html)
```html
<title>Felix Weissheimer – Bewerbungsexperte Basel</title>
```

### Wie läuft das? (ablauf.html)
```html
<title>Bewerbungshilfe Basel – So funktioniert es</title>
```

---

## 2. Meta-Descriptions

Jede Seite benötigt eine einzigartige Meta-Description im `<head>`-Bereich.

### Startseite (index.html)
```html
<meta name="description" content="Professionelle Hilfe bei Lebenslauf und Bewerbungsschreiben in Basel. Felix Weissheimer hilft Ihnen persönlich – auch kurzfristig. CHF 30 pro Dokument.">
```

### Preise & Dienstleistungen (preise.html)
```html
<meta name="description" content="Bewerbungshilfe in Basel: Lebenslauf, Bewerbungsschreiben, Briefe und mehr für CHF 30 pro Dokument. Etwa 1 Stunde pro Dokument.">
```

### Über Felix Weissheimer (ueber-mich.html)
```html
<meta name="description" content="Felix Weissheimer – 4 Jahre Erfahrung beim GGG Wegweiser Basel. Professionelle Bewerbungshilfe für Menschen mit und ohne Migrationshintergrund.">
```

### Wie läuft das? (ablauf.html)
```html
<meta name="description" content="So einfach funktioniert die Bewerbungshilfe bei Felix Weissheimer in Basel. Termin buchen, Dokumente mitbringen, fertig.">
```

---

## 3. H1-Überschriften

Jede Seite benötigt genau eine H1-Überschrift mit relevanten Keywords.

### Startseite (index.html)
```html
<h1>Bewerbungshilfe Basel – Lebenslauf & Bewerbungsschreiben</h1>
```

### Preise & Dienstleistungen (preise.html)
```html
<h1>Preise & Dienstleistungen</h1>
```

### Über Felix Weissheimer (ueber-mich.html)
```html
<h1>Über Felix Weissheimer</h1>
```

### Wie läuft das? (ablauf.html)
```html
<h1>Wie läuft das?</h1>
```

---

## 4. Schema Markup (Strukturierte Daten)

Füge dieses JSON-LD Script in den `<head>`-Bereich **jeder Seite** ein:

```html
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "LocalBusiness",
  "name": "Bewerbungen & Mehr – Felix Weissheimer",
  "description": "Professionelle Hilfe bei Lebenslauf und Bewerbungsschreiben in Basel",
  "url": "https://bewerbungenundmehr.ch",
  "telephone": "+41765756052",
  "email": "felix@bewerbungenundmehr.ch",
  "address": {
    "@type": "PostalAddress",
    "streetAddress": "Luftgässlein 3",
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
  "paymentAccepted": "Cash, TWINT",
  "areaServed": {
    "@type": "City",
    "name": "Basel"
  },
  "founder": {
    "@type": "Person",
    "name": "Felix Weissheimer"
  },
  "sameAs": []
}
</script>
```

---

## 5. Vollständiger HTML-Head pro Seite

### Startseite (index.html)
```html
<!DOCTYPE html>
<html lang="de-CH">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <title>Lebenslauf & Bewerbungsschreiben Basel – Felix Weissheimer</title>
  <meta name="description" content="Professionelle Hilfe bei Lebenslauf und Bewerbungsschreiben in Basel. Felix Weissheimer hilft Ihnen persönlich – auch kurzfristig. CHF 30 pro Dokument.">
  
  <meta name="robots" content="index, follow">
  <link rel="canonical" href="https://bewerbungenundmehr.ch/">
  
  <!-- Open Graph für Social Media -->
  <meta property="og:title" content="Lebenslauf & Bewerbungsschreiben Basel – Felix Weissheimer">
  <meta property="og:description" content="Professionelle Hilfe bei Lebenslauf und Bewerbungsschreiben in Basel. CHF 30 pro Dokument.">
  <meta property="og:type" content="website">
  <meta property="og:url" content="https://bewerbungenundmehr.ch/">
  <meta property="og:locale" content="de_CH">
  
  <!-- Schema Markup -->
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "LocalBusiness",
    "name": "Bewerbungen & Mehr – Felix Weissheimer",
    "description": "Professionelle Hilfe bei Lebenslauf und Bewerbungsschreiben in Basel",
    "url": "https://bewerbungenundmehr.ch",
    "telephone": "+41765756052",
    "email": "felix@bewerbungenundmehr.ch",
    "address": {
      "@type": "PostalAddress",
      "streetAddress": "Luftgässlein 3",
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
      "name": "Felix Weissheimer"
    }
  }
  </script>
</head>
```

### Preise & Dienstleistungen (preise.html)
```html
<!DOCTYPE html>
<html lang="de-CH">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <title>Preise Bewerbungshilfe Basel – CHF 30 pro Dokument</title>
  <meta name="description" content="Bewerbungshilfe in Basel: Lebenslauf, Bewerbungsschreiben, Briefe und mehr für CHF 30 pro Dokument. Etwa 1 Stunde pro Dokument.">
  
  <meta name="robots" content="index, follow">
  <link rel="canonical" href="https://bewerbungenundmehr.ch/preise.html">
  
  <!-- Open Graph für Social Media -->
  <meta property="og:title" content="Preise Bewerbungshilfe Basel – CHF 30 pro Dokument">
  <meta property="og:description" content="Lebenslauf, Bewerbungsschreiben, Briefe und mehr für CHF 30 pro Dokument in Basel.">
  <meta property="og:type" content="website">
  <meta property="og:url" content="https://bewerbungenundmehr.ch/preise.html">
  <meta property="og:locale" content="de_CH">
</head>
```

### Über Felix Weissheimer (ueber-mich.html)
```html
<!DOCTYPE html>
<html lang="de-CH">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <title>Felix Weissheimer – Bewerbungsexperte Basel</title>
  <meta name="description" content="Felix Weissheimer – 4 Jahre Erfahrung beim GGG Wegweiser Basel. Professionelle Bewerbungshilfe für Menschen mit und ohne Migrationshintergrund.">
  
  <meta name="robots" content="index, follow">
  <link rel="canonical" href="https://bewerbungenundmehr.ch/ueber-mich.html">
  
  <!-- Open Graph für Social Media -->
  <meta property="og:title" content="Felix Weissheimer – Bewerbungsexperte Basel">
  <meta property="og:description" content="4 Jahre Erfahrung beim GGG Wegweiser Basel. Professionelle Bewerbungshilfe.">
  <meta property="og:type" content="website">
  <meta property="og:url" content="https://bewerbungenundmehr.ch/ueber-mich.html">
  <meta property="og:locale" content="de_CH">
  
  <!-- Person Schema -->
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "Person",
    "name": "Felix Weissheimer",
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
</head>
```

### Wie läuft das? (ablauf.html)
```html
<!DOCTYPE html>
<html lang="de-CH">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <title>Bewerbungshilfe Basel – So funktioniert es</title>
  <meta name="description" content="So einfach funktioniert die Bewerbungshilfe bei Felix Weissheimer in Basel. Termin buchen, Dokumente mitbringen, fertig.">
  
  <meta name="robots" content="index, follow">
  <link rel="canonical" href="https://bewerbungenundmehr.ch/ablauf.html">
  
  <!-- Open Graph für Social Media -->
  <meta property="og:title" content="Bewerbungshilfe Basel – So funktioniert es">
  <meta property="og:description" content="So einfach funktioniert die Bewerbungshilfe bei Felix Weissheimer in Basel.">
  <meta property="og:type" content="website">
  <meta property="og:url" content="https://bewerbungenundmehr.ch/ablauf.html">
  <meta property="og:locale" content="de_CH">
</head>
```

---

## 6. Google Business Profile (Extern)

### Einrichtung
1. Gehe zu https://business.google.com
2. Erstelle einen neuen Eintrag mit folgenden Daten:

| Feld | Wert |
|------|------|
| Unternehmensname | Felix Weissheimer – Bewerbungen & Mehr |
| Kategorie | Bewerbungscoach / Schreibservice |
| Adresse | Luftgässlein 3, 4051 Basel |
| Telefon | 076 575 60 52 |
| Website | https://bewerbungenundmehr.ch |
| Beschreibung | Professionelle Hilfe bei Lebenslauf und Bewerbungsschreiben in Basel. Persönliche Beratung, auch kurzfristig. CHF 30 pro Dokument. |

### Wichtige Ergänzungen
- Öffnungszeiten hinzufügen
- Fotos hochladen (Büro, Portrait)
- Services mit Preisen eintragen
- Regelmässig auf Bewertungen antworten

---

## 7. JavaScript-Menu: Implementierungshinweis

Das JS-Overlay-Menu ist SEO-kompatibel, wenn folgende Regeln eingehalten werden:

### ✅ Korrekte Implementierung
```html
<nav id="menu-overlay">
  <a href="/preise.html">Preise & Dienstleistungen</a>
  <a href="/ueber-mich.html">Über Felix Weissheimer</a>
  <a href="/ablauf.html">Wie läuft das?</a>
  <a href="/buchen">Termin machen</a>
</nav>
```

### ❌ Falsche Implementierung (vermeiden!)
```html
<nav id="menu-overlay">
  <span onclick="navigateTo('preise')">Preise & Dienstleistungen</span>
  <span onclick="navigateTo('ueber')">Über Felix Weissheimer</span>
</nav>
```

**Regel:** Verwende immer echte `<a href="...">`-Tags mit vollständigen URLs.

---

## 8. Keyword-Strategie

### Primäre Keywords (auf Startseite fokussieren)
- Lebenslauf Basel
- Bewerbungsschreiben Basel
- Bewerbungshilfe Basel

### Sekundäre Keywords (auf Unterseiten)
- CV schreiben lassen Basel
- Hilfe bei Bewerbung Basel
- Felix Weissheimer Basel
- Bewerbungscoach Basel

### Long-tail Keywords (für späteren Blog/FAQ)
- Lebenslauf erstellen lassen Basel günstig
- Bewerbungsschreiben Hilfe für Ausländer Basel
- Professioneller Lebenslauf Basel

---

## 9. Technische Checkliste

### Vor dem Launch prüfen
- [ ] Alle Title-Tags eingetragen
- [ ] Alle Meta-Descriptions eingetragen
- [ ] H1-Überschriften auf allen Seiten
- [ ] Schema Markup auf Startseite
- [ ] Canonical URLs gesetzt
- [ ] `lang="de-CH"` im HTML-Tag
- [ ] Mobile-responsive Design
- [ ] Schnelle Ladezeiten (< 3 Sekunden)
- [ ] SSL-Zertifikat (https://)
- [ ] robots.txt erstellt
- [ ] sitemap.xml erstellt

### robots.txt
```
User-agent: *
Allow: /
Sitemap: https://bewerbungenundmehr.ch/sitemap.xml
```

### sitemap.xml
```xml
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <url>
    <loc>https://bewerbungenundmehr.ch/</loc>
    <priority>1.0</priority>
  </url>
  <url>
    <loc>https://bewerbungenundmehr.ch/preise.html</loc>
    <priority>0.8</priority>
  </url>
  <url>
    <loc>https://bewerbungenundmehr.ch/ueber-mich.html</loc>
    <priority>0.8</priority>
  </url>
  <url>
    <loc>https://bewerbungenundmehr.ch/ablauf.html</loc>
    <priority>0.7</priority>
  </url>
</urlset>
```

---

## 10. Nach dem Launch

### Google Search Console
1. Website bei https://search.google.com/search-console anmelden
2. Sitemap einreichen
3. Indexierung überwachen

### Monitoring
- Wöchentlich Rankings prüfen für:
  - "Lebenslauf Basel"
  - "Bewerbungsschreiben Basel"
  - "Felix Weissheimer"
- Google Business Profile Statistiken beobachten

---

## Zusammenfassung: Prioritäten

| Priorität | Massnahme | Aufwand |
|-----------|-----------|---------|
| 🔴 Hoch | Title-Tags | 15 Min |
| 🔴 Hoch | Meta-Descriptions | 15 Min |
| 🔴 Hoch | H1-Überschriften | 10 Min |
| 🔴 Hoch | Google Business Profile | 30 Min |
| 🟡 Mittel | Schema Markup | 20 Min |
| 🟡 Mittel | sitemap.xml | 10 Min |
| 🟢 Nice-to-have | Open Graph Tags | 15 Min |
