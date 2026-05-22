<?php
/**
 * Premium surfaces: animated mesh gradient, film grain noise, Inter stylistic sets.
 * Use on body with .pm-mesh-bg (light), .pm-mesh-bg-dark (TV/signage), or .pm-mesh-bg-admin (admin shell).
 */
?>
<style id="pm-design-system">
  @keyframes pm-mesh-shift {
    0%, 100% { transform: translate(0, 0) scale(1); }
    33% { transform: translate(1.5%, -1%) scale(1.02); }
    66% { transform: translate(-1%, 1.5%) scale(1.01); }
  }
  @keyframes pm-pulse-dot {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.72; transform: scale(0.92); }
  }
  .pm-app,
  .admin-app {
    font-feature-settings: "cv02", "cv03", "cv04", "ss01";
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
  }
  body.pm-mesh-bg,
  body.pm-mesh-bg-admin,
  body.pm-mesh-bg-dark {
    position: relative;
    isolation: isolate;
  }
  body.pm-mesh-bg::before,
  body.pm-mesh-bg-admin::before,
  body.pm-mesh-bg-dark::before {
    content: "";
    position: fixed;
    inset: 0;
    z-index: -2;
    animation: pm-mesh-shift 22s ease-in-out infinite;
    will-change: transform;
  }
  body.pm-mesh-bg::before {
    background:
      radial-gradient(ellipse 85% 55% at 15% 35%, rgba(99, 102, 241, 0.38), transparent 52%),
      radial-gradient(ellipse 70% 50% at 88% 18%, rgba(59, 130, 246, 0.32), transparent 48%),
      radial-gradient(ellipse 55% 65% at 55% 88%, rgba(14, 165, 233, 0.22), transparent 52%),
      linear-gradient(168deg, #f8fafc 0%, #eef2ff 42%, #f0f9ff 100%);
  }
  body.pm-mesh-bg-admin::before {
    background:
      radial-gradient(ellipse 120% 80% at 10% -10%, rgba(59, 130, 246, 0.12), transparent 45%),
      radial-gradient(ellipse 80% 60% at 95% 60%, rgba(139, 92, 246, 0.08), transparent 50%),
      linear-gradient(180deg, #f5f7fb 0%, #eef2f7 50%, #f8fafc 100%);
    animation-duration: 28s;
  }
  body.pm-mesh-bg-dark::before {
    background:
      radial-gradient(ellipse 90% 55% at 25% 15%, rgba(59, 130, 246, 0.18), transparent 55%),
      radial-gradient(ellipse 75% 50% at 75% 75%, rgba(139, 92, 246, 0.12), transparent 50%),
      linear-gradient(185deg, #0b1220 0%, #0f172a 35%, #1e293b 100%);
    animation-duration: 26s;
  }
  body.pm-mesh-bg::after,
  body.pm-mesh-bg-admin::after,
  body.pm-mesh-bg-dark::after {
    content: "";
    position: fixed;
    inset: 0;
    z-index: -1;
    pointer-events: none;
    opacity: 0.06;
    background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 512 512' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");
    mix-blend-mode: overlay;
  }
  body.pm-mesh-bg-dark::after {
    opacity: 0.09;
    mix-blend-mode: soft-light;
  }
  body.pm-mesh-bg-admin::after {
    opacity: 0.045;
  }
  .pm-live-pulse-dark {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    font-size: 0.65rem;
    font-weight: 700;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: #94a3b8;
  }
  .pm-live-pulse-dark::before {
    content: "";
    width: 7px;
    height: 7px;
    border-radius: 999px;
    background: #34d399;
    box-shadow: 0 0 14px rgba(52, 211, 153, 0.85);
    animation: pm-pulse-dot 2.2s ease-in-out infinite;
  }
</style>
