  <style>
    :root{
      --ink:#132c53;--muted:#5c7296;--line:#d0e0fb;--danger:#b42348;--ok:#15803d;
      --surface:rgba(255,255,255,.86);
      --nav-bg-1:#0b1730;
      --nav-bg-2:#0f254b;
      --nav-line:rgba(125,211,252,.28);
      --nav-text:#c7d7f8;
      --nav-text-muted:#8ca8dc;
      --nav-active:#eaf1ff;
      --rail-w:72px;
      --rail-w-hover:252px;
    }
    *{box-sizing:border-box}
    body.admin-app{
      margin:0;min-height:100vh;color:var(--ink);
      font-family:Inter,ui-sans-serif,system-ui,-apple-system,"Segoe UI",Roboto,Arial;
      background:transparent;
      padding:0;
    }
    .layout{min-height:100vh;display:flex;min-width:0}
    .sidebar{
      width:var(--rail-w-hover);flex:0 0 var(--rail-w-hover);
      padding:10px;
      background:
        linear-gradient(180deg,var(--nav-bg-1) 0%,var(--nav-bg-2) 100%);
      border-right:1px solid rgba(255,255,255,.08);
      position:sticky;top:0;height:100vh;
      transition:width .22s cubic-bezier(.4,0,.2,1), flex-basis .22s cubic-bezier(.4,0,.2,1), padding .22s ease, box-shadow .22s ease;
      z-index:40;
      box-shadow:20px 0 46px rgba(8,23,53,.26);
      display:flex;
      flex-direction:column;
      gap:8px;
    }
    .admin-mobile-bar,
    .sidebar-backdrop{
      display:none;
    }
    .scrollbar-thin{
      scrollbar-width:thin;
      scrollbar-color:rgba(100,116,139,.45) transparent;
    }
    .scrollbar-thin::-webkit-scrollbar{
      height:6px;
      width:6px;
    }
    .scrollbar-thin::-webkit-scrollbar-thumb{
      background:rgba(100,116,139,.45);
      border-radius:999px;
    }
    .scrollbar-thin::-webkit-scrollbar-track{
      background:transparent;
    }
    @media (min-width:1024px){
      .sidebar{
        position:sticky;
        top:0;
        width:var(--rail-w);
        flex:0 0 var(--rail-w);
        padding:10px 9px;
        overflow:hidden;
        transition:width .24s cubic-bezier(.2,.8,.2,1), flex-basis .24s cubic-bezier(.2,.8,.2,1), padding .24s cubic-bezier(.2,.8,.2,1), box-shadow .24s ease;
      }
      .sidebar:hover,
      html.sb-hover-open .sidebar{
        width:var(--rail-w-hover);
        flex-basis:var(--rail-w-hover);
        padding:10px;
      }
      .sidebar .brand-row{justify-content:center}
      .sidebar .sidebar-head,
      .sidebar .side-nav{
        border-radius:14px;
      }
      .sidebar:hover .brand-row,
      html.sb-hover-open .sidebar .brand-row{justify-content:flex-start}
      .side-nav a{justify-content:flex-start;gap:10px;padding:10px 10px}
      .side-nav .nav-label{display:inline}
      html:not(.sb-hover-open) .sidebar:not(:hover) .nav-caption{display:none}
      html:not(.sb-hover-open) .sidebar:not(:hover) .brand-text{display:none}
      html:not(.sb-hover-open) .sidebar:not(:hover) .side-nav{padding:8px 6px;gap:8px}
      html:not(.sb-hover-open) .sidebar:not(:hover) .nav-group{gap:8px}
      html:not(.sb-hover-open) .sidebar:not(:hover) .nav-group + .nav-group{padding-top:8px}
      html:not(.sb-hover-open) .sidebar:not(:hover) .side-nav a{justify-content:center;padding:10px 8px;gap:0;border-radius:10px;min-height:40px}
      html:not(.sb-hover-open) .sidebar:not(:hover) .side-nav .nav-label{display:none}
      html:not(.sb-hover-open) .sidebar:not(:hover) .sidebar-foot{padding:0}
      html:not(.sb-hover-open) .sidebar:not(:hover) .sidebar-foot .nav-label{display:none}
      html:not(.sb-hover-open) .sidebar:not(:hover) .sidebar-foot .logout-link{justify-content:center;padding:10px 8px;gap:0}
      html:not(.sb-hover-open) .sidebar:not(:hover) .side-nav a.active{
        box-shadow:0 8px 20px rgba(15,23,42,.26);
      }
      .toggle{display:none !important}
      .content{
        flex:1;
        min-width:0;
        contain:layout paint;
      }
    }
    .sidebar-head{
      padding:8px;
      background:rgba(255,255,255,.03);
      border:1px solid rgba(148,163,184,.14);
      border-radius:14px;
    }
    .brand-row{display:flex;align-items:center;justify-content:space-between;gap:10px;margin-bottom:0}
    .brand-mark{
      width:36px;height:36px;border-radius:11px;display:inline-flex;align-items:center;justify-content:center;
      background:linear-gradient(135deg,rgba(147,197,253,.24),rgba(56,189,248,.16));
      border:1px solid rgba(147,197,253,.36);color:#dbeafe;flex-shrink:0;
      box-shadow:0 8px 20px rgba(14,165,233,.22), inset 0 1px 0 rgba(255,255,255,.14);
    }
    .brand-mark svg{width:18px;height:18px;stroke-width:2}
    .brand-text{min-width:0}
    .brand-title{font-weight:900;font-size:.98rem;line-height:1.1;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;color:#e2e8f0}
    .side-sub{font-size:.75rem;color:var(--nav-text-muted);margin-top:2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
    .toggle{
      border:1px solid rgba(148,163,184,.28);background:rgba(15,23,42,.45);border-radius:10px;
      height:40px;width:40px;display:inline-flex;align-items:center;justify-content:center;
      padding:0;flex-shrink:0;
      cursor:pointer;color:#dbeafe;
    }
    .toggle svg{
      width:22px;height:22px;display:block;
      flex-shrink:0;stroke-width:1.75;
    }
    .toggle:hover{background:rgba(30,41,59,.8)}
    .side-nav{
      display:flex;flex-direction:column;gap:10px;margin:0;padding:8px;
      background:rgba(255,255,255,.02);
      border:1px solid rgba(148,163,184,.14);
      border-radius:14px;
      min-height:0;
      overflow-y:auto;
      overflow-x:hidden;
    }
    .nav-group{display:grid;gap:6px}
    .nav-group + .nav-group{padding-top:10px;border-top:1px solid rgba(148,163,184,.18)}
    .nav-caption{
      font-size:.64rem;letter-spacing:.12em;text-transform:uppercase;font-weight:800;
      color:var(--nav-text-muted);padding:0 2px 2px 2px;white-space:nowrap;transition:opacity .18s ease;
    }
    .lang-switch-wrap{
      padding:0 2px 6px;
      flex-shrink:0;
    }
    .lang-switch-form{display:block;margin:0}
    /* Collapsed rail: globe icon only (matches nav link chips) */
    .lang-switch-icon{
      display:none;
      width:100%;
      align-items:center;
      justify-content:center;
      min-height:40px;
      padding:10px 8px;
      margin:0;
      border:1px solid transparent;
      border-radius:10px;
      background:transparent;
      color:#9fb6e8;
      cursor:pointer;
      font-family:inherit;
      transition:background .2s ease, border-color .2s ease, color .2s ease, transform .2s cubic-bezier(.22,1,.36,1);
    }
    .lang-switch-icon svg{
      width:20px;height:20px;
      min-width:20px;min-height:20px;
      display:block;
      flex-shrink:0;
    }
    .lang-switch-icon:hover{
      background:rgba(148,163,184,.14);
      border-color:rgba(191,219,254,.22);
      color:#dbeafe;
    }
    .lang-switch-icon:active{
      transform:scale(.96);
    }
    .lang-switch-track{
      position:relative;
      display:flex;
      align-items:center;
      width:100%;
      max-width:140px;
      height:40px;
      padding:4px;
      border-radius:12px;
      border:1px solid rgba(255,255,255,.12);
      background:rgba(15,23,42,.55);
      backdrop-filter:blur(8px);
      -webkit-backdrop-filter:blur(8px);
      box-shadow:
        inset 0 1px 0 rgba(255,255,255,.1),
        inset 0 -1px 0 rgba(0,0,0,.25),
        0 6px 18px rgba(8,17,35,.35);
      opacity:1;
      transform:scale(1);
      transition:opacity .28s ease, transform .32s cubic-bezier(.22,1,.36,1);
    }
    .lang-switch-pill{
      position:absolute;
      top:4px;
      bottom:4px;
      left:4px;
      width:calc(50% - 4px);
      border-radius:8px;
      background:linear-gradient(180deg,rgba(255,255,255,.32) 0%,rgba(255,255,255,.16) 100%);
      border:1px solid rgba(255,255,255,.24);
      box-shadow:0 2px 10px rgba(0,0,0,.22), inset 0 1px 0 rgba(255,255,255,.28);
      pointer-events:none;
      will-change:transform;
      transition:
        transform .5s cubic-bezier(.34,1.28,.64,1),
        box-shadow .4s cubic-bezier(.22,1,.36,1);
      transform:translate3d(0,0,0) scale(1);
    }
    .lang-switch-wrap[data-lang="id"] .lang-switch-pill{
      transform:translate3d(100%,0,0) scale(1);
    }
    html.lang-nav-pending .lang-switch-wrap{
      pointer-events:none;
    }
    @media (prefers-reduced-motion:reduce){
      .lang-switch-pill,
      .lang-switch-btn,
      .lang-switch-track,
      .lang-switch-icon{
        transition-duration:.01ms !important;
      }
    }
    .lang-switch-btn{
      position:relative;
      z-index:1;
      flex:1 1 50%;
      width:50%;
      margin:0;
      padding:8px 0;
      border:0;
      background:transparent;
      cursor:pointer;
      font-family:inherit;
      font-size:.75rem;
      font-weight:800;
      letter-spacing:.08em;
      text-transform:uppercase;
      line-height:1;
      color:#94a3b8;
      transition:color .35s cubic-bezier(.22,1,.36,1), transform .25s cubic-bezier(.22,1,.36,1);
    }
    .lang-switch-btn:hover{
      color:#e2e8f0;
    }
    .lang-switch-wrap[data-lang="en"] .lang-switch-btn[data-lang-pick="en"],
    .lang-switch-wrap[data-lang="id"] .lang-switch-btn[data-lang-pick="id"]{
      color:#ffffff;
      text-shadow:0 1px 2px rgba(0,0,0,.35);
      transform:scale(1.02);
    }
    @media (min-width:1024px){
      html:not(.sb-hover-open) .sidebar:not(:hover) .lang-switch-icon{
        display:flex;
      }
      html:not(.sb-hover-open) .sidebar:not(:hover) .lang-switch-track{
        display:none;
        opacity:0;
        transform:scale(.94);
        pointer-events:none;
      }
      html:not(.sb-hover-open) .sidebar:not(:hover) .lang-switch-wrap{
        display:block;
        padding:0;
      }
      .sidebar:hover .lang-switch-icon,
      html.sb-hover-open .lang-switch-icon{
        display:none;
      }
      .sidebar:hover .lang-switch-track,
      html.sb-hover-open .lang-switch-track{
        display:flex;
        opacity:1;
        transform:scale(1);
        pointer-events:auto;
      }
    }
    @media (max-width:1023px){
      .lang-switch-icon{display:none !important}
      .lang-switch-track{
        display:flex !important;
        opacity:1 !important;
        transform:none !important;
        pointer-events:auto !important;
        max-width:140px;
      }
    }
    .side-nav a{
      text-decoration:none;
      display:flex;align-items:center;gap:10px;
      padding:10px 10px;border-radius:10px;
      color:var(--nav-text);font-weight:700;font-size:.9rem;line-height:1.2;
      border:1px solid transparent;border-style:solid;
      margin:0;
    }
    .side-nav a svg{
      width:20px !important;height:20px !important;
      min-width:20px;min-height:20px;
      color:#9fb6e8;flex-shrink:0;display:block;
      stroke-width:1.5;
    }
    .side-nav a:hover{background:rgba(148,163,184,.14);border-color:rgba(191,219,254,.22)}
    .side-nav a.active{
      background:linear-gradient(135deg,rgba(219,234,254,.95),rgba(224,231,255,.9));
      border-color:rgba(191,219,254,.85);color:#0f2a55;
      box-shadow:inset 3px 0 0 #60a5fa, 0 12px 28px rgba(15,23,42,.2);
      font-weight:800;
    }
    .side-nav a.active svg{color:#1d4ed8}
    .side-nav .nav-label{white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
    .side-nav a.active .nav-label{font-weight:800}
    .sidebar-foot{
      margin-top:auto;
      padding:0;
    }
    .logout-link{
      text-decoration:none;
      display:flex;align-items:center;gap:10px;
      width:100%;
      padding:11px 12px;border-radius:12px;
      color:#fecaca;font-weight:700;font-size:.9rem;line-height:1.2;
      border:1px solid rgba(248,113,113,.28);
      background:rgba(127,29,29,.22);
      box-sizing:border-box;
    }
    .logout-link svg{
      width:20px;height:20px;min-width:20px;min-height:20px;
      color:#fca5a5;display:block;stroke-width:1.6;
    }
    .logout-link:hover{
      background:rgba(127,29,29,.28);
      border-color:rgba(248,113,113,.36);
    }
    .content{
      flex:1;min-width:0;width:100%;
      padding:clamp(18px,3vw,28px) clamp(20px,4vw,48px) clamp(22px,3vw,36px);
      background:linear-gradient(180deg,rgba(248,250,252,.42),rgba(248,250,252,.2));
    }
    .shell{max-width:720px;margin:0 auto;width:100%;min-width:0}
    .shell.wide{max-width:min(90rem,100%);margin:0 auto}
    .top{
      display:flex;align-items:flex-start;justify-content:space-between;gap:12px;margin-bottom:12px;
      padding:16px 18px;border-radius:14px;border:1px solid var(--line);background:var(--surface);
      box-shadow:0 1px 0 rgba(255,255,255,.85) inset, 0 20px 50px rgba(8,112,184,.07);
    }
    .eyebrow{font-size:.72rem;letter-spacing:.14em;text-transform:uppercase;color:#6b85ab;font-weight:800}
    .page-title{margin:6px 0 0;font-size:clamp(1.35rem,2.6vw,1.85rem);font-weight:900;letter-spacing:-.02em}
    .nav-row{display:flex;flex-wrap:wrap;gap:8px;align-items:center}
    .link{
      text-decoration:none;font-size:.84rem;padding:8px 12px;border-radius:10px;
      background:#ecf3ff;border:1px solid #c9dafc;color:#1f4aa0;font-weight:700;
    }
    .link:hover{background:#e2ecff}
    .flash{padding:10px 12px;border-radius:10px;margin-bottom:12px;font-size:.9rem;font-weight:600}
    .flash.ok{background:#dcfce7;border:1px solid #86efac;color:var(--ok)}
    .flash.err{background:#ffe4e6;border:1px solid #fda4af;color:var(--danger)}
    .panel{
      border:1px solid var(--line);border-radius:14px;background:var(--surface);
      padding:16px 18px 18px;
      box-shadow:0 1px 0 rgba(255,255,255,.85) inset, 0 20px 50px rgba(8,112,184,.07);
    }
    .panel h2{margin:0 0 4px;font-size:1.12rem;font-weight:900;letter-spacing:-.02em}
    .panel-desc{margin:0 0 14px;font-size:.84rem;color:var(--muted);line-height:1.45}
    .label{display:block;font-size:.78rem;color:#5a78b0;margin-bottom:5px;font-weight:700}
    .field{
      width:100%;padding:9px 11px;border-radius:10px;border:1px solid #c8daf8;background:rgba(251,252,255,.95);color:#173456;font:inherit;
    }
    .field:focus{outline:none;border-color:#6ca0ff;box-shadow:0 0 0 2px rgba(96,141,229,.2)}
    input[type="file"].field{padding:7px}
    .hint{font-size:.76rem;color:var(--muted);margin-top:4px;line-height:1.4}
    .btn{
      display:inline-flex;align-items:center;justify-content:center;gap:6px;
      padding:9px 14px;border-radius:10px;font-weight:700;font-size:.9rem;cursor:pointer;border:1px solid transparent;font:inherit;
    }
    .btn-primary{width:100%;background:#2563eb;color:#fff;border-color:#1d4ed8;box-shadow:0 12px 28px rgba(37,99,235,.22)}
    .btn-primary:hover{background:#1d4ed8}
    .btn-secondary{background:#f1f5ff;border-color:#c9dafc;color:#1e3a5f}
    .btn-secondary:hover{background:#e8efff}
    .btn-add{
      background:#2563eb;color:#fff;border-color:#1d4ed8;
      padding:9px 12px;border-radius:10px;font-weight:800;
      display:inline-flex;align-items:center;gap:8px;text-decoration:none;
      box-shadow:0 20px 50px rgba(37,99,235,.2);
    }
    .btn-add:hover{background:#1d4ed8}
    .btn-add svg{width:16px;height:16px}
    .btn-danger{background:#e11d48;color:#fff;border-color:#be123c}
    .btn-danger:hover{background:#be123c}
    .btn-ghost{background:transparent;border-color:#cfe0ff;color:#1f4aa0}
    .btn-ghost:hover{background:#f5f9ff}
    .row-actions{display:flex;gap:8px;flex-wrap:wrap;align-items:center}
    .preview-wrap{margin:8px 0 10px}
    .preview-img{width:88px;height:88px;border-radius:14px;object-fit:cover;border:1px solid var(--line);background:#f1f5ff}
    .preview-fallback{
      width:88px;height:88px;border-radius:14px;display:flex;align-items:center;justify-content:center;
      background:#dbeafe;color:#1d4ed8;font-weight:900;font-size:1.5rem;border:1px solid var(--line);
    }
    .list-head{display:flex;align-items:flex-start;justify-content:space-between;gap:10px;margin-bottom:10px}
    .count{font-size:.8rem;color:var(--muted);font-weight:600}
    .list{
      list-style:none;margin:0;padding:0;
      max-height:min(70vh,720px);
      overflow-y:auto;
      overflow-x:hidden;
      -webkit-overflow-scrolling:touch;
      overscroll-behavior:contain;
    }
    /* Premium data table */
    .pm-table-wrap{
      border-radius:14px;border:1px solid var(--line);
      background:var(--surface);
      box-shadow:0 1px 0 rgba(255,255,255,.85) inset, 0 20px 50px rgba(8,112,184,.06);
      overflow:hidden;
      -webkit-overflow-scrolling:touch;
      --pm-sbw: 0px;
    }
    .pm-table-scroll{
      width:100%;
      max-width:100%;
      max-height:min(72vh,720px);
      overflow:auto;
      -webkit-overflow-scrolling:touch;
      overscroll-behavior:contain;
    }
    .pm-table-wrap--flush{
      border:none;border-radius:0;box-shadow:none;background:transparent;
    }
    /* Employee detail — absence log: reserve width for View Document button */
    .pm-table-wrap--absence-log .pm-table--absence-log{
      min-width:0;
      width:100%;
    }
    /* Spread columns across full table width; proofs sized for one-line button only */
    .pm-table-wrap--absence-log col.pm-col-date{ width:16%; }
    .pm-table-wrap--absence-log col.pm-col-status{ width:17%; }
    .pm-table-wrap--absence-log col.pm-col-note{ width:48%; }
    .pm-table-wrap--absence-log col.pm-col-proofs{ width:19%; }
    .pm-table-wrap--absence-log .pm-col-note-cell{
      overflow-wrap:anywhere;
      word-break:break-word;
    }
    .pm-table-wrap--absence-log .pm-col-proofs-head,
    .pm-table-wrap--absence-log .pm-col-proofs-cell{
      text-align:center;
      white-space:nowrap;
    }
    .pm-table-wrap--absence-log .pm-col-proofs-cell{
      padding-left:8px;
      padding-right:10px;
    }
    .pm-table-wrap--absence-log .pm-proof-btn{
      margin-left:auto;
      margin-right:auto;
    }
    /* Employee directory */
    .pm-table-wrap--employee-directory{
      overflow:hidden;
      border-radius:14px;
      border:1px solid var(--line);
      background:var(--surface);
      box-shadow:0 1px 0 rgba(255,255,255,.85) inset, 0 20px 50px rgba(8,112,184,.06);
    }
    .pm-table-x-sync{
      width:100%;
    }
    .pm-table-wrap--employee-directory .pm-table--employee-directory{
      width:100%;
      min-width:0;
      table-layout:fixed;
    }
    .pm-table-wrap--employee-directory col.pm-col-employee{width:34%}
    .pm-table-wrap--employee-directory col.pm-col-status{width:14%}
    .pm-table-wrap--employee-directory col.pm-col-days{width:10%}
    .pm-table-wrap--employee-directory col.pm-col-last{width:18%}
    .pm-table-wrap--employee-directory col.pm-col-actions{width:24%}
    .pm-table-wrap--employee-directory .pm-table-head{
      overflow:hidden;
      border-bottom:1px solid rgba(208,224,251,.9);
      background:rgba(248,250,252,.98);
      padding-right:var(--pm-sbw);
    }
    .pm-table-wrap--employee-directory .pm-table-head .pm-table thead th{
      background:rgba(248,250,252,.98);
    }
    .pm-table-wrap--employee-directory .pm-table-body{
      max-height:min(72vh,720px);
      overflow-y:auto;
      overflow-x:hidden;
      scrollbar-gutter:stable;
    }
    @media (max-width:1023px){
      .pm-table-wrap--employee-directory .pm-table-x-sync{
        overflow-x:auto;
        -webkit-overflow-scrolling:touch;
      }
      .pm-table-wrap--employee-directory .pm-table--employee-directory{
        min-width:52rem;
        width:max-content;
        table-layout:auto;
      }
      .pm-table-wrap--employee-directory .pm-table-head{
        padding-right:0;
      }
      .pm-table-wrap--employee-directory .pm-table-body{
        overflow-x:visible;
        scrollbar-gutter:auto;
      }
    }
    .pm-table{
      width:100%;border-collapse:collapse;min-width:640px;
      table-layout:fixed;
      font-size:.875rem;font-feature-settings:"cv02","cv03","cv04","ss01";
    }
    .pm-table-head{
      overflow:hidden;
      border-bottom:1px solid rgba(208,224,251,.9);
      background:rgba(248,250,252,.9);
      padding-right: var(--pm-sbw);
    }
    .pm-table-body{
      overflow-y:auto;
      overflow-x:hidden;
      -webkit-overflow-scrolling:touch;
    }
    .pm-table thead th{
      text-align:left;font-size:.68rem;letter-spacing:.1em;text-transform:uppercase;
      color:#6b85ab;font-weight:800;padding:12px 16px;border-bottom:none;
      background:transparent;position:static;
      box-shadow:none;
    }
    .pm-table thead th.pm-col-actions,
    .pm-table tbody td.pm-col-actions{
      text-align:right;
    }
    .pm-table tbody tr{
      border-bottom:1px solid rgba(226,235,251,.85);
      transition:background .15s ease;
    }
    .pm-table tbody tr.pm-row{
      content-visibility:auto;
      contain-intrinsic-size:100px;
    }
    .pm-table tbody tr:hover{background:rgba(239,246,255,.55)}
    .pm-table tbody td{padding:14px 16px;vertical-align:middle;color:#173456}
    .pm-table .pm-name{font-weight:700;letter-spacing:-.01em}
    .pm-status-dot{
      display:inline-flex;align-items:center;gap:.5rem;
      font-size:inherit;line-height:1;
    }
    .pm-status-dot::before{
      content:"";width:8px;height:8px;border-radius:999px;flex-shrink:0;
      background:#94a3b8;
      box-shadow:0 0 0 1px rgba(255,255,255,.6);
    }
    .pm-status-dot--in::before{
      background:#22c55e;
      box-shadow:0 0 0 1px rgba(34,197,94,.35), 0 0 14px rgba(34,197,94,.45);
      animation:none;
    }
    .pm-status-dot--out::before{background:#cbd5e1}
    @keyframes pm-row-pulse{
      0%,100%{opacity:1;transform:scale(1)}
      50%{opacity:.75;transform:scale(.92)}
    }
    @media (prefers-reduced-motion: reduce){
      .sidebar,
      .side-nav,
      .side-nav a,
      .logout-link,
      .flash{
        transition:none !important;
      }
      .pm-status-dot--in::before{
        animation:none !important;
      }
    }
    @media (max-width:1023px){
      :root{
        --admin-mobile-bar-h:3.25rem;
      }
      .admin-mobile-bar{
        display:flex;
        align-items:center;
        gap:10px;
        position:fixed;
        top:0;
        left:0;
        right:0;
        z-index:60;
        width:100%;
        padding:max(10px, env(safe-area-inset-top)) 12px 10px;
        background:linear-gradient(180deg,var(--nav-bg-1) 0%,var(--nav-bg-2) 100%);
        border-bottom:1px solid rgba(125,211,252,.35);
        box-shadow:0 8px 24px rgba(8,23,53,.28);
      }
      .admin-mobile-bar__title{
        font-weight:900;
        font-size:.95rem;
        color:#e2e8f0;
        white-space:nowrap;
        overflow:hidden;
        text-overflow:ellipsis;
        min-width:0;
        flex:1;
      }
      .admin-mobile-bar .toggle{display:inline-flex !important}
      .sidebar-backdrop{
        display:block;
        position:fixed;
        inset:0;
        z-index:58;
        border:0;
        padding:0;
        margin:0;
        background:rgba(8,23,53,.55);
        backdrop-filter:blur(2px);
        -webkit-backdrop-filter:blur(2px);
        opacity:0;
        pointer-events:none;
        transition:opacity .28s ease;
        cursor:pointer;
      }
      html.sb-mobile-nav-open .sidebar-backdrop{
        opacity:1;
        pointer-events:auto;
      }
      .layout{
        flex-direction:column;
        min-height:100vh;
        min-height:100dvh;
      }
      .sidebar{
        position:fixed;
        left:0;
        right:0;
        top:calc(max(10px, env(safe-area-inset-top)) + var(--admin-mobile-bar-h) + 10px);
        width:auto;
        max-width:100%;
        max-height:0;
        overflow:hidden;
        z-index:59;
        margin:0 10px;
        padding:0;
        border:1px solid transparent;
        border-radius:0 0 14px 14px;
        box-shadow:none;
        background:linear-gradient(180deg,var(--nav-bg-1) 0%,var(--nav-bg-2) 100%);
        opacity:0;
        transform:translateY(-6px);
        pointer-events:none;
        transition:
          max-height .32s cubic-bezier(.4,0,.2,1),
          opacity .22s ease,
          transform .28s cubic-bezier(.4,0,.2,1),
          box-shadow .28s ease,
          border-color .2s ease;
      }
      html.sb-mobile-nav-open .sidebar{
        max-height:min(calc(100dvh - max(10px, env(safe-area-inset-top)) - var(--admin-mobile-bar-h) - 28px), 72vh);
        opacity:1;
        transform:translateY(0);
        pointer-events:auto;
        border-color:rgba(125,211,252,.35);
        box-shadow:0 20px 48px rgba(8,23,53,.42);
        overflow-y:auto;
        -webkit-overflow-scrolling:touch;
        overscroll-behavior:contain;
      }
      html.sb-mobile-nav-open{
        overflow:hidden;
      }
      .sidebar-head{display:none}
      .side-nav{
        margin:0;
        padding:10px 12px;
        max-height:none;
        opacity:1;
        overflow:visible;
        pointer-events:auto;
      }
      .side-nav a{justify-content:flex-start !important;padding:10px 10px !important;gap:10px !important}
      .sidebar-foot{
        display:block;
        padding:0 12px 12px;
      }
      .sidebar-foot .nav-label{display:inline !important}
      .side-nav .nav-label{display:inline !important}
      .content{
        width:100%;
        flex:1 1 auto;
        min-width:0;
        padding-top:calc(max(10px, env(safe-area-inset-top)) + var(--admin-mobile-bar-h) + 22px);
        padding-right:clamp(12px, 4vw, 20px);
        padding-bottom:max(18px, env(safe-area-inset-bottom));
        padding-left:clamp(12px, 4vw, 20px);
      }
    }
  </style>
