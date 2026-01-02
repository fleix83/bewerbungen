# Termin Manager - Bewerbungen & Mehr

Ein webbasiertes Buchungssystem für Bewerbungsdienstleistungen.

## Technologie-Stack

- **Frontend:** Vue.js 3 (Composition API) + Vite
- **Backend:** PHP 8.x (REST API)
- **Datenbank:** MySQL 8.x
- **Styling:** Plain CSS mit CSS-Variablen

## Projektstruktur

```
terminmanager/
├── frontend/           # Vue.js Frontend
│   ├── src/
│   │   ├── assets/    # CSS & Bilder
│   │   ├── components/# Vue Komponenten
│   │   ├── views/     # View Komponenten
│   │   ├── stores/    # Pinia Stores
│   │   ├── services/  # API Services
│   │   └── router/    # Vue Router
│   └── .env           # Umgebungsvariablen
│
├── api/               # PHP Backend API
│   ├── config.php     # Datenbank-Konfiguration
│   ├── services.php   # Services Endpoint
│   ├── availability.php # Verfügbarkeits-Endpoint
│   ├── bookings.php   # Buchungs-Endpoint
│   ├── customers.php  # Kunden-Endpoint
│   └── appointments.php # Termin-Endpoint
│
└── docs/              # Dokumentation
    ├── PRD_termin_manager.md
    ├── implementation_plan.md
    └── styleguide.md
```

## Setup

### 1. Datenbank

Die Datenbank wurde bereits importiert:
- Datenbankname: `luftgaessli`
- Benutzer: `luftgaessli`
- Passwort: `luftgaessli2026!!!`

### 2. XAMPP/Apache Konfiguration

Stelle sicher, dass XAMPP läuft und die API über folgende URL erreichbar ist:
```
http://localhost/bewerbungen/terminmanager/api/
```

### 3. Frontend Setup

```bash
cd frontend
npm install
npm run dev
```

Die Frontend-Anwendung läuft dann auf `http://localhost:5173`

### 4. Umgebungsvariablen

Frontend `.env` Datei bereits konfiguriert:
```
VITE_API_BASE_URL=http://localhost/bewerbungen/terminmanager/api
```

## API Endpoints

### Public Endpoints

- `GET /api/services.php` - Liste aller Services
- `GET /api/availability.php?type=month&year=2025&month=1` - Monatliche Verfügbarkeit
- `GET /api/availability.php?type=day&date=2025-01-15&slots=1` - Tagesslots
- `POST /api/bookings.php` - Neue Buchung erstellen

### Admin Endpoints

- `GET /api/customers.php` - Kundenliste
- `GET /api/customers.php?id=1` - Kundendetails
- `GET /api/appointments.php?id=1` - Termindetails
- `PUT /api/appointments.php?id=1` - Status aktualisieren

## Verwendung

### Kunden-Flow

1. **Buchung starten** (`/buchen`)
   - Services auswählen
   - Kontaktdaten eingeben
   - "Termin wählen" klicken

2. **Datum auswählen** (`/buchen/datum`)
   - Monat navigieren
   - Verfügbaren Tag auswählen

3. **Zeit auswählen** (`/buchen/zeit/:datum`)
   - Freien Zeitslot auswählen
   - "Buchen" klicken

4. **Bestätigung** (`/buchen/bestaetigung`)
   - Buchungsdetails anzeigen
   - Adresse & Hinweise

### Admin-Bereich

1. **Kundenliste** (`/admin/kunden`)
   - Alle Kunden anzeigen
   - Sortieren nach Name/Datum

2. **Kundendetail** (`/admin/kunde/:id`)
   - Stammdaten
   - Buchungshistorie

3. **Terminkarte** (`/admin/termin/:id`)
   - Termindetails
   - Status ändern

## Development

### Frontend starten

```bash
cd frontend
npm run dev
```

### Frontend bauen

```bash
cd frontend
npm run build
```

## Features

✅ Service-Auswahl mit Checkboxen
✅ Kontaktformular mit Validierung
✅ Kalender-Ansicht für Datum
✅ Zeitslot-Auswahl
✅ Buchungsbestätigung
✅ Admin-Bereich für Termine
✅ Kundenverwaltung
✅ Status-Updates

## Datenbank-Schema

Verwendet folgende Haupttabellen:
- `services` - Dienstleistungen
- `customers` - Kunden
- `events` - Termine
- `bookings` - Service-Buchungen
- `availability_settings` - Verfügbarkeitsregeln
- `blocked_dates` - Gesperrte Tage

Wichtige Views:
- `v_available_slots` - Freie Slots
- `v_booking_details` - Buchungsdetails

Stored Procedures:
- `sp_create_customer_booking` - Buchung erstellen
- `sp_get_available_slots_for_date` - Slots abrufen

## Kontakt

Bei Fragen oder Problemen:
- WhatsApp: [076 575 60 52](https://wa.me/41765756052)
- Telefon: 076 575 60 52
