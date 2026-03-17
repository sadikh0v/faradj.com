<?php include base_path('src/views/admin/layout.php'); ?>
<?php
$events = $events ?? [];
$totalPages = $totalPages ?? 1;
$page = $page ?? 1;
?>
<div class="admin-page">
  <div class="page-header">
    <h1>Xəbərlər</h1>
    <a href="/admin/events/create" class="btn-create">
      <i class="fas fa-plus"></i> Yeni xəbər
    </a>
  </div>

  <?php if ($msg = flash('success')): ?>
    <div class="flash success">
      <i class="fas fa-check-circle"></i> <?= htmlspecialchars($msg) ?>
    </div>
  <?php endif; ?>

  <form method="GET" action="/admin/events" class="filters-bar">
    <input type="text" name="search" placeholder="Axtar..."
           value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
    <select name="category">
      <option value="">Bütün kateqoriyalar</option>
      <?php foreach (['xebərlər','yeniləmə','aksiyalar','şirkət','tədbirlər','sərgi','festival'] as $c): ?>
        <option value="<?= htmlspecialchars($c) ?>" <?= ($_GET['category'] ?? '') === $c ? 'selected' : '' ?>>
          <?= htmlspecialchars(ucfirst($c)) ?>
        </option>
      <?php endforeach; ?>
    </select>
    <select name="status">
      <option value="">Bütün statuslar</option>
      <option value="published" <?= ($_GET['status'] ?? '') === 'published' ? 'selected' : '' ?>>Aktiv</option>
      <option value="draft" <?= ($_GET['status'] ?? '') === 'draft' ? 'selected' : '' ?>>Qaralama</option>
    </select>
    <button type="submit"><i class="fas fa-search"></i> Axtar</button>
    <a href="/admin/events" class="btn-reset">Sıfırla</a>
  </form>

  <div class="admin-table-card">
    <table class="admin-table">
      <thead>
        <tr>
          <th width="60">ID</th>
          <th>Başlıq</th>
          <th>Kateqoriya</th>
          <th>Müəllif</th>
          <th>Tarix</th>
          <th>Status</th>
          <th width="140">Əməliyyat</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($events as $e): ?>
        <tr>
          <td class="td-id">#<?= (int)$e['id'] ?></td>
          <td>
            <div class="td-title"><?= htmlspecialchars($e['title']) ?></div>
            <div class="td-excerpt"><?= htmlspecialchars(mb_substr($e['excerpt'] ?? '', 0, 60)) ?>...</div>
          </td>
          <td>
            <span class="cat-badge cat-<?= htmlspecialchars($e['category'] ?? '') ?>">
              <?= htmlspecialchars($e['category'] ?? '') ?>
            </span>
          </td>
          <td><?= htmlspecialchars($e['author'] ?? '') ?></td>
          <td><?= $e['event_date'] ?? '—' ?></td>
          <td>
            <label class="toggle-switch" title="<?= ($e['is_published'] ?? 0) ? 'Deaktiv et' : 'Aktiv et' ?>">
              <input type="checkbox" class="toggle-input" data-id="<?= (int)$e['id'] ?>"
                     <?= ($e['is_published'] ?? 0) ? 'checked' : '' ?>>
              <span class="toggle-slider"></span>
            </label>
          </td>
          <td class="td-actions">
            <a href="/admin/events/edit?id=<?= (int)$e['id'] ?>" class="action-btn edit" title="Redaktə et">
              <i class="fas fa-edit"></i>
            </a>
            <button type="button" class="action-btn delete btn-delete-event" title="Sil" data-id="<?= (int)$e['id'] ?>" data-title="<?= htmlspecialchars($e['title'] ?? '') ?>">
              <i class="fas fa-trash"></i>
            </button>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <?php if ($totalPages > 1): ?>
    <div class="pagination">
      <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <a href="?page=<?= $i ?>&search=<?= urlencode($_GET['search'] ?? '') ?>&category=<?= urlencode($_GET['category'] ?? '') ?>&status=<?= urlencode($_GET['status'] ?? '') ?>"
           class="page-btn <?= $page === $i ? 'active' : '' ?>"><?= $i ?></a>
      <?php endfor; ?>
    </div>
    <?php endif; ?>
  </div>
</div>

<form method="POST" action="/admin/events/delete" id="deleteForm" style="display:none;">
  <input type="hidden" name="id" id="deleteId">
</form>

<script>
document.querySelectorAll('.btn-delete-event').forEach(function(btn) {
  btn.addEventListener('click', function() {
    var id = this.dataset.id;
    var title = this.dataset.title || '';
    customConfirm('Silmək istəyirsiniz?', title, function() {
      document.getElementById('deleteId').value = id;
      document.getElementById('deleteForm').submit();
    });
  });
});
document.querySelectorAll('.toggle-input').forEach(function(input) {
  input.addEventListener('change', async function() {
    var id = this.dataset.id;
    var res = await fetch('/admin/events/toggle', {
      method: 'POST',
      headers: {'Content-Type': 'application/x-www-form-urlencoded'},
      body: new URLSearchParams({ id: id })
    });
    var data = await res.json();
    if (!data.success) this.checked = !this.checked;
  });
});
</script>

<?php include base_path('src/views/admin/layout-footer.php'); ?>
