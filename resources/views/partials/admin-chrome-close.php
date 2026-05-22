      </div>
    </main>
  </div>
  <script>
    setTimeout(() => {
      document.querySelectorAll('.flash').forEach((el) => {
        el.style.transition = 'opacity .35s ease';
        el.style.opacity = '0';
        setTimeout(() => el.remove(), 380);
      });
    }, 4000);

    (function initSidebar() {
      const toggle = document.getElementById('sidebarToggle');
      const sidebar = document.querySelector('.sidebar');
      if (!toggle) return;
      const mqMobile = window.matchMedia('(max-width: 1023px)');
      const backdrop = document.getElementById('sidebarBackdrop');

      function setMobileNavOpen(open) {
        document.documentElement.classList.toggle('sb-mobile-nav-open', open);
        toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        if (backdrop) {
          backdrop.setAttribute('aria-hidden', open ? 'false' : 'true');
        }
      }
      const carryKey = 'pm_admin_sidebar_hover_carry';

      function isMobileNav() {
        return mqMobile.matches;
      }

      toggle.addEventListener('click', () => {
        if (!isMobileNav()) return;
        setMobileNavOpen(!document.documentElement.classList.contains('sb-mobile-nav-open'));
      });

      if (backdrop) {
        backdrop.addEventListener('click', () => {
          if (isMobileNav()) setMobileNavOpen(false);
        });
      }

      if (sidebar) {
        sidebar.querySelectorAll('a[href]').forEach((link) => {
          link.addEventListener('click', () => {
            if (isMobileNav()) {
              setMobileNavOpen(false);
              return;
            }
            try {
              sessionStorage.setItem(carryKey, '1');
              document.documentElement.classList.add('sb-hover-open');
            } catch (e) {}
          });
        });

        sidebar.addEventListener('mouseleave', () => {
          if (isMobileNav()) return;
          document.documentElement.classList.remove('sb-hover-open');
        });
      }

      function onViewportChange() {
        toggle.setAttribute('aria-controls', 'adminSideNav');
        if (!isMobileNav()) {
          setMobileNavOpen(false);
        } else {
          setMobileNavOpen(document.documentElement.classList.contains('sb-mobile-nav-open'));
          document.documentElement.classList.remove('sb-hover-open');
          try { sessionStorage.removeItem(carryKey); } catch (e) {}
        }
      }

      mqMobile.addEventListener('change', onViewportChange);
      onViewportChange();
    })();

    (function initLangSwitch() {
      const carryKey = 'pm_admin_sidebar_hover_carry';
      const motionOk = !window.matchMedia('(prefers-reduced-motion: reduce)').matches;
      /** Let the pill finish sliding before navigation (matches CSS ~500ms). */
      const navigateDelayMs = motionOk ? 460 : 0;

      function localeUrl(code, redirect) {
        const params = new URLSearchParams();
        params.set('locale', code);
        params.set('redirect', redirect);
        return '/admin/locale?' + params.toString();
      }

      function applyLocale(wrap, code) {
        if (!code || wrap.dataset.lang === code || wrap.dataset.pending === '1') {
          return false;
        }

        const form = wrap.querySelector('.lang-switch-form');
        const redirectInput = form && form.querySelector('input[name="redirect"]');
        const redirect = (redirectInput && redirectInput.value)
          ? redirectInput.value
          : window.location.pathname + window.location.search;

        wrap.dataset.pending = '1';
        wrap.dataset.lang = code;

        wrap.querySelectorAll('.lang-switch-btn').forEach((b) => {
          const pick = b.getAttribute('data-lang-pick');
          b.setAttribute('aria-pressed', pick === code ? 'true' : 'false');
        });

        const iconBtn = wrap.querySelector('[data-lang-cycle]');
        if (iconBtn) {
          const base = iconBtn.getAttribute('data-lang-label') || 'Language';
          iconBtn.setAttribute('aria-label', base + ' — ' + code.toUpperCase());
        }

        const localeInput = form && form.querySelector('input[name="locale"]');
        if (localeInput) {
          localeInput.value = code;
        }

        document.documentElement.classList.add('lang-nav-pending');

        try {
          sessionStorage.setItem(carryKey, '1');
          document.documentElement.classList.add('sb-hover-open');
        } catch (err) {}

        const go = () => {
          window.location.assign(localeUrl(code, redirect));
        };

        // Paint the new pill position, then navigate after the slide completes.
        requestAnimationFrame(() => {
          requestAnimationFrame(() => {
            if (navigateDelayMs > 0) {
              window.setTimeout(go, navigateDelayMs);
            } else {
              go();
            }
          });
        });

        return true;
      }

      document.querySelectorAll('[data-lang-switch]').forEach((wrap) => {
        const form = wrap.querySelector('.lang-switch-form');
        if (!form) return;

        wrap.querySelectorAll('[data-lang-pick]').forEach((btn) => {
          btn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            applyLocale(wrap, btn.getAttribute('data-lang-pick'));
          });
        });

        const cycleBtn = wrap.querySelector('[data-lang-cycle]');
        if (cycleBtn) {
          cycleBtn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            const current = wrap.dataset.lang || 'id';
            const next = current === 'en' ? 'id' : 'en';
            applyLocale(wrap, next);
          });
        }
      });
    })();
  </script>
</body>
</html>
