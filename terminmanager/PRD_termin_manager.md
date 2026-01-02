# PRD: Termin Manager - Bewerbungen & Mehr

## Überblick

Der Termin Manager ist ein webbasiertes Buchungssystem für das Bewerbungsservice "Bewerbungen & Mehr". Kunden können über die Landing Page Termine für Bewerbungsdienstleistungen buchen.

---

## Ziele

1. Kunden können selbstständig Termine online buchen
2. Felix sieht alle Buchungen im internen Kalender
3. Doppelbuchungen werden systemseitig verhindert
4. Einfache, mobile-first Benutzerführung

---

## Benutzerrollen

| Rolle | Beschreibung |
|-------|--------------|
| **Kunde** | Bucht Termine über die öffentliche Webseite |
| **Admin (Felix)** | Verwaltet Termine, sieht Kundendaten, pflegt Kalender |

---

## Kunden-Flow (Public)

### Screen 1: Buchung (`/buchen`)

**Einstieg:** Kunde klickt auf Landing Page "Termin machen"

**Funktionen:**
- Service-Auswahl via Checkboxen:
  - ☐ Lebenslauf (1h) 
  - ☐ Bewerbungsschreiben (1h)
  - ☐ Etwas anderes (1h)
- Optionales Freitextfeld "Ich brauche..."
- Kontaktformular:
  - Vorname (required)
  - Nachname (required)
  - Email (required)
  - Telefon (optional)
- Button "Termin wählen" → navigiert zu Screen 2
- Alternative Kontaktoptionen:
  - WhatsApp-Link
  - Telefonnummer (076 575 60 52)

**Validierung:**
- Mindestens ein Service muss ausgewählt sein
- Vorname, Nachname, Email sind Pflichtfelder
- Email-Format wird geprüft

---

### Screen 2: Datum auswählen (`/buchen/datum`)

**Funktionen:**
- Monatskalender-Ansicht (aktueller Monat als Default)
- Navigation: Monat vor/zurück
- Tagesanzeige:
  - **Gelb gestreift:** Freie Slots vorhanden
  - **Grau:** Keine freien Slots / blockiert
- Tap auf Tag → navigiert zu Screen 3
- Vergangene Tage sind nicht wählbar
- Feiertage sind grau (blocked_dates)

**Daten:**
- Zeigt symbolisch freie Verfügbarkeit pro Tag
- Keine Detail-Uhrzeiten auf diesem Screen

---

### Screen 3: Zeit auswählen (`/buchen/zeit/:datum`)

**Funktionen:**
- Anzeige: Wochentag + Datum (z.B. "MITTWOCH 06. JANUAR 2026")
- Liste aller Zeitslots (8:00 - 22:00):
  - **Gelb + "frei":** Buchbar, mit "Buchen"/"Wählen" Button
  - **Grau + "belegt":** Nicht buchbar, kein Button
- Slot-Kombinierung:
  - 1 Service = 1h Slot
  - 2 Services = 2h kombinierter Slot
  - 3 Services = 3h kombinierter Slot
- Button "Buchen" → erstellt Buchung, navigiert zu Screen 4

**Logik:**
- Prüft Verfügbarkeit gegen `events` Tabelle
- Berücksichtigt `blocks_availability` Flag
- Kombinierte Slots nur wenn alle Teil-Slots frei

---

### Screen 4: Bestätigung (`/buchen/bestaetigung`)

**Anzeige:**
- **Ihr Termin:**
  - Wochentag
  - Datum
  - Uhrzeit (z.B. "14 - 16 Uhr")
- **Adresse:**
  - Luftgässlein 3, Basel
  - 1. Stock
- **Ihre Buchung:**
  - Liste der Services mit Einzelpreisen
  - Total-Summe
- **Hinweise:**
  - "Bitte bringen Sie alle vorhandenen Unterlagen mit, Zeugnisse, Lebenslauf (falls vorhanden) bereits vorhandene Dokumente..."
  - "Bei Fragen, kontaktieren Sie mich. Ich freue mich auf Sie."
- **Kontakt:**
  - WhatsApp-Link
  - Telefonnummer

---

## Admin-Bereich (Internal)

### Screen: Terminkarte (`/admin/termin/:id`)

**Zweck:** Detailansicht eines Kundentermins für Felix

**Anzeige:**
- Kundendaten:
  - Kundennummer
  - Vorname, Nachname
  - Email
  - Telefon
- Termindaten:
  - Datum + Uhrzeit
  - Status (pending/confirmed/cancelled/completed)
- Buchungsdetails:
  - Services mit Preisen
  - Notizen
  - Total
- Aktionen:
  - Status ändern
  - Termin stornieren
  - Notiz hinzufügen

---

### Screen: Kundenliste (`/admin/kunden`)

**Funktionen:**
- Tabellenansicht aller Kunden
- Spalten: Vorname, Nachname, Erstelldatum
- Sortierung:
  - Nach Nachname (A-Z / Z-A)
  - Nach Datum (neu-alt / alt-neu)
- Suchfeld (optional)
- Tap auf Zeile → öffnet Kundendetail

---

### Screen: Kundendetail (`/admin/kunde/:id`)

**Anzeige:**
- Kundenstammdaten:
  - Kundennummer
  - Vorname, Nachname
  - Email, Telefon
  - Erstellt am
- Buchungshistorie:
  - Liste aller Termine des Kunden
  - Datum, Uhrzeit, Services, Status
  - Tap → öffnet Terminkarte

---

## Datenbank-Integration

**Verwendete Tabellen:**
- `users` - Admin-Benutzer
- `customers` - Kundendaten
- `services` - Dienstleistungen
- `events` - Termine
- `bookings` - Service-Buchungen pro Termin
- `event_types` - Terminarten
- `availability_settings` - Verfügbarkeitsregeln
- `blocked_dates` - Feiertage/blockierte Tage

**Wichtige Views:**
- `v_available_slots` - Freie Buchungsslots
- `v_daily_schedule` - Tagesübersicht
- `v_booking_details` - Buchungsdetails

**Stored Procedures:**
- `sp_check_availability` - Verfügbarkeitsprüfung
- `sp_create_customer_booking` - Buchung erstellen
- `sp_get_available_slots_for_date` - Slots pro Tag

---

## Technische Anforderungen

| Bereich | Technologie |
|---------|-------------|
| Frontend | Vue.js 3 (Composition API) |
| Styling | Plain CSS mit globalen Variablen |
| Backend | Node.js + Express (oder PHP) |
| Datenbank | MySQL 8.x |
| Hosting | TBD |

---

## Nicht im Scope (v1)

- Email-Bestätigungen
- SMS-Benachrichtigungen  
- Online-Bezahlung
- Terminänderung durch Kunde
- Kalender-Sync (iCal)
- Multi-User Admin

---

## Erfolgskriterien

1. Kunde kann in < 2 Minuten einen Termin buchen
2. Keine Doppelbuchungen möglich
3. Mobile-optimierte Darstellung
4. Alle Buchungen erscheinen korrekt in der Datenbank
