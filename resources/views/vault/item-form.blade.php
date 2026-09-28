{{-- resources/views/vault/item-form.blade.php --}}

@php
    $isEdit = isset($item);

    $pageTitle = $isEdit
        ? 'EDIT SECURE OBJECT'
        : 'CREATE SECURE OBJECT';

    $submitLabel = $isEdit
        ? 'UPDATE OBJECT'
        : 'STORE OBJECT';

    $itemTitle = $item['title'] ?? '';
    $itemNotes = $item['notes'] ?? '';

    /*
    |--------------------------------------------------------------------------
    | FIELD DEFINITIONS
    |--------------------------------------------------------------------------
    | Prioritaskan variable yang dikirim controller.
    | Jika controller menggunakan $fields, tetap bisa digunakan.
    */

    $definitions = $fieldDefinitions
        ?? $fields
        ?? [];

    /*
    |--------------------------------------------------------------------------
    | Sensitive field detection
    |--------------------------------------------------------------------------
    */

    $isSensitiveField = function ($name) {
        $name = strtolower($name);

        return str_contains($name, 'password')
            || str_contains($name, 'pin')
            || str_contains($name, 'recovery code');
    };

    /*
    |--------------------------------------------------------------------------
    | Existing values
    |--------------------------------------------------------------------------
    */

    $existingFields = $item['fields'] ?? [];
