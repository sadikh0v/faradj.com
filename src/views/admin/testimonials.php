<?php include base_path('src/views/admin/layout.php'); ?>
<?php $testimonials = $items ?? []; ?>
<div class="admin-page">
  <div class="admin-topbar" style="display:flex;justify-content:space-between;align-items:center;">
    <h1><i class="fas fa-star" style="color:#6c63ff;margin-right:10px;"></i>Rəylər</h1>
    <button class="btn-primary" onclick="openTestimonialModal()">
        <i class="fas fa-plus"></i> Yeni rəy
    </button>
  </div>

  <?php if ($msg = flash('success')): ?>
    <div class="flash success">
      <i class="fas fa-check-circle"></i> <?= htmlspecialchars($msg) ?>
    </div>
  <?php endif; ?>

  <div class="admin-table-wrap">
    <table class="admin-table">
      <thead>
        <tr>
          <th>#</th>
          <th>Ad</th>
          <th>Şirkət</th>
          <th>Rəy (AZ)</th>
          <th>Reytinq</th>
          <th>Status</th>
          <th>Əməliyyat</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($testimonials as $t): ?>
        <tr>
          <td><?= (int)($t['sort_order'] ?? 0) ?></td>
          <td><?= htmlspecialchars($t['name'] ?? '—') ?></td>
          <td><?= htmlspecialchars($t['company'] ?? '—') ?></td>
          <td><?= htmlspecialchars(mb_substr($t['text_az'] ?? '', 0, 60)) ?><?= mb_strlen($t['text_az'] ?? '') > 60 ? '...' : '' ?></td>
          <td><?= str_repeat('★', (int)($t['rating'] ?? 5)) ?></td>
          <td>
            <span class="status-badge <?= ($t['is_active'] ?? 1) ? 'published' : 'draft' ?>">
                <?= ($t['is_active'] ?? 1) ? 'Aktiv' : 'Gizli' ?>
            </span>
          </td>
          <td>
            <button class="action-btn edit" type="button" data-testimonial='<?= htmlspecialchars(json_encode($t), ENT_QUOTES, 'UTF-8') ?>' onclick="editTestimonial(this)" title="Redaktə">
                <i class="fas fa-edit"></i>
            </button>
            <form method="POST" action="/admin/testimonials/delete" style="display:inline;">
                <input type="hidden" name="id" value="<?= (int)($t['id'] ?? 0) ?>">
                <button type="button" class="action-btn delete"
                    onclick="if(confirm('Silmək istəyirsiniz?')) this.closest('form').submit()">
                    <i class="fas fa-trash"></i>
                </button>
            </form>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <?php if (empty($testimonials)): ?>
  <p class="admin-empty">Rəy yoxdur. Yeni əlavə edin.</p>
  <?php endif; ?>
</div>

