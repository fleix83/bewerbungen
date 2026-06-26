# Virtual Doorbell Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** A public `/klingel` page with one button that, when tapped, sends a loud max-priority ntfy push to the owner's phone in real time.

**Architecture:** A self-contained static HTML page POSTs to a new plain-PHP relay (`ring.php`). The relay rate-limits, then forwards to `ntfy.sh` with `Priority: 5`. The ntfy topic is a secret kept only in server config — never in the client page. No database, no accounts.

**Tech Stack:** PHP 8.2+ (reuses existing `terminmanager/api/config.php` helpers), static HTML/CSS/JS, ntfy push service, Apache `.htaccess` rewrite. Verification via `curl` + browser + phone (no test framework in this project).

## Global Constraints

- **PHP version:** 8.2+ (already required by the project).
- **Reuse existing API patterns:** every PHP endpoint starts with `require_once 'config.php';` and responds via `sendJSON($data, $code)` / `sendError($msg, $code)` (defined in `terminmanager/api/config.php`).
- **Secret stays server-side:** `NTFY_TOPIC` lives only in `terminmanager/api/config.local.php` (gitignored). It must NEVER appear in the client page, in git, or in any response body.
- **ntfy send:** `POST {NTFY_SERVER}/{NTFY_TOPIC}` with headers `Title: Klingel`, `Priority: 5`, `Tags: bell`. Keep header values ASCII (the 🔔 emoji comes from the `bell` tag, not from a header). The German text "Jemand klingelt an der Tür" goes in the **body** (UTF-8 safe), never in a header.
- **Rate limits:** per-IP 1 ring / 10 s → `429`; global cap 60 rings / rolling hour → `429`. File-based, best-effort, degrades gracefully if the temp dir is unwritable (still rings).
- **Client cooldown:** button disabled ~10 s after a successful ring.
- **URL:** `/klingel` (production) and `/bewerbungen/klingel` (local XAMPP).
- **Brand:** dark blue `#002198`, yellow `#F3D676`, cream background `#FAF5ED`, `30px` border-radius, Source Sans Pro. Button label text: `KLINGELN`.
- **German UI copy (exact strings):**
  - Success: `✓ Es hat geklingelt – einen Moment, ich komme gleich.`
  - Throttled (429): `Es hat eben schon geklingelt – bitte einen Moment.`
  - Error (network / 502 / other): `Konnte nicht benachrichtigen – bitte nochmal.`
  - In-progress: `Klingelt …`

---

## File Structure

| File | Responsibility |
|------|----------------|
| `terminmanager/api/config.local.example.php` | Committed template — document `NTFY_TOPIC` / `NTFY_SERVER`. |
| `terminmanager/api/config.local.php` | Local, gitignored — holds the real secret topic. Edited locally, never committed. |
| `terminmanager/api/ring.php` | New relay: POST-only, rate-limit, forward to ntfy, JSON responses. |
| `terminmanager/doorbell/index.html` | New public page: one button + JS + status states. |
| `terminmanager/doorbell/README.md` | One-time phone setup + topic rotation notes. |
| `.htaccess` | Add `^klingel/?$` rewrite to the doorbell page. |

Task order is dependency order: **1 → 2 → 3 → 4**. Config enables the relay; the relay backs the page; the rewrite + README give the page its public URL.

---

## Task 1: ntfy config

**Files:**
- Modify: `terminmanager/api/config.local.example.php`
- Modify (local, gitignored — do NOT commit): `terminmanager/api/config.local.php`

**Interfaces:**
- Consumes: nothing.
- Produces: two PHP constants available after `require_once 'config.php';` — `NTFY_TOPIC` (string, secret) and optional `NTFY_SERVER` (string, defaults to `https://ntfy.sh` when absent).

- [ ] **Step 1: Generate a secret topic value**

Run:
```bash
echo "klingel-$(openssl rand -hex 16)"
```
Expected: a line like `klingel-9f3a...c1` (48 hex chars after the prefix). Copy this value — it is used in Steps 2 and 3 and in Task 4's README.

- [ ] **Step 2: Add the documented placeholder to the committed example file**

Append to `terminmanager/api/config.local.example.php` (after the existing `DB_NAME` line):