@endphp

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        PDV // {{ $pageTitle }}
    </title>

    <style>
        /* =====================================================
           PDV // CREATE / EDIT TERMINAL
        ===================================================== */

        :root {
            --black: #010302;
            --black-soft: #050806;
            --panel: rgba(7, 13, 9, 0.90);
            --panel-soft: rgba(7, 13, 9, 0.68);

            --green: #00f815;
            --green-soft: #059e12;
            --green-dark: #1e6025;

            --cyan: #03e6f2;

            --white: #f8f8f8;
            --gray: #9aa29e;
            --gray-dark: #505379;

            --red: #ff4057;

            --border: rgba(0, 248, 21, 0.18);
            --border-strong: rgba(0, 248, 21, 0.42);
        }


        /* =====================================================
           RESET
        ===================================================== */

        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            min-height: 100%;
        }

        body {
            background: var(--black);
            color: var(--white);

            font-family:
                "Courier New",
                Courier,
                monospace;

            overflow-x: hidden;
        }


        /* =====================================================
           BACKGROUND
        ===================================================== */

        .pdv-bg {
            position: fixed;
            inset: 0;
            z-index: 0;

            overflow: hidden;

            background:
                radial-gradient(
                    circle at 50% 20%,
                    rgba(0, 248, 21, 0.055),
                    transparent 45%
                ),
                var(--black);
        }

        #pdv-matrix {
            position: absolute;
            inset: 0;

            width: 100%;
            height: 100%;

            opacity: 0.25;

            pointer-events: none;
        }

        .pdv-grid {
            position: absolute;
            inset: 0;

            background-image:
                linear-gradient(
                    rgba(0, 248, 21, 0.035) 1px,
                    transparent 1px
                ),
                linear-gradient(
                    90deg,
                    rgba(0, 248, 21, 0.035) 1px,
                    transparent 1px
                );

            background-size: 45px 45px;
        }

        .pdv-scanlines {
            position: absolute;
            inset: 0;

            pointer-events: none;

            background:
                repeating-linear-gradient(
                    0deg,
                    transparent 0px,
                    transparent 3px,
                    rgba(255, 255, 255, 0.018) 4px,
                    transparent 5px
                );
        }

        .pdv-vignette {
            position: absolute;
            inset: 0;

            pointer-events: none;

            background:
                radial-gradient(
                    ellipse at center,
                    transparent 45%,
                    rgba(0, 0, 0, 0.62) 100%
                );
        }


        /* =====================================================
           WRAPPER
        ===================================================== */

        .pdv-wrapper {
            position: relative;
            z-index: 10;

            width: 100%;
            max-width: 1000px;

            margin: 0 auto;

            padding: 30px 25px 60px;
        }


        /* =====================================================
           TOP BAR
        ===================================================== */

        .pdv-topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;

            gap: 15px;

            padding: 13px 17px;
            margin-bottom: 20px;

            border: 1px solid var(--border);

            background:
                rgba(7, 13, 9, 0.78);
        }

        .pdv-brand {
            color: var(--green);

            font-size: 11px;
            font-weight: bold;

            letter-spacing: 2px;

            text-shadow:
                0 0 8px rgba(0, 248, 21, 0.45);
        }

        .pdv-system-status {
            display: flex;
            align-items: center;

            gap: 8px;

            color: var(--gray);

            font-size: 9px;
            letter-spacing: 1.5px;
        }

        .pdv-status-dot {
            width: 6px;
            height: 6px;

            background: var(--green);

            box-shadow:
                0 0 9px rgba(0, 248, 21, 0.8);

            animation:
                pdv-pulse 2s infinite;
        }


        /* =====================================================
           HEADER
        ===================================================== */

        .pdv-header {
            position: relative;

            overflow: hidden;

            padding: 28px 30px;
            margin-bottom: 18px;

            border:
                1px solid var(--border-strong);

            background:
                linear-gradient(
                    135deg,
                    rgba(0, 248, 21, 0.035),
                    rgba(7, 13, 9, 0.92)
                );
        }

        .pdv-header::before {
            content: "";

            position: absolute;

            left: 0;
            top: 0;

            width: 4px;
            height: 100%;

            background: var(--green);

            box-shadow:
                0 0 15px rgba(0, 248, 21, 0.45);
        }

        .pdv-header::after {
            content: "";

            position: absolute;

            right: 0;
            bottom: 0;

            width: 35px;
            height: 35px;

            border-right:
                2px solid var(--green);

            border-bottom:
                2px solid var(--green);
        }

        .pdv-code {
            margin-bottom: 9px;

            color: var(--green-dark);

            font-size: 9px;
            letter-spacing: 2px;
        }

        .pdv-header h1 {
            margin: 0;

            font-size:
                clamp(25px, 4vw, 38px);

            letter-spacing: -1px;
        }

        .pdv-header h1 span {
            color: var(--green);

            text-shadow:
                0 0 12px rgba(0, 248, 21, 0.25);
        }

        .pdv-header-description {
            margin-top: 10px;

            color: var(--gray);

            font-size: 10px;
            letter-spacing: 1px;
        }


        /* =====================================================
           FORM TERMINAL
        ===================================================== */

        .pdv-form-terminal {
            border:
                1px solid var(--border);

            background:
                var(--panel);

            padding: 25px;

            box-shadow:
                0 10px 45px rgba(0, 0, 0, 0.28);
        }

        .pdv-section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;

            gap: 15px;

            padding-bottom: 15px;
            margin-bottom: 20px;

            border-bottom:
                1px solid var(--border);
        }

        .pdv-section-title {
            color: var(--green);

            font-size: 10px;
            font-weight: bold;

            letter-spacing: 2px;
        }

        .pdv-encryption-status {
            color: var(--gray-dark);

            font-size: 8px;
            letter-spacing: 1px;
        }

        .pdv-encryption-status span {
            color: var(--green);
        }


        /* =====================================================
           ERROR
        ===================================================== */

        .pdv-error {
            margin-bottom: 20px;

            padding: 13px 15px;

            border-left:
                2px solid var(--red);

            background:
                rgba(255, 64, 87, 0.045);

            color: #ff7d8b;

            font-size: 10px;
        }

        .pdv-error-title {
            margin-bottom: 7px;

            color: var(--red);

            font-weight: bold;
            letter-spacing: 1px;
        }

        .pdv-error ul {
            margin: 0;
            padding-left: 18px;
        }


        /* =====================================================
           TITLE INPUT
        ===================================================== */

        .pdv-title-block {
            margin-bottom: 25px;
        }

        .pdv-label {
            display: block;

            margin-bottom: 8px;

            color: var(--gray);

            font-size: 9px;
            letter-spacing: 1.5px;

            text-transform: uppercase;
        }

        .pdv-required {
            color: var(--green);
        }

        .pdv-input,
        .pdv-textarea {
            width: 100%;

            border:
                1px solid rgba(0, 248, 21, 0.15);

            outline: none;

            background:
                rgba(0, 0, 0, 0.38);

            color: var(--white);

            font-family:
                "Courier New",
                Courier,
                monospace;

            font-size: 11px;

            transition:
                border-color 0.2s ease,
                background 0.2s ease,
                box-shadow 0.2s ease;
        }

        .pdv-input {
            min-height: 44px;

            padding: 11px 13px;
        }

        .pdv-textarea {
            min-height: 110px;

            padding: 12px 13px;

            resize: vertical;

            line-height: 1.6;
        }

        .pdv-input::placeholder,
        .pdv-textarea::placeholder {
            color: var(--gray-dark);
        }

        .pdv-input:focus,
        .pdv-textarea:focus {
            border-color:
                rgba(0, 248, 21, 0.60);

            background:
                rgba(0, 248, 21, 0.025);

            box-shadow:
                0 0 18px rgba(0, 248, 21, 0.055);
        }


        /* =====================================================
           FIELDS GRID
        ===================================================== */

        .pdv-fields-grid {
            display: grid;

            grid-template-columns:
                repeat(2, minmax(0, 1fr));

            gap: 20px;
        }

        .pdv-field {
            position: relative;
        }

        .pdv-field.full {
            grid-column:
                1 / -1;
        }

        .pdv-field-index {
            position: absolute;

            right: 0;
            top: 0;

            color: var(--green-dark);

            font-size: 8px;
            letter-spacing: 1px;
        }

        .pdv-field-control {
            position: relative;

            display: flex;
            align-items: stretch;
        }

        .pdv-field-control .pdv-input {
            padding-right: 48px;
        }


        /* =====================================================
           SHOW / HIDE BUTTON
        ===================================================== */

        .pdv-toggle-secret {
            position: absolute;

            right: 1px;
            top: 1px;
            bottom: 1px;

            width: 42px;

            border: 0;

            border-left:
                1px solid rgba(0, 248, 21, 0.10);

            background:
                rgba(0, 248, 21, 0.018);

            color: var(--gray);

            font-family:
                "Courier New",
                Courier,
                monospace;

            cursor: pointer;

            transition:
                0.18s ease;
        }

        .pdv-toggle-secret:hover {
            color: var(--green);

            background:
                rgba(0, 248, 21, 0.055);
        }


        /* =====================================================
           NOTES
        ===================================================== */

        .pdv-notes-section {
            margin-top: 25px;

            padding-top: 23px;

            border-top:
                1px solid var(--border);
        }


        /* =====================================================
           FORM ACTIONS
        ===================================================== */

        .pdv-actions {
            display: flex;

            gap: 10px;

            margin-top: 25px;

            padding-top: 20px;

            border-top:
                1px solid var(--border);
        }

        .pdv-action {
            min-height: 43px;

            display: inline-flex;
            align-items: center;
            justify-content: center;

            padding: 0 20px;

            border:
                1px solid var(--border);

            background:
                rgba(0, 248, 21, 0.025);

            color: var(--gray);

            font-family:
                "Courier New",
                Courier,
                monospace;

            font-size: 9px;
            font-weight: bold;

            letter-spacing: 1.5px;

            text-decoration: none;

            cursor: pointer;

            transition:
                0.18s ease;
        }

        .pdv-action:hover {
            border-color:
                rgba(0, 248, 21, 0.45);

            color: var(--green);

            background:
                rgba(0, 248, 21, 0.045);
        }

        .pdv-action:active {
            transform:
                translateY(1px);
        }

        .pdv-submit {
            flex: 1;

            border-color:
                var(--green);

            color: var(--green);

            background:
                rgba(0, 248, 21, 0.055);
        }

        .pdv-submit:hover {
            color: var(--black);

            background:
                var(--green);

            box-shadow:
                0 0 22px rgba(0, 248, 21, 0.18);
        }

        .pdv-submit:disabled {
            pointer-events: none;
        }

        .pdv-cancel {
            min-width: 150px;
        }


        /* =====================================================
           TERMINAL FOOTER
        ===================================================== */

        .pdv-terminal-footer {
            display: flex;

            justify-content: space-between;

            gap: 15px;

            margin-top: 15px;

            color: var(--gray-dark);

            font-size: 8px;
            letter-spacing: 1px;
        }

        .pdv-terminal-footer span:first-child {
            color: var(--green-dark);
        }


        /* =====================================================
           FOCUS EFFECT
        ===================================================== */

        .pdv-focus-line {
            position: absolute;

            left: 0;
            bottom: 0;

            width: 0;
            height: 1px;

            background: var(--green);

            box-shadow:
                0 0 8px rgba(0, 248, 21, 0.5);

            transition:
                width 0.25s ease;

            pointer-events: none;
        }

        .pdv-input:focus ~ .pdv-focus-line {
            width: 100%;
        }


        /* =====================================================
           ANIMATION
        ===================================================== */

        @keyframes pdv-pulse {
            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.35;
            }
        }

        @keyframes pdv-submit-flash {
            0% {
                box-shadow:
                    0 0 0 rgba(0, 248, 21, 0);
            }

            50% {
                box-shadow:
                    0 0 25px rgba(0, 248, 21, 0.25);
            }

            100% {
                box-shadow:
                    0 0 0 rgba(0, 248, 21, 0);
            }
        }

        .pdv-submit:focus {
            animation:
                pdv-submit-flash 0.6s ease;
        }


        /* =====================================================
           RESPONSIVE
        ===================================================== */

        @media (max-width: 750px) {
            .pdv-fields-grid {
                grid-template-columns: 1fr;
            }

            .pdv-field.full {
                grid-column: auto;
            }
        }

        @media (max-width: 600px) {
            .pdv-wrapper {
                padding:
                    15px 12px 40px;
            }

            .pdv-topbar {
                align-items: flex-start;

                flex-direction: column;
            }

            .pdv-header {
                padding:
                    24px 20px;
            }

            .pdv-form-terminal {
                padding:
                    20px 16px;
            }

            .pdv-section-header {
                align-items: flex-start;

                flex-direction: column;
            }

            .pdv-actions {
                flex-direction: column;
            }

            .pdv-submit,
            .pdv-cancel {
                width: 100%;
            }

            .pdv-terminal-footer {
                flex-direction: column;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                animation: none !important;
                transition: none !important;
            }
        }
    </style>
    @vite(['resources/js/app.js'])
