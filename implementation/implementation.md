# Implementation Plan: Bewerbungen & Mehr Landing Page

## Project Overview

A static, SEO-optimized website for "Bewerbungen & Mehr" - a job application assistance service in Basel, Switzerland. Built with vanilla HTML/CSS/JS, prepared for future Kirby CMS integration.

---

## 1. Project Structure

```
bewerbungenundmehr/
├── index.html                 # Landing Page
├── menu.html                  # Menu/Navigation Page
├── preise.html               # Preise & Angebote
├── ueber-mich.html           # Über Felix Weissheimer
├── ablauf.html               # Wie läuft das?
├── css/
│   └── global.css            # All styles
├── js/
│   └── main.js               # Minimal JS (menu toggle if needed)
├── assets/
│   ├── images/
│   │   ├── muenster.svg      # Basel Münster illustration (convert to SVG)
│   │   ├── felix-weissheimer.jpg
│   │   ├── checkmark.svg     # Checkmark illustration
│   │   └── icons/
│   │       ├── close.svg
│   │       ├── whatsapp.svg
│   │       ├── phone.svg
│   │       └── arrow-right.svg
│   └── fonts/                # If self-hosting Source Sans Pro
├── robots.txt
├── sitemap.xml
└── favicon.ico
```

---

## 2. Design Tokens (from Styleguide)

### Colors
| Token | Hex | Usage |
|-------|-----|-------|
| `--color-primary` | `#002198` | Primary blue (text, buttons, icons) |
| `--color-secondary` | `#F3D676` | Yellow (backgrounds, highlights, menu buttons) |
| `--color-background` | `#FAF5ED` | Cream background |
| `--color-dot-green` | `#95FCA8` | Green dots |
| `--color-dot-yellow` | `#F3D676` | Yellow dots |
| `--color-dot-blue` | `#002198` | Blue dots |
| `--color-text` | `#2A2A2A` | Body text |
| `--color-white` | `#FFFFFF` | White backgrounds |

### Typography
| Element | Font | Weight | Size |
|---------|------|--------|------|
| Hero Text | Source Sans Pro | 600 (semibold) | 30px |
| Button "Mehr" | Source Sans Pro | 700 (bold) | 20px |
| Page Headings | Source Sans Pro | 600 (semibold) | 30px |
| Text/Listings | Source Sans Pro | 600 (semibold) | 23px |
| Body Text | Source Sans Pro | 600 (semibold) | 20px |
| Address | Source Sans Pro | 400 (regular), italic | 28px |
| Button "Termin machen" | Source Sans Pro | 600 (semibold) | 18px (uppercase) |

### Spacing & Layout
| Token | Value |
|-------|-------|
| `--border-radius` | 30px |
| `--spacing-xs` | 8px |
| `--spacing-sm` | 16px |
| `--spacing-md` | 24px |
| `--spacing-lg` | 32px |
| `--spacing-xl` | 48px |
| `--max-width` | 420px (mobile-first) |

---

## 3. Global CSS Structure

```css
/* ========================================
   BEWERBUNGEN & MEHR - Global Styles
   ======================================== */

/* ----- 1. CSS Custom Properties ----- */
:root {
  /* Colors */
  --color-primary: #002198;
  --color-secondary: #F3D676;
  --color-background: #FAF5ED;
  --color-dot-green: #95FCA8;
  --color-text: #2A2A2A;
  --color-white: #FFFFFF;
  
  /* Typography */
  --font-family: 'Source Sans Pro', -apple-system, sans-serif;
  --font-weight-regular: 400;
  --font-weight-semibold: 600;
  --font-weight-bold: 700;
  
  /* Font Sizes */
  --font-size-sm: 18px;
  --font-size-base: 20px;
  --font-size-md: 23px;
  --font-size-lg: 28px;
  --font-size-xl: 30px;
  
  /* Spacing */
  --spacing-xs: 8px;
  --spacing-sm: 16px;
  --spacing-md: 24px;
  --spacing-lg: 32px;
  --spacing-xl: 48px;
  
  /* Layout */
  --border-radius: 30px;
  --border-radius-sm: 15px;
  --max-width: 420px;
  --page-padding: 24px;
}

/* ----- 2. Reset & Base ----- */
/* ----- 3. Typography ----- */
/* ----- 4. Layout Components ----- */
/* ----- 5. Buttons ----- */
/* ----- 6. Navigation ----- */
/* ----- 7. Cards & Lists ----- */
/* ----- 8. Page-Specific Styles ----- */
/* ----- 9. Utilities ----- */
/* ----- 10. Responsive ----- */
```

