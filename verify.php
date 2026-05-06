<?php
/**
 * verify.php
 * Fungsi: Memverifikasi keaslian teks menggunakan Public Key dan Signature
 * Jika teks dimodifikasi (simulasi MITM), verifikasi akan GAGAL
 */

header('Content-Type: application/json');

// Hanya menerima request POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Hanya menerima request POST.']);
    exit;
}

// Ambil input dari request
$text            = isset($_POST['text']) ? trim($_POST['text']) : '';
$signatureBase64 = isset($_POST['signature']) ? trim($_POST['signature']) : '';

if (empty($text)) {
    echo json_encode(['success' => false, 'message' => 'Teks tidak boleh kosong.']);
    exit;
}

if (empty($signatureBase64)) {
    echo json_encode(['success' => false, 'message' => 'Signature tidak boleh kosong.']);
    exit;
}

// Path ke public key
$publicKeyPath = __DIR__ . '/keys/public_key.pem';

if (!file_exists($publicKeyPath)) {
    echo json_encode([
        'success' => false,
        'message' => 'Public key tidak ditemukan. Silakan generate key terlebih dahulu.',
    ]);
    exit;
}

try {
    // 1. Baca isi file public key
    $publicKeyPem = file_get_contents($publicKeyPath);

    if ($publicKeyPem === false) {
        throw new Exception("Gagal membaca file public key.");
    }

    // 2. Load public key dari string PEM
    $publicKey = openssl_pkey_get_public($publicKeyPem);

    if (!$publicKey) {
        throw new Exception("Gagal memuat public key: " . openssl_error_string());
    }

    // 3. Decode signature dari Base64 kembali ke binary
    $signatureBinary = base64_decode($signatureBase64);

    if ($signatureBinary === false) {
        throw new Exception("Signature bukan format Base64 yang valid.");
    }

    // 4. Verifikasi signature menggunakan openssl_verify
    //    Return value:
    //      1  = Signature VALID (teks asli, tidak dimodifikasi)
    //      0  = Signature TIDAK VALID (teks telah dimodifikasi / MITM!)
    //     -1  = Error saat verifikasi
    $verifyResult = openssl_verify($text, $signatureBinary, $publicKey, OPENSSL_ALGO_SHA256);

    // 5. Bebaskan resource key
    openssl_free_key($publicKey);

    // 6. Tentukan status berdasarkan hasil verifikasi
    if ($verifyResult === 1) {
        // ✅ VALID - Teks tidak dimodifikasi
        echo json_encode([
            'success'   => true,
            'valid'     => true,
            'status'    => 'VALID',
            'message'   => '✅ Dokumen VALID! Teks asli dan tidak ada modifikasi.',
            'detail'    => 'Signature cocok dengan teks dan public key. Dokumen dapat dipercaya.',
            'timestamp' => date('d-m-Y H:i:s'),
        ]);

    } elseif ($verifyResult === 0) {
        // ❌ TIDAK VALID - Teks dimodifikasi (simulasi MITM berhasil dideteksi)
        echo json_encode([
            'success'   => true,
            'valid'     => false,
            'status'    => 'TIDAK VALID',
            'message'   => '❌ PERINGATAN! Dokumen TIDAK VALID atau DATA DIMODIFIKASI!',
            'detail'    => 'Signature tidak cocok dengan teks yang diberikan. Kemungkinan terjadi serangan Man-in-the-Middle (MITM) atau teks telah diubah.',
            'timestamp' => date('d-m-Y H:i:s'),
        ]);

    } else {
        // Error teknis saat verifikasi
        throw new Exception("Error saat verifikasi: " . openssl_error_string());
    }

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
    ]);
}