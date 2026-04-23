<?php
require_once __DIR__ . '/../load_env.php';
require_once __DIR__ . '/../helpers/events_schema.php';

if (!function_exists('db')) {
    function db(): PDO
    {
        global $pdo;
        if (!isset($pdo)) {
            require_once base_path('db.php');
        }
        return $pdo;
    }
}

if (!function_exists('flash')) {
    function flash(string $key, ?string $value = null): ?string
    {
        if ($value !== null) {
            $_SESSION['_flash'][$key] = $value;
            return null;
        }
        $v = $_SESSION['_flash'][$key] ?? null;
        unset($_SESSION['_flash'][$key]);
        return $v;
    }
}

class AdminController
{
    private static function guard(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        if (empty($_SESSION['admin'])) {
            header('Location: /admin/login');
            exit;
        }
    }

    public static function login(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        $error = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once base_path('src/helpers/RateLimit.php');
            if (!RateLimit::check('admin_login', 5, 900)) {
                $error = 'Çox sayda uğursuz cəhd. 15 dəqiqə gözləyin.';
                include base_path('src/views/admin/login.php');
                return;
            }
            $email = trim($_POST['email'] ?? '');
            $pass = $_POST['password'] ?? '';
            $hash = env('ADMIN_PASSWORD_HASH', '');
            if ($email === env('ADMIN_EMAIL', '') && $hash && password_verify($pass, $hash)) {
                session_regenerate_id(true);
                $_SESSION['admin'] = true;
                $_SESSION['admin_email'] = $email;
                RateLimit::reset('admin_login');
                header('Location: /admin');
                exit;
            }
            $error = 'Email və ya şifrə yanlışdır';
        }
        include base_path('src/views/admin/login.php');
    }

    public static function logout(): void
    {
        session_destroy();
        header('Location: /admin/login');
        exit;
    }

    public static function getUnreadCounts(): array
    {
        try {
            $contacts = db()->query("SELECT COUNT(*) FROM contact_messages WHERE is_read=0")->fetchColumn();
            $b2b = db()->query("SELECT COUNT(*) FROM b2b_requests WHERE is_read=0")->fetchColumn();
            $callbacks = db()->query("SELECT COUNT(*) FROM callbacks WHERE is_read=0")->fetchColumn();
            return [
                'contacts'  => (int) $contacts,
                'b2b'       => (int) $b2b,
                'callbacks' => (int) $callbacks,
                'total'     => (int) $contacts + (int) $b2b + (int) $callbacks,
            ];
        } catch (\Throwable $e) {
            return ['contacts' => 0, 'b2b' => 0, 'callbacks' => 0, 'total' => 0];
        }
    }

    public static function dashboard(): void
    {
        self::guard();
        $pdo = db();

        $stats = [
            'events_total' => 0,
            'events_published' => 0,
            'contacts_total' => 0,
            'contacts_new' => 0,
            'b2b_total' => 0,
            'b2b_new' => 0,
            'callbacks_new' => 0,
        ];

        try {
            $stats['events_total'] = (int) $pdo->query("SELECT COUNT(*) FROM events")->fetchColumn();
            $pub = events_published_column_name();
            $stats['events_published'] = (int) $pdo->query("SELECT COUNT(*) FROM events WHERE `{$pub}`=1")->fetchColumn();
        } catch (PDOException $e) {}

        try {
            $stats['b2b_total'] = (int) $pdo->query("SELECT COUNT(*) FROM b2b_requests")->fetchColumn();
            $stats['b2b_new'] = (int) $pdo->query("SELECT COUNT(*) FROM b2b_requests WHERE created_at >= NOW() - INTERVAL 7 DAY")->fetchColumn();
        } catch (PDOException $e) {}

        try {
            $stats['callbacks_new'] = (int) $pdo->query("SELECT COUNT(*) FROM callbacks WHERE created_at >= NOW() - INTERVAL 7 DAY")->fetchColumn();
        } catch (PDOException $e) {}

        try {
            $stats['contacts_total'] = (int) $pdo->query("SELECT COUNT(*) FROM contact_messages")->fetchColumn();
            $stats['contacts_new'] = (int) $pdo->query("SELECT COUNT(*) FROM contact_messages WHERE created_at >= NOW() - INTERVAL 7 DAY")->fetchColumn();
        } catch (PDOException $e) {}

        $recentEvents = [];
        $recentContacts = [];
        $chartData = [];
        $recentB2b = [];
        $recentCallbacks = [];
        try {
            $recentEvents = $pdo->query("SELECT * FROM events ORDER BY created_at DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
            $recentEvents = array_map('normalize_event_row', $recentEvents);
        } catch (PDOException $e) {}
        try {
            $recentContacts = $pdo->query("SELECT * FROM contact_messages ORDER BY created_at DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {}
        try {
            $chartData = $pdo->query("
                SELECT DATE(visited_at) as day, COUNT(*) as cnt
                FROM visitors
                WHERE visited_at >= NOW() - INTERVAL 7 DAY
                GROUP BY DATE(visited_at)
                ORDER BY day ASC
            ")->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {}
        try {
            $recentB2b = $pdo->query("SELECT * FROM b2b_requests ORDER BY created_at DESC LIMIT 3")->fetchAll(PDO::FETCH_ASSOC);
            $recentCallbacks = $pdo->query("SELECT * FROM callbacks ORDER BY created_at DESC LIMIT 3")->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {}

        include base_path('src/views/admin/dashboard.php');
    }

    public static function contacts(): void
    {
        self::guard();
        try {
            db()->exec("UPDATE contact_messages SET is_read=1 WHERE is_read=0");
        } catch (PDOException $e) {}
        $items = [];
        try {
            $items = db()->query("SELECT * FROM contact_messages ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {}
        include base_path('src/views/admin/contacts.php');
    }

    public static function b2b(): void
    {
        self::guard();
        try {
            db()->exec("UPDATE b2b_requests SET is_read=1 WHERE is_read=0");
        } catch (PDOException $e) {}
        $items = [];
        try {
            $items = db()->query("SELECT * FROM b2b_requests ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {}
        include base_path('src/views/admin/b2b.php');
    }

    public static function callbacks(): void
    {
        self::guard();
        try {
            db()->exec("UPDATE callbacks SET is_read=1 WHERE is_read=0");
        } catch (PDOException $e) {}
        $items = [];
        try {
            $items = db()->query("SELECT * FROM callbacks ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {}
        include base_path('src/views/admin/callbacks.php');
    }

    public static function users(): void
    {
        self::guard();
        $userData = null;

        if (!empty($_GET['email'])) {
            $email = trim($_GET['email']);
            $userData = [];
            try {
                $tables = [
                    'Əlaqə mesajları' => 'contact_messages',
                    'B2B müraciətlər' => 'b2b_requests',
                    'Zəng sorğuları'  => 'callbacks',
                ];
                foreach ($tables as $label => $table) {
                    if ($table === 'callbacks') {
                        $userData[$label] = [];
                        continue;
                    }
                    $stmt = db()->prepare("SELECT * FROM $table WHERE email = ?");
                    $stmt->execute([$email]);
                    $userData[$label] = $stmt->fetchAll(PDO::FETCH_ASSOC);
                }
            } catch (PDOException $e) {}
        }

        include base_path('src/views/admin/users.php');
    }

    public static function exportUser(): void
    {
        self::guard();
        $email = trim($_GET['email'] ?? '');
        if (!$email) {
            header('Location: /admin/users');
            exit;
        }

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="user_data_' . date('Y-m-d') . '.csv"');

        $out = fopen('php://output', 'w');
        fprintf($out, chr(0xEF) . chr(0xBB) . chr(0xBF)); // UTF-8 BOM

        $tables = ['contact_messages', 'b2b_requests'];
        foreach ($tables as $table) {
            try {
                $stmt = db()->prepare("SELECT * FROM $table WHERE email = ?");
                $stmt->execute([$email]);
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if (!empty($rows)) {
                    fputcsv($out, ['=== ' . $table . ' ===']);
                    fputcsv($out, array_keys($rows[0]));
                    foreach ($rows as $row) {
                        fputcsv($out, $row);
                    }
                    fputcsv($out, []);
                }
            } catch (PDOException $e) {}
        }
        fclose($out);
        exit;
    }

    public static function deleteUser(): void
    {
        self::guard();
        $email = trim($_GET['email'] ?? '');
        if (!$email) {
            header('Location: /admin/users');
            exit;
        }

        try {
            db()->prepare("DELETE FROM contact_messages WHERE email = ?")->execute([$email]);
            db()->prepare("DELETE FROM b2b_requests WHERE email = ?")->execute([$email]);
            flash('success', $email . ' — bütün məlumatlar silindi.');
        } catch (PDOException $e) {
            flash('error', 'Xəta baş verdi: ' . $e->getMessage());
        }

        header('Location: /admin/users');
        exit;
    }

    public static function faqs(): void
    {
        self::guard();
        $items = [];
        try {
            $items = db()->query("SELECT * FROM faqs ORDER BY sort_order ASC")->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {}
        include base_path('src/views/admin/faqs.php');
    }

    public static function saveFaq(): void
    {
        self::guard();
        $id = $_POST['id'] ?? null;
        $data = [
            trim($_POST['question_az'] ?? ''),
            trim($_POST['question_ru'] ?? ''),
            trim($_POST['question_en'] ?? ''),
            trim($_POST['answer_az'] ?? ''),
            trim($_POST['answer_ru'] ?? ''),
            trim($_POST['answer_en'] ?? ''),
            (int) ($_POST['sort_order'] ?? 0),
            isset($_POST['is_active']) ? 1 : 0,
        ];
        try {
            if ($id) {
                $data[] = $id;
                db()->prepare("UPDATE faqs SET question_az=?,question_ru=?,question_en=?,answer_az=?,answer_ru=?,answer_en=?,sort_order=?,is_active=? WHERE id=?")->execute($data);
                flash('success', 'FAQ yeniləndi.');
            } else {
                db()->prepare("INSERT INTO faqs (question_az,question_ru,question_en,answer_az,answer_ru,answer_en,sort_order,is_active) VALUES (?,?,?,?,?,?,?,?)")->execute($data);
                flash('success', 'FAQ əlavə edildi.');
            }
        } catch (PDOException $e) {
            flash('error', 'Xəta: ' . $e->getMessage());
        }
        header('Location: /admin/faqs');
        exit;
    }

    public static function deleteFaq(): void
    {
        self::guard();
        $id = (int) ($_POST['id'] ?? 0);
        if ($id) {
            try {
                db()->prepare("DELETE FROM faqs WHERE id=?")->execute([$id]);
                flash('success', 'FAQ silindi.');
            } catch (PDOException $e) {}
        }
        header('Location: /admin/faqs');
        exit;
    }

    public static function testimonials(): void
    {
        self::guard();
        $items = [];
        try {
            $items = db()->query("SELECT * FROM testimonials ORDER BY sort_order ASC")->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {}
        include base_path('src/views/admin/testimonials.php');
    }

    public static function saveTestimonial(): void
    {
        self::guard();
        $id = $_POST['id'] ?? null;
        $data = [
            trim($_POST['name'] ?? ''),
            trim($_POST['company'] ?? ''),
            trim($_POST['text_az'] ?? ''),
            trim($_POST['text_ru'] ?? ''),
            trim($_POST['text_en'] ?? ''),
            min(5, max(1, (int) ($_POST['rating'] ?? 5))),
            isset($_POST['is_verified']) ? 1 : 0,
            isset($_POST['is_active']) ? 1 : 0,
            (int) ($_POST['sort_order'] ?? 0),
        ];
        try {
            if ($id) {
                $data[] = $id;
                db()->prepare("UPDATE testimonials SET name=?,company=?,text_az=?,text_ru=?,text_en=?,rating=?,is_verified=?,is_active=?,sort_order=? WHERE id=?")->execute($data);
                flash('success', 'Rəy yeniləndi.');
            } else {
                db()->prepare("INSERT INTO testimonials (name,company,text_az,text_ru,text_en,rating,is_verified,is_active,sort_order) VALUES (?,?,?,?,?,?,?,?,?)")->execute($data);
                flash('success', 'Rəy əlavə edildi.');
            }
        } catch (PDOException $e) {
            flash('error', 'Xəta: ' . $e->getMessage());
        }
        header('Location: /admin/testimonials');
        exit;
    }

    public static function deleteTestimonial(): void
    {
        self::guard();
        $id = (int) ($_POST['id'] ?? 0);
        if ($id) {
            try {
                db()->prepare("DELETE FROM testimonials WHERE id=?")->execute([$id]);
                flash('success', 'Rəy silindi.');
            } catch (PDOException $e) {}
        }
        header('Location: /admin/testimonials');
        exit;
    }

    public static function gallery(): void
    {
        self::guard();

        $usedFiles = [];
        try {
            $imgCol = events_image_column_name();
            $events  = db()->query("SELECT `$imgCol` FROM events WHERE `$imgCol` IS NOT NULL AND `$imgCol` != ''")->fetchAll(PDO::FETCH_COLUMN);
            $brands  = db()->query("SELECT logo FROM brands WHERE logo IS NOT NULL AND logo != ''")->fetchAll(PDO::FETCH_COLUMN);
            $clients = db()->query("SELECT logo FROM clients WHERE logo IS NOT NULL AND logo != ''")->fetchAll(PDO::FETCH_COLUMN);
            $usedFiles = array_merge($events, $brands, $clients);
            $usedFiles = array_map('basename', $usedFiles);
        } catch (PDOException $e) {}

        include base_path('src/views/admin/gallery.php');
    }

    public static function deleteFile(): void
    {
        self::guard();
        header('Content-Type: application/json');

        $file   = basename($_POST['file'] ?? '');
        $dirKey = $_POST['dir'] ?? '';

        $allowedDirs = [
            '/assets/img/events/'  => base_path('public/assets/img/events/'),
            '/assets/img/brands/'  => base_path('public/assets/img/brands/'),
            '/assets/img/clients/' => base_path('public/assets/img/clients/'),
        ];

        if (!$file || !isset($allowedDirs[$dirKey])) {
            echo json_encode(['success' => false, 'error' => 'Invalid']);
            exit;
        }

        $fullPath = $allowedDirs[$dirKey] . $file;

        if (!file_exists($fullPath)) {
            echo json_encode(['success' => false, 'error' => 'File not found']);
            exit;
        }

        unlink($fullPath);

        $webPath = $dirKey . $file;
        try {
            $imgCol = events_image_column_name();
            db()->prepare("UPDATE events SET `$imgCol` = NULL WHERE `$imgCol` = ?")->execute([$webPath]);
            db()->prepare("UPDATE brands  SET logo  = NULL WHERE logo  = ?")->execute([$webPath]);
            db()->prepare("UPDATE clients SET logo  = NULL WHERE logo  = ?")->execute([$webPath]);
        } catch (PDOException $e) {}

        echo json_encode(['success' => true]);
        exit;
    }

    public static function events(): void
    {
        self::guard();

        $search = trim($_GET['search'] ?? '');
        $category = $_GET['category'] ?? '';
        $status = $_GET['status'] ?? '';
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $perPage = 10;
        $offset = ($page - 1) * $perPage;

        $where = ['1=1'];
        $params = [];

        if ($search) {
            $ec = events_excerpt_column_names();
            $ex = $ec['excerpt'];
            $where[] = "(title LIKE ? OR `$ex` LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }
        if ($category) {
            $where[] = 'category = ?';
            $params[] = $category;
        }
        $pubCol = events_published_column_name();
        if ($status === 'published') {
            $where[] = "`$pubCol` = 1";
        } elseif ($status === 'draft') {
            $where[] = "`$pubCol` = 0";
        }

        $whereStr = implode(' AND ', $where);

        $totalCount = 0;
        $totalPages = 1;
        try {
            $stmt = $pdo = db()->prepare("SELECT COUNT(*) FROM events WHERE $whereStr");
            $stmt->execute($params);
            $totalCount = (int) $stmt->fetchColumn();
            $totalPages = max(1, (int) ceil($totalCount / $perPage));
        } catch (PDOException $e) {}

        $events = [];
        try {
            $stmt = db()->prepare("
                SELECT * FROM events 
                WHERE $whereStr
                ORDER BY created_at DESC
                LIMIT $perPage OFFSET $offset
            ");
            $stmt->execute($params);
            $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $events = array_map('normalize_event_row', $events);
        } catch (PDOException $e) {}

        include base_path('src/views/admin/events.php');
    }

    public static function createEvent(): void
    {
        self::guard();
        $event = null;
        $old = [];
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = self::validateEventForm();

            if (empty($data['errors'])) {
                $image = self::uploadImage();

                try {
                    $ec = events_excerpt_column_names();
                    $img = events_image_column_name();
                    $pub = events_published_column_name();
                    $e1 = $ec['excerpt'];
                    $e2 = $ec['excerpt_ru'];
                    $e3 = $ec['excerpt_en'];
                    $cols = [
                        'title', 'title_ru', 'title_en',
                        $e1, $e2, $e3,
                        'full_text', 'full_text_ru', 'full_text_en',
                        'category',
                        $img,
                    ];
                    $vals = [
                        $data['title'],
                        $data['title_ru'] ?? '',
                        $data['title_en'] ?? '',
                        $data['excerpt'],
                        $data['excerpt_ru'] ?? '',
                        $data['excerpt_en'] ?? '',
                        $data['full_text'],
                        $data['full_text_ru'] ?? '',
                        $data['full_text_en'] ?? '',
                        $data['category'],
                        $image,
                    ];
                    if (events_has_column('author')) {
                        $cols[] = 'author';
                        $vals[] = $data['author'];
                    }
                    $cols[] = 'event_date';
                    $vals[] = $data['event_date'] ?: date('Y-m-d');
                    $cols[] = $pub;
                    $vals[] = $data['is_published'];
                    $quoted = array_map(static function ($c) {
                        return '`' . str_replace('`', '``', $c) . '`';
                    }, $cols);
                    $sql = 'INSERT INTO events (' . implode(',', $quoted) . ') VALUES (' . implode(',', array_fill(0, count($cols), '?')) . ')';
                    db()->prepare($sql)->execute($vals);

                    flash('success', 'Xəbər uğurla əlavə edildi!');
                    header('Location: /admin/events');
                    exit;
                } catch (PDOException $e) {
                    error_log('[Admin] createEvent: ' . $e->getMessage());
                    $data['errors']['form'] = 'DB Xətası: ' . $e->getMessage();
                }
            }

            $errors = $data['errors'];
            $old = $data;
        }

        include base_path('src/views/admin/event-form.php');
    }

    public static function editEvent(): void
    {
        self::guard();
        $id = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);
        $event = null;
        try {
            $stmt = db()->prepare("SELECT * FROM events WHERE id = ?");
            $stmt->execute([$id]);
            $event = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {}

        if (!$event) {
            flash('error', 'Xəbər tapılmadı');
            header('Location: /admin/events');
            exit;
        }

        $event = normalize_event_row($event);

        $old = [];
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = self::validateEventForm();

            if (empty($data['errors'])) {
                $image = self::uploadImage() ?? ($event['image_url'] ?? $event['image'] ?? '');

                try {
                    $ec = events_excerpt_column_names();
                    $img = events_image_column_name();
                    $pub = events_published_column_name();
                    $e1 = $ec['excerpt'];
                    $e2 = $ec['excerpt_ru'];
                    $e3 = $ec['excerpt_en'];
                    $sets = [
                        'title = ?', 'title_ru = ?', 'title_en = ?',
                        "`$e1` = ?", "`$e2` = ?", "`$e3` = ?",
                        'full_text = ?', 'full_text_ru = ?', 'full_text_en = ?',
                        'category = ?',
                        "`$img` = ?",
                    ];
                    $vals = [
                        $data['title'],
                        $data['title_ru'] ?? '',
                        $data['title_en'] ?? '',
                        $data['excerpt'],
                        $data['excerpt_ru'] ?? '',
                        $data['excerpt_en'] ?? '',
                        $data['full_text'],
                        $data['full_text_ru'] ?? '',
                        $data['full_text_en'] ?? '',
                        $data['category'],
                        $image,
                    ];
                    if (events_has_column('author')) {
                        $sets[] = 'author = ?';
                        $vals[] = $data['author'];
                    }
                    $sets[] = 'event_date = ?';
                    $vals[] = $data['event_date'] ?: date('Y-m-d');
                    $sets[] = "`$pub` = ?";
                    $vals[] = $data['is_published'];
                    $vals[] = $id;
                    $sql = 'UPDATE events SET ' . implode(', ', $sets) . ' WHERE id = ?';
                    db()->prepare($sql)->execute($vals);

                    flash('success', 'Xəbər yeniləndi!');
                    header('Location: /admin/events');
                    exit;
                } catch (PDOException $e) {
                    error_log('[Admin] editEvent: ' . $e->getMessage());
                    $data['errors']['form'] = 'DB Xətası: ' . $e->getMessage();
                }
            }

            $errors = $data['errors'];
            $old = $data;
        }

        include base_path('src/views/admin/event-form.php');
    }

    public static function deleteEvent(): void
    {
        self::guard();
        $id = (int) ($_POST['id'] ?? 0);

        try {
            $imgCol = events_image_column_name();
            $stmt = db()->prepare("SELECT `$imgCol` AS img FROM events WHERE id = ?");
            $stmt->execute([$id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row && !empty($row['img'])) {
                $imgPath = base_path('public' . $row['img']);
                if (file_exists($imgPath)) {
                    unlink($imgPath);
                }
            }

            db()->prepare("DELETE FROM events WHERE id = ?")->execute([$id]);
        } catch (PDOException $e) {}

        flash('success', 'Xəbər silindi!');
        header('Location: /admin/events');
        exit;
    }

    public static function toggleEvent(): void
    {
        self::guard();
        $id = (int) ($_POST['id'] ?? 0);

        try {
            $pub = events_published_column_name();
            db()->prepare("UPDATE events SET `$pub` = NOT `$pub` WHERE id = ?")->execute([$id]);
            $stmt = db()->prepare("SELECT `$pub` FROM events WHERE id = ?");
            $stmt->execute([$id]);
            $published = (bool) $stmt->fetchColumn();

            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'published' => $published]);
        } catch (PDOException $e) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false]);
        }
        exit;
    }

    public static function settings(): void
    {
        self::guard();
        $items = [];
        try {
            $items = db()->query("SELECT * FROM settings ORDER BY id")->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {}
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $settings = $_POST['settings'] ?? [];
            try {
                $stmt = db()->prepare("UPDATE settings SET value=?, updated_at=NOW() WHERE key_name=?");
                foreach ($settings as $key => $value) {
                    $stmt->execute([trim((string) $value), $key]);
                }
                if (function_exists('setting_clear_cache')) {
                    setting_clear_cache();
                }
                flash('success', 'Parametrlər yadda saxlandı!');
            } catch (PDOException $e) {
                flash('error', 'Xəta: ' . $e->getMessage());
            }
            header('Location: /admin/settings');
            exit;
        }
        include base_path('src/views/admin/settings.php');
    }

    public static function brands(): void
    {
        self::guard();
        $items = [];
        try {
            $items = db()->query("SELECT * FROM brands ORDER BY sort_order, id")->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {}
        include base_path('src/views/admin/brands.php');
    }

    public static function saveBrand(): void
    {
        self::guard();
        $id = (int) ($_POST['id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $web = trim($_POST['website'] ?? '');
        $badge = ($_POST['badge'] ?? '') === 'distributor' ? 'distributor' : 'partner';
        $sort = (int) ($_POST['sort_order'] ?? 0);
        $active = isset($_POST['is_active']) ? 1 : 0;

        $logo = null;
        if (!empty($_FILES['logo']['tmp_name']) && $_FILES['logo']['error'] === 0) {
            $ext = strtolower(pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION));
            $filename = 'brand_' . uniqid() . '.' . $ext;
            $dir = base_path('public/assets/img/brands/');
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
            if (move_uploaded_file($_FILES['logo']['tmp_name'], $dir . $filename)) {
                self::resizeImage($dir . $filename);
                $logo = '/assets/img/brands/' . $filename;
            }
        }

        if ($id) {
            $sql = $logo
                ? "UPDATE brands SET name=?,website=?,badge=?,sort_order=?,is_active=?,logo=? WHERE id=?"
                : "UPDATE brands SET name=?,website=?,badge=?,sort_order=?,is_active=? WHERE id=?";
            $params = $logo ? [$name, $web, $badge, $sort, $active, $logo, $id] : [$name, $web, $badge, $sort, $active, $id];
            db()->prepare($sql)->execute($params);
        } else {
            db()->prepare("INSERT INTO brands (name,website,badge,sort_order,is_active,logo) VALUES (?,?,?,?,?,?)")
                ->execute([$name, $web, $badge, $sort, $active, $logo]);
        }
        flash('success', 'Brand yadda saxlandı!');
        header('Location: /admin/brands');
        exit;
    }

    public static function deleteBrand(): void
    {
        self::guard();
        $id = (int) ($_POST['id'] ?? 0);
        db()->prepare("DELETE FROM brands WHERE id=?")->execute([$id]);
        flash('success', 'Brand silindi!');
        header('Location: /admin/brands');
        exit;
    }

    public static function clients(): void
    {
        self::guard();
        $items = [];
        try {
            $items = db()->query("SELECT * FROM clients ORDER BY sort_order, id")->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {}
        include base_path('src/views/admin/clients.php');
    }

    public static function saveClient(): void
    {
        self::guard();
        $id = (int) ($_POST['id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $web = trim($_POST['website'] ?? '');
        $badge = trim($_POST['badge'] ?? '');
        $sort = (int) ($_POST['sort_order'] ?? 0);
        $active = isset($_POST['is_active']) ? 1 : 0;

        $logo = null;
        if (!empty($_FILES['logo']['tmp_name']) && $_FILES['logo']['error'] === 0) {
            $ext = strtolower(pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION));
            $filename = 'client_' . uniqid() . '.' . $ext;
            $dir = base_path('public/assets/img/clients/');
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
            if (move_uploaded_file($_FILES['logo']['tmp_name'], $dir . $filename)) {
                self::resizeImage($dir . $filename);
                $logo = '/assets/img/clients/' . $filename;
            }
        }

        if ($id) {
            $sql = $logo
                ? "UPDATE clients SET name=?,website=?,badge=?,sort_order=?,is_active=?,logo=? WHERE id=?"
                : "UPDATE clients SET name=?,website=?,badge=?,sort_order=?,is_active=? WHERE id=?";
            $params = $logo ? [$name, $web, $badge, $sort, $active, $logo, $id] : [$name, $web, $badge, $sort, $active, $id];
            db()->prepare($sql)->execute($params);
        } else {
            db()->prepare("INSERT INTO clients (name,website,badge,sort_order,is_active,logo) VALUES (?,?,?,?,?,?)")
                ->execute([$name, $web, $badge, $sort, $active, $logo]);
        }
        flash('success', 'Müştəri yadda saxlandı!');
        header('Location: /admin/clients');
        exit;
    }

    public static function deleteClient(): void
    {
        self::guard();
        $id = (int) ($_POST['id'] ?? 0);
        db()->prepare("DELETE FROM clients WHERE id=?")->execute([$id]);
        flash('success', 'Müştəri silindi!');
        header('Location: /admin/clients');
        exit;
    }

    public static function suppliers(): void
    {
        self::guard();
        $suppliers = [];
        try {
            $suppliers = db()->query("SELECT * FROM suppliers ORDER BY sort_order ASC, id ASC")->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {}
        include base_path('src/views/admin/suppliers.php');
    }

    public static function saveSupplier(): void
    {
        self::guard();
        $id = !empty($_POST['id']) ? (int) $_POST['id'] : null;
        $data = [
            trim($_POST['country_az'] ?? ''),
            trim($_POST['country_ru'] ?? ''),
            trim($_POST['country_en'] ?? ''),
            trim($_POST['brands'] ?? ''),
            (float) ($_POST['latitude'] ?? 0),
            (float) ($_POST['longitude'] ?? 0),
            $_POST['type'] ?? 'partner',
            trim($_POST['flag'] ?? ''),
            strtolower(trim($_POST['iso_code'] ?? '')),
            (int) ($_POST['sort_order'] ?? 0),
            isset($_POST['is_active']) ? 1 : 0,
        ];
        try {
            if ($id) {
                $data[] = $id;
                db()->prepare("UPDATE suppliers SET
                    country_az=?, country_ru=?, country_en=?, brands=?,
                    latitude=?, longitude=?, type=?, flag=?, iso_code=?,
                    sort_order=?, is_active=? WHERE id=?")->execute($data);
            } else {
                db()->prepare("INSERT INTO suppliers
                    (country_az, country_ru, country_en, brands,
                     latitude, longitude, type, flag, iso_code, sort_order, is_active)
                    VALUES (?,?,?,?,?,?,?,?,?,?,?)")->execute($data);
            }
            flash('success', 'Təchizatçı yadda saxlandı!');
        } catch (PDOException $e) {
            flash('error', 'Xəta: ' . $e->getMessage());
        }
        header('Location: /admin/suppliers');
        exit;
    }

    public static function deleteSupplier(): void
    {
        self::guard();
        $id = (int) ($_POST['id'] ?? 0);
        db()->prepare("DELETE FROM suppliers WHERE id=?")->execute([$id]);
        flash('success', 'Təchizatçı silindi!');
        header('Location: /admin/suppliers');
        exit;
    }

    public static function stats(): void
    {
        self::guard();
        $data = [
            'by_day' => [],
            'top_pages' => [],
            'by_lang' => [],
            'total' => 0,
            'today' => 0,
        ];
        try {
            $data['by_day'] = db()->query("
                SELECT DATE(visited_at) as day, COUNT(*) as cnt
                FROM visitors
                WHERE visited_at >= NOW() - INTERVAL 30 DAY
                GROUP BY DATE(visited_at)
                ORDER BY day ASC
            ")->fetchAll(PDO::FETCH_ASSOC);

            $data['top_pages'] = db()->query("
                SELECT
                    REGEXP_REPLACE(page, '[?&]nc=[0-9]+', '') as page,
                    COUNT(*) as cnt
                FROM visitors
                GROUP BY REGEXP_REPLACE(page, '[?&]nc=[0-9]+', '')
                ORDER BY cnt DESC
                LIMIT 10
            ")->fetchAll(PDO::FETCH_ASSOC);

            $data['by_lang'] = db()->query("
                SELECT lang, COUNT(*) as cnt
                FROM visitors
                GROUP BY lang
                ORDER BY cnt DESC
            ")->fetchAll(PDO::FETCH_ASSOC);

            $data['total'] = (int) db()->query("SELECT COUNT(*) FROM visitors")->fetchColumn();
            $data['today'] = (int) db()->query("SELECT COUNT(*) FROM visitors WHERE DATE(visited_at) = CURDATE()")->fetchColumn();
        } catch (PDOException $e) {}
        include base_path('src/views/admin/stats.php');
    }

    private static function resizeImage(string $path, int $maxW = 400, int $maxH = 400): void
    {
        $info = @getimagesize($path);
        if (!$info) {
            return;
        }

        [$w, $h, $type] = $info;
        if ($w <= $maxW && $h <= $maxH) {
            return;
        }

        $ratio = min($maxW / $w, $maxH / $h);
        $newW = (int) ($w * $ratio);
        $newH = (int) ($h * $ratio);

        $src = match ($type) {
            IMAGETYPE_PNG => imagecreatefrompng($path),
            IMAGETYPE_JPEG => imagecreatefromjpeg($path),
            IMAGETYPE_GIF => imagecreatefromgif($path),
            default => null,
        };
        if (!$src) {
            return;
        }

        $dst = imagecreatetruecolor($newW, $newH);

        if ($type === IMAGETYPE_PNG) {
            imagealphablending($dst, false);
            imagesavealpha($dst, true);
            $transparent = imagecolorallocatealpha($dst, 255, 255, 255, 127);
            imagefilledrectangle($dst, 0, 0, $newW, $newH, $transparent);
        }

        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newW, $newH, $w, $h);

        match ($type) {
            IMAGETYPE_PNG => imagepng($dst, $path, 8),
            IMAGETYPE_JPEG => imagejpeg($dst, $path, 85),
            IMAGETYPE_GIF => imagegif($dst, $path),
            default => null,
        };

        imagedestroy($src);
        imagedestroy($dst);
    }

    private static function uploadImage(): ?string
    {
        if (!isset($_FILES['image']) ||
            $_FILES['image']['error'] !== UPLOAD_ERR_OK ||
            empty($_FILES['image']['tmp_name'])) {
            return null;
        }

        $file = $_FILES['image'];

        // Проверка по расширению а не по mime-type
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

        if (!in_array($ext, $allowed)) {
            return null;
        }
        if ($file['size'] > 5 * 1024 * 1024) {
            return null;
        }

        $filename = 'event_' . uniqid() . '.' . $ext;
        $dir = base_path('public/assets/img/events/');

        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        if (move_uploaded_file($file['tmp_name'], $dir . $filename)) {
            return '/assets/img/events/' . $filename;
        }
        return null;
    }

    private static function validateEventForm(): array
    {
        $data = [
            'title' => trim($_POST['title'] ?? ''),
            'title_ru' => trim($_POST['title_ru'] ?? ''),
            'title_en' => trim($_POST['title_en'] ?? ''),
            'excerpt' => trim($_POST['excerpt'] ?? ''),
            'excerpt_ru' => trim($_POST['excerpt_ru'] ?? ''),
            'excerpt_en' => trim($_POST['excerpt_en'] ?? ''),
            'full_text' => strip_tags(trim($_POST['full_text'] ?? ''), '<p><br><strong><em><ul><ol><li><h2><h3><a><u>'),
            'full_text_ru' => strip_tags(trim($_POST['full_text_ru'] ?? ''), '<p><br><strong><em><ul><ol><li><h2><h3><a><u>'),
            'full_text_en' => strip_tags(trim($_POST['full_text_en'] ?? ''), '<p><br><strong><em><ul><ol><li><h2><h3><a><u>'),
            'category' => trim($_POST['category'] ?? 'xebərlər'),
            'author' => trim($_POST['author'] ?? 'Faradj MMC'),
            'event_date' => trim($_POST['event_date'] ?? ''),
            'is_published' => isset($_POST['is_published']) ? 1 : 0,
            'errors' => [],
        ];

        if (empty($data['title'])) {
            $data['errors']['title'] = 'Başlıq (AZ) mütləqdir';
        }
        if (empty($data['excerpt'])) {
            $data['errors']['excerpt'] = 'Qısa mətn (AZ) mütləqdir';
        }
        if (empty($data['full_text'])) {
            $data['errors']['full_text'] = 'Tam mətn (AZ) mütləqdir';
        }

        return $data;
    }
}
