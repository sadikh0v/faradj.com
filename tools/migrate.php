<?php
require_once __DIR__ . '/../src/load_env.php';
require_once __DIR__ . '/../db.php';

$queries = [

"CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(200) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin') DEFAULT 'admin',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)",

"CREATE TABLE IF NOT EXISTS events (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    title_ru VARCHAR(255),
    title_en VARCHAR(255),
    slug VARCHAR(255) UNIQUE,
    category VARCHAR(100),
    short_text TEXT,
    short_text_ru TEXT,
    short_text_en TEXT,
    full_text LONGTEXT,
    full_text_ru LONGTEXT,
    full_text_en LONGTEXT,
    image VARCHAR(500),
    event_date DATE,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)",

"CREATE TABLE IF NOT EXISTS contact_messages (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    email VARCHAR(200) NOT NULL,
    phone VARCHAR(50) DEFAULT NULL,
    subject VARCHAR(255) DEFAULT NULL,
    message TEXT NOT NULL,
    is_read TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)",

"CREATE TABLE IF NOT EXISTS b2b_requests (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    company VARCHAR(255),
    contact VARCHAR(255),
    phone VARCHAR(100),
    email VARCHAR(200),
    activity VARCHAR(100),
    volume VARCHAR(100),
    budget VARCHAR(100),
    products TEXT,
    note TEXT,
    is_read TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)",

"CREATE TABLE IF NOT EXISTS callbacks (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200),
    phone VARCHAR(100),
    time_pref VARCHAR(100),
    is_read TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)",

"CREATE TABLE IF NOT EXISTS visitors (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    ip_address VARCHAR(45),
    page VARCHAR(500),
    referrer VARCHAR(500),
    user_agent VARCHAR(500),
    lang VARCHAR(10),
    visited_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)",

"CREATE TABLE IF NOT EXISTS site_actions (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    action_type VARCHAR(50) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_date (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

"CREATE TABLE IF NOT EXISTS brands (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    logo VARCHAR(500),
    website VARCHAR(500),
    badge VARCHAR(50),
    sort_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)",

"CREATE TABLE IF NOT EXISTS clients (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    logo VARCHAR(500),
    badge VARCHAR(50),
    sort_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)",

"CREATE TABLE IF NOT EXISTS settings (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    key_name VARCHAR(100) NOT NULL UNIQUE,
    value TEXT,
    label VARCHAR(200),
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)",

"CREATE TABLE IF NOT EXISTS faqs (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    question_az VARCHAR(500),
    question_ru VARCHAR(500),
    question_en VARCHAR(500),
    answer_az TEXT,
    answer_ru TEXT,
    answer_en TEXT,
    sort_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)",

"CREATE TABLE IF NOT EXISTS testimonials (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200),
    company VARCHAR(200),
    text_az TEXT,
    text_ru TEXT,
    text_en TEXT,
    rating TINYINT DEFAULT 5,
    is_verified TINYINT(1) DEFAULT 1,
    is_active TINYINT(1) DEFAULT 1,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)",

"CREATE TABLE IF NOT EXISTS suppliers (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    country_az VARCHAR(200) NOT NULL,
    country_ru VARCHAR(200),
    country_en VARCHAR(200),
    brands VARCHAR(500),
    latitude DECIMAL(10,6) NOT NULL,
    longitude DECIMAL(10,6) NOT NULL,
    type ENUM('distributor','partner') DEFAULT 'partner',
    flag VARCHAR(10),
    color VARCHAR(20) DEFAULT '#6c63ff',
    is_active TINYINT(1) DEFAULT 1,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)",

// Добавить колонки если не существуют (MySQL 8.0.27+)
"ALTER TABLE suppliers ADD COLUMN IF NOT EXISTS iso_code VARCHAR(5) DEFAULT NULL",
"ALTER TABLE contact_messages ADD COLUMN IF NOT EXISTS phone VARCHAR(50) DEFAULT NULL",
"ALTER TABLE contact_messages ADD COLUMN IF NOT EXISTS subject VARCHAR(255) DEFAULT NULL",
"ALTER TABLE contact_messages ADD COLUMN IF NOT EXISTS is_read TINYINT(1) DEFAULT 0",
"ALTER TABLE b2b_requests ADD COLUMN IF NOT EXISTS is_read TINYINT(1) DEFAULT 0",
"ALTER TABLE callbacks ADD COLUMN IF NOT EXISTS is_read TINYINT(1) DEFAULT 0",

