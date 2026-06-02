<?php
$viewsRoot = dirname(__DIR__);
$dir = $dir ?? [
  'rows' => [],
  'q' => '',
  'status' => 'all',
  'category' => 'all',
  'start_date' => date('Y-m-d'),
  'end_date' => date('Y-m-d'),
  'range_label' => '',
  'totalFiltered' => 0,
  'registeredTotal' => 0,
];
$pageTitle = __('employee_management_title') . ' — ' . __('app_name');
$activeNav = 'employees';
$shellClass = 'shell wide';
include $viewsRoot . '/partials/admin-chrome-open.php';
?>
    <?php if (!empty($_GET['success'])): ?>
      <div class="flash ok"><?php echo htmlspecialchars($_GET['success']); ?></div>
    <?php endif; ?>
    <?php if (!empty($_GET['error'])): ?>
      <div class="flash err"><?php echo htmlspecialchars($_GET['error']); ?></div>
    <?php endif; ?>

    <header class="tw-relative tw-mb-6 tw-flex tw-flex-col tw-gap-4 tw-overflow-hidden tw-rounded-2xl tw-border tw-border-white/50 tw-bg-white/70 tw-p-6 tw-backdrop-blur-xl tw-shadow-[0_20px_50px_rgba(8,112,184,0.12)] sm:tw-flex-row sm:tw-items-start sm:tw-justify-between">
      <div class="tw-space-y-2">
        <p class="tw-m-0 tw-text-[0.72rem] tw-font-bold tw-uppercase tw-tracking-[0.16em] tw-text-slate-500"><?php echo htmlspecialchars(__('employees')); ?></p>
        <h1 class="tw-m-0 tw-text-3xl tw-font-black tw-text-slate-900"><?php echo htmlspecialchars(__('employee_management_title')); ?></h1>
        <p class="tw-m-0 tw-max-w-2xl tw-text-sm tw-leading-relaxed tw-text-slate-600"><?php echo htmlspecialchars(__('employee_mgmt_page_lede')); ?></p>
      </div>
      <div class="tw-flex tw-flex-col tw-items-stretch tw-gap-2 sm:tw-items-end">
        <span class="tw-text-xs tw-font-semibold tw-text-slate-500"><?php echo (int) $dir['registeredTotal']; ?> <?php echo htmlspecialchars(__('registered')); ?></span>
        <div class="tw-flex tw-flex-col tw-gap-2 sm:tw-flex-row sm:tw-items-center">
          <button type="button" id="openBulkImport" class="tw-inline-flex tw-items-center tw-justify-center tw-gap-2 tw-rounded-xl tw-border tw-border-white/60 tw-bg-white/80 tw-px-4 tw-py-2.5 tw-text-sm tw-font-bold tw-text-slate-800 tw-shadow-[0_10px_24px_rgba(15,23,42,.08)] tw-backdrop-blur-md tw-transition hover:tw-border-blue-200 hover:tw-bg-white">
            <svg class="tw-h-4 tw-w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
              <polyline points="17 8 12 3 7 8"/>
              <line x1="12" x2="12" y1="3" y2="15"/>
            </svg>
            <?php echo htmlspecialchars(__('bulk_import')); ?>
          </button>
          <a class="tw-inline-flex tw-items-center tw-justify-center tw-gap-2 tw-rounded-xl tw-bg-blue-600 tw-px-4 tw-py-2.5 tw-text-sm tw-font-bold tw-text-white tw-shadow-[0_10px_24px_rgba(37,99,235,.25)] tw-transition hover:tw-bg-blue-700" href="/admin-panel/employees/new">
            <svg class="tw-h-4 tw-w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <path d="M12 5v14"/><path d="M5 12h14"/>
            </svg>
            <?php echo htmlspecialchars(__('add_employee')); ?>
          </a>
        </div>
      </div>
    </header>

    <form id="employeeFilterForm" class="tw-mb-6" method="get" action="/admin/employees" autocomplete="off">
      <div class="tw-grid tw-gap-4 lg:tw-grid-cols-12">
        <section class="tw-rounded-2xl tw-border tw-border-white/50 tw-bg-white/68 tw-p-5 tw-shadow-[0_20px_50px_rgba(8,112,184,0.1)] tw-backdrop-blur-xl lg:tw-col-span-7">
          <p class="tw-m-0 tw-text-xs tw-font-semibold tw-uppercase tw-tracking-widest tw-text-slate-500"><?php echo htmlspecialchars(__('date_window')); ?></p>
          <p class="tw-m-0 tw-mt-1 tw-text-sm tw-text-slate-600"><?php echo htmlspecialchars(__('range')); ?>: <span class="tw-font-semibold tw-text-slate-900"><?php echo htmlspecialchars($dir['range_label'] ?? ''); ?></span></p>
          <div class="tw-mt-4 tw-flex tw-flex-wrap tw-items-center tw-gap-2">
            <label class="tw-sr-only" for="empStart"><?php echo htmlspecialchars(__('start_date')); ?></label>
            <input class="emp-auto tw-min-w-0 tw-flex-1 tw-rounded-xl tw-border tw-border-slate-200 tw-bg-white tw-px-3 tw-py-2.5 tw-text-sm tw-text-slate-900 tw-outline-none tw-transition tw-duration-300 focus:tw-border-blue-400 focus:tw-ring-2 focus:tw-ring-blue-100" type="date" name="start_date" id="empStart" value="<?php echo htmlspecialchars($dir['start_date'] ?? ''); ?>">
            <span class="tw-text-slate-400">—</span>
            <label class="tw-sr-only" for="empEnd"><?php echo htmlspecialchars(__('end_date')); ?></label>
            <input class="emp-auto tw-min-w-0 tw-flex-1 tw-rounded-xl tw-border tw-border-slate-200 tw-bg-white tw-px-3 tw-py-2.5 tw-text-sm tw-text-slate-900 tw-outline-none tw-transition tw-duration-300 focus:tw-border-blue-400 focus:tw-ring-2 focus:tw-ring-blue-100" type="date" name="end_date" id="empEnd" value="<?php echo htmlspecialchars($dir['end_date'] ?? ''); ?>">
          </div>
          <div class="tw-mt-3 tw-flex tw-flex-wrap tw-gap-2">
            <button type="button" class="emp-preset tw-rounded-full tw-border tw-border-slate-200 tw-bg-white tw-px-3 tw-py-1.5 tw-text-xs tw-font-semibold tw-text-slate-700 tw-shadow-sm tw-transition tw-duration-300 hover:tw-border-blue-300 hover:tw-bg-blue-50" data-preset="today"><?php echo htmlspecialchars(__('today')); ?></button>
            <button type="button" class="emp-preset tw-rounded-full tw-border tw-border-slate-200 tw-bg-white tw-px-3 tw-py-1.5 tw-text-xs tw-font-semibold tw-text-slate-700 tw-shadow-sm tw-transition tw-duration-300 hover:tw-border-blue-300 hover:tw-bg-blue-50" data-preset="week"><?php echo htmlspecialchars(__('this_week')); ?></button>
            <button type="button" class="emp-preset tw-rounded-full tw-border tw-border-slate-200 tw-bg-white tw-px-3 tw-py-1.5 tw-text-xs tw-font-semibold tw-text-slate-700 tw-shadow-sm tw-transition tw-duration-300 hover:tw-border-blue-300 hover:tw-bg-blue-50" data-preset="month"><?php echo htmlspecialchars(__('this_month')); ?></button>
          </div>
        </section>
        <section class="tw-rounded-2xl tw-border tw-border-white/50 tw-bg-white/68 tw-p-5 tw-shadow-[0_20px_50px_rgba(8,112,184,0.1)] tw-backdrop-blur-xl lg:tw-col-span-5">
          <div class="tw-flex tw-items-start tw-justify-between tw-gap-2">
            <div>
              <p class="tw-m-0 tw-text-xs tw-font-semibold tw-uppercase tw-tracking-widest tw-text-slate-500"><?php echo htmlspecialchars(__('lens')); ?></p>
              <p id="employeeMatchCount" class="tw-m-0 tw-mt-1 tw-text-sm tw-font-semibold tw-text-slate-800"><?php echo htmlspecialchars(((int) $dir['totalFiltered'] === 1) ? __('match_one', ['n' => (int) $dir['totalFiltered']]) : __('match_many', ['n' => (int) $dir['totalFiltered']])); ?></p>
            </div>
          </div>
          <label class="tw-mt-3 tw-block tw-text-xs tw-font-semibold tw-text-slate-600" for="empQ"><?php echo htmlspecialchars(__('search')); ?></label>
          <div class="tw-relative tw-mt-1">
            <span class="tw-pointer-events-none tw-absolute tw-left-3 tw-top-1/2 tw--translate-y-1/2 tw-text-slate-400" aria-hidden="true">
              <svg class="tw-h-4 tw-w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.3-4.3"/></svg>
            </span>
            <input class="emp-search tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-white tw-py-2.5 tw-pl-10 tw-pr-3 tw-text-sm tw-text-slate-900 tw-outline-none tw-transition tw-duration-300 focus:tw-border-blue-400 focus:tw-ring-2 focus:tw-ring-blue-100" type="search" name="q" id="empQ" value="<?php echo htmlspecialchars($dir['q'] ?? ''); ?>" placeholder="<?php echo htmlspecialchars(__('search_placeholder')); ?>" autocomplete="off" spellcheck="false">
          </div>
          <div class="tw-mt-3 tw-flex tw-flex-col tw-gap-3 sm:tw-flex-row sm:tw-items-end sm:tw-gap-4">
            <div class="tw-w-full sm:tw-flex-1">
              <label class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600" for="empCategory"><?php echo htmlspecialchars(__('category_asn')); ?></label>
              <select class="emp-auto tw-mt-1 tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-white tw-px-3 tw-py-2.5 tw-text-sm tw-font-semibold tw-text-slate-800 tw-outline-none tw-transition tw-duration-300 focus:tw-border-blue-400 focus:tw-ring-2 focus:tw-ring-blue-100" name="category" id="empCategory">
                <?php
                  $cat = (string) ($dir['category'] ?? 'all');
                  $catOpts = [
                    'all' => __('all'),
                    'PNS' => 'PNS',
                    'PPPK' => 'PPPK',
                    'PPPK PW' => 'PPPK PW',
                  ];
                  foreach ($catOpts as $k => $lab) {
                    $sel = $cat === $k ? ' selected' : '';
                    echo '<option value="' . htmlspecialchars($k) . '"' . $sel . '>' . htmlspecialchars($lab) . '</option>';
                  }
                ?>
              </select>
            </div>
            <div class="tw-w-full sm:tw-flex-1">
              <label class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600" for="empStatus"><?php echo htmlspecialchars(__('status')); ?></label>
              <select class="emp-auto tw-mt-1 tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-white tw-px-3 tw-py-2.5 tw-text-sm tw-font-semibold tw-text-slate-800 tw-outline-none tw-transition tw-duration-300 focus:tw-border-blue-400 focus:tw-ring-2 focus:tw-ring-blue-100" name="status" id="empStatus">
                <?php
                  $st = (string) ($dir['status'] ?? 'all');
                  $opts = [
                    'all' => __('all'),
                    'in_office' => __('status.in_office'),
                    'wfh' => 'WFH',
                    'sakit' => 'Sakit',
                    'cuti_tahunan' => 'Cuti Tahunan',
                    'dinas' => 'Dinas Luar',
                    'izin' => __('status.izin'),
                  ];
                  foreach ($opts as $k => $lab) {
                    $sel = $st === $k ? ' selected' : '';
                    echo '<option value="' . htmlspecialchars($k) . '"' . $sel . '>' . htmlspecialchars($lab) . '</option>';
                  }
                ?>
              </select>
            </div>
          </div>
        </section>
      </div>
    </form>

    <section class="tw-max-w-full tw-rounded-2xl tw-border tw-border-white/50 tw-bg-white/68 tw-p-1 tw-shadow-[0_20px_50px_rgba(8,112,184,0.1)] tw-backdrop-blur-xl sm:tw-p-0">
        <?php if ((int) $dir['registeredTotal'] === 0): ?>
          <div class="tw-flex tw-flex-col tw-items-center tw-justify-center tw-gap-3 tw-px-6 tw-py-14 tw-text-center">
            <p class="tw-m-0 tw-text-base tw-font-semibold tw-text-slate-900"><?php echo htmlspecialchars(__('no_employees')); ?></p>
            <p class="tw-m-0 tw-max-w-md tw-text-sm tw-text-slate-600"><?php echo htmlspecialchars(__('no_employees_hint')); ?></p>
            <a class="tw-inline-flex tw-items-center tw-justify-center tw-rounded-xl tw-bg-blue-600 tw-px-4 tw-py-2.5 tw-text-sm tw-font-bold tw-text-white tw-shadow-[0_20px_50px_rgba(8,112,184,0.35)] tw-transition hover:tw-bg-blue-700" href="/admin-panel/employees/new"><?php echo htmlspecialchars(__('add_employee')); ?></a>
          </div>
        <?php elseif ((int) $dir['totalFiltered'] === 0): ?>
          <div class="tw-flex tw-flex-col tw-items-center tw-justify-center tw-gap-3 tw-px-6 tw-py-14 tw-text-center">
            <p class="tw-m-0 tw-text-base tw-font-semibold tw-text-slate-900"><?php echo htmlspecialchars(__('no_match_view')); ?></p>
            <p class="tw-m-0 tw-max-w-md tw-text-sm tw-text-slate-600"><?php echo htmlspecialchars(__('no_match_view_hint')); ?></p>
            <a class="tw-inline-flex tw-items-center tw-justify-center tw-rounded-xl tw-border tw-border-slate-200 tw-bg-white tw-px-4 tw-py-2.5 tw-text-sm tw-font-semibold tw-text-slate-900 tw-transition hover:tw-bg-slate-100" href="/admin/employees"><?php echo htmlspecialchars(__('clear_filters')); ?></a>
          </div>
        <?php else: ?>
          <div class="pm-table-wrap pm-table-wrap--employee-directory">
            <div class="pm-table-x-sync scrollbar-thin">
              <div class="pm-table-head" aria-hidden="true">
                <table class="pm-table pm-table--employee-directory">
                  <colgroup>
                    <col class="pm-col-employee">
                    <col class="pm-col-status">
                    <col class="pm-col-days">
                    <col class="pm-col-last">
                    <col class="pm-col-actions">
                  </colgroup>
                  <thead>
                    <tr>
                      <th><?php echo htmlspecialchars(__('employee')); ?></th>
                      <th class="pm-col-status"><?php echo htmlspecialchars(__('status')); ?></th>
                      <th><?php echo htmlspecialchars(__('days_out')); ?></th>
                      <th><?php echo htmlspecialchars(__('last_absence')); ?></th>
                      <th class="pm-col-actions tw-whitespace-nowrap"><?php echo htmlspecialchars(__('actions')); ?></th>
                    </tr>
                  </thead>
                </table>
              </div>
              <div class="pm-table-body" role="region" aria-label="<?php echo htmlspecialchars(__('employee_list')); ?>">
                <table class="pm-table pm-table--employee-directory">
                  <colgroup>
                    <col class="pm-col-employee">
                    <col class="pm-col-status">
                    <col class="pm-col-days">
                    <col class="pm-col-last">
                    <col class="pm-col-actions">
                  </colgroup>
                  <tbody id="employeeList"></tbody>
                </table>
                <div id="employeeListSentinel" class="tw-h-1"></div>
              </div>
            </div>
          </div>
          <div id="clientNoMatch" class="tw-hidden tw-border-t tw-border-dashed tw-border-slate-200 tw-px-4 tw-py-10 tw-text-center tw-text-sm tw-text-slate-600">
            <?php echo htmlspecialchars(__('no_match_search')); ?>
          </div>
        <?php endif; ?>
    </section>

    <div id="bulkImportBackdrop" class="tw-pointer-events-none tw-fixed tw-inset-0 tw-z-50 tw-flex tw-items-center tw-justify-center tw-bg-slate-900/35 tw-p-4 tw-opacity-0 tw-backdrop-blur-md tw-transition tw-duration-200" aria-hidden="true">
      <div id="bulkImportPanel" role="dialog" aria-modal="true" aria-labelledby="bulkImportTitle" class="tw-max-h-[min(90vh,540px)] tw-w-full tw-max-w-md tw-scale-95 tw-overflow-hidden tw-rounded-2xl tw-border tw-border-white/55 tw-bg-white/72 tw-opacity-0 tw-shadow-[0_24px_80px_rgba(8,112,184,0.2)] tw-backdrop-blur-xl tw-transition tw-duration-200">
        <div class="tw-border-b tw-border-white/40 tw-bg-gradient-to-br tw-from-white/90 tw-to-white/50 tw-px-5 tw-py-4">
          <h2 id="bulkImportTitle" class="tw-m-0 tw-text-lg tw-font-black tw-text-slate-900"><?php echo htmlspecialchars(__('bulk_import_title')); ?></h2>
          <p class="tw-m-0 tw-mt-1 tw-text-sm tw-leading-relaxed tw-text-slate-600"><?php echo htmlspecialchars(__('bulk_import_lede')); ?></p>
        </div>
        <form id="bulkImportForm" class="tw-space-y-4 tw-px-5 tw-py-5" method="post" action="/admin-panel/employees/import" enctype="multipart/form-data" autocomplete="off">
          <div>
            <label class="tw-mb-2 tw-block tw-text-xs tw-font-bold tw-uppercase tw-tracking-wider tw-text-slate-500" for="import_file"><?php echo htmlspecialchars(__('workbook_file')); ?></label>
            <input class="tw-block tw-w-full tw-cursor-pointer tw-rounded-xl tw-border tw-border-slate-200/80 tw-bg-white/90 tw-px-3 tw-py-2.5 tw-text-sm tw-text-slate-800 tw-outline-none file:tw-mr-3 file:tw-rounded-lg file:tw-border-0 file:tw-bg-blue-600 file:tw-px-3 file:tw-py-1.5 file:tw-text-xs file:tw-font-bold file:tw-text-white focus:tw-border-blue-400 focus:tw-ring-2 focus:tw-ring-blue-100" type="file" name="import_file" id="import_file" accept=".xlsx,.xls,.xlsm,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel" required>
          </div>
          <div class="tw-flex tw-flex-wrap tw-items-center tw-justify-end tw-gap-2 tw-pt-1">
            <button type="button" id="closeBulkImport" class="tw-inline-flex tw-items-center tw-justify-center tw-rounded-xl tw-border tw-border-slate-200 tw-bg-white tw-px-4 tw-py-2.5 tw-text-sm tw-font-semibold tw-text-slate-800 tw-transition hover:tw-bg-slate-50"><?php echo htmlspecialchars(__('cancel')); ?></button>
            <button type="submit" id="bulkImportSubmit" class="tw-inline-flex tw-min-w-[8.5rem] tw-items-center tw-justify-center tw-rounded-xl tw-bg-blue-600 tw-px-4 tw-py-2.5 tw-text-sm tw-font-bold tw-text-white tw-shadow-[0_10px_24px_rgba(37,99,235,.25)] tw-transition hover:tw-bg-blue-700 disabled:tw-cursor-not-allowed disabled:tw-opacity-60"><?php echo htmlspecialchars(__('import')); ?></button>
          </div>
        </form>
      </div>
    </div>

  <script>
  (function () {
    const i18n = <?php echo json_encode([
        'matchOne' => __('match_one'),
        'matchMany' => __('match_many'),
        'noPosition' => __('no_position'),
        'edit' => __('edit'),
        'delete' => __('delete'),
        'deleteConfirm' => __('delete_employee_confirm'),
        'editEmployee' => __('edit_employee'),
        'deleteEmployee' => __('delete_employee'),
        'processing' => __('processing'),
        'import' => __('import'),
    ], JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
    const form = document.getElementById('employeeFilterForm');
    if (!form) return;
    const tableWrap = document.querySelector('.pm-table-wrap');
    const tableBody = document.querySelector('.pm-table-body');
    const startEl = document.getElementById('empStart');
    const endEl = document.getElementById('empEnd');
    const qEl = document.getElementById('empQ');
    const listEl = document.getElementById('employeeList');
    const countEl = document.getElementById('employeeMatchCount');
    const noMatchEl = document.getElementById('clientNoMatch');

    function syncHeaderScrollbarGutter() {
      if (!tableWrap || !tableBody) return;
      if (window.innerWidth <= 1023) {
        tableWrap.style.setProperty('--pm-sbw', '0px');
        return;
      }
      const sbw = tableBody.offsetWidth - tableBody.clientWidth;
      tableWrap.style.setProperty('--pm-sbw', Math.max(0, sbw) + 'px');
    }

    function toYmd(d) {
      const y = d.getFullYear();
      const m = String(d.getMonth() + 1).padStart(2, '0');
      const day = String(d.getDate()).padStart(2, '0');
      return y + '-' + m + '-' + day;
    }

    function setRange(start, end) {
      if (startEl) startEl.value = start;
      if (endEl) endEl.value = end;
    }

    function submitForm() {
      if (form.requestSubmit) form.requestSubmit();
      else form.submit();
    }

    function escapeHtml(s) {
      return s.replace(/[&<>"']/g, function (ch) {
        if (ch === '&') return '&amp;';
        if (ch === '<') return '&lt;';
        if (ch === '>') return '&gt;';
        if (ch === '"') return '&quot;';
        return '&#39;';
      });
    }

    function highlight(name, q) {
      const safeName = escapeHtml(name);
      if (!q) return safeName;
      const lower = name.toLowerCase();
      const needle = q.toLowerCase();
      const idx = lower.indexOf(needle);
      if (idx === -1) return safeName;
      const before = escapeHtml(name.slice(0, idx));
      const match = escapeHtml(name.slice(idx, idx + q.length));
      const after = escapeHtml(name.slice(idx + q.length));
      return before + '<mark class="tw-bg-amber-200 tw-text-slate-900 tw-rounded tw-px-0.5">' + match + '</mark>' + after;
    }

    const ROWS_PER_PAGE = 20;
    const allRows = <?php echo json_encode(array_values($dir['rows'] ?? []), JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
    const sentinel = document.getElementById('employeeListSentinel');
    let filteredRows = allRows.slice();
    let renderedCount = 0;
    let activeQuery = '';

    function badgeClassFor(variant) {
      const map = {
        green: 'tw-bg-emerald-500/10 tw-text-emerald-700 tw-ring-emerald-500/20',
        blue: 'tw-bg-sky-500/10 tw-text-sky-800 tw-ring-sky-500/20',
        red: 'tw-bg-rose-500/10 tw-text-rose-800 tw-ring-rose-500/20',
        yellow: 'tw-bg-amber-500/10 tw-text-amber-900 tw-ring-amber-500/20',
        slate: 'tw-bg-slate-500/10 tw-text-slate-800 tw-ring-slate-500/15',
        violet: 'tw-bg-violet-500/10 tw-text-violet-900 tw-ring-violet-500/20',
        orange: 'tw-bg-orange-500/10 tw-text-orange-900 tw-ring-orange-500/25'
      };
      return map[String(variant || 'slate')] || map.slate;
    }

    function rowHtml(row, q) {
      const id = Number(row.id || 0);
      const detailUrl = String(row.detail_url || ('/admin/employees/' + id));
      const name = String(row.name || '');
      const photo = String(row.photo || '');
      const initial = String(row.initial || '?');
      const subtitle = String(row.division_line || i18n.noPosition);
      const badge = row.badge || {};
      const badgeLabel = String(badge.label || '');
      const badgeClass = badgeClassFor(badge.variant);
      const absenceDays = Number(row.absence_days || 0);
      const lastAbsence = String(row.last_absence || '—');
      const isActivePulse = !!row.isActivePulse;
      const highlightedName = q ? highlight(name, q) : escapeHtml(name);

      return ''
        + '<tr class="pm-row" data-detail-url="' + escapeHtml(detailUrl) + '" data-employee-name="' + escapeHtml(name) + '" role="link" tabindex="0"'
        + ' onkeydown="if(event.key===\'Enter\'){location.href=this.dataset.detailUrl;}"'
        + ' onclick="if(event.target.closest(\'a,button\'))return;location.href=this.dataset.detailUrl;">'
        + '<td><div class="tw-flex tw-items-center tw-gap-3">'
        + '<div class="tw-relative tw-h-10 tw-w-10 tw-shrink-0 tw-overflow-hidden tw-rounded-xl tw-bg-gradient-to-br tw-from-slate-100 tw-to-slate-200 tw-ring-1 tw-ring-slate-200/80">'
        + (photo !== ''
          ? '<img class="tw-h-full tw-w-full tw-object-cover" src="' + escapeHtml(photo) + '" alt="" width="40" height="40" loading="lazy" decoding="async">'
          : '<div class="tw-flex tw-h-full tw-w-full tw-items-center tw-justify-center tw-text-xs tw-font-black tw-text-blue-800">' + escapeHtml(initial) + '</div>')
        + '</div><div class="tw-min-w-0">'
        + '<p class="pm-name tw-m-0 tw-truncate tw-text-sm tw-font-semibold tw-tracking-tight tw-text-slate-900 js-employee-name">' + highlightedName + '</p>'
        + '<p class="tw-m-0 tw-mt-0.5 tw-truncate tw-text-xs tw-text-slate-500">' + escapeHtml(subtitle) + '</p>'
        + '<div class="tw-mt-1.5 md:tw-hidden"><span class="tw-inline-flex tw-items-center tw-rounded-full tw-px-2 tw-py-0.5 tw-text-[0.65rem] tw-font-semibold tw-ring-1 tw-ring-inset ' + badgeClass + '">' + escapeHtml(badgeLabel) + '</span></div>'
        + '</div></div></td>'
        + '<td class="pm-col-status"><span class="pm-status-dot ' + (isActivePulse ? 'pm-status-dot--in' : 'pm-status-dot--out') + '"><span class="pm-status-badge tw-inline-flex tw-items-center tw-justify-center tw-rounded-full tw-px-2.5 tw-py-1 tw-text-center tw-text-[0.7rem] tw-font-semibold tw-leading-snug tw-ring-1 tw-ring-inset ' + badgeClass + '">' + escapeHtml(badgeLabel) + '</span></span></td>'
        + '<td class="tw-text-sm tw-tabular-nums tw-text-slate-700">' + absenceDays + '</td>'
        + '<td class="tw-text-sm tw-text-slate-600">' + escapeHtml(lastAbsence) + '</td>'
        + '<td class="pm-col-actions tw-whitespace-nowrap" onclick="event.stopPropagation();"><div class="tw-inline-flex tw-flex-nowrap tw-items-center tw-justify-end tw-gap-2">'
        + '<a class="tw-inline-flex tw-h-9 tw-w-24 tw-shrink-0 tw-items-center tw-justify-center tw-gap-1 tw-rounded-lg tw-border tw-border-slate-200 tw-bg-white tw-px-1.5 tw-text-xs tw-font-semibold tw-text-slate-800 tw-shadow-sm tw-transition tw-duration-300 hover:tw-bg-slate-50" href="/admin-panel/employees/edit?id=' + id + '" aria-label="' + escapeHtml(i18n.editEmployee) + '">'
        + '<svg class="tw-h-3.5 tw-w-3.5 tw-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>'
        + '<span>' + escapeHtml(i18n.edit) + '</span></a>'
        + '<form class="tw-m-0 tw-inline-block tw-w-24 tw-shrink-0" method="POST" action="/admin-panel/employee/delete" onsubmit="return confirm(' + JSON.stringify(i18n.deleteConfirm) + ');">'
        + '<input type="hidden" name="id" value="' + id + '">'
        + '<button class="tw-inline-flex tw-h-9 tw-w-full tw-items-center tw-justify-center tw-gap-1 tw-rounded-lg tw-border tw-border-rose-200 tw-bg-rose-600 tw-px-1.5 tw-text-xs tw-font-semibold tw-text-white tw-transition tw-duration-300 hover:tw-bg-rose-700" type="submit" aria-label="' + escapeHtml(i18n.deleteEmployee) + '">'
        + '<svg class="tw-h-3.5 tw-w-3.5 tw-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>'
        + '<span>' + escapeHtml(i18n.delete) + '</span></button>'
        + '</form></div></td></tr>';
    }

    function renderNextChunk() {
      if (!listEl) return;
      if (renderedCount >= filteredRows.length) return;
      const next = filteredRows.slice(renderedCount, renderedCount + ROWS_PER_PAGE);
      const html = next.map(function (row) { return rowHtml(row, activeQuery); }).join('');
      listEl.insertAdjacentHTML('beforeend', html);
      renderedCount += next.length;
      syncHeaderScrollbarGutter();
    }

    function resetAndRender() {
      if (!listEl) return;
      listEl.innerHTML = '';
      renderedCount = 0;
      renderNextChunk();
      if (countEl) {
        const visible = filteredRows.length;
        countEl.textContent = (visible === 1 ? i18n.matchOne : i18n.matchMany).replace(':n', String(visible));
      }
      if (noMatchEl) {
        noMatchEl.classList.toggle('tw-hidden', filteredRows.length !== 0);
      }
      syncHeaderScrollbarGutter();
    }

    function applyClientSearch() {
      if (!qEl) return;
      activeQuery = qEl.value.trim();
      if (activeQuery === '') {
        filteredRows = allRows.slice();
      } else {
        const nq = activeQuery.toLowerCase();
        filteredRows = allRows.filter(function (row) {
          return String(row.name || '').toLowerCase().indexOf(nq) !== -1;
        });
      }
      resetAndRender();
    }

    document.querySelectorAll('.emp-preset').forEach(function (btn) {
      btn.addEventListener('click', function () {
        const p = btn.getAttribute('data-preset');
        const now = new Date();
        if (p === 'today') {
          const t = toYmd(now);
          setRange(t, t);
        } else if (p === 'week') {
          const d = new Date(now);
          const day = d.getDay();
          const monOffset = (day + 6) % 7;
          const mon = new Date(d);
          mon.setDate(d.getDate() - monOffset);
          const sun = new Date(mon);
          sun.setDate(mon.getDate() + 6);
          setRange(toYmd(mon), toYmd(sun));
        } else if (p === 'month') {
          const a = new Date(now.getFullYear(), now.getMonth(), 1);
          const b = new Date(now.getFullYear(), now.getMonth() + 1, 0);
          setRange(toYmd(a), toYmd(b));
        }
        submitForm();
      });
    });

    document.querySelectorAll('.emp-auto').forEach(function (el) {
      el.addEventListener('change', function () {
        submitForm();
      });
    });

    if (qEl) {
      let searchDebounceId = null;
      qEl.addEventListener('input', function () {
        if (searchDebounceId) window.clearTimeout(searchDebounceId);
        searchDebounceId = window.setTimeout(function () {
          applyClientSearch();
          searchDebounceId = null;
        }, 300);
      });
    }
    applyClientSearch();
    if (tableBody && sentinel && 'IntersectionObserver' in window) {
      const io = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
          if (entry.isIntersecting) renderNextChunk();
        });
      }, { root: tableBody, threshold: 0.1, rootMargin: '200px' });
      io.observe(sentinel);
    } else if (tableBody) {
      tableBody.addEventListener('scroll', function () {
        if ((tableBody.scrollTop + tableBody.clientHeight) >= (tableBody.scrollHeight - 200)) {
          renderNextChunk();
        }
      });
    }
    syncHeaderScrollbarGutter();
    window.addEventListener('resize', syncHeaderScrollbarGutter);

    (function bulkImportUi() {
      var openBtn = document.getElementById('openBulkImport');
      var backdrop = document.getElementById('bulkImportBackdrop');
      var panel = document.getElementById('bulkImportPanel');
      var closeBtn = document.getElementById('closeBulkImport');
      var form = document.getElementById('bulkImportForm');
      var submitBtn = document.getElementById('bulkImportSubmit');
      if (!openBtn || !backdrop || !panel || !form || !submitBtn) return;

      function setOpen(on) {
        backdrop.setAttribute('aria-hidden', on ? 'false' : 'true');
        backdrop.classList.toggle('tw-pointer-events-none', !on);
        backdrop.classList.toggle('tw-opacity-0', !on);
        panel.classList.toggle('tw-scale-95', !on);
        panel.classList.toggle('tw-opacity-0', !on);
        document.documentElement.style.overflow = on ? 'hidden' : '';
      }

      openBtn.addEventListener('click', function () { setOpen(true); });
      if (closeBtn) closeBtn.addEventListener('click', function () { setOpen(false); });
      backdrop.addEventListener('click', function (e) {
        if (e.target === backdrop) setOpen(false);
      });
      document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && backdrop.getAttribute('aria-hidden') === 'false') setOpen(false);
      });
      form.addEventListener('submit', function () {
        submitBtn.disabled = true;
        submitBtn.setAttribute('aria-busy', 'true');
        submitBtn.textContent = i18n.processing;
      });
    })();
  })();
  </script>
<?php
include $viewsRoot . '/partials/admin-chrome-close.php';
