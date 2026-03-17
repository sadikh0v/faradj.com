<?php
$isEdit = !empty($event);
$title = $isEdit ? 'Xəbəri redaktə et' : 'Yeni xəbər';
$action = $isEdit ? '/admin/events/edit?id=' . ($event['id'] ?? 0) : '/admin/events/create';
$val = function ($f) use ($old, $event) {
    return htmlspecialchars($old[$f] ?? $event[$f] ?? '');
};
$event = $event ?? [];
$old = $old ?? [];
$errors = $errors ?? [];
$includeQuill = true;
?>
<?php include base_path('src/views/admin/layout.php'); ?>

<div class="admin-page">
  <div class="page-header">
    <h1><?= $title ?></h1>
    <a href="/admin/events" class="btn-back">
      <i class="fas fa-arrow-left"></i> Geri
    </a>
  </div>

  <?php if (!empty($errors)): ?>
    <div class="flash error">
      <i class="fas fa-exclamation-circle"></i> Xətalı sahələri düzəldin
    </div>
  <?php endif; ?>

  <form method="POST" action="<?= $action ?>" enctype="multipart/form-data" class="event-form">
    <div class="form-grid">
      <div class="form-main">
        <div class="lang-tabs">
          <button type="button" class="lang-tab active" data-lang="az">AZ — Azərbaycan</button>
          <button type="button" class="lang-tab" data-lang="ru">RU — Русский</button>
          <button type="button" class="lang-tab" data-lang="en">EN — English</button>
        </div>

        <div class="lang-panel active" data-lang="az">
          <div class="form-group <?= isset($errors['title']) ? 'error' : '' ?>">
            <label>Başlıq (AZ) *</label>
            <input type="text" name="title" value="<?= $val('title') ?>" placeholder="Xəbərin başlığı">
            <?php if (isset($errors['title'])): ?>
              <span class="field-error"><?= $errors['title'] ?></span>
            <?php endif; ?>
          </div>
          <div class="form-group <?= isset($errors['excerpt']) ? 'error' : '' ?>">
            <label>Qısa mətn (AZ) *</label>
            <textarea name="excerpt" rows="2" placeholder="Kartda görünəcək qısa mətn (1-2 cümlə)"><?= $val('excerpt') ?></textarea>
            <?php if (isset($errors['excerpt'])): ?>
              <span class="field-error"><?= $errors['excerpt'] ?></span>
            <?php endif; ?>
          </div>
          <div class="form-group <?= isset($errors['full_text']) ? 'error' : '' ?>">
            <label>Tam mətn (AZ) *</label>
            <input type="hidden" name="full_text" id="full_text_input" value="<?= $val('full_text') ?>">
            <div id="editor-az" style="height:250px;border-radius:0 0 8px 8px;"></div>
            <?php if (isset($errors['full_text'])): ?>
              <span class="field-error"><?= $errors['full_text'] ?></span>
            <?php endif; ?>
          </div>
        </div>

        <div class="lang-panel" data-lang="ru">
          <div class="form-group">
            <label>Заголовок (RU)</label>
            <input type="text" name="title_ru" value="<?= $val('title_ru') ?>" placeholder="Заголовок новости">
          </div>
          <div class="form-group">
            <label>Краткий текст (RU)</label>
            <textarea name="excerpt_ru" rows="2" placeholder="Краткое описание для карточки"><?= $val('excerpt_ru') ?></textarea>
          </div>
          <div class="form-group">
            <label>Полный текст (RU)</label>
            <input type="hidden" name="full_text_ru" id="full_text_ru_input" value="<?= $val('full_text_ru') ?>">
            <div id="editor-ru" style="height:250px;border-radius:0 0 8px 8px;"></div>
          </div>
        </div>

        <div class="lang-panel" data-lang="en">
          <div class="form-group">
            <label>Title (EN)</label>
            <input type="text" name="title_en" value="<?= $val('title_en') ?>" placeholder="News title">
          </div>
          <div class="form-group">
            <label>Excerpt (EN)</label>
            <textarea name="excerpt_en" rows="2" placeholder="Short description for card"><?= $val('excerpt_en') ?></textarea>
          </div>
          <div class="form-group">
            <label>Full text (EN)</label>
            <input type="hidden" name="full_text_en" id="full_text_en_input" value="<?= $val('full_text_en') ?>">
            <div id="editor-en" style="height:250px;border-radius:0 0 8px 8px;"></div>
          </div>
        </div>
      </div>

      <div class="form-sidebar">
        <div class="sidebar-card">
          <h4>Nəşr</h4>
          <label class="checkbox-label">
            <input type="checkbox" name="is_published" value="1"
                   <?= ($old['is_published'] ?? $event['is_published'] ?? 1) ? 'checked' : '' ?>>
            <span>Aktiv (dərc edilmiş)</span>
          </label>
          <div class="form-actions">
            <button type="submit" class="btn-save">
              <i class="fas fa-save"></i>
              <?= $isEdit ? 'Yenilə' : 'Əlavə et' ?>
            </button>
            <?php if ($isEdit): ?>
            <button type="button" class="btn-delete-small" data-id="<?= (int)$event['id'] ?>" data-title="<?= htmlspecialchars($event['title'] ?? '') ?>">
              <i class="fas fa-trash"></i> Sil
            </button>
            <?php endif; ?>
          </div>
        </div>

        <div class="sidebar-card">
          <h4>Kateqoriya</h4>
          <select name="category" class="form-select">
            <?php
            $cats = [
              'xebərlər' => 'Xəbərlər',
              'yeniləmə' => 'Yeniləmə',
              'aksiyalar' => 'Aksiyalar',
              'şirkət' => 'Şirkət həyatı',
              'tədbirlər' => 'Tədbirlər',
              'sərgi' => 'Sərgi',
              'festival' => 'Festival',
            ];
            $selected = $old['category'] ?? $event['category'] ?? 'xebərlər';
            foreach ($cats as $v => $l): ?>
              <option value="<?= htmlspecialchars($v) ?>" <?= $selected === $v ? 'selected' : '' ?>>
                <?= htmlspecialchars($l) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="sidebar-card">
          <h4>Detallar</h4>
          <div class="form-group">
            <label>Tarix</label>
            <input type="date" name="event_date" value="<?= $val('event_date') ?>">
          </div>
          <div class="form-group">
            <label>Müəllif</label>
            <input type="text" name="author" value="<?= $val('author') ?: 'Faradj MMC' ?>">
          </div>
        </div>

        <div class="sidebar-card">
          <h4>Şəkil</h4>
          <?php if (!empty($event['image_url'])): ?>
            <div class="current-image">
              <img src="<?= htmlspecialchars($event['image_url']) ?>" alt="Cari şəkil">
              <span>Cari şəkil</span>
            </div>
          <?php endif; ?>
          <label class="upload-area" id="uploadArea">
            <input type="file" name="image" accept="image/*" id="imageInput" style="display:none">
            <i class="fas fa-cloud-upload-alt"></i>
            <span id="uploadText">Şəkil seçin (JPG, PNG, WebP — max 5MB)</span>
          </label>
          <div id="imagePreview"></div>
        </div>
      </div>
    </div>
  </form>
