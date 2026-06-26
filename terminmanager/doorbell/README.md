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

Rate limits: 1 ring / 10 s per IP, 60 rings / hour total. The counters live in
`terminmanager/api/doorbell-cache/` (an app-local dir, because the system temp
dir is not writable by the Apache `daemon` user under XAMPP). That directory must
be **writable by the web-server user** — `ring.php` creates it automatically, but
if throttling ever silently stops working, `chmod 775` (or `777`) the directory.
Its runtime files are gitignored.