// Начальные настройки
"INSERT IGNORE INTO settings (key_name, value, label) VALUES
    ('seo_home_title', 'Faradj MMC — Biznes və Yaradıcılıq üçün İlham', 'Ana səhifə title'),
    ('seo_home_desc', 'DOMS rəsmi distribyutoru. Dəftərxana, ofis ləvazimatları, korporativ təchizat.', 'Ana səhifə description'),
    ('seo_events_title', 'Xəbərlər və Tədbirlər — Faradj MMC', 'Xəbərlər title'),
    ('seo_events_desc', 'Faradj MMC-nin son xəbərləri, tədbirləri və yeniliklərə baxın.', 'Xəbərlər description'),
    ('seo_partners_title', 'Tərəfdaşlar — Faradj MMC', 'Tərəfdaşlar title'),
    ('seo_partners_desc', 'Faradj MMC-nin brendləri və korporativ müştəriləri.', 'Tərəfdaşlar description'),
    ('seo_contacts_title', 'Əlaqə — Faradj MMC', 'Əlaqə title'),
    ('seo_contacts_desc', 'Faradj MMC ilə əlaqə saxlayın. Bakı, İnşaatçılar pr. 106', 'Əlaqə description'),
    ('seo_b2b_title', 'B2B Müraciət — Faradj MMC', 'B2B title'),
    ('seo_b2b_desc', 'Korporativ əməkdaşlıq üçün müraciət forması. Xüsusi qiymətlər və şərtlər.', 'B2B description'),
    ('phone_main',    '+994 55 859 12 11',                    'Əsas telefon'),
    ('phone_second',  '+994 10 521 93 53',                    'İkinci telefon'),
    ('whatsapp',      '994506167212',                         'WhatsApp nömrəsi'),
    ('email_info',    'info@faradj.com',                      'Əlaqə email'),
    ('email_sales',   'sales@faradj.org',                     'Satış email'),
    ('address_main',  'Bakı, İnşaatçılar pr. 106',            'Əsas ünvan'),
    ('address_store', 'Bakı, Murtuza Muxtarov 179',           'Mağaza ünvanı'),
    ('instagram',     'https://www.instagram.com/qelemstationery', 'Instagram'),
    ('tiktok',        'https://www.tiktok.com/@qelemstationery',   'TikTok'),
    ('linkedin',      'https://www.linkedin.com/in/faradjmmc',     'LinkedIn')
",
];

$success = 0;
$errors = [];

foreach ($queries as $sql) {
    try {
        $pdo->exec($sql);
        $success++;
    } catch (PDOException $e) {
        $errors[] = $e->getMessage();
    }
}

