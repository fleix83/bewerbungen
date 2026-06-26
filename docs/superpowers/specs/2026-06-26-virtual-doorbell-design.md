# Virtual Doorbell — Design Spec

**Date:** 2026-06-26
**Status:** Approved (pending spec review)

## Problem

The office has no working doorbell. Clients arriving for appointments have no way to
signal that they are at the door. We need a virtual doorbell: a link the owner (Felix)
can send to a client; when the client taps a button, Felix receives a **real-time,
loud alert on his phone** — even when his screen is locked and no browser tab is open.

## Solution Summary

A public page at **`https://www.bewerbungenundmehr.ch/klingel`** with a single big
"KLINGELN" button. On tap, a PHP relay endpoint sends a **max-priority push via
[ntfy](https://ntfy.sh)** to Felix's phone. ntfy is free, open-source, cross-platform
(iOS + Android), and its app delivers a loud alert that bypasses silent mode when the
topic is configured for max priority.

```
Client opens /klingel
   │  taps "KLINGELN"
   ▼
POST /terminmanager/api/ring.php          (same-origin fetch)
   │  rate-limit check (per IP, file-based)
   ▼
POST https://ntfy.sh/<secret-topic>       (Priority: 5, Title, Tags)
   │
   ▼
ntfy app on Felix's phone → LOUD alert "🔔 Jemand klingelt an der Tür"
```

## Decisions (from brainstorming)

| Question | Decision |
|----------|----------|
| Where does the alert land? | Phone push notification |
| Phone OS | Both / unsure → must be cross-platform |
| Push channel | **ntfy** (free, open-source, loud max-priority alerts) |
| Client identifies themselves? | No — single anonymous one-tap button |
| Client confirmation after ring? | Yes — show a clear "it rang" message |
| Backup channel (email)? | No — ntfy only for now |

## Components

### 1. Public doorbell page — `terminmanager/doorbell/index.html`

- Self-contained static page (inline CSS + a small `<script>`); no Vue build step.
- Brand-styled to match the site: dark blue `#002198`, yellow `#F3D676`, cream
  background `#FAF5ED`, generous `30px` border-radius, Source Sans Pro.
- Layout: a short heading, one large circular/rounded **KLINGELN** button (bell icon),
  and a status line below it.
- Behavior on tap:
  1. Disable the button, show a "Klingelt…" intermediate state.
  2. `fetch('…/terminmanager/api/ring.php', { method: 'POST' })`.
  3. On `200`: show **"✓ Es hat geklingelt – einen Moment, ich komme gleich."** and keep
     the button disabled for a **~10s client-side cooldown** (matches the server rate
     limit; prevents accidental double-rings), then re-enable.
  4. On `429`: show **"Es hat eben schon geklingelt – bitte einen Moment."**
  5. On any other error / network failure: show **"Konnte nicht benachrichtigen –
     bitte nochmal."** and re-enable the button.
- The page contains **no secrets** — it only knows the relay URL, never the ntfy topic.

### 2. Clean URL — `.htaccess`

Add a rewrite so the page is reachable at `/klingel` (production) and
`/bewerbungen/klingel` (local XAMPP). Place it alongside the existing Terminmanager
exclusion rules in the root `.htaccess`:

```apache
# Virtual doorbell page
RewriteRule ^klingel/?$ terminmanager/doorbell/index.html [L]
```

This must sit **before** the catch-all Kirby rule and after `RewriteEngine on`. The
existing `RewriteRule ^terminmanager/ - [L]` already lets the relay PHP and any static
assets under `terminmanager/` be served directly.

### 3. PHP relay endpoint — `terminmanager/api/ring.php`

- `require_once 'config.php';` to reuse `sendJSON()` / `sendError()` and headers.
- Accept **POST only** (return `405` otherwise; `config.php` already short-circuits
  `OPTIONS`).
- **Rate limiting** (file-based, no DB):
  - Store a small JSON/timestamp record per client IP under a writable temp dir
    (e.g. `sys_get_temp_dir() . '/doorbell_<hash(ip)>'`).
  - Reject with `429` if the same IP rang within the last **10 seconds**.
  - Also enforce a coarse global/hourly cap (e.g. **max 60 rings/hour total**) to bound
    abuse from many IPs; on exceed, return `429`. Use a single global counter file with
    an hourly window.
  - File access guarded so a missing/unwritable dir degrades gracefully (still rings,
    logs a warning) rather than blocking legitimate use.
- **Send to ntfy**:
  - Read `NTFY_TOPIC` (required) and `NTFY_SERVER` (default `https://ntfy.sh`) from
    config (defined in `config.local.php`).
  - If `NTFY_TOPIC` is undefined/empty → `500` with a clear server-config error (do not
    fall back to a guessable topic).
  - `POST {NTFY_SERVER}/{NTFY_TOPIC}` with:
    - Body: `Jemand klingelt an der Tür` + a `H:i`/`d.m.` timestamp.
    - Header `Title: 🔔 Klingel`
    - Header `Priority: 5` (max / urgent)
    - Header `Tags: bell`
  - Use cURL; fall back to `file_get_contents` with a stream context if cURL is
    unavailable. Set a short timeout (~5s).
  - On ntfy non-2xx or transport failure → `502` (page tells the client to retry).
  - On success → `200 { "status": "ok" }`.

### 4. Config — `config.local.php` / `config.local.example.php`

Add to `config.local.example.php` (committed) and the real `config.local.php`
(gitignored):

```php
// Virtual doorbell (ntfy push). Keep NTFY_TOPIC secret — anyone who knows it
// can publish to / subscribe to your alerts.
define('NTFY_TOPIC', 'klingel-CHANGE-ME-to-a-long-random-string');
define('NTFY_SERVER', 'https://ntfy.sh'); // optional; default is https://ntfy.sh
```

### 5. Phone setup (one-time, documented — not code)

1. Install the free **ntfy** app (App Store / Play Store).
2. Subscribe to the secret topic (the value of `NTFY_TOPIC`).
3. In the app, set that topic's notifications to **max priority / loud** so it rings
   and bypasses silent mode.

A short `terminmanager/doorbell/README.md` will capture these steps plus how to rotate
the topic.

## Security & Abuse Considerations

- **Topic secrecy:** the ntfy topic is the only access control ntfy.sh offers. It lives
  server-side only (`config.local.php`), never in the client page or git. Use a long
  random string. Rotating = change the value + re-subscribe on the phone.
- **Rate limiting:** per-IP (10s) + global hourly cap prevent a public button from
  flooding Felix's phone.
- **No PII / no DB:** the ring carries no personal data; nothing is persisted beyond
  transient rate-limit files.
- **Same-origin:** the page and relay share an origin, so no CORS exposure is added
  for this flow.

## Testing Strategy

- **Relay unit-ish checks (manual/curl):**
  - `GET ring.php` → `405`.
  - First `POST` → `200 {status:ok}`; immediate second `POST` from same IP → `429`.
  - With `NTFY_TOPIC` unset → `500` config error.
- **ntfy integration:** subscribe a desktop browser (ntfy web app) to the topic during
  dev and confirm a ring arrives with the right title/priority.
- **Page UX:** verify success, `429`, and error states render the correct German
  messages and the cooldown disables the button.
- **End-to-end:** on Felix's phone with the ntfy app subscribed, open `/klingel`, tap,
  confirm a loud alert arrives within ~1–2s while the screen is locked.

## Out of Scope (YAGNI)

- Client name / contact entry (chosen: anonymous one-tap).
- Accounts, auth, or a database table for rings.
- Email/SMS backup channel (ntfy only for now).
- Self-hosted ntfy server (public ntfy.sh is sufficient for "someone is ringing"; can
  revisit for privacy later).
- Per-client unique links (one shared `/klingel` URL).

## Files Touched

| File | Change |
|------|--------|
| `terminmanager/doorbell/index.html` | New — public doorbell page |
| `terminmanager/doorbell/README.md` | New — phone setup + topic rotation notes |
| `terminmanager/api/ring.php` | New — PHP relay → ntfy |
| `terminmanager/api/config.local.example.php` | Add `NTFY_TOPIC` / `NTFY_SERVER` |
| `terminmanager/api/config.local.php` | (local, gitignored) set real topic |
| `.htaccess` | Add `^klingel/?$` rewrite |
