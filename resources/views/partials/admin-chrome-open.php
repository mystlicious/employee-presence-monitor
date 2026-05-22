<?php
$pageTitle = $pageTitle ?? __('app_name') . ' — Admin';
$activeNav = $activeNav ?? '';
$shellClass = $shellClass ?? 'shell wide';
$viewsRoot = dirname(__DIR__);
?>
<!doctype html>
<html lang="<?php echo htmlspecialchars(app_lang_attr()); ?>">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title><?php echo htmlspecialchars($pageTitle); ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
  <?php include $viewsRoot . '/partials/pm-design-snippet.php'; ?>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = { prefix: 'tw-' };
  </script>
  <script>
    (function () {
      try {
        if (sessionStorage.getItem('pm_admin_sidebar_hover_carry') === '1') {
          document.documentElement.classList.add('sb-hover-open');
          sessionStorage.removeItem('pm_admin_sidebar_hover_carry');
        }
      } catch (e) {}
    })();
  </script>
  <?php include $viewsRoot . '/partials/admin-styles.php'; ?>
</head>
<body class="admin-app pm-mesh-bg-admin">
  <div class="layout">
    <button type="button" class="sidebar-backdrop" id="sidebarBackdrop" tabindex="-1" aria-hidden="true"></button>
    <header class="admin-mobile-bar">
      <button class="toggle" id="sidebarToggle" type="button" aria-label="<?php echo htmlspecialchars(__('toggle_menu')); ?>" aria-expanded="false" aria-controls="adminSideNav">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
          <path d="M4 6h16M4 12h16M4 18h10"/>
        </svg>
      </button>
      <span class="admin-mobile-bar__title"><?php echo htmlspecialchars(__('app_name')); ?></span>
    </header>
    <aside class="sidebar">
      <div class="sidebar-head">
        <div class="brand-row">
          <div class="brand-mark" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
              <path d="M3 12l2-2 4 4 10-10 2 2-12 12z"/>
            </svg>
          </div>
          <div class="brand-text">
            <div class="brand-title"><?php echo htmlspecialchars(__('app_name')); ?></div>
            <div class="side-sub"><?php echo htmlspecialchars(__('operations_console')); ?></div>
          </div>
        </div>
      </div>
      <nav class="side-nav" id="adminSideNav">
        <div class="nav-group">
          <div class="nav-caption"><?php echo htmlspecialchars(__('workspace')); ?></div>
          <a href="/admin-panel" class="<?php echo $activeNav === 'home' ? 'active' : ''; ?>">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <path d="M3 10.5L12 3l9 7.5V21a1 1 0 0 1-1 1h-5v-7H9v7H4a1 1 0 0 1-1-1z"/>
            </svg>
            <span class="nav-label"><?php echo htmlspecialchars(__('admin_home')); ?></span>
          </a>
          <a href="/admin/dashboard" class="<?php echo $activeNav === 'dashboard' ? 'active' : ''; ?>">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <path d="M4 19V5"/><path d="M4 19h16"/><path d="M8 17v-6"/><path d="M12 17V9"/><path d="M16 17v-3"/>
            </svg>
            <span class="nav-label"><?php echo htmlspecialchars(__('analytics')); ?></span>
          </a>
          <a href="/admin/employees" class="<?php echo $activeNav === 'employees' ? 'active' : ''; ?>">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M20 8v6"/><path d="M23 11h-6"/>
            </svg>
            <span class="nav-label"><?php echo htmlspecialchars(__('employees')); ?></span>
          </a>
        </div>
        <div class="nav-group nav-group-muted">
          <div class="nav-caption"><?php echo htmlspecialchars(__('language')); ?></div>
          <?php
            $localeNow = app_locale();
            $localeRedirect = htmlspecialchars($_SERVER['REQUEST_URI'] ?? '/admin-panel', ENT_QUOTES, 'UTF-8');
          ?>
          <div
            class="lang-switch-wrap"
            data-lang-switch
            data-lang="<?php echo htmlspecialchars($localeNow, ENT_QUOTES, 'UTF-8'); ?>"
          >
            <form method="post" action="/admin/locale" class="lang-switch-form">
              <input type="hidden" name="redirect" value="<?php echo $localeRedirect; ?>">
              <input type="hidden" name="locale" value="<?php echo htmlspecialchars($localeNow, ENT_QUOTES, 'UTF-8'); ?>">
              <button
                type="button"
                class="lang-switch-icon"
                data-lang-cycle
                aria-label="<?php echo htmlspecialchars(__('language') . ' — ' . strtoupper($localeNow)); ?>"
                data-lang-label="<?php echo htmlspecialchars(__('language'), ENT_QUOTES, 'UTF-8'); ?>"
              >
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                  <circle cx="12" cy="12" r="10"/>
                  <path d="M12 2a14.5 14.5 0 0 1 0 20"/>
                  <path d="M2 12h20"/>
                </svg>
              </button>
              <div
                class="lang-switch-track"
                role="group"
                aria-label="<?php echo htmlspecialchars(__('language')); ?>"
              >
                <div class="lang-switch-pill" aria-hidden="true"></div>
                <button
                  type="button"
                  class="lang-switch-btn"
                  data-lang-pick="en"
                  aria-pressed="<?php echo $localeNow === 'en' ? 'true' : 'false'; ?>"
                >EN</button>
                <button
                  type="button"
                  class="lang-switch-btn"
                  data-lang-pick="id"
                  aria-pressed="<?php echo $localeNow === 'id' ? 'true' : 'false'; ?>"
                >ID</button>
              </div>
            </form>
          </div>
        </div>
        <div class="nav-group nav-group-muted">
          <div class="nav-caption"><?php echo htmlspecialchars(__('quick_links')); ?></div>
          <a href="/">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <path d="M9 14l-4-4 4-4"/><path d="M5 10h11a4 4 0 0 1 0 8h-1"/>
            </svg>
            <span class="nav-label"><?php echo htmlspecialchars(__('back_home')); ?></span>
          </a>
        </div>
      </nav>
      <div class="sidebar-foot">
        <a href="/admin/logout" class="logout-link">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="M16 17l5-5-5-5"/><path d="M21 12H9"/>
          </svg>
          <span class="nav-label"><?php echo htmlspecialchars(__('logout')); ?></span>
        </a>
      </div>
    </aside>
    <main class="content">
      <div class="<?php echo htmlspecialchars($shellClass); ?>">
