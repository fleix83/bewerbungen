# Styleguide: Termin Manager - Bewerbungen & Mehr

## Typografie

### Font Family

```css
@import url('https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@400;600;700&display=swap');

:root {
  --font-primary: 'Source Sans Pro', sans-serif;
}
```

### Text Styles

| Element | Size | Weight | Line-Height | Color |
|---------|------|--------|-------------|-------|
| Screen Title | 30px | 600 | 35px | #002198 |
| Section Title | 24px | 600 | 30px | #002198 |
| Body Text / Notes | 23px | 600 | 30px | #000000 |
| Checkbox Label | 18px | 400 | 24px | #000000 |
| Duration (1h) | 14px | 400 | 18px | #666666 |
| Input Label | 16px | 600 | 20px | #000000 |
| Button Text | 16px | 600 | normal | #002198 |
| Contact Links | 14px | 400 | 18px | #002198 |

---

## Farbpalette

```css
:root {
  /* Primär */
  --color-primary: #FFD700;
  --color-primary-dark: #E6C200;
  
  /* Text */
  --color-text-title: #002198;
  --color-text-body: #000000;
  --color-text-muted: #666666;
  
  /* Hintergrund */
  --color-bg-page: #FFF8E7;
  --color-bg-card: #FFFBF0;
  --color-bg-white: #FFFFFF;
  
  /* Slots */
  --color-slot-free: #FFD700;
  --color-slot-occupied: #9E9E9E;
  --color-dot-free: #90EE90;
  --color-dot-occupied: #666666;
  
  /* UI */
  --color-border: #E0E0E0;
  --color-border-focus: #002198;
}
```

---

## Komponenten

### Screen Title
```css
.screen-title {
  color: #002198;
  font-family: "Source Sans Pro", sans-serif;
  font-size: 30px;
  font-weight: 600;
  line-height: 35px;
}
```

### Section Title
```css
.section-title {
  color: #002198;
  font-family: "Source Sans Pro", sans-serif;
  font-size: 24px;
  font-weight: 600;
  line-height: 30px;
}
```

### Body Text / Notes
```css
.body-text {
  color: #000;
  font-family: "Source Sans Pro", sans-serif;
  font-size: 23px;
  font-weight: 600;
  line-height: 30px;
}
```

### Button Primary
```css
.btn-primary {
  display: flex;
  width: 209px;
  height: 42px;
  padding: 14px 17px;
  justify-content: center;
  align-items: center;
  gap: 10px;
  
  background-color: #FFD700;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  
  color: #002198;
  font-family: "Source Sans Pro", sans-serif;
  font-size: 16px;
  font-weight: 600;
  text-transform: uppercase;
}
```

### Button Slot
```css
.btn-slot {
  display: flex;
  padding: 8px 16px;
  justify-content: center;
  align-items: center;
  
  background-color: #002198;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  
  color: #FFFFFF;
  font-family: "Source Sans Pro", sans-serif;
  font-size: 14px;
  font-weight: 600;
}
```

### Checkbox
```css
.checkbox {
  display: flex;
  align-items: center;
  gap: 16px;
}

.checkbox-box {
  width: 24px;
  height: 24px;
  border: 2px solid #002198;
  border-radius: 4px;
  background: #FFFFFF;
}

.checkbox-label {
  color: #000;
  font-family: "Source Sans Pro", sans-serif;
  font-size: 18px;
  font-weight: 400;
}

.checkbox-duration {
  color: #666;
  font-size: 14px;
  margin-left: 8px;
}
```

### Input Field
```css
.input-label {
  display: block;
  margin-bottom: 8px;
  color: #000;
  font-family: "Source Sans Pro", sans-serif;
  font-size: 16px;
  font-weight: 600;
}

.input-field {
  width: 100%;
  padding: 12px 16px;
  background: #FFFFFF;
  border: 1px solid #E0E0E0;
  border-radius: 4px;
  
  font-family: "Source Sans Pro", sans-serif;
  font-size: 16px;
  color: #000;
}

.input-field:focus {
  outline: none;
  border-color: #002198;
}
```

