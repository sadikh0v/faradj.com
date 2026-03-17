<?php include base_path('src/views/admin/layout.php'); ?>

<div class="admin-page">
  <div class="page-header">
    <h1>Dashboard</h1>
    <span class="page-date"><?= date('d.m.Y') ?></span>
  </div>

  <?php
  $cards = [
    ['icon'=>'fas fa-newspaper', 'value'=>$stats['events_published'] ?? 0, 'label'=>'Aktiv xəbər', 'sub'=>($stats['events_total'] ?? 0).' ümumi', 'color'=>'#6c63ff', 'bg'=>'#ede9ff'],
    ['icon'=>'fas fa-envelope', 'value'=>$stats['contacts_new'] ?? 0, 'label'=>'Yeni müraciət', 'sub'=>'7 günlük', 'color'=>'#e91e8c', 'bg'=>'#fce4f3'],
    ['icon'=>'fas fa-briefcase', 'value'=>$stats['b2b_new'] ?? 0, 'label'=>'Yeni B2B sorğu', 'sub'=>'7 günlük', 'color'=>'#00b894', 'bg'=>'#e0f7f4'],
    ['icon'=>'fas fa-phone', 'value'=>$stats['callbacks_new'] ?? 0, 'label'=>'Zəng sorğusu', 'sub'=>'7 günlük', 'color'=>'#f39c12', 'bg'=>'#fef9e7'],
  ];
  ?>

  <div class="stats-grid">
    <?php foreach ($cards as $c): ?>
    <div class="stat-card" style="border-left: 4px solid <?= $c['color'] ?>">
      <div style="display:flex;align-items:center;gap:16px;">
        <div style="width:48px;height:48px;border-radius:12px;background:<?= $c['bg'] ?>;display:flex;align-items:center;justify-content:center;">
          <i class="<?= $c['icon'] ?>" style="color:<?= $c['color'] ?>;font-size:20px;"></i>
        </div>
        <div>
          <div class="stat-value" style="color:<?= $c['color'] ?>"><?= $c['value'] ?></div>
          <div class="stat-label"><?= $c['label'] ?></div>
          <div style="font-size:11px;color:#aaa;"><?= $c['sub'] ?></div>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>

  <div style="display:flex;gap:12px;margin-bottom:24px;flex-wrap:wrap;">
    <a href="/admin/events/create" class="btn-primary" style="text-decoration:none;display:flex;align-items:center;gap:8px;">
      <i class="fas fa-plus"></i> Yeni xəbər
    </a>
    <a href="/admin/brands" style="background:#f0f0ff;color:#6c63ff;padding:10px 20px;border-radius:8px;text-decoration:none;font-size:14px;font-weight:600;display:flex;align-items:center;gap:8px;">
      <i class="fas fa-tags"></i> Brendlər
    </a>
    <a href="/admin/settings" style="background:#f0f0ff;color:#6c63ff;padding:10px 20px;border-radius:8px;text-decoration:none;font-size:14px;font-weight:600;display:flex;align-items:center;gap:8px;">
      <i class="fas fa-sliders-h"></i> Parametrlər
    </a>
    <a href="/" target="_blank" style="background:#e0f7f4;color:#00b894;padding:10px 20px;border-radius:8px;text-decoration:none;font-size:14px;font-weight:600;display:flex;align-items:center;gap:8px;">
      <i class="fas fa-external-link-alt"></i> Sayta bax
    </a>
  </div>

  <div class="admin-table-wrap" style="margin-bottom:24px;padding:20px;">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
      <h3 style="margin:0;font-size:15px;font-weight:700;color:#1a1a2e;">
        <i class="fas fa-chart-area" style="color:#6c63ff;margin-right:8px;"></i>
        Son 7 gün — Ziyarətlər
      </h3>
    </div>
    <div style="display:flex;align-items:flex-end;gap:8px;height:80px;">
      <?php
      $chartData = $chartData ?? [];
      $maxV = !empty($chartData) ? max(array_column($chartData, 'cnt')) : 1;
      $days = [];
      for ($i = 6; $i >= 0; $i--) {
        $days[date('Y-m-d', strtotime("-$i days"))] = 0;
      }
      foreach ($chartData as $r) { $days[$r['day']] = (int)($r['cnt'] ?? 0); }
      foreach ($days as $day => $cnt):
        $h = $maxV > 0 ? max(8, round(($cnt / $maxV) * 80)) : 8;
        $label = date('d.m', strtotime($day));
      ?>
      <div style="flex:1;display:flex;flex-direction:column;align-items:center;gap:4px;">
        <span style="font-size:10px;color:#6c63ff;font-weight:600;"><?= $cnt ?: '' ?></span>
        <div style="width:100%;background:#6c63ff;border-radius:4px 4px 0 0;height:<?= $h ?>px;transition:height 0.3s;"></div>
        <span style="font-size:10px;color:#999;"><?= $label ?></span>
      </div>
      <?php endforeach; ?>
    </div>
  </div>

  <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:24px;">

    <div class="admin-table-wrap">
      <div class="table-header" style="display:flex;justify-content:space-between;align-items:center;">
        <h3><i class="fas fa-briefcase"></i> Son B2B sorğular</h3>
        <a href="/admin/b2b" style="font-size:12px;color:#6c63ff;">Hamısına bax →</a>
      </div>
      <table class="admin-table">
        <tbody>
        <?php if (empty($recentB2b ?? [])): ?>
        <tr><td colspan="2" style="text-align:center;color:#aaa;padding:20px;">Sorğu yoxdur</td></tr>
        <?php else: foreach ($recentB2b as $r): ?>
        <tr>
          <td>
            <strong><?= htmlspecialchars($r['company'] ?? $r['name'] ?? '') ?></strong>
            <div style="font-size:12px;color:#999;"><?= htmlspecialchars($r['phone'] ?? '') ?></div>
          </td>
          <td style="text-align:right;font-size:12px;color:#aaa;">
            <?= date('d.m H:i', strtotime($r['created_at'] ?? 'now')) ?>
          </td>
        </tr>
        <?php endforeach; endif; ?>
        </tbody>
      </table>
    </div>

    <div class="admin-table-wrap">
      <div class="table-header" style="display:flex;justify-content:space-between;align-items:center;">
        <h3><i class="fas fa-phone"></i> Son zəng sorğuları</h3>
        <a href="/admin/callbacks" style="font-size:12px;color:#6c63ff;">Hamısına bax →</a>
      </div>
      <table class="admin-table">
        <tbody>
        <?php if (empty($recentCallbacks ?? [])): ?>
        <tr><td colspan="2" style="text-align:center;color:#aaa;padding:20px;">Sorğu yoxdur</td></tr>
        <?php else: foreach ($recentCallbacks as $r): ?>
        <tr>
          <td>
            <strong><?= htmlspecialchars($r['name'] ?? '') ?></strong>
            <div style="font-size:12px;color:#999;"><?= htmlspecialchars($r['phone'] ?? '') ?></div>
          </td>
          <td style="text-align:right;font-size:12px;color:#aaa;">
            <?= date('d.m H:i', strtotime($r['created_at'] ?? 'now')) ?>
          </td>
        </tr>
        <?php endforeach; endif; ?>
        </tbody>
      </table>
    </div>

  </div>

  <div class="admin-table-card">
    <div class="table-header">
      <h3>Son xəbərlər</h3>
      <a href="/admin/events" class="link-all">Hamısına bax →</a>
    </div>
    <table class="admin-table">
      <thead>
        <tr>
          <th>Başlıq</th>
          <th>Kateqoriya</th>
          <th>Tarix</th>
          <th>Status</th>
          <th>Əməliyyat</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($recentEvents ?? [] as $e): ?>
        <tr>
          <td><?= htmlspecialchars($e['title'] ?? '') ?></td>
          <td>
            <span class="cat-badge cat-<?= htmlspecialchars($e['category'] ?? '') ?>">
              <?= htmlspecialchars($e['category'] ?? '') ?>
            </span>
          </td>
          <td><?= $e['event_date'] ?? date('Y-m-d', strtotime($e['created_at'] ?? 'now')) ?></td>
          <td>
            <span class="status-badge <?= ($e['is_published'] ?? 0) ? 'published' : 'draft' ?>">
              <?= ($e['is_published'] ?? 0) ? 'Aktiv' : 'Qaralama' ?>
            </span>
          </td>
          <td>
            <a href="/admin/events/edit?id=<?= (int)($e['id'] ?? 0) ?>" class="action-btn edit">
              <i class="fas fa-edit"></i>
            </a>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<?php include base_path('src/views/admin/layout-footer.php'); ?>
