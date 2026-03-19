<main>
    <section id="hero" class="hero-section">
        <div class="container hero-container">
            <div class="hero-content">
                <h1 class="hero-title">
                    <span class="highlight-text" id="typingText" data-typing-text="<?= t('home.hero.title') ?>"></span>
                </h1>
                <p class="hero-subtitle">
                    <?= t('home.hero.desc') ?>
                </p>
                <div class="hero-btns">
                    <a href="https://catalog.faradj.com" target="_blank" class="btn btn-outline"><?= t('home.hero.btn1') ?></a>
                    <a href="/contacts.php" class="btn btn-text"><?= t('home.hero.btn2') ?> <i class="fas fa-arrow-right"></i></a>
                </div>
                <div class="live-counter">
                    <span class="live-dot"></span>
                    <span><?= t('home.live_counter_before') ?></span>
                    <strong id="orderCount">0</strong>
                    <span><?= t('home.live_counter_after') ?></span>
                </div>
            </div>
            <div class="hero-visual"></div>
        </div>
    </section>

    <section id="doms" class="doms-section">
        <div class="container">
            <div class="doms-wrapper glass-card animate-on-scroll">
                <div class="doms-info">
                    <span class="badge"><?= t('home.doms.badge') ?></span>
                    <div class="doms-logo-placeholder">
                        <?php img_webp('/assets/img/logo/doms.png', 'DOMS', ['style' => 'max-width: 300px']); ?>
                    </div>
                    <p><?= t('home.doms.desc') ?></p>
                </div>
                <div class="doms-instagram-embed">
                    <iframe
                        src="https://www.instagram.com/doms.azerbaijan_/embed"
                        frameborder="0"
                        scrolling="no"
                        allowtransparency="true"
                        class="instagram-iframe"
                    ></iframe>
                </div>
            </div>
        </div>
    </section>

    <!-- Marquee брендов -->
    <section class="marquee-section" id="brandsMarquee" data-marquee="css">
        <h3 class="marquee-title text-center"><?= t('home.brands_title') ?></h3>
        <div class="marquee-track">
            <?php
            $marqueeItems = !empty($marquee_brands) ? $marquee_brands : [];
            $usePlaceholder = empty($marqueeItems);
            $items = $usePlaceholder
                ? array_fill(0, 8, ['name' => 'Faradj MMC', 'logo' => '/assets/img/logo/faradj_logo.png', 'placeholder' => true])
                : array_merge($marqueeItems, $marqueeItems);
            ?>
            <?php foreach ($items as $item): ?>
            <div class="marquee-item <?= !empty($item['placeholder']) ? 'marquee-placeholder' : '' ?>">
                <?php if (!empty($item['logo'])): ?>
                <img src="<?= htmlspecialchars($item['logo']) ?>"
                     alt="<?= htmlspecialchars($item['name']) ?>"
                     loading="lazy">
                <?php else: ?>
                <span><?= htmlspecialchars($item['name']) ?></span>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Marquee клиентов (обратное направление) -->
    <?php if (!empty($marquee_clients) || $usePlaceholder): ?>
    <section id="partners" class="marquee-section marquee-reverse alt-bg" data-marquee="css">
        <h3 class="marquee-title text-center"><?= t('home.clients_title') ?></h3>
        <div class="marquee-track">
            <?php
            $clientItems = !empty($marquee_clients) ? $marquee_clients : [];
            $clientItems = !empty($clientItems)
                ? array_merge($clientItems, $clientItems)
                : array_fill(0, 8, ['name' => 'Faradj MMC', 'logo' => '/assets/img/logo/faradj_logo.png', 'placeholder' => true]);
            ?>
            <?php foreach ($clientItems as $item): ?>
            <div class="marquee-item <?= !empty($item['placeholder']) ? 'marquee-placeholder' : '' ?>">
                <?php if (!empty($item['logo'])): ?>
                <img src="<?= htmlspecialchars($item['logo']) ?>"
                     alt="<?= htmlspecialchars($item['name']) ?>"
                     loading="lazy">
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

    <section id="products" class="products-section">
        <div class="container">
            <h2 class="section-title text-center animate-on-scroll"><?= t('home.products_title') ?></h2>
            <div class="products-grid">
                <a href="https://catalog.faradj.com" target="_blank" class="product-card glass-card animate-on-scroll">
                    <div class="card-icon"><i class="fas fa-briefcase"></i></div>
                    <h3><?= t('product.office') ?></h3>
                    <p><?= t('product.office_desc') ?></p>
                </a>
                <a href="https://catalog.faradj.com" target="_blank" class="product-card glass-card animate-on-scroll">
                    <div class="card-icon"><i class="fas fa-graduation-cap"></i></div>
                    <h3><?= t('product.school') ?></h3>
                    <p><?= t('product.school_desc') ?></p>
                </a>
                <a href="https://catalog.faradj.com" target="_blank" class="product-card glass-card animate-on-scroll">
                    <div class="card-icon"><i class="fas fa-palette"></i></div>
                    <h3><?= t('product.art') ?></h3>
                    <p><?= t('product.art_desc') ?></p>
                </a>
                <a href="https://catalog.faradj.com" target="_blank" class="product-card glass-card animate-on-scroll">
                    <div class="card-icon"><i class="fas fa-gamepad"></i></div>
                    <h3><?= t('product.toys') ?></h3>
                    <p><?= t('product.toys_desc') ?></p>
                </a>
                <a href="https://catalog.faradj.com" target="_blank" class="product-card glass-card animate-on-scroll">
                    <div class="card-icon"><i class="fas fa-puzzle-piece"></i></div>
                    <h3><?= t('product.puzzles') ?></h3>
                    <p><?= t('product.puzzles_desc') ?></p>
                </a>
                <a href="https://catalog.faradj.com" target="_blank" class="product-card glass-card animate-on-scroll">
                    <div class="card-icon"><i class="fas fa-box-open"></i></div>
                    <h3><?= t('product.household') ?></h3>
                    <p><?= t('product.household_desc') ?></p>
                </a>
            </div>
        </div>
    </section>

    <section id="events" class="events-section">
        <div class="container">
            <h2 class="section-title text-center animate-on-scroll"><?= t('home.events_title') ?></h2>
            <div class="info-grid mb-50">
                <div class="event-block glass-card animate-on-scroll">
                    <h3><i class="fas fa-building"></i> <?= t('home.about.title') ?></h3>
                    <p><?= t('home.about.desc') ?></p>
                </div>
                <div class="event-block glass-card animate-on-scroll">
                    <h3><i class="fas fa-bullseye"></i> <?= t('home.about.goal_title') ?></h3>
                    <p><?= t('home.about.goal_desc') ?></p>
                </div>
                <div class="event-block glass-card animate-on-scroll">
                    <h3><i class="fas fa-hand-holding-heart"></i> <?= t('home.about.values_title') ?></h3>
                    <p><?= t('home.about.values_desc') ?></p>
                </div>
            </div>
        </div>
    </section>

    <section class="why-us-section">
        <div class="container">
            <div class="section-header">
                <h2><?= t('home.why_us_title') ?></h2>
                <div class="section-divider"></div>
            </div>
            <div class="why-us-grid">
                <?php
                $reasons = [
                    ['icon'=>'fas fa-certificate', 'color'=>'#6c63ff', 'key'=>'official'],
                    ['icon'=>'fas fa-shipping-fast', 'color'=>'#00b894', 'key'=>'delivery'],
                    ['icon'=>'fas fa-handshake', 'color'=>'#e91e8c', 'key'=>'corporate'],
                    ['icon'=>'fas fa-globe', 'color'=>'#f39c12', 'key'=>'brands'],
                    ['icon'=>'fas fa-shield-alt', 'color'=>'#2b5876', 'key'=>'quality'],
                    ['icon'=>'fas fa-headset', 'color'=>'#e74c3c', 'key'=>'support'],
                ];
                foreach ($reasons as $r): ?>
                <div class="why-us-card glass-card">
                    <div class="why-us-icon" style="background:<?= $r['color'] ?>20;color:<?= $r['color'] ?>">
                        <i class="<?= $r['icon'] ?>"></i>
                    </div>
                    <h3><?= t('home.why_' . $r['key'] . '_title') ?></h3>
                    <p><?= t('home.why_' . $r['key'] . '_text') ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="testimonials-section">
        <div class="container">
            <h2 class="section-title text-center animate-on-scroll"><?= t('testimonials.title') ?></h2>
            <div class="testimonials-track-wrap">
                <div class="testimonials-track" id="testimonialsTrack">
                    <?php
                    $testimonials = [];
                    try {
                        if (function_exists('db')) {
                            $testimonials = db()->query("SELECT * FROM testimonials WHERE is_active=1 ORDER BY sort_order ASC")->fetchAll(PDO::FETCH_ASSOC);
                            $lang = currentLang();
                            $textKey = 'text_' . $lang;
                            $testimonials = array_map(function ($t) use ($textKey) {
                                $name = $t['name'] ?? '';
                                $text = $t[$textKey] ?? $t['text_az'] ?? '';
                                $init = preg_match('/^([A-Za-zƏəÖöÜüİıÇçĞğŞş])([A-Za-zƏəÖöÜüİıÇçĞğŞş])?/u', $name, $m) ? mb_strtoupper($m[1] . ($m[2] ?? '')) : '?';
                                return ['name' => $name, 'text' => $text, 'init' => $init, 'rating' => (int)($t['rating'] ?? 5), 'verified' => $t['is_verified'] ?? 1];
                            }, $testimonials);
                        }
                    } catch (Throwable $e) {}
                    if (empty($testimonials)) {
                        $testimonials = [
                        ['name' => 'Anar Məmmədov', 'text' => 'Faradj MMC ilə əməkdaşlığımız çox uğurlu oldu. Keyfiyyətli məhsullar, sürətli çatdırılma və peşəkar yanaşma.', 'init' => 'AM'],
                        ['name' => 'Leyla Həsənova', 'text' => 'Korporativ sifarişlərimizi həmişə vaxtında və eksiksiz yerinə yetirirlər. Çox məmnunam.', 'init' => 'LH'],
                        ['name' => 'Rauf Əliyev', 'text' => 'DOMS məhsulları üçün ən yaxşı distribyutor. Qiymətlər əlverişli, xidmət peşəkardır.', 'init' => 'RƏ'],
                        ['name' => 'Nigar Quliyeva', 'text' => 'Yaradıcılıq ləvazimatlarının geniş çeşidi və keyfiyyəti üçün minnətdarıq.', 'init' => 'NQ'],
                        ['name' => 'Tural Babayev', 'text' => 'Hər dəfə sifariş verdikdə məmnun qalıram. Çatdırılma çox sürətlidir.', 'init' => 'TB'],
                        ['name' => 'Sevinc Rzayeva', 'text' => 'Məktəb ləvazimatları üçün ən etibarlı ünvan. Uşaqlarım çox xoşlayır.', 'init' => 'SR'],
                        ['name' => 'Kamran Hüseynov', 'text' => 'Ofis üçün lazım olan hər şeyi burada tapıram. Geniş çeşid, münasib qiymət.', 'init' => 'KH'],
                        ['name' => 'Aysel Nəcəfova', 'text' => 'İlk sifarişimdən etibarən daimi müştəriyəm. Xidmət səviyyəsi çox yüksəkdir.', 'init' => 'AN'],
                        ['name' => 'Elnur Qasımov', 'text' => 'Tender sifarişlərimi həmişə Faradj MMC vasitəsilə veririm. Etibarlı tərəfdaş.', 'init' => 'EQ'],
                        ['name' => 'Günel Musayeva', 'text' => 'DOMS rəssamlıq ləvazimatları çox keyfiyyətlidir. Mütləq tövsiyə edirəm.', 'init' => 'GM'],
                        ['name' => 'Fərid Quluzadə', 'text' => 'Sürətli cavab, operativ çatdırılma. Biznes üçün ideal tərəfdaş.', 'init' => 'FQ'],
                        ['name' => 'Xədicə Əliyeva', 'text' => 'Uşaq oyuncaqları bölməsi çox zəngindir. Keyfiyyətə tam əminəm.', 'init' => 'XƏ'],
                        ['name' => 'Samir İsmayılov', 'text' => 'Korporativ müqavilə şərtləri çox əlverişlidir. Uzunmüddətli əməkdaşlıq.', 'init' => 'Sİ'],
                        ['name' => 'Lalə Hacıyeva', 'text' => 'Yaradıcılıq mərkəzimiz üçün bütün materialları buradan alırıq.', 'init' => 'LH'],
                        ['name' => 'Vüsal Məmmədli', 'text' => 'Peşəkar komanda, keyfiyyətli məhsul. Hər zaman tövsiyə edirəm.', 'init' => 'VM'],
                        ['name' => 'Nərmin Sultanova', 'text' => 'Qiymət-keyfiyyət nisbəti mükəmməldir. Çox razıyam.', 'init' => 'NS'],
                        ['name' => 'Rəşad Həsənli', 'text' => 'Sifarişim həmişə tam və vaxtında gəlir. Etibarlı şirkətdir.', 'init' => 'RH'],
                        ['name' => 'Zəhra Əhmədova', 'text' => 'Ofis ləvazimatları üçün ən yaxşı seçim. Böyük çeşid.', 'init' => 'ZƏ'],
                        ['name' => 'Murad Cavadov', 'text' => 'Faradj MMC ilə işləmək həmişə zövqlüdür. Peşəkar yanaşma.', 'init' => 'MC'],
                        ['name' => 'İlahə Rəsulova', 'text' => 'Sifariş prosesi çox asandır. Sayt da çox rahatdır.', 'init' => 'İR'],
                        ['name' => 'Orxan Nəsirov', 'text' => 'Uzun illərdir müştəriyəm. Heç vaxt məyus olmamışam.', 'init' => 'ON'],
                        ['name' => 'Türkan Əlizadə', 'text' => 'Məktəb mövsümündə böyük kömək oldu. Sürətli xidmət.', 'init' => 'TƏ'],
                        ];
                    }
                    foreach ($testimonials as $item):
                        $rating = (int)($item['rating'] ?? 5);
                        $verified = $item['verified'] ?? true;
                    ?>
                    <div class="testimonial-card glass-card">
                        <span class="testimonial-quote">"</span>
                        <p class="testimonial-text"><?= htmlspecialchars($item['text']) ?></p>
                        <div class="testimonial-rating"><?= str_repeat('<i class="fas fa-star"></i>', $rating) ?></div>
                        <div class="testimonial-avatar"><?= htmlspecialchars($item['init']) ?></div>
                        <h4><?= htmlspecialchars($item['name']) ?></h4>
                        <?php if ($verified): ?><span class="verified-badge">✓ <?= t('testimonials.verified') ?></span><?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>

    <section id="contacts" class="contacts-section">
        <div class="container">
            <h2 class="section-title text-center animate-on-scroll"><?= t('contacts.title') ?></h2>
            <div class="contacts-grid">
                <div class="contact-info glass-card animate-on-scroll">
                    <div class="info-item">
                        <i class="fas fa-phone-alt"></i>
                        <div>
                            <p><a href="tel:<?= preg_replace('/\s+/', '', setting('phone_main', '+994558591211')) ?>"><?= setting('phone_main', '+994 55 859 12 11') ?></a></p>
                            <p><a href="tel:<?= preg_replace('/\s+/', '', setting('phone_second', '+994105219353')) ?>"><?= setting('phone_second', '+994 10 521 93 53') ?></a></p>
                        </div>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-envelope"></i>
                        <p><a href="mailto:<?= setting('email_sales', 'Sales@faradj.org') ?>"><?= setting('email_sales', 'Sales@faradj.org') ?></a></p>
                    </div>
                    <div class="social-links-block">
                        <h4><?= t('contacts.social') ?></h4>
                        <div class="social-icons">
                            <a href="<?= setting('instagram', 'https://www.instagram.com/qelemstationery') ?>" target="_blank" class="social-btn insta"><i class="fab fa-instagram"></i></a>
                            <a href="<?= setting('tiktok', 'https://www.tiktok.com/@qelemstationery') ?>" target="_blank" class="social-btn tiktok"><i class="fab fa-tiktok"></i></a>
                            <a href="<?= setting('linkedin', 'https://www.linkedin.com/in/faradjmmc') ?>" target="_blank" class="social-btn linkedin"><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                </div>
                <div class="maps-wrapper">
                    <div class="map-card glass-card animate-on-scroll">
                        <h4><i class="fas fa-map-marker-alt"></i><?= t('contacts.office') ?></h4>
                        <p><?= setting('address_main', 'Bakı şəhəri, İnşaatçılar pr. 106') ?></p>
                        <div class="map-frame">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3039.2396380311548!2d49.81967027681036!3d40.381381171445355!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x40307d00077f71f7%3A0x2f12378035dcbd24!2sFaradj%20LLC!5e0!3m2!1sru!2saz!4v1767786877376!5m2!1sru!2saz" width="600" height="450" style="border: 0" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                    </div>
                    <div class="map-card glass-card animate-on-scroll">
                        <h4><i class="fas fa-store"></i><?= t('contacts.store') ?></h4>
                        <p><?= setting('address_store', 'Bakı şəhəri, Murtuza Muxtarov 179') ?></p>
                        <div class="map-frame">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3039.153336051888!2d49.824619576810356!3d40.38329407144506!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x40307d00554e3d7f%3A0xa5ae3826352b2fdd!2sQelem%20stationery!5e0!3m2!1sru!2saz!4v1767786908149!5m2!1sru!2saz" width="600" height="450" style="border: 0" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Instagram секция -->
    <section class="instagram-section" id="instagramSection">
        <div class="container">
            <div class="section-header text-center">
                <span class="section-badge"><i class="fab fa-instagram"></i> Instagram</span>
                <h2 class="section-title">@qelemstationery</h2>
                <p class="section-subtitle"><?= t('home.instagram_sub') ?></p>
            </div>
            <div class="instagram-grid" id="instagramGrid">
                <?php for ($i = 0; $i < 6; $i++): ?>
                <div class="ig-skeleton skeleton"></div>
                <?php endfor; ?>
            </div>
            <div class="instagram-follow">
                <a href="<?= setting('instagram', 'https://instagram.com/qelemstationery') ?>" target="_blank" rel="noopener" class="ig-follow-btn">
                    <i class="fab fa-instagram"></i>
                    <?= t('home.instagram_follow') ?>
                </a>
            </div>
        </div>
    </section>
</main>