---

## 4. Page-by-Page Implementation

### 4.1 Landing Page (index.html)

**Visual Elements:**
- Full yellow background (`#F3D676`)
- "→ Mehr" link top-right corner
- Basel Münster SVG illustration (centered)
- Hero text block with highlighted keywords
- Address block (italic, links to Google Maps)
- "TERMIN MACHEN" button (dark blue pill)
- "→ auch kurzfristig" subtitle

**HTML Structure:**
```html
<body class="page-landing">
  <header class="landing-header">
    <nav>
      <a href="menu.html" class="nav-link nav-link--mehr">→ Mehr</a>
    </nav>
  </header>
  
  <main class="landing-main">
    <figure class="landing-illustration">
      <img src="assets/images/muenster.svg" alt="Basel Münster Illustration" width="280" height="180">
    </figure>
    
    <section class="landing-hero">
      <h1 class="sr-only">Bewerbungshilfe Basel – Lebenslauf & Bewerbungsschreiben</h1>
      <p class="hero-text">
        <span class="hero-text__line">HILFE</span>
        <span class="hero-text__line">BEIM ERSTELLEN VON</span>
        <span class="hero-text__line"><mark class="highlight">BEWERBUNGSSCHREIBEN</mark></span>
        <span class="hero-text__line"><mark class="highlight">LEBENSLAUF</mark> UND MEHR.</span>
        <span class="hero-text__line">IN BASEL.</span>
      </p>
    </section>
    
    <address class="landing-address">
      <a href="https://maps.google.com/?q=Luftgässlein+3+Basel" target="_blank" rel="noopener">
        Felix Weissheimer<br>
        Luftgässlein 3, Basel<br>
        (Beim Bankverein)
      </a>
    </address>
    
    <div class="landing-cta">
      <a href="/buchen" class="btn btn--primary">TERMIN MACHEN</a>
      <span class="cta-subtitle">→ auch kurzfristig</span>
    </div>
  </main>
</body>
```

**SEO Requirements:**
- Title: `Lebenslauf & Bewerbungsschreiben Basel – Felix Weissheimer`
- Meta description: As per seo.md
- Schema.org LocalBusiness markup
- H1 with primary keywords (can be visually hidden, shown via `.sr-only`)

---

### 4.2 Menu Page (menu.html)

**Visual Elements:**
- Cream background (`#FAF5ED`)
- X close button (top-right, links back to index.html)
- Three yellow pill buttons (navigation links)
- "TERMIN MACHEN" button (dark blue)
- WhatsApp link with icon
- Phone link with icon

**HTML Structure:**
```html
<body class="page-menu">
  <header class="menu-header">
    <a href="index.html" class="btn-close" aria-label="Menü schliessen">
      <svg><!-- X icon --></svg>
    </a>
  </header>
  
  <main class="menu-main">
    <nav class="menu-nav" role="navigation" aria-label="Hauptnavigation">
      <ul class="menu-list">
        <li><a href="preise.html" class="menu-link">Preise & Angebote</a></li>
        <li><a href="ueber-mich.html" class="menu-link">Über Felix Weissheimer</a></li>
        <li><a href="ablauf.html" class="menu-link">Wie läuft das?</a></li>
      </ul>
    </nav>
    
    <div class="menu-actions">
      <a href="/buchen" class="btn btn--primary">TERMIN MACHEN</a>
      
      <div class="contact-links">
        <a href="https://wa.me/41765756052" class="contact-link">
          <svg><!-- WhatsApp icon --></svg>
          Per Whatsapp kontaktieren
        </a>
        <a href="tel:+41765756052" class="contact-link">
          <svg><!-- Phone icon --></svg>
          Anrufen 076 575 60 52
        </a>
      </div>
    </div>
  </main>
</body>
```

**Key CSS Classes:**
- `.menu-link` - Yellow pill buttons with 30px border-radius
- `.btn-close` - X button styling
- `.contact-link` - Icon + text link styling

---

### 4.3 Preise & Angebote (preise.html)

**Visual Elements:**
- Cream background
- X close button (links to menu.html)
- Blue heading "Preise & Angebote"
- Price list with white background rows
- Yellow dots for main services
- Green dots for additional services
- Explanatory text blocks
- Contact CTA link
- Bottom action buttons

