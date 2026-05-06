# 🔐 Digital Signature Web Application

> **Web Verifikator Dokumen** — Aplikasi praktikum kriptografi untuk pembuatan, verifikasi, dan deteksi Man-in-the-Middle (MITM) menggunakan tanda tangan digital RSA-SHA256.

---

## 📋 Daftar Isi

- [Tentang Proyek](#tentang-proyek)
- [Fitur Utama](#fitur-utama)
- [Teknologi & Keamanan](#teknologi--keamanan)
- [Persyaratan Sistem](#persyaratan-sistem)
- [Instalasi](#instalasi)
- [Cara Penggunaan](#cara-penggunaan)
- [Struktur Proyek](#struktur-proyek)
- [Dokumentasi API](#dokumentasi-api)
- [Konsep Keamanan](#konsep-keamanan)
- [Demonstrasi MITM](#demonstrasi-mitm)

---

## 📖 Tentang Proyek

**digital-signature-webApp-php** adalah aplikasi web interaktif yang dirancang untuk memahami dan mendemonstrasikan konsep **Digital Signature (Tanda Tangan Digital)** dalam kriptografi. 

Aplikasi ini memungkinkan pengguna untuk:
- Generate pasangan kunci RSA 2048-bit
- Menandatangani dokumen/teks menggunakan private key
- Memverifikasi keaslian dokumen menggunakan public key
- Mensimulasikan dan mendeteksi serangan **Man-in-the-Middle (MITM)**

Proyek ini cocok untuk:
- 🎓 Pembelajaran kriptografi di perguruan tinggi
- 🔬 Praktikum keamanan informasi
- 📚 Referensi implementasi digital signature
- 🛡️ Pemahaman dasar RSA dan hashing

---

## ✨ Fitur Utama

### 1️⃣ **Generate RSA Key Pair**
- Generate pasangan kunci RSA 2048-bit secara aman
- Private Key untuk menandatangani dokumen
- Public Key untuk memverifikasi signature
- Automatic save ke folder `keys/`
- Tampilan formatted PEM untuk kedua key

### 2️⃣ **Sign Document/Text**
- Buat digital signature dari teks/dokumen
- Menggunakan Private Key dan algoritma SHA-256
- Output signature dalam format Base64
- Automatic fill ke tab verify
- Timestamp untuk setiap signature

### 3️⃣ **Verify Signature**
- Verifikasi keaslian dokumen menggunakan Public Key
- Deteksi modifikasi data
- Status valid/invalid dengan detail informasi
- Support untuk signature dalam format Base64

### 4️⃣ **MITM Detection & Simulation**
- Simulasi serangan Man-in-the-Middle
- Modifikasi dokumen untuk menunjukkan deteksi
- Visual flow chart untuk memahami attack pattern
- Demonstrasi langsung dampak MITM

### 5️⃣ **User Interface**
- Modern dark theme dengan gradient accent
- Grid background & glassmorphism design
- Responsive untuk desktop dan mobile
- Real-time step indicator
- Informasi detail untuk setiap proses

---

## 🛠️ Teknologi & Keamanan

### Backend
- **PHP 7.4+** dengan extension OpenSSL
- RESTful API untuk operasi cryptographic
- Input validation dan error handling

### Frontend
- **HTML5** & **CSS3** dengan design system
- **Vanilla JavaScript** untuk interaksi
- Modern CSS features (Grid, Flexbox, CSS Variables)
- Google Fonts (JetBrains Mono, Syne)

### Kriptografi
- **RSA 2048-bit** untuk key generation
- **SHA-256** untuk hashing
- **OPENSSL** PHP Extension untuk operasi cryptographic
- **Base64** encoding untuk signature transport

### Keamanan
- Private Key tersimpan di server (folder `keys/`)
- No key transmission ke client (safe)
- Input sanitization untuk mencegah injection
- POST-only untuk operasi sensitif

---

## 📦 Persyaratan Sistem

### Server
- **PHP 7.4** atau lebih tinggi
- **OpenSSL Extension** (biasanya sudah default di PHP)
- **XAMPP** atau web server Apache (recommended)
- Write permission untuk folder `keys/`

### Browser
- Modern browser dengan ES6 support
- JavaScript enabled
- Local storage support (opsional)

### Verifikasi OpenSSL
```bash
php -m | grep -i openssl
# atau
php -r "echo openssl_version();"
```

---

## 🚀 Instalasi

### 1. Clone atau Download Project
```bash
cd c:\xampp\htdocs
git clone <repository-url> digital-signature-webApp-php
cd digital-signature-webApp-php
```

### 2. Setup Folder Keys
```bash
mkdir keys
chmod 755 keys
```

### 3. Verifikasi Struktur
```
digital-signature-webApp-php/
├── index.php           # Main application
├── generate_key.php    # API: Generate keys
├── sign.php           # API: Create signature
├── verify.php         # API: Verify signature
├── keys/              # Storage untuk RSA keys
│   ├── private_key.pem
│   └── public_key.pem
└── Docs/
    └── README.md
```

### 4. Jalankan di XAMPP
```bash
# Start Apache di XAMPP
# Buka di browser
http://localhost/digital-signature-webApp-php/
```

---

## 📖 Cara Penggunaan

### Step 1: Generate RSA Key Pair ⚙️
1. Klik button **"Generate Kunci RSA 2048-bit"**
2. Tunggu proses generate (sesuai loading spinner)
3. Lihat hasil Private Key dan Public Key di tab
4. Optional: Copy salah satu key untuk backup
5. Keys otomatis tersimpan di server

```bash
# Output di folder keys/
keys/private_key.pem    # Private key (rahasia)
keys/public_key.pem     # Public key (bisa dibagikan)
```

### Step 2: Sign Document ✍️
1. Masuk ke tab **"Sign"**
2. Input teks/dokumen di textarea
3. Klik **"Buat Signature"**
4. Tunggu proses signing
5. Lihat hasil signature dalam Base64
6. Signature auto-fill ke tab Verify

```
Input: "Hello World"
Output: lKFJvKDJw9...LvNxOplQmA== (Base64)
```

### Step 3: Verify Signature ✅
1. Masuk ke tab **"Verify"**
2. Pastikan teks dan signature sudah terisi
3. Klik **"Verifikasi Signature"**
4. Lihat hasil:
   - ✅ **VALID** = Dokumen asli, tidak ada modifikasi
   - ❌ **TIDAK VALID** = Dokumen dimodifikasi atau MITM terdeteksi

### Step 4: Simulasi MITM 🎯
1. Buat signature dari dokumen original
2. Di tab Verify, modifikasi teksnya (ubah 1 karakter saja)
3. Klik **"Simulasi MITM"** atau verifikasi manual
4. Hasilnya akan invalid ❌
5. Ubah teks kembali ke original, hasilnya valid ✅

---

## 📂 Struktur Proyek

```
digital-signature-webApp-php/
│
├── 📄 index.php
│   ├─ HTML template aplikasi
│   ├─ CSS styling (dark theme, modern design)
│   ├─ JavaScript interaksi
│   └─ AJAX calls ke API endpoints
│
├── 🔑 generate_key.php
│   ├─ Generate RSA 2048-bit key pair
│   ├─ Export ke format PEM
│   └─ Save ke folder keys/
│
├── ✍️ sign.php
│   ├─ Terima teks dari client
│   ├─ Load private key dari file
│   ├─ Create signature dgn SHA-256
│   └─ Return Base64 encoded signature
│
├── ✅ verify.php
│   ├─ Terima teks + signature dari client
│   ├─ Load public key dari file
│   ├─ Verify signature
│   └─ Return valid/invalid + detail
│
├── 🗝️ keys/ (folder)
│   ├─ private_key.pem (rahasia!)
│   └─ public_key.pem (shareable)
│
└── 📚 Docs/
    └─ README.md (dokumentasi ini)
```

---

## 🌐 Dokumentasi API

### 1. Generate Key API

**Endpoint:** `POST /generate_key.php`

**Parameter:** (Tidak ada, auto-triggered)

**Response (Success):**
```json
{
  "success": true,
  "message": "Pasangan kunci RSA 2048-bit berhasil digenerate!",
  "private_key": "-----BEGIN RSA PRIVATE KEY-----\n...",
  "public_key": "-----BEGIN PUBLIC KEY-----\n...",
  "timestamp": "07-05-2026 14:23:45"
}
```

**Response (Error):**
```json
{
  "success": false,
  "message": "Gagal generate key pair: ..."
}
```

---

### 2. Sign API

**Endpoint:** `POST /sign.php`

**Parameters:**
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `text` | string | ✅ | Teks/dokumen yang akan ditandatangani |

**Example Request:**
```javascript
fetch('/sign.php', {
  method: 'POST',
  headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
  body: 'text=Hello%20World'
})
```

**Response (Success):**
```json
{
  "success": true,
  "message": "Signature berhasil dibuat!",
  "original_text": "Hello World",
  "signature_base64": "lKFJvKDJw9...LvNxOplQmA==",
  "algorithm": "RSA-SHA256",
  "timestamp": "07-05-2026 14:24:10"
}
```

---

### 3. Verify API

**Endpoint:** `POST /verify.php`

**Parameters:**
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `text` | string | ✅ | Teks/dokumen untuk diverifikasi |
| `signature` | string | ✅ | Signature dalam Base64 format |

**Response (VALID):**
```json
{
  "success": true,
  "valid": true,
  "status": "VALID",
  "message": "✅ Dokumen VALID! Teks asli dan tidak ada modifikasi.",
  "detail": "Signature cocok dengan teks dan public key. Dokumen dapat dipercaya.",
  "timestamp": "07-05-2026 14:25:00"
}
```

**Response (INVALID - MITM Detected):**
```json
{
  "success": true,
  "valid": false,
  "status": "TIDAK VALID",
  "message": "❌ PERINGATAN! Dokumen TIDAK VALID atau DATA DIMODIFIKASI!",
  "detail": "Signature tidak cocok dengan teks. Kemungkinan MITM atau teks telah diubah.",
  "timestamp": "07-05-2026 14:25:30"
}
```

---

## 🔐 Konsep Keamanan

### 🔑 RSA (Rivest-Shamir-Adleman)

**Enkripsi Asimetrik** yang menggunakan 2 kunci:
- **Private Key**: Digunakan untuk sign (hanya pemilik tahu)
- **Public Key**: Digunakan untuk verify (boleh dibagikan)

```
Sign: message + private_key → signature
Verify: message + signature + public_key → valid/invalid
```

### #️⃣ SHA-256 (Secure Hash Algorithm)

**Hash Function** yang membuat:
- Input apapun → 256-bit hash yang unik
- Deterministic (same input = same hash)
- One-way (tidak bisa reverse)
- Collision-resistant (sangat sulit ada hash yang sama)

```
"Hello" → a185c0e8e32af...24 (SHA-256)
"hello" → 2cf24dba5fb0...e0 (SHA-256)
(berbeda 1 karakter = hash berbeda total)
```

### 🔗 Kombinasi RSA + SHA-256

**Process:**
```
1. Hash dokumen dgn SHA-256
2. Encrypt hash dgn Private Key
3. = Digital Signature

Verify:
1. Decrypt signature dgn Public Key
2. Hash dokumen dgn SHA-256
3. Bandingkan kedua hash
4. Jika sama = VALID, jika beda = INVALID
```

### 🛡️ Keuntungan Digital Signature

| Aspek | Benefit |
|-------|---------|
| **Authentication** | Pembuktian penulis/sender |
| **Non-Repudiation** | Sender tidak bisa deny |
| **Integrity** | Deteksi modifikasi data |
| **Confidentiality** | (Optional dengan encryption) |

---

## 🎯 Demonstrasi MITM (Man-in-the-Middle)

### Skenario Serangan

```
Alice                   Attacker                    Bob
  │                         │                         │
  ├──── msg + signature ────→│                         │
  │                          │ (intercept & modify)    │
  │                          ├──── modified msg ──────→│
  │                          │                         │
```

### Contoh: MITM Attack Attempt

**Original Message:**
```
"Transfer 100 ke Alice"
Signature: ABC123...XYZ (valid untuk message ini)
```

**Attacker Modifies:**
```
"Transfer 100 ke Attacker"  ← Diubah!
Signature: ABC123...XYZ (sama, tapi tidak cocok dgn message baru)
```

**Result:**
```
Verify("Transfer 100 ke Attacker", "ABC123...XYZ")
→ ❌ TIDAK VALID - MITM DETECTED!
```

### Cara Simulasi di Aplikasi

1. **Generate key** → Copy text + signature
2. **Ubah text** (1 atau lebih karakter)
3. **Verifikasi** → Akan invalid ❌
4. **Undo ubahan** → Akan valid ✅

**Kesimpulan:** Digital signature tidak bisa di-bypass jika data dimodifikasi!

---

## 💡 Use Cases

### 1. 📜 Dokumen Digital
- Kontrak e-signature
- Sertifikat digital
- License verification

### 2. 🔐 Keamanan Data
- Software code signing
- Package authentication
- Firmware verification

### 3. 💰 Transaksi
- Banking transactions
- E-payment confirmation
- Smart contract

### 4. 📨 Komunikasi
- Email signing
- Message authentication
- Secure messaging

---

## 🚦 Status & Version

- **Current Version:** 1.0.0
- **PHP Version:** 7.4+
- **Last Updated:** May 2026
- **Status:** ✅ Production Ready

---

## 📚 Referensi & Learning Resources

### Teori Kriptografi
- [RSA Cryptosystem - Wikipedia](https://en.wikipedia.org/wiki/RSA_(cryptosystem))
- [SHA-2 Hash Algorithm](https://en.wikipedia.org/wiki/SHA-2)
- [Digital Signature Standard](https://en.wikipedia.org/wiki/Digital_Signature_Standard)

### PHP OpenSSL
- [PHP OpenSSL Functions](https://www.php.net/manual/en/ref.openssl.php)
- [openssl_sign()](https://www.php.net/manual/en/function.openssl-sign.php)
- [openssl_verify()](https://www.php.net/manual/en/function.openssl-verify.php)

### Man-in-the-Middle Attacks
- [MITM Attack Explanation](https://owasp.org/www-community/attacks/Manipulator-in-the-middle_attack)
- [Detection & Prevention](https://en.wikipedia.org/wiki/Man-in-the-middle_attack)

---

## 📝 Catatan Penting

⚠️ **Security Notes:**

1. **Private Key Safety**
   - Jangan pernah share private key
   - Backup di tempat aman
   - Gunakan permission yang tepat di folder `keys/`

2. **Production Deployment**
   - Use HTTPS/SSL only
   - Implement rate limiting
   - Add authentication & authorization
   - Monitor file access ke keys/

3. **Key Management**
   - Rotate keys secara periodic
   - Implement key versioning
   - Backup strategy yang solid

4. **Compliance**
   - Sesuaikan dengan regulasi lokal (ITE Law, dsb)
   - Implement audit logging
   - Data retention policy

---

## 📞 Support & Feedback

Untuk pertanyaan atau feedback:
- 📧 Email: [your-email]
- 🐛 Issues: Report via GitHub Issues
- 💬 Discussion: Forum atau komunitas lokal

---

## 📄 License

Proyek ini tersedia untuk keperluan **pembelajaran dan praktikum**.
Silakan use, modify, dan share dengan tetap mencantumkan attribution.

---

**Made with ❤️ for Cryptography Education**

*Last Updated: May 7, 2026*