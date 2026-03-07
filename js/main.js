/* ============================================================
   BEWERBUNGEN & MEHR — Animations & Interactions
   ============================================================ */

(function () {
    'use strict';

    /* ---------- Slide-in on scroll (IntersectionObserver) ---------- */
    function initSlideIn() {
        var elements = document.querySelectorAll('.slide-in');
        if (!elements.length) return;

        var observer = new IntersectionObserver(
            function (entries) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        observer.unobserve(entry.target);
                    }
                });
            },
            {
                threshold: 0.15,
                rootMargin: '0px 0px -40px 0px'
            }
        );

        elements.forEach(function (el) {
            observer.observe(el);
            // After slide-in completes, remove opacity/transition so mix-blend-mode works
            if (el.classList.contains('deutsch__visual')) {
                el.addEventListener('transitionend', function handler() {
                    el.removeEventListener('transitionend', handler);
                    el.classList.add('blend-ready');
                });
            }
        });
    }

    /* ---------- Smooth scroll for anchor links ---------- */
    function initSmoothScroll() {
        document.querySelectorAll('a[href^="#"]').forEach(function (link) {
            link.addEventListener('click', function (e) {
                var target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });
    }

    /* ---------- Nav scroll effect ---------- */
    function initNavScroll() {
        var nav = document.querySelector('.topnav');
        if (!nav) return;

        window.addEventListener('scroll', function () {
            if (window.scrollY > 60) {
                nav.classList.add('is-scrolled');
            } else {
                nav.classList.remove('is-scrolled');
            }
        }, { passive: true });
    }

    /* ---------- CSV Parser ---------- */
    function parseCSV(text, delimiter) {
        var lines = text.trim().split('\n');
        if (lines.length < 2) return [];
        var headers = lines[0].split(delimiter);
        var rows = [];
        for (var i = 1; i < lines.length; i++) {
            var values = lines[i].split(delimiter);
            if (values.length < headers.length) continue;
            var row = {};
            for (var j = 0; j < headers.length; j++) {
                row[headers[j].trim()] = values[j].trim();
            }
            rows.push(row);
        }
        return rows;
    }

    /* ---------- Stellen Table Renderer ---------- */
    function renderStellenTable(rows) {
        var table = document.createElement('table');
        table.className = 'stellen-table';

        var thead = document.createElement('thead');
        var headerRow = document.createElement('tr');
        var columns = ['titel', 'firma', 'beschreibung', 'pensum'];
        var labels = { titel: 'Titel', firma: 'Firma', beschreibung: 'Beschreibung', pensum: 'Pensum' };
        columns.forEach(function (col) {
            var th = document.createElement('th');
            th.textContent = labels[col];
            headerRow.appendChild(th);
        });
        thead.appendChild(headerRow);
        table.appendChild(thead);

        var tbody = document.createElement('tbody');
        rows.forEach(function (row) {
            var tr = document.createElement('tr');
            if (row.url) {
                tr.style.cursor = 'pointer';
                tr.setAttribute('role', 'link');
                tr.setAttribute('tabindex', '0');
                tr.addEventListener('click', function () {
                    window.open(row.url, '_blank', 'noopener');
                });
                tr.addEventListener('keydown', function (e) {
                    if (e.key === 'Enter') window.open(row.url, '_blank', 'noopener');
                });
            }
            columns.forEach(function (col) {
                var td = document.createElement('td');
                td.setAttribute('data-label', labels[col]);
                td.textContent = row[col] || '';
                tr.appendChild(td);
            });
            tbody.appendChild(tr);
        });
        table.appendChild(tbody);
        return table;
    }

    /* ---------- Stellen Modal ---------- */
    function initStellenModal() {
        var openBtn = document.getElementById('stellen-open');
        var modal = document.getElementById('stellen-modal');
        var body = document.getElementById('stellen-modal-body');
        if (!openBtn || !modal) return;

        var closeBtns = modal.querySelectorAll('[data-stellen-close]');
        var loaded = false;

        function openModal() {
            modal.style.setProperty('--stellen-top', '170px');
            modal.removeAttribute('hidden');
            requestAnimationFrame(function () {
                requestAnimationFrame(function () {
                    modal.classList.add('is-active');
                });
            });
            document.body.classList.add('stellen-modal-open');
            modal.querySelector('.stellen-modal__close').focus();

            if (!loaded) {
                loaded = true;
                var supabaseUrl = 'https://mbhljibgcykdbvcrwkof.supabase.co/rest/v1/stellen';
                var supabaseKey = 'sb_publishable_wJW02dLkzBh1Eo5Q3IFn6w_E0pG6Z-N';
                fetch(supabaseUrl + '?select=titel,firma,beschreibung,pensum,url&order=id.asc', {
                    headers: {
                        'apikey': supabaseKey,
                        'Authorization': 'Bearer ' + supabaseKey
                    }
                })
                    .then(function (res) { return res.json(); })
                    .then(function (rows) {
                        body.innerHTML = '';
                        if (!rows.length) {
                            body.textContent = 'Keine Stellen gefunden.';
                            return;
                        }
                        body.appendChild(renderStellenTable(rows));
                    })
                    .catch(function () {
                        body.textContent = 'Stellen konnten nicht geladen werden.';
                    });
            }
        }

        function closeModal() {
            modal.classList.remove('is-active');
            document.body.classList.remove('stellen-modal-open');
            modal.addEventListener('transitionend', function handler() {
                modal.removeEventListener('transitionend', handler);
                if (!modal.classList.contains('is-active')) {
                    modal.setAttribute('hidden', '');
                }
            });
            openBtn.focus();
        }

        openBtn.addEventListener('click', openModal);

        closeBtns.forEach(function (btn) {
            btn.addEventListener('click', closeModal);
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && modal.classList.contains('is-active')) {
                closeModal();
            }
        });

        // Focus trap
        modal.addEventListener('keydown', function (e) {
            if (e.key !== 'Tab') return;
            var focusable = modal.querySelectorAll('button, [href], [tabindex]:not([tabindex="-1"])');
            if (!focusable.length) return;
            var first = focusable[0];
            var last = focusable[focusable.length - 1];
            if (e.shiftKey && document.activeElement === first) {
                e.preventDefault();
                last.focus();
            } else if (!e.shiftKey && document.activeElement === last) {
                e.preventDefault();
                first.focus();
            }
        });
    }

    /* ---------- Mobile Nav Toggle ---------- */
    function initNavToggle() {
        var toggle = document.getElementById('nav-toggle');
        var menu = document.getElementById('nav-menu');
        if (!toggle || !menu) return;

        toggle.addEventListener('click', function () {
            var open = toggle.classList.toggle('is-open');
            menu.classList.toggle('is-open');
            toggle.setAttribute('aria-expanded', open);
        });

        menu.querySelectorAll('.topnav__pill').forEach(function (link) {
            link.addEventListener('click', function () {
                toggle.classList.remove('is-open');
                menu.classList.remove('is-open');
                toggle.setAttribute('aria-expanded', 'false');
            });
        });
    }

    /* ---------- Init ---------- */
    document.addEventListener('DOMContentLoaded', function () {
        initSlideIn();
        initSmoothScroll();
        initNavScroll();
        initNavToggle();
        initStellenModal();
    });
})();
