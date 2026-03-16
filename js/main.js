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

    /* ---------- Meine Stellen (localStorage) ---------- */
    function getMeineStellen() {
        try {
            return JSON.parse(localStorage.getItem('meineStellen')) || [];
        } catch (e) {
            return [];
        }
    }

    function saveMeineStellen(list) {
        localStorage.setItem('meineStellen', JSON.stringify(list));
    }

    function isStelleGespeichert(titel, firma) {
        return getMeineStellen().some(function (s) {
            return s.titel === titel && s.firma === firma;
        });
    }

    function addStelle(titel, firma, url) {
        var list = getMeineStellen();
        if (!list.some(function (s) { return s.titel === titel && s.firma === firma; })) {
            list.push({ titel: titel, firma: firma, url: url });
            saveMeineStellen(list);
        }
    }

    function removeStelle(index) {
        var list = getMeineStellen();
        list.splice(index, 1);
        saveMeineStellen(list);
    }

    function updateMeineStellenBtn() {
        var count = getMeineStellen().length;
        var btns = document.querySelectorAll('.stellen-meine-btn');
        btns.forEach(function (btn) {
            var badge = btn.querySelector('.stellen-meine-btn__badge');
            if (count > 0) {
                btn.disabled = false;
                if (badge) { badge.textContent = count; badge.hidden = false; }
            } else {
                btn.disabled = true;
                if (badge) { badge.hidden = true; }
            }
        });
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
        // Desktop "Meine Stellen" button in header row
        var thAdd = document.createElement('th');
        thAdd.className = 'stellen-table__th-meine';
        var desktopBtn = document.createElement('button');
        desktopBtn.type = 'button';
        desktopBtn.className = 'btn stellen-meine-btn stellen-meine-btn--desktop';
        desktopBtn.disabled = true;
        desktopBtn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="stellen-meine-btn__arrow"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>Meine Stellen<span class="stellen-meine-btn__badge" hidden>0</span>';
        desktopBtn.addEventListener('click', function () {
            var mobileBtn = document.getElementById('meine-stellen-open');
            if (mobileBtn) mobileBtn.click();
        });
        thAdd.appendChild(desktopBtn);
        headerRow.appendChild(thAdd);
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

            // Add button cell
            var tdAdd = document.createElement('td');
            tdAdd.className = 'stellen-add-cell';
            var addBtn = document.createElement('button');
            addBtn.type = 'button';

            if (isStelleGespeichert(row.titel, row.firma)) {
                addBtn.className = 'stellen-add-btn stellen-add-btn--added';
                addBtn.textContent = 'Gemerkt \u2713';
                addBtn.disabled = true;
            } else {
                addBtn.className = 'stellen-add-btn';
                addBtn.textContent = 'Hinzufügen';
            }

            addBtn.addEventListener('click', function (e) {
                e.stopPropagation();
                addStelle(row.titel || '', row.firma || '', row.url || '');
                addBtn.className = 'stellen-add-btn stellen-add-btn--added';
                addBtn.textContent = 'Gemerkt \u2713';
                addBtn.disabled = true;
                updateMeineStellenBtn();
            });

            tdAdd.appendChild(addBtn);
            tr.appendChild(tdAdd);
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
            var isMobile = window.innerWidth < 768;
            modal.style.setProperty('--stellen-top', isMobile ? '100px' : '170px');
            modal.removeAttribute('hidden');
            requestAnimationFrame(function () {
                requestAnimationFrame(function () {
                    modal.classList.add('is-active');
                });
            });
            document.body.classList.add('stellen-modal-open');
            modal.querySelector('.stellen-modal__close').focus();

            updateMeineStellenBtn();

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

    /* ---------- Meine Stellen Modal ---------- */
    function initMeineStellenModal() {
        var openBtn = document.getElementById('meine-stellen-open');
        var modal = document.getElementById('meine-stellen-modal');
        var listEl = document.getElementById('meine-stellen-list');
        var form = document.getElementById('meine-stellen-form');
        var telInput = document.getElementById('meine-stellen-tel');
        var emailInput = document.getElementById('meine-stellen-email');
        var submitBtn = document.getElementById('meine-stellen-submit');
        if (!openBtn || !modal) return;

        var closeBtns = modal.querySelectorAll('[data-meine-stellen-close]');

        function renderList() {
            var stellen = getMeineStellen();
            listEl.innerHTML = '';
            if (stellen.length === 0) {
                var empty = document.createElement('p');
                empty.className = 'meine-stellen-empty';
                empty.textContent = 'Noch keine Stellen gemerkt.';
                listEl.appendChild(empty);
                return;
            }
            stellen.forEach(function (s, i) {
                var item = document.createElement('div');
                item.className = 'meine-stellen-item';
                var info = document.createElement('div');
                info.className = 'meine-stellen-item__info';
                var titel = document.createElement('div');
                titel.className = 'meine-stellen-item__titel';
                titel.textContent = s.titel;
                var firma = document.createElement('div');
                firma.className = 'meine-stellen-item__firma';
                firma.textContent = s.firma;
                info.appendChild(titel);
                info.appendChild(firma);
                var removeBtn = document.createElement('button');
                removeBtn.type = 'button';
                removeBtn.className = 'meine-stellen-item__remove';
                removeBtn.innerHTML = '&times;';
                removeBtn.setAttribute('aria-label', 'Entfernen');
                removeBtn.addEventListener('click', function () {
                    removeStelle(i);
                    renderList();
                    updateMeineStellenBtn();
                    updateSubmitState();
                });
                item.appendChild(info);
                item.appendChild(removeBtn);
                listEl.appendChild(item);
            });
        }

        function updateSubmitState() {
            var hasContact = telInput.value.trim() !== '' || emailInput.value.trim() !== '';
            var hasStellen = getMeineStellen().length > 0;
            submitBtn.disabled = !(hasContact && hasStellen);
        }

        telInput.addEventListener('input', updateSubmitState);
        emailInput.addEventListener('input', updateSubmitState);

        function openModal() {
            renderList();
            updateSubmitState();
            var isMobile = window.innerWidth < 768;
            modal.style.setProperty('--stellen-top', isMobile ? '100px' : '170px');
            modal.removeAttribute('hidden');
            requestAnimationFrame(function () {
                requestAnimationFrame(function () {
                    modal.classList.add('is-active');
                });
            });
            document.body.classList.add('stellen-modal-open');
            modal.querySelector('.stellen-modal__close').focus();
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
            var focusable = modal.querySelectorAll('button:not([disabled]), input, [href], [tabindex]:not([tabindex="-1"])');
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

        // Form submit → mailto
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            var stellen = getMeineStellen();
            if (!stellen.length) return;

            var tel = telInput.value.trim();
            var email = emailInput.value.trim();
            if (!tel && !email) return;

            var subject = 'Bewerbungsanfrage — ' + stellen.length + ' Stellen';
            var body = 'Guten Tag\nIch möchte mich auf diese Stellen bewerben und dafür bei Dir einen Termin abmachen.\n\nMeine Stellen:\n';
            stellen.forEach(function (s) {
                body += '- ' + s.titel + ' bei ' + s.firma;
                if (s.url) body += ' (' + s.url + ')';
                body += '\n';
            });
            body += '\nKontaktiere mich per:\n';
            if (tel) body += 'Telefon: ' + tel + '\n';
            if (email) body += 'Email: ' + email + '\n';

            var mailto = 'mailto:felix@bewerbungenundmehr.ch'
                + '?subject=' + encodeURIComponent(subject)
                + '&body=' + encodeURIComponent(body);
            window.location.href = mailto;
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
        initMeineStellenModal();
    });
})();
