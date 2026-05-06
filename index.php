<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Digital Signature Verifier</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background: #0f172a; }
        .card { background: #1e293b; border: 1px solid #334155; }
        .input-field {
            background: #0f172a;
            border: 1px solid #475569;
            color: #e2e8f0;
            width: 100%;
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 14px;
            outline: none;
            transition: border 0.2s;
        }
        .input-field:focus { border-color: #6366f1; }
        .btn {
            padding: 10px 24px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s;
            border: none;
        }
        .btn-indigo { background: #6366f1; color: white; }
        .btn-indigo:hover { background: #4f46e5; }
        .btn-emerald { background: #10b981; color: white; }
        .btn-emerald:hover { background: #059669; }
        .btn-rose { background: #f43f5e; color: white; }
        .btn-rose:hover { background: #e11d48; }
        .result-box {
            background: #0f172a;
            border: 1px solid #334155;
            border-radius: 8px;
            padding: 14px;
            font-family: monospace;
            font-size: 12px;
            color: #94a3b8;
            word-break: break-all;
            white-space: pre-wrap;
            max-height: 180px;
            overflow-y: auto;
        }
        .badge-valid {
            background: #052e16;
            border: 1px solid #16a34a;
            color: #4ade80;
            padding: 12px 20px;
            border-radius: 8px;
            font-size: 18px;
            font-weight: 700;
            text-align: center;
        }
        .badge-invalid {
            background: #2d0a13;
            border: 1px solid #dc2626;
            color: #f87171;
            padding: 12px 20px;
            border-radius: 8px;
            font-size: 18px;
            font-weight: 700;
            text-align: center;
        }
        .section-title {
            font-size: 18px;
            font-weight: 700;
            color: #e2e8f0;
            margin-bottom: 4px;
        }
        .section-num {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 28px; height: 28px;
            border-radius: 50%;
            font-weight: 700;
            font-size: 14px;
            margin-right: 8px;
        }
        label { color: #94a3b8; font-size: 13px; font-weight: 500; }
        .spinner {
            display: inline-block;
            width: 16px; height: 16px;
            border: 2px solid rgba(255,255,255,0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.7s linear infinite;
            margin-right: 6px;
            vertical-align: middle;
        }
        @keyframes spin { to { transform: rotate(360deg); } }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #0f172a; }
        ::-webkit-scrollbar-thumb { background: #475569; border-radius: 3px; }
    </style>
</head>
<body class="min-h-screen py-10 px-4">

<div class="max-w-5xl mx-auto">
    <!-- Header -->
    <div class="text-center mb-10">
        <div class="inline-flex items-center gap-3 mb-3">
            <svg width="36" height="36" viewBox="0 0 36 36" fill="none">
                <rect width="36" height="36" rx="10" fill="#6366f1"/>
                <path d="M18 8L10 12V20C10 24.4 13.4 28.5 18 29.5C22.6 28.5 26 24.4 26 20V12L18 8Z" fill="white" fill-opacity="0.9"/>
                <path d="M15 18L17 20L21 16" stroke="#6366f1" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <h1 class="text-3xl font-bold text-white">Digital Signature Verifier</h1>
        </div>
        <p class="text-slate-400 text-sm">RSA-2048 + SHA-256 · Demonstrasi tanda tangan digital & serangan MITM</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- ===== SECTION 1: GENERATE KEY ===== -->
        <div class="card rounded-2xl p-6">
            <div class="flex items-center mb-5">
                <span class="section-num bg-indigo-500 text-white">1</span>
                <div>
                    <div class="section-title">Generate RSA Key</div>
                    <div class="text-slate-500 text-xs">Buat pasangan kunci RSA 2048-bit</div>
                </div>
            </div>

            <div class="mb-4 p-3 rounded-lg" style="background:#1a2744; border:1px solid #2d3f6e;">
                <div class="flex items-start gap-2">
                    <svg class="mt-0.5 flex-shrink-0" width="14" height="14" viewBox="0 0 14 14" fill="none">
                        <circle cx="7" cy="7" r="6" stroke="#6366f1" stroke-width="1.5"/>
                        <path d="M7 6v4M7 4.5v.5" stroke="#6366f1" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                    <p class="text-slate-400 text-xs leading-relaxed">
                        Kunci akan disimpan ke <code class="text-indigo-400">keys/private_key.pem</code> dan <code class="text-indigo-400">keys/public_key.pem</code>
                    </p>
                </div>
            </div>

            <button class="btn btn-indigo w-full mb-4" id="btnGenerate" onclick="generateKey()">
                🔑 Generate Key Pair
            </button>

            <div id="keyResult" class="hidden">
                <div class="mb-3">
                    <label class="block mb-1">Public Key</label>
                    <div class="result-box" id="publicKeyDisplay"></div>
                </div>
                <div>
                    <label class="block mb-1">Status</label>
                    <div class="result-box" id="keyStatus"></div>
                </div>
            </div>
        </div>

        <!-- ===== SECTION 2: SIGN DATA ===== -->
        <div class="card rounded-2xl p-6">
            <div class="flex items-center mb-5">
                <span class="section-num bg-emerald-500 text-white">2</span>
                <div>
                    <div class="section-title">Sign Data</div>
                    <div class="text-slate-500 text-xs">Tanda tangani pesan dengan private key</div>
                </div>
            </div>

            <div class="mb-4">
                <label class="block mb-1">Pesan yang akan ditandatangani</label>
                <textarea id="signMessage" class="input-field" rows="3"
                    placeholder="Contoh: Transfer ke Budi: Rp 100.000">Transfer ke Budi: Rp 100.000</textarea>
            </div>

            <button class="btn btn-emerald w-full mb-4" onclick="signData()">
                ✍️ Sign dengan Private Key
            </button>

            <div id="signResult" class="hidden">
                <div class="mb-3">
                    <label class="block mb-1">Signature (Base64)</label>
                    <div class="result-box" id="signatureDisplay"></div>
                </div>
                <div class="mb-3">
                    <label class="block mb-1">
                        <span class="text-amber-400">⚡ Simulasi MITM</span>
                        <span class="text-slate-500 ml-1">– Salin ke Verify dengan pesan diubah</span>
                    </label>
                    <button class="btn w-full text-xs" style="background:#1e3a2b; border:1px solid #10b981; color:#4ade80;"
                        onclick="copyToVerify()">
                        📋 Copy Signature ke Verify (ubah "Budi" → "Andi")
                    </button>
                </div>
                <div>
                    <label class="block mb-1">Info</label>
                    <div class="result-box" id="signInfo"></div>
                </div>
            </div>
        </div>

        <!-- ===== SECTION 3: VERIFY ===== -->
        <div class="card rounded-2xl p-6">
            <div class="flex items-center mb-5">
                <span class="section-num bg-rose-500 text-white">3</span>
                <div>
                    <div class="section-title">Verify Signature</div>
                    <div class="text-slate-500 text-xs">Verifikasi keaslian pesan & tanda tangan</div>
                </div>
            </div>

            <div class="mb-3">
                <label class="block mb-1">Pesan yang diverifikasi</label>
                <textarea id="verifyMessage" class="input-field" rows="3"
                    placeholder="Masukkan pesan..."></textarea>
            </div>

            <div class="mb-4">
                <label class="block mb-1">Signature (Base64)</label>
                <textarea id="verifySignature" class="input-field" rows="3"
                    placeholder="Tempel signature di sini..."></textarea>
            </div>

            <button class="btn btn-rose w-full mb-4" onclick="verifyData()">
                🔍 Verify Signature
            </button>

            <div id="verifyResult" class="hidden">
                <div id="verifyBadge" class="mb-3"></div>
                <div>
                    <label class="block mb-1">Detail</label>
                    <div class="result-box" id="verifyDetail"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- MITM Explanation -->
    <div class="mt-6 card rounded-2xl p-5">
        <h3 class="font-bold text-white mb-2 flex items-center gap-2">
            <span>⚠️</span> Cara Simulasi Serangan Man-in-the-Middle (MITM)
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-3 text-sm text-slate-400">
            <div class="p-3 rounded-lg" style="background:#0f172a; border:1px solid #334155;">
                <div class="text-indigo-400 font-semibold mb-1">Step 1</div>
                Generate RSA Key di kolom kiri
            </div>
            <div class="p-3 rounded-lg" style="background:#0f172a; border:1px solid #334155;">
                <div class="text-emerald-400 font-semibold mb-1">Step 2</div>
                Sign pesan "Transfer ke <strong class="text-white">Budi</strong>: Rp 100.000"
            </div>
            <div class="p-3 rounded-lg" style="background:#0f172a; border:1px solid #334155;">
                <div class="text-amber-400 font-semibold mb-1">Step 3</div>
                Klik tombol <em>Copy Signature ke Verify</em> – pesan otomatis diubah ke "<strong class="text-white">Andi</strong>"
            </div>
            <div class="p-3 rounded-lg" style="background:#0f172a; border:1px solid #334155;">
                <div class="text-rose-400 font-semibold mb-1">Step 4</div>
                Klik Verify → akan terdeteksi <strong class="text-red-400">TIDAK VALID</strong>
            </div>
        </div>
    </div>

    <p class="text-center text-slate-600 text-xs mt-6">Digital Signature Verifier · RSA-2048 + SHA-256 · PHP OpenSSL</p>
</div>

<script>
let lastSignature = '';
let lastMessage   = '';

function setLoading(btnId, loading, originalText) {
    const btn = document.getElementById(btnId);
    if (!btn) return;
    if (loading) {
        btn.disabled = true;
        btn.innerHTML = `<span class="spinner"></span> Memproses...`;
    } else {
        btn.disabled = false;
        btn.innerHTML = originalText;
    }
}

async function generateKey() {
    const btn = document.getElementById('btnGenerate');
    btn.disabled = true;
    btn.innerHTML = `<span class="spinner"></span> Generating...`;

    try {
        const res  = await fetch('generate_key.php', { method: 'POST' });
        const data = await res.json();

        document.getElementById('keyResult').classList.remove('hidden');
        if (data.success) {
            document.getElementById('publicKeyDisplay').textContent = data.public_key;
            document.getElementById('keyStatus').textContent =
                '✅ Key pair berhasil dibuat!\n' +
                '📁 Private key: keys/private_key.pem\n' +
                '📁 Public key:  keys/public_key.pem\n' +
                '🔐 Algoritma: RSA-2048\n' +
                '📅 ' + new Date().toLocaleString('id-ID');
        } else {
            document.getElementById('publicKeyDisplay').textContent = '❌ Error: ' + data.error;
            document.getElementById('keyStatus').textContent = data.error;
        }
    } catch (e) {
        alert('Gagal menghubungi server: ' + e.message);
    }

    btn.disabled = false;
    btn.innerHTML = '🔑 Generate Key Pair';
}

async function signData() {
    const message = document.getElementById('signMessage').value.trim();
    if (!message) { alert('Masukkan pesan terlebih dahulu!'); return; }

    const btn = document.querySelector('#signResult').previousElementSibling;
    const origText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = `<span class="spinner"></span> Signing...`;

    try {
        const fd = new FormData();
        fd.append('message', message);

        const res  = await fetch('sign.php', { method: 'POST', body: fd });
        const data = await res.json();

        document.getElementById('signResult').classList.remove('hidden');
        if (data.success) {
            lastSignature = data.signature;
            lastMessage   = message;
            document.getElementById('signatureDisplay').textContent = data.signature;
            document.getElementById('signInfo').textContent =
                '✅ Signature berhasil dibuat!\n' +
                '📝 Pesan: ' + message + '\n' +
                '🔑 Algoritma: RSA-SHA256\n' +
                '📏 Panjang signature: ' + data.signature.length + ' karakter (base64)\n' +
                '📅 ' + new Date().toLocaleString('id-ID');
        } else {
            document.getElementById('signatureDisplay').textContent = '❌ ' + data.error;
            document.getElementById('signInfo').textContent = data.error;
        }
    } catch (e) {
        alert('Gagal menghubungi server: ' + e.message);
    }

    btn.disabled = false;
    btn.innerHTML = origText;
}

function copyToVerify() {
    if (!lastSignature) {
        alert('Sign data terlebih dahulu!');
        return;
    }
    // MITM: ubah "Budi" → "Andi"
    const tamperedMessage = lastMessage.replace(/Budi/g, 'Andi');
    document.getElementById('verifyMessage').value   = tamperedMessage;
    document.getElementById('verifySignature').value = lastSignature;

    // Scroll ke section verify
    document.getElementById('verifyMessage').scrollIntoView({ behavior: 'smooth', block: 'center' });

    // Flash highlight
    const vm = document.getElementById('verifyMessage');
    vm.style.border = '1px solid #f59e0b';
    setTimeout(() => { vm.style.border = '1px solid #475569'; }, 1500);
}

async function verifyData() {
    const message   = document.getElementById('verifyMessage').value.trim();
    const signature = document.getElementById('verifySignature').value.trim();

    if (!message || !signature) {
        alert('Masukkan pesan dan signature!');
        return;
    }

    const btn = document.querySelector('[onclick="verifyData()"]');
    const origText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = `<span class="spinner"></span> Verifying...`;

    try {
        const fd = new FormData();
        fd.append('message',   message);
        fd.append('signature', signature);

        const res  = await fetch('verify.php', { method: 'POST', body: fd });
        const data = await res.json();

        document.getElementById('verifyResult').classList.remove('hidden');

        const badge  = document.getElementById('verifyBadge');
        const detail = document.getElementById('verifyDetail');

        if (data.valid) {
            badge.innerHTML = `<div class="badge-valid">✅ VALID – Pesan Asli & Tidak Dimodifikasi</div>`;
            detail.textContent =
                '🟢 STATUS: VALID\n' +
                '📝 Pesan: ' + message + '\n' +
                '🔑 Algoritma: RSA-SHA256\n' +
                '📋 Signature cocok dengan public key\n' +
                '✅ Integritas data terjaga\n' +
                '📅 ' + new Date().toLocaleString('id-ID');
        } else {
            badge.innerHTML = `<div class="badge-invalid">❌ TIDAK VALID – Pesan Dimodifikasi / Signature Palsu</div>`;
            detail.textContent =
                '🔴 STATUS: TIDAK VALID\n' +
                '📝 Pesan: ' + message + '\n' +
                '⚠️  Signature TIDAK cocok!\n' +
                '🚨 Kemungkinan: pesan dimodifikasi (MITM) atau signature dipalsukan\n' +
                '❌ Data tidak dapat dipercaya\n' +
                '📅 ' + new Date().toLocaleString('id-ID');
        }
    } catch (e) {
        alert('Gagal menghubungi server: ' + e.message);
    }

    btn.disabled = false;
    btn.innerHTML = origText;
}
</script>
</body>
</html>