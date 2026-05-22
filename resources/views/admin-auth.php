<?php
$redirect = $redirect ?? '/admin/employees';
?>
<!doctype html>
<html lang="<?php echo htmlspecialchars(app_lang_attr()); ?>">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title><?php echo htmlspecialchars(__('sign_in')); ?> — <?php echo htmlspecialchars(__('app_name')); ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
  <?php include __DIR__ . '/partials/pm-design-snippet.php'; ?>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    @keyframes pm-mesh-a {
      0%, 100% { transform: translate(0, 0) scale(1); opacity: 0.55; }
      33% { transform: translate(4%, -3%) scale(1.08); opacity: 0.7; }
      66% { transform: translate(-3%, 4%) scale(0.95); opacity: 0.5; }
    }
    @keyframes pm-mesh-b {
      0%, 100% { transform: translate(0, 0) scale(1); opacity: 0.45; }
      50% { transform: translate(-5%, 5%) scale(1.12); opacity: 0.65; }
    }
    @keyframes pm-mesh-c {
      0%, 100% { transform: translate(0, 0) scale(1.05); opacity: 0.4; }
      40% { transform: translate(6%, 3%) scale(0.92); opacity: 0.58; }
      80% { transform: translate(-4%, -5%) scale(1.1); opacity: 0.48; }
    }
    .pm-mesh-blob-a { animation: pm-mesh-a 18s ease-in-out infinite; }
    .pm-mesh-blob-b { animation: pm-mesh-b 22s ease-in-out infinite; }
    .pm-mesh-blob-c { animation: pm-mesh-c 26s ease-in-out infinite; }
  </style>
</head>
<body class="relative min-h-screen overflow-x-hidden font-sans text-slate-900 antialiased">
  <!-- Animated mesh atmosphere -->
  <div class="pointer-events-none fixed inset-0 -z-10 bg-slate-950">
    <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900"></div>
    <div class="pm-mesh-blob-a absolute -left-[20%] top-[10%] h-[min(70vw,520px)] w-[min(70vw,520px)] rounded-full bg-blue-500/35 blur-[100px]"></div>
    <div class="pm-mesh-blob-b absolute -right-[15%] top-[35%] h-[min(65vw,480px)] w-[min(65vw,480px)] rounded-full bg-violet-500/30 blur-[90px]"></div>
    <div class="pm-mesh-blob-c absolute bottom-[5%] left-[20%] h-[min(55vw,420px)] w-[min(55vw,420px)] rounded-full bg-cyan-400/25 blur-[85px]"></div>
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_80%_50%_at_50%_-20%,rgba(120,180,255,0.12),transparent)]"></div>
  </div>

  <div class="flex min-h-screen w-full flex-col items-center justify-center px-4 py-10 sm:px-6">
    <div class="w-full max-w-md">
      <div class="rounded-[2rem] border border-white/50 bg-white/90 p-8 shadow-2xl backdrop-blur-2xl sm:p-10">
        <!-- Admin icon -->
        <div class="flex justify-center">
          <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-50 ring-1 ring-blue-100/80 shadow-[inset_0_1px_0_rgba(255,255,255,0.9),0_4px_14px_rgba(37,99,235,0.12)]">
            <svg class="h-7 w-7 text-blue-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
              <path d="m9 12 2 2 4-4"/>
            </svg>
          </div>
        </div>

        <h1 class="mt-6 text-center text-2xl font-extrabold tracking-tight text-slate-900 sm:text-3xl"><?php echo htmlspecialchars(__('sign_in')); ?></h1>
        <p class="mt-2 text-center text-sm font-medium leading-relaxed text-slate-600"><?php echo htmlspecialchars(__('sign_in_subtitle')); ?></p>

        <?php if (!empty($_GET['success'])): ?>
          <div class="mt-5 rounded-xl border border-emerald-200/80 bg-emerald-50/90 px-4 py-3 text-center text-sm font-medium text-emerald-900"><?php echo htmlspecialchars($_GET['success']); ?></div>
        <?php endif; ?>
        <?php
          $flashError = flash_pull('flash_error');
          $loginError = $flashError ?? ($_GET['error'] ?? null);
        ?>
        <?php if (!empty($loginError)): ?>
          <div class="mt-5 rounded-xl border border-rose-200/80 bg-rose-50/90 px-4 py-3 text-center text-sm font-medium text-rose-900" role="alert"><?php echo htmlspecialchars((string) $loginError); ?></div>
        <?php endif; ?>

        <form class="mt-8 space-y-5" method="POST" action="/admin/login">
          <input type="hidden" name="redirect" value="<?php echo htmlspecialchars($redirect); ?>">
          <div>
            <label class="mb-2 block text-center text-[0.68rem] font-extrabold uppercase tracking-[0.18em] text-slate-900" for="adminPassword"><?php echo htmlspecialchars(__('password')); ?></label>
            <input
              id="adminPassword"
              class="h-14 w-full rounded-xl border border-slate-200 bg-white px-4 text-base font-semibold text-slate-900 shadow-[inset_0_1px_2px_rgba(15,23,42,0.06)] outline-none ring-0 transition duration-300 placeholder:text-slate-400 focus:border-blue-500 focus:shadow-[inset_0_1px_2px_rgba(15,23,42,0.04),0_0_0_4px_rgba(59,130,246,0.2),0_0_28px_rgba(59,130,246,0.12)] focus:ring-2 focus:ring-blue-500/40"
              type="password"
              name="password"
              placeholder="••••••••"
              required
              autofocus
              autocomplete="current-password"
            >
          </div>
          <button
            class="group w-full rounded-xl bg-gradient-to-r from-blue-600 to-indigo-700 py-3.5 text-sm font-extrabold tracking-wide text-white shadow-[0_12px_36px_rgba(37,99,235,0.5),inset_0_1px_0_0_rgba(255,255,255,0.22),0_0_56px_-8px_rgba(59,130,246,0.35)] transition duration-300 hover:-translate-y-0.5 hover:from-blue-500 hover:to-indigo-600 hover:shadow-[0_18px_48px_rgba(49,46,129,0.42),inset_0_1px_0_0_rgba(255,255,255,0.25),0_0_72px_-6px_rgba(59,130,246,0.3)] active:translate-y-0"
            type="submit"
          >
            <?php echo htmlspecialchars(__('enter_admin')); ?>
          </button>
        </form>

        <div class="mt-8 flex justify-center">
          <a
            class="inline-flex items-center gap-2 rounded-full px-3 py-2 text-sm font-medium text-slate-500 transition hover:bg-white/40 hover:text-slate-700"
            href="/"
          >
            <svg class="h-4 w-4 shrink-0 opacity-70" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <path d="m15 18-6-6 6-6"/>
            </svg>
            <?php echo htmlspecialchars(__('back_home')); ?>
          </a>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
