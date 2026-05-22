<!doctype html>
<html lang="<?php echo htmlspecialchars(app_lang_attr()); ?>">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>In/Out — <?php echo htmlspecialchars(__('app_name')); ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Montserrat:wght@700;800;900&display=swap" rel="stylesheet">
  <?php include __DIR__ . '/partials/pm-design-snippet.php'; ?>
  <style>
    @media (prefers-reduced-motion: reduce) {
      body.pm-mesh-bg.pm-home-lock::before {
        animation: none !important;
      }
    }
    body.pm-mesh-bg.pm-home-lock::before {
      animation-duration: 30s;
    }
    * { box-sizing: border-box; }
    body.pm-app.pm-home-lock {
      margin: 0;
      height: 100vh;
      height: 100dvh;
      max-height: 100dvh;
      overflow: hidden;
      display: flex;
      flex-direction: column;
      align-items: stretch;
      padding: 1.5rem 0;
      color: #0f172a;
      font-family: Inter, ui-sans-serif, system-ui, -apple-system, sans-serif;
    }
    @media (max-width: 1023px) {
      body.pm-app.pm-home-lock {
        height: auto;
        min-height: 100dvh;
        max-height: none;
        overflow-x: hidden;
        overflow-y: auto;
        justify-content: flex-start;
        padding: 1.5rem 0 3rem;
      }
      .main {
        max-height: none;
        overflow: visible;
      }
    }

    .main {
      flex: 1 1 auto;
      width: 100%;
      max-width: 75rem;
      min-height: 0;
      margin: 0 auto;
      padding: 1.5rem 1rem;
      position: relative;
      z-index: 1;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      overflow-x: hidden;
      overflow-y: auto;
      gap: clamp(0.65rem, 1.8dvh, 1.1rem);
    }
    @media (min-width: 640px) {
      .main {
        padding: 3rem 2rem;
      }
    }

    .intro {
      text-align: center;
      max-width: 44rem;
      margin: 0 auto;
      flex-shrink: 0;
    }
    .intro-eyebrow {
      font-size: clamp(0.75rem, 1.35vw, 0.875rem);
      font-weight: 800;
      letter-spacing: 0.26em;
      text-transform: uppercase;
      color: rgb(71 85 105);
      margin: 0;
    }
    .intro-title-brand {
      font-family: Montserrat, Inter, ui-sans-serif, system-ui, sans-serif;
      font-size: clamp(2.25rem, 8vw, 3.75rem);
      font-weight: 900;
      letter-spacing: -0.04em;
      line-height: 1;
      margin: 0.4rem 0 0;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      flex-wrap: wrap;
      gap: 0 0.08em;
    }
    .intro-title__in {
      color: #10b981;
    }
    .intro-title__slash {
      margin: 0 0.02em;
      font-weight: 900;
      color: #0f172a;
      opacity: 0.92;
    }
    .intro-title__out {
      color: #dc2626;
    }
    .intro-lede-wrap {
      max-width: 30rem;
      margin: 0.5rem auto 0;
    }
    .intro-lede {
      margin: 0;
      font-size: clamp(0.9375rem, 1.85vw, 1.0625rem);
      line-height: 1.5;
      color: rgb(71 85 105);
      font-weight: 450;
    }

    .portal-grid {
      display: grid;
      grid-template-columns: 1fr;
      gap: 0.85rem;
      align-items: stretch;
      width: 100%;
      flex: 0 1 auto;
    }
    @media (min-width: 1024px) {
      .portal-grid {
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: clamp(0.85rem, 1.5vw, 1.15rem);
      }
    }

    .tile {
      --glow-x: 50%;
      --glow-y: 50%;
      --tile-glow: 59, 130, 246;
      position: relative;
      display: flex;
      flex-direction: column;
      align-items: center;
      text-align: center;
      text-decoration: none;
      color: inherit;
      border-radius: 1.5rem;
      min-height: 0;
      height: auto;
      padding: clamp(1.35rem, 2.2dvh, 1.75rem) clamp(2rem, 3vw, 2.5rem);
      overflow: hidden;
      background: rgba(255, 255, 255, 0.4);
      backdrop-filter: blur(40px);
      -webkit-backdrop-filter: blur(40px);
      border: 1px solid rgba(255, 255, 255, 0.4);
      box-shadow:
        0 4px 24px rgba(15, 23, 42, 0.06),
        0 1px 0 rgba(255, 255, 255, 0.55) inset;
      transition:
        transform 420ms cubic-bezier(0.22, 1, 0.36, 1),
        box-shadow 420ms cubic-bezier(0.22, 1, 0.36, 1),
        border-color 420ms cubic-bezier(0.22, 1, 0.36, 1);
    }
    @media (min-width: 1024px) {
      .tile {
        min-height: 23rem;
      }
    }
    .tile::after {
      content: "";
      position: absolute;
      inset: 0;
      pointer-events: none;
      opacity: 0;
      transition: opacity 420ms cubic-bezier(0.22, 1, 0.36, 1);
      background: radial-gradient(
        380px circle at var(--glow-x) var(--glow-y),
        rgba(var(--tile-glow), 0.14),
        transparent 58%
      );
    }
    .tile--display {
      --tile-glow: 59, 130, 246;
    }
    .tile--input {
      --tile-glow: 16, 185, 129;
    }
    .tile--admin {
      --tile-glow: 139, 92, 246;
    }

    .tile--display:hover {
      transform: translateY(-1rem);
      border-color: rgba(59, 130, 246, 0.48);
      box-shadow:
        0 28px 56px rgba(15, 23, 42, 0.1),
        0 0 0 1px rgba(59, 130, 246, 0.22),
        0 0 52px rgba(59, 130, 246, 0.28),
        0 1px 0 rgba(255, 255, 255, 0.65) inset;
    }
    .tile--input:hover {
      transform: translateY(-1rem);
      border-color: rgba(16, 185, 129, 0.45);
      box-shadow:
        0 28px 56px rgba(15, 23, 42, 0.1),
        0 0 0 1px rgba(16, 185, 129, 0.2),
        0 0 52px rgba(16, 185, 129, 0.26),
        0 1px 0 rgba(255, 255, 255, 0.65) inset;
    }
    .tile--admin:hover {
      transform: translateY(-1rem);
      border-color: rgba(139, 92, 246, 0.5);
      box-shadow:
        0 28px 56px rgba(15, 23, 42, 0.1),
        0 0 0 1px rgba(139, 92, 246, 0.28),
        0 0 56px rgba(139, 92, 246, 0.32),
        0 1px 0 rgba(255, 255, 255, 0.65) inset;
    }
    .tile:hover::after {
      opacity: 1;
    }

    .tile--display:focus-visible {
      outline: none;
      border-color: rgba(59, 130, 246, 0.55);
      box-shadow:
        0 0 0 3px rgba(59, 130, 246, 0.3),
        0 0 48px rgba(59, 130, 246, 0.22);
    }
    .tile--input:focus-visible {
      outline: none;
      border-color: rgba(16, 185, 129, 0.5);
      box-shadow:
        0 0 0 3px rgba(16, 185, 129, 0.28),
        0 0 44px rgba(16, 185, 129, 0.2);
    }
    .tile--admin:focus-visible {
      outline: none;
      border-color: rgba(139, 92, 246, 0.55);
      box-shadow:
        0 0 0 3px rgba(139, 92, 246, 0.32),
        0 0 48px rgba(139, 92, 246, 0.25);
    }

    .tile__badge {
      width: 3.75rem;
      height: 3.75rem;
      border-radius: 9999px;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
      margin-bottom: 0.65rem;
      border: 1px solid rgba(255, 255, 255, 0.55);
      box-shadow:
        0 6px 18px rgba(15, 23, 42, 0.07),
        inset 0 1px 0 rgba(255, 255, 255, 0.65);
    }
    .tile__badge svg {
      width: 2rem;
      height: 2rem;
      stroke-width: 1.85;
    }
    .tile__badge--display {
      background: linear-gradient(145deg, rgba(59, 130, 246, 0.28), rgba(99, 102, 241, 0.18));
      color: rgb(29 78 216);
    }
    .tile__badge--input {
      background: linear-gradient(145deg, rgba(16, 185, 129, 0.28), rgba(20, 184, 166, 0.16));
      color: rgb(4 120 87);
    }
    .tile__badge--admin {
      background: linear-gradient(145deg, rgba(139, 92, 246, 0.26), rgba(59, 130, 246, 0.14));
      color: rgb(91 33 182);
    }

    .tile__kicker {
      font-size: clamp(0.625rem, 1.05vw, 0.6875rem);
      font-weight: 800;
      letter-spacing: 0.22em;
      text-transform: uppercase;
      color: rgb(100 116 139);
      margin: 0 0 0.3rem;
      line-height: 1.25;
    }
    .tile__title {
      font-family: Montserrat, Inter, ui-sans-serif, sans-serif;
      font-size: clamp(1.2rem, 2.15vw, 1.5rem);
      font-weight: 800;
      letter-spacing: -0.03em;
      margin: 0;
      line-height: 1.22;
      color: #0f172a;
    }
    .tile__desc {
      flex: 1 1 auto;
      min-height: 0;
      margin: 0.5rem 0 0;
      padding-bottom: 0.75rem;
      font-size: clamp(0.875rem, 1.35vw, 0.9375rem);
      line-height: 1.52;
      color: rgb(100 116 139);
      font-weight: 450;
      max-width: 32ch;
      width: 100%;
    }

    .tile__cta {
      display: flex;
      width: 100%;
      justify-content: center;
      align-items: center;
      margin-top: 0;
      padding: 0.8rem 1.1rem;
      border-radius: 0.875rem;
      font-size: 0.875rem;
      font-weight: 700;
      letter-spacing: 0.02em;
      flex-shrink: 0;
      transition:
        transform 320ms cubic-bezier(0.22, 1, 0.36, 1),
        box-shadow 320ms cubic-bezier(0.22, 1, 0.36, 1),
        background 320ms ease,
        border-color 320ms ease,
        color 320ms ease;
    }
    .tile--display:hover .tile__cta--glass {
      transform: translateY(-1px);
      border-color: rgba(59, 130, 246, 0.38);
      box-shadow: 0 10px 28px rgba(59, 130, 246, 0.14);
    }
    .tile--input:hover .tile__cta--glass {
      transform: translateY(-1px);
      border-color: rgba(16, 185, 129, 0.35);
      box-shadow: 0 10px 28px rgba(16, 185, 129, 0.12);
    }
    .tile__cta--glass {
      background: rgba(255, 255, 255, 0.38);
      backdrop-filter: blur(14px);
      -webkit-backdrop-filter: blur(14px);
      border: 1px solid rgba(255, 255, 255, 0.55);
      color: rgb(30 64 175);
      box-shadow: 0 4px 16px rgba(15, 23, 42, 0.06);
    }
    .tile__cta--admin {
      background: linear-gradient(180deg, #2563eb 0%, #1d4ed8 100%);
      border: 1px solid rgba(255, 255, 255, 0.22);
      color: #fff;
      box-shadow:
        0 10px 28px rgba(37, 99, 235, 0.35),
        inset 0 1px 0 rgba(255, 255, 255, 0.2);
    }
    .tile--admin:hover .tile__cta--admin {
      transform: translateY(-1px);
      box-shadow:
        0 14px 32px rgba(37, 99, 235, 0.45),
        inset 0 1px 0 rgba(255, 255, 255, 0.25);
    }
  </style>
</head>
<body class="pm-app pm-mesh-bg pm-home-lock">
  <main class="main">
    <header class="intro">
      <p class="intro-eyebrow"><?php echo htmlspecialchars(__('presence_intelligence')); ?></p>
      <h1 class="intro-title-brand">
        <span class="intro-title__in">In</span><span class="intro-title__slash" aria-hidden="true">/</span><span class="intro-title__out">Out</span>
      </h1>
      <div class="intro-lede-wrap">
        <p class="intro-lede"><?php echo htmlspecialchars(__('home_lede')); ?></p>
      </div>
    </header>

    <div class="portal-grid">
      <a href="/display-mode" class="tile tile--display" data-pm-tile aria-label="<?php echo htmlspecialchars(__('open_display_mode')); ?>">
        <div class="tile__badge tile__badge--display" aria-hidden="true">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="14" x="2" y="3" rx="2"/><path d="M8 21h8"/><path d="M12 17v4"/></svg>
        </div>
        <p class="tile__kicker"><?php echo htmlspecialchars(__('featured')); ?></p>
        <h2 class="tile__title"><?php echo htmlspecialchars(__('display_mode')); ?></h2>
        <p class="tile__desc"><?php echo htmlspecialchars(__('display_mode_desc')); ?></p>
        <span class="tile__cta tile__cta--glass"><?php echo htmlspecialchars(__('open_display_mode')); ?></span>
      </a>

      <a href="/input-form" class="tile tile--input" data-pm-tile aria-label="<?php echo htmlspecialchars(__('input_form')); ?>">
        <div class="tile__badge tile__badge--input" aria-hidden="true">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><rect width="14" height="20" x="5" y="2" rx="2" ry="2"/><path d="M12 18h.01"/></svg>
        </div>
        <p class="tile__kicker"><?php echo htmlspecialchars(__('employees')); ?></p>
        <h2 class="tile__title"><?php echo htmlspecialchars(__('input_form')); ?></h2>
        <p class="tile__desc"><?php echo htmlspecialchars(__('input_form_desc')); ?></p>
        <span class="tile__cta tile__cta--glass"><?php echo htmlspecialchars(__('submit_presence')); ?></span>
      </a>

      <a href="/admin-panel" class="tile tile--admin" data-pm-tile aria-label="<?php echo htmlspecialchars(__('open_admin_panel')); ?>">
        <div class="tile__badge tile__badge--admin" aria-hidden="true">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/><path d="m9 12 2 2 4-4"/></svg>
        </div>
        <p class="tile__kicker"><?php echo htmlspecialchars(__('operations')); ?></p>
        <h2 class="tile__title"><?php echo htmlspecialchars(__('admin_panel')); ?></h2>
        <p class="tile__desc"><?php echo htmlspecialchars(__('admin_panel_desc')); ?></p>
        <span class="tile__cta tile__cta--admin"><?php echo htmlspecialchars(__('open_admin_panel')); ?></span>
      </a>
    </div>
  </main>
  <script>
    document.addEventListener("keydown", (event) => {
      if (event.key === "1") window.location.href = "/display-mode";
      if (event.key === "2") window.location.href = "/input-form";
      if (event.key === "3") window.location.href = "/admin-panel";
    });
    document.querySelectorAll("[data-pm-tile]").forEach(function (el) {
      el.addEventListener("pointermove", function (e) {
        const r = el.getBoundingClientRect();
        el.style.setProperty("--glow-x", ((e.clientX - r.left) / r.width) * 100 + "%");
        el.style.setProperty("--glow-y", ((e.clientY - r.top) / r.height) * 100 + "%");
      });
    });
  </script>
</body>
</html>