// FAQ данные из lang файлов
$faqs = [
    ['az' => 'Korporativ təchizat müqaviləsi bağlamaq üçün hansı şərtlər tələb olunur?', 'ru' => 'Какие условия требуются для заключения корпоративного договора поставки?', 'en' => 'What are the requirements for concluding a corporate supply agreement?', 'az_a' => 'Korporativ əməkdaşlıq üçün şirkətin VÖEN-i və səlahiyyətli nümayəndənin əlaqə məlumatları kifayətdir. Aylıq dövriyyədən asılı olaraq fərdi qiymət cədvəli, təxirəsalınmaz çatdırılma prioriteti və 30 günədək ödəniş müddəti təklif edilir.', 'ru_a' => 'Для корпоративного сотрудничества достаточно ИНН компании и контактных данных уполномоченного представителя. В зависимости от ежемесячного оборота предлагается индивидуальный прайс-лист, приоритетная доставка и отсрочка платежа до 30 дней.', 'en_a' => 'For corporate cooperation, the company\'s TIN and contact details of an authorized representative are sufficient. Depending on monthly turnover, an individual price list, priority delivery and payment terms of up to 30 days are offered.', 'sort' => 1],
    ['az' => 'DOMS məhsullarının orijinallığına zəmanət verirsinizmi?', 'ru' => 'Гарантируете ли вы оригинальность продукции DOMS?', 'en' => 'Do you guarantee the authenticity of DOMS products?', 'az_a' => 'Bəli. Faradj MMC DOMS brendinin Azərbaycanda yeganə rəsmi distribyutorudur. Bütün məhsullar istehsalçının keyfiyyət sertifikatları ilə təchiz edilmiş orijinal mallardır.', 'ru_a' => 'Да. Faradj MMC является единственным официальным дистрибьютором бренда DOMS в Азербайджане. Все продукты являются оригинальными товарами, снабжёнными сертификатами качества производителя.', 'en_a' => 'Yes. Faradj MMC is the sole official distributor of the DOMS brand in Azerbaijan. All products are original goods supplied with manufacturer quality certificates.', 'sort' => 2],
    ['az' => 'Tender prosedurlarında iştirak edirsinizmi?', 'ru' => 'Участвуете ли вы в тендерных процедурах?', 'en' => 'Do you participate in tender procedures?', 'az_a' => 'Bəli. Dövlət satınalmaları, korporativ tenderlər və beynəlxalq layihələr çərçivəsində rəsmi tender sənədləri, qiymət təklifləri və zəmanət məktubları hazırlayırıq.', 'ru_a' => 'Да. В рамках государственных закупок, корпоративных тендеров и международных проектов мы подготавливаем официальные тендерные документы, коммерческие предложения и гарантийные письма.', 'en_a' => 'Yes. Within the framework of public procurement, corporate tenders and international projects, we prepare official tender documents, price offers and guarantee letters.', 'sort' => 3],
    ['az' => 'Çatdırılma şərtləri və sığorta necə tənzimlənir?', 'ru' => 'Каковы условия доставки и как регулируется страхование?', 'en' => 'What are the delivery terms and how is insurance regulated?', 'az_a' => 'Bakı daxili sifarişlər 1-2 iş günü, regionlara 3-5 iş günü ərzində çatdırılır. 2.000 AZN-i keçən korporativ sifarişlər üçün çatdırılma pulsuzdur.', 'ru_a' => 'Заказы по Баку доставляются в течение 1-2 рабочих дней, в регионы — 3-5 рабочих дней. Для корпоративных заказов свыше 2000 AZN доставка бесплатна.', 'en_a' => 'Orders within Baku are delivered within 1-2 business days, to regions within 3-5 business days. Delivery is free for corporate orders exceeding 2,000 AZN.', 'sort' => 4],
    ['az' => 'Geri qaytarma və dəyişdirmə proseduru necə həyata keçirilir?', 'ru' => 'Как осуществляется процедура возврата и обмена?', 'en' => 'How is the return and exchange procedure carried out?', 'az_a' => 'Zavod qüsuru aşkar edildikdə məhsul çatdırılma tarixindən etibarən 7 təqvim günü ərzində dəyişdirilir və ya tam məbləğdə geri qaytarılır.', 'ru_a' => 'При обнаружении заводского дефекта товар заменяется или полностью возмещается в течение 7 календарных дней с даты доставки.', 'en_a' => 'If a manufacturing defect is detected, the product is replaced or fully refunded within 7 calendar days from the delivery date.', 'sort' => 5],
    ['az' => 'Ödəniş şərtləri hansılardır?', 'ru' => 'Каковы условия оплаты?', 'en' => 'What are the payment terms?', 'az_a' => 'Pərakəndə müştərilər üçün nağd, bank kartı və bank köçürməsi qəbul edilir. Korporativ müştərilər üçün 15, 30 və ya 45 günlük ödəniş müddəti razılaşdırılır.', 'ru_a' => 'Для розничных покупателей принимается наличный расчёт, банковская карта и банковский перевод. Для корпоративных клиентов согласовывается отсрочка платежа 15, 30 или 45 дней.', 'en_a' => 'For retail customers, cash, bank card and bank transfer are accepted. For corporate clients, payment terms of 15, 30 or 45 days are negotiated.', 'sort' => 6],
    ['az' => 'Xüsusi çap, brendinq və fərdi dizayn sifarişləri mümkündürmü?', 'ru' => 'Возможны ли заказы на специальную печать, брендинг и индивидуальный дизайн?', 'en' => 'Are custom printing, branding and individual design orders possible?', 'az_a' => 'Bəli. Korporativ hədiyyəlik dəftər, qələm dəsti, logolu kançelyariya məmulatları və xüsusi qablaşdırma xidmətlərini təklif edirik.', 'ru_a' => 'Да. Мы предлагаем корпоративные подарочные ежедневники, наборы ручек, брендированные канцелярские товары и услуги специальной упаковки.', 'en_a' => 'Yes. We offer corporate gift notebooks, pen sets, logo-branded stationery products and special packaging services.', 'sort' => 7],
];