```php

// Virtual doorbell (ntfy push). Keep NTFY_TOPIC SECRET — anyone who knows it
// can publish to / subscribe to your alerts. Generate one with:
//   echo "klingel-$(openssl rand -hex 16)"
define('NTFY_TOPIC', 'klingel-CHANGE-ME-to-a-long-random-string');
define('NTFY_SERVER', 'https://ntfy.sh'); // optional; default is https://ntfy.sh
```

- [ ] **Step 3: Set the real topic in the local (gitignored) config**

Append to `terminmanager/api/config.local.php` (this file already exists and holds DB creds; it is gitignored). Use the value generated in Step 1:

```php

// Virtual doorbell push topic — SECRET, do not commit.
define('NTFY_TOPIC', 'klingel-9f3a...c1'); // <- paste your value from Step 1
define('NTFY_SERVER', 'https://ntfy.sh');
```

- [ ] **Step 4: Verify the constant loads (and is NOT the placeholder)**

Run from the repo root:
```bash
php -r "require 'terminmanager/api/config.php'; echo defined('NTFY_TOPIC') && NTFY_TOPIC !== 'klingel-CHANGE-ME-to-a-long-random-string' ? 'OK '.substr(NTFY_TOPIC,0,9).'…' : 'MISSING';"
```
Expected: `OK klingel-…` (NOT `MISSING`).

- [ ] **Step 5: Confirm the secret is not staged**

Run:
```bash
git status --porcelain terminmanager/api/config.local.php
```
Expected: **no output** (the file is gitignored; the secret must not appear in git).

- [ ] **Step 6: Commit the example only**

```bash
git add terminmanager/api/config.local.example.php
git commit -m "Add ntfy doorbell config to example local config"
```

---

## Task 2: PHP relay endpoint `ring.php`

**Files:**
- Create: `terminmanager/api/ring.php`

**Interfaces:**
- Consumes: `require_once 'config.php';` → `sendJSON($data, $code)`, `sendError($msg, $code)`; constants `NTFY_TOPIC`, `NTFY_SERVER` from Task 1.
- Produces: HTTP endpoint at `terminmanager/api/ring.php`.
  - `POST` (no body needed) → `200 {"status":"ok"}` and a push is sent.
  - `GET`/other method → `405 {"error":"Method not allowed"}`.
  - Same IP within 10 s, or >60 rings in the last hour → `429 {"error":"…"}` (no push).
  - `NTFY_TOPIC` missing/placeholder → `500 {"error":"…"}`.
  - ntfy transport failure / non-2xx → `502 {"error":"Benachrichtigung fehlgeschlagen."}`.

- [ ] **Step 1: Create `ring.php` with the full relay**

Create `terminmanager/api/ring.php`:

```php
<?php
// Virtual doorbell relay: forwards a "someone is ringing" push to ntfy.
// config.php sets JSON + CORS headers, short-circuits OPTIONS, and defines
// sendJSON()/sendError(). It also loads config.local.php (NTFY_TOPIC).
require_once 'config.php';

// --- Method guard -----------------------------------------------------------
if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    sendError('Method not allowed', 405);
}

// --- Server config ----------------------------------------------------------
$placeholder = 'klingel-CHANGE-ME-to-a-long-random-string';
if (!defined('NTFY_TOPIC') || NTFY_TOPIC === '' || NTFY_TOPIC === $placeholder) {
    sendError('Doorbell not configured (NTFY_TOPIC missing).', 500);
}
$server = defined('NTFY_SERVER') && NTFY_SERVER !== '' ? rtrim(NTFY_SERVER, '/') : 'https://ntfy.sh';

// --- Rate limiting (file-based, best-effort) --------------------------------
$dir = sys_get_temp_dir();
$ip  = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
$now = time();

// per-IP: reject if the same IP successfully rang within the last 10 seconds
$ipFile = $dir . '/doorbell_ip_' . md5($ip);
$last   = @file_get_contents($ipFile);
if ($last !== false && is_numeric($last) && ($now - (int)$last) < 10) {
    sendError('Bitte einen Moment warten.', 429);
}

// global: reject if more than 60 rings happened in the last rolling hour
$globalFile = $dir . '/doorbell_global.json';
$window     = [];
$raw        = @file_get_contents($globalFile);
if ($raw !== false) {
    $decoded = json_decode($raw, true);
    if (is_array($decoded)) {
        $window = array_values(array_filter($decoded, fn($t) => is_numeric($t) && ($now - (int)$t) < 3600));
    }
}
if (count($window) >= 60) {
    sendError('Zu viele Klingelversuche. Bitte später erneut.', 429);
}

// --- Build + send the ntfy notification -------------------------------------
$body    = 'Jemand klingelt an der Tür (' . date('H:i') . ')';
$headers = ['Title: Klingel', 'Priority: 5', 'Tags: bell'];
$url     = $server . '/' . rawurlencode(NTFY_TOPIC);

$ok = false;
if (function_exists('curl_init')) {
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => $body,
        CURLOPT_HTTPHEADER     => $headers,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 5,
    ]);
    $resp = curl_exec($ch);
    $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    $ok = ($resp !== false && $code >= 200 && $code < 300);
} else {
    $ctx = stream_context_create(['http' => [
        'method'        => 'POST',
        'header'        => implode("\r\n", $headers),
        'content'       => $body,
        'timeout'       => 5,
        'ignore_errors' => true,
    ]]);
    $resp = @file_get_contents($url, false, $ctx);
    $ok   = ($resp !== false);
}

if (!$ok) {
    sendError('Benachrichtigung fehlgeschlagen.', 502);
}

// --- Record success for rate limiting ---------------------------------------
@file_put_contents($ipFile, (string) $now, LOCK_EX);
$window[] = $now;
@file_put_contents($globalFile, json_encode($window), LOCK_EX);

sendJSON(['status' => 'ok']);
```

