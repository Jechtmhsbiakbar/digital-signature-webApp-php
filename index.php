<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Digital Signature Verifier</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            background: #f4f6f9;
            color: #1f2937;
            font-family: Arial, Helvetica, sans-serif;
        }

        .card {
            background: white;
            border: 1px solid #dbe2ea;
            border-radius: 14px;
            padding: 24px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04);
        }

        .title {
            font-size: 22px;
            font-weight: 700;
            color: #111827;
        }

        .subtitle {
            color: #6b7280;
            font-size: 14px;
        }

        .section-title {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 4px;
            color: #111827;
        }

        .section-desc {
            font-size: 13px;
            color: #6b7280;
        }

        .number-box {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            background: #2563eb;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            flex-shrink: 0;
        }

        .input-field {
            width: 100%;
            border: 1px solid #cbd5e1;
            background: #ffffff;
            padding: 12px 14px;
            border-radius: 10px;
            font-size: 14px;
            outline: none;
            transition: 0.2s;
        }

        .input-field:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-size: 13px;
            font-weight: 600;
            color: #374151;
        }

        .btn {
            width: 100%;
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.2s;
        }

        .btn-primary {
            background: #2563eb;
            color: white;
        }

        .btn-primary:hover {
            background: #1d4ed8;
        }

        .btn-success {
            background: #059669;
            color: white;
        }

        .btn-success:hover {
            background: #047857;
        }

        .btn-danger {
            background: #dc2626;
            color: white;
        }

        .btn-danger:hover {
            background: #b91c1c;
        }

        .btn-warning {
            background: #f59e0b;
            color: white;
        }

        .btn-warning:hover {
            background: #d97706;
        }

        .result-box {
            background: #f8fafc;
            border: 1px solid #dbe2ea;
            border-radius: 10px;
            padding: 14px;
            font-size: 13px;
            white-space: pre-wrap;
            word-break: break-word;
            color: #374151;
            max-height: 220px;
            overflow-y: auto;
            font-family: monospace;
        }

        .valid-box {
            background: #dcfce7;
            border: 1px solid #22c55e;
            color: #166534;
            padding: 14px;
            border-radius: 10px;
            font-weight: bold;
            text-align: center;
        }

        .invalid-box {
            background: #fee2e2;
            border: 1px solid #ef4444;
            color: #991b1b;
            padding: 14px;
            border-radius: 10px;
            font-weight: bold;
            text-align: center;
        }

        .info-box {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            color: #1d4ed8;
            border-radius: 10px;
            padding: 12px;
            font-size: 13px;
        }

        .spinner {
            display: inline-block;
            width: 14px;
            height: 14px;
            border: 2px solid rgba(255, 255, 255, 0.4);
            border-top-color: white;
            border-radius: 50%;
            animation: spin .7s linear infinite;
            margin-right: 6px;
            vertical-align: middle;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }
    </style>
</head>

