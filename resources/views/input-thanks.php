<!doctype html>
<html lang="<?php echo htmlspecialchars(app_lang_attr()); ?>">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title><?php echo htmlspecialchars(__('thanks_title')); ?> — <?php echo htmlspecialchars(__('app_name')); ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <?php include __DIR__ . '/partials/pm-design-snippet.php'; ?>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="pm-app pm-mesh-bg min-h-screen font-sans antialiased">
  <main class="flex min-h-screen items-center justify-center px-4 py-6 sm:px-8 sm:py-12">
    <div class="relative z-10 w-full max-w-md rounded-2xl border border-white/50 bg-white/80 p-10 text-center shadow-[0_24px_60px_rgba(8,112,184,0.15)] backdrop-blur-xl">
      <div class="text-4xl" aria-hidden="true">✅</div>
      <h1 class="mt-4 text-2xl font-extrabold tracking-tight text-slate-900"><?php echo htmlspecialchars(__('thanks_title')); ?></h1>
      <p class="mt-2 text-sm leading-relaxed text-slate-600"><?php echo htmlspecialchars(__('thanks_body')); ?></p>
      <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:justify-center">
        <a class="inline-flex justify-center rounded-xl bg-gradient-to-br from-blue-500 to-blue-700 px-5 py-3 text-sm font-extrabold text-white shadow-[0_20px_50px_rgba(8,112,184,0.4)] transition duration-300 hover:shadow-[0_24px_60px_rgba(8,112,184,0.48)]" href="/input-form"><?php echo htmlspecialchars(__('submit_another')); ?></a>
        <a class="inline-flex justify-center rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-800 transition duration-300 hover:bg-slate-50" href="/"><?php echo htmlspecialchars(__('home')); ?></a>
      </div>
    </div>
  </main>
</body>
</html>