### Time Slot
```css
.time-slot {
  display: flex;
  align-items: center;
  padding: 16px;
  border-radius: 8px;
  margin-bottom: 8px;
}

.time-slot--free {
  background-color: #FFD700;
}

.time-slot--occupied {
  background-color: #9E9E9E;
}

.time-slot__dot {
  width: 12px;
  height: 12px;
  border-radius: 50%;
  margin-right: 16px;
}

.time-slot__dot--free {
  background-color: #90EE90;
}

.time-slot__dot--occupied {
  background-color: #666;
}

.time-slot__status {
  font-size: 16px;
  min-width: 50px;
}

.time-slot__time {
  flex: 1;
  font-size: 16px;
}
```

### Termin Box (Terminkarte)
```css
.termin-box {
  background-color: #FFFFFF;
  border-radius: 8px;
  padding: 16px 20px;
}

.termin-box__content {
  font-family: "Source Sans Pro", sans-serif;
  font-size: 18px;
  color: #000;
}
```

### Contact Link
```css
.contact-link {
  display: flex;
  align-items: center;
  gap: 8px;
  
  color: #002198;
  font-family: "Source Sans Pro", sans-serif;
  font-size: 14px;
  text-decoration: none;
}
```

---

## Spacing

> **UX Design Note:** Carefully manage the spaces between elements. Keep them clean and balanced. Consistent spacing creates visual hierarchy and improves readability. When in doubt, use more whitespace rather than less.

```css
:root {
  --spacing-xs: 4px;
  --spacing-sm: 8px;
  --spacing-md: 16px;
  --spacing-lg: 24px;
  --spacing-xl: 32px;
}
```

### Spacing Guidelines

| Context | Spacing | Usage |
|---------|---------|-------|
| Between form fields | 16px | Inputs, checkboxes |
| Between sections | 32px | "Ich brauche..." to "Kontakt" |
| Inside components | 16px | Padding in slots, cards |
| Title to content | 24px | Screen title to first element |
| List items | 8px | Time slots, checkbox options |

---

## Layout

```css
:root {
  --container-max-width: 420px;
  --container-padding: 24px;
  --border-radius: 4px;
  --border-radius-lg: 8px;
}
```

---

## global.css

```css
@import url('https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@400;600;700&display=swap');

*, *::before, *::after {
  box-sizing: border-box;
  margin: 0;
  padding: 0;
}

:root {
  --color-primary: #FFD700;
  --color-primary-dark: #E6C200;
  --color-text-title: #002198;
  --color-text-body: #000000;
  --color-text-muted: #666666;
  --color-bg-page: #FFF8E7;
  --color-bg-card: #FFFBF0;
  --color-bg-white: #FFFFFF;
  --color-slot-free: #FFD700;
  --color-slot-occupied: #9E9E9E;
  --color-dot-free: #90EE90;
  --color-border: #E0E0E0;
  
  --font-primary: 'Source Sans Pro', sans-serif;
  
  --spacing-xs: 4px;
  --spacing-sm: 8px;
  --spacing-md: 16px;
  --spacing-lg: 24px;
  --spacing-xl: 32px;
  
  --container-max-width: 420px;
  --container-padding: 24px;
}

body {
  font-family: var(--font-primary);
  color: var(--color-text-body);
  background-color: var(--color-bg-page);
  line-height: 1.4;
  -webkit-font-smoothing: antialiased;
}

.screen {
  min-height: 100vh;
  padding: var(--spacing-xl) var(--container-padding);
}

.screen-title {
  color: var(--color-text-title);
  font-size: 30px;
  font-weight: 600;
  line-height: 35px;
  margin-bottom: var(--spacing-xl);
}

.section-title {
  color: var(--color-text-title);
  font-size: 24px;
  font-weight: 600;
  line-height: 30px;
  margin-bottom: var(--spacing-md);
}

.btn-primary {
  display: flex;
  width: 209px;
  height: 42px;
  padding: 14px 17px;
  justify-content: center;
  align-items: center;
  gap: 10px;
  background-color: var(--color-primary);
  color: var(--color-text-title);
  font-family: var(--font-primary);
  font-size: 16px;
  font-weight: 600;
  text-transform: uppercase;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.btn-primary:hover {
  background-color: var(--color-primary-dark);
}

a {
  color: var(--color-text-title);
  text-decoration: none;
}

a:hover {
  text-decoration: underline;
}
```
