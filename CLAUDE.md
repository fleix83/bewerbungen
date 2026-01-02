# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This repository contains two main projects:

1. **Bewerbungen & Mehr Landing Page** - A static website built with Kirby CMS for a job application assistance service in Basel
2. **Terminmanager** - A Vue.js + PHP booking system for scheduling appointments

Both projects run on XAMPP (Apache + PHP + MySQL) and are served from the same domain.

## Development Environment

### Starting the Development Server

**Kirby CMS (Landing Page):**
```bash
# Start XAMPP Apache and MySQL
# Access at: http://localhost/bewerbungen/

# Alternative: Use PHP built-in server
composer start
# Access at: http://localhost:8000
```

**Terminmanager Frontend:**
```bash
cd terminmanager/frontend
npm install
npm run dev
# Access at: http://localhost:5173
```

**Terminmanager API:**
- Served via XAMPP at: `http://localhost/bewerbungen/terminmanager/api/`
- No build step required (plain PHP)

### Building for Production

```bash
cd terminmanager/frontend
npm run build
```

## Architecture

### Project Structure

```
bewerbungen/
├── kirby/              # Kirby CMS core (v5.2+)
├── site/               # Kirby site files (templates, blueprints, snippets)
├── content/            # Kirby content files
├── terminmanager/      # Booking system (separate app)
│   ├── frontend/       # Vue.js 3 SPA
│   │   ├── src/
│   │   │   ├── views/      # Page components (public/ and admin/)
│   │   │   ├── components/ # Reusable components
│   │   │   ├── stores/     # Pinia state management
│   │   │   ├── services/   # API layer (api.js)
│   │   │   └── router/     # Vue Router config
│   │   └── vite.config.js
│   └── api/            # PHP REST API
│       ├── config.php      # DB config + CORS headers
│       ├── services.php    # GET services
│       ├── availability.php # GET availability
│       ├── bookings.php    # POST bookings
│       ├── customers.php   # GET/PUT customers
│       ├── appointments.php # GET/PUT appointments
│       └── email.php       # Email notifications (PHPMailer)
├── design/             # Design mockups and assets
└── implementation/     # Implementation docs
```

### Kirby CMS (Landing Page)

**Architecture:**
- Minimal Kirby Plainkit setup
- Templates: `site/templates/` - PHP templates for rendering pages
- Blueprints: `site/blueprints/` - Panel field definitions
- Content: `content/` - Text content stored in flat files
- Entry point: `index.php` bootstraps Kirby and renders the current page

**Routing:**
- Kirby handles all routing automatically based on content folder structure
- No custom routes needed for basic pages

### Terminmanager (Booking System)

**Frontend Architecture:**
- Vue 3 with Composition API
- Pinia for state management (bookingStore.js, adminStore.js)
- Vue Router for SPA routing
- Axios for API calls (centralized in `services/api.js`)

**Frontend Routes:**
```
Public:
  /buchen              - Service selection + contact form
  /buchen/datum        - Calendar view (month)
  /buchen/zeit/:datum  - Time slot selection
  /buchen/bestaetigung - Booking confirmation

Admin:
  /admin/kunden        - Customer list
  /admin/kunde/:id     - Customer detail + booking history
  /admin/termin/:id    - Appointment card (status updates)
```

**Backend Architecture:**
- REST API with plain PHP (no framework)
- PDO for database access with prepared statements
- Shared config file (`config.php`) for DB connection + CORS
- Each endpoint is a separate PHP file
- Email sending via PHPMailer (composer dependency)

**API Endpoints:**
```php
GET  /api/services.php
GET  /api/availability.php?type=month&year=2025&month=1
GET  /api/availability.php?type=day&date=2025-01-15&slots=1
POST /api/bookings.php
GET  /api/customers.php
GET  /api/customers.php?id=1
GET  /api/appointments.php?id=1
PUT  /api/appointments.php?id=1
```

**Database Structure:**
- Database: `luftgaessli`
- Credentials: See `terminmanager/api/config.php`
- Main tables:
  - `services` - Bookable services (CV, cover letter, etc.)
  - `customers` - Customer contact information
  - `events` - Calendar events (bookings, personal blocks)
  - `bookings` - Junction table linking events to services
  - `availability_settings` - Weekly availability rules
  - `blocked_dates` - Holidays and exceptions
- Views:
  - `v_available_slots` - Available time slots calculation
  - `v_booking_details` - Booking details with customer info
- Stored Procedures:
  - `sp_create_customer_booking` - Atomic booking creation
  - `sp_get_available_slots_for_date` - Fetch free slots for a date