<body class="min-h-screen py-8 px-4">

    <div class="max-w-6xl mx-auto">

        <!-- HEADER -->
        <div class="text-center mb-8">

            <h1 class="title mb-2">Digital Signature Verifier</h1>
            <p class="subtitle">
                RSA-2048 + SHA-256 · Verifikasi tanda tangan digital dan simulasi MITM
            </p>
        </div>
        <!-- IDENTITAS MAHASISWA -->
        <div class="card mb-5 py-4">

            <div class="flex items-center justify-between mb-3">
                <h2 class="text-base font-bold text-gray-800">
                    Identitas Mahasiswa
                </h2>

                <span class="text-xs text-gray-500">
                    Tugas Kriptografi
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 text-sm">

                <div>
                    <span class="text-gray-500">Nama</span>
                    <div class="font-medium">Muhammad Hasbih Akbar</div>
                </div>

                <div>
                    <span class="text-gray-500">NIM</span>
                    <div class="font-medium">231220075</div>
                </div>

                <div>
                    <span class="text-gray-500">Kelas</span>
                    <div class="font-medium">TI - 31</div>
                </div>

                <div>
                    <span class="text-gray-500">Dosen Pengampu</span>
                    <div class="font-medium">Sucipto, M.Kom</div>
                </div>

                <div>
                    <span class="text-gray-500">Mata Kuliah</span>
                    <div class="font-medium">Kriptografi</div>
                </div>

                <div>
                    <span class="text-gray-500">Prodi</span>
                    <div class="font-medium">Teknik Informatika</div>
                </div>

            </div>

        </div>
        <!-- MAIN GRID -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

            <!-- GENERATE -->
            <div class="card">

                <div class="flex items-start gap-3 mb-5">
                    <div class="number-box">1</div>

                    <div>
                        <div class="section-title">Generate RSA Key</div>
                        <div class="section-desc">
                            Membuat pasangan public key dan private key
                        </div>
                    </div>
                </div>

                <div class="info-box mb-4">
                    Public key dan private key akan dibuat otomatis dan digunakan
                    untuk proses digital signature serta verifikasi data.
                </div>

                <button id="btnGenerate" class="btn btn-primary mb-4" onclick="generateKey()">
                    Generate Key Pair
                </button>

                <div id="keyResult" class="hidden">

                    <div class="mb-4">
                        <label>Public Key</label>
                        <div class="result-box" id="publicKeyDisplay"></div>
                    </div>

                    <div>
                        <label>Status</label>
                        <div class="result-box" id="keyStatus"></div>
                    </div>

                </div>

            </div>

            <!-- SIGN -->
            <div class="card">

                <div class="flex items-start gap-3 mb-5">
                    <div class="number-box bg-green-600">2</div>

                    <div>
                        <div class="section-title">Sign Data</div>
                        <div class="section-desc">
                            Membuat signature menggunakan private key
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <label>Pesan</label>

                    <textarea id="signMessage" rows="4" class="input-field"
                        placeholder="Masukkan pesan...">Transfer ke Jokw: Rp 100.000</textarea>
                </div>

                <button class="btn btn-success mb-4" onclick="signData()">
                    Sign Data
                </button>

                <div id="signResult" class="hidden">

                    <div class="mb-4">
                        <label>Signature</label>
                        <div class="result-box" id="signatureDisplay"></div>
                    </div>

                    <div class="mb-4">
                        <button class="btn btn-warning" onclick="copyToVerify()">
                            Simulasi MITM (Jokw → Widodd)
                        </button>
                    </div>

                    <div>
                        <label>Info</label>
                        <div class="result-box" id="signInfo"></div>
                    </div>

                </div>

            </div>

            <!-- VERIFY -->
            <div class="card">

                <div class="flex items-start gap-3 mb-5">
                    <div class="number-box bg-red-600">3</div>

                    <div>
                        <div class="section-title">Verify Signature</div>
                        <div class="section-desc">
                            Verifikasi integritas data dan signature
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <label>Pesan yang Diverifikasi</label>

                    <textarea id="verifyMessage" rows="4" class="input-field"
                        placeholder="Masukkan pesan..."></textarea>
                </div>

                <div class="mb-4">
                    <label>Signature</label>

                    <textarea id="verifySignature" rows="4" class="input-field"
                        placeholder="Tempel signature..."></textarea>
                </div>

                <button class="btn btn-danger mb-4" onclick="verifyData()">
                    Verify Signature
                </button>

                <div id="verifyResult" class="hidden">

                    <div id="verifyBadge" class="mb-4"></div>

                    <div>
                        <label>Detail</label>
                        <div class="result-box" id="verifyDetail"></div>
                    </div>

                </div>

            </div>

        </div>

        <!-- EXPLANATION -->
        <div class="card mt-6">

            <h2 class="section-title mb-4">
                Simulasi Serangan MITM
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 text-sm">

                <div class="info-box">
                    <strong>Step 1</strong>
                    <br><br>
                    Generate RSA key pair
                </div>

                <div class="info-box">
                    <strong>Step 2</strong>
                    <br><br>
                    Sign pesan:
                    <br>
                    "Transfer ke Jokw"
                </div>

                <div class="info-box">
                    <strong>Step 3</strong>
                    <br><br>
                    Jalankan simulasi MITM untuk mengubah pesan menjadi "Widodd"
                </div>

                <div class="info-box">
                    <strong>Step 4</strong>
                    <br><br>
                    Verify akan menghasilkan status TIDAK VALID
                </div>

            </div>

        </div>

        <div class="text-center text-gray-500 text-xs mt-6">
            Digital Signature Verifier · PHP OpenSSL
        </div>

    </div>

    <script>
        let lastSignature = '';
        let lastMessage = '';

        function setLoading(button, loading, text) {
            if (loading) {
                button.disabled = true;
                button.innerHTML = `<span class="spinner"></span> Processing...`;
            } else {
                button.disabled = false;
                button.innerHTML = text;
            }
        }

        async function generateKey() {

            const btn = document.getElementById('btnGenerate');

            setLoading(btn, true);

            try {

                const res = await fetch('generate_key.php', {
                    method: 'POST'
                });

                const data = await res.json();

                document.getElementById('keyResult').classList.remove('hidden');

                if (data.success) {

                    document.getElementById('publicKeyDisplay').textContent = data.public_key;

                    document.getElementById('keyStatus').textContent =
                        '✅ Key pair berhasil dibuat\n\n' +
                        '🔐 Algoritma: RSA-2048\n' +
                        '📁 Public Key: keys/public_key.pem\n' +
                        '📁 Private Key: keys/private_key.pem\n' +
                        '📅 ' + new Date().toLocaleString('id-ID');

                } else {

                    document.getElementById('publicKeyDisplay').textContent = 'Error';

                    document.getElementById('keyStatus').textContent = data.error;
                }

            } catch (e) {

                alert('Gagal menghubungi server: ' + e.message);

            }

            setLoading(btn, false, 'Generate Key Pair');
        }

        async function signData() {

            const message = document.getElementById('signMessage').value.trim();

            if (!message) {
                alert('Masukkan pesan terlebih dahulu!');
                return;
            }

            const btn = document.querySelector('[onclick="signData()"]');

            setLoading(btn, true);

            try {

                const fd = new FormData();

                fd.append('message', message);

                const res = await fetch('sign.php', {
                    method: 'POST',
                    body: fd
                });

                const data = await res.json();

                document.getElementById('signResult').classList.remove('hidden');

                if (data.success) {

                    lastSignature = data.signature;
                    lastMessage = message;

                    document.getElementById('signatureDisplay').textContent = data.signature;

                    // AUTO COPY SIGNATURE
                    document.getElementById('verifySignature').value = data.signature;

                    document.getElementById('signInfo').textContent =
                        '✅ Signature berhasil dibuat\n\n' +
                        '📝 Pesan: ' + message + '\n' +
                        '🔑 Algoritma: RSA-SHA256\n' +
                        '📏 Panjang Signature: ' + data.signature.length + ' karakter\n' +
                        '📅 ' + new Date().toLocaleString('id-ID');

                } else {

                    document.getElementById('signatureDisplay').textContent = 'Error';

                    document.getElementById('signInfo').textContent = data.error;
                }

            } catch (e) {

                alert('Gagal menghubungi server: ' + e.message);

            }

            setLoading(btn, false, 'Sign Data');
        }

        function copyToVerify() {

            if (!lastSignature) {
                alert('Sign data terlebih dahulu!');
                return;
            }

            const tamperedMessage = lastMessage.replace(/Jokw/g, 'Widodd');

            document.getElementById('verifyMessage').value = tamperedMessage;

            document.getElementById('verifySignature').value = lastSignature;

            document.getElementById('verifyMessage').scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
        }

        async function verifyData() {

            const message = document.getElementById('verifyMessage').value.trim();

            const signature = document.getElementById('verifySignature').value.trim();

            if (!message || !signature) {

                alert('Masukkan pesan dan signature!');

                return;
            }

            const btn = document.querySelector('[onclick="verifyData()"]');

            setLoading(btn, true);

            try {

                const fd = new FormData();

                fd.append('message', message);

                fd.append('signature', signature);

                const res = await fetch('verify.php', {
                    method: 'POST',
                    body: fd
                });

                const data = await res.json();

                document.getElementById('verifyResult').classList.remove('hidden');

                const badge = document.getElementById('verifyBadge');

                const detail = document.getElementById('verifyDetail');

                if (data.valid) {

                    badge.innerHTML = `
                        <div class="valid-box">
                            ✅ VALID - Pesan Asli dan Tidak Dimodifikasi
                        </div>
                    `;

                    detail.textContent =
                        '🟢 STATUS: VALID\n\n' +
                        '📝 Pesan: ' + message + '\n' +
                        '📋 Signature cocok dengan public key\n' +
                        '🔒 Integritas data aman\n' +
                        '📅 ' + new Date().toLocaleString('id-ID');

                } else {

                    badge.innerHTML = `
                        <div class="invalid-box">
                            ❌ TIDAK VALID - Pesan Dimodifikasi / Signature Palsu
                        </div>
                    `;

                    detail.textContent =
                        '🔴 STATUS: TIDAK VALID\n\n' +
                        '📝 Pesan: ' + message + '\n' +
                        '⚠️ Signature tidak cocok\n' +
                        '🚨 Kemungkinan terjadi MITM\n' +
                        '❌ Data tidak dapat dipercaya\n' +
                        '📅 ' + new Date().toLocaleString('id-ID');
                }

            } catch (e) {

                alert('Gagal menghubungi server: ' + e.message);

            }

            setLoading(btn, false, 'Verify Signature');
        }
    </script>

</body>

</html>