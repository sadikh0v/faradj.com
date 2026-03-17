<main class="admin-main">
    <div class="container">
        <h1>Xəbərlər və Tədbirlər — İdarəetmə</h1>
        <p class="admin-logout"><a href="/logout.php">Çıxış</a></p>

        <div class="admin-stats glass-card">
            <h3>Statistika</h3>
            <a href="/admin-export-b2b.php" class="btn-export"><i class="fas fa-file-csv"></i> CSV Export</a>
            <div class="admin-stats-grid">
                <div class="admin-stat"><strong><?= (int)($visitorTotal ?? 0) ?></strong> <span>Ümumi baxış</span></div>
                <div class="admin-stat"><strong><?= (int)($visitorToday ?? 0) ?></strong> <span>Bu gün</span></div>
                <div class="admin-stat"><strong><?= (int)($b2bCount30 ?? 0) ?></strong> <span>Müraciət (30 gün)</span></div>
                <div class="admin-stat"><strong><?= (int)($callbackCount30 ?? 0) ?></strong> <span>Zəng sorğusu (30 gün)</span></div>
            </div>
            <?php if (!empty($visitorTopPages)): ?>
            <h4>Populyar səhifələr (TOP 10)</h4>
            <ul class="admin-top-pages">
                <?php foreach ($visitorTopPages as $row): ?>
                <li><?= htmlspecialchars($row['page']) ?> — <?= (int)$row['cnt'] ?></li>
                <?php endforeach; ?>
            </ul>
            <?php endif; ?>
            <div class="admin-charts">
                <div class="chart-wrap">
                    <h4>Ziyarətçilər (son 7 gün)</h4>
                    <canvas id="visitChart" height="200"></canvas>
                </div>
            </div>
        </div>

        <?php if (!empty($message)): ?>
            <div class="admin-message <?= htmlspecialchars($messageType ?? 'success') ?>"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <div class="admin-actions">
            <button type="button" class="btn-add-event" id="btnAddEvent">+ Yeni xəbər/tədbir</button>
        </div>

        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Başlıq</th>
                    <th>Kateqoriya</th>
                    <th>Tarix</th>
                    <th>Status</th>
                    <th>Əməliyyatlar</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($events as $e): ?>
                <tr>
                    <td><?= (int)$e['id'] ?></td>
                    <td><?= htmlspecialchars($e['title']) ?></td>
                    <td><?= htmlspecialchars($e['category']) ?></td>
                    <td><?= htmlspecialchars($e['event_date'] ?? '-') ?></td>
                    <td><?= $e['is_published'] ? 'Aktiv' : 'Draft' ?></td>
                    <td>
                        <a href="?edit=<?= (int)$e['id'] ?>" class="btn-edit">Düzəlt</a>
                        <a href="?delete=<?= (int)$e['id'] ?>" class="btn-delete" onclick="return confirm('Silmək istədiyinizə əminsiniz?')">Sil</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <?php if (empty($events)): ?>
            <p class="admin-empty">Hələ xəbər yoxdur. Yeni əlavə edin.</p>
        <?php endif; ?>
    </div>
</main>

<div id="eventModal" class="modal-overlay hidden">
    <div class="modal-box glass-card">
        <h2 id="modalTitle">Yeni xəbər</h2>
        <form method="post" id="eventForm">
            <input type="hidden" name="id" id="eventId" value="">
            <div class="form-row">
                <div class="input-group">
                    <label for="title">Başlıq *</label>
                    <input type="text" name="title" id="title" required />
                </div>
                <div class="input-group">
                    <label for="category">Kateqoriya</label>
                    <select name="category" id="category">
                        <option value="xebərlər">Xəbərlər</option>
                        <option value="yeniləmə">Yeniləmə</option>
                        <option value="aksiyalar">Aksiyalar</option>
                        <option value="şirkət">Şirkət həyatı</option>
                        <option value="tədbirlər">Tədbirlər</option>
                    </select>
                </div>
            </div>
            <div class="input-group">
                <label for="excerpt">Qısa mətn</label>
                <input type="text" name="excerpt" id="excerpt" />
            </div>
            <div class="input-group">
                <label for="full_text">Tam mətn</label>
                <textarea name="full_text" id="full_text" rows="5"></textarea>
            </div>
            <div class="form-row">
                <div class="input-group">
                    <label for="author">Müəllif</label>
                    <input type="text" name="author" id="author" />
                </div>
                <div class="input-group">
                    <label for="event_date">Tarix</label>
                    <input type="date" name="event_date" id="event_date" />
                </div>
            </div>
            <div class="input-group">
                <label for="image_url">Şəkil URL</label>
                <input type="text" name="image_url" id="image_url" placeholder="/img/events/..." />
            </div>
            <div class="input-group">
                <label><input type="checkbox" name="is_published" id="is_published" value="1" checked /> Dərc edilsin</label>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn-submit">Saxla</button>
                <button type="button" class="btn-cancel" id="btnCancelModal">Ləğv et</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
(function() {
  const byDay = <?= json_encode($visitsByDay ?? []) ?>;
  const dayNames = ['Baz', 'B.e', 'Ç.a', 'Ç', 'C.a', 'Cüm', 'Ş'];
  const labels = [];
  const data = [];
  for (let i = 6; i >= 0; i--) {
    const d = new Date();
    d.setDate(d.getDate() - i);
    const ds = d.toISOString().slice(0, 10);
    labels.push(dayNames[d.getDay()] || ds);
    const found = byDay.find(r => r.d === ds);
    data.push(found ? parseInt(found.cnt, 10) : 0);
  }
  const ctx = document.getElementById('visitChart');
  if (ctx) {
    new Chart(ctx, {
      type: 'line',
      data: {
        labels: labels,
        datasets: [{
          label: 'Ziyarətçilər',
          data: data,
          borderColor: '#6c63ff',
          backgroundColor: 'rgba(108,99,255,0.1)',
          tension: 0.4,
          fill: true
        }]
      },
      options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true } }
      }
    });
  }
})();
</script>
<?php if ($editEvent): ?>
<script>window.__editEvent = <?= json_encode($editEvent) ?>;</script>
<?php endif; ?>
