<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Verifikator Dokumen — Digital Signature</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;600;700&family=Syne:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        /* ===== CSS VARIABLES ===== */
        :root {
            --bg-primary:    #0a0e1a;
            --bg-card:       #0f1628;
            --bg-input:      #080c18;
            --border-dim:    #1e2a45;
            --border-bright: #2a3f6b;
            --accent-blue:   #3b82f6;
            --accent-cyan:   #06b6d4;
            --accent-green:  #10b981;
            --accent-red:    #ef4444;
            --accent-yellow: #f59e0b;
            --text-primary:  #e2e8f0;
            --text-muted:    #64748b;
            --text-bright:   #f8fafc;
            --glow-blue:     rgba(59, 130, 246, 0.15);
            --glow-cyan:     rgba(6, 182, 212, 0.15);
            --glow-green:    rgba(16, 185, 129, 0.2);
            --glow-red:      rgba(239, 68, 68, 0.2);
        }

        /* ===== RESET & BASE ===== */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Syne', sans-serif;
            background-color: var(--bg-primary);
            color: var(--text-primary);
            min-height: 100vh;
            background-image:
                radial-gradient(ellipse 80% 50% at 50% -20%, rgba(59,130,246,0.08) 0%, transparent 60%),
                radial-gradient(ellipse 60% 40% at 80% 80%, rgba(6,182,212,0.05) 0%, transparent 50%);
        }

        /* ===== GRID BACKGROUND ===== */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image:
                linear-gradient(rgba(30,42,69,0.4) 1px, transparent 1px),
                linear-gradient(90deg, rgba(30,42,69,0.4) 1px, transparent 1px);
            background-size: 40px 40px;
            pointer-events: none;
            z-index: 0;
        }

        /* ===== LAYOUT ===== */
        .wrapper {
            position: relative;
            z-index: 1;
            max-width: 900px;
            margin: 0 auto;
            padding: 40px 20px 80px;
        }

        /* ===== HEADER ===== */
        .header {
            text-align: center;
            margin-bottom: 48px;
        }

        .header-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(59,130,246,0.1);
            border: 1px solid rgba(59,130,246,0.3);
            border-radius: 100px;
            padding: 6px 16px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 11px;
            color: var(--accent-cyan);
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin-bottom: 20px;
        }

        .header-badge::before {
            content: '';
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--accent-cyan);
            box-shadow: 0 0 8px var(--accent-cyan);
            animation: pulse-dot 2s ease-in-out infinite;
        }

        @keyframes pulse-dot {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.4; transform: scale(0.7); }
        }

        h1 {
            font-size: clamp(28px, 5vw, 42px);
            font-weight: 800;
            line-height: 1.1;
            background: linear-gradient(135deg, #f8fafc 0%, #93c5fd 50%, #06b6d4 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 12px;
        }

        .header-sub {
            color: var(--text-muted);
            font-size: 15px;
            font-family: 'JetBrains Mono', monospace;
        }

        /* ===== STEP INDICATOR ===== */
        .steps-indicator {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0;
            margin-bottom: 40px;
        }

        .step-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
        }

        .step-dot {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            border: 2px solid var(--border-dim);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 13px;
            color: var(--text-muted);
            background: var(--bg-card);
            transition: all 0.3s ease;
        }

        .step-label {
            font-size: 11px;
            color: var(--text-muted);
            font-family: 'JetBrains Mono', monospace;
            letter-spacing: 0.5px;
        }

        .step-line {
            flex: 1;
            height: 2px;
            background: var(--border-dim);
            max-width: 80px;
            margin-top: -22px;
        }

        /* ===== CARDS ===== */
        .card {
            background: var(--bg-card);
            border: 1px solid var(--border-dim);
            border-radius: 16px;
            padding: 32px;
            margin-bottom: 24px;
            position: relative;
            overflow: hidden;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--border-bright), transparent);
        }

        .card:hover {
            border-color: var(--border-bright);
        }

        .card-header {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 24px;
        }

        .card-icon {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }

        .card-icon.blue  { background: rgba(59,130,246,0.12); border: 1px solid rgba(59,130,246,0.25); }
        .card-icon.cyan  { background: rgba(6,182,212,0.12);  border: 1px solid rgba(6,182,212,0.25);  }
        .card-icon.green { background: rgba(16,185,129,0.12); border: 1px solid rgba(16,185,129,0.25); }
        .card-icon.red   { background: rgba(239,68,68,0.12);  border: 1px solid rgba(239,68,68,0.25);  }

        .card-title {
            flex: 1;
        }

        .card-title h2 {
            font-size: 18px;
            font-weight: 700;
            color: var(--text-bright);
        }

        .card-title p {
            font-size: 13px;
            color: var(--text-muted);
            font-family: 'JetBrains Mono', monospace;
            margin-top: 2px;
        }

        .card-step-badge {
            font-family: 'JetBrains Mono', monospace;
            font-size: 10px;
            padding: 4px 10px;
            border-radius: 100px;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .badge-blue  { background: rgba(59,130,246,0.1);  color: var(--accent-blue);  border: 1px solid rgba(59,130,246,0.3);  }
        .badge-cyan  { background: rgba(6,182,212,0.1);   color: var(--accent-cyan);  border: 1px solid rgba(6,182,212,0.3);   }
        .badge-green { background: rgba(16,185,129,0.1);  color: var(--accent-green); border: 1px solid rgba(16,185,129,0.3); }
        .badge-red   { background: rgba(239,68,68,0.1);   color: var(--accent-red);   border: 1px solid rgba(239,68,68,0.3);  }

        /* ===== FORM ELEMENTS ===== */
        label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: var(--text-muted);
            font-family: 'JetBrains Mono', monospace;
            margin-bottom: 8px;
        }

        textarea, input[type="text"] {
            width: 100%;
            background: var(--bg-input);
            border: 1px solid var(--border-dim);
            border-radius: 10px;
            padding: 14px 16px;
            color: var(--text-primary);
            font-family: 'JetBrains Mono', monospace;
            font-size: 13px;
            line-height: 1.6;
            resize: vertical;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
            outline: none;
        }

        textarea:focus, input[type="text"]:focus {
            border-color: var(--accent-blue);
            box-shadow: 0 0 0 3px rgba(59,130,246,0.1);
        }

        textarea { min-height: 90px; }

        .form-group { margin-bottom: 20px; }

        /* ===== BUTTONS ===== */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            border-radius: 10px;
            font-family: 'Syne', sans-serif;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            border: none;
            transition: all 0.2s ease;
            text-decoration: none;
            letter-spacing: 0.3px;
        }

        .btn:active { transform: scale(0.97); }

        .btn-blue {
            background: linear-gradient(135deg, #2563eb, #3b82f6);
            color: white;
            box-shadow: 0 4px 14px rgba(59,130,246,0.3);
        }
        .btn-blue:hover { background: linear-gradient(135deg, #1d4ed8, #2563eb); box-shadow: 0 6px 20px rgba(59,130,246,0.45); transform: translateY(-1px); }

        .btn-cyan {
            background: linear-gradient(135deg, #0891b2, #06b6d4);
            color: white;
            box-shadow: 0 4px 14px rgba(6,182,212,0.3);
        }
        .btn-cyan:hover { background: linear-gradient(135deg, #0e7490, #0891b2); box-shadow: 0 6px 20px rgba(6,182,212,0.45); transform: translateY(-1px); }

        .btn-green {
            background: linear-gradient(135deg, #059669, #10b981);
            color: white;
            box-shadow: 0 4px 14px rgba(16,185,129,0.3);
        }
        .btn-green:hover { background: linear-gradient(135deg, #047857, #059669); box-shadow: 0 6px 20px rgba(16,185,129,0.45); transform: translateY(-1px); }

        .btn-red {
            background: linear-gradient(135deg, #dc2626, #ef4444);
            color: white;
            box-shadow: 0 4px 14px rgba(239,68,68,0.3);
        }
        .btn-red:hover { background: linear-gradient(135deg, #b91c1c, #dc2626); box-shadow: 0 6px 20px rgba(239,68,68,0.45); transform: translateY(-1px); }

        .btn-outline {
            background: transparent;
            color: var(--text-muted);
            border: 1px solid var(--border-dim);
        }
        .btn-outline:hover { border-color: var(--border-bright); color: var(--text-primary); }

        .btn-sm { padding: 8px 14px; font-size: 12px; }

        /* ===== OUTPUT PANELS ===== */
        .output-panel {
            margin-top: 20px;
            border-radius: 10px;
            overflow: hidden;
            border: 1px solid var(--border-dim);
            display: none;
        }

        .output-panel.show { display: block; animation: fadeIn 0.3s ease; }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(8px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .output-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 16px;
            background: rgba(255,255,255,0.03);
            border-bottom: 1px solid var(--border-dim);
        }

        .output-bar-label {
            font-family: 'JetBrains Mono', monospace;
            font-size: 10px;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: var(--text-muted);
        }

        .output-body {
            padding: 16px;
            background: var(--bg-input);
        }

        .output-text {
            font-family: 'JetBrains Mono', monospace;
            font-size: 12px;
            line-height: 1.7;
            word-break: break-all;
            color: var(--text-primary);
            white-space: pre-wrap;
        }

        /* ===== RESULT STATUS ===== */
        .result-valid {
            background: rgba(16,185,129,0.08);
            border: 1px solid rgba(16,185,129,0.3);
            border-radius: 12px;
            padding: 20px 24px;
            margin-top: 20px;
            display: none;
        }

        .result-invalid {
            background: rgba(239,68,68,0.08);
            border: 1px solid rgba(239,68,68,0.3);
            border-radius: 12px;
            padding: 20px 24px;
            margin-top: 20px;
            display: none;
        }

        .result-valid.show, .result-invalid.show { display: block; animation: fadeIn 0.3s ease; }

        .result-status {
            font-size: 22px;
            font-weight: 800;
            margin-bottom: 6px;
        }

        .result-detail {
            font-size: 13px;
            font-family: 'JetBrains Mono', monospace;
            line-height: 1.6;
        }

        .result-valid .result-status  { color: var(--accent-green); }
        .result-invalid .result-status { color: var(--accent-red); }
        .result-valid .result-detail  { color: rgba(16,185,129,0.85); }
        .result-invalid .result-detail { color: rgba(239,68,68,0.85); }

        /* ===== KEY DISPLAY ===== */
        .key-display {
            margin-top: 20px;
            display: none;
        }

        .key-display.show { display: block; animation: fadeIn 0.3s ease; }

        .key-tabs {
            display: flex;
            gap: 2px;
            margin-bottom: 2px;
            background: var(--bg-input);
            border-radius: 10px 10px 0 0;
            padding: 4px;
            border: 1px solid var(--border-dim);
            border-bottom: none;
        }

        .key-tab {
            flex: 1;
            padding: 8px 12px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 11px;
            border: none;
            border-radius: 7px;
            cursor: pointer;
            transition: all 0.2s;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            background: transparent;
            color: var(--text-muted);
        }

        .key-tab.active {
            background: var(--bg-card);
            color: var(--text-primary);
        }

        .key-content {
            background: var(--bg-input);
            border: 1px solid var(--border-dim);
            border-radius: 0 0 10px 10px;
            padding: 16px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 11px;
            line-height: 1.7;
            word-break: break-all;
            color: #94a3b8;
            max-height: 160px;
            overflow-y: auto;
            display: none;
        }

        .key-content.show { display: block; }

        /* ===== SUCCESS MESSAGE ===== */
        .success-msg {
            display: flex;
            align-items: center;
            gap: 10px;
            background: rgba(16,185,129,0.08);
            border: 1px solid rgba(16,185,129,0.25);
            border-radius: 8px;
            padding: 12px 16px;
            margin-bottom: 16px;
            font-size: 13px;
            color: var(--accent-green);
            font-family: 'JetBrains Mono', monospace;
        }

        /* ===== LOADING SPINNER ===== */
        .spinner {
            width: 16px; height: 16px;
            border: 2px solid rgba(255,255,255,0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
            display: none;
        }

        @keyframes spin { to { transform: rotate(360deg); } }

        /* ===== MITM SECTION ===== */
        .mitm-section {
            background: rgba(245,158,11,0.05);
            border: 1px solid rgba(245,158,11,0.2);
            border-radius: 16px;
            padding: 24px 32px;
            margin-bottom: 24px;
        }

        .mitm-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
        }

        .mitm-title {
            font-size: 16px;
            font-weight: 700;
            color: var(--accent-yellow);
        }

        .mitm-subtitle {
            font-size: 12px;
            color: rgba(245,158,11,0.7);
            font-family: 'JetBrains Mono', monospace;
        }

        .mitm-flow {
            display: grid;
            grid-template-columns: 1fr auto 1fr;
            gap: 12px;
            align-items: center;
            margin: 20px 0;
        }

        .mitm-box {
            background: var(--bg-input);
            border-radius: 10px;
            padding: 14px 16px;
            border: 1px solid var(--border-dim);
        }

        .mitm-box-label {
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: var(--text-muted);
            font-family: 'JetBrains Mono', monospace;
            margin-bottom: 6px;
        }

        .mitm-box-text {
            font-family: 'JetBrains Mono', monospace;
            font-size: 13px;
        }

        .mitm-box-text.original { color: var(--accent-green); }
        .mitm-box-text.modified { color: var(--accent-red); }

        .mitm-arrow {
            text-align: center;
            font-size: 22px;
        }

        .mitm-steps {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .mitm-steps li {
            display: flex;
            gap: 12px;
            font-size: 13px;
            font-family: 'JetBrains Mono', monospace;
            color: var(--text-muted);
            align-items: flex-start;
        }

        .mitm-steps li .num {
            background: rgba(245,158,11,0.15);
            color: var(--accent-yellow);
            border-radius: 50%;
            width: 22px;
            height: 22px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 700;
        }

        /* ===== COPY BUTTON ===== */
        .btn-copy {
            background: transparent;
            border: 1px solid var(--border-dim);
            border-radius: 6px;
            padding: 4px 10px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 10px;
            color: var(--text-muted);
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-copy:hover {
            border-color: var(--accent-blue);
            color: var(--accent-blue);
        }

        /* ===== DIVIDER ===== */
        .divider {
            display: flex;
            align-items: center;
            gap: 16px;
            margin: 16px 0;
            color: var(--text-muted);
            font-size: 11px;
            font-family: 'JetBrains Mono', monospace;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .divider::before, .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border-dim);
        }

        /* ===== QUICK FILL ===== */
        .quick-fill {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-bottom: 10px;
        }

        .chip {
            background: rgba(59,130,246,0.08);
            border: 1px solid rgba(59,130,246,0.2);
            border-radius: 100px;
            padding: 4px 12px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 11px;
            color: var(--accent-blue);
            cursor: pointer;
            transition: all 0.2s;
        }

        .chip:hover {
            background: rgba(59,130,246,0.15);
            border-color: rgba(59,130,246,0.4);
        }

        /* ===== ROW ACTIONS ===== */
        .row-actions {
            display: flex;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap;
        }

        /* ===== INFO BOX ===== */
        .info-box {
            background: rgba(59,130,246,0.06);
            border: 1px solid rgba(59,130,246,0.15);
            border-radius: 10px;
            padding: 14px 18px;
            font-size: 12px;
            font-family: 'JetBrains Mono', monospace;
            color: rgba(147,197,253,0.8);
            line-height: 1.7;
            margin-top: 14px;
        }

        /* ===== FOOTER ===== */
        .footer {
            text-align: center;
            padding-top: 40px;
            border-top: 1px solid var(--border-dim);
            color: var(--text-muted);
            font-size: 12px;
            font-family: 'JetBrains Mono', monospace;
            line-height: 2;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 640px) {
            .card { padding: 20px; }
            .mitm-flow { grid-template-columns: 1fr; }
            .mitm-arrow { transform: rotate(90deg); }
            .wrapper { padding: 20px 16px 60px; }
        }

        /* ===== SCROLLBAR ===== */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: var(--bg-input); }
        ::-webkit-scrollbar-thumb { background: var(--border-bright); border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--accent-blue); }
    </style>
</head>
<body>

<div class="wrapper">

    <!-- ===== HEADER ===== -->
    <div class="header">
        <div class="header-badge">RSA · SHA-256 · Digital Signature</div>
        <h1>Web Verifikator Dokumen</h1>
        <p class="header-sub">// Praktikum Kriptografi — Digital Signature & MITM Detection</p>
    </div>

    <!-- ===== STEP INDICATOR ===== -->
    <div class="steps-indicator">
        <div class="step-item">
            <div class="step-dot" id="dot-1">1</div>
            <div class="step-label">Generate</div>
        </div>
        <div class="step-line"></div>
        <div class="step-item">
            <div class="step-dot" id="dot-2">2</div>
            <div class="step-label">Sign</div>
        </div>
        <div class="step-line"></div>
        <div class="step-item">
            <div class="step-dot" id="dot-3">3</div>
            <div class="step-label">Verify</div>
        </div>
    </div>


    <!-- ===================================================== -->
    <!-- CARD 1: GENERATE KEY                                   -->
    <!-- ===================================================== -->
    <div class="card">
        <div class="card-header">
            <div class="card-icon blue">🔑</div>
            <div class="card-title">
                <h2>Generate Key Pair</h2>
                <p>openssl_pkey_new() — RSA 2048-bit</p>
            </div>
            <div class="card-step-badge badge-blue">STEP 01</div>
        </div>

        <p style="font-size:13px; color:var(--text-muted); margin-bottom:20px; font-family:'JetBrains Mono',monospace; line-height:1.7;">
            Generate pasangan kunci RSA 2048-bit. Private key disimpan di <code style="color:var(--accent-cyan)">keys/private_key.pem</code>
            dan public key di <code style="color:var(--accent-cyan)">keys/public_key.pem</code>.
        </p>

        <div class="row-actions">
            <button class="btn btn-blue" id="btn-generate" onclick="generateKey()">
                <span>⚙️</span>
                <span>Generate Key Pair</span>
                <div class="spinner" id="spin-gen"></div>
            </button>
        </div>

        <!-- Output: Key Display -->
        <div class="key-display" id="key-display">
            <div id="gen-success-msg" class="success-msg">
                ✅ <span id="gen-success-text"></span>
            </div>
            <div class="key-tabs">
                <button class="key-tab active" id="tab-private" onclick="switchTab('private')">🔒 Private Key</button>
                <button class="key-tab" id="tab-public"  onclick="switchTab('public')">🔓 Public Key</button>
            </div>
            <div class="key-content show" id="content-private"></div>
            <div class="key-content" id="content-public"></div>
            <div class="info-box" style="margin-top:8px; border-radius:0 0 10px 10px;">
                ℹ️ Private key WAJIB dirahasiakan. Hanya public key yang boleh dibagikan untuk verifikasi.
            </div>
        </div>
    </div>


    <!-- ===================================================== -->
    <!-- CARD 2: SIGN                                           -->
    <!-- ===================================================== -->
    <div class="card">
        <div class="card-header">
            <div class="card-icon cyan">✍️</div>
            <div class="card-title">
                <h2>Tanda Tangan Digital (Sign)</h2>
                <p>openssl_sign() — OPENSSL_ALGO_SHA256</p>
            </div>
            <div class="card-step-badge badge-cyan">STEP 02</div>
        </div>

        <div class="form-group">
            <label>Teks Dokumen</label>
            <div class="quick-fill">
                <span class="chip" onclick="fillText('sign-text', 'Transfer ke Budi: Rp 100.000')">
                    Transfer ke Budi: Rp 100.000
                </span>
                <span class="chip" onclick="fillText('sign-text', 'Surat Perjanjian Kerja Sama — PT. Alpha')">
                    Surat Perjanjian...
                </span>
                <span class="chip" onclick="fillText('sign-text', 'Data Mahasiswa: NIM 123456789, Nilai A')">
                    Data Mahasiswa...
                </span>
            </div>
            <textarea id="sign-text" placeholder="Masukkan teks yang ingin ditandatangani...">Transfer ke Budi: Rp 100.000</textarea>
        </div>

        <div class="row-actions">
            <button class="btn btn-cyan" onclick="signText()">
                <span>✍️</span>
                <span>Buat Signature</span>
                <div class="spinner" id="spin-sign"></div>
            </button>
            <button class="btn btn-outline btn-sm" onclick="clearField('sign-text'); hidePanel('sign-output')">
                Bersihkan
            </button>
        </div>

        <!-- Output: Signature -->
        <div class="output-panel" id="sign-output">
            <div class="output-bar">
                <span class="output-bar-label">📋 Signature (Base64 / SHA-256)</span>
                <button class="btn-copy" onclick="copyText('sign-result')">Copy</button>
            </div>
            <div class="output-body">
                <div class="output-text" id="sign-result"></div>
            </div>
        </div>

        <div class="divider" style="margin-top:20px;">— auto-fill ke verify —</div>
        <div class="info-box">
            💡 Setelah klik "Buat Signature", teks dan signature akan otomatis ter-copy ke section Verify di bawah. Kamu tinggal klik "Verifikasi".
        </div>
    </div>


    <!-- ===================================================== -->
    <!-- MITM SIMULATION BANNER                                 -->
    <!-- ===================================================== -->
    <div class="mitm-section">
        <div class="mitm-header">
            <span style="font-size:24px;">🕵️</span>
            <div>
                <div class="mitm-title">Simulasi Serangan Man-in-the-Middle (MITM)</div>
                <div class="mitm-subtitle">// Demonstrasi bagaimana digital signature mendeteksi modifikasi teks</div>
            </div>
        </div>

        <div class="mitm-flow">
            <div class="mitm-box">
                <div class="mitm-box-label">🔏 Teks Asli (Ditandatangani)</div>
                <div class="mitm-box-text original">Transfer ke Budi:<br>Rp 100.000</div>
            </div>
            <div class="mitm-arrow">⚡ →</div>
            <div class="mitm-box">
                <div class="mitm-box-label">💀 Setelah MITM (Dimodifikasi)</div>
                <div class="mitm-box-text modified">Transfer ke <strong>Andi</strong>:<br>Rp <strong>500.000</strong></div>
            </div>
        </div>

        <ul class="mitm-steps">
            <li>
                <span class="num">1</span>
                <span>Sign teks asli <code style="color:var(--accent-green)">"Transfer ke Budi: Rp 100.000"</code> → dapatkan signature</span>
            </li>
            <li>
                <span class="num">2</span>
                <span>Paste signature tersebut ke form Verify di bawah</span>
            </li>
            <li>
                <span class="num">3</span>
                <span>Ubah teksnya menjadi <code style="color:var(--accent-red)">"Transfer ke Andi: Rp 100.000"</code> (simulasi hacker)</span>
            </li>
            <li>
                <span class="num">4</span>
                <span>Klik Verifikasi → hasilnya akan <strong style="color:var(--accent-red)">TIDAK VALID</strong> karena hash berubah</span>
            </li>
        </ul>

        <div style="margin-top:16px; display:flex; gap:8px; flex-wrap:wrap;">
            <button class="btn btn-red btn-sm" onclick="simulateMITM()">
                💀 Isi Otomatis Teks Palsu (Simulasi MITM)
            </button>
        </div>
    </div>


    <!-- ===================================================== -->
    <!-- CARD 3: VERIFY                                         -->
    <!-- ===================================================== -->
    <div class="card">
        <div class="card-header">
            <div class="card-icon green">🔍</div>
            <div class="card-title">
                <h2>Verifikasi Dokumen</h2>
                <p>openssl_verify() — Periksa keaslian dokumen</p>
            </div>
            <div class="card-step-badge badge-green">STEP 03</div>
        </div>

        <div class="form-group">
            <label>Teks yang akan Diverifikasi</label>
            <textarea id="verify-text" placeholder="Paste teks yang ingin diverifikasi..."></textarea>
        </div>

        <div class="form-group">
            <label>Signature (Base64)</label>
            <textarea id="verify-sig" placeholder="Paste signature Base64 di sini..." style="min-height:70px;"></textarea>
        </div>

        <div class="row-actions">
            <button class="btn btn-green" onclick="verifyText()">
                <span>🔍</span>
                <span>Verifikasi Dokumen</span>
                <div class="spinner" id="spin-verify"></div>
            </button>
            <button class="btn btn-outline btn-sm" onclick="clearVerify()">
                Bersihkan
            </button>
        </div>

        <!-- Result: VALID -->
        <div class="result-valid" id="result-valid">
            <div class="result-status" id="result-status-text">✅ VALID</div>
            <div class="result-detail" id="result-detail-text"></div>
        </div>

        <!-- Result: TIDAK VALID -->
        <div class="result-invalid" id="result-invalid">
            <div class="result-status" id="result-invalid-text">❌ TIDAK VALID</div>
            <div class="result-detail" id="result-invalid-detail"></div>
        </div>
    </div>


    <!-- ===== FOOTER ===== -->
    <div class="footer">
        <p>🔐 Web Verifikator Dokumen — Praktikum Kriptografi</p>
        <p>Menggunakan: RSA 2048-bit · SHA-256 · OpenSSL PHP Extension</p>
        <p style="margin-top:8px; font-size:10px;">openssl_pkey_new · openssl_pkey_export · openssl_sign · openssl_verify</p>
    </div>

</div><!-- /wrapper -->


<!-- ===== JAVASCRIPT ===== -->
<script>
    // ─── Generate Key ─────────────────────────────────────────────────────
    function generateKey() {
        const btn = document.getElementById('btn-generate');
        setLoading('spin-gen', true);
        btn.disabled = true;

        fetch('generate_key.php')
            .then(r => r.json())
            .then(data => {
                setLoading('spin-gen', false);
                btn.disabled = false;

                if (data.success) {
                    document.getElementById('content-private').textContent = data.private_key;
                    document.getElementById('content-public').textContent  = data.public_key;
                    document.getElementById('gen-success-text').textContent =
                        `Key berhasil digenerate! [${data.timestamp}]`;
                    document.getElementById('key-display').classList.add('show');

                    // Highlight step 1
                    highlightStep(1);
                } else {
                    alert('❌ Gagal: ' + data.message);
                }
            })
            .catch(err => {
                setLoading('spin-gen', false);
                btn.disabled = false;
                alert('❌ Error koneksi: ' + err.message);
            });
    }

    // ─── Tab Key Display ──────────────────────────────────────────────────
    function switchTab(tab) {
        document.getElementById('content-private').classList.remove('show');
        document.getElementById('content-public').classList.remove('show');
        document.getElementById('tab-private').classList.remove('active');
        document.getElementById('tab-public').classList.remove('active');

        document.getElementById('content-' + tab).classList.add('show');
        document.getElementById('tab-' + tab).classList.add('active');
    }

    // ─── Sign ─────────────────────────────────────────────────────────────
    function signText() {
        const text = document.getElementById('sign-text').value.trim();
        if (!text) { alert('⚠️ Teks tidak boleh kosong!'); return; }

        setLoading('spin-sign', true);

        const form = new FormData();
        form.append('text', text);

        fetch('sign.php', { method: 'POST', body: form })
            .then(r => r.json())
            .then(data => {
                setLoading('spin-sign', false);

                if (data.success) {
                    document.getElementById('sign-result').textContent = data.signature_base64;
                    document.getElementById('sign-output').classList.add('show');

                    // Auto-fill ke form verify
                    document.getElementById('verify-text').value = data.original_text;
                    document.getElementById('verify-sig').value  = data.signature_base64;

                    highlightStep(2);
                } else {
                    alert('❌ Gagal: ' + data.message);
                }
            })
            .catch(err => {
                setLoading('spin-sign', false);
                alert('❌ Error: ' + err.message);
            });
    }

    // ─── Verify ───────────────────────────────────────────────────────────
    function verifyText() {
        const text = document.getElementById('verify-text').value.trim();
        const sig  = document.getElementById('verify-sig').value.trim();

        if (!text) { alert('⚠️ Teks tidak boleh kosong!'); return; }
        if (!sig)  { alert('⚠️ Signature tidak boleh kosong!'); return; }

        setLoading('spin-verify', true);

        // Sembunyikan hasil sebelumnya
        document.getElementById('result-valid').classList.remove('show');
        document.getElementById('result-invalid').classList.remove('show');

        const form = new FormData();
        form.append('text', text);
        form.append('signature', sig);

        fetch('verify.php', { method: 'POST', body: form })
            .then(r => r.json())
            .then(data => {
                setLoading('spin-verify', false);

                if (data.success) {
                    if (data.valid) {
                        // ✅ VALID
                        document.getElementById('result-status-text').textContent  = data.message;
                        document.getElementById('result-detail-text').textContent  = data.detail + '\n⏱ ' + data.timestamp;
                        document.getElementById('result-valid').classList.add('show');
                    } else {
                        // ❌ TIDAK VALID
                        document.getElementById('result-invalid-text').textContent   = data.message;
                        document.getElementById('result-invalid-detail').textContent = data.detail + '\n⏱ ' + data.timestamp;
                        document.getElementById('result-invalid').classList.add('show');
                    }
                    highlightStep(3);
                } else {
                    alert('❌ Error: ' + data.message);
                }
            })
            .catch(err => {
                setLoading('spin-verify', false);
                alert('❌ Error: ' + err.message);
            });
    }

    // ─── Simulasi MITM ────────────────────────────────────────────────────
    function simulateMITM() {
        // Hanya ganti teks, tapi pertahankan signature yang ada
        const currentSig = document.getElementById('verify-sig').value;

        if (!currentSig) {
            alert('⚠️ Lakukan Sign terlebih dahulu untuk mendapatkan signature asli!\n\nLangkah:\n1. Sign teks "Transfer ke Budi: Rp 100.000"\n2. Klik tombol ini untuk mengganti teks (MITM)\n3. Klik Verifikasi');
            return;
        }

        // Ubah teks → simulasi MITM
        document.getElementById('verify-text').value = 'Transfer ke Andi: Rp 100.000';

        // Flash highlight
        const tv = document.getElementById('verify-text');
        tv.style.borderColor = '#ef4444';
        tv.style.boxShadow   = '0 0 0 3px rgba(239,68,68,0.2)';
        setTimeout(() => {
            tv.style.borderColor = '';
            tv.style.boxShadow   = '';
        }, 1500);

        document.getElementById('result-valid').classList.remove('show');
        document.getElementById('result-invalid').classList.remove('show');
    }

    // ─── Helpers ──────────────────────────────────────────────────────────
    function setLoading(spinnerId, state) {
        document.getElementById(spinnerId).style.display = state ? 'block' : 'none';
    }

    function hidePanel(id) {
        document.getElementById(id).classList.remove('show');
    }

    function clearField(id) {
        document.getElementById(id).value = '';
    }

    function clearVerify() {
        clearField('verify-text');
        clearField('verify-sig');
        document.getElementById('result-valid').classList.remove('show');
        document.getElementById('result-invalid').classList.remove('show');
    }

    function fillText(fieldId, text) {
        document.getElementById(fieldId).value = text;
        document.getElementById(fieldId).focus();
    }

    function copyText(elementId) {
        const text = document.getElementById(elementId).textContent;
        navigator.clipboard.writeText(text).then(() => {
            const btn = event.target;
            btn.textContent = 'Copied!';
            btn.style.color = 'var(--accent-green)';
            btn.style.borderColor = 'var(--accent-green)';
            setTimeout(() => {
                btn.textContent = 'Copy';
                btn.style.color = '';
                btn.style.borderColor = '';
            }, 1500);
        });
    }

    function highlightStep(n) {
        for (let i = 1; i <= 3; i++) {
            const dot = document.getElementById('dot-' + i);
            if (i <= n) {
                dot.style.background    = 'var(--accent-blue)';
                dot.style.borderColor   = 'var(--accent-blue)';
                dot.style.color         = 'white';
                dot.style.boxShadow     = '0 0 12px rgba(59,130,246,0.5)';
            }
        }
    }
</script>

</body>
</html>