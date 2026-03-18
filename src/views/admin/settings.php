<?php include base_path('src/views/admin/layout.php'); ?>
<?php $items = $items ?? []; ?>

<div class="admin-page">
  <div class="admin-topbar">
    <h1><i class="fas fa-sliders-h" style="color:#6c63ff;margin-right:10px;"></i>Parametrlər</h1>
  </div>

  <?php if ($flash = flash('success')): ?>
    <div class="flash success"><i class="fas fa-check-circle"></i> <?= htmlspecialchars($flash) ?></div>
  <?php endif; ?>
  <?php if ($err = flash('error')): ?>
    <div class="flash error"><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($err) ?></div>
  <?php endif; ?>

  <form method="POST" action="/admin/settings">

    <!-- Ряд 1: Əlaqə + Ünvanlar -->
    <div class="settings-grid" style="grid-template-columns:1fr 1fr;margin-bottom:20px;">
      <div class="settings-section">
        <div class="settings-section-title">
          <i class="fas fa-phone"></i> Əlaqə məlumatları
        </div>
        <?php
        $contactKeys = ['phone_main','phone_second','whatsapp','email_info','email_sales'];
        foreach ($items as $item):
          if (!in_array($item['key_name'], $contactKeys)) continue;
        ?>
        <div class="settings-row">
          <label><?= htmlspecialchars($item['label'] ?? $item['key_name']) ?></label>
          <input type="text" name="settings[<?= htmlspecialchars($item['key_name']) ?>]"
                 value="<?= htmlspecialchars($item['value'] ?? '') ?>">
        </div>
        <?php endforeach; ?>
      </div>

      <div class="settings-section">
        <div class="settings-section-title">
          <i class="fas fa-map-marker-alt"></i> Ünvanlar
        </div>
        <?php
        $addressKeys = ['address_main','address_store'];
        foreach ($items as $item):
          if (!in_array($item['key_name'], $addressKeys)) continue;
        ?>
        <div class="settings-row">
          <label><?= htmlspecialchars($item['label'] ?? $item['key_name']) ?></label>
          <input type="text" name="settings[<?= htmlspecialchars($item['key_name']) ?>]"
                 value="<?= htmlspecialchars($item['value'] ?? '') ?>">
        </div>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Ряд 2: Sosial şəbəkələr на всю ширину, поля в 3 колонки -->
    <div class="settings-grid" style="grid-template-columns:1fr;margin-bottom:20px;">
      <div class="settings-section">
        <div class="settings-section-title">
          <i class="fas fa-share-alt"></i> Sosial şəbəkələr
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:0;">
        <?php
        $socialKeys = ['instagram','tiktok','linkedin'];
        foreach ($items as $item):
          if (!in_array($item['key_name'], $socialKeys)) continue;
        ?>
        <div class="settings-row">
          <label><?= htmlspecialchars($item['label'] ?? $item['key_name']) ?></label>
          <input type="url" name="settings[<?= htmlspecialchars($item['key_name']) ?>]"
                 value="<?= htmlspecialchars($item['value'] ?? '') ?>">
        </div>
        <?php endforeach; ?>
        </div>
      </div>
    </div>

    <!-- SEO — на всю ширину -->
    <div class="settings-grid" style="grid-template-columns:1fr;margin-bottom:20px;">
      <div class="settings-section">
        <div class="settings-section-title">
          <i class="fas fa-search"></i> SEO
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:0;">
        <?php
        $seoKeys = ['seo_home_title','seo_home_desc','seo_events_title','seo_events_desc','seo_partners_title','seo_partners_desc','seo_contacts_title','seo_contacts_desc','seo_b2b_title','seo_b2b_desc'];
        foreach ($items as $item):
          if (!in_array($item['key_name'], $seoKeys)) continue;
        ?>
        <div class="settings-row">
          <label><?= htmlspecialchars($item['label'] ?? $item['key_name']) ?></label>
          <input type="text" name="settings[<?= htmlspecialchars($item['key_name']) ?>]"
                 value="<?= htmlspecialchars($item['value'] ?? '') ?>"
                 placeholder="<?= htmlspecialchars($item['label'] ?? '') ?>">
        </div>
        <?php endforeach; ?>
        </div>
      </div>
    </div>

    <div style="margin-top:24px;">
      <button type="submit" class="btn-primary">
        <i class="fas fa-save"></i> Yadda saxla
      </button>
    </div>
  </form>
</div>

<?php include base_path('src/views/admin/layout-footer.php'); ?>
