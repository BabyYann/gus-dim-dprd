<?php
/**
 * API OCR e-KTP Ultra Presisi berbasis Google Gemini Vision AI
 * Sistem Informasi Pemenangan Terpadu Gus Dim Dapil Kraksaan Raya
 * Dilengkapi Diagnostik & Analisis Akar Masalah Real-Time
 */

require_once dirname(__DIR__) . '/config/helpers.php';

$startTime = microtime(true);

// Pastikan request adalah POST
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
    json_response(['success' => false, 'message' => 'Metode request harus POST.'], 405);
}

$body = get_request_body();
$rawImage = $body['image'] ?? $body['foto'] ?? '';
$mimeType = 'image/jpeg';
$base64Data = '';

if (!empty($_FILES['image']['tmp_name'])) {
    $fileTmp = $_FILES['image']['tmp_name'];
    $mimeType = mime_content_type($fileTmp) ?: 'image/jpeg';
    $base64Data = base64_encode(file_get_contents($fileTmp));
} elseif (!empty($rawImage)) {
    if (preg_match('/^data:(image\/[a-zA-Z0-9.+_-]+);base64,(.+)$/', $rawImage, $matches)) {
        $mimeType = $matches[1];
        $base64Data = $matches[2];
    } else {
        $base64Data = $rawImage;
        $mimeType = 'image/jpeg';
    }
} else {
    json_response(['success' => false, 'message' => 'Foto KTP tidak ditemukan dalam request.'], 400);
}

// Bersihkan spasi atau newline pada base64
$base64Data = preg_replace('/\s+/', '', $base64Data);

if (empty($base64Data)) {
    json_response(['success' => false, 'message' => 'Data gambar KTP kosong.'], 400);
}

// Prioritaskan API Key custom dari request uji coba jika disediakan
$apiKey = trim($body['api_key'] ?? '');
if (empty($apiKey)) {
    $apiKey = defined('GEMINI_API_KEY') ? GEMINI_API_KEY : '';
}

if (empty($apiKey)) {
    json_response([
        'success' => false,
        'message' => 'GEMINI_API_KEY belum dikonfigurasi di server. Silakan masukkan API key Anda pada form pengujian.'
    ], 500);
}

// Daftar model AI Gemini berperforma tinggi dengan failover otomatis
$preferredModel = defined('GEMINI_MODEL') ? GEMINI_MODEL : 'gemini-3.5-flash';
$candidateModels = array_unique([$preferredModel, 'gemini-3.5-flash', 'gemini-flash-latest', 'gemini-3.6-flash', 'gemini-pro-latest']);

// Jika request meminta model spesifik
$customModel = trim($body['model'] ?? '');
if (!empty($customModel)) {
    array_unshift($candidateModels, $customModel);
}
$candidateModels = array_values(array_unique($candidateModels));

$prompt = "Kamu adalah asisten OCR ahli untuk kartu identitas e-KTP Republik Indonesia. "
        . "Tugasmu adalah membaca dan mengekstrak data dari foto KTP yang diberikan secara sangat akurat dan presisi. "
        . "Format keluaran WAJIB HANYA JSON murni (valid JSON tanpa tanda kutip markdown) dengan struktur objek: "
        . "{"
        . "\"is_ktp\": true, "
        . "\"nik\": \"16 digit angka NIK murni\", "
        . "\"nama\": \"Nama lengkap warga\", "
        . "\"tempat_tgl_lahir\": \"Tempat dan tanggal lahir\", "
        . "\"jenis_kelamin\": \"LAKI-LAKI atau PEREMPUAN\", "
        . "\"alamat\": \"Alamat jalan atau dusun\", "
        . "\"rt_rw\": \"Nomor RT dan RW misal 003/002\", "
        . "\"kel_desa\": \"Nama desa atau kelurahan\", "
        . "\"kecamatan\": \"Nama kecamatan\", "
        . "\"agama\": \"Agama\", "
        . "\"status_perkawinan\": \"Status perkawinan\", "
        . "\"pekerjaan\": \"Pekerjaan\""
        . "} "
        . "Pedoman penting: "
        . "1. NIK harus tepat 16 digit angka. Koreksi jika ada huruf O terbaca 0, I terbaca 1, B terbaca 8, S terbaca 5. "
        . "2. Baca field 'Kecamatan' dan 'Kel/Desa' secara akurat dan bersih tanpa kata awalan (contoh: jika tertulis 'Kecamatan : BESUK', isi 'kecamatan': 'Besuk'; jika tertulis 'Kel/Desa : SUMURDALAM', isi 'kel_desa': 'Sumurdalam'). "
        . "3. Wilayah KTP ini umumnya di Kabupaten Probolinggo (Dapil Kraksaan, Besuk, atau Gading). "
        . "4. Jika gambar BUKAN e-KTP Indonesia atau teks tidak terbaca sama sekali, kembalikan is_ktp: false dan seluruh field string kosong. "
        . "5. Jangan sertakan teks pengantar atau markdown apapun selain objek JSON di atas.";

$payload = json_encode([
    'contents' => [
        [
            'parts' => [
                ['text' => $prompt],
                [
                    'inline_data' => [
                        'mime_type' => $mimeType,
                        'data' => $base64Data
                    ]
                ]
            ]
        ]
    ],
    'generationConfig' => [
        'response_mime_type' => 'application/json',
        'temperature' => 0.1
    ]
]);