**HTML Structure:**
```html
<body class="page-preise">
  <header class="page-header">
    <a href="menu.html" class="btn-close" aria-label="Zurück zum Menü">
      <svg><!-- X icon --></svg>
    </a>
  </header>
  
  <main class="page-main">
    <h1 class="page-title">Preise & Angebote</h1>
    
    <section class="price-list" aria-label="Preisliste">
      <ul class="price-items">
        <li class="price-item">
          <span class="dot dot--yellow"></span>
          <span class="price-item__name">Lebenslauf</span>
          <span class="price-item__price">CHF 30.00</span>
        </li>
        <!-- More items -->
      </ul>
      
      <p class="price-note">
        <span class="dot dot--yellow"></span>
        Eine Stunde pro Dokument. Für mehr als zwei Dokumente, machen Sie bitte einen zweiten Termin.
      </p>
    </section>
    
    <section class="additional-services">
      <ul class="service-items">
        <li class="service-item">
          <span class="dot dot--green"></span>
          <span class="service-item__name">Bewerbungen online hochladen</span>
        </li>
        <li class="service-item">
          <span class="dot dot--green"></span>
          <span class="service-item__name">Zeugnisse einscannen</span>
        </li>
      </ul>
      
      <p class="service-note">
        <span class="dot dot--green"></span>
        Inklusive innerhalb einer Stunde bei einem Dokument oder innerhalb zwei Stunden bei zwei Dokumenten.
      </p>
      
      <div class="extra-time">
        <span>Bei mehr Zeit:</span>
        <span class="extra-time__price">CHF 10.00</span>
      </div>
    </section>
    
    <section class="contact-cta">
      <a href="https://wa.me/41765756052" class="cta-link">
        → Kontaktieren Sie mich unkompliziert per Whatsapp oder Mobile bei Fragen und speziellen Anfragen.
      </a>
    </section>
    
    <div class="page-actions">
      <a href="/buchen" class="btn btn--primary">TERMIN MACHEN</a>
      <div class="contact-links">
        <!-- WhatsApp & Phone links -->
      </div>
    </div>
  </main>
</body>
```

**Key CSS Classes:**
- `.price-item` - White background row with flex layout
- `.dot` - 23px colored circle
- `.dot--yellow`, `.dot--green`, `.dot--blue` - Color variants
- `.price-item__price` - Right-aligned, white background pill

---

### 4.4 Über Felix Weissheimer (ueber-mich.html)

**Visual Elements:**
- Cream background
- X close button
- Blue heading
- Photo of Felix (black & white)
- Bio text
- Contact links

**HTML Structure:**
```html
<body class="page-about">
  <header class="page-header">
    <a href="menu.html" class="btn-close" aria-label="Zurück zum Menü">
      <svg><!-- X icon --></svg>
    </a>
  </header>
  
  <main class="page-main">
    <h1 class="page-title">Über Felix Weissheimer</h1>
    
    <figure class="about-photo">
      <img src="assets/images/felix-weissheimer.jpg" 
           alt="Felix Weissheimer - Bewerbungsexperte Basel" 
           width="340" height="220"
           loading="lazy">
    </figure>
    
    <article class="about-content">
      <p><!-- Bio text from mockup --></p>
    </article>
    
    <section class="contact-cta">
      <a href="https://wa.me/41765756052" class="cta-link">
        → Kontaktieren Sie mich unkompliziert per Whatsapp oder Mobile bei Fragen und speziellen Anfragen.
      </a>
    </section>
    
    <div class="page-actions">
      <!-- Same as preise.html -->
    </div>
  </main>
</body>
```

**SEO Requirements:**
- Person Schema markup
- Alt text with name and profession

---

### 4.5 Ablauf (ablauf.html)

**Visual Elements:**
- Cream background
- X close button
- Blue heading "Wie läuft das?"
- Three steps with colored dots (yellow → green → blue)
- Large checkmark illustration at bottom