<!-- Modal -->
<div id="testimonialModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:9999;align-items:center;justify-content:center;overflow-y:auto;">
  <div style="background:#fff;border-radius:16px;padding:32px;max-width:700px;width:95%;margin:20px auto;">
    <h3 id="testimonialModalTitle" style="margin:0 0 24px;font-size:18px;">Yeni rəy</h3>
    <form method="POST" action="/admin/testimonials/save">
      <input type="hidden" name="id" id="testimonialId">
      
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
        <div>
          <label style="font-size:12px;font-weight:600;color:#666;margin-bottom:4px;display:block;">Ad</label>
          <input type="text" name="name" id="testimonialName" required
                 style="width:100%;padding:10px;border:1.5px solid #e8e8e8;border-radius:8px;font-size:13px;box-sizing:border-box;">
        </div>
        <div>
          <label style="font-size:12px;font-weight:600;color:#666;margin-bottom:4px;display:block;">Şirkət</label>
          <input type="text" name="company" id="testimonialCompany"
                 style="width:100%;padding:10px;border:1.5px solid #e8e8e8;border-radius:8px;font-size:13px;box-sizing:border-box;">
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;margin-bottom:16px;">
        <div>
          <label style="font-size:12px;font-weight:600;color:#666;margin-bottom:4px;display:block;">Rəy (AZ)</label>
          <textarea name="text_az" id="testimonialTextAz" rows="4" required
                    style="width:100%;padding:10px;border:1.5px solid #e8e8e8;border-radius:8px;font-size:13px;box-sizing:border-box;"></textarea>
        </div>
        <div>
          <label style="font-size:12px;font-weight:600;color:#666;margin-bottom:4px;display:block;">Rəy (RU)</label>
          <textarea name="text_ru" id="testimonialTextRu" rows="4"
                    style="width:100%;padding:10px;border:1.5px solid #e8e8e8;border-radius:8px;font-size:13px;box-sizing:border-box;"></textarea>
        </div>
        <div>
          <label style="font-size:12px;font-weight:600;color:#666;margin-bottom:4px;display:block;">Rəy (EN)</label>
          <textarea name="text_en" id="testimonialTextEn" rows="4"
                    style="width:100%;padding:10px;border:1.5px solid #e8e8e8;border-radius:8px;font-size:13px;box-sizing:border-box;"></textarea>
        </div>
      </div>

      <div style="display:flex;gap:16px;margin-bottom:24px;flex-wrap:wrap;align-items:center;">
        <div>
          <label style="font-size:12px;font-weight:600;color:#666;margin-bottom:4px;display:block;">Reytinq (1-5 ★)</label>
          <input type="number" name="rating" id="testimonialRating" min="1" max="5" value="5"
                 style="width:80px;padding:10px;border:1.5px solid #e8e8e8;border-radius:8px;font-size:13px;">
        </div>
        <div>
          <label style="font-size:12px;font-weight:600;color:#666;margin-bottom:4px;display:block;">Sıra</label>
          <input type="number" name="sort_order" id="testimonialSort" value="0" min="0"
                 style="width:80px;padding:10px;border:1.5px solid #e8e8e8;border-radius:8px;font-size:13px;">
        </div>
        <div style="display:flex;align-items:center;gap:12px;margin-top:20px;">
          <label style="display:flex;align-items:center;gap:6px;cursor:pointer;">
            <input type="checkbox" name="is_verified" id="testimonialVerified" value="1" checked>
            <span style="font-size:13px;font-weight:600;">Təsdiqlənmiş</span>
          </label>
          <label style="display:flex;align-items:center;gap:6px;cursor:pointer;">
            <input type="checkbox" name="is_active" id="testimonialActive" value="1" checked>
            <span style="font-size:13px;font-weight:600;">Aktiv</span>
          </label>
        </div>
      </div>

      <div style="display:flex;gap:12px;justify-content:flex-end;">
        <button type="button" onclick="closeTestimonialModal()" 
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

<script>
function openTestimonialModal() {
    document.getElementById('testimonialModalTitle').textContent = 'Yeni rəy';
    document.getElementById('testimonialId').value = '';
    document.getElementById('testimonialName').value = '';
    document.getElementById('testimonialCompany').value = '';
    document.getElementById('testimonialTextAz').value = '';
    document.getElementById('testimonialTextRu').value = '';
    document.getElementById('testimonialTextEn').value = '';
    document.getElementById('testimonialRating').value = '5';
    document.getElementById('testimonialSort').value = '0';
    document.getElementById('testimonialVerified').checked = true;
    document.getElementById('testimonialActive').checked = true;
    document.getElementById('testimonialModal').style.display = 'flex';
}
function editTestimonial(btn) {
    const t = btn.dataset?.testimonial ? JSON.parse(btn.dataset.testimonial) : {};
    document.getElementById('testimonialModalTitle').textContent = 'Redaktə et';
    document.getElementById('testimonialId').value = t.id || '';
    document.getElementById('testimonialName').value = t.name || '';
    document.getElementById('testimonialCompany').value = t.company || '';
    document.getElementById('testimonialTextAz').value = t.text_az || '';
    document.getElementById('testimonialTextRu').value = t.text_ru || '';
    document.getElementById('testimonialTextEn').value = t.text_en || '';
    document.getElementById('testimonialRating').value = parseInt(t.rating) || 5;
    document.getElementById('testimonialSort').value = t.sort_order || 0;
    document.getElementById('testimonialVerified').checked = (t.is_verified == 1);
    document.getElementById('testimonialActive').checked = (t.is_active == 1);
    document.getElementById('testimonialModal').style.display = 'flex';
}
function closeTestimonialModal() {
    document.getElementById('testimonialModal').style.display = 'none';
}
document.getElementById('testimonialModal').addEventListener('click', function(e) {
    if (e.target === this) closeTestimonialModal();
});
</script>

<?php include base_path('src/views/admin/layout-footer.php'); ?>
