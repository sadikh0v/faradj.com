    <!-- Cookie Consent Banner -->
    <div id="cookieConsent" class="cookie-consent hidden">
        <div class="cookie-consent-inner">
            <div class="cookie-consent-header">
                <div class="cookie-consent-icon">🍪</div>
                <h3>Məlumatların Qorunması və Cookie Siyasəti</h3>
            </div>
            <p class="cookie-consent-text">
                Azərbaycan Respublikasının "Fərdi məlumatlar haqqında" Qanunu və 
                Avropa İttifaqının GDPR (2016/679) tələblərinə uyğun olaraq, 
                saytımızın düzgün fəaliyyəti, istifadəçi təcrübəsinin 
                təkmilləşdirilməsi və statistik təhlil məqsədilə cookie fayllarından 
                istifadə edirik. Seçimlərinizi aşağıda idarə edə bilərsiniz.
            </p>
            
            <div class="cookie-categories">
                <div class="cookie-category">
                    <div class="cookie-category-header">
                        <div>
                            <strong>Zəruri Cookie-lər</strong>
                            <p>Saytın texniki fəaliyyəti üçün vacibdir. Deaktiv edilə bilməz.</p>
                        </div>
                        <div class="cookie-toggle disabled">
                            <div class="toggle-track active">
                                <div class="toggle-thumb"></div>
                            </div>
                            <span>Həmişə aktiv</span>
                        </div>
                    </div>
                </div>
                
                <div class="cookie-category">
                    <div class="cookie-category-header">
                        <div>
                            <strong>Analitika Cookie-ləri</strong>
                            <p>Google Analytics və Yandex.Metrica vasitəsilə anonim statistika toplanır. Şəxsi məlumatlar emal edilmir.</p>
                        </div>
                        <div class="cookie-toggle">
                            <label class="toggle-label">
                                <input type="checkbox" id="analyticsConsent" checked>
                                <div class="toggle-track">
                                    <div class="toggle-thumb"></div>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="cookie-category">
                    <div class="cookie-category-header">
                        <div>
                            <strong>Marketinq Cookie-ləri</strong>
                            <p>Hədəflənmiş reklamlar və təkrar marketinq kampaniyaları üçün istifadə olunur.</p>
                        </div>
                        <div class="cookie-toggle">
                            <label class="toggle-label">
                                <input type="checkbox" id="marketingConsent">
                                <div class="toggle-track">
                                    <div class="toggle-thumb"></div>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <p class="cookie-legal">
                Ətraflı məlumat üçün <a href="/privacy">Məxfilik Siyasəti</a>mizi 
                oxuyun. Razılığınızı istənilən vaxt geri götürmək hüququnuz vardır.
            </p>

            <div class="cookie-consent-actions">
                <button id="cookieReject" class="cookie-btn-reject">
                    Yalnız zəruri
                </button>
                <button id="cookieSavePrefs" class="cookie-btn-prefs">
                    Seçimi saxla
                </button>
                <button id="cookieAcceptAll" class="cookie-btn-accept">
                    Hamısını qəbul et
                </button>
            </div>
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
            <button type="button" class="sticky-cta-close" id="closeStickyCta" aria-label="Bağla"><i class="fas fa-times"></i></button>
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
                <?= csrf_field() ?>
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

    <script defer src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.2/dist/confetti.browser.min.js"></script>
    <?php if (($currentPage ?? '') === 'partners'): ?>
    <script defer src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <?php endif; ?>
    <script defer src="/assets/js/script<?= $assetSuffix ?? '' ?>.js"></script>
    <script defer src="/assets/js/app<?= $assetSuffix ?? '' ?>.js"></script>
    <script defer src="/assets/js/forms<?= $assetSuffix ?? '' ?>.js"></script>
    <?php if (!empty($extraJs)): ?>
        <?php foreach ((array)$extraJs as $js): ?>
    <?php
        $compiledJs = preg_replace('/\.(css|js)$/', ($assetSuffix ?? '') . '.$1', $js);
        $isLocalJs = is_string($compiledJs) && str_starts_with($compiledJs, '/');
        if (!$isLocalJs || file_exists(__DIR__ . '/../../public' . $compiledJs)):
    ?>
    <script defer src="<?= htmlspecialchars($compiledJs) ?>"></script>
    <?php endif; ?>
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

    <!-- Schema.org LocalBusiness -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "LocalBusiness",
      "@id": "https://faradj.com",
      "name": "Faradj MMC",
      "description": "Azərbaycanın aparıcı dəftərxana ləvazimatları idxalçısı və DOMS brendinin rəsmi distribyutoru",
      "url": "https://faradj.com",
      "logo": "https://faradj.com/assets/img/logo/faradj_logo.png",
      "image": "https://faradj.com/assets/img/og-image.php",
      "telephone": "+994558591211",
      "email": "info@faradj.com",
      "foundingDate": "2011",
      "address": {
        "@type": "PostalAddress",
        "streetAddress": "İnşaatçılar prospekti, 106",
        "addressLocality": "Bakı",
        "addressCountry": "AZ"
      },
      "geo": {
        "@type": "GeoCoordinates",
        "latitude": "40.4093",
        "longitude": "49.8671"
      },
      "openingHoursSpecification": [
        {
          "@type": "OpeningHoursSpecification",
          "dayOfWeek": ["Monday","Tuesday","Wednesday","Thursday","Friday"],
          "opens": "09:00",
          "closes": "18:00"
        },
        {
          "@type": "OpeningHoursSpecification",
          "dayOfWeek": "Saturday",
          "opens": "10:00",
          "closes": "15:00"
        }
      ],
      "sameAs": [
        "https://www.instagram.com/qelemstationery",
        "https://www.tiktok.com/@qelemstationery",
        "https://www.linkedin.com/in/faradjmmc"
      ],
      "priceRange": "₼₼",
      "currenciesAccepted": "AZN",
      "paymentAccepted": "Cash, Bank Transfer, Credit Card"
    }
    </script>
</body>
</html>
