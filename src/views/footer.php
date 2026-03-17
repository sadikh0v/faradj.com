    <div id="cookieBanner" class="cookie-banner glass-card">
        <div class="cookie-content">
            <i class="fas fa-cookie-bite cookie-icon"></i>
            <p><?= t('cookie.text') ?></p>
        </div>
        <div class="cookie-btns">
            <button id="cookieAccept" class="btn-cookie-accept"><?= t('cookie.accept') ?></button>
            <button id="cookieDecline" class="btn-cookie-decline"><?= t('cookie.decline') ?></button>
        </div>
    </div>

    <footer class="main-footer">
        <div class="container footer-content">
            <div class="footer-info">
                <p>&copy; Faradj MMC. <?= t('footer.rights') ?> <a href="/privacy.php" class="footer-privacy-link"><?= t('footer.privacy') ?></a></p>
                <p class="developer-link">
                    <?= t('footer.developed') ?>: <a href="https://faradj.com/" target="_blank">Faradj.com</a>
                </p>
            </div>
            <button id="backToTop" class="scroll-top-btn">
                <div class="pencil-wrapper">
                    <i class="fas fa-pencil-alt pencil-icon"></i>
                    <span class="rocket-smoke"></span>
                </div>
                <span class="btn-text"><?= t('footer.back_top') ?></span>
            </button>
        </div>
    </footer>

    <!-- Exit Popup -->
    <div id="exitPopup" class="exit-popup-overlay">
        <div class="exit-popup glass-card">
            <button class="exit-popup-close" id="closeExitPopup"><i class="fas fa-times"></i></button>
            <div class="exit-popup-content">
                <div class="exit-popup-icon">🎁</div>
                <h3><?= t('popup.title') ?></h3>
                <p><?= t('popup.subtitle') ?></p>
                <div class="exit-popup-offer">
                    <span class="offer-badge"><?= t('popup.offer') ?></span>
                    <p><?= t('popup.offer_sub') ?></p>
                </div>
                <a href="/b2b.php" class="btn-popup-cta"><?= t('popup.btn') ?> <i class="fas fa-arrow-right"></i></a>
                <button type="button" class="btn-popup-skip" id="skipExitPopup"><?= t('popup.skip') ?></button>
            </div>
        </div>
    </div>

    <!-- Sticky CTA -->
    <div id="stickyCta" class="sticky-cta">
        <div class="sticky-cta-inner">
            <span class="sticky-cta-badge">🔥 <?= t('sticky.badge') ?></span>
            <p><?= t('sticky.text') ?></p>
            <a href="/b2b.php" class="sticky-cta-btn"><?= t('sticky.btn') ?></a>
            <button type="button" class="sticky-cta-close" id="closeStickyCta"><i class="fas fa-times"></i></button>
        </div>
    </div>

    <!-- Callback Button -->
    <button class="callback-btn" id="callbackBtn" aria-label="Zəng sifariş et">
        <i class="fas fa-phone-alt"></i>
    </button>
    <div class="callback-modal" id="callbackModal">
        <div class="callback-box glass-card">
            <button type="button" class="callback-close" id="callbackClose"><i class="fas fa-times"></i></button>
            <div class="callback-header">
                <div class="callback-icon"><i class="fas fa-phone-alt"></i></div>
                <h3><?= t('callback.title') ?></h3>
                <p><?= t('callback.subtitle') ?></p>
            </div>
            <form class="callback-form" id="callbackForm">
                <div class="form-field">
                    <input type="text" name="name" placeholder="<?= t('callback.name') ?>" required />
                    <span class="field-icon"></span>
                </div>
                <div class="form-field">
                    <input type="tel" name="phone" placeholder="+994" required />
                    <span class="field-icon"></span>
                </div>
                <div class="form-field">
                <select name="time">
                    <option value=""><?= t('callback.time') ?></option>
                    <option value="09:00-11:00">09:00 — 11:00</option>
                    <option value="11:00-13:00">11:00 — 13:00</option>
                    <option value="13:00-15:00">13:00 — 15:00</option>
                    <option value="15:00-17:00">15:00 — 17:00</option>
                    <option value="17:00-19:00">17:00 — 19:00</option>
                </select>
                </div>
                <button type="submit" class="btn-callback-submit">
                    <i class="fas fa-paper-plane"></i> <?= t('callback.submit') ?>
                </button>
            </form>
        </div>
    </div>

    <!-- WhatsApp Button -->
    <a href="https://wa.me/<?= setting('whatsapp', '994558591211') ?>" class="whatsapp-float" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp">
        <i class="fab fa-whatsapp"></i>
    </a>

    <!-- AI Помощник -->
    <div id="aiChat" class="ai-chat">
        <button class="ai-toggle-btn" id="aiToggle">
            <span class="ai-icon-closed">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 14H9V8h2v8zm4 0h-2V8h2v8z" fill="white"/>
                    <circle cx="12" cy="12" r="10" stroke="white" stroke-width="1.5" fill="none"/>
                    <path d="M8 10h8M8 12h6M8 14h7" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
            </span>
            <span class="ai-icon-open" style="display:none"><i class="fas fa-times"></i></span>
            <span class="ai-badge" id="aiBadge">AI</span>
        </button>
        <div class="ai-window" id="aiWindow">
            <div class="ai-header">
                <div class="ai-avatar">
                    <img src="/assets/img/logo/faradj_logo.png" alt="Faradj AI">
                </div>
                <div class="ai-header-info">
                    <div class="ai-name">Faradj Assistant</div>
                    <div class="ai-status"><span class="ai-dot"></span> Online</div>
                </div>
                <button class="ai-close" id="aiClose"><i class="fas fa-times"></i></button>
            </div>
            <div class="ai-messages" id="aiMessages">
                <div class="ai-msg ai-msg-bot">
                    <div class="ai-msg-bubble">
                        👋 Salam! Mən Faradj MMC-nin AI köməkçisiyəm.<br><br>
                        Məhsullar, qiymətlər, çatdırılma və ya korporativ əməkdaşlıq barədə suallarınıza cavab verə bilərəm.
                    </div>
                    <div class="ai-msg-time"><?= date('H:i') ?></div>
                </div>
                <div class="ai-quick-btns">
                    <button class="ai-quick-btn" data-msg="Hansı məhsullarınız var?">📦 Məhsullar</button>
                    <button class="ai-quick-btn" data-msg="Çatdırılma necə işləyir?">🚚 Çatdırılma</button>
                    <button class="ai-quick-btn" data-msg="Korporativ müştəri olmaq istəyirəm">💼 Korporativ</button>
                    <button class="ai-quick-btn" data-msg="DOMS brendini haradan ala bilərəm?">✏️ DOMS</button>
                </div>
            </div>
            <div class="ai-input-area">
                <input type="text" id="aiInput" class="ai-input" placeholder="Sualınızı yazın..." maxlength="500">
                <button class="ai-send" id="aiSend"><i class="fas fa-paper-plane"></i></button>
            </div>
            <div class="ai-footer-note">Powered by Claude AI · Faradj MMC</div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.2/dist/confetti.browser.min.js"></script>
    <script src="/assets/js/script<?= $assetSuffix ?? '' ?>.js"></script>
    <script src="/assets/js/ai-chat.js"></script>
    <script src="/assets/js/app<?= $assetSuffix ?? '' ?>.js"></script>
    <script src="/assets/js/forms<?= $assetSuffix ?? '' ?>.js"></script>
    <?php if (!empty($extraJs)): ?>
        <?php foreach ((array)$extraJs as $js): ?>
    <script src="<?= htmlspecialchars(preg_replace('/\.(css|js)$/', ($assetSuffix ?? '') . '.$1', $js)) ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>

    <script>
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', async () => {
            try {
                const reg = await navigator.serviceWorker.register('/sw.js');
                if ('Notification' in window && Notification.permission === 'default') {
                    setTimeout(async () => {
                        const permission = await Notification.requestPermission();
                        if (permission === 'granted') {
                            if (typeof showToast === 'function') showToast('Bildirişlər aktivləşdirildi!', 'success');
                        }
                    }, 10000);
                }
            } catch (e) {}
        });
    }
    </script>

    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Organization",
      "name": "Faradj MMC",
      "url": "https://faradj.com",
      "logo": "https://faradj.com/assets/img/logo/faradj_logo.png",
      "description": "Azərbaycanın aparıcı dəftərxana və ofis ləvazimatları təchizatçısı",
      "foundingDate": "2011",
      "address": [
        {
          "@type": "PostalAddress",
          "streetAddress": <?= json_encode(setting('address_main', 'Bakı, İnşaatçılar pr. 106')) ?>,
          "addressLocality": "Bakı",
          "addressCountry": "AZ"
        }
      ],
      "contactPoint": [
        {
          "@type": "ContactPoint",
          "telephone": <?= json_encode(setting('phone_main', '+994-55-859-12-11')) ?>,
          "contactType": "customer service",
          "availableLanguage": ["Azerbaijani", "Russian"]
        }
      ],
      "sameAs": [
        <?= json_encode(setting('instagram', 'https://www.instagram.com/qelemstationery')) ?>,
        <?= json_encode(setting('tiktok', 'https://www.tiktok.com/@qelemstationery')) ?>,
        <?= json_encode(setting('linkedin', 'https://www.linkedin.com/in/faradjmmc')) ?>
      ]
    }
    </script>
</body>
</html>
