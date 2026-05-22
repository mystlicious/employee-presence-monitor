<!doctype html>
<html lang="<?php echo htmlspecialchars(app_lang_attr()); ?>">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title><?php echo htmlspecialchars(__('page_title_input_form')); ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
  <?php include __DIR__ . '/partials/pm-design-snippet.php'; ?>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = { prefix: 'tw-' };
  </script>
  <style>
    /* Input page: mesh stays in background; surfaces are opaque for readability */
    body.pm-input-page {
      min-height: 100vh;
    }
    body.pm-input-page::before {
      opacity: 0.55;
    }
    body.pm-input-page::after {
      opacity: 0.035;
    }
    .pm-shell {
      position: relative;
      z-index: 1;
    }
    .pm-card {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(28px);
      -webkit-backdrop-filter: blur(28px);
      border: 1px solid rgb(226 232 240);
      box-shadow:
        0 1px 2px rgba(15, 23, 42, 0.04),
        0 12px 40px rgba(15, 23, 42, 0.08);
    }
    .pm-accent-strip {
      height: 3px;
      width: 100%;
      background: linear-gradient(90deg, #2563eb, #7c3aed, #0ea5e9);
      flex-shrink: 0;
    }
    .pm-float {
      position: relative;
    }
    .pm-float--search .pm-float-input {
      padding-left: 3rem;
    }
    .pm-float--search .pm-float-label {
      left: 3rem;
    }
    /* Keep floated label aligned with text, not over the search icon */
    .pm-float--search .pm-float-input:focus + .pm-float-label,
    .pm-float--search .pm-float-input:not(:placeholder-shown) + .pm-float-label {
      left: 3rem;
    }
    .pm-float-input {
      display: block;
      width: 100%;
      border-radius: 0.875rem;
      border: 1px solid rgb(203 213 225);
      background: #ffffff;
      padding: 1.35rem 0.85rem 0.45rem;
      font-size: 0.9375rem;
      font-weight: 500;
      color: #0f172a;
      box-shadow: inset 0 1px 2px rgba(15, 23, 42, 0.04);
      transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }
    .pm-float-input:focus {
      outline: none;
      border-color: #3b82f6;
      box-shadow:
        inset 0 1px 2px rgba(15, 23, 42, 0.04),
        0 0 0 3px rgba(59, 130, 246, 0.2);
    }
    .pm-float-label {
      pointer-events: none;
      position: absolute;
      left: 0.85rem;
      top: 50%;
      transform: translateY(-50%);
      font-size: 0.9375rem;
      font-weight: 600;
      color: #64748b;
      transition: top 0.18s ease, transform 0.18s ease, font-size 0.18s ease, color 0.18s ease;
      transform-origin: left center;
    }
    .pm-float-input:focus + .pm-float-label,
    .pm-float-input:not(:placeholder-shown) + .pm-float-label {
      top: 0.55rem;
      transform: translateY(0) scale(0.72);
      color: #3b82f6;
    }
    .pm-float--tall .pm-float-input {
      min-height: 6.5rem;
      padding-top: 1.6rem;
      resize: vertical;
    }
    .pm-float--tall .pm-float-label {
      top: 1.15rem;
      transform: translateY(0);
    }
    .pm-float--tall .pm-float-input:focus + .pm-float-label,
    .pm-float--tall .pm-float-input:not(:placeholder-shown) + .pm-float-label {
      top: 0.55rem;
      transform: scale(0.72);
    }
    .pm-match-list {
      position: absolute;
      left: 0;
      right: 0;
      top: calc(100% + 6px);
      z-index: 30;
      max-height: 220px;
      overflow: auto;
      display: none;
      border-radius: 0.875rem;
      border: 1px solid rgb(191 219 254);
      background: #ffffff;
      box-shadow: 0 18px 48px rgba(15, 23, 42, 0.12), 0 0 0 1px rgba(255, 255, 255, 0.8) inset;
    }
    .pm-match-item {
      padding: 0.6rem 0.75rem;
      cursor: pointer;
      font-size: 0.9rem;
      color: #0f172a;
      border-bottom: 1px solid #eef4ff;
    }
    .pm-match-item:last-child {
      border-bottom: none;
    }
    .pm-match-item:hover,
    .pm-match-item.active {
      background: #eef4ff;
    }
    .pm-match-item mark {
      background: #fde68a;
      padding: 0 2px;
      border-radius: 3px;
    }
    .pm-status-chip {
      position: relative;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.4rem;
      min-height: 3rem;
      padding: 0.65rem 0.75rem;
      border-radius: 0.5rem;
      border: 1px solid rgb(226 232 240);
      background: rgb(255 255 255);
      font-size: 0.8125rem;
      font-weight: 700;
      color: #1e293b;
      cursor: pointer;
      text-align: center;
      line-height: 1.25;
      transition: transform 0.15s ease, border-color 0.15s ease, background 0.15s ease, box-shadow 0.15s ease, color 0.15s ease;
    }
    @media (min-width: 480px) {
      .pm-status-chip {
        font-size: 0.875rem;
        padding: 0.75rem 0.85rem;
        min-height: 3.25rem;
      }
    }
    .pm-status-chip:hover {
      transform: translateY(-2px);
      border-color: rgb(191 219 254);
      background: rgb(248 250 252);
      box-shadow: 0 4px 12px rgba(15, 23, 42, 0.06);
    }
    .pm-status-chip.is-active {
      background: rgb(239 246 255);
      border-color: rgb(59 130 246);
      color: rgb(30 64 175);
      box-shadow: 0 0 0 1px rgba(59, 130, 246, 0.15), 0 6px 16px rgba(37, 99, 235, 0.12);
    }
    .pm-status-chip .pm-status-check {
      display: none;
      flex-shrink: 0;
      color: rgb(37 99 235);
    }
    .pm-status-chip.is-active .pm-status-check {
      display: block;
    }
  </style>
</head>
<body class="pm-app pm-mesh-bg pm-input-page">
  <main class="pm-shell tw-mx-auto tw-w-full tw-max-w-xl tw-px-4 tw-py-6 sm:tw-px-8 sm:tw-py-12">
    <header class="pm-card tw-mb-5 tw-flex tw-flex-col tw-overflow-hidden tw-rounded-2xl">
      <div class="pm-accent-strip" aria-hidden="true"></div>
      <div class="tw-px-5 tw-py-5 sm:tw-px-6">
        <p class="tw-m-0 tw-text-[0.72rem] tw-font-extrabold tw-uppercase tw-tracking-[0.14em] tw-text-slate-500"><?php echo htmlspecialchars(__('presence_monitoring')); ?></p>
        <h1 class="tw-m-0 tw-mt-2 tw-text-2xl tw-font-black tw-tracking-tight tw-text-slate-900 sm:tw-text-3xl"><?php echo htmlspecialchars(__('input_presence')); ?></h1>
        <p class="tw-m-0 tw-mt-2 tw-text-sm tw-leading-relaxed tw-text-slate-600"><?php echo htmlspecialchars(__('input_presence_lede')); ?></p>
      </div>
    </header>

    <form action="/input-form" method="POST" enctype="multipart/form-data" class="pm-card tw-rounded-2xl tw-px-5 tw-py-6 tw-backdrop-blur-xl sm:tw-px-7 sm:tw-py-8" id="presenceForm" novalidate>
      <?php if (!empty($_GET['error'])): ?>
        <div class="tw-mb-5 tw-rounded-xl tw-border tw-border-rose-200 tw-bg-rose-50 tw-px-3 tw-py-2.5 tw-text-sm tw-font-semibold tw-text-rose-800"><?php echo htmlspecialchars($_GET['error']); ?></div>
      <?php endif; ?>

      <div class="tw-space-y-8">
        <div class="tw-relative">
          <p class="tw-mb-2 tw-text-[0.7rem] tw-font-extrabold tw-uppercase tw-tracking-wider tw-text-slate-800"><?php echo htmlspecialchars(__('employee')); ?></p>
          <div class="employee-picker tw-relative">
            <div class="pm-float pm-float--search">
              <svg class="tw-pointer-events-none tw-absolute tw-left-3.5 tw-top-1/2 tw-z-10 tw-h-5 tw-w-5 tw--translate-y-1/2 tw-text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <circle cx="11" cy="11" r="8"/><path d="M21 21l-4.3-4.3"/>
              </svg>
              <input id="employeeSearch" class="pm-float-input" type="text" placeholder=" " autocomplete="off" required>
              <label class="pm-float-label" for="employeeSearch"><?php echo htmlspecialchars(__('search_select_employee')); ?></label>
            </div>
            <input type="hidden" name="employee_name" id="employeeNameField" value="">
            <div id="employeeMatches" class="pm-match-list" role="listbox" aria-label="<?php echo htmlspecialchars(__('employee_suggestions')); ?>"></div>
          </div>
          <p class="tw-mt-2 tw-text-xs tw-font-medium tw-text-slate-500" id="employeeState"><?php echo htmlspecialchars(__('start_typing')); ?></p>
        </div>

        <div>
          <p class="tw-mb-3 tw-text-[0.7rem] tw-font-extrabold tw-uppercase tw-tracking-wider tw-text-slate-800"><?php echo htmlspecialchars(__('status')); ?></p>
          <div
            id="statusSegment"
            class="tw-grid tw-grid-cols-2 tw-gap-2.5 sm:tw-grid-cols-3"
            role="radiogroup"
            aria-label="<?php echo htmlspecialchars(__('presence_status')); ?>"
          >
            <?php foreach ($statuses as $s): ?>
              <button type="button" class="pm-status-chip" data-status="<?php echo htmlspecialchars($s); ?>" aria-pressed="false">
                <span><?php echo htmlspecialchars($s); ?></span>
                <svg class="pm-status-check tw-h-4 tw-w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                  <path d="M20 6 9 17l-5-5"/>
                </svg>
              </button>
            <?php endforeach; ?>
          </div>
          <input type="hidden" name="status" id="statusField" value="">
          <p id="statusClientError" class="tw-mt-2 tw-hidden tw-text-xs tw-font-semibold tw-text-rose-600" role="alert"></p>
          <p class="tw-mt-3 tw-text-xs tw-leading-snug tw-text-slate-600"><span class="tw-font-semibold tw-text-slate-800">Izin Keluar</span> <?php echo htmlspecialchars(__('izin_keluar_hint')); ?></p>
        </div>

        <div class="tw-space-y-3">
          <div>
            <p class="tw-mb-2 tw-text-[0.7rem] tw-font-extrabold tw-uppercase tw-tracking-wider tw-text-slate-800"><?php echo htmlspecialchars(__('location')); ?></p>
            <div class="pm-float">
              <input id="locationField" class="pm-float-input" type="text" name="location" placeholder=" " required>
              <label class="pm-float-label" for="locationField"><?php echo htmlspecialchars(__('location_destination')); ?></label>
            </div>
          </div>
          <div>
            <p class="tw-mb-2 tw-text-[0.7rem] tw-font-extrabold tw-uppercase tw-tracking-wider tw-text-slate-800"><?php echo htmlspecialchars(__('date')); ?></p>
            <input id="logDateField" type="date" name="log_date" class="tw-w-full tw-rounded-xl tw-border tw-border-slate-300 tw-bg-white tw-px-3 tw-py-2.5 tw-text-sm tw-font-semibold tw-text-slate-900 tw-shadow-[inset_0_1px_2px_rgba(15,23,42,0.04)] focus:tw-border-blue-500 focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-blue-200" value="<?php echo htmlspecialchars($selectedDate); ?>" required>
            <p class="tw-mt-2 tw-text-xs tw-text-slate-500"><?php echo htmlspecialchars(__('submitted_auto')); ?></p>
          </div>
        </div>

        <div id="timeWindowRow" class="tw-hidden tw-grid tw-grid-cols-1 tw-gap-4 sm:tw-grid-cols-2">
          <div>
            <p class="tw-mb-2 tw-text-[0.7rem] tw-font-extrabold tw-uppercase tw-tracking-wider tw-text-slate-800"><?php echo htmlspecialchars(__('start_time')); ?></p>
            <input type="time" name="start_time" id="startTimeField" class="tw-w-full tw-rounded-xl tw-border tw-border-slate-300 tw-bg-white tw-px-3 tw-py-2.5 tw-font-mono tw-text-sm tw-font-semibold tw-text-slate-900 tw-shadow-[inset_0_1px_2px_rgba(15,23,42,0.04)] focus:tw-border-blue-500 focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-blue-200">
          </div>
          <div>
            <p class="tw-mb-2 tw-text-[0.7rem] tw-font-extrabold tw-uppercase tw-tracking-wider tw-text-slate-800"><?php echo htmlspecialchars(__('end_time')); ?></p>
            <input type="time" name="end_time" id="endTimeField" class="tw-w-full tw-rounded-xl tw-border tw-border-slate-300 tw-bg-white tw-px-3 tw-py-2.5 tw-font-mono tw-text-sm tw-font-semibold tw-text-slate-900 tw-shadow-[inset_0_1px_2px_rgba(15,23,42,0.04)] focus:tw-border-blue-500 focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-blue-200">
          </div>
        </div>

        <div id="proofUploadRow" class="tw-hidden tw-space-y-2">
          <p class="tw-mb-2 tw-text-[0.7rem] tw-font-extrabold tw-uppercase tw-tracking-wider tw-text-slate-800"><?php echo htmlspecialchars(__('upload_proof')); ?></p>
          <label class="tw-flex tw-cursor-pointer tw-flex-col tw-gap-2 tw-rounded-xl tw-border tw-border-dashed tw-border-slate-300 tw-bg-slate-50/80 tw-px-4 tw-py-4 tw-transition hover:tw-border-blue-300 hover:tw-bg-sky-50/60">
            <span class="tw-text-sm tw-font-semibold tw-text-slate-700"><?php echo htmlspecialchars(__('proof_file_hint')); ?></span>
            <input id="proofFile" type="file" name="proof" accept="application/pdf,image/jpeg,image/png,.pdf,.jpg,.jpeg,.png" class="tw-text-sm tw-font-medium tw-text-slate-800 file:tw-mr-3 file:tw-rounded-lg file:tw-border-0 file:tw-bg-blue-600 file:tw-px-3 file:tw-py-2 file:tw-text-xs file:tw-font-bold file:tw-text-white hover:file:tw-bg-blue-700">
          </label>
          <p class="tw-text-xs tw-text-slate-500"><?php echo htmlspecialchars(__('proof_optional_hint')); ?></p>
          <p id="proofFileError" class="tw-hidden tw-text-xs tw-font-semibold tw-text-rose-600" role="alert"></p>
        </div>

        <div class="pm-float pm-float--tall">
          <textarea id="noteField" name="note" rows="3" class="pm-float-input" placeholder=" "></textarea>
          <label class="pm-float-label" for="noteField"><?php echo htmlspecialchars(__('note_optional')); ?></label>
        </div>

        <button class="tw-w-full tw-rounded-xl tw-border tw-border-blue-800/80 tw-bg-gradient-to-br tw-from-blue-600 tw-to-indigo-700 tw-py-4 tw-text-sm tw-font-extrabold tw-tracking-wide tw-text-white tw-shadow-[0_14px_40px_rgba(37,99,235,0.45),0_0_48px_-6px_rgba(59,130,246,0.4)] tw-transition hover:tw--translate-y-0.5 hover:tw-brightness-[1.03] hover:tw-shadow-[0_18px_48px_rgba(37,99,235,0.5),0_0_56px_-4px_rgba(59,130,246,0.38)] active:tw-translate-y-0" type="submit"><?php echo htmlspecialchars(__('submit_presence')); ?></button>
      </div>
    </form>
  </main>

  <script>
    const i18n = <?php echo json_encode([
        'startTyping' => __('start_typing'),
        'noMatchingEmployee' => __('no_matching_employee'),
        'noMatchYet' => __('no_match_yet'),
        'foundMatches' => __('found_matches'),
        'selected' => __('selected'),
        'selectFromList' => __('select_from_list'),
        'chooseStatus' => __('choose_status'),
        'employeeMustSelect' => __('employee_must_select_list'),
        'proofTooLarge' => __('proof_too_large'),
        'proofTypeError' => __('proof_type_error'),
    ], JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
    const employeeOptions = <?php echo json_encode(array_values($employees), JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
    const employeeSearch = document.getElementById('employeeSearch');
    const employeeNameField = document.getElementById('employeeNameField');
    const employeeMatches = document.getElementById('employeeMatches');
    const employeeState = document.getElementById('employeeState');
    const statusField = document.getElementById('statusField');
    const statusSegment = document.getElementById('statusSegment');
    const statusClientError = document.getElementById('statusClientError');

    function escapeHtml(s) {
      return String(s)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#39;');
    }

    function setEmployeeState(msg, isError) {
      employeeState.textContent = msg;
      employeeState.classList.toggle('tw-text-rose-600', !!isError);
      employeeState.classList.toggle('tw-text-slate-500', !isError);
    }

    function highlightMatch(name, query) {
      if (!query) return escapeHtml(name);
      const lowerName = name.toLowerCase();
      const lowerQuery = query.toLowerCase();
      const idx = lowerName.indexOf(lowerQuery);
      if (idx < 0) return escapeHtml(name);
      const before = escapeHtml(name.slice(0, idx));
      const hit = escapeHtml(name.slice(idx, idx + query.length));
      const after = escapeHtml(name.slice(idx + query.length));
      return `${before}<mark>${hit}</mark>${after}`;
    }

    function selectedFromText(value) {
      const exact = employeeOptions.find((n) => n.toLowerCase() === value.trim().toLowerCase());
      if (exact) {
        employeeNameField.value = exact;
        employeeSearch.value = exact;
        setEmployeeState(i18n.selected.replace(':name', exact), false);
      } else {
        employeeNameField.value = '';
      }
      return exact;
    }

    function hideMatches() {
      employeeMatches.style.display = 'none';
      employeeMatches.innerHTML = '';
    }

    function renderMatches(query) {
      const q = query.trim();
      if (!q) {
        hideMatches();
        setEmployeeState(i18n.startTyping, false);
        return;
      }
      const filtered = employeeOptions
        .filter((name) => name.toLowerCase().includes(q.toLowerCase()))
        .slice(0, 8);

      if (!filtered.length) {
        employeeMatches.innerHTML = '<div class="pm-match-item">' + escapeHtml(i18n.noMatchingEmployee) + '</div>';
        employeeMatches.style.display = 'block';
        setEmployeeState(i18n.noMatchYet, true);
        return;
      }

      employeeMatches.innerHTML = filtered
        .map((name, i) => `<div class="pm-match-item${i === 0 ? ' active' : ''}" data-name="${escapeHtml(name)}">${highlightMatch(name, q)}</div>`)
        .join('');
      employeeMatches.style.display = 'block';
      setEmployeeState(i18n.foundMatches.replace(':n', String(filtered.length)), false);
    }

    employeeSearch?.addEventListener('input', (e) => {
      const value = e.target.value;
      selectedFromText(value);
      renderMatches(value);
    });

    employeeSearch?.addEventListener('focus', () => {
      renderMatches(employeeSearch.value);
    });

    employeeSearch?.addEventListener('blur', () => {
      setTimeout(() => {
        selectedFromText(employeeSearch.value);
        hideMatches();
        if (!employeeNameField.value && employeeSearch.value.trim() !== '') {
          setEmployeeState(i18n.selectFromList, true);
        }
      }, 120);
    });

    employeeMatches?.addEventListener('mousedown', (e) => {
      const target = e.target.closest('.pm-match-item[data-name]');
      if (!target) return;
      const raw = target.getAttribute('data-name');
      const name = raw
        .replace(/&quot;/g, '"')
        .replace(/&#39;/g, "'")
        .replace(/&lt;/g, '<')
        .replace(/&gt;/g, '>')
        .replace(/&amp;/g, '&');
      employeeSearch.value = name;
      employeeNameField.value = name;
      setEmployeeState(i18n.selected.replace(':name', name), false);
      hideMatches();
      e.preventDefault();
    });

    function setStatusValue(value) {
      if (!statusField || !statusSegment) return;
      statusField.value = value;
      if (statusClientError) {
        statusClientError.textContent = '';
        statusClientError.classList.add('tw-hidden');
      }
      statusSegment.querySelectorAll('.pm-status-chip').forEach((btn) => {
        const v = btn.getAttribute('data-status') || '';
        btn.classList.toggle('is-active', v === value);
        btn.setAttribute('aria-pressed', v === value ? 'true' : 'false');
      });
      updateTimeWindowVisibility();
      updateProofUploadVisibility();
    }

    function updateProofUploadVisibility() {
      const row = document.getElementById('proofUploadRow');
      const input = document.getElementById('proofFile');
      const err = document.getElementById('proofFileError');
      if (!row || !statusField) return;
      const show = ['Dinas Luar', 'Sakit', 'Cuti Tahunan'].includes(statusField.value);
      row.classList.toggle('tw-hidden', !show);
      if (!show && input) input.value = '';
      if (err) {
        err.textContent = '';
        err.classList.add('tw-hidden');
      }
    }

    statusSegment?.querySelectorAll('.pm-status-chip').forEach((btn) => {
      btn.addEventListener('click', () => {
        setStatusValue(btn.getAttribute('data-status') || '');
      });
    });

    function updateTimeWindowVisibility() {
      const row = document.getElementById('timeWindowRow');
      const start = document.getElementById('startTimeField');
      const end = document.getElementById('endTimeField');
      if (!statusField || !row || !start || !end) return;
      const isIzinKeluar = statusField.value === 'Izin Keluar';
      row.classList.toggle('tw-hidden', !isIzinKeluar);
      row.classList.toggle('tw-grid', isIzinKeluar);
      start.required = isIzinKeluar;
      end.required = isIzinKeluar;
      if (!isIzinKeluar) {
        start.value = '';
        end.value = '';
      }
    }

    updateTimeWindowVisibility();
    updateProofUploadVisibility();

    document.getElementById('proofFile')?.addEventListener('change', (e) => {
      const err = document.getElementById('proofFileError');
      const t = e.target;
      if (!err || !t.files || !t.files[0]) {
        if (err) {
          err.textContent = '';
          err.classList.add('tw-hidden');
        }
        return;
      }
      const f = t.files[0];
      const max = 5 * 1024 * 1024;
      const okTypes = ['application/pdf', 'image/jpeg', 'image/png'];
      if (f.size > max) {
        err.textContent = i18n.proofTooLarge;
        err.classList.remove('tw-hidden');
        t.value = '';
        return;
      }
      if (!okTypes.includes(f.type)) {
        err.textContent = i18n.proofTypeError;
        err.classList.remove('tw-hidden');
        t.value = '';
        return;
      }
      err.textContent = '';
      err.classList.add('tw-hidden');
    });

    document.getElementById('presenceForm')?.addEventListener('submit', (e) => {
      if (!statusField.value.trim()) {
        e.preventDefault();
        if (statusClientError) {
          statusClientError.textContent = i18n.chooseStatus;
          statusClientError.classList.remove('tw-hidden');
        }
        statusSegment?.querySelector('.pm-status-chip')?.focus();
        return;
      }
      const chosen = selectedFromText(employeeSearch.value || '');
      if (!chosen) {
        e.preventDefault();
        employeeSearch.focus();
        setEmployeeState(i18n.employeeMustSelect, true);
      }
    });
  </script>
</body>
</html>
