<?php include base_path('src/views/admin/layout.php'); ?>
<?php $items = $items ?? []; ?>
<div class="admin-page">
  <div class="page-header">
    <h1>Zəng sorğuları</h1>
  </div>

  <div class="admin-table-card">
    <table class="admin-table">
      <thead>
        <tr>
          <th width="60">ID</th>
          <th>Ad</th>
          <th>Telefon</th>
          <th>Uyğun vaxt</th>
          <th>Tarix</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($items as $row): ?>
        <tr>
          <td class="td-id">#<?= (int)($row['id'] ?? 0) ?></td>
          <td><?= htmlspecialchars($row['name'] ?? '—') ?></td>
          <td>
            <span class="copy-btn" data-copy="<?= htmlspecialchars($row['phone'] ?? '') ?>" title="Kopyala">
              <?= htmlspecialchars($row['phone'] ?? '—') ?>
              <i class="fas fa-copy" style="font-size:11px;color:#aaa;margin-left:4px;"></i>
            </span>
          </td>
          <td><?= htmlspecialchars($row['time_pref'] ?? $row['time_slot'] ?? '—') ?></td>
          <td><?= htmlspecialchars($row['created_at'] ?? '—') ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <?php if (empty($items)): ?>
    <p class="admin-empty">Zəng sorğusu yoxdur.</p>
    <?php endif; ?>
  </div>
</div>

<?php include base_path('src/views/admin/layout-footer.php'); ?>
