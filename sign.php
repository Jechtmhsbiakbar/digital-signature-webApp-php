<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Validasi input
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Method tidak diizinkan.']);
    exit;
}

$message = isset($_POST['message']) ? trim($_POST['message']) : '';

if (empty($message)) {
    echo json_encode(['success' => false, 'error' => 'Pesan tidak boleh kosong.']);
    exit;
}

// Path private key
$privateKeyPath = __DIR__ . '/keys/private_key.pem';

if (!file_exists($privateKeyPath)) {
    echo json_encode([
        'success' => false,
        'error'   => 'Private key tidak ditemukan. Generate key terlebih dahulu.'
    ]);
    exit;
}

// Baca private key
$privateKeyPem = file_get_contents($privateKeyPath);

if ($privateKeyPem === false || empty($privateKeyPem)) {
    echo json_encode([
        'success' => false,
        'error'   => 'Gagal membaca private key.'
    ]);
    exit;
}

// Load private key resource
$privateKey = openssl_pkey_get_private($privateKeyPem);

if ($privateKey === false) {
    $errors = [];
    while ($msg = openssl_error_string()) {
        $errors[] = $msg;
    }
    echo json_encode([
        'success' => false,
        'error'   => 'Private key tidak valid: ' . implode('; ', $errors)
    ]);
    exit;
}

// Buat signature dengan SHA-256
$signature = '';
$result = openssl_sign($message, $signature, $privateKey, OPENSSL_ALGO_SHA256);

if (!$result) {
    $errors = [];
    while ($msg = openssl_error_string()) {
        $errors[] = $msg;
    }
    echo json_encode([
        'success' => false,
        'error'   => 'Gagal membuat signature: ' . implode('; ', $errors)
    ]);
    exit;
}

// Free resource
if (is_resource($privateKey)) {
    openssl_free_key($privateKey);
}

// Encode ke Base64
$signatureBase64 = base64_encode($signature);

echo json_encode([
    'success'         => true,
    'signature'       => $signatureBase64,
    'message'         => $message,
    'algorithm'       => 'RSA-SHA256',
    'signature_length'=> strlen($signatureBase64),
]);