$attempts = [];
$geminiResponse = null;
$successfulModel = '';

foreach ($candidateModels as $model) {
    $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key=" . urlencode($apiKey);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'User-Agent: GusDim-OCR-Engine/1.0'
    ]);
    curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

    $responseRaw = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlErr = curl_error($ch);
    $curlErrNo = curl_errno($ch);
    curl_close($ch);

    $attempts[] = [
        'model' => $model,
        'http_code' => $httpCode,
        'curl_errno' => $curlErrNo,
        'error' => $curlErr ?: null
    ];

    if ($httpCode === 200 && !empty($responseRaw)) {
        $parsed = json_decode($responseRaw, true);
        if (!empty($parsed['candidates'][0]['content']['parts'][0]['text'])) {
            $geminiResponse = $parsed;
            $successfulModel = $model;
            break; // Berhasil
        }
    } elseif ($curlErrNo === 7 || $curlErrNo === 28) {
        // Hentikan loop jika terjadi timeout / blokir jaringan di hosting
        break;
    }
}

if (!$geminiResponse || empty($geminiResponse['candidates'][0]['content']['parts'][0]['text'])) {
    json_response([
        'success' => false,
        'message' => 'Gagal memproses gambar melalui AI Vision. Pastikan foto KTP terlihat jelas dan coba lagi.',
        'attempts' => $attempts,
        'execution_time_ms' => round((microtime(true) - $startTime) * 1000)
    ], 502);
}

$textOutput = trim($geminiResponse['candidates'][0]['content']['parts'][0]['text']);
$extracted = json_decode($textOutput, true);

if (!is_array($extracted)) {
    // Bersihkan kemungkinan markdown wrapper jika ada
    $cleanJson = preg_replace('/^```(?:json)?\s*/i', '', $textOutput);
    $cleanJson = preg_replace('/\s*```$/', '', $cleanJson);
    $extracted = json_decode(trim($cleanJson), true);
}

if (!is_array($extracted) || empty($extracted['is_ktp']) || empty($extracted['nik'])) {
    if (is_array($extracted) && isset($extracted['is_ktp']) && $extracted['is_ktp'] === false) {
        json_response([
            'success' => false,
            'message' => 'Gambar yang dipindai tidak terdeteksi sebagai e-KTP Indonesia. Mohon posisikan kartu KTP di dalam bingkai foto dengan jelas.',
            'raw_output' => $textOutput,
            'model_used' => $successfulModel,
            'execution_time_ms' => round((microtime(true) - $startTime) * 1000)
        ]);
    }
}

// Normalisasi NIK murni 16 digit angka
$nikMurni = preg_replace('/\D/', '', $extracted['nik'] ?? '');
if (strlen($nikMurni) > 16) {
    $nikMurni = substr($nikMurni, 0, 16);
}
$extracted['nik'] = $nikMurni;

// Format nama rapi huruf kapital
if (!empty($extracted['nama'])) {
    $extracted['nama'] = strtoupper(trim($extracted['nama']));
}

// Cek otomatis ke database apakah NIK sudah pernah didaftarkan
$extracted['exists'] = false;
$extracted['existing_data'] = null;

if (strlen($nikMurni) === 16) {
    if (Database::isMysql()) {
        try {
            $pdo = Database::getPdo();
            $stmt = $pdo->prepare("SELECT id, nama, jalur, desa, kecamatan, input_by_user_name, DATE_FORMAT(created_at, '%d/%m/%Y %H:%i') as tanggal FROM pendukung WHERE nik = :nik LIMIT 1");
            $stmt->execute(['nik' => $nikMurni]);
            $existing = $stmt->fetch();
            if ($existing) {
                $extracted['exists'] = true;
                $extracted['existing_data'] = $existing;
            }
        } catch (Exception $e) {
            // Abaikan error database saat cek duplikasi
        }
    } else {
        $dbData = Database::getJsonData();
        $pendukungList = $dbData['pendukung'] ?? [];
        foreach ($pendukungList as $p) {
            if (($p['nik'] ?? '') === $nikMurni) {
                $extracted['exists'] = true;
                $extracted['existing_data'] = [
                    'nama' => $p['nama'] ?? '',
                    'jalur' => $p['jalur'] ?? '',
                    'desa' => $p['desa'] ?? '',
                    'kecamatan' => $p['kecamatan'] ?? '',
                    'input_by_user_name' => $p['input_by_user_name'] ?? 'Petugas Lapangan',
                    'tanggal' => $p['created_at'] ?? ''
                ];
                break;
            }
        }
    }
}

// Catat log audit jika pengguna terautentikasi
$user = get_auth_user();
if ($user) {
    log_activity($user['id'] ?? null, $user['nama'] ?? 'Petugas', 'OCR_KTP', $nikMurni ?: null, 'Pemindaian KTP AI Gemini Vision: ' . ($extracted['nama'] ?? ''));
}

// Tambahkan informasi diagnostik akar masalah
$extracted['diagnostics'] = [
    'model_used' => $successfulModel,
    'execution_time_ms' => round((microtime(true) - $startTime) * 1000),
    'image_mime' => $mimeType,
    'image_payload_kb' => round(strlen($base64Data) * 0.75 / 1024, 2),
    'raw_output' => $textOutput,
    'attempts' => $attempts
];

json_response([
    'success' => true,
    'message' => 'e-KTP berhasil dipindai dengan presisi AI Gemini Vision.',
    'data' => $extracted
]);