**Key Implementation Details:**
1. **Slot Duration:** Each service = 1 hour, bookings can combine multiple services
2. **Availability:** Mo-So, 8:00-22:00 (configurable via `availability_settings`)
3. **Blocking:** Events with `blocks_availability=1` block customer bookings
4. **Email:** Confirmation emails sent via PHPMailer (SMTP config in `EMAIL_SETUP.md`)

## Design System

### Terminmanager Styleguide

Located at: `terminmanager/styleguide.md`

**Colors:**
```css
--color-primary: #FFD700        /* Yellow - buttons, highlights */
--color-text-title: #002198     /* Dark blue - titles */
--color-bg-page: #FFF8E7        /* Cream - page background */
--color-slot-free: #FFD700      /* Yellow - available slots */
--color-slot-occupied: #9E9E9E  /* Gray - occupied slots */
--color-dot-free: #90EE90       /* Light green - free indicator */
```

**Typography:**
- Font: Source Sans Pro (400, 600, 700)
- Screen Title: 30px, weight 600
- Body Text: 23px, weight 600
- Buttons: 16px, weight 600, uppercase

**Component Patterns:**
- `.btn-primary` - Yellow button with dark blue text
- `.time-slot` - Slot display with colored dot indicator
- `.checkbox` - Custom checkbox with 24px box
- Max container width: 420px (mobile-first)

### Landing Page Styleguide

Located at: `implementation/implementation.md`

**Colors:**
```css
--color-primary: #002198        /* Dark blue */
--color-secondary: #F3D676      /* Yellow */
--color-background: #FAF5ED     /* Cream */
--color-dot-green: #95FCA8
--color-dot-yellow: #F3D676
--color-dot-blue: #002198
```

**Typography:**
- Font: Source Sans Pro (400, 600, 700)
- Hero Text: 30px, weight 600
- Address: 28px, weight 400, italic
- Border radius: 30px

## Common Development Tasks

### Working with the Terminmanager API

All API files share CORS configuration from `config.php`:
```php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
```

When adding new endpoints:
1. Start with `require_once 'config.php';`
2. Use `getDBConnection()` for database access
3. Use `sendJSON($data, $statusCode)` or `sendError($message, $statusCode)` for responses
4. Always use prepared statements for SQL queries

### Working with Vue Components

**Component Locations:**
- Booking flow: `terminmanager/frontend/src/components/booking/`
- Admin components: `terminmanager/frontend/src/components/admin/`
- Shared components: `terminmanager/frontend/src/components/common/`

**State Management:**
- Booking state: Use `bookingStore` from Pinia
- Admin state: Use `adminStore` from Pinia
- API calls: Import from `services/api.js`

### Database Changes

Schema file: `terminmanager/bewerbungen_mehr_database.sql`

When modifying the database:
1. Update the SQL schema file
2. Test locally in XAMPP/MySQL
3. Document any stored procedure changes
4. Update relevant API endpoints

### Kirby CMS Development

**Content Structure:**
- Each page is a folder in `content/`
- Page data stored in `.txt` files using Kirby's field syntax
- Panel (admin): Access at `/panel` (requires license)

**Template Hierarchy:**
- Default template: `site/templates/default.php`
- Create new templates by adding PHP files to `site/templates/`
- Template name matches content type

## Important Notes

### XAMPP Configuration

- Document root: `/Applications/XAMPP/xamppfiles/htdocs/`
- This project: `/Applications/XAMPP/xamppfiles/htdocs/bewerbungen/`
- MySQL access: localhost:3306
- PHP version: 8.2+ required by Kirby

### Environment Files

**Terminmanager Frontend `.env`:**
```
VITE_API_BASE_URL=http://localhost/bewerbungen/terminmanager/api
```

**API Database Config:**
Located in `terminmanager/api/config.php` (not in .env file)

### Email Configuration

Email setup is documented in `terminmanager/EMAIL_SETUP.md`. PHPMailer is configured in `terminmanager/api/email.php`.

### No Git Repository

This project is not currently version controlled (checked `.git` - none exists). When making changes, be extra careful with backups.

### Documentation

- Terminmanager PRD: `terminmanager/PRD_termin_manager.md`
- Implementation plan: `terminmanager/implementation_plan.md`
- Landing page specs: `implementation/implementation.md`
- SEO requirements: `implementation/seo.md`

## Cross-Project Integration

The landing page (Kirby CMS) and Terminmanager are separate applications:
- Landing page "Termin machen" button links to `/buchen` (Terminmanager entry point)
- Terminmanager runs as SPA on Vue Router
- Both share the same visual language (blue + yellow color scheme, Source Sans Pro)
