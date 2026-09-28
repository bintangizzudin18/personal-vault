<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Unlock Vault // PDV</title>

    <style>
        :root {
            --black: #010302;
            --green: #00F815;
            --dark-green: #1E6025;
            --green-2: #059E12;
            --cyan: #03E6F2;
            --white: #F8F8F8;
            --gray: #9AA29E;
            --purple-gray: #505379;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html,
        body {
            width: 100%;
            min-height: 100%;
        }

        body {
            min-height: 100vh;
            overflow: hidden;
            background: var(--black);
            color: var(--white);
            font-family:
                "Courier New",
                Courier,
                monospace;
        }

        /* ========================================
           MATRIX BACKGROUND
        ======================================== */

        #matrix {
            position: fixed;
            inset: 0;
            z-index: 0;
            width: 100%;
            height: 100%;
            opacity: 0.18;
        }

        .scanlines {
            position: fixed;
            inset: 0;
            z-index: 1;
            pointer-events: none;

            background:
                repeating-linear-gradient(
                    to bottom,
                    rgba(0, 0, 0, 0) 0px,
                    rgba(0, 0, 0, 0) 2px,
                    rgba(0, 248, 21, 0.025) 3px,
                    rgba(0, 0, 0, 0) 4px
                );
        }

        .grid {
            position: fixed;
            inset: 0;
            z-index: 1;
            pointer-events: none;

            background-image:
                linear-gradient(
                    rgba(0, 248, 21, 0.025) 1px,
                    transparent 1px
                ),
                linear-gradient(
                    90deg,
                    rgba(0, 248, 21, 0.025) 1px,
                    transparent 1px
                );

            background-size: 45px 45px;
        }

        .vignette {
            position: fixed;
            inset: 0;
            z-index: 2;
            pointer-events: none;

            background:
                radial-gradient(
                    circle at center,
                    transparent 25%,
                    rgba(1, 3, 2, 0.45) 65%,
                    rgba(1, 3, 2, 0.95) 100%
                );
        }

        /* ========================================
           MAIN
        ======================================== */

        .page {
            position: relative;
            z-index: 5;

            min-height: 100vh;

            display: flex;
            align-items: center;
            justify-content: center;

            padding: 30px 20px;
        }

        .terminal {
            position: relative;

            width: 100%;
            max-width: 520px;

            padding: 2px;

            background:
                linear-gradient(
                    135deg,
                    rgba(0, 248, 21, 0.8),
                    rgba(3, 230, 242, 0.25),
                    rgba(0, 248, 21, 0.4)
                );

            clip-path: polygon(
                0 14px,
                14px 0,
                calc(100% - 14px) 0,
                100% 14px,
                100% calc(100% - 14px),
                calc(100% - 14px) 100%,
                14px 100%,
                0 calc(100% - 14px)
            );

            box-shadow:
                0 0 25px rgba(0, 248, 21, 0.08),
                0 0 70px rgba(3, 230, 242, 0.035);
        }

        .terminal-inner {
            position: relative;

            background:
                linear-gradient(
                    145deg,
                    rgba(3, 12, 5, 0.98),
                    rgba(1, 3, 2, 0.99)
                );

            padding: 42px 38px;

            clip-path: polygon(
                0 13px,
                13px 0,
                calc(100% - 13px) 0,
                100% 13px,
                100% calc(100% - 13px),
                calc(100% - 13px) 100%,
                13px 100%,
                0 calc(100% - 13px)
            );
        }

        /* ========================================
           HEADER
        ======================================== */

        .brand {
            text-align: center;
            margin-bottom: 35px;
        }

        .brand-symbol {
            width: 72px;
            height: 72px;

            margin: 0 auto 18px;

            display: flex;
            align-items: center;
            justify-content: center;

            border: 1px solid rgba(0, 248, 21, 0.5);

            color: var(--green);

            font-size: 27px;
            font-weight: bold;

            position: relative;

            box-shadow:
                0 0 15px rgba(0, 248, 21, 0.08),
                inset 0 0 18px rgba(0, 248, 21, 0.05);
        }

        .brand-symbol::before,
        .brand-symbol::after {
            content: "";

            position: absolute;

            width: 8px;
            height: 8px;

            border-color: var(--cyan);
        }

        .brand-symbol::before {
            top: -1px;
            left: -1px;

            border-top: 1px solid;
            border-left: 1px solid;
        }

        .brand-symbol::after {
            bottom: -1px;
            right: -1px;

            border-bottom: 1px solid;
            border-right: 1px solid;
        }

        .brand h1 {
            font-size: clamp(22px, 5vw, 30px);
            letter-spacing: 5px;
            font-weight: 700;

            color: var(--white);

            text-shadow:
                0 0 8px rgba(0, 248, 21, 0.35);
        }

        .brand h1 span {
            color: var(--green);
        }

        .brand-subtitle {
            margin-top: 9px;

            color: var(--gray);

            font-size: 11px;
            letter-spacing: 3px;
            text-transform: uppercase;
        }

        /* ========================================
           STATUS
        ======================================== */

        .status {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 9px;

            margin-bottom: 30px;

            color: var(--cyan);

            font-size: 10px;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .status-dot {
            width: 6px;
            height: 6px;

            border-radius: 50%;

            background: var(--cyan);

            box-shadow:
                0 0 7px var(--cyan);

            animation: pulse 1.6s infinite;
        }

        @keyframes pulse {
            0%,
            100% {
                opacity: 0.4;
            }

            50% {
                opacity: 1;
            }
        }

        /* ========================================
           FORM
        ======================================== */

        .field {
            position: relative;
            margin-bottom: 22px;
        }

        .field-label {
            display: block;

            margin-bottom: 9px;

            color: var(--gray);

            font-size: 10px;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .input-wrapper {
            position: relative;
        }

        .pdv-input {
            width: 100%;

            padding: 15px 50px 15px 15px;

            border: 1px solid rgba(0, 248, 21, 0.28);
            outline: none;

            background:
                rgba(0, 248, 21, 0.025);

            color: var(--green);

            font-family: inherit;
            font-size: 14px;
            letter-spacing: 1px;

            transition:
                border-color 0.2s ease,
                box-shadow 0.2s ease,
                background 0.2s ease;
        }

        .pdv-input::placeholder {
            color: rgba(154, 162, 158, 0.35);
        }

        .pdv-input:focus {
            border-color: var(--green);

            background:
                rgba(0, 248, 21, 0.045);

            box-shadow:
                0 0 0 1px rgba(0, 248, 21, 0.12),
                0 0 18px rgba(0, 248, 21, 0.06);
        }

        .focus-line {
            position: absolute;

            left: 0;
            bottom: 0;

            width: 0;
            height: 1px;

            background: var(--cyan);

            box-shadow:
                0 0 7px rgba(3, 230, 242, 0.6);

            transition: width 0.3s ease;
        }

        .pdv-input:focus ~ .focus-line {
            width: 100%;
        }

        .toggle-secret {
            position: absolute;

            top: 50%;
            right: 12px;

            transform: translateY(-50%);

            border: 0;
            background: transparent;

            color: var(--gray);

            font-family: inherit;
            font-size: 10px;
            letter-spacing: 1px;

            cursor: pointer;
        }

        .toggle-secret:hover {
            color: var(--cyan);
        }

        /* ========================================
           ERROR
        ======================================== */

        .error-message {
            margin-top: 9px;

            color: #ff6b6b;

            font-size: 10px;
            line-height: 1.5;
        }

        /* ========================================
           BUTTON
        ======================================== */

        .unlock-button {
            position: relative;

            width: 100%;

            margin-top: 8px;
            padding: 16px 20px;

            border: 1px solid var(--green);

            background:
                rgba(0, 248, 21, 0.07);

            color: var(--green);

            font-family: inherit;
            font-size: 11px;
            font-weight: bold;
            letter-spacing: 3px;

            cursor: pointer;

            overflow: hidden;

            transition:
                color 0.2s ease,
                background 0.2s ease,
                box-shadow 0.2s ease;
        }

        .unlock-button::before {
            content: "";

            position: absolute;

            top: 0;
            left: -100%;

            width: 100%;
            height: 100%;

            background:
                linear-gradient(
                    90deg,
                    transparent,
                    rgba(0, 248, 21, 0.15),
                    transparent
                );

            transition: left 0.45s ease;
        }

        .unlock-button:hover {
            background: var(--green);
            color: var(--black);

            box-shadow:
                0 0 20px rgba(0, 248, 21, 0.18);
        }

        .unlock-button:hover::before {
            left: 100%;
        }

        .unlock-button:disabled {
            cursor: wait;
            opacity: 0.6;
        }

        /* ========================================
           FOOTER INFO
        ======================================== */

        .security-info {
            display: grid;
            grid-template-columns: 1fr 1fr;

            gap: 10px;

            margin-top: 30px;
            padding-top: 20px;

            border-top: 1px solid rgba(80, 83, 121, 0.25);
        }

        .security-item {
            padding: 10px;

            border-left: 1px solid rgba(0, 248, 21, 0.25);

            background:
                rgba(255, 255, 255, 0.01);
        }

        .security-item-label {
            display: block;

            margin-bottom: 5px;

            color: var(--gray);

            font-size: 8px;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        .security-item-value {
            color: var(--green);

            font-size: 9px;
            letter-spacing: 1px;
        }

        .back-link {
            display: block;

            margin-top: 24px;

            color: var(--gray);

            text-align: center;
            text-decoration: none;

            font-size: 9px;
            letter-spacing: 1.5px;
            text-transform: uppercase;

            transition: color 0.2s ease;
        }

        .back-link:hover {
            color: var(--cyan);
        }

        /* ========================================
           CORNER DECORATIONS
        ======================================== */

        .corner {
            position: absolute;

            width: 18px;
            height: 18px;

            pointer-events: none;
        }

        .corner.tl {
            top: 13px;
            left: 13px;

            border-top: 1px solid var(--green);
            border-left: 1px solid var(--green);
        }

        .corner.tr {
            top: 13px;
            right: 13px;

            border-top: 1px solid var(--cyan);
            border-right: 1px solid var(--cyan);
        }

        .corner.bl {
            bottom: 13px;
            left: 13px;

            border-bottom: 1px solid var(--cyan);
            border-left: 1px solid var(--cyan);
        }

        .corner.br {
            bottom: 13px;
            right: 13px;

            border-bottom: 1px solid var(--green);
            border-right: 1px solid var(--green);
        }

        /* ========================================
           RESPONSIVE
        ======================================== */

        @media (max-width: 600px) {
            .page {
                padding: 18px 12px;
            }

            .terminal-inner {
                padding: 32px 22px;
            }

            .brand {
                margin-bottom: 28px;
            }

            .brand-symbol {
                width: 60px;
                height: 60px;

                font-size: 22px;
            }

            .security-info {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

    <canvas id="matrix"></canvas>

    <div class="scanlines"></div>
    <div class="grid"></div>
    <div class="vignette"></div>

    <main class="page">

        <section class="terminal">

            <div class="terminal-inner">

                <div class="corner tl"></div>
                <div class="corner tr"></div>
                <div class="corner bl"></div>
                <div class="corner br"></div>

                <header class="brand">

                    <div class="brand-symbol">
                        [ ]
                    </div>

                    <h1>
                        PERSONAL <span>VAULT</span>
                    </h1>

                    <div class="brand-subtitle">
                        Personal Digital Vault
                    </div>

                </header>

                <div class="status">
                    <span class="status-dot"></span>
                    Vault Locked // Authentication Required
                </div>

                <form
                    method="POST"
                    action="{{ route('vault.unlock.process') }}"
                    id="unlock-form"
                >
                    @csrf

                    <div class="field">

                        <label
                            for="root_secret"
                            class="field-label"
                        >
                            Root Secret
                        </label>

                        <div class="input-wrapper">

                            <input
                                type="password"
                                id="root_secret"
                                name="root_secret"
                                class="pdv-input"
                                placeholder="ENTER ROOT SECRET"
                                autocomplete="off"
                                spellcheck="false"
                                required
                                autofocus
                            >

                            <button
                                type="button"
                                class="toggle-secret"
                                id="toggle-secret"
                            >
                                SHOW
                            </button>

                            <span class="focus-line"></span>

                        </div>

                        @error('root_secret')
                            <div class="error-message">
                                [ERROR] {{ $message }}
                            </div>
                        @enderror

                    </div>

                    @if (session('error'))
                        <div class="error-message" style="margin-bottom: 18px;">
                            [ERROR] {{ session('error') }}
                        </div>
                    @endif

                    <button
                        type="submit"
                        class="unlock-button"
                        id="unlock-button"
                    >
                        [ UNLOCK VAULT ]
                    </button>

                </form>

                <div class="security-info">

                    <div class="security-item">
                        <span class="security-item-label">
                            Encryption
                        </span>

                        <span class="security-item-value">
                            AES-256-GCM
                        </span>
                    </div>

                    <div class="security-item">
                        <span class="security-item-label">
                            Key Derivation
                        </span>

                        <span class="security-item-value">
                            HKDF-SHA-256
                        </span>
                    </div>

                </div>

                <a
                    href="{{ url('/') }}"
                    class="back-link"
                >
                    ← Return to System
                </a>

            </div>

        </section>

    </main>

    <script>
        /* ========================================
           ROOT SECRET VISIBILITY
        ======================================== */

        const secretInput = document.getElementById('root_secret');
        const toggleSecret = document.getElementById('toggle-secret');

        toggleSecret.addEventListener('click', function () {
            const isPassword = secretInput.type === 'password';

            secretInput.type = isPassword
                ? 'text'
                : 'password';

            this.textContent = isPassword
                ? 'HIDE'
                : 'SHOW';
        });


        /* ========================================
           DOUBLE SUBMIT PROTECTION
        ======================================== */

        const unlockForm = document.getElementById('unlock-form');
        const unlockButton = document.getElementById('unlock-button');

        unlockForm.addEventListener('submit', function () {
            unlockButton.disabled = true;
            unlockButton.textContent = '[ AUTHENTICATING... ]';
        });


        /* ========================================
           MATRIX
        ======================================== */

        const canvas = document.getElementById('matrix');
        const ctx = canvas.getContext('2d');

        let width;
        let height;
        let columns;
        let drops;

        const characters =
            '01ABCDEFGHIJKLMNOPQRSTUVWXYZ#$%&@';

        function resizeMatrix() {
            width = canvas.width = window.innerWidth;
            height = canvas.height = window.innerHeight;

            const fontSize = 14;

            columns = Math.floor(width / fontSize);
            drops = Array(columns).fill(1);
        }

        function drawMatrix() {
            ctx.fillStyle = 'rgba(1, 3, 2, 0.055)';
            ctx.fillRect(0, 0, width, height);

            const fontSize = 14;

            ctx.font = fontSize + 'px monospace';

            for (let i = 0; i < drops.length; i++) {

                const character =
                    characters[
                        Math.floor(
                            Math.random() * characters.length
                        )
                    ];

                const x = i * fontSize;
                const y = drops[i] * fontSize;

                const random = Math.random();

                if (random > 0.985) {
                    ctx.fillStyle = '#03E6F2';
                } else if (random > 0.96) {
                    ctx.fillStyle = '#00F815';
                } else {
                    ctx.fillStyle = '#1E6025';
                }

                ctx.fillText(
                    character,
                    x,
                    y
                );

                if (
                    y > height &&
                    Math.random() > 0.975
                ) {
                    drops[i] = 0;
                }

                drops[i]++;
            }
        }

        resizeMatrix();

        window.addEventListener(
            'resize',
            resizeMatrix
        );

        setInterval(
            drawMatrix,
            45
        );
    </script>

</body>
</html>
