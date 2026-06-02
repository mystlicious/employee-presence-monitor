<!doctype html>
<html lang="<?php echo htmlspecialchars(app_lang_attr()); ?>">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title><?php echo htmlspecialchars(__('app_name')); ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
  <?php include __DIR__ . '/partials/pm-design-snippet.php'; ?>
  <style>
    :root {
      --sig-ink: #f1f5f9;
      --sig-muted: #94a3b8;
      --sig-line: rgba(148, 163, 184, 0.35);
      --sig-ok: #34d399;
      --sig-warn: #fbbf24;
      --sig-danger: #f87171;
      --ease: cubic-bezier(0, 0, 0.2, 1);
      --dur: 300ms;
      --sig-bar-h: 4.5rem;
      --ctrl-h: 2.5rem;
    }
    * { box-sizing: border-box; }
    html {
      width: 100%;
      height: 100%;
      overflow: hidden;
      background: #0b1220;
      overscroll-behavior: none;
    }
    body.pm-app.pm-mesh-bg-dark {
      margin: 0;
      min-height: 100vh;
      height: 100vh;
      overflow: hidden;
      color: var(--sig-ink);
      font-family: Inter, ui-sans-serif, system-ui, -apple-system, sans-serif;
      background: #0b1220;
    }
    .sig-bar {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      z-index: 100;
      height: var(--sig-bar-h);
      display: grid;
      grid-template-columns: minmax(0, 1fr) auto minmax(0, 1fr);
      align-items: center;
      gap: 1rem;
      padding: 0 clamp(0.75rem, 2vw, 1.5rem);
      background: rgba(15, 23, 42, 0.72);
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
      border-bottom: 1px solid rgba(148, 163, 184, 0.2);
      box-shadow: 0 20px 50px rgba(0, 0, 0, 0.35);
    }
    .sig-bar__left {
      display: flex;
      align-items: center;
      gap: 0.75rem;
      min-width: 0;
    }
    .sig-brand {
      font-size: clamp(0.95rem, 1.5vw, 1.1rem);
      font-weight: 800;
      letter-spacing: -0.03em;
      color: #fff;
      white-space: nowrap;
    }
    .sig-bar__stats {
      display: flex;
      align-items: center;
      gap: 0.625rem;
      flex-wrap: wrap;
      justify-content: center;
    }
    .sig-pill {
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      padding: 0.4rem 0.9rem;
      border-radius: 999px;
      font-size: 0.6875rem;
      font-weight: 700;
      letter-spacing: 0.06em;
      text-transform: uppercase;
      border: 1px solid transparent;
      background: rgba(255, 255, 255, 0.06);
    }
    .sig-pill span { color: var(--sig-muted); font-weight: 600; }
    .sig-pill strong { font-size: 1.125rem; font-weight: 800; letter-spacing: -0.02em; color: #fff; }
    .sig-pill--in {
      border-color: rgba(52, 211, 153, 0.45);
      box-shadow: 0 0 24px rgba(16, 185, 129, 0.22);
    }
    .sig-pill--in strong { color: var(--sig-ok); }
    .sig-pill--out {
      border-color: rgba(248, 113, 113, 0.4);
      box-shadow: 0 0 24px rgba(239, 68, 68, 0.18);
    }
    .sig-pill--out strong { color: var(--sig-danger); }
    .sig-bar__right {
      text-align: right;
      min-width: 0;
    }
    .sig-bar__right .sig-sub {
      font-size: 0.65rem;
      font-weight: 600;
      letter-spacing: 0.1em;
      text-transform: uppercase;
      color: var(--sig-muted);
      margin: 0;
    }
    .sig-clock {
      font-size: clamp(1.15rem, 2vw, 1.5rem);
      font-weight: 800;
      font-variant-numeric: tabular-nums;
      letter-spacing: -0.02em;
      line-height: 1.1;
    }
    .sig-date {
      font-size: 0.75rem;
      color: var(--sig-muted);
      margin-top: 0.15rem;
    }
    .sig-shell {
      height: 100vh;
      padding-top: var(--sig-bar-h);
      display: flex;
      flex-direction: column;
      gap: 0.625rem;
      padding-left: clamp(0.5rem, 1.2vw, 0.75rem);
      padding-right: clamp(0.5rem, 1.2vw, 0.75rem);
      padding-bottom: 0.5rem;
    }
    .sig-tools {
      flex: 0 0 auto;
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      justify-content: space-between;
      gap: 0.5rem;
      padding: 0.5rem 0.75rem;
      border-radius: 1rem;
      background: rgba(255, 255, 255, 0.04);
      border: 1px solid rgba(148, 163, 184, 0.2);
      backdrop-filter: blur(12px);
    }
    .sig-tools__nav {
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      gap: 0.5rem;
    }
    .sig-nav-btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: var(--ctrl-h);
      height: var(--ctrl-h);
      border-radius: 0.625rem;
      border: 1px solid rgba(148, 163, 184, 0.3);
      background: rgba(255, 255, 255, 0.06);
      color: #e2e8f0;
      text-decoration: none;
      transition: background var(--dur) var(--ease), border-color var(--dur) var(--ease);
    }
    .sig-nav-btn:hover {
      background: rgba(59, 130, 246, 0.15);
      border-color: rgba(59, 130, 246, 0.45);
    }
    .sig-nav-btn svg { width: 1.15rem; height: 1.15rem; }
    .sig-date-input {
      height: var(--ctrl-h);
      border-radius: 0.625rem;
      border: 1px solid rgba(148, 163, 184, 0.3);
      background: rgba(15, 23, 42, 0.5);
      color: var(--sig-ink);
      padding: 0 0.65rem 0 2.35rem;
      font: inherit;
      font-size: 0.8125rem;
      color-scheme: dark;
    }
    .sig-date-input::-webkit-calendar-picker-indicator {
      opacity: 0;
      cursor: pointer;
      position: absolute;
      left: 0;
      right: auto;
      width: 2.35rem;
      height: 100%;
    }
    .sig-date-wrap { position: relative; display: inline-flex; align-items: center; }
    .sig-date-pick {
      position: absolute;
      left: 0.55rem;
      right: auto;
      top: 50%;
      transform: translateY(-50%);
      width: 1.15rem;
      height: 1.15rem;
      color: #93c5fd;
      opacity: 0.95;
      pointer-events: none;
    }
    .sig-filter {
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      gap: 0.5rem;
    }
    .sig-filter label { font-size: 0.75rem; color: var(--sig-muted); margin: 0; }
    .sig-filter select {
      height: var(--ctrl-h);
      border-radius: 0.625rem;
      border: 1px solid rgba(148, 163, 184, 0.3);
      background-color: rgba(15, 23, 42, 0.5);
      color: var(--sig-ink);
      font: inherit;
      font-size: 0.8125rem;
      padding: 0 2rem 0 0.65rem;
      min-width: 8rem;
      appearance: none;
      background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");
      background-repeat: no-repeat;
      background-position: right 0.55rem center;
      background-size: 1rem;
    }
    .sig-board {
      flex: 1 1 auto;
      min-height: 0;
      min-width: 0;
      display: flex;
      flex-direction: column;
      padding: 0.25rem 0.25rem 0;
    }
    .sig-board-head {
      flex: 0 0 auto;
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 0.5rem;
      padding: 0.2rem 0.35rem 0.5rem;
    }
    .sig-board-title {
      font-size: clamp(1rem, 1.5vw, 1.2rem);
      font-weight: 800;
      letter-spacing: -0.02em;
    }
    .sig-hint { font-size: 0.8rem; color: var(--sig-muted); margin: 0; }
    .sig-total {
      font-size: 0.7rem;
      color: var(--sig-muted);
      margin-left: 0.5rem;
    }
    #cards {
      display: grid;
      gap: 0.75rem;
      overflow: hidden;
      width: 100%;
      min-height: 0;
    }
    /* Fixed slot grid: always perPage cells (e.g. 3×2) filling the board — same card size whether 1 or 6 logs */
    #cards.cards-balanced {
      flex: 1 1 auto;
      min-height: 0;
      align-content: stretch;
      grid-template-columns: repeat(var(--sig-cols, 3), minmax(0, 1fr));
      grid-template-rows: repeat(var(--sig-rows, 2), minmax(0, 1fr));
      grid-auto-rows: unset;
    }
    #cards.cards-balanced .sig-card {
      min-height: 0;
      height: 100%;
    }
    #cards.cards-balanced .sig-media {
      align-self: stretch;
      height: auto;
      min-height: 7.9rem;
    }
    #cards.cards-balanced .sig-grid-slot {
      min-height: 0;
      height: 100%;
      pointer-events: none;
      visibility: hidden;
    }
    @media (max-width: 600px) {
      .sig-bar {
        grid-template-columns: 1fr;
        height: auto;
        min-height: var(--sig-bar-h);
        padding: 0.5rem clamp(0.75rem, 2vw, 1.5rem);
      }
      .sig-bar__right { text-align: left; }
    }
    .sig-card {
      border-radius: 1.125rem;
      padding: 0.72rem 0.78rem 0.72rem 0.84rem;
      display: grid;
      grid-template-columns: 7.25rem 1fr;
      gap: 0.62rem;
      min-height: 8.4rem;
      background: linear-gradient(180deg, rgba(255, 255, 255, 0.065), rgba(255, 255, 255, 0.03));
      backdrop-filter: blur(14px);
      -webkit-backdrop-filter: blur(14px);
      border: 1px solid rgba(148, 163, 184, 0.28);
      box-shadow:
        0 0 0 1px rgba(255, 255, 255, 0.06) inset,
        0 14px 40px rgba(0, 0, 0, 0.22);
      transition: transform var(--dur) var(--ease), box-shadow var(--dur) var(--ease), border-color var(--dur) var(--ease);
    }
    .sig-media{
      min-width:0;
      align-self:start;
      height: 8.4rem;
      min-height: 7.9rem;
      border-radius: 1rem;
      overflow:hidden;
      background: rgba(15, 23, 42, 0.42);
      border: 1px solid rgba(148, 163, 184, 0.22);
      box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.06);
      position: relative;
    }
    .sig-media::after{
      content:"";
      position:absolute;
      inset:0;
      background: radial-gradient(120px 160px at 20% 25%, rgba(56, 189, 248, 0.18), transparent 55%);
      pointer-events:none;
      opacity: 0.9;
    }
    .sig-glow--in-office {
      border-color: rgba(110, 231, 183, 0.5);
      box-shadow:
        0 0 34px rgba(16, 185, 129, 0.22),
        0 0 0 1px rgba(110, 231, 183, 0.14) inset,
        0 14px 40px rgba(0, 0, 0, 0.22);
    }
    .sig-glow--wfh {
      border-color: rgba(96, 165, 250, 0.55);
      box-shadow:
        0 0 34px rgba(59, 130, 246, 0.22),
        0 0 0 1px rgba(96, 165, 250, 0.14) inset,
        0 14px 40px rgba(0, 0, 0, 0.22);
    }
    .sig-glow--sakit {
      border-color: rgba(248, 113, 113, 0.6);
      box-shadow:
        0 0 36px rgba(239, 68, 68, 0.26),
        0 0 0 1px rgba(248, 113, 113, 0.12) inset,
        0 14px 40px rgba(0, 0, 0, 0.22);
    }
    .sig-glow--cuti {
      border-color: rgba(251, 191, 36, 0.6);
      box-shadow:
        0 0 34px rgba(245, 158, 11, 0.22),
        0 0 0 1px rgba(251, 191, 36, 0.12) inset,
        0 14px 40px rgba(0, 0, 0, 0.22);
    }
    .sig-glow--dinas {
      border-color: rgba(167, 139, 250, 0.6);
      box-shadow:
        0 0 34px rgba(124, 58, 237, 0.22),
        0 0 0 1px rgba(167, 139, 250, 0.12) inset,
        0 14px 40px rgba(0, 0, 0, 0.22);
    }
    .sig-glow--izin-keluar {
      border-color: rgba(251, 146, 60, 0.62);
      box-shadow:
        0 0 34px rgba(249, 115, 22, 0.22),
        0 0 0 1px rgba(251, 146, 60, 0.12) inset,
        0 14px 40px rgba(0, 0, 0, 0.22);
    }
    .sig-glow--other {
      border-color: rgba(148, 163, 184, 0.35);
      box-shadow:
        0 0 26px rgba(148, 163, 184, 0.12),
        0 0 0 1px rgba(255, 255, 255, 0.06) inset,
        0 14px 40px rgba(0, 0, 0, 0.22);
    }
    .sig-avatar {
      width: 100%;
      height: 100%;
      min-height: 7.9rem;
      object-fit: cover;
      display:block;
      filter: saturate(1.05) contrast(1.03);
    }
    .sig-avatar-fallback {
      width: 100%;
      height: 100%;
      min-height: 7.9rem;
      display: flex;
      align-items: center;
      justify-content: center;
      background: linear-gradient(135deg, rgba(59, 130, 246, 0.22), rgba(124, 58, 237, 0.18));
      color: #e0f2fe;
      font-weight: 900;
      font-size: 1.65rem;
      letter-spacing: -0.02em;
    }
    .sig-card-main { min-width: 0; display: flex; flex-direction: column; min-height: 0; }
    .sig-card-top {
      display: flex;
      align-items: flex-start;
      justify-content: space-between;
      gap: 0.5rem;
      flex-wrap: wrap;
    }
    .sig-name {
      flex: 1;
      min-width: 0;
      font-size: clamp(1.05rem, 1.65vw, 1.45rem);
      font-weight: 800;
      letter-spacing: -0.025em;
      line-height: 1.25;
      white-space: normal;
      overflow-wrap: anywhere;
      word-break: break-word;
    }
    .sig-status {
      flex-shrink: 0;
      max-width: min(240px, 48%);
      padding: 0.35rem 0.75rem;
      border-radius: 999px;
      font-size: clamp(0.72rem, 1vw, 0.88rem);
      font-weight: 700;
      border: 1px solid rgba(148, 163, 184, 0.35);
      background: rgba(255, 255, 255, 0.06);
      color: #e2e8f0;
      line-height: 1.25;
      word-break: break-word;
      display: -webkit-box;
      -webkit-box-orient: vertical;
      -webkit-line-clamp: 2;
      overflow: hidden;
    }
    .sig-status--wfh {
      background: rgba(59, 130, 246, 0.2);
      border-color: rgba(96, 165, 250, 0.7);
      color: #dbeafe;
      box-shadow: 0 0 16px rgba(59, 130, 246, 0.26);
    }
    .sig-status--sakit {
      background: rgba(239, 68, 68, 0.2);
      border-color: rgba(248, 113, 113, 0.72);
      color: #fee2e2;
      box-shadow: 0 0 16px rgba(239, 68, 68, 0.25);
    }
    .sig-status--cuti {
      background: rgba(245, 158, 11, 0.2);
      border-color: rgba(251, 191, 36, 0.72);
      color: #fef3c7;
      box-shadow: 0 0 16px rgba(245, 158, 11, 0.22);
    }
    .sig-status--dinas {
      background: rgba(124, 58, 237, 0.2);
      border-color: rgba(167, 139, 250, 0.72);
      color: #ede9fe;
      box-shadow: 0 0 16px rgba(124, 58, 237, 0.24);
    }
    .sig-status--izin-keluar {
      background: rgba(249, 115, 22, 0.22);
      border-color: rgba(251, 146, 60, 0.74);
      color: #ffedd5;
      box-shadow: 0 0 16px rgba(249, 115, 22, 0.24);
    }
    .sig-status--other,
    .sig-status--in-office {
      background: rgba(52, 211, 153, 0.2);
      border-color: rgba(110, 231, 183, 0.72);
      color: #d1fae5;
      box-shadow: 0 0 16px rgba(52, 211, 153, 0.22);
    }
    .sig-info { margin-top: 0.45rem; display: grid; gap: 0.25rem; }
    .sig-info-row { display: flex; align-items: center; gap: 0.35rem; min-width: 0; }
    .sig-info-svg {
      width: 1rem;
      height: 1rem;
      flex-shrink: 0;
      color: #7dd3fc;
      opacity: 0.9;
    }
    .sig-info-text {
      font-size: clamp(0.78rem, 0.95vw, 0.88rem);
      color: #cbd5e1;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }
    .sig-info-text strong { color: #f1f5f9; font-weight: 700; }
    .sig-meta-grid {
      display: grid;
      grid-template-columns: repeat(2, minmax(0, 1fr));
      gap: 0.35rem;
      margin-top: 0.15rem;
    }
    .sig-meta-chip {
      display: inline-flex;
      align-items: center;
      gap: 0.32rem;
      min-width: 0;
      border-radius: 0.6rem;
      background: rgba(15, 23, 42, 0.42);
      border: 1px solid rgba(148, 163, 184, 0.18);
      padding: 0.22rem 0.35rem;
    }
    .sig-meta-chip .sig-info-text {
      font-size: clamp(0.72rem, 0.9vw, 0.8rem);
    }
    .sig-note {
      margin-top: 0.35rem;
      position: relative;
      padding: 0.4rem 0.48rem 0.4rem 1.7rem;
      border-radius: 0.75rem;
      background: rgba(15, 23, 42, 0.54);
      border: 1px solid rgba(148, 163, 184, 0.15);
      font-size: clamp(0.72rem, 0.86vw, 0.8rem);
      color: #cbd5e1;
      line-height: 1.4;
      display: -webkit-box;
      -webkit-box-orient: vertical;
      -webkit-line-clamp: 3;
      overflow: hidden;
      text-overflow: ellipsis;
    }
    .sig-note .quote-svg {
      position: absolute;
      left: 0.45rem;
      top: 0.45rem;
      width: 1.1rem;
      height: 1.1rem;
      color: #64748b;
      opacity: 0.85;
    }
    .sig-empty {
      grid-column: 1 / -1;
      min-height: 10rem;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      border: 1px dashed rgba(148, 163, 184, 0.35);
      border-radius: 1.25rem;
      background: rgba(255, 255, 255, 0.03);
      padding: 1.25rem;
      text-align: center;
    }
    .sig-pager {
      flex: 0 0 auto;
      font-size: 0.78rem;
      color: var(--sig-muted);
      text-align: right;
      padding: 0.15rem 0.35rem 0;
      min-height: 1.05rem;
    }
    .sig-viewport {
      width: 100%;
      max-width: 100%;
      overflow-x: hidden;
    }
    @media (max-width: 1023px) {
      html {
        height: auto;
        overflow-x: hidden;
        overflow-y: auto;
      }
      body.pm-app.pm-mesh-bg-dark {
        height: auto;
        min-height: 100dvh;
        overflow-x: hidden;
        overflow-y: visible;
      }
      .sig-bar {
        position: relative;
        top: auto;
        left: auto;
        right: auto;
        height: auto;
        min-height: 0;
        display: flex;
        flex-direction: column;
        align-items: stretch;
        gap: 0.65rem;
        padding: 0.75rem 1rem;
      }
      .sig-bar__left,
      .sig-bar__stats,
      .sig-bar__right {
        min-width: 0;
        width: 100%;
        text-align: left;
      }
      .sig-bar__stats {
        justify-content: flex-start;
        flex-wrap: wrap;
      }
      .sig-shell {
        height: auto;
        min-height: 0;
        padding-top: 0;
        padding-left: 0.75rem;
        padding-right: 0.75rem;
        padding-bottom: 1rem;
        overflow: visible;
      }
      .sig-board {
        min-height: 0;
        overflow: visible;
      }
      .sig-board-head {
        flex-direction: column;
        align-items: flex-start;
      }
      .sig-hint { display: none; }
      #cards,
      #cards.cards-balanced {
        display: flex !important;
        flex-direction: column !important;
        gap: 0.75rem !important;
        height: auto !important;
        min-height: 0 !important;
        overflow: visible !important;
        grid-template-columns: unset !important;
        grid-template-rows: unset !important;
      }
      #cards.cards-balanced .sig-grid-slot {
        display: none !important;
      }
      #cards.cards-balanced .sig-card {
        height: auto !important;
        min-height: 0 !important;
      }
      .sig-card {
        grid-template-columns: 5.5rem 1fr;
        align-items: stretch;
        min-height: auto;
        height: auto;
        padding: 0.65rem;
      }
      .sig-media {
        align-self: stretch;
        height: auto;
        min-height: 5.75rem;
        max-height: none;
      }
      .sig-avatar,
      .sig-avatar-fallback {
        min-height: 100%;
        height: 100%;
      }
      .sig-tools {
        flex-direction: column;
        align-items: stretch;
        gap: 0.65rem;
      }
      .sig-tools__nav {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 0.5rem;
        width: 100%;
      }
      .sig-tools__nav form {
        flex: 1 1 10.5rem;
        min-width: 0;
        max-width: 100%;
      }
      .sig-date-wrap {
        flex: 1 1 auto;
        width: 100%;
        min-width: 10.5rem;
      }
      .sig-date-input {
        width: 100%;
        min-width: 0;
        font-size: 0.875rem;
        padding-left: 2.35rem;
        padding-right: 0.65rem;
      }
      .sig-filter select {
        width: 100%;
        min-width: 0;
      }
    }
  </style>
  <meta http-equiv="refresh" content="120">
