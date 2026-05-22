<?php
$viewsRoot = __DIR__;
$pageTitle = __('presence_insights') . ' — ' . __('app_name');
$activeNav = 'dashboard';
$shellClass = 'shell wide';

/** @var array<string,string> */
$paletteByStatus = [
  __('status.in_office') => '#10b981',
  'WFH' => '#3b82f6',
  'Sakit' => '#ef4444',
  'Cuti Tahunan' => '#eab308',
  'Dinas Luar' => '#8b5cf6',
  __('status.izin') => '#f97316',
];

/** Gradient pairs [light, deep] per status for gauge segments (order follows statusCounts keys) */
$gradientPairs = [
  __('status.in_office') => ['#10b981', '#34d399'],
  'WFH' => ['#60a5fa', '#2563eb'],
  'Sakit' => ['#fb7185', '#dc2626'],
  'Cuti Tahunan' => ['#fde047', '#ca8a04'],
  'Dinas Luar' => ['#c4b5fd', '#7c3aed'],
  __('status.izin') => ['#fdba74', '#ea580c'],
];
$gradientPairsOrdered = [];
foreach (array_keys($analytics['statusCounts']) as $_lbl) {
  $gradientPairsOrdered[] = $gradientPairs[$_lbl] ?? ['#94a3b8', '#64748b'];
}

include $viewsRoot . '/partials/admin-chrome-open.php';
?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
  .js-donut-left-card {
    transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.6s cubic-bezier(0.16, 1, 0.3, 1);
  }
  .js-donut-left-card.is-donut-slice-hover {
    transform: scale(1.02);
    box-shadow: 0 14px 32px rgba(15, 23, 42, 0.14), 0 6px 14px rgba(15, 23, 42, 0.08);
  }
  .js-status-pill.is-donut-hover:not(.js-donut-left-card):not(.is-donut-focused) {
    transform: translateY(-1px);
    box-shadow: 0 8px 20px rgba(15, 23, 42, 0.1);
    transition: transform 0.55s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.55s cubic-bezier(0.16, 1, 0.3, 1);
  }
  .js-status-pill.is-donut-focused {
    transform: translateY(-1px);
    box-shadow: 0 12px 28px rgba(15, 23, 42, 0.12);
    transition: transform 0.55s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.55s cubic-bezier(0.16, 1, 0.3, 1);
  }
</style>

<section class="tw-relative tw-overflow-hidden tw-rounded-2xl tw-border tw-border-white/50 tw-bg-white/70 tw-p-4 tw-backdrop-blur-xl tw-shadow-[0_20px_50px_rgba(8,112,184,0.12)] sm:tw-p-6">
  <div class="tw-pointer-events-none tw-absolute tw--right-20 tw--top-16 tw-h-48 tw-w-48 tw-rounded-full tw-bg-violet-400/15 tw-blur-3xl" aria-hidden="true"></div>
  <div class="tw-relative tw-flex tw-flex-col tw-gap-4 lg:tw-flex-row lg:tw-items-start lg:tw-justify-between">
    <div>
      <p class="tw-m-0 tw-text-[0.72rem] tw-font-extrabold tw-uppercase tw-tracking-[0.16em] tw-text-slate-500"><?php echo htmlspecialchars(__('command_center')); ?></p>
      <h1 class="tw-m-0 tw-mt-2 tw-text-2xl tw-font-black tw-tracking-tight tw-text-slate-900 sm:tw-text-3xl"><?php echo htmlspecialchars(__('presence_insights')); ?></h1>
      <p class="tw-m-0 tw-mt-1 tw-max-w-xl tw-text-sm tw-leading-relaxed tw-text-slate-600"><?php echo htmlspecialchars(__('insights_lede')); ?> <span class="tw-font-semibold tw-text-slate-800"><?php echo htmlspecialchars($analytics['selectedDate']); ?></span></p>
    </div>
    <form method="GET" action="/admin/dashboard" class="tw-flex tw-flex-wrap tw-items-center tw-gap-2 tw-rounded-xl tw-border tw-border-slate-200/60 tw-bg-white/80 tw-px-2 tw-py-2 tw-backdrop-blur-sm">
      <label for="dateFilter" class="tw-pl-1 tw-text-xs tw-font-bold tw-uppercase tw-tracking-wider tw-text-slate-500"><?php echo htmlspecialchars(__('date')); ?></label>
      <button class="tw-flex tw-h-9 tw-w-10 tw-items-center tw-justify-center tw-rounded-lg tw-border tw-border-slate-200/90 tw-bg-white tw-text-lg tw-font-bold tw-text-blue-700 tw-shadow-sm tw-transition hover:tw-bg-slate-50" type="button" id="prevDay" aria-label="<?php echo htmlspecialchars(__('previous_day')); ?>" title="<?php echo htmlspecialchars(__('previous_day')); ?>">‹</button>
      <input id="dateFilter" class="tw-h-9 tw-rounded-lg tw-border tw-border-slate-200/90 tw-bg-white tw-px-3 tw-text-sm tw-font-semibold tw-text-slate-800 tw-shadow-[inset_0_1px_0_rgba(255,255,255,0.9)] tw-outline-none focus:tw-border-blue-400 focus:tw-ring-2 focus:tw-ring-blue-100" type="date" name="date" value="<?php echo htmlspecialchars($analytics['selectedDate']); ?>">
      <button class="tw-flex tw-h-9 tw-w-10 tw-items-center tw-justify-center tw-rounded-lg tw-border tw-border-slate-200/90 tw-bg-white tw-text-lg tw-font-bold tw-text-blue-700 tw-shadow-sm tw-transition hover:tw-bg-slate-50" type="button" id="nextDay" aria-label="<?php echo htmlspecialchars(__('next_day')); ?>" title="<?php echo htmlspecialchars(__('next_day')); ?>">›</button>
    </form>
  </div>