</head>

<body>

    <!-- =====================================================
         BACKGROUND
    ====================================================== -->

    <div class="pdv-bg">

        <canvas id="pdv-matrix"></canvas>

        <div class="pdv-grid"></div>

        <div class="pdv-scanlines"></div>

        <div class="pdv-vignette"></div>

    </div>


    <!-- =====================================================
         MAIN
    ====================================================== -->

    <main class="pdv-wrapper">

        <!-- =================================================
             TOP BAR
        ================================================== -->

        <div class="pdv-topbar">

            <div class="pdv-brand">
                PDV // PERSONAL DIGITAL VAULT
            </div>

            <div class="pdv-system-status">

                <span class="pdv-status-dot"></span>

                VAULT UNLOCKED

            </div>

        </div>


        <!-- =================================================
             HEADER
        ================================================== -->

        <header class="pdv-header">

            <div>

                <div class="pdv-code">
                    OBJECT //
                    {{ strtoupper($category) }}
                </div>

                <h1>

                    <span>//</span>

                    {{ $pageTitle }}

                </h1>

                <div class="pdv-header-description">

                    {{ $isEdit
                        ? 'MODIFY EXISTING ENCRYPTED OBJECT'
                        : 'INITIALIZE NEW ENCRYPTED OBJECT'
                    }}

                    //

                    {{ strtoupper(
                        str_replace('_', ' ', $category)
                    ) }}

                </div>

            </div>

        </header>


        <!-- =================================================
             FORM
        ================================================== -->

        <form
            class="pdv-form-terminal"
            method="POST"
            action="{{
                $isEdit
                    ? route(
                        'vault.item.update',
                        [$category, $item['id']]
                    )
                    : route(
                        'vault.item.store',
                        $category
                    )
            }}"
        >

            @csrf

            @if ($isEdit)
                @method('PUT')
            @endif


            <!-- =============================================
                 ERROR
            ============================================== -->

            @if ($errors->any())

                <div class="pdv-error">

                    <div class="pdv-error-title">
                        [ INPUT ERROR ]
                    </div>

                    <ul>

                        @foreach ($errors->all() as $error)

                            <li>
                                {{ $error }}
                            </li>

                        @endforeach

                    </ul>

                </div>

            @endif


            <!-- =============================================
                 SECTION HEADER
            ============================================== -->

            <div class="pdv-section-header">

                <div class="pdv-section-title">
                    // OBJECT PARAMETERS
                </div>

                <div class="pdv-encryption-status">

                    STORAGE STATUS:

                    <span>
                        ENCRYPTED
                    </span>

                </div>

            </div>


            <!-- =============================================
                 TITLE
            ============================================== -->

            <div class="pdv-title-block">

                <label
                    class="pdv-label"
                    for="title"
                >
                    OBJECT TITLE

                    <span class="pdv-required">
                        *
                    </span>
                </label>

                <div style="position: relative;">

                    <input
                        id="title"
                        type="text"
                        name="title"
                        class="pdv-input"
                        value="{{ old('title', $itemTitle) }}"
                        placeholder="e.g. Gmail Personal"
                        autocomplete="off"
                        required
                    >

                    <div class="pdv-focus-line"></div>

                </div>

            </div>


            <!-- =============================================
                 FIELDS
            ============================================== -->

            <div class="pdv-fields-grid">

                @foreach ($definitions as $fieldName => $definition)

                    @php

                        /*
                        |--------------------------------------------------------------------------
                        | Flexible definition support
                        |--------------------------------------------------------------------------
                        */

                        if (is_array($definition)) {

                            $label =
                                $definition['label']
                                ?? $definition['name']
                                ?? $fieldName;

                            $inputType =
                                $definition['type']
                                ?? 'text';

                        } else {

                            $label =
                                is_string($definition)
                                    ? $definition
                                    : $fieldName;

                            $inputType = 'text';
                        }


                        $fieldKey =
                            is_string($fieldName)
                                ? $fieldName
                                : $label;


                        $existingValue =
                            $existingFields[$fieldKey]
                            ?? '';


                        $value =
                            old(
                                'fields.' . $fieldKey,
                                $existingValue
                            );


                        $sensitive =
                            $isSensitiveField($label);


                        if ($sensitive) {
                            $inputType = 'password';
                        }

                    @endphp


                    <div class="pdv-field">

                        <span class="pdv-field-index">

                            {{
                                str_pad(
                                    $loop->iteration,
                                    2,
                                    '0',
                                    STR_PAD_LEFT
                                )
                            }}

                        </span>


                        <label
                            class="pdv-label"
                            for="field-{{ $loop->index }}"
                        >

                            {{ $label }}

                        </label>


                        <div class="pdv-field-control">

                            <input
                                id="field-{{ $loop->index }}"
                                type="{{ $inputType }}"
                                name="fields[{{ $fieldKey }}]"
                                class="pdv-input"
                                value="{{ $value }}"
                                autocomplete="off"
                                placeholder="ENTER {{ strtoupper($label) }}"
                            >


                            @if ($sensitive)

                                <button
                                    type="button"
                                    class="pdv-toggle-secret"
                                    onclick="toggleInputSecret(this)"
                                    title="Tampilkan / sembunyikan"
                                    aria-label="Tampilkan / sembunyikan {{ $label }}"
                                >
                                    👁
                                </button>

                            @endif


                            <div class="pdv-focus-line"></div>

                        </div>

                    </div>

                @endforeach

            </div>


            <!-- =============================================
                 NOTES
            ============================================== -->

            <div class="pdv-notes-section">

                <label
                    class="pdv-label"
                    for="notes"
                >
                    NOTES //
                </label>

                <textarea
                    id="notes"
                    name="notes"
                    class="pdv-textarea"
                    placeholder="OPTIONAL // ADD SECURE NOTES..."
                >{{ old('notes', $itemNotes) }}</textarea>

            </div>


            <!-- =============================================
                 ACTIONS
            ============================================== -->

            <div class="pdv-actions">

                <a
                    href="{{ route(
                        'vault.category',
                        $category
                    ) }}"
                    class="pdv-action pdv-cancel"
                >
                    ← ABORT
                </a>


                <button
                    type="submit"
                    class="pdv-action pdv-submit"
                >
                    {{ $submitLabel }}
                </button>

            </div>

        </form>


        <!-- =================================================
             FOOTER
        ================================================== -->

        <div class="pdv-terminal-footer">

            <span>
                PDV // SECURE INPUT TERMINAL
            </span>

            <span>
                AES-256-GCM // ENCRYPTED STORAGE
            </span>

        </div>

    </main>


    <!-- =====================================================
         JAVASCRIPT
    ===================================================== -->

    <script>

        /* =====================================================
           SHOW / HIDE PASSWORD
        ===================================================== */

        function toggleInputSecret(button) {

            const container =
                button.parentElement;

            const input =
                container.querySelector('input');


            if (input.type === 'password') {

                input.type = 'text';

                button.textContent = '🙈';

            } else {

                input.type = 'password';

                button.textContent = '👁';

            }
        }


        /* =====================================================
           MATRIX
        ===================================================== */

        const matrixCanvas =
            document.getElementById('pdv-matrix');

        const matrixContext =
            matrixCanvas.getContext('2d');


        let matrixWidth;
        let matrixHeight;
        let matrixColumns;
        let matrixDrops;


        const matrixCharacters =
            '01ABCDEFGHIJKLMNOPQRSTUVWXYZ#$%&<>[]{}';


        function resizeMatrix() {

            matrixWidth =
                matrixCanvas.width =
                window.innerWidth;

            matrixHeight =
                matrixCanvas.height =
                window.innerHeight;


            const fontSize = 13;


            matrixColumns =
                Math.floor(
                    matrixWidth / fontSize
                );


            matrixDrops =
                Array(matrixColumns)
                    .fill(0)
                    .map(function () {

                        return (
                            Math.random() * -100
                        );

                    });
        }


        function drawMatrix() {

            matrixContext.fillStyle =
                'rgba(1, 3, 2, 0.08)';


            matrixContext.fillRect(
                0,
                0,
                matrixWidth,
                matrixHeight
            );


            const fontSize = 13;


            matrixContext.font =
                fontSize + 'px monospace';


            for (
                let i = 0;
                i < matrixDrops.length;
                i++
            ) {

                const character =
                    matrixCharacters[
                        Math.floor(
                            Math.random() *
                            matrixCharacters.length
                        )
                    ];


                const x =
                    i * fontSize;


                const y =
                    matrixDrops[i] *
                    fontSize;


                const random =
                    Math.random();


                if (random > 0.985) {

                    matrixContext.fillStyle =
                        '#03e6f2';

                } else if (random > 0.965) {

                    matrixContext.fillStyle =
                        '#00f815';

                } else {

                    matrixContext.fillStyle =
                        'rgba(0, 248, 21, 0.62)';
                }


                matrixContext.fillText(
                    character,
                    x,
                    y
                );


                if (
                    y > matrixHeight &&
                    Math.random() > 0.975
                ) {

                    matrixDrops[i] = 0;
                }


                matrixDrops[i]++;
            }


            requestAnimationFrame(
                drawMatrix
            );
        }


        window.addEventListener(
            'resize',
            resizeMatrix
        );


        resizeMatrix();

        drawMatrix();


        /* =====================================================
           PREVENT ACCIDENTAL DOUBLE SUBMIT
        ===================================================== */

        const form =
            document.querySelector(
                '.pdv-form-terminal'
            );


        if (form) {

            form.addEventListener(
                'submit',
                function () {

                    const submit =
                        form.querySelector(
                            '.pdv-submit'
                        );


                    if (submit) {

                        submit.disabled = true;

                        submit.textContent =
                            'ENCRYPTING...';

                        submit.style.opacity =
                            '0.65';

                        submit.style.cursor =
                            'wait';
                    }

                }
            );

        }

    </script>

</body>

</html>
