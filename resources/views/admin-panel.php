<?php
$adminPage = $adminPage ?? 'new';
$editEmployee = $editEmployee ?? null;
$pageTitle = $adminPage === 'new' ? __('page_title_add_employee') : __('page_title_edit_employee');
$activeNav = 'employees';
$shellClass = 'shell wide';
$viewsRoot = __DIR__;
include $viewsRoot . '/partials/admin-chrome-open.php';
?>
<div class="tw-mx-auto tw-w-full tw-max-w-xl">
  <header class="tw-mb-6 tw-rounded-2xl tw-border tw-border-white/50 tw-bg-white/70 tw-px-5 tw-py-5 tw-backdrop-blur-xl tw-shadow-[0_20px_50px_rgba(8,112,184,0.1)] sm:tw-px-6 sm:tw-py-6">
    <p class="tw-m-0 tw-text-[0.72rem] tw-font-extrabold tw-uppercase tw-tracking-[0.14em] tw-text-slate-500"><?php echo htmlspecialchars(__('employees')); ?></p>
    <div class="tw-mt-2 tw-flex tw-flex-col tw-gap-3 sm:tw-flex-row sm:tw-items-end sm:tw-justify-between">
      <h1 class="tw-m-0 tw-text-2xl tw-font-black tw-tracking-tight tw-text-slate-900 sm:tw-text-3xl"><?php echo htmlspecialchars($adminPage === 'new' ? __('add_employee_title') : __('edit_employee_title')); ?></h1>
      <a class="tw-inline-flex tw-shrink-0 tw-items-center tw-gap-1.5 tw-text-sm tw-font-bold tw-text-blue-700 tw-underline tw-decoration-blue-300 tw-underline-offset-4 hover:tw-text-blue-900" href="/admin/employees">← <?php echo htmlspecialchars(__('directory')); ?></a>
    </div>
  </header>

  <?php if (!empty($_GET['success'])): ?>
    <div class="tw-mb-5 tw-rounded-xl tw-border tw-border-emerald-200 tw-bg-emerald-50/90 tw-px-4 tw-py-3 tw-text-sm tw-font-semibold tw-text-emerald-900"><?php echo htmlspecialchars($_GET['success']); ?></div>
  <?php endif; ?>
  <?php if (!empty($_GET['error'])): ?>
    <div class="tw-mb-5 tw-rounded-xl tw-border tw-border-rose-200 tw-bg-rose-50/90 tw-px-4 tw-py-3 tw-text-sm tw-font-semibold tw-text-rose-900"><?php echo htmlspecialchars($_GET['error']); ?></div>
  <?php endif; ?>

  <?php if ($adminPage === 'new'): ?>
    <div class="tw-rounded-2xl tw-border tw-border-white/50 tw-bg-white/72 tw-px-5 tw-py-6 tw-backdrop-blur-xl tw-shadow-[0_20px_50px_rgba(8,112,184,0.12)] sm:tw-px-7 sm:tw-py-8">
      <h2 class="tw-m-0 tw-text-base tw-font-extrabold tw-text-slate-900"><?php echo htmlspecialchars(__('new_employee')); ?></h2>
      <p class="tw-m-0 tw-mt-1 tw-text-sm tw-leading-relaxed tw-text-slate-600"><?php echo htmlspecialchars(__('new_employee_lede')); ?></p>
      <form method="POST" action="/admin-panel/employee" enctype="multipart/form-data" class="tw-mt-6 tw-space-y-5">
        <?php $catNew = (string) ($_POST['category'] ?? 'PNS'); ?>
        <div>
          <label class="tw-mb-1.5 tw-block tw-text-[0.7rem] tw-font-bold tw-uppercase tw-tracking-wider tw-text-slate-500" for="addName"><?php echo htmlspecialchars(__('full_name')); ?></label>
          <input id="addName" class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200/95 tw-bg-white/95 tw-px-3.5 tw-py-2.5 tw-text-sm tw-font-semibold tw-text-slate-900 tw-shadow-[inset_0_1px_0_rgba(255,255,255,0.9)] tw-outline-none tw-transition focus:tw-border-blue-400 focus:tw-ring-2 focus:tw-ring-blue-100" type="text" name="name" required placeholder="e.g. Jane Doe" autocomplete="name">
        </div>
        <div>
          <label class="tw-mb-1.5 tw-block tw-text-[0.7rem] tw-font-bold tw-uppercase tw-tracking-wider tw-text-slate-500" for="addNip">NIP</label>
          <input id="addNip" class="js-nip-input tw-w-full tw-rounded-xl tw-border tw-border-slate-200/95 tw-bg-white/95 tw-px-3.5 tw-py-2.5 tw-text-sm tw-font-semibold tw-text-slate-900 tw-shadow-[inset_0_1px_0_rgba(255,255,255,0.9)] tw-outline-none tw-transition focus:tw-border-blue-400 focus:tw-ring-2 focus:tw-ring-blue-100" type="number" name="nip" required inputmode="numeric" min="0" step="1" placeholder="e.g. 199001012020121001" value="<?php echo htmlspecialchars($_POST['nip'] ?? ''); ?>">
          <p class="tw-mt-1.5 tw-text-xs tw-text-slate-500"><?php echo htmlspecialchars(__('nip_digits')); ?></p>
        </div>
        <div>
          <label class="tw-mb-1.5 tw-block tw-text-[0.7rem] tw-font-bold tw-uppercase tw-tracking-wider tw-text-slate-500" for="addPosition"><?php echo htmlspecialchars(__('position')); ?></label>
          <input id="addPosition" class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200/95 tw-bg-white/95 tw-px-3.5 tw-py-2.5 tw-text-sm tw-font-semibold tw-text-slate-900 tw-shadow-[inset_0_1px_0_rgba(255,255,255,0.9)] tw-outline-none tw-transition focus:tw-border-blue-400 focus:tw-ring-2 focus:tw-ring-blue-100" type="text" name="position" required placeholder="e.g. Analis Kepegawaian" maxlength="512" value="<?php echo htmlspecialchars($_POST['position'] ?? ''); ?>">
        </div>
        <div>
          <label class="tw-mb-1.5 tw-block tw-text-[0.7rem] tw-font-bold tw-uppercase tw-tracking-wider tw-text-slate-500" for="addCategory"><?php echo htmlspecialchars(__('category')); ?></label>
          <select id="addCategory" class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200/95 tw-bg-white/95 tw-px-3.5 tw-py-2.5 tw-text-sm tw-font-semibold tw-text-slate-900 tw-outline-none tw-transition focus:tw-border-blue-400 focus:tw-ring-2 focus:tw-ring-blue-100" name="category" required>
            <option value="PNS"<?php echo $catNew === 'PNS' ? ' selected' : ''; ?>>PNS</option>
            <option value="PPPK"<?php echo $catNew === 'PPPK' ? ' selected' : ''; ?>>PPPK</option>
            <option value="PPPK PW"<?php echo $catNew === 'PPPK PW' ? ' selected' : ''; ?>>PPPK PW</option>
          </select>
        </div>
        <div>
          <label class="tw-mb-1.5 tw-block tw-text-[0.7rem] tw-font-bold tw-uppercase tw-tracking-wider tw-text-slate-500" for="addPhoto"><?php echo htmlspecialchars(__('photo')); ?> <span class="tw-font-medium tw-normal-case tw-tracking-normal tw-text-slate-400">(<?php echo htmlspecialchars(__('optional')); ?>)</span></label>
          <input id="addPhoto" class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200/95 tw-bg-white/95 tw-px-3 tw-py-2 tw-text-sm tw-font-medium tw-text-slate-800 file:tw-mr-3 file:tw-rounded-lg file:tw-border-0 file:tw-bg-blue-50 file:tw-px-3 file:tw-py-1.5 file:tw-text-xs file:tw-font-bold file:tw-text-blue-800 hover:file:tw-bg-blue-100" type="file" name="photo" accept="image/jpeg,image/png,image/webp,image/gif">
          <p class="tw-mt-1.5 tw-text-xs tw-text-slate-500"><?php echo htmlspecialchars(__('photo_formats')); ?></p>
        </div>
        <button class="tw-w-full tw-rounded-xl tw-border tw-border-blue-700 tw-bg-gradient-to-br tw-from-blue-600 tw-to-blue-700 tw-py-3 tw-text-sm tw-font-extrabold tw-tracking-wide tw-text-white tw-shadow-[0_20px_50px_rgba(37,99,235,0.3)] tw-transition hover:tw-brightness-105 active:tw-translate-y-px" type="submit"><?php echo htmlspecialchars(__('add_employee')); ?></button>
      </form>
    </div>

  <?php elseif ($adminPage === 'edit' && !empty($editEmployee)): ?>
    <div class="tw-rounded-2xl tw-border tw-border-white/50 tw-bg-white/72 tw-px-5 tw-py-6 tw-backdrop-blur-xl tw-shadow-[0_20px_50px_rgba(8,112,184,0.12)] sm:tw-px-7 sm:tw-py-8">
      <h2 class="tw-m-0 tw-text-base tw-font-extrabold tw-text-slate-900"><?php echo htmlspecialchars(__('update_details')); ?></h2>
      <p class="tw-m-0 tw-mt-1 tw-text-sm tw-leading-relaxed tw-text-slate-600"><?php echo htmlspecialchars(__('update_lede')); ?></p>
      <form method="POST" action="/admin-panel/employee/update" enctype="multipart/form-data" class="tw-mt-6 tw-space-y-5">
        <input type="hidden" name="id" value="<?php echo (int) $editEmployee['id']; ?>">
        <?php $catEdit = (string) ($editEmployee['category'] ?? 'PNS'); ?>
        <div>
          <label class="tw-mb-1.5 tw-block tw-text-[0.7rem] tw-font-bold tw-uppercase tw-tracking-wider tw-text-slate-500" for="editName"><?php echo htmlspecialchars(__('full_name')); ?></label>
          <input id="editName" class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200/95 tw-bg-white/95 tw-px-3.5 tw-py-2.5 tw-text-sm tw-font-semibold tw-text-slate-900 tw-shadow-[inset_0_1px_0_rgba(255,255,255,0.9)] tw-outline-none tw-transition focus:tw-border-blue-400 focus:tw-ring-2 focus:tw-ring-blue-100" type="text" name="name" required value="<?php echo htmlspecialchars($editEmployee['name']); ?>" autocomplete="name">
        </div>
        <div>
          <label class="tw-mb-1.5 tw-block tw-text-[0.7rem] tw-font-bold tw-uppercase tw-tracking-wider tw-text-slate-500" for="editNip">NIP</label>
          <input id="editNip" class="js-nip-input tw-w-full tw-rounded-xl tw-border tw-border-slate-200/95 tw-bg-white/95 tw-px-3.5 tw-py-2.5 tw-text-sm tw-font-semibold tw-text-slate-900 tw-shadow-[inset_0_1px_0_rgba(255,255,255,0.9)] tw-outline-none tw-transition focus:tw-border-blue-400 focus:tw-ring-2 focus:tw-ring-blue-100" type="number" name="nip" required inputmode="numeric" min="0" step="1" value="<?php echo htmlspecialchars($editEmployee['nip'] ?? ''); ?>">
        </div>
        <div>
          <label class="tw-mb-1.5 tw-block tw-text-[0.7rem] tw-font-bold tw-uppercase tw-tracking-wider tw-text-slate-500" for="editPosition"><?php echo htmlspecialchars(__('position')); ?></label>
          <input id="editPosition" class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200/95 tw-bg-white/95 tw-px-3.5 tw-py-2.5 tw-text-sm tw-font-semibold tw-text-slate-900 tw-shadow-[inset_0_1px_0_rgba(255,255,255,0.9)] tw-outline-none tw-transition focus:tw-border-blue-400 focus:tw-ring-2 focus:tw-ring-blue-100" type="text" name="position" required maxlength="512" value="<?php echo htmlspecialchars($editEmployee['position'] ?? ''); ?>">
        </div>
        <div>
          <label class="tw-mb-1.5 tw-block tw-text-[0.7rem] tw-font-bold tw-uppercase tw-tracking-wider tw-text-slate-500" for="editCategory"><?php echo htmlspecialchars(__('category')); ?></label>
          <select id="editCategory" class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200/95 tw-bg-white/95 tw-px-3.5 tw-py-2.5 tw-text-sm tw-font-semibold tw-text-slate-900 tw-outline-none tw-transition focus:tw-border-blue-400 focus:tw-ring-2 focus:tw-ring-blue-100" name="category" required>
            <option value="PNS"<?php echo $catEdit === 'PNS' ? ' selected' : ''; ?>>PNS</option>
            <option value="PPPK"<?php echo $catEdit === 'PPPK' ? ' selected' : ''; ?>>PPPK</option>
            <option value="PPPK PW"<?php echo $catEdit === 'PPPK PW' ? ' selected' : ''; ?>>PPPK PW</option>
          </select>
        </div>
        <?php
          $ph = $editEmployee['photo'] ?? '';
          $isUrl = $ph !== '' && preg_match('#^https?://#i', $ph);
        ?>
        <div class="tw-relative tw-overflow-hidden tw-rounded-2xl tw-border tw-border-white/50 tw-bg-white/72 tw-p-4 tw-backdrop-blur-xl tw-shadow-[0_16px_40px_rgba(8,112,184,0.12)]">
          <div class="tw-pointer-events-none tw-absolute tw--right-12 tw--top-12 tw-h-28 tw-w-28 tw-rounded-full tw-bg-blue-400/15 tw-blur-3xl" aria-hidden="true"></div>
          <p class="tw-relative tw-m-0 tw-text-[0.7rem] tw-font-bold tw-uppercase tw-tracking-wider tw-text-slate-500"><?php echo htmlspecialchars(__('current_photo')); ?></p>
          <div class="tw-relative tw-mt-3 tw-flex tw-items-center tw-gap-4">
            <?php if ($ph !== ''): ?>
              <img class="tw-h-24 tw-w-24 tw-rounded-2xl tw-object-cover tw-border tw-border-white/60 tw-bg-white tw-shadow-[0_16px_40px_rgba(8,112,184,0.15)]" src="<?php echo htmlspecialchars($ph); ?>" alt="" width="96" height="96" loading="lazy" decoding="async">
            <?php else: ?>
              <div class="tw-flex tw-h-24 tw-w-24 tw-items-center tw-justify-center tw-rounded-2xl tw-bg-gradient-to-br tw-from-blue-100 tw-to-slate-100 tw-text-3xl tw-font-black tw-text-blue-800 tw-border tw-border-white/60 tw-shadow-[0_16px_40px_rgba(8,112,184,0.15)]"><?php
                $n = $editEmployee['name'];
                $fc = extension_loaded('mbstring') ? mb_substr($n, 0, 1, 'UTF-8') : substr($n, 0, 1);
                echo htmlspecialchars($fc);
              ?></div>
            <?php endif; ?>
            <div class="tw-min-w-0 tw-flex-1 tw-text-xs tw-leading-relaxed tw-text-slate-600">
              <?php if ($ph !== '' && $isUrl): ?>
                <p class="tw-m-0"><?php echo htmlspecialchars(__('external_url_photo')); ?></p>
              <?php elseif ($ph !== ''): ?>
                <p class="tw-m-0"><?php echo htmlspecialchars(__('file_on_server')); ?></p>
              <?php else: ?>
                <p class="tw-m-0"><?php echo htmlspecialchars(__('initials_until_photo')); ?></p>
              <?php endif; ?>
            </div>
          </div>
        </div>
        <div>
          <label class="tw-mb-1.5 tw-block tw-text-[0.7rem] tw-font-bold tw-uppercase tw-tracking-wider tw-text-slate-500" for="editPhoto"><?php echo htmlspecialchars(__('new_photo')); ?> <span class="tw-font-medium tw-normal-case tw-tracking-normal tw-text-slate-400">(<?php echo htmlspecialchars(__('optional')); ?>)</span></label>
          <input id="editPhoto" class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200/95 tw-bg-white/95 tw-px-3 tw-py-2 tw-text-sm tw-font-medium tw-text-slate-800 file:tw-mr-3 file:tw-rounded-lg file:tw-border-0 file:tw-bg-blue-50 file:tw-px-3 file:tw-py-1.5 file:tw-text-xs file:tw-font-bold file:tw-text-blue-800 hover:file:tw-bg-blue-100" type="file" name="photo" accept="image/jpeg,image/png,image/webp,image/gif">
          <p class="tw-mt-1.5 tw-text-xs tw-text-slate-500"><?php echo htmlspecialchars(__('photo_formats')); ?></p>
        </div>
        <label class="tw-flex tw-cursor-pointer tw-items-start tw-gap-3 tw-rounded-xl tw-border tw-border-slate-200/80 tw-bg-white/60 tw-px-4 tw-py-3 tw-text-sm tw-font-medium tw-text-slate-700">
          <input class="tw-mt-1 tw-h-4 tw-w-4 tw-rounded tw-border-slate-300 tw-text-blue-600 focus:tw-ring-blue-500" type="checkbox" name="remove_photo" value="1">
          <span><?php echo htmlspecialchars(__('remove_photo')); ?></span>
        </label>
        <div class="tw-flex tw-flex-col tw-gap-3 sm:tw-flex-row">
          <button class="tw-flex-1 tw-rounded-xl tw-border tw-border-blue-700 tw-bg-gradient-to-br tw-from-blue-600 tw-to-blue-700 tw-py-3 tw-text-sm tw-font-extrabold tw-text-white tw-shadow-[0_20px_50px_rgba(37,99,235,0.28)] tw-transition hover:tw-brightness-105" type="submit"><?php echo htmlspecialchars(__('save_changes')); ?></button>
          <a class="tw-inline-flex tw-flex-1 tw-items-center tw-justify-center tw-rounded-xl tw-border tw-border-slate-200 tw-bg-white tw-py-3 tw-text-sm tw-font-bold tw-text-slate-800 tw-shadow-sm tw-transition hover:tw-bg-slate-50" href="/admin/employees"><?php echo htmlspecialchars(__('cancel')); ?></a>
        </div>
      </form>
    </div>
  <?php endif; ?>
</div>
<script>
  document.querySelectorAll('.js-nip-input').forEach(function (el) {
    el.addEventListener('input', function () {
      var normalized = String(el.value || '').replace(/['\s]/g, '').replace(/\D+/g, '');
      if (normalized !== el.value) el.value = normalized;
    });
  });
</script>
<?php
include $viewsRoot . '/partials/admin-chrome-close.php';
