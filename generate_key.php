<?php
/**
 * generate_key.php
 * Fungsi: Generate pasangan kunci RSA (Private Key & Public Key) 2048-bit
 * dan simpan ke folder keys/
 */

header('Content-Type: application/json');

// Pastikan folder keys/ ada dan bisa ditulis
$keysDir = __DIR__ . '/keys/';
if (!is_dir($keysDir)) {
    mkdir($keysDir, 0755, true);
}

try {
    // Konfigurasi untuk generate key RSA 2048-bit dengan SHA-256
    $config = [
        "digest_alg"       => "sha256",
        "private_key_bits" => 2048,
        "private_key_type" => OPENSSL_KEYTYPE_RSA,
    ];

    // 1. Generate pasangan kunci baru
    $keyPair = openssl_pkey_new($config);

    if (!$keyPair) {
        throw new Exception("Gagal generate key pair: " . openssl_error_string());
    }

    // 2. Ekstrak Private Key ke string PEM
    $privateKeyPem = "";
    $exportSuccess = openssl_pkey_export($keyPair, $privateKeyPem);

    if (!$exportSuccess) {
        throw new Exception("Gagal mengekspor private key: " . openssl_error_string());
    }

    // 3. Ekstrak Public Key dari key pair
    $keyDetails   = openssl_pkey_get_details($keyPair);
    $publicKeyPem = $keyDetails['key'];

    if (empty($publicKeyPem)) {
        throw new Exception("Gagal mendapatkan public key.");
    }

    // 4. Simpan ke file
    $privateKeyPath = $keysDir . 'private_key.pem';
    $publicKeyPath  = $keysDir . 'public_key.pem';

    if (file_put_contents($privateKeyPath, $privateKeyPem) === false) {
        throw new Exception("Gagal menyimpan private key ke file.");
    }

    if (file_put_contents($publicKeyPath, $publicKeyPem) === false) {
        throw new Exception("Gagal menyimpan public key ke file.");
    }

    // 5. Bebaskan resource key dari memori
    openssl_free_key($keyPair);

    // Kirim respons sukses
    echo json_encode([
        'success'     => true,
        'message'     => 'Pasangan kunci RSA 2048-bit berhasil digenerate!',
        'private_key' => $privateKeyPem,
        'public_key'  => $publicKeyPem,
        'timestamp'   => date('d-m-Y H:i:s'),
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
    ]);
}