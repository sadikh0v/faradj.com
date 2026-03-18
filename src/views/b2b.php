<section class="b2b-section">
    <div class="container">
        <div class="b2b-form glass-card">
            <h2><?= t('request.title') ?></h2>
            <p class="b2b-subtitle"><?= t('request.subtitle') ?></p>

            <form id="b2bForm" novalidate>
                <?= csrf_field() ?>
                <div class="form-field">
                    <div class="field-icon-wrap">
                        <i class="fas fa-building"></i>
                    </div>
                    <div class="field-input-wrap">
                        <input type="text" name="company" placeholder="<?= t('request.company') ?> *" required>
                        <span class="field-error-msg"></span>
                    </div>
                </div>
                <div class="form-field">
                    <div class="field-icon-wrap">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="field-input-wrap">
                        <input type="text" name="contact" placeholder="<?= t('request.contact') ?> *" required>
                        <span class="field-error-msg"></span>
                    </div>
                </div>
                <div class="form-field">
                    <div class="field-icon-wrap">
                        <i class="fas fa-phone"></i>
                    </div>
                    <div class="field-input-wrap">
                        <input type="tel" name="phone" placeholder="<?= t('request.phone') ?> *" required>
                        <span class="field-error-msg"></span>
                    </div>
                </div>
                <div class="form-field">
                    <div class="field-icon-wrap">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="field-input-wrap">
                        <input type="email" name="email" placeholder="<?= t('request.email') ?> *" required>
                        <span class="field-error-msg"></span>
                    </div>
                </div>
                <div class="form-field">
                    <div class="field-icon-wrap">
                        <i class="fas fa-briefcase"></i>
                    </div>
                    <div class="field-input-wrap">
                        <select name="activity">
                            <option value=""><?= t('request.industry_placeholder') ?></option>
                            <option value="tehsil"><?= t('request.industry_tehsil') ?></option>
                            <option value="korporativ"><?= t('request.industry_korporativ') ?></option>
                            <option value="perakende"><?= t('request.industry_perakende') ?></option>
                            <option value="dovlet"><?= t('request.industry_dovlet') ?></option>
                            <option value="diger"><?= t('request.industry_diger') ?></option>
                        </select>
                        <span class="field-error-msg"></span>
                    </div>
                </div>
                <div class="form-field">
                    <div class="field-icon-wrap">
                        <i class="fas fa-box"></i>
                    </div>
                    <div class="field-input-wrap">
                        <select name="volume">
                            <option value=""><?= t('request.volume_placeholder') ?></option>
                            <option value="500-1000">500-1000 AZN</option>
                            <option value="1000-3000">1000-3000 AZN</option>
                            <option value="3000-5000">3000-5000 AZN</option>
                            <option value="5000+">5000+ AZN</option>
                        </select>
                        <span class="field-error-msg"></span>
                    </div>
                </div>
                <div class="form-field">
                    <div class="field-icon-wrap">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <div class="field-input-wrap">
                        <input type="text" name="budget" placeholder="<?= t('request.budget') ?>">
                        <span class="field-error-msg"></span>
                    </div>
                </div>
                <div class="form-field">
                    <div class="field-icon-wrap">
                        <i class="fas fa-list"></i>
                    </div>
                    <div class="field-input-wrap">
                        <textarea name="products" rows="4" placeholder="<?= t('request.products') ?> *" data-gramm="false" data-gramm_editor="false"></textarea>
                        <span class="field-error-msg"></span>
                    </div>
                </div>
                <div class="form-field">
                    <div class="field-icon-wrap">
                        <i class="fas fa-comment"></i>
                    </div>
                    <div class="field-input-wrap">
                        <textarea name="note" rows="3" placeholder="<?= t('request.note') ?>" data-gramm="false" data-gramm_editor="false"></textarea>
                        <span class="field-error-msg"></span>
                    </div>
                </div>
                <button type="submit" class="btn-submit">
                    <?= t('request.submit') ?> <i class="fas fa-paper-plane"></i>
                </button>
            </form>
        </div>
    </div>
</section>

<div id="b2bSuccess" class="b2b-success-overlay hidden">
    <div class="b2b-success glass-card">
        <i class="fas fa-check-circle"></i>
        <h3><?= t('request.success') ?></h3>
    </div>
</div>