$faqCount = $pdo->query("SELECT COUNT(*) FROM faqs")->fetchColumn();
if ($faqCount == 0) {
    $stmt = $pdo->prepare("INSERT INTO faqs (question_az, question_ru, question_en, answer_az, answer_ru, answer_en, sort_order, is_active) VALUES (?,?,?,?,?,?,?,1)");
    foreach ($faqs as $f) {
        $stmt->execute([$f['az'], $f['ru'], $f['en'], $f['az_a'], $f['ru_a'], $f['en_a'], $f['sort']]);
    }
}

// Testimonials
$testimonials = [
    ['name' => 'Anar Məmmədov', 'company' => 'Korporativ müştəri', 'az' => 'Faradj MMC ilə əməkdaşlığımız çox uğurlu oldu. Keyfiyyətli məhsullar, sürətli çatdırılma və peşəkar yanaşma.', 'ru' => 'Наше сотрудничество с Faradj MMC оказалось очень успешным. Качественная продукция, быстрая доставка и профессиональный подход.', 'en' => 'Our cooperation with Faradj MMC was very successful. Quality products, fast delivery and professional approach.', 'rating' => 5, 'sort' => 1],
    ['name' => 'Leyla Hüseynova', 'company' => 'Təhsil müəssisəsi', 'az' => 'Korporativ sifarişlərimizi həmişə vaxtında və eksiksiz yerinə yetirirlər. Çox məmnunam.', 'ru' => 'Наши корпоративные заказы всегда выполняются вовремя и в полном объёме. Очень доволен.', 'en' => 'Our corporate orders are always fulfilled on time and in full. Very satisfied.', 'rating' => 5, 'sort' => 2],
    ['name' => 'Türkan Əlizadə', 'company' => 'Pərakəndə müştəri', 'az' => 'Məktəb mövsümündə böyük kömək oldu. Sürətli xidmət və geniş çeşid.', 'ru' => 'Большая помощь в школьный сезон. Быстрое обслуживание и широкий ассортимент.', 'en' => 'Great help during the school season. Fast service and wide range of products.', 'rating' => 5, 'sort' => 3],
    ['name' => 'Orxan Nəsirov', 'company' => 'İT şirkəti', 'az' => 'Ofis ləvazimatlarını artıq 2 ildir Faradj-dan alırıq. Heç vaxt məyus olmamışam.', 'ru' => 'Уже 2 года покупаем офисные принадлежности у Faradj. Никогда не был разочарован.', 'en' => 'We have been buying office supplies from Faradj for 2 years. Never been disappointed.', 'rating' => 4, 'sort' => 4],
];

$testCount = $pdo->query("SELECT COUNT(*) FROM testimonials")->fetchColumn();
if ($testCount == 0) {
    $stmt = $pdo->prepare("INSERT INTO testimonials (name, company, text_az, text_ru, text_en, rating, is_verified, is_active, sort_order) VALUES (?,?,?,?,?,?,1,1,?)");
    foreach ($testimonials as $t) {
        $stmt->execute([$t['name'], $t['company'], $t['az'], $t['ru'], $t['en'], $t['rating'], $t['sort']]);
    }
}

// Suppliers — обновить iso_code для существующих
try {
    $pdo->exec("UPDATE suppliers SET iso_code='in' WHERE country_az='Hindistan'");
    $pdo->exec("UPDATE suppliers SET iso_code='es' WHERE country_az='İspaniya'");
    $pdo->exec("UPDATE suppliers SET iso_code='de' WHERE country_az='Almaniya'");
    $pdo->exec("UPDATE suppliers SET iso_code='jp' WHERE country_az='Yaponiya'");
    $pdo->exec("UPDATE suppliers SET iso_code='cn' WHERE country_az='Çin'");
    $pdo->exec("UPDATE suppliers SET iso_code='tr' WHERE country_az='Türkiyə'");
    $pdo->exec("UPDATE suppliers SET iso_code='ae' WHERE country_az='BƏƏ'");
    $pdo->exec("UPDATE suppliers SET iso_code='ru' WHERE country_az='Rusiya'");
    $pdo->exec("UPDATE suppliers SET iso_code='no' WHERE country_az='Norveç'");
} catch (PDOException $e) {}

