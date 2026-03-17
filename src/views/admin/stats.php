<?php include base_path('src/views/admin/layout.php'); ?>
<?php $data = $data ?? []; ?>

<div class="admin-page">
  <div class="admin-topbar">
    <h1><i class="fas fa-chart-bar" style="color:#6c63ff;margin-right:10px;"></i>Statistika</h1>
  </div>

  <!-- Карточки -->
  <div class="stats-grid" style="margin-bottom:24px;">
    <div class="stat-card">
      <div class="stat-value"><?= number_format($data['total'] ?? 0) ?></div>
      <div class="stat-label"><i class="fas fa-eye"></i> Cəmi ziyarət</div>
    </div>
    <div class="stat-card">
      <div class="stat-value"><?= number_format($data['today'] ?? 0) ?></div>
      <div class="stat-label"><i class="fas fa-calendar-day"></i> Bu gün</div>
    </div>
    <div class="stat-card">
      <div class="stat-value"><?= count($data['by_day'] ?? []) ?></div>
      <div class="stat-label"><i class="fas fa-calendar-alt"></i> Aktiv günlər (30 gün)</div>
    </div>
  </div>

  <div class="stats-bottom-grid">

    <!-- Топ страниц -->
    <div class="admin-table-wrap">
      <div class="table-header">
        <h3><i class="fas fa-file-alt"></i> Ən çox ziyarət edilən səhifələr</h3>
      </div>
      <table class="admin-table">
        <thead>
          <tr><th>Səhifə</th><th style="text-align:right">Ziyarət</th></tr>
        </thead>
        <tbody>
          <?php foreach ($data['top_pages'] ?? [] as $p):
            $page = preg_replace('/[?&]nc=\d+/', '', $p['page'] ?? '');
            $page = rtrim($page, '?&');
          ?>
          <tr>
            <td><code style="background:#f5f5f5;padding:2px 8px;border-radius:4px;font-size:13px;"><?= htmlspecialchars($page) ?></code></td>
            <td style="text-align:right;font-weight:600;color:#6c63ff;"><?= $p['cnt'] ?? 0 ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <!-- Языки -->
    <div class="admin-table-wrap">
      <div class="table-header">
        <h3><i class="fas fa-language"></i> Dil üzrə</h3>
      </div>
      <table class="admin-table">
        <thead>
          <tr><th>Dil</th><th style="text-align:right">Ziyarət</th></tr>
        </thead>
        <tbody>
          <?php
          $langNames = ['az' => '🇦🇿 Azərbaycan', 'ru' => '🇷🇺 Rus', 'en' => '🇬🇧 İngilis'];
          foreach ($data['by_lang'] ?? [] as $l):
            $name = $langNames[$l['lang'] ?? ''] ?? ($l['lang'] ?? '(boş)');
          ?>
          <tr>
            <td><?= htmlspecialchars($name) ?></td>
            <td style="text-align:right;font-weight:600;color:#6c63ff;"><?= $l['cnt'] ?? 0 ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <!-- По дням -->
    <div class="admin-table-wrap" style="grid-column: 1 / -1;">
      <div class="table-header">
        <h3><i class="fas fa-calendar-week"></i> Son 30 gün</h3>
      </div>
      <table class="admin-table">
        <thead>
          <tr><th>Tarix</th><th style="text-align:right">Ziyarət</th><th>Qrafik</th></tr>
        </thead>
        <tbody>
          <?php
          $byDay = $data['by_day'] ?? [];
          $max = !empty($byDay) ? max(array_column($byDay, 'cnt')) : 1;
          foreach ($byDay as $d):
            $pct = $max > 0 ? round(($d['cnt'] / $max) * 100) : 0;
          ?>
          <tr>
            <td><?= htmlspecialchars($d['day'] ?? '') ?></td>
            <td style="text-align:right;font-weight:600;color:#6c63ff;"><?= $d['cnt'] ?? 0 ?></td>
            <td style="width:40%;">
              <div style="background:#ede9ff;border-radius:4px;height:8px;">
                <div style="background:#6c63ff;border-radius:4px;height:8px;width:<?= $pct ?>%;"></div>
              </div>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

  </div>
</div>

<?php include base_path('src/views/admin/layout-footer.php'); ?>