</section>

<section class="tw-mt-5 tw-rounded-2xl tw-border tw-border-white/50 tw-bg-white/68 tw-p-4 tw-backdrop-blur-xl tw-shadow-[0_20px_50px_rgba(8,112,184,0.1)] sm:tw-p-6">
  <h2 class="tw-m-0 tw-text-lg tw-font-extrabold tw-tracking-tight tw-text-slate-900"><?php echo htmlspecialchars(__('status_distribution')); ?></h2>
  <p class="tw-m-0 tw-mt-1 tw-text-sm tw-leading-relaxed tw-text-slate-600"><?php echo htmlspecialchars(__('status_distribution_lede')); ?></p>

  <div class="tw-mt-6 tw-grid tw-grid-cols-1 tw-gap-8 xl:tw-grid-cols-12 xl:tw-items-center xl:tw-gap-10">
    <div class="tw-flex tw-min-w-0 tw-flex-col tw-gap-2.5 xl:tw-col-span-4 xl:tw-self-center">
      <article class="tw-relative tw-overflow-hidden tw-rounded-2xl tw-border tw-border-white/20 tw-bg-gradient-to-br tw-from-blue-600 tw-via-indigo-600 tw-to-violet-700 tw-p-5 tw-text-white tw-shadow-[0_16px_40px_rgba(37,99,235,.22)]">
        <p class="tw-m-0 tw-text-[0.72rem] tw-font-bold tw-uppercase tw-tracking-[0.14em] tw-text-white/80"><?php echo htmlspecialchars(__('total_employees')); ?></p>
        <p class="tw-m-0 tw-mt-2 tw-text-4xl tw-font-black tw-tracking-tight tw-tabular-nums sm:tw-text-5xl"><?php echo (int) $analytics['totalEmployees']; ?></p>
        <p class="tw-m-0 tw-mt-1 tw-text-xs tw-font-medium tw-text-white/85 sm:tw-text-sm"><?php echo htmlspecialchars(__('total_employees_all_status')); ?></p>
      </article>
      <?php
        $si = 0;
        foreach ($analytics['statusCounts'] as $label => $count):
          $hex = $paletteByStatus[$label] ?? '#64748b';
          $pct = (float) ($analytics['statusPercentages'][$label] ?? 0);
          $pct = min(100, max(0, $pct));
      ?>
        <article
          class="js-status-pill js-donut-left-card tw-cursor-pointer tw-rounded-xl tw-border tw-px-3 tw-py-2.5 tw-shadow-sm tw-transition tw-duration-200 hover:tw-ring-2 hover:tw-ring-offset-2 hover:tw-ring-offset-white/80"
          style="background: linear-gradient(135deg, <?php echo htmlspecialchars($hex); ?>22 0%, <?php echo htmlspecialchars($hex); ?>0f 100%); border-color: <?php echo htmlspecialchars($hex); ?>55; --pill-ring: <?php echo htmlspecialchars($hex); ?>;"
          data-status-index="<?php echo (int) $si; ?>"
          data-status-label="<?php echo htmlspecialchars($label); ?>"
          role="button"
          tabindex="0"
          aria-label="<?php echo htmlspecialchars(__('highlight_status', ['status' => $label])); ?>"
        >
          <div class="tw-flex tw-items-center tw-gap-2">
            <div class="tw-flex tw-h-8 tw-w-8 tw-shrink-0 tw-items-center tw-justify-center tw-rounded-lg tw-bg-white/80 tw-ring-1 tw-ring-black/5" style="color: <?php echo htmlspecialchars($hex); ?>">
              <span class="tw-block tw-h-2.5 tw-w-2.5 tw-rounded-full tw-bg-current" aria-hidden="true"></span>
            </div>
            <div class="tw-min-w-0 tw-flex-1">
              <p class="tw-m-0 tw-text-[0.68rem] tw-font-bold tw-uppercase tw-tracking-wide tw-leading-none tw-text-slate-700"><?php echo htmlspecialchars($label); ?></p>
              <p class="tw-m-0 tw-mt-0.5 tw-text-xl tw-font-black tw-leading-none tw-tabular-nums sm:tw-text-2xl" style="color: <?php echo htmlspecialchars($hex); ?>"><?php echo (int) $count; ?></p>
              <div class="tw-mt-1 tw-h-1.5 tw-w-full tw-overflow-hidden tw-rounded-full tw-bg-white/55 tw-ring-1 tw-ring-black/5">
                <div class="tw-h-full tw-rounded-full tw-shadow-sm" style="width: <?php echo htmlspecialchars((string) $pct); ?>%; background-color: <?php echo htmlspecialchars($hex); ?>"></div>
              </div>
            </div>
          </div>
        </article>
      <?php
          $si++;
        endforeach;
      ?>
    </div>

    <div class="tw-flex tw-min-h-0 tw-min-w-0 tw-flex-col tw-self-center xl:tw-col-span-8">
      <div class="tw-flex tw-h-full tw-min-h-[340px] tw-min-w-0 tw-flex-1 tw-flex-col tw-justify-center tw-gap-6 sm:tw-min-h-[380px] xl:tw-min-h-[420px]">
        <div class="tw-flex tw-min-h-0 tw-flex-1 tw-items-center tw-justify-center tw-px-3 tw-py-6 sm:tw-px-10 sm:tw-py-12">
          <div id="donutChartWrap" class="tw-relative tw-h-[320px] tw-w-full tw-max-w-[300px] md:tw-h-[400px] sm:tw-max-w-[360px] xl:tw-max-w-[400px]">
            <canvas id="statusDonut" class="tw-relative tw-z-0 tw-block tw-h-full tw-w-full tw-drop-shadow-[0_14px_40px_rgba(15,23,42,.1)]"></canvas>
            <div class="tw-pointer-events-none tw-absolute tw-inset-0 tw-z-[6] tw-flex tw-items-center tw-justify-center" aria-hidden="true">
              <div class="tw-flex tw-aspect-square tw-w-[48%] tw-min-w-[7.25rem] tw-max-w-[10.5rem] tw-flex-col tw-items-center tw-justify-center tw-rounded-full tw-border tw-border-white/80 tw-bg-white/50 tw-px-4 tw-py-5 tw-text-center tw-backdrop-blur-md tw-shadow-inner tw-shadow-[inset_0_2px_14px_rgba(15,23,42,0.07),0_10px_36px_rgba(15,23,42,.06)] sm:tw-max-w-[11.75rem] sm:tw-px-6 sm:tw-py-6">
                <p class="tw-m-0 tw-text-4xl tw-font-black tw-tabular-nums tw-leading-none tw-tracking-tight tw-text-slate-900 sm:tw-text-5xl"><?php echo (int) $analytics['totalEmployees']; ?></p>
                <p class="tw-m-0 tw-mt-2 tw-text-[0.62rem] tw-font-extrabold tw-uppercase tw-tracking-[0.22em] tw-text-slate-500 sm:tw-text-xs"><?php echo htmlspecialchars(__('total_employees')); ?></p>
              </div>
            </div>
          </div>
        </div>
        <div class="tw-flex tw-flex-wrap tw-items-center tw-justify-center tw-gap-x-5 tw-gap-y-2 tw-border-t tw-border-slate-100 tw-pt-5 sm:tw-justify-start" id="statusLegend" aria-label="<?php echo htmlspecialchars(__('chart_legend')); ?>">
          <?php
            $lj = 0;
            foreach ($analytics['statusCounts'] as $label => $count):
              $hex = $paletteByStatus[$label] ?? '#64748b';
              $pct = (float) ($analytics['statusPercentages'][$label] ?? 0);
          ?>
            <span
              class="js-status-pill tw-inline-flex tw-cursor-pointer tw-items-center tw-gap-2 tw-whitespace-nowrap tw-rounded-full tw-border tw-border-slate-200/80 tw-bg-white/60 tw-px-2.5 tw-py-1 tw-text-sm tw-text-slate-600 tw-shadow-sm tw-transition hover:tw-bg-white"
              style="--pill-ring: <?php echo htmlspecialchars($hex); ?>;"
              data-status-index="<?php echo (int) $lj; ?>"
              data-status-label="<?php echo htmlspecialchars($label); ?>"
              role="button"
              tabindex="0"
            >
              <span class="tw-h-3 tw-w-3 tw-shrink-0 tw-rounded-sm tw-ring-1 tw-ring-black/10" style="background-color: <?php echo htmlspecialchars($hex); ?>"></span>
              <span class="tw-font-medium tw-text-slate-800"><?php echo htmlspecialchars($label); ?></span>
              <span class="tw-tabular-nums tw-text-slate-500"><?php echo (int) $count; ?> (<?php echo htmlspecialchars((string) $pct); ?>%)</span>
            </span>
          <?php
              $lj++;
            endforeach;
          ?>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="tw-mt-5 tw-rounded-2xl tw-border tw-border-white/50 tw-bg-white/68 tw-p-4 tw-backdrop-blur-xl tw-shadow-[0_20px_50px_rgba(8,112,184,0.1)] sm:tw-p-6">
  <div class="tw-flex tw-flex-col tw-gap-3 sm:tw-flex-row sm:tw-items-start sm:tw-justify-between sm:tw-gap-4">
    <div>
      <h2 class="tw-m-0 tw-text-lg tw-font-extrabold tw-tracking-tight tw-text-slate-900"><?php echo htmlspecialchars(__('absence_trend')); ?></h2>
      <p id="trendSubtitle" class="tw-m-0 tw-mt-1 tw-text-sm tw-leading-relaxed tw-text-slate-600"><?php echo htmlspecialchars(__('trend_7d')); ?></p>
    </div>
    <div class="tw-inline-flex tw-shrink-0 tw-rounded-xl tw-border tw-border-slate-200/90 tw-bg-slate-100/80 tw-p-1 tw-shadow-inner" role="group" aria-label="<?php echo htmlspecialchars(__('trend_range')); ?>">
      <button type="button" class="trend-range-btn tw-rounded-lg tw-px-3 tw-py-1.5 tw-text-xs tw-font-extrabold tw-uppercase tw-tracking-wide tw-bg-white tw-text-blue-800 tw-shadow-sm tw-ring-1 tw-ring-slate-200/80 tw-transition hover:tw-bg-white/90" data-range="7d">7D</button>
      <button type="button" class="trend-range-btn tw-rounded-lg tw-px-3 tw-py-1.5 tw-text-xs tw-font-extrabold tw-uppercase tw-tracking-wide tw-text-slate-600 tw-transition hover:tw-bg-white/90" data-range="30d">30D</button>
      <button type="button" class="trend-range-btn tw-rounded-lg tw-px-3 tw-py-1.5 tw-text-xs tw-font-extrabold tw-uppercase tw-tracking-wide tw-text-slate-600 tw-transition hover:tw-bg-white/90" data-range="1y">1Y</button>
    </div>
  </div>
  <div class="tw-relative tw-mt-4 tw-h-[280px] tw-rounded-xl tw-border tw-border-slate-100/80 tw-bg-white/40 tw-px-2 tw-py-3 md:tw-h-[320px]"><canvas id="absenceTrend" class="tw-h-full tw-w-full"></canvas></div>
