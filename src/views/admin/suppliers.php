<?php include base_path('src/views/admin/layout.php'); ?>
<?php $suppliers = $suppliers ?? []; ?>
<div class="admin-page">
  <div class="admin-topbar" style="display:flex;justify-content:space-between;align-items:center;">
    <h1><i class="fas fa-globe" style="color:#6c63ff;margin-right:10px;"></i>Təchizatçı Xəritəsi</h1>
    <button class="btn-primary" onclick="openSupplierModal()">
        <i class="fas fa-plus"></i> Yeni ölkə
    </button>
  </div>

  <?php if ($msg = flash('success')): ?>
    <div class="flash success"><i class="fas fa-check-circle"></i> <?= htmlspecialchars($msg) ?></div>
  <?php endif; ?>
  <?php if ($err = flash('error')): ?>
    <div class="flash error"><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($err) ?></div>
  <?php endif; ?>

  <!-- Превью карты -->
  <div class="admin-table-wrap" style="margin-bottom:24px;padding:16px;">
    <div id="adminSuppliersMap" style="height:300px;border-radius:12px;"></div>
  </div>

  <!-- Список -->
  <div class="admin-table-wrap">
    <table class="admin-table">
      <thead>
        <tr>
          <th>Ölkə</th>
          <th>Brendlər</th>
          <th>Növ</th>
          <th>Koordinatlar</th>
          <th>Status</th>
          <th>Əməliyyat</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($suppliers as $s): ?>
        <tr>
          <td>
            <span style="font-size:20px;margin-right:8px;"><?= htmlspecialchars($s['flag'] ?? '') ?></span>
            <strong><?= htmlspecialchars($s['country_az']) ?></strong>
          </td>
          <td style="font-size:13px;color:#666;"><?= htmlspecialchars($s['brands'] ?? '') ?></td>
          <td>
            <span style="display:inline-block;padding:4px 10px;border-radius:8px;font-size:11px;font-weight:700;background:<?= ($s['type'] ?? '') === 'distributor' ? '#fce4f3' : '#ede9ff' ?>;color:<?= ($s['type'] ?? '') === 'distributor' ? '#e91e8c' : '#6c63ff' ?>;">
                <?= ($s['type'] ?? '') === 'distributor' ? 'DİSTRİBYUTOR' : 'TƏRƏFDAŞ' ?>
            </span>
          </td>
          <td style="font-size:12px;color:#999;">
            <?= htmlspecialchars($s['latitude'] ?? '') ?>, <?= htmlspecialchars($s['longitude'] ?? '') ?>
          </td>
          <td>
            <span class="status-badge <?= ($s['is_active'] ?? 1) ? 'published' : 'draft' ?>">
                <?= ($s['is_active'] ?? 1) ? 'Aktiv' : 'Gizli' ?>
            </span>
          </td>
          <td>
            <button class="action-btn edit" type="button" data-supplier='<?= htmlspecialchars(json_encode($s), ENT_QUOTES, 'UTF-8') ?>' onclick="editSupplier(this)" title="Redaktə">
                <i class="fas fa-edit"></i>
            </button>
            <form method="POST" action="/admin/suppliers/delete" style="display:inline;">
                <input type="hidden" name="id" value="<?= (int)($s['id'] ?? 0) ?>">
                <button type="button" class="action-btn delete"
                    onclick="if(confirm('Silmək istəyirsiniz?')) this.closest('form').submit()" title="Sil">
                    <i class="fas fa-trash"></i>
                </button>
            </form>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <?php if (empty($suppliers)): ?>
  <p class="admin-empty">Təchizatçı yoxdur. Yeni əlavə edin.</p>
  <?php endif; ?>
</div>

