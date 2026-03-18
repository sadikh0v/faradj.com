<?php include base_path('src/views/admin/layout.php'); ?>

<div class="admin-page">
  <div class="page-header">
    <h1><i class="fas fa-user-shield" style="color:#6c63ff;margin-right:10px;"></i>İstifadəçi Məlumatları (GDPR)</h1>
  </div>

  <?php if ($flash = flash('success')): ?>
    <div class="flash success"><i class="fas fa-check-circle"></i> <?= htmlspecialchars($flash) ?></div>
  <?php endif; ?>
  <?php if ($flash = flash('error')): ?>
    <div class="flash error"><i class="fas fa-times-circle"></i> <?= htmlspecialchars($flash) ?></div>
  <?php endif; ?>

  <!-- Axtarış -->
  <div class="admin-table-wrap" style="margin-bottom:24px;padding:24px;">
    <h3 style="margin:0 0 16px;font-size:15px;font-weight:700;">
        <i class="fas fa-search" style="color:#6c63ff;margin-right:8px;"></i>
        İstifadəçi məlumatlarını axtar
    </h3>
    <form method="GET" action="/admin/users" style="display:flex;gap:12px;align-items:center;">
        <input type="email" name="email" 
               value="<?= htmlspecialchars($_GET['email'] ?? '') ?>"
               placeholder="Email ünvanı daxil edin..."
               style="flex:1;padding:12px 16px;border:1.5px solid #e8e8e8;border-radius:8px;font-size:14px;">
        <button type="submit" class="btn-primary">
            <i class="fas fa-search"></i> Axtar
        </button>
    </form>
  </div>

  <?php if (isset($userData)): ?>
  <div class="admin-table-wrap" style="margin-bottom:24px;padding:24px;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
        <h3 style="margin:0;font-size:15px;font-weight:700;">
            <i class="fas fa-user" style="color:#6c63ff;margin-right:8px;"></i>
            Nəticələr: <strong><?= htmlspecialchars($_GET['email'] ?? '') ?></strong>
        </h3>
        <div style="display:flex;gap:10px;">
            <a href="/admin/users/export?email=<?= urlencode($_GET['email'] ?? '') ?>" 
               class="btn-primary" style="text-decoration:none;background:#00b894;">
                <i class="fas fa-download"></i> CSV Export
            </a>
            <button type="button" class="btn-danger" data-email="<?= htmlspecialchars($_GET['email'] ?? '') ?>" id="btnDeleteUser">
                <i class="fas fa-trash"></i> Bütün məlumatları sil
            </button>
        </div>
    </div>

    <?php foreach ($userData as $table => $rows): ?>
    <?php if (!empty($rows)): ?>
    <div style="margin-bottom:20px;">
        <h4 style="font-size:13px;color:#6c63ff;text-transform:uppercase;margin-bottom:8px;">
            <?= htmlspecialchars($table) ?> (<?= count($rows) ?> qeyd)
        </h4>
        <table class="admin-table">
            <thead>
                <tr>
                    <?php foreach (array_keys($rows[0]) as $col): ?>
                    <th><?= htmlspecialchars($col) ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rows as $row): ?>
                <tr>
                    <?php foreach ($row as $val): ?>
                    <td style="font-size:12px;"><?= htmlspecialchars($val ?? '-') ?></td>
                    <?php endforeach; ?>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
    <?php endforeach; ?>

    <?php if (empty(array_filter($userData))): ?>
    <p style="text-align:center;color:#aaa;padding:20px;">
        Bu email ilə heç bir məlumat tapılmadı.
    </p>
    <?php endif; ?>
  </div>
  <?php endif; ?>

</div>

<!-- Delete Confirm Modal -->
<script>
document.getElementById('btnDeleteUser')?.addEventListener('click', function() {
    var email = this.dataset.email || '';
    customConfirm(
        '⚠️ Bütün məlumatları silmək istəyirsiniz?',
        email + ' — bütün müraciətlər, zənglər və mesajlar silinəcək. Bu əməliyyat geri qaytarıla bilməz.',
        function() {
            window.location.href = '/admin/users/delete?email=' + encodeURIComponent(email);
        }
    );
});
</script>

<?php include base_path('src/views/admin/layout-footer.php'); ?>
