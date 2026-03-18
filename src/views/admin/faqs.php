<?php include base_path('src/views/admin/layout.php'); ?>
<?php $faqs = $items ?? []; ?>
<div class="admin-page">
  <div class="admin-topbar" style="display:flex;justify-content:space-between;align-items:center;">
    <h1><i class="fas fa-question-circle" style="color:#6c63ff;margin-right:10px;"></i>FAQ</h1>
    <button class="btn-primary" onclick="openFaqModal()">
        <i class="fas fa-plus"></i> Yeni sual
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
          <th>Sual (AZ)</th>
          <th>Status</th>
          <th>Əməliyyat</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($faqs as $f): ?>
        <tr>
          <td><?= (int)($f['sort_order'] ?? 0) ?></td>
          <td><?= htmlspecialchars(mb_substr($f['question_az'] ?? '', 0, 80)) ?><?= mb_strlen($f['question_az'] ?? '') > 80 ? '...' : '' ?></td>
          <td>
            <span class="status-badge <?= ($f['is_active'] ?? 1) ? 'published' : 'draft' ?>">
                <?= ($f['is_active'] ?? 1) ? 'Aktiv' : 'Gizli' ?>
            </span>
          </td>
          <td>
            <button class="action-btn edit" type="button" data-faq='<?= htmlspecialchars(json_encode($f), ENT_QUOTES, 'UTF-8') ?>' onclick="editFaq(this)" title="Redaktə">
                <i class="fas fa-edit"></i>
            </button>
            <form method="POST" action="/admin/faqs/delete" style="display:inline;">
                <input type="hidden" name="id" value="<?= (int)($f['id'] ?? 0) ?>">
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
  <?php if (empty($faqs)): ?>
  <p class="admin-empty">FAQ yoxdur. Yeni əlavə edin.</p>
  <?php endif; ?>
</div>

<!-- Modal -->
<div id="faqModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:9999;align-items:center;justify-content:center;overflow-y:auto;">
  <div style="background:#fff;border-radius:16px;padding:32px;max-width:700px;width:95%;margin:20px auto;">
    <h3 id="faqModalTitle" style="margin:0 0 24px;font-size:18px;">Yeni sual</h3>
    <form method="POST" action="/admin/faqs/save">
      <input type="hidden" name="id" id="faqId">
      
      <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;margin-bottom:16px;">
        <div>
          <label style="font-size:12px;font-weight:600;color:#666;margin-bottom:4px;display:block;">Sual (AZ)</label>
          <textarea name="question_az" id="faqQaz" rows="3" style="width:100%;padding:10px;border:1.5px solid #e8e8e8;border-radius:8px;font-size:13px;box-sizing:border-box;"></textarea>
        </div>
        <div>
          <label style="font-size:12px;font-weight:600;color:#666;margin-bottom:4px;display:block;">Sual (RU)</label>
          <textarea name="question_ru" id="faqQru" rows="3" style="width:100%;padding:10px;border:1.5px solid #e8e8e8;border-radius:8px;font-size:13px;box-sizing:border-box;"></textarea>
        </div>
        <div>
          <label style="font-size:12px;font-weight:600;color:#666;margin-bottom:4px;display:block;">Sual (EN)</label>
          <textarea name="question_en" id="faqQen" rows="3" style="width:100%;padding:10px;border:1.5px solid #e8e8e8;border-radius:8px;font-size:13px;box-sizing:border-box;"></textarea>
        </div>
      </div>
      
      <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;margin-bottom:16px;">
        <div>
          <label style="font-size:12px;font-weight:600;color:#666;margin-bottom:4px;display:block;">Cavab (AZ)</label>
          <textarea name="answer_az" id="faqAaz" rows="5" style="width:100%;padding:10px;border:1.5px solid #e8e8e8;border-radius:8px;font-size:13px;box-sizing:border-box;"></textarea>
        </div>
        <div>
          <label style="font-size:12px;font-weight:600;color:#666;margin-bottom:4px;display:block;">Cavab (RU)</label>
          <textarea name="answer_ru" id="faqAru" rows="5" style="width:100%;padding:10px;border:1.5px solid #e8e8e8;border-radius:8px;font-size:13px;box-sizing:border-box;"></textarea>
        </div>
        <div>
          <label style="font-size:12px;font-weight:600;color:#666;margin-bottom:4px;display:block;">Cavab (EN)</label>
          <textarea name="answer_en" id="faqAen" rows="5" style="width:100%;padding:10px;border:1.5px solid #e8e8e8;border-radius:8px;font-size:13px;box-sizing:border-box;"></textarea>
        </div>
      </div>

      <div style="display:flex;gap:16px;margin-bottom:24px;">
        <div>
          <label style="font-size:12px;font-weight:600;color:#666;margin-bottom:4px;display:block;">Sıra</label>
          <input type="number" name="sort_order" id="faqSort" value="0" 
                 style="width:80px;padding:10px;border:1.5px solid #e8e8e8;border-radius:8px;font-size:13px;">
        </div>
        <div style="display:flex;align-items:center;gap:8px;margin-top:20px;">
          <input type="checkbox" name="is_active" id="faqActive" value="1" checked>
          <label for="faqActive" style="font-size:13px;font-weight:600;">Aktiv</label>
        </div>
      </div>

      <div style="display:flex;gap:12px;justify-content:flex-end;">
        <button type="button" onclick="closeFaqModal()" 
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
function openFaqModal() {
    document.getElementById('faqModalTitle').textContent = 'Yeni sual';
    document.getElementById('faqId').value = '';
    ['Qaz','Qru','Qen','Aaz','Aru','Aen'].forEach(k => 
        document.getElementById('faq'+k).value = '');
    document.getElementById('faqSort').value = '0';
    document.getElementById('faqActive').checked = true;
    document.getElementById('faqModal').style.display = 'flex';
}
function editFaq(btn) {
    const f = btn.dataset?.faq ? JSON.parse(btn.dataset.faq) : {};
    document.getElementById('faqModalTitle').textContent = 'Redaktə et';
    document.getElementById('faqId').value = f.id || '';
    document.getElementById('faqQaz').value = f.question_az || '';
    document.getElementById('faqQru').value = f.question_ru || '';
    document.getElementById('faqQen').value = f.question_en || '';
    document.getElementById('faqAaz').value = f.answer_az || '';
    document.getElementById('faqAru').value = f.answer_ru || '';
    document.getElementById('faqAen').value = f.answer_en || '';
    document.getElementById('faqSort').value = f.sort_order || 0;
    document.getElementById('faqActive').checked = (f.is_active == 1);
    document.getElementById('faqModal').style.display = 'flex';
}
function closeFaqModal() {
    document.getElementById('faqModal').style.display = 'none';
}
document.getElementById('faqModal').addEventListener('click', function(e) {
    if (e.target === this) closeFaqModal();
});
</script>

<?php include base_path('src/views/admin/layout-footer.php'); ?>