</section>

<script>
    const statusLabels = <?php echo json_encode(array_keys($analytics['statusCounts']), JSON_UNESCAPED_UNICODE); ?>;
    const statusCounts = <?php echo json_encode(array_values($analytics['statusCounts'])); ?>;
    const statusPercentages = <?php echo json_encode(array_values($analytics['statusPercentages'])); ?>;
    const trends = <?php echo json_encode($analytics['trends'] ?? ['7d' => ['labels' => [], 'counts' => []], '30d' => ['labels' => [], 'counts' => []], '1y' => ['labels' => [], 'counts' => []]], JSON_UNESCAPED_UNICODE); ?>;
    const gradientStops = <?php echo json_encode($gradientPairsOrdered, JSON_UNESCAPED_UNICODE); ?>;
    const uiTrend = <?php echo json_encode([
        'notInOffice' => __('not_in_office_chart'),
        'employees' => __('employees'),
        '7d' => __('trend_7d'),
        '30d' => __('trend_30d'),
        '1y' => __('trend_1y'),
        'axis7d' => __('last_7_days'),
        'axis30d' => __('last_30_days'),
        'axis1y' => __('last_12_months'),
    ], JSON_UNESCAPED_UNICODE); ?>;

    let statusChart = null;
    let hoverSyncIndex = null;
    let hoverSource = null;
    let focusIndex = null;
    let pillHovering = false;
    let chartAreaHovering = false;
    let hoverClearTimer = null;

    function shadeHex(hex, pct) {
      const n = hex.replace('#', '');
      const val = parseInt(n, 16);
      const amt = Math.round(2.55 * pct);
      const r = Math.min(255, Math.max(0, (val >> 16) + amt));
      const g = Math.min(255, Math.max(0, ((val >> 8) & 0x00ff) + amt));
      const b = Math.min(255, Math.max(0, (val & 0x0000ff) + amt));
      return `#${(0x1000000 + (r << 16) + (g << 8) + b).toString(16).slice(1)}`;
    }

    function segmentGradient(ctx, c1, c2) {
      const chart = ctx.chart;
      const area = chart.chartArea;
      if (!area) return c1;
      const g = chart.ctx.createLinearGradient(area.left, area.top, area.right, area.bottom);
      g.addColorStop(0, shadeHex(c1, 10));
      g.addColorStop(0.45, c1);
      g.addColorStop(0.55, c2);
      g.addColorStop(1, shadeHex(c2, -8));
      return g;
    }

    function donutTooltipEsc(s) {
      return String(s)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
    }

    function donutExternalTooltip(context) {
      const { chart, tooltip } = context;
      let el = document.getElementById('donutStatusTooltip');
      if (!el) {
        el = document.createElement('div');
        el.id = 'donutStatusTooltip';
        el.style.position = 'fixed';
        el.style.pointerEvents = 'none';
        el.style.zIndex = '10050';
        el.style.opacity = '0';
        el.style.transition = 'opacity 0.14s ease, transform 0.12s ease';
        el.style.background = 'rgba(255, 255, 255, 0.78)';
        el.style.webkitBackdropFilter = 'blur(16px)';
        el.style.backdropFilter = 'blur(16px)';
        el.style.border = '1px solid rgba(255, 255, 255, 0.92)';
        el.style.borderRadius = '14px';
        el.style.padding = '12px 16px';
        el.style.minWidth = '140px';
        el.style.maxWidth = '260px';
        el.style.boxShadow = '0 12px 40px rgba(15, 23, 42, 0.12), 0 4px 16px rgba(59, 130, 246, 0.12)';
        document.body.appendChild(el);
      }
      if (tooltip.opacity === 0) {
        el.style.opacity = '0';
        return;
      }
      let label = '';
      let count = '';
      let pct = '';
      if (tooltip.dataPoints && tooltip.dataPoints.length) {
        const dp = tooltip.dataPoints[0];
        const idx = dp.dataIndex;
        label = dp.label != null ? String(dp.label) : '';
        count = dp.raw !== undefined && dp.raw !== null ? String(dp.raw) : '';
        pct = statusPercentages[idx] != null ? String(statusPercentages[idx]) : '';
      } else {
        const title = (tooltip.title || []).join('');
        if (title) label = title;
      }
      el.innerHTML = ''
        + '<div style="font-weight:800;font-size:14px;line-height:1.25;color:#0f172a;letter-spacing:-0.02em">' + donutTooltipEsc(label) + '</div>'
        + '<div style="margin-top:8px;font-size:12px;font-weight:600;line-height:1.45;color:#475569">'
        + '<span style="font-variant-numeric:tabular-nums">' + donutTooltipEsc(count) + '</span>'
        + '<span style="margin:0 6px;opacity:0.45">·</span>'
        + '<span style="font-variant-numeric:tabular-nums">' + donutTooltipEsc(pct) + '%</span>'
        + '</div>';
      const rect = chart.canvas.getBoundingClientRect();
      const cx = tooltip.caretX != null ? tooltip.caretX : (tooltip.x != null ? tooltip.x : 0);
      const cy = tooltip.caretY != null ? tooltip.caretY : (tooltip.y != null ? tooltip.y : 0);
      const pad = 14;
      let left = rect.left + cx + pad;
      const top = rect.top + cy;
      el.style.transform = 'translate(0, -50%)';
      el.style.opacity = '1';
      requestAnimationFrame(function () {
        const w = el.offsetWidth || 200;
        if (left + w > window.innerWidth - 10) {
          left = rect.left + cx - w - pad;
        }
        el.style.left = Math.max(8, left) + 'px';
        el.style.top = top + 'px';
      });
    }

    const donutCtx = document.getElementById('statusDonut');
    const donutChartWrap = document.getElementById('donutChartWrap');

    function syncPillHighlightClasses() {
      document.querySelectorAll('.js-status-pill').forEach((pill) => {
        const idx = parseInt(pill.getAttribute('data-status-index') || '-1', 10);
        if (Number.isNaN(idx) || idx < 0) return;
        pill.classList.toggle('is-donut-hover', hoverSyncIndex === idx);
        pill.classList.toggle('is-donut-focused', focusIndex === idx);
        pill.classList.toggle('is-donut-slice-hover', hoverSyncIndex === idx && hoverSource === 'chart');
      });
    }

    function rebuildDonutOffsets() {
      const n = statusLabels.length;
      const arr = Array(n).fill(0);
      if (focusIndex !== null && focusIndex >= 0 && focusIndex < n) {
        arr[focusIndex] = 15;
      }
      return arr;
    }

    function donutSliceBackground(ctx) {
      const i = ctx.dataIndex;
      const pair = gradientStops[i % gradientStops.length];
      return segmentGradient(ctx, pair[0], pair[1]);
    }

    function applyDonutDatasetOffsets() {
      if (!statusChart) return;
      statusChart.data.datasets[0].offset = rebuildDonutOffsets();
    }

    function applyDonutActiveSlice(idx, source) {
      if (idx === null && hoverSyncIndex === null) return;
      if (idx !== null && hoverSyncIndex === idx) {
        if (source !== hoverSource) {
          hoverSource = source;
          syncPillHighlightClasses();
        }
        return;
      }
      if (idx === null) {
        hoverSyncIndex = null;
        hoverSource = null;
      } else {
        hoverSyncIndex = idx;
        hoverSource = source;
      }
      syncPillHighlightClasses();
      if (!statusChart) return;
      if (focusIndex !== null) {
        statusChart.update('none');
        return;
      }
      statusChart.setActiveElements(idx !== null && idx !== undefined ? [{ datasetIndex: 0, index: idx }] : []);
      statusChart.update();
    }

    function scheduleHoverClear() {
      if (hoverClearTimer) clearTimeout(hoverClearTimer);
      hoverClearTimer = setTimeout(function () {
        hoverClearTimer = null;
        if (pillHovering) return;
        if (focusIndex === null && statusChart && statusChart.getActiveElements().length > 0) return;
        if (focusIndex !== null && chartAreaHovering) return;
        applyDonutActiveSlice(null, null);
      }, 55);
    }

    function toggleDonutFocus(idx) {
      if (focusIndex === idx) {
        focusIndex = null;
      } else {
        focusIndex = idx;
      }
      applyDonutDatasetOffsets();
      if (statusChart) {
        if (focusIndex === null && hoverSyncIndex !== null) {
          statusChart.setActiveElements([{ datasetIndex: 0, index: hoverSyncIndex }]);
        } else {
          statusChart.setActiveElements([]);
        }
        statusChart.update();
      }
      syncPillHighlightClasses();
    }

    if (donutCtx) {
      statusChart = new Chart(donutCtx, {
        type: 'doughnut',
        data: {
          labels: statusLabels,
          datasets: [{
            data: statusCounts,
            backgroundColor: donutSliceBackground,
            hoverBackgroundColor: donutSliceBackground,
            borderColor: 'transparent',
            borderWidth: 0,
            hoverBorderWidth: 0,
            hoverBorderColor: 'transparent',
            borderRadius: 0,
            spacing: 1,
            hoverOffset: 15,
            offset: rebuildDonutOffsets()
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          rotation: -90,
          circumference: 360,
          cutout: '72%',
          layout: { padding: 26 },
          interaction: { mode: 'nearest', intersect: true },
          animation: {
            duration: 1000,
            easing: 'easeInOutExpo'
          },
          transitions: {
            active: {
              animation: {
                duration: 600,
                easing: 'easeInOutExpo'
              }
            }
          },
          onHover: function (event, elements) {
            if (elements.length) {
              if (hoverClearTimer) {
                clearTimeout(hoverClearTimer);
                hoverClearTimer = null;
              }
              applyDonutActiveSlice(elements[0].index, 'chart');
            } else {
              scheduleHoverClear();
            }
          },
          elements: {
            arc: {
              borderJoinStyle: 'round',
              borderWidth: 0,
              hoverBorderWidth: 0,
              hoverBorderColor: 'transparent'
            }
          },
          plugins: {
            legend: { display: false },
            tooltip: {
              enabled: false,
              external: donutExternalTooltip,
              intersect: true,
              callbacks: {
                title: function () {
                  return '';
                },
                label: function (ctx) {
                  const idx = ctx.dataIndex;
                  const val = ctx.raw !== undefined ? ctx.raw : (ctx.parsed !== undefined ? ctx.parsed : '');
                  return String(val) + ' · ' + String(statusPercentages[idx]) + '%';
                }
              }
            }
          }
        }
      });

      if (donutChartWrap) {
        donutChartWrap.addEventListener('mouseenter', function () {
          chartAreaHovering = true;
          if (hoverClearTimer) {
            clearTimeout(hoverClearTimer);
            hoverClearTimer = null;
          }
        });
        donutChartWrap.addEventListener('mouseleave', function () {
          chartAreaHovering = false;
          scheduleHoverClear();
        });
      }

      document.querySelectorAll('.js-status-pill').forEach((pill) => {
        const idx = parseInt(pill.getAttribute('data-status-index') || '-1', 10);
        if (Number.isNaN(idx) || idx < 0) return;
        pill.addEventListener('mouseenter', function () {
          pillHovering = true;
          if (hoverClearTimer) {
            clearTimeout(hoverClearTimer);
            hoverClearTimer = null;
          }
          applyDonutActiveSlice(idx, 'pill');
        });
        pill.addEventListener('mouseleave', function () {
          pillHovering = false;
          scheduleHoverClear();
        });
        pill.addEventListener('focus', function () {
          pillHovering = true;
          applyDonutActiveSlice(idx, 'pill');
        });
        pill.addEventListener('blur', function () {
          pillHovering = false;
          scheduleHoverClear();
        });
        pill.addEventListener('click', function (e) {
          e.preventDefault();
          toggleDonutFocus(idx);
        });
        pill.addEventListener('keydown', function (e) {
          if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            toggleDonutFocus(idx);
          }
        });
      });
    }

    const trendCtx = document.getElementById('absenceTrend');
    let trendChart = null;
    const trendSub = document.getElementById('trendSubtitle');

    function yMaxFor(counts) {
      const nums = counts.map((v) => Number(v));
      const mx = nums.length ? Math.max.apply(null, nums) : 0;
      if (mx <= 0) return 4;
      return Math.ceil(mx * 1.15 * 10) / 10;
    }

    function buildAreaGradient(ctx) {
      const chart = ctx.chart;
      const { ctx: c, chartArea } = chart;
      if (!chartArea) return 'rgba(59,130,246,0.35)';
      const g = c.createLinearGradient(0, chartArea.top, 0, chartArea.bottom);
      g.addColorStop(0, 'rgba(59,130,246,0.45)');
      g.addColorStop(0.35, 'rgba(59,130,246,0.18)');
      g.addColorStop(1, 'rgba(59,130,246,0)');
      return g;
    }

    if (trendCtx) {
      const initial = trends['7d'] || { labels: [], counts: [] };
      trendChart = new Chart(trendCtx, {
        type: 'line',
        data: {
          labels: initial.labels,
          datasets: [{
            label: uiTrend.notInOffice,
            data: initial.counts,
            tension: 0.35,
            cubicInterpolationMode: 'monotone',
            fill: true,
            backgroundColor: (ctx) => buildAreaGradient(ctx),
            borderColor: '#2563eb',
            borderWidth: 2.5,
            pointRadius: 0,
            pointHoverRadius: 5,
            pointHoverBackgroundColor: '#1d4ed8',
            pointHoverBorderColor: 'transparent',
            pointHoverBorderWidth: 0
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          interaction: { mode: 'index', intersect: false },
          layout: { padding: { top: 8, bottom: 4, left: 6, right: 8 } },
          scales: {
            y: {
              beginAtZero: true,
              suggestedMax: yMaxFor(initial.counts),
              ticks: { precision: 1, maxTicksLimit: 8 },
              title: { display: true, text: uiTrend.employees },
              grid: { color: 'rgba(148,163,184,0.22)' }
            },
            x: {
              offset: true,
              grid: { display: false },
              title: { display: true, text: uiTrend.axis7d }
            }
          },
          plugins: {
            legend: { display: false },
            tooltip: { enabled: true }
          }
        }
      });

      const rangeCopy = {
        '7d': uiTrend['7d'],
        '30d': uiTrend['30d'],
        '1y': uiTrend['1y']
      };
      const xTitle = {
        '7d': uiTrend.axis7d,
        '30d': uiTrend.axis30d,
        '1y': uiTrend.axis1y
      };

      function setTrendRange(key) {
        const t = trends[key];
        if (!trendChart || !t) return;
        trendChart.data.labels = t.labels;
        trendChart.data.datasets[0].data = t.counts;
        trendChart.options.scales.y.suggestedMax = yMaxFor(t.counts);
        trendChart.options.scales.x.title.text = xTitle[key] || '';
        if (trendSub) trendSub.textContent = rangeCopy[key] || '';
        trendChart.update();
      }

      document.querySelectorAll('.trend-range-btn').forEach((btn) => {
        btn.addEventListener('click', () => {
          const key = btn.getAttribute('data-range') || '7d';
          document.querySelectorAll('.trend-range-btn').forEach((b) => {
            b.classList.remove('tw-bg-white', 'tw-text-blue-800', 'tw-shadow-sm', 'tw-ring-1', 'tw-ring-slate-200/80');
            b.classList.add('tw-text-slate-600');
          });
          btn.classList.add('tw-bg-white', 'tw-text-blue-800', 'tw-shadow-sm', 'tw-ring-1', 'tw-ring-slate-200/80');
          btn.classList.remove('tw-text-slate-600');
          setTrendRange(key);
        });
      });
      if (trendSub) trendSub.textContent = rangeCopy['7d'] || '';
    }

    (function initDateNav() {
      const form = document.querySelector('form[action="/admin/dashboard"]');
      const input = document.getElementById('dateFilter');
      const prev = document.getElementById('prevDay');
      const next = document.getElementById('nextDay');
      if (!form || !input) return;
      function shift(days) {
        const v = input.value;
        if (!v) return;
        const d = new Date(v + 'T12:00:00');
        d.setDate(d.getDate() + days);
        const yyyy = d.getFullYear();
        const mm = String(d.getMonth() + 1).padStart(2, '0');
        const dd = String(d.getDate()).padStart(2, '0');
        input.value = `${yyyy}-${mm}-${dd}`;
        form.submit();
      }
      input.addEventListener('change', () => form.submit());
      prev && prev.addEventListener('click', () => shift(-1));
      next && next.addEventListener('click', () => shift(1));
    })();
  </script>
<?php include $viewsRoot . '/partials/admin-chrome-close.php'; ?>