<!-- Modal -->
<div id="supplierModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:9999;align-items:center;justify-content:center;overflow-y:auto;">
  <div style="background:#fff;border-radius:16px;padding:32px;max-width:600px;width:95%;max-height:90vh;overflow-y:auto;">
    <h3 id="supplierModalTitle" style="margin:0 0 24px;font-size:18px;">Yeni ölkə</h3>
    <form method="POST" action="/admin/suppliers/save">
      <input type="hidden" name="id" id="supId">

      <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px;margin-bottom:16px;">
        <div>
          <label class="field-label" style="font-size:12px;font-weight:600;color:#666;margin-bottom:4px;display:block;">Ölkə (AZ)</label>
          <input type="text" name="country_az" id="supAz" required style="width:100%;padding:10px;border:1.5px solid #e8e8e8;border-radius:8px;font-size:13px;box-sizing:border-box;">
        </div>
        <div>
          <label class="field-label" style="font-size:12px;font-weight:600;color:#666;margin-bottom:4px;display:block;">Ölkə (RU)</label>
          <input type="text" name="country_ru" id="supRu" style="width:100%;padding:10px;border:1.5px solid #e8e8e8;border-radius:8px;font-size:13px;box-sizing:border-box;">
        </div>
        <div>
          <label class="field-label" style="font-size:12px;font-weight:600;color:#666;margin-bottom:4px;display:block;">Ölkə (EN)</label>
          <input type="text" name="country_en" id="supEn" style="width:100%;padding:10px;border:1.5px solid #e8e8e8;border-radius:8px;font-size:13px;box-sizing:border-box;">
        </div>
      </div>

      <div style="margin-bottom:16px;">
        <label class="field-label" style="font-size:12px;font-weight:600;color:#666;margin-bottom:4px;display:block;">Brendlər</label>
        <input type="text" name="brands" id="supBrands" placeholder="DOMS, Cello, Dolphin"
               style="width:100%;padding:10px;border:1.5px solid #e8e8e8;border-radius:8px;font-size:13px;box-sizing:border-box;">
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:16px;">
        <div>
          <label class="field-label" style="font-size:12px;font-weight:600;color:#666;margin-bottom:4px;display:block;">Latitude</label>
          <input type="text" name="latitude" id="supLat" placeholder="40.4093"
                 style="width:100%;padding:10px;border:1.5px solid #e8e8e8;border-radius:8px;font-size:13px;box-sizing:border-box;">
        </div>
        <div>
          <label class="field-label" style="font-size:12px;font-weight:600;color:#666;margin-bottom:4px;display:block;">Longitude</label>
          <input type="text" name="longitude" id="supLon" placeholder="49.8671"
                 style="width:100%;padding:10px;border:1.5px solid #e8e8e8;border-radius:8px;font-size:13px;box-sizing:border-box;">
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr 1fr 1fr;gap:12px;margin-bottom:16px;">
        <div>
          <label class="field-label" style="font-size:12px;font-weight:600;color:#666;margin-bottom:4px;display:block;">Növ</label>
          <select name="type" id="supType" style="width:100%;padding:10px;border:1.5px solid #e8e8e8;border-radius:8px;font-size:13px;box-sizing:border-box;">
            <option value="partner">TƏRƏFDAŞ</option>
            <option value="distributor">DİSTRİBYUTOR</option>
          </select>
        </div>
        <div>
          <label class="field-label" style="font-size:12px;font-weight:600;color:#666;margin-bottom:4px;display:block;">Emoji bayraq</label>
          <input type="text" name="flag" id="supFlag" placeholder="🇦🇿" style="width:100%;padding:10px;border:1.5px solid #e8e8e8;border-radius:8px;font-size:13px;box-sizing:border-box;">
        </div>
        <div>
          <label class="field-label" style="font-size:12px;font-weight:600;color:#666;margin-bottom:4px;display:block;">ISO kod (az, ru, tr...)</label>
          <input type="text" name="iso_code" id="supIso" placeholder="az" maxlength="5" style="width:100%;padding:10px;border:1.5px solid #e8e8e8;border-radius:8px;font-size:13px;box-sizing:border-box;">
        </div>
        <div>
          <label class="field-label" style="font-size:12px;font-weight:600;color:#666;margin-bottom:4px;display:block;">Sıra</label>
          <input type="number" name="sort_order" id="supSort" value="0" style="width:100%;padding:10px;border:1.5px solid #e8e8e8;border-radius:8px;font-size:13px;box-sizing:border-box;">
        </div>
      </div>

      <div style="display:flex;align-items:center;gap:8px;margin-bottom:24px;">
        <input type="checkbox" name="is_active" id="supActive" value="1" checked>
        <label for="supActive" style="font-size:13px;font-weight:600;">Aktiv</label>
      </div>

      <!-- Мини карта для выбора координат -->
      <div style="margin-bottom:16px;">
        <label class="field-label" style="font-size:12px;font-weight:600;color:#666;margin-bottom:4px;display:block;">Xəritədə seçin (klik ilə koordinat)</label>
        <div id="coordPickerMap" style="height:200px;border-radius:8px;border:1.5px solid #e8e8e8;"></div>
        <small style="color:#aaa;font-size:11px;">Xəritəyə klik edin — koordinatlar avtomatik doldurulacaq</small>
      </div>

      <div style="display:flex;gap:12px;justify-content:flex-end;">
        <button type="button" onclick="closeSupplierModal()"
                style="padding:10px 20px;border:1.5px solid #eee;background:#fff;border-radius:8px;cursor:pointer;">
            Ləğv et
        </button>
        <button type="submit" class="btn-primary">
            <i class="fas fa-save"></i> Yadda saxla
        </button>
      </div>
    </form>
  </div>
