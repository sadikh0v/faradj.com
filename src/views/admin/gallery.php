<?php include base_path('src/views/admin/layout.php'); ?>
<?php
$dirs = [
    'Xəbər şəkilləri' => base_path('public/assets/img/events/'),
    'Brend logoları'   => base_path('public/assets/img/brands/'),
    'Müştəri logoları' => base_path('public/assets/img/clients/'),
];
$webDirs = [
    'Xəbər şəkilləri' => '/assets/img/events/',
    'Brend logoları'   => '/assets/img/brands/',
    'Müştəri logoları' => '/assets/img/clients/',
];
?>
<div class="admin-page">
  <div class="admin-topbar">
    <h1><i class="fas fa-images" style="color:#6c63ff;margin-right:10px;"></i>Şəkil Qalereyası</h1>
  </div>

  <div class="gallery-toolbar" id="galleryToolbar" style="display:none;position:sticky;top:0;z-index:100;background:#fff;padding:12px 20px;border-radius:12px;box-shadow:0 4px 20px rgba(0,0,0,0.1);align-items:center;gap:16px;margin-bottom:16px;">
    <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
      <input type="checkbox" id="selectAll">
      <span style="font-size:13px;font-weight:600;">Hamısını seç</span>
    </label>
    <span id="selectedCount" style="font-size:13px;color:#6c63ff;font-weight:600;">0 seçilib</span>
    <button type="button" id="deleteSelectedBtn" class="btn-danger" style="margin-left:auto;display:flex;align-items:center;gap:8px;">
      <i class="fas fa-trash"></i> Seçilənləri sil
    </button>
  </div>

  <?php foreach ($dirs as $label => $dir): ?>
  <div class="admin-table-wrap" style="margin-bottom:24px;">
    <div class="table-header">
      <h3><i class="fas fa-folder"></i> <?= htmlspecialchars($label) ?></h3>
    </div>
    <div style="padding:16px;display:flex;flex-wrap:wrap;gap:12px;">
      <?php
      $webDir = $webDirs[$label];
      $files = is_dir($dir) ? array_filter(scandir($dir), function($f) { return preg_match('/\.(jpg|jpeg|png|webp|gif|svg)$/i', $f); }) : [];
      if (empty($files)): ?>
        <p style="color:#aaa;font-size:13px;">Şəkil yoxdur</p>
      <?php else: foreach ($files as $file):
        $isUsed = in_array($file, $usedFiles ?? []);
      ?>
        <div class="gallery-item" data-file="<?= htmlspecialchars($file) ?>" data-dir="<?= htmlspecialchars($webDir) ?>">
          <div class="gallery-img-wrap">
            <input type="checkbox" class="gallery-checkbox" value="<?= htmlspecialchars($file) ?>" data-dir="<?= htmlspecialchars($webDir) ?>">
            <img src="<?= htmlspecialchars($webDir . $file) ?>" alt="<?= htmlspecialchars($file) ?>">
            <?php if ($isUsed): ?>
            <span class="gallery-badge used" title="İstifadə edilir"><i class="fas fa-check"></i></span>
            <?php else: ?>
            <span class="gallery-badge unused" title="İstifadə edilmir"><i class="fas fa-exclamation"></i></span>
            <?php endif; ?>
          </div>
          <div class="gallery-name"><?= htmlspecialchars($file) ?></div>
          <div class="gallery-actions">
            <button type="button" class="gallery-delete-btn btn-danger" data-file="<?= htmlspecialchars($file) ?>" data-dir="<?= htmlspecialchars($webDir) ?>" data-used="<?= $isUsed ? '1' : '0' ?>">
              <i class="fas fa-trash"></i>
            </button>
          </div>
        </div>
      <?php endforeach; endif; ?>
    </div>
  </div>
  <?php endforeach; ?>
</div>
<?php include base_path('src/views/admin/layout-footer.php'); ?>
