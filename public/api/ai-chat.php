<?php
require_once __DIR__ . '/../../src/load_env.php';
require_once __DIR__ . '/../../src/helpers/i18n.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$message = trim($input['message'] ?? '');
$history = $input['history'] ?? [];
$lang = $input['lang'] ?? currentLang();

if (empty($message)) {
    echo json_encode(['error' => 'Empty message']);
    exit;
}

$history = array_slice($history, -20);

$apiKey = env('ANTHROPIC_API_KEY');

$langInstruction = match ($lang) {
    'ru' => 'Пользователь использует русский язык. Отвечай ТОЛЬКО на русском.',
    'en' => 'The user is using English. Reply ONLY in English.',
    default => 'İstifadəçi Azərbaycan dilindən istifadə edir. YALNIZ Azərbaycan dilində cavab ver.',
};

$systemPrompt = $langInstruction . "\n\n" . "Sən Faradj MMC şirkətinin AI köməkçisisən.

Şirkət haqqında məlumat:
- Faradj MMC 2011-ci ildən fəaliyyət göstərir
- Azərbaycanın aparıcı dəftərxana ləvazimatları idxalçısı
- DOMS brendinin Azərbaycandakı yeganə rəsmi distribyutoru
- Brendlər: DOMS, Milan, Faber-Castell, Citizen, Uni-ball, Cello, Trix, Centropen, Qamma, Brons, Dolphin, Kangaro, Scriks
- Məhsullar: ofis ləvazimatları, məktəb ləvazimatları, rəssamlıq materialları, oyuncaqlar, pazllar, təsərrüfat malları
- Çatdırılma: Bakı daxili 1-2 iş günü, regionlara 3-5 iş günü
- Korporativ müştərilər üçün xüsusi qiymətlər və müqavilə şərtləri
- Əlaqə: +994 55 859 12 11, info@faradj.com
- Ünvan: Bakı, İnşaatçılar pr. 106
- Sayt: faradj.com, Kataloq: catalog.faradj.com
- Mağaza: 'Qələm' — Bakı, Neftçilər pr. 88

Qaydalar:
1. Azərbaycan, rus və ingilis dillərini istifadəçinin yazdığı dildə cavabla
2. Qısa və dost cavablar ver (max 3-4 cümlə)
3. Qiymət soruşarsa: 'Dəqiq qiymət üçün müraciət edin' de və /b2b səhifəsinə yönləndir
4. Hər zaman kömək etməyə çalış
5. Şirkəti müsbət təqdim et
6. Əgər bilmirsənsə: 'Bu barədə menecerlərimiz sizə kömək edə bilər — +994 55 859 12 11' de";

$messages = [];
foreach ($history as $h) {
    $messages[] = [
        'role' => $h['role'],
        'content' => $h['content'],
    ];
}
$messages[] = ['role' => 'user', 'content' => $message];

$payload = json_encode([
    'model' => 'claude-sonnet-4-20250514',
    'max_tokens' => 400,
    'system' => $systemPrompt,
    'messages' => $messages,
]);

$ch = curl_init('https://api.anthropic.com/v1/messages');
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $payload,
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/json',
        'x-api-key: ' . $apiKey,
        'anthropic-version: 2023-06-01',
    ],
    CURLOPT_TIMEOUT => 30,
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if (empty($apiKey) || $httpCode !== 200) {
    echo json_encode([
        'reply' => 'Bağışlayın, texniki problem var. Zəhmət olmasa +994 55 859 12 11 nömrəsinə zəng edin.',
    ]);
    exit;
}

$data = json_decode($response, true);
$reply = $data['content'][0]['text'] ?? 'Cavab verə bilmədim. Zəhmət olmasa yenidən cəhd edin.';

echo json_encode(['reply' => $reply]);
