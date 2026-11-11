// Tuning-Panel fuer die Startseiten-Sektionen (nur mit ?tune in der URL).
// Schieberegler setzen CSS-Variablen auf :root, Werte bleiben im Browser
// gespeichert. "CSS kopieren" liefert die geaenderten Werte zum Einbauen.
(function () {
  var STORAGE_KEY = 'home-tune-v4';

  // [Variable, Label, min, max, step, Standard, Einheit]
  var groups = [
    ['Schrift', [
      ['--fs-title', 'Titel', 20, 44, 1, 32, 'px'],
      ['--fs-body', 'Text', 16, 32, 1, 24, 'px'],
      ['--fs-preise', 'Text Preise', 16, 34, 1, 26, 'px']
    ]],
    ['1 Deutsch', [
      ['--h-deutsch', 'Sektionshöhe', 400, 1100, 5, 1100, 'px'],
      ['--x-deutsch-illus', 'Grafik x', -400, 400, 1, -143, 'px'],
      ['--y-deutsch-illus', 'Grafik y', -400, 400, 1, 29, 'px'],
      ['--s-deutsch-illus', 'Grafik Grösse', 0.4, 1.8, 0.01, 1.56, ''],
      ['--x-deutsch-text', 'Text x', -400, 400, 1, 84, 'px'],
      ['--y-deutsch-text', 'Text y', -400, 400, 1, 0, 'px'],
      ['--x-deutsch-btn', 'Button x', -400, 400, 1, 0, 'px'],
      ['--y-deutsch-btn', 'Button y', -200, 200, 1, 0, 'px']
    ]],
    ['2 Auffrischen', [
      ['--h-auffrischen', 'Sektionshöhe', 400, 1100, 5, 870, 'px'],
      ['--x-auffrischen-text', 'Text x', -400, 400, 1, -91, 'px'],
      ['--y-auffrischen-text', 'Text y', -400, 400, 1, 50, 'px'],
      ['--x-auffrischen-btn', 'Button x', -400, 400, 1, 0, 'px'],
      ['--y-auffrischen-btn', 'Button y', -200, 200, 1, 0, 'px'],
      ['--x-auffrischen-illus', 'Grafik x', -400, 400, 1, 65, 'px'],
      ['--y-auffrischen-illus', 'Grafik y', -400, 400, 1, -78, 'px'],
      ['--s-auffrischen-illus', 'Grafik Grösse', 0.4, 1.8, 0.01, 0.83, '']
    ]],
    ['3 Formulare', [
      ['--h-formular', 'Sektionshöhe', 400, 1100, 5, 880, 'px'],
      ['--x-formular-illus', 'Grafik x', -400, 400, 1, 4, 'px'],
      ['--y-formular-illus', 'Grafik y', -400, 400, 1, -71, 'px'],
      ['--s-formular-illus', 'Grafik Grösse', 0.4, 1.8, 0.01, 0.87, ''],
      ['--r-formular-illus', 'Grafik Drehung', -45, 45, 0.5, 4, 'deg'],
      ['--x-formular-text', 'Text x', -400, 400, 1, 71, 'px'],
      ['--y-formular-text', 'Text y', -400, 400, 1, 0, 'px'],
      ['--x-formular-btn', 'Button x', -400, 400, 1, 0, 'px'],
      ['--y-formular-btn', 'Button y', -200, 200, 1, 0, 'px']
    ]],
    ['4 Über mich', [
      ['--h-about', 'Sektionshöhe', 400, 1100, 5, 810, 'px'],
      ['--about-photo-left', 'Foto linker Rand', 0, 70, 0.5, 32.3, '%'],
      ['--about-photo-pos', 'Foto Ausschnitt x', 0, 100, 1, 100, '%'],
      ['--about-photo-pos-y', 'Foto Ausschnitt y', 0, 100, 1, 20, '%'],
      ['--about-text-width', 'Textbreite', 300, 700, 2, 424, 'px'],
      ['--x-about-text', 'Text x', -300, 500, 1, 0, 'px'],
      ['--y-about-text', 'Text y', -300, 300, 1, 0, 'px']
    ]],
    ['5 Wie läuft das', [
      ['--x-ablauf-title', 'Titel x', -500, 300, 1, 0, 'px'],
      ['--y-ablauf-title', 'Titel y', -200, 200, 1, 0, 'px'],
      ['--x-ablauf-steps', 'Liste x', -500, 300, 1, 0, 'px'],
      ['--y-ablauf-steps', 'Liste y', -200, 200, 1, 0, 'px'],
      ['--ablauf-bottom', 'Abstand unten', 0, 500, 5, 240, 'px']
    ]],
    ['6 Was kostet das + Kontakt', [
      ['--x-preise-text', 'Text x', -200, 400, 1, 0, 'px'],
      ['--y-preise-text', 'Text y', -200, 300, 1, 0, 'px'],
      ['--preise-bottom', 'Abstand unten', 0, 400, 5, 160, 'px'],
      ['--prefooter-pad', 'Kontakt Innenabstand', 24, 240, 4, 96, 'px'],
      ['--contact-width', 'Kontakt Breite', 300, 800, 4, 576, 'px'],
      ['--x-contact', 'Kontakt x', -600, 300, 1, 0, 'px'],
      ['--y-contact', 'Kontakt y', -200, 200, 1, 0, 'px'],
      ['--x-contact-actions', 'Buttons x', -200, 300, 1, 0, 'px'],
      ['--y-contact-actions', 'Buttons y', -100, 200, 1, 0, 'px']
    ]]
  ];

  var root = document.documentElement;
  var values = {};
  try { values = JSON.parse(localStorage.getItem(STORAGE_KEY)) || {}; } catch (e) {}

  function save() {
    try { localStorage.setItem(STORAGE_KEY, JSON.stringify(values)); } catch (e) {}
  }

  function apply(name, value, unit) {
    root.style.setProperty(name, value + unit);
  }

  var panel = document.createElement('aside');
  panel.className = 'tune-panel';
  panel.innerHTML =
    '<div class="tune-panel__head"><strong>Feinjustierung</strong>' +
    '<button type="button" data-act="toggle">–</button></div>' +
    '<div class="tune-panel__body"></div>' +
    '<div class="tune-panel__foot">' +
    '<button type="button" data-act="copy">CSS kopieren</button>' +
    '<button type="button" data-act="reset">Alles zurücksetzen</button></div>' +
    '<p class="tune-panel__hint">Nur Desktop (ab 1024px). Werte bleiben in diesem Browser gespeichert.</p>';
  var body = panel.querySelector('.tune-panel__body');

  groups.forEach(function (group) {
    var details = document.createElement('details');
    details.innerHTML = '<summary>' + group[0] + '</summary>';
    group[1].forEach(function (c) {
      var name = c[0], unit = c[6], def = c[5];
      var value = name in values ? values[name] : def;
      if (name in values) apply(name, value, unit);

      var row = document.createElement('label');
      row.className = 'tune-row';
      row.innerHTML = '<span>' + c[1] + '</span>' +
        '<input type="range" min="' + c[2] + '" max="' + c[3] + '" step="' + c[4] + '" value="' + value + '">' +
        '<input type="number" step="' + c[4] + '" value="' + value + '">' +
        '<button type="button" title="Zurücksetzen">↺</button>';
      var range = row.querySelector('input[type=range]');
      var number = row.querySelector('input[type=number]');

      function set(v) {
        range.value = v;
        number.value = v;
        if (Number(v) === def) {
          delete values[name];
          root.style.removeProperty(name);
        } else {
          values[name] = Number(v);
          apply(name, v, unit);
        }
        save();
      }

      range.addEventListener('input', function () { set(range.value); });
      number.addEventListener('input', function () { if (number.value !== '') set(number.value); });
      row.querySelector('button').addEventListener('click', function (e) { e.preventDefault(); set(def); });
      row.dataset.def = def;
      row.reset = function () { set(def); };
      details.appendChild(row);
    });
    body.appendChild(details);
  });

  function cssText() {
    var lines = [];
    groups.forEach(function (g) {
      g[1].forEach(function (c) {
        if (c[0] in values) lines.push('  ' + c[0] + ': ' + values[c[0]] + c[6] + ';');
      });
    });
    return lines.length ? ':root {\n' + lines.join('\n') + '\n}' : '/* keine Änderungen */';
  }

  panel.addEventListener('click', function (e) {
    var act = e.target.dataset && e.target.dataset.act;
    if (act === 'toggle') {
      panel.classList.toggle('is-collapsed');
      e.target.textContent = panel.classList.contains('is-collapsed') ? '+' : '–';
    } else if (act === 'reset') {
      panel.querySelectorAll('.tune-row').forEach(function (row) { row.reset(); });
    } else if (act === 'copy') {
      var text = cssText();
      var done = function () { e.target.textContent = 'Kopiert ✓'; setTimeout(function () { e.target.textContent = 'CSS kopieren'; }, 1500); };
      if (navigator.clipboard) {
        navigator.clipboard.writeText(text).then(done, function () { window.prompt('CSS:', text); });
      } else {
        window.prompt('CSS:', text);
      }
    }
  });

  var style = document.createElement('style');
  style.textContent =
    '.tune-panel{position:fixed;top:12px;right:12px;z-index:9999;width:340px;max-height:calc(100vh - 24px);display:flex;flex-direction:column;' +
    'background:#fff;color:#111;border-radius:10px;box-shadow:0 10px 40px rgb(0 0 0/25%);font:13px/1.3 system-ui,sans-serif}' +
    '.tune-panel__head,.tune-panel__foot{display:flex;gap:8px;align-items:center;justify-content:space-between;padding:10px 12px}' +
    '.tune-panel__head{border-bottom:1px solid #eee}.tune-panel__foot{border-top:1px solid #eee}' +
    '.tune-panel__body{overflow:auto;padding:4px 12px}' +
    '.tune-panel summary{cursor:pointer;font-weight:600;padding:8px 0}' +
    '.tune-row{display:grid;grid-template-columns:96px 1fr 58px 22px;gap:6px;align-items:center;margin-bottom:6px}' +
    '.tune-row input[type=number]{width:100%;font:inherit;padding:2px 4px}' +
    '.tune-panel button{font:inherit;cursor:pointer;border:1px solid #ccc;background:#f6f6f6;border-radius:5px;padding:3px 8px}' +
    '.tune-row button{padding:0;width:22px;height:22px}' +
    '.tune-panel__hint{margin:0;padding:0 12px 10px;color:#666;font-size:11px}' +
    '.tune-panel.is-collapsed .tune-panel__body,.tune-panel.is-collapsed .tune-panel__foot,.tune-panel.is-collapsed .tune-panel__hint{display:none}';
  document.head.appendChild(style);
  document.body.appendChild(panel);
})();
