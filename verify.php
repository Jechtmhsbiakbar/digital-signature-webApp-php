<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

// Validasi method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'valid' => false, 'error' => 'Method tidak diizinkan.']);
    exit;
}

// Ambil input
$message         = isset($_POST['message']) ? trim($_POST['message']) : '';
$signatureBase64 = isset($_POST['signature']) ? trim($_POST['signature']) : '';

if (empty($message) || empty($signatureBase64)) {
    echo json_encode([
        'success' => false,
        'valid'   => false,
        'error'   => 'Pesan dan signature tidak boleh kosong.'
    ]);
    exit;
}

// Path public key
$publicKeyPath = __DIR__ . '/keys/public_key.pem';

if (!file_exists($publicKeyPath)) {
    echo json_encode([
        'success' => false,
        'valid'   => false,
        'error'   => 'Public key tidak ditemukan. Generate key terlebih dahulu.'
    ]);
    exit;
}

// Baca public key
$publicKeyPem = file_get_contents($publicKeyPath);

if ($publicKeyPem === false || empty($publicKeyPem)) {
    echo json_encode([
        'success' => false,
        'valid'   => false,
        'error'   => 'Gagal membaca public key.'
    ]);
    exit;
}

// Load public key
$publicKey = openssl_pkey_get_public($publicKeyPem);

if ($publicKey === false) {
    $errors = [];
    while ($msg = openssl_error_string()) {
        $errors[] = $msg;
    }

    echo json_encode([
        'success' => false,
        'valid'   => false,
        'error'   => 'Public key tidak valid: ' . implode('; ', $errors)
    ]);
    exit;
}

// Decode signature
$signature = base64_decode($signatureBase64, true);

if ($signature === false || strlen($signature) === 0) {
    echo json_encode([
        'success' => false,
        'valid'   => false,
        'error'   => 'Signature tidak valid atau kosong.'
    ]);
    exit;
}

// Verifikasi
$verifyResult = openssl_verify($message, $signature, $publicKey, OPENSSL_ALGO_SHA256);

// Free resource
if ($publicKey !== false) {
    openssl_pkey_free($publicKey);
}

// Output
if ($verifyResult === 1) {
    echo json_encode([
        'success' => true,
        'valid'   => true,
        'message' => $message,
        'status'  => 'VALID – Pesan asli dan tidak dimodifikasi.'
    ]);
} elseif ($verifyResult === 0) {
    echo json_encode([
        'success' => true,
        'valid'   => false,
        'message' => $message,
        'status'  => 'TIDAK VALID – Pesan telah dimodifikasi atau signature dipalsukan.',
        'info'    => 'Contoh: perubahan "Budi" menjadi "Andi" akan membuat verifikasi gagal.'
    ]);
} else {
    $errors = [];
    while ($msg = openssl_error_string()) {
        $errors[] = $msg;
    }

    echo json_encode([
        'success' => false,
        'valid'   => false,
        'error'   => 'Error saat verifikasi: ' . implode('; ', $errors)
    ]);
}
exit;