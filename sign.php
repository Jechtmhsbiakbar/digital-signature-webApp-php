<?php
/**
 * sign.php
 * Fungsi: Membuat tanda tangan digital (signature) dari teks
 * menggunakan Private Key dan algoritma SHA-256
 */

header('Content-Type: application/json');

// Hanya menerima request POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Hanya menerima request POST.']);
    exit;
}

// Ambil input teks dari request
$text = isset($_POST['text']) ? trim($_POST['text']) : '';

if (empty($text)) {
    echo json_encode(['success' => false, 'message' => 'Teks tidak boleh kosong.']);
    exit;
}

// Path ke private key
$privateKeyPath = __DIR__ . '/keys/private_key.pem';

if (!file_exists($privateKeyPath)) {
    echo json_encode([
        'success' => false,
        'message' => 'Private key tidak ditemukan. Silakan generate key terlebih dahulu.',
    ]);
    exit;
}

try {
    // 1. Baca isi file private key
    $privateKeyPem = file_get_contents($privateKeyPath);

    if ($privateKeyPem === false) {
        throw new Exception("Gagal membaca file private key.");
    }

    // 2. Load private key dari string PEM
    $privateKey = openssl_pkey_get_private($privateKeyPem);

    if (!$privateKey) {
        throw new Exception("Gagal memuat private key: " . openssl_error_string());
    }

    // 3. Buat signature menggunakan openssl_sign dengan SHA-256
    //    $signature akan berisi binary signature
    $signature = "";
    $signSuccess = openssl_sign($text, $signature, $privateKey, OPENSSL_ALGO_SHA256);

    if (!$signSuccess) {
        throw new Exception("Gagal membuat signature: " . openssl_error_string());
    }

    // 4. Encode signature ke Base64 agar bisa ditampilkan sebagai teks
    $signatureBase64 = base64_encode($signature);

    // 5. Bebaskan resource key
    openssl_free_key($privateKey);

    // Kirim respons sukses
    echo json_encode([
        'success'          => true,
        'message'          => 'Signature berhasil dibuat!',
        'original_text'    => $text,
        'signature_base64' => $signatureBase64,
        'algorithm'        => 'RSA-SHA256',
        'timestamp'        => date('d-m-Y H:i:s'),
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
    ]);
}