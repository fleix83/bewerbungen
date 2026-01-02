# Implementation Plan: Termin Manager

## Projektstruktur

```
termin-manager/
├── frontend/                    # Vue.js Frontend
│   ├── public/
│   │   └── index.html
│   ├── src/
│   │   ├── assets/
│   │   │   └── styles/
│   │   │       └── global.css   # Globale CSS Variablen
│   │   ├── components/
│   │   │   ├── common/
│   │   │   │   ├── AppButton.vue
│   │   │   │   ├── AppCheckbox.vue
│   │   │   │   ├── AppInput.vue
│   │   │   │   └── AppHeader.vue
│   │   │   ├── booking/
│   │   │   │   ├── ServiceSelector.vue
│   │   │   │   ├── ContactForm.vue
│   │   │   │   ├── CalendarMonth.vue
│   │   │   │   ├── CalendarDay.vue
│   │   │   │   ├── TimeSlotList.vue
│   │   │   │   ├── TimeSlot.vue
│   │   │   │   └── BookingConfirmation.vue
│   │   │   └── admin/
│   │   │       ├── AppointmentCard.vue
│   │   │       ├── CustomerList.vue
│   │   │       └── CustomerDetail.vue
│   │   ├── views/
│   │   │   ├── public/
│   │   │   │   ├── BookingView.vue
│   │   │   │   ├── DateSelectView.vue
│   │   │   │   ├── TimeSelectView.vue
│   │   │   │   └── ConfirmationView.vue
│   │   │   └── admin/
│   │   │       ├── AppointmentView.vue
│   │   │       ├── CustomersView.vue
│   │   │       └── CustomerDetailView.vue
│   │   ├── stores/
│   │   │   ├── bookingStore.js  # Pinia Store für Buchungsflow
│   │   │   └── adminStore.js    # Pinia Store für Admin
│   │   ├── services/
│   │   │   └── api.js           # API-Aufrufe
│   │   ├── router/
│   │   │   └── index.js
│   │   ├── App.vue
│   │   └── main.js
│   ├── package.json
│   └── vite.config.js
│
├── backend/                     # Node.js Backend
│   ├── src/
│   │   ├── routes/
│   │   │   ├── services.js
│   │   │   ├── availability.js
│   │   │   ├── bookings.js
│   │   │   ├── customers.js
│   │   │   └── admin.js
│   │   ├── controllers/
│   │   │   ├── servicesController.js
│   │   │   ├── availabilityController.js
│   │   │   ├── bookingsController.js
│   │   │   └── customersController.js
│   │   ├── models/
│   │   │   └── db.js            # MySQL Connection
│   │   ├── middleware/
│   │   │   └── validation.js
│   │   └── app.js
│   ├── package.json
│   └── .env
│
├── database/
│   └── bewerbungen_mehr_database.sql
│
└── docs/
    ├── PRD_termin_manager.md
    ├── implementation_plan.md
    └── styleguide.md
```

---

## Phase 1: Setup & Grundstruktur (Tag 1)

### 1.1 Frontend Setup
```bash
npm create vite@latest frontend -- --template vue
cd frontend
npm install vue-router@4 pinia axios
```

### 1.2 Backend Setup
```bash
mkdir backend && cd backend
npm init -y
npm install express mysql2 cors dotenv express-validator
```

### 1.3 Datenbank
- MySQL Datenbank erstellen
- `bewerbungen_mehr_database.sql` importieren
- Connection testen

### 1.4 Globale Styles
- `global.css` mit CSS-Variablen erstellen
- Farbschema aus Figma übernehmen
- Basis-Typografie definieren

**Deliverables:**
- [ ] Vue-Projekt läuft auf localhost:5173
- [ ] Express-Server läuft auf localhost:3000
- [ ] DB-Connection funktioniert
- [ ] global.css mit Variablen

---

## Phase 2: Backend API (Tag 2)

### 2.1 Services Endpoint
```
GET /api/services
Response: [{ id, name, duration_slots, price }]
```

### 2.2 Availability Endpoints
```
GET /api/availability/month/:year/:month
Response: [{ date, has_free_slots: boolean }]

GET /api/availability/day/:date
Query: ?slots=1 (Anzahl benötigter Slots)
Response: [{ start_slot, end_slot, time_display, status }]
```

### 2.3 Booking Endpoint
```
POST /api/bookings
Body: {
  customer: { first_name, last_name, email, phone },
  event_date,
  start_slot,
  end_slot,
  service_ids: [1, 2],
  notes
}
Response: { success, event_id, message }
```

### 2.4 Admin Endpoints
```
GET /api/admin/customers
GET /api/admin/customers/:id
GET /api/admin/appointments/:id
PATCH /api/admin/appointments/:id/status
```

**Deliverables:**
- [ ] Alle Endpoints implementiert
- [ ] Postman/Insomnia Collection zum Testen
- [ ] Error Handling

---

## Phase 3: Kunden-Flow Frontend (Tag 3-4)

### 3.1 Screen: Buchung
**Komponenten:**
- `ServiceSelector.vue` - Checkboxen für Services
- `ContactForm.vue` - Formularfelder
- `BookingView.vue` - Container