</head>
<body class="pm-app pm-mesh-bg-dark">
  <header class="sig-bar" id="sigBar">
    <div class="sig-bar__left">
      <span class="sig-brand"><?php echo htmlspecialchars(__('app_name')); ?></span>
      <span class="pm-live-pulse-dark" title="<?php echo htmlspecialchars(__('live_refresh')); ?>"><?php echo htmlspecialchars(__('live')); ?></span>
    </div>
    <div class="sig-bar__stats">
      <div class="sig-pill sig-pill--in">
        <span><?php echo htmlspecialchars(__('in_office')); ?></span>
        <strong><?php echo htmlspecialchars($inOffice); ?></strong>
      </div>
      <div class="sig-pill sig-pill--out">
        <span><?php echo htmlspecialchars(__('not_in_office')); ?></span>
        <strong><?php echo htmlspecialchars($notIn); ?></strong>
      </div>
    </div>
    <div class="sig-bar__right">
      <p class="sig-sub"><?php echo htmlspecialchars(__('local_time')); ?></p>
      <div id="clock" class="sig-clock">--:--:--</div>
      <div id="date" class="sig-date">--</div>
    </div>
  </header>

  <div class="sig-shell sig-viewport">
    <div class="sig-tools">
      <div class="sig-tools__nav">
        <a class="sig-nav-btn" href="/display-mode?date=<?php echo htmlspecialchars($prevDate); ?><?php echo $statusFilter !== '' ? '&status=' . urlencode($statusFilter) : ''; ?>" aria-label="<?php echo htmlspecialchars(__('previous_day')); ?>">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 6l-6 6 6 6"/></svg>
        </a>
        <form method="GET" action="/display-mode" style="display:flex;align-items:center;gap:0.5rem;margin:0;">
          <?php if ($statusFilter !== ''): ?>
            <input type="hidden" name="status" value="<?php echo htmlspecialchars($statusFilter); ?>">
          <?php endif; ?>
          <span class="sig-date-wrap">
            <input class="sig-date-input" id="displayDateInput" type="date" name="date" value="<?php echo htmlspecialchars($selectedDate); ?>" required>
            <svg class="sig-date-pick" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/></svg>
          </span>
        </form>
        <a class="sig-nav-btn" href="/display-mode?date=<?php echo htmlspecialchars($nextDate); ?><?php echo $statusFilter !== '' ? '&status=' . urlencode($statusFilter) : ''; ?>" aria-label="<?php echo htmlspecialchars(__('next_day')); ?>">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 6l6 6-6 6"/></svg>
        </a>
      </div>
      <form method="GET" action="/display-mode" class="sig-filter" id="statusFilterForm">
        <input type="hidden" name="date" value="<?php echo htmlspecialchars($selectedDate); ?>">
        <label for="statusFilter"><?php echo htmlspecialchars(__('status')); ?></label>
        <select name="status" id="statusFilter" onchange="this.form.submit()">
          <option value=""<?php echo $statusFilter === '' ? ' selected' : ''; ?>><?php echo htmlspecialchars(__('all')); ?></option>
          <?php foreach ($filterStatuses as $fs): ?>
            <option value="<?php echo htmlspecialchars($fs); ?>"<?php echo $statusFilter === $fs ? ' selected' : ''; ?>><?php echo htmlspecialchars(ui_status_label($fs)); ?></option>
          <?php endforeach; ?>
        </select>
      </form>
    </div>

    <section class="sig-board">
      <div class="sig-board-head">
        <div class="sig-board-title"><?php echo htmlspecialchars(__('log')); ?> · <?php echo htmlspecialchars($selectedDate); ?></div>
        <p class="sig-hint"><?php echo htmlspecialchars(__('auto_page')); ?></p>
      </div>
      <div id="cards"></div>
    </section>

    <footer class="sig-pager" id="pager"></footer>
  </div>

  <script>
    const i18n = <?php echo json_encode([
        'fullDay' => __('full_day'),
        'loggedAt' => __('logged_at_prefix'),
        'noMatching' => __('no_matching_entries'),
        'noLogsDate' => __('no_logs_date'),
        'tryOtherFilter' => __('try_other_filter'),
        'everyoneInOffice' => __('everyone_in_office'),
        'showingPage' => __('showing_page'),
    ], JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
    const statusFilterActive = <?php echo json_encode($statusFilter !== ''); ?>;
    const absentLogs = <?php echo json_encode($logs, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
    let currentPage = 0;
    let perPage = 12;
    let cycle = null;

    const icons = {
      location: '<svg class="sig-info-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21s-7-4.35-7-10a7 7 0 1114 0c0 5.65-7 10-7 10z"/><circle cx="12" cy="11" r="2"/></svg>',
      clock: '<svg class="sig-info-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>',
      send: '<svg class="sig-info-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 2L11 13"/><path d="M22 2l-7 20-4-9-9-4 20-7z"/></svg>',
      quote: '<svg class="quote-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M7 7a5 5 0 00-5 5v6h6v-6H4a5 5 0 015-5V7H7zm13 0a5 5 0 00-5 5v6h6v-6h-4a5 5 0 015-5V7h-6z"/></svg>',
      check: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:2.5rem;height:2.5rem;color:#34d399"><circle cx="12" cy="12" r="10"/><path d="M8 12l2.5 2.5L16 9"/></svg>',
      search: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:2.5rem;height:2.5rem;color:#38bdf8"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/></svg>',
    };

    function initials(name) {
      if (!name) return '?';
      return name.trim().charAt(0).toUpperCase();
    }

    function escapeHtml(s) {
      if (s == null || s === '') return '';
      return String(s)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#39;');
    }

    function escapeAttr(s) {
      return String(s)
        .replace(/&/g, '&amp;')
        .replace(/"/g, '&quot;')
        .replace(/</g, '&lt;')
        .replace(/\n/g, ' ');
    }

    function glowClassFromTone(statusTone) {
      if (statusTone === 'sig-status--wfh') return 'sig-glow--wfh';
      if (statusTone === 'sig-status--sakit') return 'sig-glow--sakit';
      if (statusTone === 'sig-status--cuti') return 'sig-glow--cuti';
      if (statusTone === 'sig-status--dinas') return 'sig-glow--dinas';
      if (statusTone === 'sig-status--izin-keluar') return 'sig-glow--izin-keluar';
      if (statusTone === 'sig-status--in-office') return 'sig-glow--in-office';
      if (statusTone === 'sig-status--other') return 'sig-glow--other';
      return 'sig-glow--other';
    }

    function createCard(item) {
      const node = document.createElement('article');
      const statusTone = statusToneClass(item.status);
      node.className = 'sig-card ' + glowClassFromTone(statusTone);
      const timeLabel = (item.start_time && item.end_time)
        ? `${item.start_time.slice(0,5)} - ${item.end_time.slice(0,5)}`
        : i18n.fullDay;
      const submitted = item.log_time ? item.log_time.slice(0, 5) : '-';
      const name = escapeHtml(item.employee_name) || '-';
      const status = escapeHtml(item.status) || '-';
      const location = escapeHtml(item.location) || '-';
      const rawNote = (item.note != null && String(item.note).trim() !== '') ? String(item.note) : '';
      const noteHtml = rawNote ? escapeHtml(rawNote) : '—';
      const photoSrc = item.photo ? escapeHtml(item.photo) : '';
      node.innerHTML = `
        <div class="sig-media">${photoSrc ? `<img class="sig-avatar" src="${photoSrc}" alt="" width="160" height="160" decoding="async">` : `<div class="sig-avatar-fallback">${initials(item.employee_name)}</div>`}</div>
        <div class="sig-card-main">
          <div class="sig-card-top">
            <div class="sig-name">${name}</div>
            <span class="sig-status ${statusTone}">${status}</span>
          </div>
          <div class="sig-info">
            <div class="sig-info-row">
              ${icons.location}
              <span class="sig-info-text"><strong>${location}</strong></span>
            </div>
            <div class="sig-info-row">
              ${icons.clock}
              <span class="sig-info-text">${timeLabel}</span>
            </div>
            <div class="sig-info-row">
              ${icons.send}
              <span class="sig-info-text">${i18n.loggedAt} <strong>${submitted}</strong></span>
            </div>
          </div>
          <div class="sig-note" title="${rawNote ? escapeAttr(rawNote) : ''}">
            ${icons.quote}
            ${noteHtml}
          </div>
        </div>
      `;
      return node;
    }

    function statusToneClass(status) {
      const value = String(status || '').toLowerCase();
      if (value.includes('izin keluar')) return 'sig-status--izin-keluar';
      if (value.includes('sakit')) return 'sig-status--sakit';
      if (value.includes('cuti')) return 'sig-status--cuti';
      if (value.includes('wfh')) return 'sig-status--wfh';
      if (value.includes('dinas')) return 'sig-status--dinas';
      if (value.includes('in office')) return 'sig-status--in-office';
      return 'sig-status--other';
    }

    function verticalRoomForCards() {
      const vh = window.innerHeight || document.documentElement.clientHeight;
      const shell = document.querySelector('.sig-shell');
      const pad = shell ? parseFloat(getComputedStyle(shell).paddingTop) + parseFloat(getComputedStyle(shell).paddingBottom) : 24;
      const tools = document.querySelector('.sig-tools');
      const boardHead = document.querySelector('.sig-board-head');
      const pager = document.getElementById('pager');
      let used = pad + 20;
      if (tools) used += tools.getBoundingClientRect().height;
      if (boardHead) used += boardHead.getBoundingClientRect().height;
      if (pager) used += pager.getBoundingClientRect().height;
      return Math.max(200, vh - used);
    }

    function gridLayout() {
      const w = window.innerWidth;
      if (w <= 600) return { cols: 1, rows: 2 };
      if (w <= 960) return { cols: 2, rows: 2 };
      return { cols: 3, rows: 2 };
    }

    function isMobileStack() {
      return window.innerWidth <= 1023;
    }

    function computePerPage() {
      if (isMobileStack()) {
        return Math.max(1, absentLogs.length);
      }
      const { cols, rows } = gridLayout();
      return cols * rows;
    }

    function renderCards() {
      const host = document.getElementById('cards');
      const pager = document.getElementById('pager');
      if (!host || !pager) return;
      host.innerHTML = '';
      perPage = computePerPage();

      if (!absentLogs.length) {
        host.classList.remove('cards-balanced');
        host.style.minHeight = '';
        host.style.height = '';
        host.style.removeProperty('--sig-cols');
        host.style.removeProperty('--sig-rows');
        const emptyTitle = statusFilterActive ? i18n.noMatching : i18n.noLogsDate;
        const emptySub = statusFilterActive ? i18n.tryOtherFilter : i18n.everyoneInOffice;
        host.innerHTML = `
          <div class="sig-empty">
            <div style="margin-bottom:0.5rem;display:flex;justify-content:center;">${statusFilterActive ? icons.search : icons.check}</div>
            <div style="font-size:1.25rem;font-weight:800;">${emptyTitle}</div>
            <div style="color:#94a3b8;margin-top:0.35rem;">${emptySub}</div>
          </div>`;
        pager.textContent = '';
        return;
      }

      const pages = Math.ceil(absentLogs.length / perPage);
      if (currentPage >= pages) currentPage = 0;
      const start = currentPage * perPage;
      const end = Math.min(start + perPage, absentLogs.length);

      const visibleCount = end - start;
      const layout = gridLayout();
      const mobileStack = isMobileStack();
      absentLogs.slice(start, end).forEach((item) => host.appendChild(createCard(item)));
      if (!mobileStack) {
      const emptySlots = perPage - visibleCount;
      for (let i = 0; i < emptySlots; i++) {
        const slot = document.createElement('div');
        slot.className = 'sig-grid-slot';
        slot.setAttribute('aria-hidden', 'true');
        host.appendChild(slot);
      }
      }
      pager.textContent = i18n.showingPage
        .replace(':from', String(start + 1))
        .replace(':to', String(end))
        .replace(':total', String(absentLogs.length))
        .replace(':page', String(currentPage + 1))
        .replace(':pages', String(pages));
      if (mobileStack) {
        host.classList.remove('cards-balanced');
        host.style.minHeight = '';
        host.style.height = '';
        host.style.removeProperty('--sig-cols');
        host.style.removeProperty('--sig-rows');
      } else {
        const room = verticalRoomForCards();
        host.style.setProperty('--sig-cols', String(layout.cols));
        host.style.setProperty('--sig-rows', String(layout.rows));
        host.style.minHeight = room + 'px';
        host.style.height = room + 'px';
        host.classList.add('cards-balanced');
      }
      currentPage = (currentPage + 1) % pages;
    }

    function startCycle() {
      if (cycle) clearInterval(cycle);
      renderCards();
      if (absentLogs.length > perPage) {
        cycle = setInterval(renderCards, 8000);
      }
    }

    function updateClock() {
      const now = new Date();
      const clock = document.getElementById('clock');
      const date = document.getElementById('date');
      if (!clock || !date) return;
      clock.textContent = now.toLocaleTimeString('id-ID', { hour12: false });
      date.textContent = now.toLocaleDateString('id-ID', { weekday: 'long', day: '2-digit', month: 'long', year: 'numeric' });
    }

    window.addEventListener('resize', startCycle);
    const displayDateInput = document.getElementById('displayDateInput');
    if (displayDateInput) {
      const dateWrap = displayDateInput.closest('.sig-date-wrap');
      dateWrap?.addEventListener('click', () => {
        if (typeof displayDateInput.showPicker === 'function') {
          displayDateInput.showPicker();
        } else {
          displayDateInput.focus();
        }
      });
      displayDateInput.addEventListener('change', () => {
        if (!displayDateInput.value) {
          const params = new URLSearchParams(window.location.search);
          params.delete('date');
          const q = params.toString();
          window.location.assign('/display-mode' + (q ? '?' + q : ''));
          return;
        }
        displayDateInput.form?.submit();
      });
    }

    updateClock();
    setInterval(updateClock, 1000);
    startCycle();
  </script>
</body>
</html>
