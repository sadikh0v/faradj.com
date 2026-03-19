<?php include base_path('src/views/admin/layout.php'); ?>
<?php $items = $items ?? []; ?>
<div class="admin-page">
  <div class="page-header">
    <h1>B2B Sorğular</h1>
  </div>

  <div class="admin-table-card">
    <table class="admin-table">
      <thead>
        <tr>
          <th width="60">ID</th>
          <th>Şirkət</th>
          <th>Əlaqə şəxsi</th>
          <th>Telefon</th>
          <th>E-mail</th>
          <th>Məhsullar</th>
          <th>Fəaliyyət</th>
          <th>Həcm</th>
          <th>Büdcə</th>
          <th>Tarix</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($items as $row): ?>
        <tr>
          <td class="td-id">#<?= (int)($row['id'] ?? 0) ?></td>
          <td><?= htmlspecialchars($row['company'] ?? '—') ?></td>
          <td><?= htmlspecialchars($row['contact'] ?? '—') ?></td>
          <td>
            <span class="copy-btn" data-copy="<?= htmlspecialchars($row['phone'] ?? '') ?>" title="Kopyala">
              <?= htmlspecialchars($row['phone'] ?? '—') ?>
              <i class="fas fa-copy" style="font-size:11px;color:#aaa;margin-left:4px;"></i>
            </span>
          </td>
          <td>
            <span class="copy-btn" data-copy="<?= htmlspecialchars($row['email'] ?? '') ?>" title="Kopyala">
              <?= htmlspecialchars($row['email'] ?? '—') ?>
              <i class="fas fa-copy" style="font-size:11px;color:#aaa;margin-left:4px;"></i>
            </span>
          </td>
          <td style="max-width:200px;">
            <?php if (!empty($row['products'])): ?>
            <div class="products-value" style="max-height:60px;overflow:hidden;font-size:12px;color:#444;line-height:1.4;cursor:pointer;" onclick="this.style.maxHeight = this.style.maxHeight === 'none' ? '60px' : 'none'">
              <?= htmlspecialchars($row['products']) ?>
            </div>
            <?php else: ?>
            <span style="color:#aaa;font-size:12px;">—</span>
            <?php endif; ?>
          </td>
          <td><?= htmlspecialchars($row['activity'] ?? '—') ?></td>
          <td><?= htmlspecialchars($row['volume'] ?? '—') ?></td>
          <td><?= htmlspecialchars($row['budget'] ?? '—') ?></td>
          <td><?= htmlspecialchars($row['created_at'] ?? '—') ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <?php if (empty($items)): ?>
    <p class="admin-empty">B2B sorğusu yoxdur.</p>
    <?php endif; ?>
  </div>
</div>

<?php include base_path('src/views/admin/layout-footer.php'); ?>
