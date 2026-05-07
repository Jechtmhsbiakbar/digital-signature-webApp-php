<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Set environment variable untuk OpenSSL (khusus di Windows dengan XAMPP)
putenv("OPENSSL_CONF=C:\\xampp\\php\\extras\\ssl\\openssl.cnf");

$keysDir = __DIR__ . '/keys';
// Buat folder keys jika belum ada
if (!is_dir($keysDir)) {
    if (!mkdir($keysDir, 0755, true)) {
        echo json_encode([
            'success' => false,
            'error'   => 'Gagal membuat folder keys/. Pastikan permission direktori mencukupi.'
        ]);
        exit;
    }
}

// Konfigurasi key RSA 2048-bit
$config = [
    'config'           => 'C:\\xampp\\php\\extras\\ssl\\openssl.cnf', // Path ke openssl.cnf di XAMPP
    'digest_alg'       => 'sha256',
    'private_key_bits' => 2048,
    'private_key_type' => OPENSSL_KEYTYPE_RSA,
];
// Generate key pair
$keyResource = openssl_pkey_new($config);

if ($keyResource === false) {
    $errors = [];
    while ($msg = openssl_error_string()) {
        $errors[] = $msg;
    }
    echo json_encode([
        'success' => false,
        'error'   => 'Gagal generate key: ' . implode('; ', $errors)
    ]);
    exit;
}

// Export private key
$privateKeyPem = '';

$exported = openssl_pkey_export(
    $keyResource,
    $privateKeyPem,
    null,
    $config
);

if (!$exported || empty($privateKeyPem)) {

    $errors = [];

    while ($msg = openssl_error_string()) {
        $errors[] = $msg;
    }

    echo json_encode([
        'success' => false,
        'error'   => 'Gagal export private key: ' . implode('; ', $errors)
    ]);

    exit;
}

// Ambil public key
$keyDetails = openssl_pkey_get_details($keyResource);

if ($keyDetails === false || !isset($keyDetails['key'])) {
    echo json_encode([
        'success' => false,
        'error'   => 'Gagal mendapatkan detail public key.'
    ]);
    exit;
}

$publicKeyPem = $keyDetails['key'];

// Simpan ke file
$privateKeyPath = $keysDir . '/private_key.pem';
$publicKeyPath  = $keysDir . '/public_key.pem';

$savedPrivate = file_put_contents($privateKeyPath, $privateKeyPem);
$savedPublic  = file_put_contents($publicKeyPath,  $publicKeyPem);

if ($savedPrivate === false || $savedPublic === false) {
    echo json_encode([
        'success' => false,
        'error'   => 'Gagal menyimpan key ke file. Periksa permission folder keys/.'
    ]);
    exit;
}

// Free resource (PHP 8+ otomatis, tapi tetap good practice)
if (is_resource($keyResource)) {
    openssl_pkey_free($keyResource);
}

echo json_encode([
    'success'    => true,
    'public_key' => $publicKeyPem,
    'message'    => 'Key pair RSA-2048 berhasil dibuat dan disimpan.',
    'paths'      => [
        'private' => 'keys/private_key.pem',
        'public'  => 'keys/public_key.pem',
    ]
]);