**HTML Structure:**
```html
<body class="page-ablauf">
  <header class="page-header">
    <a href="menu.html" class="btn-close" aria-label="Zurück zum Menü">
      <svg><!-- X icon --></svg>
    </a>
  </header>
  
  <main class="page-main">
    <h1 class="page-title">Wie läuft das?</h1>
    
    <ol class="process-steps">
      <li class="process-step">
        <span class="dot dot--yellow" aria-hidden="true"></span>
        <p>Im persönlichen Gespräch klären wir Ihr Anliegen und erarbeiten alle wichtigen Punkte. Sie sagen mir, was sie brauchen.</p>
      </li>
      <li class="process-step">
        <span class="dot dot--green" aria-hidden="true"></span>
        <p>Danach formuliere ich mit Ihnen zusammen Ihr Dokument. Sie können jederzeit korrigierend eingreifen.</p>
      </li>
      <li class="process-step">
        <span class="dot dot--blue" aria-hidden="true"></span>
        <p>Ich gebe Ihrem Schreiben den letzten Schliff. Sie gehen mit einem fertigen Dokument oder mit einer fertigen Bewerbung nachhause.</p>
      </li>
    </ol>
    
    <figure class="process-illustration">
      <img src="assets/images/checkmark.svg" 
           alt="Erfolgreich abgeschlossen" 
           width="200" height="200"
           loading="lazy">
    </figure>
  </main>
</body>
```

---

## 5. Component Specifications

### 5.1 Button "Termin machen"
```css
.btn--primary {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: 16px 32px;
  background-color: var(--color-primary);
  color: var(--color-white);
  font-family: var(--font-family);
  font-size: var(--font-size-sm);
  font-weight: var(--font-weight-semibold);
  text-transform: uppercase;
  letter-spacing: 0.5px;
  border-radius: var(--border-radius);
  text-decoration: none;
  transition: opacity 0.2s ease;
}

.btn--primary:hover {
  opacity: 0.9;
}
```

### 5.2 Menu Link (Yellow Pill)
```css
.menu-link {
  display: inline-block;
  padding: 16px 28px;
  background-color: var(--color-secondary);
  color: var(--color-primary);
  font-family: var(--font-family);
  font-size: var(--font-size-md);
  font-weight: var(--font-weight-semibold);
  border-radius: var(--border-radius);
  text-decoration: none;
}
```

### 5.3 Close Button
```css
.btn-close {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 44px;
  height: 44px;
  background: transparent;
  border: none;
  cursor: pointer;
}

.btn-close svg {
  width: 32px;
  height: 32px;
  stroke: var(--color-primary);
  stroke-width: 3px;
}
```

### 5.4 Colored Dots
```css
.dot {
  display: inline-block;
  width: 23px;
  height: 23px;
  border-radius: 50%;
  flex-shrink: 0;
}

.dot--yellow { background-color: var(--color-secondary); }
.dot--green { background-color: var(--color-dot-green); }
.dot--blue { background-color: var(--color-primary); }
```

### 5.5 Price Item Row
```css
.price-item {
  display: flex;
  align-items: center;
  gap: var(--spacing-sm);
  padding: 12px 16px;
  background-color: var(--color-white);
  border-radius: var(--border-radius-sm);
  margin-bottom: var(--spacing-xs);
}

.price-item__name {
  flex: 1;
  font-weight: var(--font-weight-semibold);
  font-size: var(--font-size-md);
}

.price-item__price {
  font-weight: var(--font-weight-semibold);
  font-size: var(--font-size-md);
}
```

---

## 6. SEO Implementation Checklist

### Per Page Requirements

| Page | Title | Meta Description | H1 | Schema |
|------|-------|------------------|-----|--------|
| index.html | ✓ Per seo.md | ✓ Per seo.md | ✓ (sr-only) | LocalBusiness |
| preise.html | ✓ Per seo.md | ✓ Per seo.md | ✓ Visible | - |
| ueber-mich.html | ✓ Per seo.md | ✓ Per seo.md | ✓ Visible | Person |
| ablauf.html | ✓ Per seo.md | ✓ Per seo.md | ✓ Visible | - |
| menu.html | "Menü – Bewerbungen & Mehr" | noindex | - | - |

### Technical SEO Files
- [ ] `robots.txt` - Per seo.md specification
- [ ] `sitemap.xml` - Per seo.md specification
- [ ] Canonical URLs on all pages
- [ ] Open Graph tags on all pages
- [ ] `lang="de-CH"` on all HTML tags

---

## 7. Asset Preparation

### Images to Optimize
| File | Format | Max Width | Notes |
|------|--------|-----------|-------|
| muenster.svg | SVG | - | Convert from PNG, optimize |
| felix-weissheimer.jpg | WebP + JPG fallback | 680px | Grayscale, compressed |
| checkmark.svg | SVG | - | Convert from PNG |

