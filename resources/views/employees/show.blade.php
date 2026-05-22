<?php
$viewsRoot = dirname(__DIR__);
$e = $show['employee'] ?? [];
$name = (string) ($e['name'] ?? '');
$pageTitle = $name !== '' ? $name . ' — ' . __('employee') . ' — ' . __('app_name') : __('page_title_employee_show');
$activeNav = 'employees';
$shellClass = 'shell wide';
$badge = $show['badge'] ?? ['label' => '—', 'variant' => 'slate', 'bucket' => 'other_out'];
$badgeLabel = (string) ($badge['label'] ?? '—');
$v = (string) ($badge['variant'] ?? 'slate');
$bt = [
  'green' => 'tw-bg-emerald-50 tw-text-emerald-800 tw-ring-emerald-200',
  'blue' => 'tw-bg-sky-50 tw-text-sky-800 tw-ring-sky-200',
  'red' => 'tw-bg-rose-50 tw-text-rose-800 tw-ring-rose-200',
  'yellow' => 'tw-bg-amber-50 tw-text-amber-900 tw-ring-amber-200',
  'slate' => 'tw-bg-slate-100 tw-text-slate-800 tw-ring-slate-200',
  'violet' => 'tw-bg-violet-50 tw-text-violet-900 tw-ring-violet-200',
  'orange' => 'tw-bg-orange-50 tw-text-orange-900 tw-ring-orange-200',
];
$badgeClass = $bt[$v] ?? $bt['slate'];
$photo = (string) ($e['photo'] ?? '');
$position = trim((string) ($e['position'] ?? ''));
$category = trim((string) ($e['category'] ?? ''));
$divLine = $position !== '' ? $position : __('no_position');
if ($category !== '') {
    $divLine .= ' · ' . $category;
}
$nip = trim((string) ($e['nip'] ?? ''));
$initial = extension_loaded('mbstring') ? mb_substr($name, 0, 1, 'UTF-8') : substr($name, 0, 1);
$initial = $initial !== '' ? $initial : '?';
include $viewsRoot . '/partials/admin-chrome-open.php';
?>
<div class="tw-space-y-6">
  <a class="tw-inline-flex tw-items-center tw-gap-2 tw-text-sm tw-font-bold tw-text-blue-700 tw-underline tw-decoration-blue-300 tw-underline-offset-4 hover:tw-text-blue-900" href="/admin/employees">← <?php echo htmlspecialchars(__('back_directory')); ?></a>

  <div class="tw-relative tw-overflow-hidden tw-rounded-2xl tw-border tw-border-white/50 tw-bg-white/72 tw-p-6 tw-backdrop-blur-xl tw-shadow-[0_20px_50px_rgba(8,112,184,0.12)] sm:tw-flex sm:tw-items-center sm:tw-gap-8 sm:tw-p-8">
    <div class="tw-pointer-events-none tw-absolute tw--right-16 tw--top-20 tw-h-40 tw-w-40 tw-rounded-full tw-bg-blue-400/15 tw-blur-3xl" aria-hidden="true"></div>
    <div class="tw-relative tw-mx-auto tw-mb-5 tw-h-24 tw-w-24 tw-shrink-0 tw-overflow-hidden tw-rounded-2xl tw-border tw-border-white/60 tw-bg-white tw-shadow-[0_16px_40px_rgba(8,112,184,0.15)] sm:tw-mx-0 sm:tw-mb-0 sm:tw-h-28 sm:tw-w-28">
      <?php if ($photo !== ''): ?>
        <img class="tw-h-full tw-w-full tw-object-cover" src="<?php echo htmlspecialchars($photo); ?>" alt="" width="112" height="112" loading="lazy" decoding="async">
      <?php else: ?>
        <div class="tw-flex tw-h-full tw-w-full tw-items-center tw-justify-center tw-bg-gradient-to-br tw-from-blue-100 tw-to-slate-100 tw-text-3xl tw-font-black tw-text-blue-800"><?php echo htmlspecialchars($initial); ?></div>
      <?php endif; ?>
    </div>
    <div class="tw-relative tw-min-w-0 tw-flex-1 tw-text-center sm:tw-text-left">
      <h1 class="tw-m-0 tw-text-2xl tw-font-black tw-tracking-tight tw-text-slate-900 sm:tw-text-3xl"><?php echo htmlspecialchars($name); ?></h1>
      <p class="tw-m-0 tw-mt-1 tw-text-sm tw-font-semibold tw-text-slate-600"><?php echo htmlspecialchars($divLine); ?></p>
      <?php if ($nip !== ''): ?>
        <p class="tw-m-0 tw-mt-1 tw-text-xs tw-font-medium tw-tabular-nums tw-text-slate-500">NIP <?php echo htmlspecialchars($nip); ?></p>
      <?php endif; ?>
      <div class="tw-mt-4 tw-flex tw-flex-wrap tw-items-center tw-justify-center tw-gap-2 sm:tw-justify-start">
        <span class="tw-text-[0.68rem] tw-font-extrabold tw-uppercase tw-tracking-wider tw-text-slate-500"><?php echo htmlspecialchars(__('current_status')); ?></span>
        <span class="tw-inline-flex tw-items-center tw-rounded-full tw-px-3 tw-py-0.5 tw-text-xs tw-font-bold tw-ring-1 tw-ring-inset <?php echo htmlspecialchars($badgeClass); ?>"><?php echo htmlspecialchars($badgeLabel); ?></span>
      </div>
      <p class="tw-m-0 tw-mt-3 tw-text-sm tw-text-slate-700"><span class="tw-font-bold tw-text-slate-900"><?php echo htmlspecialchars(__('last_absence_label')); ?></span> <?php echo htmlspecialchars($show['last_absence'] ?? '—'); ?></p>
    </div>
  </div>

  <div class="tw-grid tw-grid-cols-1 tw-gap-3 sm:tw-grid-cols-3">
    <div class="tw-rounded-2xl tw-border tw-border-white/50 tw-bg-white/65 tw-px-4 tw-py-4 tw-backdrop-blur-xl tw-shadow-[0_16px_40px_rgba(8,112,184,0.08)] sm:tw-px-5 sm:tw-py-5">
      <p class="tw-m-0 tw-text-[0.68rem] tw-font-bold tw-uppercase tw-tracking-wider tw-text-slate-500"><?php echo htmlspecialchars(__('this_week_cap')); ?></p>
      <p class="tw-m-0 tw-mt-2 tw-text-2xl tw-font-black tw-tabular-nums tw-text-slate-900"><?php echo (int) ($show['absence_week'] ?? 0); ?> <span class="tw-text-sm tw-font-semibold tw-text-slate-600"><?php echo htmlspecialchars((int) ($show['absence_week'] ?? 0) === 1 ? __('day') : __('days')); ?></span></p>
    </div>
    <div class="tw-rounded-2xl tw-border tw-border-white/50 tw-bg-white/65 tw-px-4 tw-py-4 tw-backdrop-blur-xl tw-shadow-[0_16px_40px_rgba(8,112,184,0.08)] sm:tw-px-5 sm:tw-py-5">
      <p class="tw-m-0 tw-text-[0.68rem] tw-font-bold tw-uppercase tw-tracking-wider tw-text-slate-500"><?php echo htmlspecialchars(__('this_month_cap')); ?></p>
      <p class="tw-m-0 tw-mt-2 tw-text-2xl tw-font-black tw-tabular-nums tw-text-slate-900"><?php echo (int) ($show['absence_month'] ?? 0); ?> <span class="tw-text-sm tw-font-semibold tw-text-slate-600"><?php echo htmlspecialchars((int) ($show['absence_month'] ?? 0) === 1 ? __('day') : __('days')); ?></span></p>
    </div>
    <div class="tw-rounded-2xl tw-border tw-border-white/50 tw-bg-white/65 tw-px-4 tw-py-4 tw-backdrop-blur-xl tw-shadow-[0_16px_40px_rgba(8,112,184,0.08)] sm:tw-px-5 sm:tw-py-5">
      <p class="tw-m-0 tw-text-[0.68rem] tw-font-bold tw-uppercase tw-tracking-wider tw-text-slate-500"><?php echo htmlspecialchars(__('this_year_cap')); ?></p>
      <p class="tw-m-0 tw-mt-2 tw-text-2xl tw-font-black tw-tabular-nums tw-text-slate-900"><?php echo (int) ($show['absence_year'] ?? 0); ?> <span class="tw-text-sm tw-font-semibold tw-text-slate-600"><?php echo htmlspecialchars((int) ($show['absence_year'] ?? 0) === 1 ? __('day') : __('days')); ?></span></p>
    </div>
  </div>

  <div class="tw-overflow-hidden tw-rounded-2xl tw-border tw-border-white/50 tw-bg-white/68 tw-backdrop-blur-xl tw-shadow-[0_20px_50px_rgba(8,112,184,0.1)]">
    <h2 class="tw-m-0 tw-border-b tw-border-slate-200/80 tw-px-5 tw-py-4 tw-text-lg tw-font-extrabold tw-tracking-tight tw-text-slate-900 sm:tw-px-6"><?php echo htmlspecialchars(__('absence_log')); ?></h2>
    <div class="pm-table-wrap pm-table-wrap--flush pm-table-wrap--absence-log">
      <table class="pm-table pm-table--absence-log">
        <colgroup>
          <col class="pm-col-date">
          <col class="pm-col-status">
          <col class="pm-col-note">
          <col class="pm-col-proofs">
        </colgroup>
        <thead>
          <tr>
            <th><?php echo htmlspecialchars(__('date')); ?></th>
            <th><?php echo htmlspecialchars(__('status')); ?></th>
            <th><?php echo htmlspecialchars(__('note')); ?></th>
            <th class="pm-col-proofs-head"><?php echo htmlspecialchars(__('proofs')); ?></th>
          </tr>
        </thead>
        <tbody>
          <?php
            $rows = $show['logs'] ?? [];
          if (count($rows) === 0) {
              echo '<tr><td colspan="4" class="tw-py-8 tw-text-center tw-text-sm tw-text-slate-600">' . htmlspecialchars(__('no_log_entries'), ENT_QUOTES, 'UTF-8') . '</td></tr>';
          } else {
              foreach ($rows as $L) {
                  $hasProof = !empty($L['has_proof']);
                  $viewU = (string) ($L['proof_view_url'] ?? '');
                  $dlU = (string) ($L['proof_download_url'] ?? '');
                  $ext = (string) ($L['proof_ext'] ?? '');
                  echo '<tr>';
                  echo '<td class="tw-font-semibold tw-text-slate-800">' . htmlspecialchars($L['date_label'] ?? '—', ENT_QUOTES, 'UTF-8') . '</td>';
                  echo '<td>' . htmlspecialchars($L['status'] ?? '—', ENT_QUOTES, 'UTF-8') . '</td>';
                  echo '<td class="pm-col-note-cell tw-text-slate-600">' . htmlspecialchars($L['note'] ?? '—', ENT_QUOTES, 'UTF-8') . '</td>';
                  echo '<td class="pm-col-proofs-cell">';
                  if ($hasProof && $viewU !== '') {
                      echo '<button type="button" class="pm-proof-btn tw-inline-flex tw-items-center tw-justify-center tw-gap-1.5 tw-whitespace-nowrap tw-rounded-xl tw-border tw-border-sky-200/80 tw-bg-sky-50 tw-px-2.5 tw-py-1.5 tw-text-xs tw-font-bold tw-text-sky-700 tw-shadow-sm tw-transition hover:tw-border-sky-300 hover:tw-bg-sky-100 hover:tw-text-sky-900 focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-sky-300 sm:tw-px-3" data-proof-url="' . htmlspecialchars($viewU, ENT_QUOTES, 'UTF-8') . '" data-proof-dl="' . htmlspecialchars($dlU, ENT_QUOTES, 'UTF-8') . '" data-proof-ext="' . htmlspecialchars($ext, ENT_QUOTES, 'UTF-8') . '" title="' . htmlspecialchars(__('view_document'), ENT_QUOTES, 'UTF-8') . '" aria-label="' . htmlspecialchars(__('view_document'), ENT_QUOTES, 'UTF-8') . '">';
                      echo '<svg class="tw-shrink-0" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M10 9H8"/><path d="M16 13H8"/><path d="M16 17H8"/></svg>';
                      echo '<span class="tw-whitespace-nowrap">' . htmlspecialchars(__('view_document'), ENT_QUOTES, 'UTF-8') . '</span>';
                      echo '</button>';
                  } else {
                      echo '<span class="tw-text-sm tw-font-medium tw-text-slate-300">—</span>';
                  }
                  echo '</td>';
                  echo '</tr>';
              }
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<div id="proofModal" class="tw-pointer-events-none tw-fixed tw-inset-0 tw-z-[200] tw-flex tw-items-center tw-justify-center tw-p-4 tw-opacity-0 tw-transition tw-duration-200" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="proofModalTitle">
  <div id="proofModalBackdrop" class="tw-absolute tw-inset-0 tw-bg-slate-900/45 tw-backdrop-blur-sm"></div>
  <div class="tw-relative tw-flex tw-max-h-[min(90vh,860px)] tw-w-full tw-max-w-3xl tw-flex-col tw-overflow-hidden tw-rounded-2xl tw-border tw-border-white/40 tw-bg-white/35 tw-shadow-[0_24px_80px_rgba(15,23,42,0.25)] tw-backdrop-blur-2xl">
    <div class="tw-flex tw-items-center tw-justify-between tw-gap-3 tw-border-b tw-border-white/30 tw-px-5 tw-py-4 sm:tw-px-6">
      <h3 id="proofModalTitle" class="tw-m-0 tw-text-base tw-font-extrabold tw-tracking-tight tw-text-slate-900"><?php echo htmlspecialchars(__('proof_modal_title')); ?></h3>
      <div class="tw-flex tw-items-center tw-gap-2">
        <a id="proofModalDownload" href="#" class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-border tw-border-sky-200 tw-bg-sky-50 tw-px-3 tw-py-2 tw-text-xs tw-font-bold tw-text-sky-800 tw-no-underline tw-shadow-sm hover:tw-bg-sky-100"><?php echo htmlspecialchars(__('download')); ?></a>
        <button type="button" id="proofModalClose" class="tw-inline-flex tw-h-9 tw-w-9 tw-items-center tw-justify-center tw-rounded-xl tw-border tw-border-slate-200 tw-bg-white/60 tw-text-slate-600 hover:tw-bg-white" aria-label="<?php echo htmlspecialchars(__('close')); ?>">&times;</button>
      </div>
    </div>
    <div class="tw-min-h-0 tw-flex-1 tw-bg-slate-900/5 tw-p-4 sm:tw-p-6">
      <div id="proofModalBody" class="tw-flex tw-h-[min(72vh,640px)] tw-items-center tw-justify-center tw-overflow-auto tw-rounded-xl tw-border tw-border-white/30 tw-bg-white/50 tw-p-2"></div>
    </div>
  </div>
</div>

<script>
(function () {
  const i18n = <?php echo json_encode([
      'pdfProof' => __('pdf_proof'),
      'proofImage' => __('proof_image'),
      'pdfPreviewUnavailable' => __('pdf_preview_unavailable'),
      'openNewTab' => __('open_new_tab'),
  ], JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
  const modal = document.getElementById('proofModal');
  const backdrop = document.getElementById('proofModalBackdrop');
  const body = document.getElementById('proofModalBody');
  const closeBtn = document.getElementById('proofModalClose');
  const dl = document.getElementById('proofModalDownload');
  if (!modal || !body || !closeBtn || !dl) return;

  function openModal(url, downloadUrl, ext) {
    body.innerHTML = '';
    dl.href = downloadUrl;
    if (ext === 'pdf') {
      const objectEl = document.createElement('object');
      objectEl.type = 'application/pdf';
      objectEl.data = url;
      objectEl.className = 'tw-h-full tw-min-h-[min(70vh,600px)] tw-w-full tw-rounded-lg tw-border-0 tw-bg-white';
      objectEl.setAttribute('aria-label', i18n.pdfProof);
      const fallback = document.createElement('p');
      fallback.className = 'tw-p-4 tw-text-center tw-text-sm tw-text-slate-600';
      fallback.innerHTML = i18n.pdfPreviewUnavailable + ' <a class="tw-font-bold tw-text-blue-700 tw-underline" href="' + url + '" target="_blank" rel="noopener">' + i18n.openNewTab + '</a>.';
      objectEl.appendChild(fallback);
      body.appendChild(objectEl);
    } else {
      const img = document.createElement('img');
      img.src = url;
      img.alt = i18n.proofImage;
      img.className = 'tw-max-h-full tw-max-w-full tw-rounded-lg tw-object-contain tw-shadow-sm';
      body.appendChild(img);
    }
    modal.classList.remove('tw-pointer-events-none', 'tw-opacity-0');
    modal.classList.add('tw-opacity-100');
    modal.setAttribute('aria-hidden', 'false');
    closeBtn.focus();
  }

  function closeModal() {
    body.innerHTML = '';
    dl.removeAttribute('href');
    modal.classList.add('tw-pointer-events-none', 'tw-opacity-0');
    modal.classList.remove('tw-opacity-100');
    modal.setAttribute('aria-hidden', 'true');
  }

  document.querySelectorAll('.pm-proof-btn').forEach((btn) => {
    btn.addEventListener('click', () => {
      const url = btn.getAttribute('data-proof-url') || '';
      const downloadUrl = btn.getAttribute('data-proof-dl') || '';
      const ext = (btn.getAttribute('data-proof-ext') || '').toLowerCase();
      if (!url) return;
      openModal(url, downloadUrl, ext);
    });
  });

  closeBtn.addEventListener('click', closeModal);
  backdrop?.addEventListener('click', closeModal);
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && modal.getAttribute('aria-hidden') === 'false') closeModal();
  });
})();
</script>
<?php
include $viewsRoot . '/partials/admin-chrome-close.php';
