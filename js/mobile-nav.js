/**
 * Mobile Navigation - Hamburger toggle for landing header
 */
(function() {
  var btn = document.querySelector('.landing-header__hamburger');
  if (!btn) return;

  var nav = document.getElementById('nav-panel');
  if (!nav) return;

  btn.addEventListener('click', function() {
    var expanded = btn.getAttribute('aria-expanded') === 'true';
    toggle(!expanded);
  });

  // Close on nav link click
  nav.addEventListener('click', function(e) {
    if (e.target.matches('.landing-header__nav-link')) {
      toggle(false);
    }
  });

  // Close on Escape
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && btn.getAttribute('aria-expanded') === 'true') {
      toggle(false);
      btn.focus();
    }
  });

  // Close on resize to desktop
  var mq = window.matchMedia('(min-width: 1200px)');
  mq.addEventListener('change', function(e) {
    if (e.matches) {
      toggle(false);
    }
  });

  function toggle(open) {
    btn.setAttribute('aria-expanded', String(open));
    btn.setAttribute('aria-label', open ? 'Navigation schliessen' : 'Navigation öffnen');
    nav.classList.toggle('is-open', open);
  }
})();