**Logik:**
- Services von API laden
- Formularvalidierung
- State in Pinia Store speichern
- Navigation zu Datum-Auswahl

### 3.2 Screen: Datum auswählen
**Komponenten:**
- `CalendarMonth.vue` - Monatsansicht
- `CalendarDay.vue` - Einzelner Tag

**Logik:**
- Monatsdaten von API laden
- Farbcodierung (gelb/grau)
- Monatswechsel
- Tap → Navigation mit Datum

### 3.3 Screen: Zeit auswählen
**Komponenten:**
- `TimeSlotList.vue` - Container
- `TimeSlot.vue` - Einzelner Slot

**Logik:**
- Slots für gewähltes Datum laden
- Slot-Kombination basierend auf Anzahl Services
- "Buchen" → API-Call → Bestätigung

### 3.4 Screen: Bestätigung
**Komponenten:**
- `BookingConfirmation.vue`

**Logik:**
- Buchungsdaten aus Store anzeigen
- Statische Adresse + Hinweise
- WhatsApp/Telefon Links

**Deliverables:**
- [ ] Kompletter Buchungsflow funktioniert
- [ ] Mobile-optimiert
- [ ] Fehlerbehandlung (Slot nicht mehr frei)

---

## Phase 4: Admin-Bereich (Tag 5)

### 4.1 Screen: Terminkarte
**Komponenten:**
- `AppointmentCard.vue`

**Daten:**
- Kundendaten
- Termindaten
- Buchungsdetails
- Status-Änderung

### 4.2 Screen: Kundenliste
**Komponenten:**
- `CustomerList.vue`

**Funktionen:**
- Tabelle mit Sortierung
- Klick → Detail

### 4.3 Screen: Kundendetail
**Komponenten:**
- `CustomerDetail.vue`

**Daten:**
- Stammdaten
- Buchungshistorie

**Deliverables:**
- [ ] Admin-Views funktionieren
- [ ] Sortierung Kundenliste
- [ ] Status-Update möglich

---

## Phase 5: Polish & Testing (Tag 6)

### 5.1 UI Polish
- Animations/Transitions
- Loading States
- Error States
- Empty States

### 5.2 Testing
- Manuelle Tests aller Flows
- Edge Cases (keine Slots, Doppelbuchung)
- Mobile Testing

### 5.3 Bugfixes

**Deliverables:**
- [ ] Produktionsreif
- [ ] Keine kritischen Bugs

---

## API-Referenz

### Services
| Method | Endpoint | Beschreibung |
|--------|----------|--------------|
| GET | `/api/services` | Liste aktiver Services |

### Availability
| Method | Endpoint | Beschreibung |
|--------|----------|--------------|
| GET | `/api/availability/month/:year/:month` | Verfügbarkeit pro Tag im Monat |
| GET | `/api/availability/day/:date` | Freie Slots für ein Datum |

### Bookings
| Method | Endpoint | Beschreibung |
|--------|----------|--------------|
| POST | `/api/bookings` | Neue Buchung erstellen |
| GET | `/api/bookings/:id` | Buchungsdetails |

### Customers (Admin)
| Method | Endpoint | Beschreibung |
|--------|----------|--------------|
| GET | `/api/admin/customers` | Kundenliste |
| GET | `/api/admin/customers/:id` | Kundendetail mit Buchungen |

### Appointments (Admin)
| Method | Endpoint | Beschreibung |
|--------|----------|--------------|
| GET | `/api/admin/appointments/:id` | Termindetails |
| PATCH | `/api/admin/appointments/:id/status` | Status ändern |

---

## Datenbank-Queries

### Freie Slots für Monat
```sql
SELECT 
    DATE(event_date) as date,
    COUNT(*) > 0 as has_free_slots
FROM v_available_slots
WHERE event_date BETWEEN :start_date AND :end_date
GROUP BY DATE(event_date);
```

### Freie Slots für Tag (mit Slot-Kombination)
```sql
CALL sp_get_available_slots_for_date(1, :date, :required_slots);
```

### Buchung erstellen
```sql
CALL sp_create_customer_booking(
    :customer_number,
    :first_name,
    :last_name,
    :email,
    :phone,
    :event_date,
    :start_slot,
    :end_slot,
    :service_ids,  -- comma-separated
    :notes,
    @event_id,
    @success,
    @message
);
SELECT @event_id, @success, @message;
```

---

## Risiken & Mitigation

| Risiko | Wahrscheinlichkeit | Mitigation |
|--------|-------------------|------------|
| Race Condition bei Buchung | Mittel | DB-Transaction + Lock |
| Keine freien Slots | Niedrig | Hinweis auf Telefon-Kontakt |
| Mobile Performance | Niedrig | Lazy Loading, minimale Bundles |

---

## Nächste Schritte nach v1

1. Email-Bestätigungen (Nodemailer)
2. Kalender-Integration (Vollkalender für Admin)
3. Terminänderung durch Kunde
4. Reporting/Statistiken