// Suppliers (карта поставщиков)
$supplierCount = $pdo->query("SELECT COUNT(*) FROM suppliers")->fetchColumn();
if ($supplierCount == 0) {
    $pdo->exec("INSERT INTO suppliers (country_az, country_ru, country_en, brands, latitude, longitude, type, flag) VALUES
        ('Hindistan', 'Индия', 'India', 'DOMS, Cello, Dolphin', 20.5937, 78.9629, 'distributor', '🇮🇳'),
        ('İspaniya', 'Испания', 'Spain', 'Milan', 40.4168, -3.7038, 'partner', '🇪🇸'),
        ('Almaniya', 'Германия', 'Germany', 'Faber-Castell', 51.1657, 10.4515, 'partner', '🇩🇪'),
        ('Yaponiya', 'Япония', 'Japan', 'Citizen, Uni-ball', 36.2048, 138.2529, 'partner', '🇯🇵'),
        ('Çin', 'Китай', 'China', 'Kangaro, Trix', 35.8617, 104.1954, 'partner', '🇨🇳'),
        ('Türkiyə', 'Турция', 'Turkey', 'Brons, Scriks', 38.9637, 35.2433, 'partner', '🇹🇷'),
        ('BƏƏ', 'ОАЭ', 'UAE', 'Qamma', 23.4241, 53.8478, 'partner', '🇦🇪'),
        ('Norveç', 'Норвегия', 'Norway', 'Centropen', 59.9139, 10.7522, 'partner', '🇳🇴'),
        ('Rusiya', 'Россия', 'Russia', 'Qamma, Nevskaya palitra, Multi-pulti', 55.7558, 37.6173, 'partner', '🇷🇺')");
}

// SEO настройки (INSERT IGNORE — уже есть в основном INSERT выше)
$seoSettings = [
    ['seo_home_title', 'Faradj MMC — Biznes və Yaradıcılıq üçün İlham', 'Ana səhifə title'],
    ['seo_home_desc', 'DOMS rəsmi distribyutoru. Dəftərxana, ofis ləvazimatları, korporativ təchizat.', 'Ana səhifə description'],
    ['seo_events_title', 'Xəbərlər və Tədbirlər — Faradj MMC', 'Xəbərlər title'],
    ['seo_events_desc', 'Faradj MMC-nin son xəbərləri, tədbirləri və yeniliklərə baxın.', 'Xəbərlər description'],
    ['seo_partners_title', 'Tərəfdaşlar — Faradj MMC', 'Tərəfdaşlar title'],
    ['seo_partners_desc', 'Faradj MMC-nin brendləri və korporativ müştəriləri.', 'Tərəfdaşlar description'],
    ['seo_contacts_title', 'Əlaqə — Faradj MMC', 'Əlaqə title'],
    ['seo_contacts_desc', 'Faradj MMC ilə əlaqə saxlayın. Bakı, İnşaatçılar pr. 106', 'Əlaqə description'],
    ['seo_b2b_title', 'B2B Müraciət — Faradj MMC', 'B2B title'],
    ['seo_b2b_desc', 'Korporativ əməkdaşlıq üçün müraciət forması. Xüsusi qiymətlər və şərtlər.', 'B2B description'],
];

$stmt = $pdo->prepare("INSERT IGNORE INTO settings (key_name, value, label) VALUES (?,?,?)");
foreach ($seoSettings as $s) {
    $stmt->execute($s);
}

echo "<h3>✅ Uğurlu: $success</h3>";
if ($errors) {
    echo "<h3>⚠️ Xətalar:</h3><ul>";
    foreach ($errors as $e) echo "<li>" . htmlspecialchars($e) . "</li>";
    echo "</ul>";
}
echo "<p><strong>Migrate tamamlandı. Bu faylı silin!</strong></p>";
