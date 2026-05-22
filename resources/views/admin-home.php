<?php
$viewsRoot = __DIR__;
$pageTitle = __('admin_home') . ' — ' . __('app_name');
$activeNav = 'home';
$shellClass = 'shell wide';
include $viewsRoot . '/partials/admin-chrome-open.php';
?>
<div class="tw-space-y-6 sm:tw-space-y-8">
  <header class="tw-relative tw-overflow-hidden tw-rounded-2xl tw-border tw-border-white/50 tw-bg-white/70 tw-px-5 tw-py-6 tw-backdrop-blur-xl tw-shadow-[0_20px_50px_rgba(8,112,184,0.12)] sm:tw-px-8 sm:tw-py-8">
    <div class="tw-pointer-events-none tw-absolute tw--right-16 tw--top-20 tw-h-56 tw-w-56 tw-rounded-full tw-bg-blue-400/20 tw-blur-3xl" aria-hidden="true"></div>
    <div class="tw-pointer-events-none tw-absolute tw--bottom-24 tw--left-12 tw-h-48 tw-w-48 tw-rounded-full tw-bg-violet-400/15 tw-blur-3xl" aria-hidden="true"></div>
    <div class="tw-relative">
      <p class="tw-m-0 tw-text-[0.72rem] tw-font-extrabold tw-uppercase tw-tracking-[0.16em] tw-text-slate-500"><?php echo htmlspecialchars(__('admin_home')); ?></p>
      <h1 class="tw-m-0 tw-mt-2 tw-text-3xl tw-font-black tw-tracking-tight tw-text-slate-900 sm:tw-text-4xl"><?php echo htmlspecialchars(__('welcome_back')); ?></h1>
      <p class="tw-m-0 tw-mt-2 tw-max-w-2xl tw-text-sm tw-leading-relaxed tw-text-slate-600 sm:tw-text-base"><?php echo htmlspecialchars(__('admin_home_lede')); ?></p>
    </div>
  </header>

  <div class="tw-grid tw-grid-cols-1 tw-gap-3 sm:tw-grid-cols-3">
    <article class="tw-group tw-relative tw-overflow-hidden tw-rounded-2xl tw-border tw-border-white/50 tw-bg-white/65 tw-px-4 tw-py-4 tw-backdrop-blur-xl tw-shadow-[0_16px_40px_rgba(8,112,184,0.08)] tw-transition tw-duration-300 hover:tw-border-blue-200/60 hover:tw-shadow-[0_20px_50px_rgba(8,112,184,0.14)] sm:tw-px-5 sm:tw-py-5">
      <p class="tw-m-0 tw-text-[0.68rem] tw-font-bold tw-uppercase tw-tracking-[0.12em] tw-text-slate-500"><?php echo htmlspecialchars(__('total_employees')); ?></p>
      <p class="tw-m-0 tw-mt-2 tw-text-3xl tw-font-black tw-tabular-nums tw-tracking-tight tw-text-slate-900 sm:tw-text-4xl"><?php echo (int) $totalEmployees; ?></p>
      <p class="tw-m-0 tw-mt-1 tw-text-xs tw-font-medium tw-text-slate-500"><?php echo htmlspecialchars(__('registered_directory')); ?></p>
    </article>
    <article class="tw-group tw-relative tw-overflow-hidden tw-rounded-2xl tw-border tw-border-emerald-200/40 tw-bg-gradient-to-br tw-from-emerald-50/90 tw-to-white/70 tw-px-4 tw-py-4 tw-backdrop-blur-xl tw-shadow-[0_16px_40px_rgba(16,185,129,0.12)] tw-transition tw-duration-300 hover:tw-shadow-[0_20px_50px_rgba(16,185,129,0.18)] sm:tw-px-5 sm:tw-py-5">
      <p class="tw-m-0 tw-text-[0.68rem] tw-font-bold tw-uppercase tw-tracking-[0.12em] tw-text-emerald-800/80"><?php echo htmlspecialchars(__('in_office_today')); ?></p>
      <p class="tw-m-0 tw-mt-2 tw-text-3xl tw-font-black tw-tabular-nums tw-tracking-tight tw-text-emerald-700 sm:tw-text-4xl"><?php echo (int) $employeesInToday; ?></p>
      <p class="tw-m-0 tw-mt-1 tw-text-xs tw-font-medium tw-text-emerald-900/60"><?php echo htmlspecialchars(__('latest_snapshot')); ?></p>
    </article>
    <article class="tw-group tw-relative tw-overflow-hidden tw-rounded-2xl tw-border tw-border-blue-200/45 tw-bg-gradient-to-br tw-from-blue-50/90 tw-to-white/70 tw-px-4 tw-py-4 tw-backdrop-blur-xl tw-shadow-[0_16px_40px_rgba(37,99,235,0.1)] tw-transition tw-duration-300 hover:tw-shadow-[0_20px_50px_rgba(37,99,235,0.16)] sm:tw-px-5 sm:tw-py-5">
      <p class="tw-m-0 tw-text-[0.68rem] tw-font-bold tw-uppercase tw-tracking-[0.12em] tw-text-blue-900/70"><?php echo htmlspecialchars(__('absence_7d')); ?></p>
      <p class="tw-m-0 tw-mt-2 tw-text-3xl tw-font-black tw-tabular-nums tw-tracking-tight tw-text-blue-700 sm:tw-text-4xl"><?php echo (int) $last7AbsenceTotal; ?></p>
      <p class="tw-m-0 tw-mt-1 tw-text-xs tw-font-medium tw-text-blue-900/55"><?php echo htmlspecialchars(__('rolling_window')); ?></p>
    </article>
  </div>

  <div class="tw-grid tw-grid-cols-1 tw-gap-4 md:tw-grid-cols-2 md:tw-gap-5">
    <a href="/admin/employees" class="tw-group tw-flex tw-flex-col tw-rounded-2xl tw-border tw-border-white/50 tw-bg-white/65 tw-p-5 tw-backdrop-blur-xl tw-shadow-[0_20px_50px_rgba(8,112,184,0.1)] tw-transition tw-duration-300 hover:tw--translate-y-0.5 hover:tw-border-blue-200/70 hover:tw-shadow-[0_24px_60px_rgba(8,112,184,0.16)] sm:tw-p-6">
      <div class="tw-mb-3 tw-flex tw-h-11 tw-w-11 tw-items-center tw-justify-center tw-rounded-xl tw-bg-blue-500/10 tw-text-blue-700 tw-ring-1 tw-ring-blue-500/20">
        <svg class="tw-h-5 tw-w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M20 8v6"/><path d="M23 11h-6"/></svg>
      </div>
      <h2 class="tw-m-0 tw-text-lg tw-font-extrabold tw-tracking-tight tw-text-slate-900 sm:tw-text-xl"><?php echo htmlspecialchars(__('employee_management')); ?></h2>
      <p class="tw-m-0 tw-mt-2 tw-flex-1 tw-text-sm tw-leading-relaxed tw-text-slate-600"><?php echo htmlspecialchars(__('employee_mgmt_desc')); ?></p>
      <span class="tw-mt-4 tw-inline-flex tw-items-center tw-text-sm tw-font-bold tw-text-blue-700 group-hover:tw-gap-1 tw-transition-all"><?php echo htmlspecialchars(__('open_directory')); ?> <span aria-hidden="true" class="tw-pl-0.5">→</span></span>
    </a>
    <a href="/admin/dashboard" class="tw-group tw-flex tw-flex-col tw-rounded-2xl tw-border tw-border-white/50 tw-bg-white/65 tw-p-5 tw-backdrop-blur-xl tw-shadow-[0_20px_50px_rgba(8,112,184,0.1)] tw-transition tw-duration-300 hover:tw--translate-y-0.5 hover:tw-border-violet-200/70 hover:tw-shadow-[0_24px_60px_rgba(139,92,246,0.14)] sm:tw-p-6">
      <div class="tw-mb-3 tw-flex tw-h-11 tw-w-11 tw-items-center tw-justify-center tw-rounded-xl tw-bg-violet-500/10 tw-text-violet-700 tw-ring-1 tw-ring-violet-500/20">
        <svg class="tw-h-5 tw-w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 19V5"/><path d="M4 19h16"/><path d="M8 17v-6"/><path d="M12 17V9"/><path d="M16 17v-3"/></svg>
      </div>
      <h2 class="tw-m-0 tw-text-lg tw-font-extrabold tw-tracking-tight tw-text-slate-900 sm:tw-text-xl"><?php echo htmlspecialchars(__('analytics')); ?></h2>
      <p class="tw-m-0 tw-mt-2 tw-flex-1 tw-text-sm tw-leading-relaxed tw-text-slate-600"><?php echo htmlspecialchars(__('analytics_desc')); ?></p>
      <span class="tw-mt-4 tw-inline-flex tw-items-center tw-text-sm tw-font-bold tw-text-violet-700 group-hover:tw-gap-1 tw-transition-all"><?php echo htmlspecialchars(__('open_insights')); ?> <span aria-hidden="true" class="tw-pl-0.5">→</span></span>
    </a>
  </div>
</div>
<?php include $viewsRoot . '/partials/admin-chrome-close.php'; ?>
