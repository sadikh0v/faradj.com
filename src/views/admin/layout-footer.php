    </div><!-- /admin-content -->
  </main><!-- /admin-main -->

  <!-- Custom Confirm Modal -->
  <div id="confirmModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:9999;align-items:center;justify-content:center;backdrop-filter:blur(4px);">
    <div style="background:#fff;border-radius:16px;padding:32px;max-width:400px;width:90%;box-shadow:0 20px 60px rgba(0,0,0,0.2);text-align:center;">
      <div style="width:56px;height:56px;background:#fff0f0;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
        <i class="fas fa-trash" style="color:#ff4757;font-size:22px;"></i>
      </div>
      <h3 id="confirmTitle" style="margin:0 0 8px;font-size:18px;color:#1a1a2e;">Silmək istəyirsiniz?</h3>
      <p id="confirmText" style="margin:0 0 24px;color:#888;font-size:14px;"></p>
      <div style="display:flex;gap:12px;justify-content:center;">
        <button id="confirmNo" type="button" style="flex:1;padding:10px 20px;border:1.5px solid #eee;background:#fff;border-radius:8px;font-size:14px;cursor:pointer;color:#666;font-weight:600;">
          Ləğv et
        </button>
        <button id="confirmYes" type="button" style="flex:1;padding:10px 20px;background:#ff4757;border:none;border-radius:8px;font-size:14px;cursor:pointer;color:#fff;font-weight:600;">
          <i class="fas fa-trash"></i> Bəli, sil
        </button>
      </div>
    </div>
  </div>

  <script src="/assets/js/admin.js"></script>
</body>
</html>
