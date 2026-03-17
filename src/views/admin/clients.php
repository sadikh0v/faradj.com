<?php include base_path('src/views/admin/layout.php'); ?>
<?php $items = $items ?? []; ?>
<div class="admin-page">
  <div class="page-header">
    <h1>Müştərilər</h1>
    <button type="button" class="btn-create" onclick="openClientModal()">
      <i class="fas fa-plus"></i> Yeni müştəri
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
          <th>Badge</th>
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
          <td><?= htmlspecialchars($row['badge'] ?? '—') ?></td>
          <td><?= (int)($row['sort_order'] ?? 0) ?></td>
          <td><?= ($row['is_active'] ?? 1) ? 'Aktiv' : 'Deaktiv' ?></td>
          <td class="td-actions">
            <button type="button" class="action-btn edit" onclick="editClient(<?= (int)$row['id'] ?>, '<?= htmlspecialchars(addslashes($row['name'] ?? '')) ?>', '<?= htmlspecialchars(addslashes($row['website'] ?? '')) ?>', '<?= htmlspecialchars(addslashes($row['badge'] ?? '')) ?>', <?= (int)($row['sort_order'] ?? 0) ?>, <?= ($row['is_active'] ?? 1) ?>)" title="Redaktə">
              <i class="fas fa-edit"></i>
            </button>
            <form method="POST" action="/admin/clients/delete" style="display:inline;">
              <input type="hidden" name="id" value="<?= (int)$row['id'] ?>">
              <button type="button" class="action-btn delete btn-delete-client" title="Sil" data-title="<?= htmlspecialchars($row['name'] ?? '') ?>"><i class="fas fa-trash"></i></button>
            </form>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <?php if (empty($items)): ?>
    <p class="admin-empty">Müştəri yoxdur.</p>
    <?php endif; ?>
  </div>
</div>

<div id="clientModal" class="modal-overlay" style="display:none">
  <div class="modal-box">
    <h3 id="clientModalTitle">Yeni müştəri</h3>
    <form method="POST" action="/admin/clients/save" enctype="multipart/form-data">
      <input type="hidden" name="id" id="clientId" value="0">
      <div class="form-group">
        <label>Ad</label>
        <input type="text" name="name" id="clientName" required>
      </div>
      <div class="form-group">
        <label>Website</label>
        <input type="text" name="website" id="clientWebsite" placeholder="https://">
      </div>
      <div class="form-group">
        <label>Badge</label>
        <select name="badge" id="clientBadge">
          <option value="">—</option>
          <option value="yeni">Yeni</option>
          <option value="daimi">Daimi</option>
          <option value="tender">Tender</option>
          <option value="vip">VIP</option>
        </select>
      </div>
      <div class="form-group">
        <label>Logo (fayl)</label>
        <input type="file" name="logo" accept="image/*">
      </div>
      <div class="form-group">
        <label>Sıra</label>
        <input type="number" name="sort_order" id="clientSort" value="0">
      </div>
      <div class="form-group">
        <label><input type="checkbox" name="is_active" id="clientActive" value="1" checked> Aktiv</label>
      </div>
      <div class="modal-actions">
        <button type="submit" class="btn-primary">Yadda saxla</button>
        <button type="button" class="btn-cancel" onclick="document.getElementById('clientModal').style.display='none'">Ləğv et</button>
      </div>
    </form>
  </div>
</div>

<script>
function openClientModal() {
  document.getElementById('clientModalTitle').textContent = 'Yeni müştəri';
  document.getElementById('clientId').value = '0';
  document.getElementById('clientName').value = '';
  document.getElementById('clientWebsite').value = '';
  document.getElementById('clientBadge').value = '';
  document.getElementById('clientSort').value = '0';
  document.getElementById('clientActive').checked = true;
  document.getElementById('clientModal').style.display = 'flex';
}
function editClient(id, name, website, badge, sort, active) {
  document.getElementById('clientModalTitle').textContent = 'Müştərini redaktə et';
  document.getElementById('clientId').value = id;
  document.getElementById('clientName').value = name;
  document.getElementById('clientWebsite').value = website;
  document.getElementById('clientBadge').value = badge;
  document.getElementById('clientSort').value = sort;
  document.getElementById('clientActive').checked = !!active;
  document.getElementById('clientModal').style.display = 'flex';
}
</script>

<?php include base_path('src/views/admin/layout-footer.php'); ?>
