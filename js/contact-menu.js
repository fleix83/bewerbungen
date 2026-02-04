/**
 * Contact Menu - Popup for "Anfragen" buttons
 */
(function() {
  const menu = document.getElementById('contact-menu');
  if (!menu) return;

  const overlay = menu.querySelector('.contact-menu__overlay');
  const closeBtn = menu.querySelector('.contact-menu__close');
  const form = menu.querySelector('.contact-menu__form');
  const formToggle = menu.querySelector('[data-toggle-form]');

  // Open menu on Anfragen click (event delegation)
  document.addEventListener('click', function(e) {
    if (e.target.matches('[data-contact-menu]') || e.target.closest('[data-contact-menu]')) {
      e.preventDefault();
      openMenu();
    }
  });

  // Close handlers
  if (overlay) {
    overlay.addEventListener('click', closeMenu);
  }
  if (closeBtn) {
    closeBtn.addEventListener('click', closeMenu);
  }
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && !menu.hidden) {
      closeMenu();
    }
  });

  // Form toggle
  if (formToggle && form) {
    formToggle.addEventListener('click', function() {
      form.hidden = !form.hidden;
      if (!form.hidden) {
        form.querySelector('input[name="name"]').focus();
      }
    });
  }

  // Form submission (mailto fallback)
  if (form) {
    form.addEventListener('submit', handleFormSubmit);
  }

  function openMenu() {
    menu.hidden = false;
    document.body.style.overflow = 'hidden';
    // Focus first interactive element for accessibility
    const firstLink = menu.querySelector('.contact-menu__item');
    if (firstLink) {
      firstLink.focus();
    }
  }

  function closeMenu() {
    menu.hidden = true;
    if (form) {
      form.hidden = true;
      form.reset();
    }
    document.body.style.overflow = '';
  }

  function handleFormSubmit(e) {
    e.preventDefault();
    const data = new FormData(form);
    const name = data.get('name') || '';
    const email = data.get('email') || '';
    const message = data.get('message') || '';

    const subject = 'Kontaktanfrage von ' + name;
    const body = message + '\n\nVon: ' + name + '\nE-Mail: ' + email;

    window.location.href = 'mailto:felix@bewerbungenundmehr.ch?subject=' +
      encodeURIComponent(subject) + '&body=' + encodeURIComponent(body);

    closeMenu();
  }
})();