- [ ] **Step 2: Subscribe to the topic so you can watch rings arrive**

In a **separate terminal**, stream the topic (use your real topic from Task 1). Leave this running during Steps 3–6:
```bash
curl -s "https://ntfy.sh/$(php -r "require 'terminmanager/api/config.php'; echo NTFY_TOPIC;")/json"
```
Expected: it connects and prints an `{"event":"open",...}` line, then waits.

- [ ] **Step 3: Verify the method guard (run BEFORE relying on POST)**

Ensure XAMPP Apache is running, then:
```bash
curl -s -o /dev/null -w "%{http_code}\n" http://localhost/bewerbungen/terminmanager/api/ring.php
```
Expected: `405`

- [ ] **Step 4: Verify a successful ring**

```bash
curl -s -w "\n%{http_code}\n" -X POST http://localhost/bewerbungen/terminmanager/api/ring.php
```
Expected: body `{"status":"ok"}` then `200`.
Also: the Step 2 subscriber terminal prints a `"event":"message"` line containing `"message":"Jemand klingelt an der Tür ...","priority":5` and tag `bell`.

- [ ] **Step 5: Verify per-IP throttling**

Immediately (within 10 s) repeat the POST:
```bash
curl -s -w "\n%{http_code}\n" -X POST http://localhost/bewerbungen/terminmanager/api/ring.php
```
Expected: body `{"error":"Bitte einen Moment warten."}` then `429`.

- [ ] **Step 6: Verify the missing-config guard**

Temporarily confirm the guard by pointing at the placeholder (no edit to real config needed — just reason-check the code path), OR run this one-off that simulates an unset topic:
```bash
php -r "
define('NTFY_TOPIC','klingel-CHANGE-ME-to-a-long-random-string');
\$placeholder='klingel-CHANGE-ME-to-a-long-random-string';
echo (NTFY_TOPIC===\$placeholder) ? '500-path OK' : 'unexpected';
"
```
Expected: `500-path OK` (confirms the placeholder triggers the `500` branch).

- [ ] **Step 7: Commit**

```bash
git add terminmanager/api/ring.php
git commit -m "Add ring.php relay: rate-limited ntfy doorbell push"
```

---

## Task 3: Public doorbell page

**Files:**
- Create: `terminmanager/doorbell/index.html`

**Interfaces:**
- Consumes: the `ring.php` endpoint from Task 2 (POST → `200`/`429`/`5xx`).
- Produces: a static page. API base is derived at runtime:
  `const base = location.pathname.startsWith('/bewerbungen') ? '/bewerbungen' : '';`
  `const RING_URL = base + '/terminmanager/api/ring.php';` — works at both `/klingel` and `/bewerbungen/klingel`.

- [ ] **Step 1: Create the page**