</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
// Превью карта в админке
const adminMap = L.map('adminSuppliersMap', {
    center: [30, 55], zoom: 2, zoomControl: false, attributionControl: false
});
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(adminMap);

const suppliersData = <?= json_encode($suppliers) ?>;
const baku = [40.4093, 49.8671];

suppliersData.forEach(s => {
    if (!s.is_active) return;
    const color = s.type === 'distributor' ? '#e91e8c' : '#6c63ff';
    L.circleMarker([parseFloat(s.latitude), parseFloat(s.longitude)], {
        radius: 7, fillColor: color, color: '#fff', weight: 2, fillOpacity: 0.9
    }).bindPopup('<strong>' + (s.country_az || '') + '</strong><br><small>' + (s.brands || '') + '</small>').addTo(adminMap);

    L.polyline([[parseFloat(s.latitude), parseFloat(s.longitude)], baku], {
        color: color, weight: 1, opacity: 0.4, dashArray: '4,6'
    }).addTo(adminMap);
});

L.circleMarker(baku, {
    radius: 10, fillColor: '#1a1a2e', color: '#fff', weight: 2, fillOpacity: 1
}).bindPopup('<strong>Faradj MMC — Bakı</strong>').addTo(adminMap);

// Picker карта
let pickerMap = null;

function openSupplierModal() {
    document.getElementById('supplierModalTitle').textContent = 'Yeni ölkə';
    document.getElementById('supId').value = '';
    ['Az','Ru','En','Brands','Lat','Lon','Flag','Iso'].forEach(k => {
        const el = document.getElementById('sup'+k);
        if (el) el.value = '';
    });
    document.getElementById('supType').value = 'partner';
    document.getElementById('supSort').value = '0';
    document.getElementById('supActive').checked = true;
    document.getElementById('supplierModal').style.display = 'flex';
    initPickerMap();
}

function editSupplier(btn) {
    const s = btn.dataset?.supplier ? JSON.parse(btn.dataset.supplier) : {};
    document.getElementById('supplierModalTitle').textContent = 'Redaktə et';
    document.getElementById('supId').value = s.id || '';
    document.getElementById('supAz').value = s.country_az || '';
    document.getElementById('supRu').value = s.country_ru || '';
    document.getElementById('supEn').value = s.country_en || '';
    document.getElementById('supBrands').value = s.brands || '';
    document.getElementById('supLat').value = s.latitude || '';
    document.getElementById('supLon').value = s.longitude || '';
    document.getElementById('supType').value = s.type || 'partner';
    document.getElementById('supFlag').value = s.flag || '';
    document.getElementById('supIso').value = s.iso_code || '';
    document.getElementById('supSort').value = s.sort_order || 0;
    document.getElementById('supActive').checked = (s.is_active == 1);
    document.getElementById('supplierModal').style.display = 'flex';
    initPickerMap(s.latitude, s.longitude);
}

function initPickerMap(lat, lon) {
    setTimeout(() => {
        if (pickerMap) { pickerMap.remove(); pickerMap = null; }
        const el = document.getElementById('coordPickerMap');
        if (!el) return;
        pickerMap = L.map('coordPickerMap', {
            center: [lat ? parseFloat(lat) : 30, lon ? parseFloat(lon) : 55],
            zoom: lat ? 4 : 2,
            attributionControl: false
        });
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(pickerMap);

        let marker = null;
        if (lat && lon) {
            marker = L.marker([parseFloat(lat), parseFloat(lon)]).addTo(pickerMap);
        }

        pickerMap.on('click', function(e) {
            const {lat, lng} = e.latlng;
            document.getElementById('supLat').value = lat.toFixed(4);
            document.getElementById('supLon').value = lng.toFixed(4);
            if (marker) pickerMap.removeLayer(marker);
            marker = L.marker([lat, lng]).addTo(pickerMap);
        });
    }, 100);
}

function closeSupplierModal() {
    document.getElementById('supplierModal').style.display = 'none';
    if (pickerMap) { pickerMap.remove(); pickerMap = null; }
}

document.getElementById('supplierModal').addEventListener('click', function(e) {
    if (e.target === this) closeSupplierModal();
});
</script>

<?php include base_path('src/views/admin/layout-footer.php'); ?>
