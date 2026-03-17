<?php include base_path('src/views/admin/layout.php'); ?>
<?php $items = $items ?? []; ?>
<div class="admin-page">
  <div class="page-header">
    <h1>Brendlər</h1>
    <button type="button" class="btn-create" onclick="openBrandModal()">
      <i class="fas fa-plus"></i> Yeni brend
    </button>
  </div>

  <?php if ($msg = flash('success')): ?>
    <div class="flash success">
      <i class="fas fa-check-circle"></i> <?= htmlspecialchars($msg) ?>
    </div>
  <?php endif; ?>

  <div class="admin-table-card">
    <table class="admin-table">
      <thead>
        <tr>
          <th width="60">ID</th>
          <th>Logo</th>
          <th>Ad</th>
          <th>Website</th>
          <th>Sıra</th>
          <th>Status</th>
          <th width="140">Əməliyyat</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($items as $row): ?>
        <tr>
          <td class="td-id">#<?= (int)($row['id'] ?? 0) ?></td>
          <td><?php if (!empty($row['logo'])): ?><img src="<?= htmlspecialchars($row['logo']) ?>" alt="" style="height:32px;"><?php else: ?>—<?php endif; ?></td>
          <td><?= htmlspecialchars($row['name'] ?? '—') ?></td>
          <td><?= htmlspecialchars($row['website'] ?? '—') ?></td>
          <td><?= (int)($row['sort_order'] ?? 0) ?></td>
          <td><?= ($row['is_active'] ?? 1) ? 'Aktiv' : 'Deaktiv' ?></td>
          <td class="td-actions">
            <button type="button" class="action-btn edit" onclick="editBrand(<?= (int)$row['id'] ?>, '<?= htmlspecialchars(addslashes($row['name'] ?? '')) ?>', '<?= htmlspecialchars(addslashes($row['website'] ?? '')) ?>', <?= (int)($row['sort_order'] ?? 0) ?>, <?= ($row['is_active'] ?? 1) ?>)" title="Redaktə">
              <i class="fas fa-edit"></i>
            </button>
            <form method="POST" action="/admin/brands/delete" style="display:inline;">
              <input type="hidden" name="id" value="<?= (int)$row['id'] ?>">
              <button type="button" class="action-btn delete btn-delete-brand" title="Sil" data-title="<?= htmlspecialchars($row['name'] ?? '') ?>"><i class="fas fa-trash"></i></button>
            </form>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <?php if (empty($items)): ?>
    <p class="admin-empty">Brend yoxdur.</p>
    <?php endif; ?>
  </div>
</div>

<div id="brandModal" class="modal-overlay" style="display:none">
  <div class="modal-box">
    <h3 id="brandModalTitle">Yeni brend</h3>
    <form method="POST" action="/admin/brands/save" enctype="multipart/form-data">
      <input type="hidden" name="id" id="brandId" value="0">
      <div class="form-group">
        <label>Ad</label>
        <input type="text" name="name" id="brandName" required>
      </div>
      <div class="form-group">
        <label>Website</label>
        <input type="text" name="website" id="brandWebsite" placeholder="https://">
      </div>
      <div class="form-group">
        <label>Logo (fayl)</label>
        <input type="file" name="logo" accept="image/*">
      </div>
      <div class="form-group">
        <label>Sıra</label>
        <input type="number" name="sort_order" id="brandSort" value="0">
      </div>
      <div class="form-group">
        <label><input type="checkbox" name="is_active" id="brandActive" value="1" checked> Aktiv</label>
      </div>
      <div class="modal-actions">
        <button type="submit" class="btn-primary">Yadda saxla</button>
        <button type="button" class="btn-cancel" onclick="document.getElementById('brandModal').style.display='none'">Ləğv et</button>
      </div>
    </form>
  </div>
</div>

<script>
function openBrandModal() {
  document.getElementById('brandModalTitle').textContent = 'Yeni brend';
  document.getElementById('brandId').value = '0';
  document.getElementById('brandName').value = '';
  document.getElementById('brandWebsite').value = '';
  document.getElementById('brandSort').value = '0';
  document.getElementById('brandActive').checked = true;
  document.getElementById('brandModal').style.display = 'flex';
}
function editBrand(id, name, website, sort, active) {
  document.getElementById('brandModalTitle').textContent = 'Brendi redaktə et';
  document.getElementById('brandId').value = id;
  document.getElementById('brandName').value = name;
  document.getElementById('brandWebsite').value = website;
  document.getElementById('brandSort').value = sort;
  document.getElementById('brandActive').checked = !!active;
  document.getElementById('brandModal').style.display = 'flex';
}
</script>

<?php include base_path('src/views/admin/layout-footer.php'); ?>
