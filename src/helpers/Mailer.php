<?php
require_once base_path('src/helpers/phpmailer/Exception.php');
require_once base_path('src/helpers/phpmailer/PHPMailer.php');
require_once base_path('src/helpers/phpmailer/SMTP.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Mailer
{
    private static function create(): PHPMailer
    {
        $mail = new PHPMailer(true);

        $mail->isSMTP();
        $mail->Host       = env('MAIL_HOST', 'mail.faradj.com');
        $mail->SMTPAuth   = true;
        $mail->Username   = env('MAIL_USERNAME', 'noreply@faradj.com');
        $mail->Password   = env('MAIL_PASSWORD', '');
        $mail->SMTPSecure = env('MAIL_ENCRYPTION', 'ssl') === 'ssl'
            ? PHPMailer::ENCRYPTION_SMTPS
            : PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = (int) env('MAIL_PORT', 465);
        $mail->CharSet    = 'UTF-8';
        $mail->setFrom(
            env('MAIL_FROM', 'noreply@faradj.com'),
            env('MAIL_FROM_NAME', 'Faradj MMC')
        );

        return $mail;
    }

    public static function getRecipient(string $type): string
    {
        $map = [
            'b2b'      => env('MAIL_TO_B2B',      'sales@faradj.org'),
            'contact'  => env('MAIL_TO_CONTACT',  'info@faradj.com'),
            'callback' => env('MAIL_TO_CALLBACK', 'sales@faradj.org'),
        ];
        return $map[$type] ?? env('MAIL_TO_CONTACT', 'info@faradj.com');
    }

    /** B2B Sorğu → MAIL_TO_B2B */
    public static function sendB2B(array $data): bool
    {
        try {
            $mail = self::create();
            $mail->addAddress(self::getRecipient('b2b'), 'Faradj Sales');
            if (!empty($data['email'])) {
                $mail->addReplyTo($data['email'], $data['contact'] ?? '');
            }
            $mail->Subject = 'Yeni B2B müraciəti — Faradj MMC';
            $mail->isHTML(true);
            $mail->Body = self::templateB2B($data);
            $mail->AltBody = strip_tags($mail->Body);
            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log('[Mailer] B2B error: ' . $e->getMessage());
            return false;
        }
    }

    /** Əlaqə formu → MAIL_TO_CONTACT */
    public static function sendContact(array $data): bool
    {
        try {
            $mail = self::create();
            $mail->addAddress(self::getRecipient('contact'), 'Faradj Info');
            if (!empty($data['email'])) {
                $mail->addReplyTo($data['email'], $data['name'] ?? '');
            }
            $mail->Subject = 'Yeni mesaj — Faradj MMC';
            $mail->isHTML(true);
            $mail->Body = self::templateContact($data);
            $mail->AltBody = strip_tags($mail->Body);
            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log('[Mailer] Contact error: ' . $e->getMessage());
            return false;
        }
    }

    /** Zəng sorğusu → MAIL_TO_CALLBACK */
    public static function sendCallback(array $data): bool
    {
        try {
            $mail = self::create();
            $mail->addAddress(self::getRecipient('callback'), 'Faradj Sales');
            if (!empty($data['email'])) {
                $mail->addReplyTo($data['email'], $data['name'] ?? '');
            }
            $mail->Subject = 'Zəng sifarişi — Faradj MMC';
            $mail->isHTML(true);
            $mail->Body = '
            <div style="font-family:Arial,sans-serif;max-width:500px;">
              <div style="background:linear-gradient(135deg,#6c63ff,#8b5cf6);padding:24px;border-radius:12px 12px 0 0;text-align:center;">
                <h2 style="color:white;margin:0;">📞 Zəng Sorğusu</h2>
              </div>
              <div style="background:#f9f9f9;padding:24px;border-radius:0 0 12px 12px;">
                <p><strong>Ad:</strong> ' . htmlspecialchars($data['name'] ?? '') . '</p>
                <p><strong>Telefon:</strong> ' . htmlspecialchars($data['phone'] ?? '') . '</p>
                <p><strong>Uyğun vaxt:</strong> ' . htmlspecialchars($data['time'] ?? '-') . '</p>
                <p style="font-size:12px;color:#999;">' . date('d.m.Y H:i') . ' — faradj.com</p>
              </div>
            </div>';
            $mail->AltBody = "Zəng sorğusu\nAd: " . ($data['name'] ?? '') . "\nTelefon: " . ($data['phone'] ?? '') . "\nVaxt: " . ($data['time'] ?? '');
            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log('[Mailer] Callback error: ' . $e->getMessage());
            return false;
        }
    }

    private static function templateB2B(array $d): string
    {
        $industry = $d['industry'] ?? $d['activity'] ?? '-';
        return '
        <div style="font-family:Arial,sans-serif;max-width:600px;margin:0 auto;">
          <div style="background:linear-gradient(135deg,#6c63ff,#ff6584);padding:28px;border-radius:12px 12px 0 0;text-align:center;">
            <h1 style="color:white;margin:0;font-size:22px;">🆕 Yeni Müraciət</h1>
          </div>
          <div style="background:#f9f9f9;padding:28px;border-radius:0 0 12px 12px;">
            <table style="width:100%;border-collapse:collapse;">
              <tr><td style="padding:10px;font-weight:bold;color:#555;width:40%;">Şirkət:</td><td style="padding:10px;color:#1a1a2e;"><strong>' . htmlspecialchars($d['company'] ?? '') . '</strong></td></tr>
              <tr style="background:#fff;"><td style="padding:10px;font-weight:bold;color:#555;">Əlaqə şəxsi:</td><td style="padding:10px;color:#1a1a2e;">' . htmlspecialchars($d['contact'] ?? '') . '</td></tr>
              <tr><td style="padding:10px;font-weight:bold;color:#555;">Telefon:</td><td style="padding:10px;color:#1a1a2e;">' . htmlspecialchars($d['phone'] ?? '') . '</td></tr>
              <tr style="background:#fff;"><td style="padding:10px;font-weight:bold;color:#555;">E-mail:</td><td style="padding:10px;"><a href="mailto:' . htmlspecialchars($d['email'] ?? '') . '" style="color:#6c63ff;">' . htmlspecialchars($d['email'] ?? '') . '</a></td></tr>
              <tr><td style="padding:10px;font-weight:bold;color:#555;">Fəaliyyət sahəsi:</td><td style="padding:10px;color:#1a1a2e;">' . htmlspecialchars($industry) . '</td></tr>
              <tr style="background:#fff;"><td style="padding:10px;font-weight:bold;color:#555;">Aylıq həcm:</td><td style="padding:10px;color:#1a1a2e;">' . htmlspecialchars($d['volume'] ?? '-') . '</td></tr>
              <tr><td style="padding:10px;font-weight:bold;color:#555;">Büdcə:</td><td style="padding:10px;color:#1a1a2e;">' . htmlspecialchars($d['budget'] ?? '-') . '</td></tr>
              <tr style="background:#fff;"><td style="padding:10px;font-weight:bold;color:#555;">Məhsullar:</td><td style="padding:10px;color:#1a1a2e;">' . nl2br(htmlspecialchars($d['products'] ?? '-')) . '</td></tr>
              <tr><td style="padding:10px;font-weight:bold;color:#555;">Əlavə qeyd:</td><td style="padding:10px;color:#1a1a2e;">' . nl2br(htmlspecialchars($d['note'] ?? '-')) . '</td></tr>
            </table>
            <div style="margin-top:20px;padding:12px;background:#ede9ff;border-radius:8px;text-align:center;font-size:12px;color:#6c63ff;">Bu sorğu faradj.com saytından göndərildi — ' . date('d.m.Y H:i') . '</div>
          </div>
        </div>';
    }

    /** Admin notification for new requests — sends to ADMIN_EMAIL */
    public static function sendAdminNotification(array $data): bool
    {
        $adminEmail = env('ADMIN_EMAIL', env('MAIL_TO_CONTACT', 'info@faradj.com'));
        if (!$adminEmail) {
            return false;
        }
        try {
            $mail = self::create();
            $mail->addAddress($adminEmail, 'Faradj Admin');
            $type = $data['type'] ?? 'contact';
            $labels = ['contact' => 'Əlaqə formu', 'b2b' => 'B2B müraciəti', 'callback' => 'Zəng sorğusu'];
            $mail->Subject = '🆕 Yeni müraciət: ' . ($labels[$type] ?? $type) . ' — Faradj MMC';
            $mail->isHTML(true);
            $mail->Body = self::templateAdminNotification($data);
            $mail->AltBody = strip_tags($mail->Body);
            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log('[Mailer] Admin notification error: ' . $e->getMessage());
            return false;
        }
    }

    private static function templateAdminNotification(array $d): string
    {
        $type = $d['type'] ?? 'contact';
        $adminUrl = $d['admin_url'] ?? 'https://faradj.com/admin/contacts';
        if ($type === 'b2b') {
            $adminUrl = 'https://faradj.com/admin/b2b';
        } elseif ($type === 'callback') {
            $adminUrl = 'https://faradj.com/admin/callbacks';
        }
        $labels = [
            'name' => 'Ad', 'email' => 'E-mail', 'phone' => 'Telefon', 'message' => 'Mesaj',
            'company' => 'Şirkət', 'contact' => 'Əlaqə şəxsi', 'activity' => 'Fəaliyyət', 'volume' => 'Aylıq həcm',
            'budget' => 'Büdcə', 'products' => 'Məhsullar', 'note' => 'Qeyd', 'subject' => 'Mövzu',
            'time' => 'Uyğun vaxt',
        ];
        $rows = '';
        foreach ($d as $k => $val) {
            if (in_array($k, ['type', 'admin_url']) || $val === '' || $val === null) {
                continue;
            }
            $label = $labels[$k] ?? $k;
            $rows .= "<tr><td style='padding:8px;font-weight:bold;color:#555;width:120px;'>{$label}:</td><td style='padding:8px;'>" . nl2br(htmlspecialchars((string)$val)) . "</td></tr>";
        }
        return "
        <div style='font-family:Arial,sans-serif;max-width:600px;margin:0 auto;'>
          <div style='background:linear-gradient(135deg,#6c63ff,#ff6584);padding:24px;border-radius:12px 12px 0 0;text-align:center;'>
            <h2 style='color:white;margin:0;font-size:18px;'>🆕 Yeni müraciət</h2>
          </div>
          <div style='background:#f9f9f9;padding:24px;border-radius:0 0 12px 12px;'>
            <table style='width:100%;border-collapse:collapse;'>{$rows}</table>
            <div style='margin-top:20px;text-align:center;'>
              <a href='{$adminUrl}' style='display:inline-block;background:#6c63ff;color:white;padding:12px 24px;border-radius:8px;text-decoration:none;font-weight:600;'>Admin paneldə bax</a>
            </div>
            <p style='font-size:12px;color:#999;margin-top:16px;'>" . date('d.m.Y H:i') . " — faradj.com</p>
          </div>
        </div>";
    }

    private static function templateContact(array $d): string
    {
        return '
        <div style="font-family:Arial,sans-serif;max-width:600px;margin:0 auto;">
          <div style="background:linear-gradient(135deg,#6c63ff,#ff6584);padding:28px;border-radius:12px 12px 0 0;text-align:center;">
            <h1 style="color:white;margin:0;font-size:22px;">📩 Yeni Müraciət</h1>
          </div>
          <div style="background:#f9f9f9;padding:28px;border-radius:0 0 12px 12px;">
            <table style="width:100%;border-collapse:collapse;">
              <tr><td style="padding:10px;font-weight:bold;color:#555;width:35%;">Ad Soyad:</td><td style="padding:10px;color:#1a1a2e;"><strong>' . htmlspecialchars($d['name'] ?? '') . '</strong></td></tr>
              <tr style="background:#fff;"><td style="padding:10px;font-weight:bold;color:#555;">E-mail:</td><td style="padding:10px;"><a href="mailto:' . htmlspecialchars($d['email'] ?? '') . '" style="color:#6c63ff;">' . htmlspecialchars($d['email'] ?? '') . '</a></td></tr>
              <tr><td style="padding:10px;font-weight:bold;color:#555;">Telefon:</td><td style="padding:10px;color:#1a1a2e;">' . htmlspecialchars($d['phone'] ?? '-') . '</td></tr>
              <tr style="background:#fff;"><td style="padding:10px;font-weight:bold;color:#555;">Mövzu:</td><td style="padding:10px;color:#1a1a2e;">' . htmlspecialchars($d['subject'] ?? '-') . '</td></tr>
              <tr><td style="padding:10px;font-weight:bold;color:#555;vertical-align:top;">Mesaj:</td><td style="padding:10px;color:#1a1a2e;">' . nl2br(htmlspecialchars($d['message'] ?? '')) . '</td></tr>
            </table>
            <div style="margin-top:20px;padding:12px;background:#ede9ff;border-radius:8px;text-align:center;font-size:12px;color:#6c63ff;">Bu müraciət faradj.com saytından göndərildi — ' . date('d.m.Y H:i') . '</div>
          </div>
        </div>';
    }
}