</div>

<form method="POST" action="/admin/events/delete" id="deleteForm" style="display:none;">
  <input type="hidden" name="id" id="deleteId">
</form>

<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
<script>
var editors = {
  az: new Quill('#editor-az', { theme: 'snow', modules: {
    toolbar: [['bold','italic','underline'],[{'list':'ordered'},{'list':'bullet'}],['link'],[{'header':[2,3,false]}],['clean']]
  }}),
  ru: new Quill('#editor-ru', { theme: 'snow', modules: { toolbar: [['bold','italic','underline'],[{'list':'ordered'},{'list':'bullet'}],['link'],['clean']] }}),
  en: new Quill('#editor-en', { theme: 'snow', modules: { toolbar: [['bold','italic','underline'],[{'list':'ordered'},{'list':'bullet'}],['link'],['clean']] }})
};
editors.az.root.innerHTML = document.getElementById('full_text_input').value || '';
editors.ru.root.innerHTML = document.getElementById('full_text_ru_input').value || '';
editors.en.root.innerHTML = document.getElementById('full_text_en_input').value || '';

document.querySelector('.event-form').addEventListener('submit', function() {
  document.getElementById('full_text_input').value = editors.az.root.innerHTML;
  document.getElementById('full_text_ru_input').value = editors.ru.root.innerHTML;
  document.getElementById('full_text_en_input').value = editors.en.root.innerHTML;
});

document.querySelectorAll('.lang-tab').forEach(function(tab) {
  tab.addEventListener('click', function() {
    var lang = this.dataset.lang;
    document.querySelectorAll('.lang-tab').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.lang-panel').forEach(p => p.classList.remove('active'));
    this.classList.add('active');
    document.querySelector('.lang-panel[data-lang="' + lang + '"]').classList.add('active');
  });
});

document.getElementById('uploadArea')?.addEventListener('click', () => {
  document.getElementById('imageInput').click();
});

document.getElementById('imageInput')?.addEventListener('change', function() {
  const file = this.files[0];
  if (!file) return;
  document.getElementById('uploadText').textContent = file.name;
  const reader = new FileReader();
  reader.onload = (e) => {
    document.getElementById('imagePreview').innerHTML =
      '<img src="' + e.target.result + '" style="width:100%;border-radius:8px;margin-top:8px">';
  };
  reader.readAsDataURL(file);
});

document.querySelector('.btn-delete-small')?.addEventListener('click', function() {
  var id = this.dataset.id;
  var title = this.dataset.title || '';
  customConfirm('Silmək istəyirsiniz?', title, function() {
    document.getElementById('deleteId').value = id;
    document.getElementById('deleteForm').submit();
  });
});
</script>

<?php include base_path('src/views/admin/layout-footer.php'); ?>