### Icons (SVG)
- `close.svg` - X icon (stroke-based)
- `whatsapp.svg` - WhatsApp logo
- `phone.svg` - Phone icon
- `arrow-right.svg` - → arrow

---

## 8. Implementation Order

### Phase 1: Setup (30 min)
1. Create folder structure
2. Set up `global.css` with all CSS custom properties
3. Add Google Fonts link for Source Sans Pro
4. Create `robots.txt` and `sitemap.xml`

### Phase 2: Landing Page (1 hour)
1. Build `index.html` with full SEO head
2. Style landing page components
3. Convert Münster PNG to SVG (or optimize PNG)
4. Test responsive behavior

### Phase 3: Menu Page (30 min)
1. Build `menu.html`
2. Style menu components
3. Create close button SVG
4. Add WhatsApp/Phone icons

### Phase 4: Preise & Angebote (45 min)
1. Build `preise.html` with full SEO head
2. Style price list components
3. Style dots and white backgrounds
4. Add contact section

### Phase 5: Über Mich (30 min)
1. Build `ueber-mich.html` with full SEO head
2. Optimize Felix photo
3. Style about page layout
4. Add Person schema

### Phase 6: Ablauf (30 min)
1. Build `ablauf.html` with full SEO head
2. Style process steps
3. Convert checkmark to SVG
4. Test layout

### Phase 7: Testing & Polish (30 min)
1. Cross-browser testing
2. Mobile responsiveness check
3. Lighthouse audit
4. Link verification
5. Schema validation

---

## 9. External Links & Contact Info

| Element | URL/Value |
|---------|-----------|
| Google Maps | `https://maps.google.com/?q=Luftgässlein+3+4051+Basel` |
| WhatsApp | `https://wa.me/41765756052` |
| Phone | `tel:+41765756052` |
| Termin Manager | `/buchen` (placeholder for now) |

---

## 10. Notes for Future Kirby CMS Integration

Structure the HTML with clear content blocks that can be converted to Kirby blueprints:
- Hero text blocks
- Price list items
- Process steps
- Bio content

Use semantic class names that map to potential Kirby field names.

---

## 11. Content from Mockups

### Landing Page Text
```
HILFE
BEIM ERSTELLEN VON
BEWERBUNGSSCHREIBEN
LEBENSLAUF UND MEHR.
IN BASEL.

Felix Weissheimer
Luftgässlein 3, Basel
(Beim Bankverein)

TERMIN MACHEN
→ auch kurzfristig
```

### Preise & Angebote Content
```
Preise & Angebote

• Lebenslauf                CHF 30.00
• Bewerbungsschreiben       CHF 30.00
• Brief                     CHF 30.00
• Rekurs                    CHF 30.00
• Formular ausfüllen        CHF 30.00
• Anderes Dokument          CHF 30.00

• Eine Stunde pro Dokument. Für mehr als zwei Dokumente, 
  machen Sie bitte einen zweiten Termin.

• Bewerbungen online hochladen
• Zeugnisse einscannen

• Inklusive innerhalb einer Stunde bei einem Dokument oder 
  innerhalb zwei Stunden bei zwei Dokumenten.

Bei mehr Zeit:              CHF 10.00

→ Kontaktieren Sie mich unkompliziert per Whatsapp oder 
  Mobile bei Fragen und speziellen Anfragen.
```

### Ablauf Content
```
Wie läuft das?

• Im persönlichen Gespräch klären wir Ihr Anliegen und 
  erarbeiten alle wichtigen Punkte. Sie sagen mir, was 
  sie brauchen.

• Danach formuliere ich mit Ihnen zusammen Ihr Dokument. 
  Sie können jederzeit korrigierend eingreifen.

• Ich gebe Ihrem Schreiben den letzten Schliff. Sie gehen 
  mit einem fertigen Dokument oder mit einer fertigen 
  Bewerbung nachhause.
```

---

## 12. Accessibility Requirements

- All images have descriptive alt text
- Interactive elements have minimum 44x44px touch target
- Color contrast meets WCAG AA standards
- Focus states visible on all interactive elements
- Skip link for keyboard navigation (optional)
- ARIA labels on icon-only buttons
- Semantic HTML structure (header, main, nav, etc.)