Create `terminmanager/doorbell/index.html`:

```html
<!doctype html>
<html lang="de">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <meta name="robots" content="noindex">
  <title>Klingel · Bewerbungen & Mehr</title>
  <style>
    :root {
      --blue: #002198;
      --yellow: #F3D676;
      --cream: #FAF5ED;
    }
    * { box-sizing: border-box; }
    body {
      margin: 0; min-height: 100dvh;
      display: flex; flex-direction: column;
      align-items: center; justify-content: center; gap: 2rem;
      padding: 2rem;
      background: var(--cream); color: var(--blue);
      font-family: "Source Sans Pro", system-ui, -apple-system, sans-serif;
      text-align: center;
    }
    h1 { font-size: 1.9rem; font-weight: 600; margin: 0; }
    p.sub { font-size: 1.1rem; font-weight: 400; margin: 0; max-width: 22rem; }
    button {
      font-family: inherit;
      width: 16rem; max-width: 80vw; aspect-ratio: 1;
      border: none; border-radius: 30px;
      background: var(--yellow); color: var(--blue);
      font-size: 1.6rem; font-weight: 700; text-transform: uppercase; letter-spacing: .05em;
      box-shadow: 0 10px 30px rgba(0,33,152,.18);
      cursor: pointer; transition: transform .08s ease, opacity .2s ease;
    }
    button:active { transform: scale(.97); }
    button:disabled { opacity: .5; cursor: default; }
    .bell { display: block; font-size: 2.6rem; margin-bottom: .4rem; }
    #status { min-height: 1.6rem; font-size: 1.15rem; font-weight: 600; }
    #status.ok  { color: #1a7a3c; }
    #status.err { color: #b00020; }
  </style>
</head>
<body>
  <h1>Bewerbungen &amp; Mehr</h1>
  <p class="sub">Sie sind da? Drücken Sie auf den Knopf – ich werde sofort benachrichtigt.</p>
  <button id="ring" type="button">
    <span class="bell" aria-hidden="true">🔔</span>
    Klingeln
  </button>
  <div id="status" role="status" aria-live="polite"></div>

  <script>
    const base = location.pathname.startsWith('/bewerbungen') ? '/bewerbungen' : '';
    const RING_URL = base + '/terminmanager/api/ring.php';
    const btn = document.getElementById('ring');
    const status = document.getElementById('status');

    function setStatus(text, kind) {
      status.textContent = text;
      status.className = kind || '';
    }

    async function ring() {
      btn.disabled = true;
      setStatus('Klingelt …', '');
      try {
        const res = await fetch(RING_URL, { method: 'POST' });
        if (res.ok) {
          setStatus('✓ Es hat geklingelt – einen Moment, ich komme gleich.', 'ok');
          setTimeout(() => { btn.disabled = false; }, 10000); // 10s cooldown
        } else if (res.status === 429) {
          setStatus('Es hat eben schon geklingelt – bitte einen Moment.', 'err');
          setTimeout(() => { btn.disabled = false; }, 3000);
        } else {
          setStatus('Konnte nicht benachrichtigen – bitte nochmal.', 'err');
          setTimeout(() => { btn.disabled = false; }, 2000);
        }
      } catch (e) {
        setStatus('Konnte nicht benachrichtigen – bitte nochmal.', 'err');
        setTimeout(() => { btn.disabled = false; }, 2000);
      }
    }

    btn.addEventListener('click', ring);
  </script>
</body>
</html>
```

- [ ] **Step 2: Verify the page loads at its direct path**

```bash
curl -s -o /dev/null -w "%{http_code}\n" http://localhost/bewerbungen/terminmanager/doorbell/index.html
```
Expected: `200`

- [ ] **Step 3: Verify the success flow in a browser**

Open `http://localhost/bewerbungen/terminmanager/doorbell/index.html`, click **Klingeln**.
Expected: status shows `Klingelt …` then `✓ Es hat geklingelt – einen Moment, ich komme gleich.`; the button greys out and re-enables after ~10 s; the ntfy subscriber (Task 2 Step 2) shows a new message.

- [ ] **Step 4: Verify the cooldown + the server-throttle text**

