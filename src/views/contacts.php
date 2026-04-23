<main class="contacts-main">
    <section class="contacts-section">
        <div class="container">
            <h2 class="section-title text-center"><?= t('contacts.title') ?></h2>

            <div class="contacts-top-grid">
                <div class="contact-info glass-card">
                    <h3><?= t('contacts.info_title') ?></h3>
                    <div class="info-item">
                        <i class="fas fa-phone-alt"></i>
                        <div>
                            <p><a href="tel:<?= preg_replace('/\s+/', '', setting('phone_main', '+994558591211')) ?>"><?= setting('phone_main', '+994 55 859 12 11') ?></a></p>
                            <p><a href="tel:<?= preg_replace('/\s+/', '', setting('phone_second', '+994105219353')) ?>"><?= setting('phone_second', '+994 10 521 93 53') ?></a></p>
                        </div>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-envelope"></i>
                        <p><a href="mailto:<?= setting('email_info', 'info@faradj.com') ?>"><?= setting('email_info', 'info@faradj.com') ?></a></p>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <div>
                            <p><?= htmlspecialchars(setting('address_main') ?: t('contacts.address')) ?></p>
                            <p><?= htmlspecialchars(setting('address_store') ?: t('contacts.store_addr')) ?></p>
                        </div>
                    </div>
                    <div class="social-links-block">
                        <h4><?= t('contacts.social') ?></h4>
                        <div class="social-icons">
                            <a href="<?= setting('instagram', 'https://www.instagram.com/qelemstationery') ?>" target="_blank" class="social-btn insta" aria-label="Instagram səhifəmiz"><i class="fab fa-instagram"></i></a>
                            <a href="<?= setting('tiktok', 'https://www.tiktok.com/@qelemstationery') ?>" target="_blank" class="social-btn tiktok" aria-label="TikTok səhifəmiz"><i class="fab fa-tiktok"></i></a>
                            <a href="<?= setting('linkedin', 'https://www.linkedin.com/in/faradjmmc') ?>" target="_blank" class="social-btn linkedin" aria-label="LinkedIn səhifəmiz"><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                </div>

                <div class="contact-form-wrapper glass-card">
                    <h3><?= t('contacts.form_title') ?></h3>
                    <form class="contact-form" id="contactForm">
                        <?= csrf_field() ?>
                        <div class="form-row">
                            <div class="form-field input-group">
                                <label for="contactName"><?= t('contacts.name') ?></label>
                                <input type="text" id="contactName" name="name" placeholder="<?= t('contacts.name') ?>" required />
                                <span class="field-icon"></span>
                            </div>
                            <div class="form-field input-group">
                                <label for="contactEmail"><?= t('contacts.email') ?></label>
                                <input type="email" id="contactEmail" name="email" placeholder="<?= t('contacts.email') ?>" required />
                                <span class="field-icon"></span>
                            </div>
                        </div>
                        <div class="form-field input-group">
                            <label for="contactPhone"><?= t('contacts.phone') ?></label>
                            <input type="tel" id="contactPhone" name="phone" placeholder="+994" />
                            <span class="field-icon"></span>
                        </div>
                        <div class="form-field input-group">
                            <label for="contactSubject"><?= t('contacts.subject') ?></label>
                            <input type="text" id="contactSubject" name="subject" placeholder="<?= t('contacts.subject') ?>" required />
                            <span class="field-icon"></span>
                        </div>
                        <div class="form-field input-group">
                            <label for="contactMessage"><?= t('contacts.message') ?></label>
                            <textarea id="contactMessage" name="message" rows="5" placeholder="<?= t('contacts.message') ?>" required></textarea>
                            <span class="field-icon"></span>
                        </div>
                        <button type="submit" class="btn-submit"><?= t('contacts.send') ?> <i class="fas fa-paper-plane"></i></button>
                    </form>
                </div>
            </div>

            <div class="maps-wrapper">
                <div class="map-card glass-card">
                    <h4><i class="fas fa-map-marker-alt"></i> <?= t('contacts.office') ?></h4>
                    <p><?= htmlspecialchars(setting('address_main') ?: t('contacts.address_street')) ?></p>
                    <div class="map-frame">
                        <iframe title="Faradj MMC ofis xəritəsi" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3039.2396380311548!2d49.81967027681036!3d40.381381171445355!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x40307d00077f71f7%3A0x2f12378035dcbd24!2sFaradj%20LLC!5e0!3m2!1sru!2saz!4v1767786877376!5m2!1sru!2saz" width="600" height="450" style="border: 0" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
                <div class="map-card glass-card">
                    <h4><i class="fas fa-store"></i> <?= t('contacts.store') ?></h4>
                    <p><?= htmlspecialchars(setting('address_store') ?: t('contacts.store_addr_street')) ?></p>
                    <div class="map-frame">
                        <iframe title="Faradj MMC mağaza xəritəsi" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3039.153336051888!2d49.824619576810356!3d40.38329407144506!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x40307d00554e3d7f%3A0xa5ae3826352b2fdd!2sQelem%20stationery!5e0!3m2!1sru!2saz!4v1767786908149!5m2!1sru!2saz" width="600" height="450" style="border: 0" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
            </div>

            <div class="faq-section">
                <h2 class="section-title text-center"><?= t('contacts.faq_title') ?></h2>
                <div class="faq-list">
                    <?php foreach (faq_items() as $item): ?>
                    <div class="faq-item">
                        <div class="faq-question"><span><?= htmlspecialchars($item['q'] ?? '', ENT_QUOTES, 'UTF-8') ?></span><i class="fas fa-chevron-down faq-arrow"></i></div>
                        <div class="faq-answer"><p><?= htmlspecialchars($item['a'] ?? '', ENT_QUOTES, 'UTF-8') ?></p></div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>
</main>
