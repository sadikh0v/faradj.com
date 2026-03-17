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
        <div class="gallery-item" title="<?= htmlspecialchars($file) ?>">
          <div class="gallery-img-wrap">
            <img src="<?= htmlspecialchars($webDir . $file) ?>" alt="<?= htmlspecialchars($file) ?>">
            <?php if ($isUsed): ?>
            <span class="gallery-badge used" title="İstifadə edilir">
              <i class="fas fa-check"></i>
            </span>
            <?php else: ?>
            <span class="gallery-badge unused" title="İstifadə edilmir">
              <i class="fas fa-exclamation"></i>
            </span>
            <?php endif; ?>
          </div>
          <div class="gallery-name"><?= htmlspecialchars($file) ?></div>
          <div class="gallery-actions">
            <button type="button" class="gallery-delete-btn btn-danger"
                    data-file="<?= htmlspecialchars($file) ?>"
                    data-dir="<?= htmlspecialchars($webDir) ?>"
                    data-used="<?= $isUsed ? '1' : '0' ?>">
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