Two checks:
1. **Client cooldown:** after a successful ring, confirm the button stays greyed/disabled for ~10 s and then re-enables. A second click during that window does nothing (button is disabled).
2. **Server `429` text:** open the page in a **second tab/incognito window** and click **Klingeln** there within 10 s of the first tab's successful ring (the server sees the same IP twice).
   Expected: the second tab shows `Es hat eben schon geklingelt – bitte einen Moment.` in red.
   (The raw server `429` is also already verified by Task 2 Step 5.)

- [ ] **Step 5: Commit**

```bash
git add terminmanager/doorbell/index.html
git commit -m "Add doorbell page with KLINGELN button and status states"
```

---

## Task 4: Pretty URL, docs, end-to-end

**Files:**
- Modify: `.htaccess` (repo root)
- Create: `terminmanager/doorbell/README.md`

**Interfaces:**
- Consumes: the page from Task 3, the relay from Task 2.
- Produces: working URLs `/klingel` (prod) and `/bewerbungen/klingel` (local).

- [ ] **Step 1: Add the rewrite rule**

In the repo-root `.htaccess`, add the doorbell rule immediately after the existing `^buchen/(.*)$` line (inside the `<IfModule mod_rewrite.c>` block, before the dot-file/content blocks):

```apache
# Virtual doorbell page
RewriteRule ^klingel/?$ terminmanager/doorbell/index.html [L]
```

- [ ] **Step 2: Verify the pretty URL serves the page**

```bash
curl -s -o /dev/null -w "%{http_code}\n" http://localhost/bewerbungen/klingel
curl -s http://localhost/bewerbungen/klingel | grep -c "Klingeln"
```
Expected: `200`, then a count `>= 1` (the page HTML is served at the pretty URL).

- [ ] **Step 3: Write the setup README**

Create `terminmanager/doorbell/README.md`:

```markdown
# Virtual Doorbell (`/klingel`)

A public page with one button. When a client taps it, `ring.php` sends a
max-priority [ntfy](https://ntfy.sh) push to the owner's phone — loud, real-time,
works with the screen locked.

## One-time phone setup

1. Install the free **ntfy** app:
   - iOS: App Store → "ntfy"
   - Android: Play Store / F-Droid → "ntfy"
2. In the app, **Subscribe to a topic** and enter the exact value of `NTFY_TOPIC`
   from `terminmanager/api/config.local.php` (keep it secret).
3. Open that subscription's settings and set notifications to **Max priority**
   (and allow it to override silent / Do Not Disturb) so it rings loudly.
4. Test: open `https://www.bewerbungenundmehr.ch/klingel` and tap **Klingeln** —
   your phone should alert within ~1–2 seconds.

## Rotating the topic (if it ever leaks / gets spammed)

1. Generate a new value: `echo "klingel-$(openssl rand -hex 16)"`.
2. Update `NTFY_TOPIC` in `terminmanager/api/config.local.php` on the server.
3. Re-subscribe the app to the new topic; unsubscribe the old one.

## How it works

`/klingel` (rewritten in `.htaccess` to `terminmanager/doorbell/index.html`)
→ POST `terminmanager/api/ring.php` → rate-limit → POST `ntfy.sh/<topic>`
(`Priority: 5`, `Tags: bell`). The topic lives only in `config.local.php`
(gitignored) — never in the page or in git.

Rate limits: 1 ring / 10 s per IP, 60 rings / hour total.
```

- [ ] **Step 4: End-to-end test on the phone**

With the ntfy app subscribed (per the README), open `http://localhost/bewerbungen/klingel` on a device and tap **Klingeln**.
Expected: the phone receives a loud `🔔 Klingel` notification reading `Jemand klingelt an der Tür (HH:MM)` within ~1–2 s, even with the screen locked.

- [ ] **Step 5: Commit**

```bash
git add .htaccess terminmanager/doorbell/README.md
git commit -m "Add /klingel URL rewrite and doorbell setup README"
```

---

## After implementation

All four tasks committed on branch `feature/virtual-doorbell`. Use the
**superpowers:finishing-a-development-branch** skill to choose how to integrate
(merge to `main` / open a PR). Before deploying to production, confirm:
- `NTFY_TOPIC` is set in the **production** `config.local.php` (the secret is not deployed via git).
- The production `.htaccess` `RewriteBase` is `/` (existing project convention), so `/klingel` resolves at the domain root.
```
