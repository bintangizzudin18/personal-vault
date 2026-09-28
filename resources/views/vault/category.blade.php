<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        PDV // {{ strtoupper(str_replace('_', ' ', $category)) }}
    </title>


    <style>

        /* =====================================================
           PDV // DIRECTORY TERMINAL
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
           BASE
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


        button,
        input,
        textarea,
        select {
            font: inherit;
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

            opacity: 0.18;

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
           MAIN
        ===================================================== */

        .pdv-wrapper {
            position: relative;

            z-index: 10;

            width: 100%;
            max-width: 1250px;

            margin: 0 auto;

            padding:
                30px 25px 60px;
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

            border:
                1px solid var(--border);

            background:
                rgba(7, 13, 9, 0.78);

            box-shadow:
                0 0 25px rgba(0, 248, 21, 0.025);

            margin-bottom: 20px;
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

            white-space: nowrap;
        }


        .pdv-status-dot {
            width: 6px;
            height: 6px;

            flex-shrink: 0;

            background: var(--green);

            box-shadow:
                0 0 9px rgba(0, 248, 21, 0.8);

            animation:
                pdv-pulse 2s infinite;
        }


        /* =====================================================
           CATEGORY HEADER
        ===================================================== */

        .pdv-header {
            display: flex;

            justify-content: space-between;
            align-items: flex-end;

            gap: 25px;

            padding: 28px 30px;

            border:
                1px solid var(--border-strong);

            background:
                linear-gradient(
                    135deg,
                    rgba(0, 248, 21, 0.035),
                    rgba(7, 13, 9, 0.92)
                );

            position: relative;

            overflow: hidden;
        }


        .pdv-header::before {
            content: "";

            position: absolute;

            left: 0;
            top: 0;

            width: 4px;
            height: 100%;

            background:
                var(--green);

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


        .pdv-directory-code {
            color: var(--green-dark);

            font-size: 9px;

            letter-spacing: 2px;

            margin-bottom: 9px;
        }


        .pdv-header h1 {
            margin: 0;

            color: var(--white);

            font-size:
                clamp(25px, 4vw, 38px);

            letter-spacing: -1px;

            word-break: break-word;
        }


        .pdv-header h1 span {
            color: var(--green);

            text-shadow:
                0 0 12px rgba(0, 248, 21, 0.25);
        }


        .pdv-header-description {
            margin-top: 9px;

            color: var(--gray);

            font-size: 10px;

            letter-spacing: 1px;
        }


        .pdv-item-count {
            min-width: 120px;

            padding: 15px;

            border:
                1px solid var(--border);

            background:
                rgba(0, 248, 21, 0.025);

            text-align: center;
        }


        .pdv-item-count-label {
            display: block;

            color: var(--gray-dark);

            font-size: 8px;

            letter-spacing: 1.5px;

            margin-bottom: 7px;
        }


        .pdv-item-count-number {
            display: block;

            color: var(--green);

            font-size: 23px;

            font-weight: bold;
        }


        /* =====================================================
           TOOLBAR
        ===================================================== */

        .pdv-toolbar {
            display: flex;

            justify-content: space-between;
            align-items: center;

            gap: 15px;

            margin-top: 18px;
            margin-bottom: 20px;
        }


        .pdv-toolbar-left {
            display: flex;

            gap: 10px;

            align-items: center;
        }


        .pdv-button {
            display: inline-flex;

            align-items: center;
            justify-content: center;

            min-height: 40px;

            padding: 0 16px;

            border:
                1px solid var(--green);

            background:
                rgba(0, 248, 21, 0.045);

            color: var(--green);

            font-family:
                "Courier New",
                Courier,
                monospace;

            font-size: 10px;

            font-weight: bold;

            letter-spacing: 1.5px;

            text-decoration: none;

            cursor: pointer;

            transition:
                transform 0.2s ease,
                background 0.2s ease,
                color 0.2s ease,
                box-shadow 0.2s ease;
        }


        .pdv-button:hover {
            background:
                var(--green);

            color:
                var(--black);

            box-shadow:
                0 0 18px rgba(0, 248, 21, 0.22);
        }


        .pdv-button:active {
            transform:
                translateY(1px);
        }


        .pdv-back-button {
            border-color:
                var(--border);

            color:
                var(--gray);
        }


        .pdv-back-button:hover {
            border-color:
                var(--green);

            color:
                var(--green);

            background:
                rgba(0, 248, 21, 0.035);
        }


        /* =====================================================
           MESSAGES
        ===================================================== */

        .pdv-message {
            padding:
                13px 16px;

            margin-bottom:
                18px;

            border-left:
                2px solid;

            font-size:
                10px;

            letter-spacing:
                0.5px;
        }


        .pdv-success {
            color:
                var(--green);

            border-color:
                var(--green);

            background:
                rgba(0, 248, 21, 0.045);
        }


        .pdv-error {
            color:
                #ff7888;

            border-color:
                var(--red);

            background:
                rgba(255, 64, 87, 0.045);
        }


        .pdv-message-label {
            margin-right:
                8px;

            font-weight:
                bold;
        }


        /* =====================================================
           ITEM GRID
        ===================================================== */

        .pdv-items {
            display: grid;

            grid-template-columns:
                repeat(2, minmax(0, 1fr));

            gap: 18px;
        }


        /* =====================================================
           ITEM CARD
        ===================================================== */

        .pdv-item {
            position: relative;

            padding: 21px;

            border:
                1px solid var(--border);

            background:
                var(--panel);

            overflow: hidden;

            transition:
                transform 0.22s ease,
                border-color 0.22s ease,
                box-shadow 0.22s ease,
                background 0.22s ease;
        }


        .pdv-item:hover {
            transform:
                translateY(-4px);

            border-color:
                rgba(0, 248, 21, 0.48);

            background:
                rgba(8, 16, 10, 0.94);

            box-shadow:
                0 8px 35px rgba(0, 0, 0, 0.45),
                0 0 25px rgba(0, 248, 21, 0.07);
        }


        .pdv-item::before {
            content: "";

            position: absolute;

            top: 0;
            left: 0;

            width: 45px;
            height: 2px;

            background:
                var(--green);

            box-shadow:
                0 0 10px rgba(0, 248, 21, 0.35);

            transition:
                width 0.3s ease,
                box-shadow 0.3s ease;
        }


        .pdv-item:hover::before {
            width: 85px;

            box-shadow:
                0 0 18px rgba(0, 248, 21, 0.55);
        }


        .pdv-item::after {
            content: "";

            position: absolute;

            right: 0;
            bottom: 0;

            width: 22px;
            height: 22px;

            border-right:
                1px solid var(--green-dark);

            border-bottom:
                1px solid var(--green-dark);
        }


        /* =====================================================
           ITEM HEADER
        ===================================================== */

        .pdv-item-header {
            display: flex;

            align-items: flex-start;
            justify-content: space-between;

            gap: 15px;

            padding-bottom: 17px;

            border-bottom:
                1px solid var(--border);
        }


        .pdv-item-index {
            color:
                var(--green-dark);

            font-size:
                8px;

            letter-spacing:
                1px;

            margin-bottom:
                5px;
        }


        .pdv-item-title {
            margin: 0;

            color:
                var(--white);

            font-size:
                17px;

            font-weight:
                bold;

            word-break:
                break-word;

            transition:
                color 0.2s ease,
                text-shadow 0.2s ease;
        }


        .pdv-item:hover .pdv-item-title {
            color:
                var(--green);

            text-shadow:
                0 0 10px rgba(0, 248, 21, 0.18);
        }


        .pdv-item-status {
            display: flex;

            align-items: center;

            gap: 5px;

            color:
                var(--green);

            font-size:
                8px;

            letter-spacing:
                1px;

            white-space:
                nowrap;
        }


        .pdv-item-status-dot {
            width: 5px;
            height: 5px;

            background:
                var(--green);

            box-shadow:
                0 0 6px rgba(0, 248, 21, 0.7);

            transition:
                box-shadow 0.2s ease,
                transform 0.2s ease;
        }


        .pdv-item:hover
        .pdv-item-status-dot {
            transform:
                scale(1.35);

            box-shadow:
                0 0 10px rgba(0, 248, 21, 0.95);
        }


        /* =====================================================
           FIELDS
        ===================================================== */

        .pdv-field {
            margin-top:
                16px;

            transition:
                transform 0.18s ease;
        }


        .pdv-field:hover {
            transform:
                translateX(2px);
        }


        .pdv-field-name {
            margin-bottom:
                7px;

            color:
                var(--gray);

            font-size:
                9px;

            letter-spacing:
                1.5px;

            text-transform:
                uppercase;
        }


        .pdv-field-row {
            display: flex;

            align-items:
                stretch;

            gap:
                6px;
        }


        .pdv-field-value {
            flex: 1;

            min-width:
                0;

            min-height:
                39px;

            display:
                flex;

            align-items:
                center;

            padding:
                9px 11px;

            border:
                1px solid rgba(0, 248, 21, 0.10);

            background:
                rgba(0, 0, 0, 0.38);

            color:
                var(--white);

            font-size:
                10px;

            line-height:
                1.5;

            white-space:
                pre-wrap;

            word-break:
                break-word;

            overflow-wrap:
                anywhere;

            transition:
                border-color 0.18s ease,
                background 0.18s ease,
                color 0.15s ease,
                text-shadow 0.15s ease;
        }


        .pdv-field:hover
        .pdv-field-value {
            border-color:
                rgba(0, 248, 21, 0.22);

            background:
                rgba(0, 248, 21, 0.025);
        }


        .pdv-field-value.secret {
            color:
                var(--green);

            font-family:
                "Courier New",
                Courier,
                monospace;

            letter-spacing:
                2px;
        }


        .pdv-field-value.secret.revealed {
            color:
                var(--cyan);

            letter-spacing:
                1px;

            text-shadow:
                0 0 8px rgba(3, 230, 242, 0.35);
        }


        /* =====================================================
           FIELD BUTTON
        ===================================================== */

        .pdv-field-button {
            flex-shrink:
                0;

            min-width:
                39px;

            padding:
                0 10px;

            border:
                1px solid var(--border);

            background:
                rgba(0, 248, 21, 0.025);

            color:
                var(--gray);

            font-family:
                "Courier New",
                Courier,
                monospace;

            font-size:
                9px;

            cursor:
                pointer;

            transition:
                transform 0.12s ease,
                background 0.18s ease,
                border-color 0.18s ease,
                color 0.18s ease,
                box-shadow 0.18s ease;
        }


        .pdv-field-button:hover {
            color:
                var(--green);

            border-color:
                rgba(0, 248, 21, 0.45);

            background:
                rgba(0, 248, 21, 0.055);
        }


        .pdv-field-button:active {
            transform:
                translateY(1px);
        }


        .pdv-field-button.copy-success {
            color:
                var(--cyan);

            border-color:
                rgba(3, 230, 242, 0.55);

            background:
                rgba(3, 230, 242, 0.06);

            box-shadow:
                0 0 12px rgba(3, 230, 242, 0.08);
        }


        /* =====================================================
           TERMINAL FLASH
        ===================================================== */

        .pdv-flash {
            animation:
                pdv-terminal-flash 0.28s ease;
        }


        @keyframes pdv-terminal-flash {

            0% {
                background:
                    rgba(3, 230, 242, 0.14);
            }

            100% {
                background:
                    rgba(0, 0, 0, 0.38);
            }
        }


        /* =====================================================
           NOTES
        ===================================================== */

        .pdv-notes {
            margin-top:
                20px;

            padding-top:
                17px;

            border-top:
                1px solid var(--border);
        }


        .pdv-notes-label {
            margin-bottom:
                8px;

            color:
                var(--gray);

            font-size:
                9px;

            letter-spacing:
                1.5px;
        }


        /* =====================================================
           ITEM ACTIONS
        ===================================================== */

        .pdv-actions {
            display:
                flex;

            gap:
                7px;

            margin-top:
                21px;

            padding-top:
                17px;

            border-top:
                1px solid var(--border);
        }


        .pdv-action {
            flex: 1;

            min-height:
                35px;

            display:
                flex;

            align-items:
                center;

            justify-content:
                center;

            padding:
                0 10px;

            border:
                1px solid var(--border);

            background:
                rgba(0, 248, 21, 0.018);

            color:
                var(--gray);

            font-family:
                "Courier New",
                Courier,
                monospace;

            font-size:
                9px;

            letter-spacing:
                1px;

            text-decoration:
                none;

            cursor:
                pointer;

            transition:
                transform 0.12s ease,
                background 0.18s ease,
                border-color 0.18s ease,
                color 0.18s ease,
                box-shadow 0.18s ease;
        }


        .pdv-action:hover {
            color:
                var(--green);

            border-color:
                rgba(0, 248, 21, 0.45);

            background:
                rgba(0, 248, 21, 0.045);
        }


        .pdv-action:active {
            transform:
                translateY(1px);
        }


        .pdv-action-delete {
            color:
                #c96a76;
        }


        .pdv-action-delete:hover {
            color:
                var(--red);

            border-color:
                rgba(255, 64, 87, 0.45);

            background:
                rgba(255, 64, 87, 0.045);

            box-shadow:
                0 0 15px rgba(255, 64, 87, 0.07);
        }


        .pdv-actions form {
            flex: 1;

            display:
                flex;
        }


        .pdv-actions form .pdv-action {
            width:
                100%;
        }


        /* =====================================================
           EMPTY STATE
        ===================================================== */

        .pdv-empty {
            padding:
                70px 25px;

            border:
                1px dashed rgba(0, 248, 21, 0.20);

            background:
                rgba(7, 13, 9, 0.68);

            text-align:
                center;
        }


        .pdv-empty-icon {
            margin-bottom:
                18px;

            color:
                var(--green);

            font-size:
                32px;

            opacity:
                0.65;
        }


        .pdv-empty-title {
            color:
                var(--white);

            font-size:
                13px;

            letter-spacing:
                2px;
        }


        .pdv-empty-text {
            margin-top:
                9px;

            color:
                var(--gray-dark);

            font-size:
                9px;

            letter-spacing:
                1px;
        }


        /* =====================================================
           FOOTER
        ===================================================== */

        .pdv-footer {
            display:
                flex;

            justify-content:
                space-between;

            gap:
                15px;

            margin-top:
                25px;

            padding:
                13px 2px;

            color:
                var(--gray-dark);

            font-size:
                8px;

            letter-spacing:
                1px;
        }


        .pdv-footer span:first-child {
            color:
                var(--green-dark);
        }


        /* =====================================================
           ANIMATION
        ===================================================== */

        @keyframes pdv-pulse {

            0%,
            100% {
                opacity:
                    1;
            }

            50% {
                opacity:
                    0.35;
            }
        }


        /* =====================================================
           RESPONSIVE
        ===================================================== */

        @media (max-width: 850px) {

            .pdv-items {
                grid-template-columns:
                    1fr;
            }
        }


        @media (max-width: 600px) {

            .pdv-wrapper {
                padding:
                    15px 12px 40px;
            }


            .pdv-topbar {
                margin-bottom:
                    12px;

                align-items:
                    flex-start;

                flex-direction:
                    column;
            }


            .pdv-system-status {
                font-size:
                    8px;
            }


            .pdv-header {
                padding:
                    24px 20px;

                align-items:
                    flex-start;

                flex-direction:
                    column;
            }


            .pdv-item-count {
                width:
                    100%;
            }


            .pdv-toolbar {
                align-items:
                    stretch;

                flex-direction:
                    column;
            }


            .pdv-toolbar-left {
                width:
                    100%;
            }


            .pdv-toolbar-left .pdv-button {
                flex:
                    1;
            }


            .pdv-back-button {
                width:
                    100%;
            }


            .pdv-item-header {
                gap:
                    10px;
            }


            .pdv-item-status {
                font-size:
                    7px;
            }


            .pdv-field-row {
                flex-wrap:
                    wrap;
            }


            .pdv-field-value {
                width:
                    100%;

                flex-basis:
                    100%;
            }


            .pdv-field-button {
                flex:
                    1;

                min-height:
                    35px;
            }


            .pdv-actions {
                flex-direction:
                    column;
            }


            .pdv-actions form {
                width:
                    100%;
            }


            .pdv-footer {
                flex-direction:
                    column;

                gap:
                    7px;
            }


            .pdv-item:hover {
                transform:
                    translateY(-2px);
            }
        }


        @media (prefers-reduced-motion: reduce) {

            *,
            *::before,
            *::after {
                animation:
                    none !important;

                transition:
                    none !important;
            }
        }

    </style>

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
             CATEGORY INDEX
        ================================================== -->

        @php

            $categoryNames = [
                'recovery_security',
                'm_banking',
                'e_wallet',
                'email',
                'social_media',
                'game',
                'education',
                'work_freelance',
                'shopping',
                'devices_services',
                'investment_finance',
                'identity_personal',
                'documents',
                'notes',
            ];


            $categoryIndex = array_search(
                $category,
                $categoryNames,
                true
            );


            $directoryNumber =
                $categoryIndex !== false
                    ? $categoryIndex + 1
                    : 0;

        @endphp


        <!-- =================================================
             CATEGORY HEADER
        ================================================== -->

        <section class="pdv-header">


            <div>

                <div class="pdv-directory-code">

                    DIRECTORY //

                    {{ str_pad(
                        $directoryNumber,
                        2,
                        '0',
                        STR_PAD_LEFT
                    ) }}

                </div>


                <h1>

                    <span>//</span>

                    {{ strtoupper(
                        str_replace(
                            '_',
                            ' ',
                            $category
                        )
                    ) }}

                </h1>


                <div class="pdv-header-description">

                    SECURE OBJECT DIRECTORY //

                    ENCRYPTED STORAGE

                </div>

            </div>


            <div class="pdv-item-count">

                <span class="pdv-item-count-label">
                    STORED OBJECTS
                </span>


                <span class="pdv-item-count-number">
                    {{ count($items) }}
                </span>

            </div>

        </section>


        <!-- =================================================
             MESSAGES
        ================================================== -->

        @if (session('success'))

            <div class="pdv-message pdv-success">

                <span class="pdv-message-label">
                    [ SUCCESS ]
                </span>

                {{ session('success') }}

            </div>

        @endif


        @if ($errors->any())

            <div class="pdv-message pdv-error">

                <span class="pdv-message-label">
                    [ ERROR ]
                </span>

                {{ $errors->first() }}

            </div>

        @endif


        <!-- =================================================
             TOOLBAR
        ================================================== -->

        <div class="pdv-toolbar">


            <div class="pdv-toolbar-left">

                <a
                    class="pdv-button"
                    href="{{ route(
                        'vault.item.create',
                        $category
                    ) }}"
                >

                    + NEW ENTRY

                </a>

            </div>


            <a
                class="pdv-button pdv-back-button"
                href="{{ route(
                    'vault.dashboard'
                ) }}"
            >

                ← DASHBOARD

            </a>

        </div>


        <!-- =================================================
             ITEMS
        ================================================== -->

        @if (count($items) === 0)


            <div class="pdv-empty">


                <div class="pdv-empty-icon">
                    [ _ ]
                </div>


                <div class="pdv-empty-title">
                    DIRECTORY EMPTY
                </div>


                <div class="pdv-empty-text">
                    NO SECURE OBJECTS FOUND IN THIS DIRECTORY
                </div>


            </div>


        @else


            <section class="pdv-items">


                @foreach ($items as $index => $item)


                    <article class="pdv-item">


                        <!-- =====================================
                             ITEM HEADER
                        ====================================== -->

                        <div class="pdv-item-header">


                            <div>


                                <div class="pdv-item-index">

                                    OBJECT //

                                    {{ str_pad(
                                        $index + 1,
                                        3,
                                        '0',
                                        STR_PAD_LEFT
                                    ) }}

                                </div>


                                <h2 class="pdv-item-title">

                                    {{ $item['title']
                                        ?? 'UNTITLED OBJECT'
                                    }}

                                </h2>


                            </div>


                            <div class="pdv-item-status">

                                <span
                                    class="pdv-item-status-dot"
                                ></span>

                                STORED

                            </div>


                        </div>


                        <!-- =====================================
                             FIELDS
                        ====================================== -->

                        @foreach (
                            $item['fields'] ?? []
                            as $name => $value
                        )


                            @php

                                $lowerName =
                                    strtolower($name);


                                $isSensitive =
                                    str_contains(
                                        $lowerName,
                                        'password'
                                    )
                                    ||
                                    str_contains(
                                        $lowerName,
                                        'pin'
                                    )
                                    ||
                                    str_contains(
                                        $lowerName,
                                        'recovery code'
                                    );

                            @endphp


                            <div class="pdv-field">


                                <div class="pdv-field-name">

                                    {{ $name }}

                                </div>


                                <div class="pdv-field-row">


                                    @if ($isSensitive)


                                        <div
                                            class="
                                                pdv-field-value
                                                secret
                                            "
                                            data-secret="{{ $value }}"
                                            data-visible="false"
                                        >

                                            ••••••••••••

                                        </div>


                                        <button
                                            type="button"
                                            class="pdv-field-button"
                                            onclick="toggleSecret(this)"
                                            title="Tampilkan / sembunyikan"
                                        >

                                            👁

                                        </button>


                                        <button
                                            type="button"
                                            class="pdv-field-button"
                                            onclick="copySecret(this)"
                                            title="Salin"
                                        >

                                            COPY

                                        </button>


                                    @else


                                        <div class="pdv-field-value">

                                            {{ $value }}

                                        </div>


                                        @if ($value !== '')

                                            <button
                                                type="button"
                                                class="pdv-field-button"
                                                onclick="copyValue(this)"
                                                title="Salin"
                                            >

                                                COPY

                                            </button>

                                        @endif


                                    @endif


                                </div>


                            </div>


                        @endforeach


                        <!-- =====================================
                             NOTES
                        ====================================== -->

                        @if (!empty($item['notes']))


                            <div class="pdv-notes">


                                <div class="pdv-notes-label">

                                    NOTES //

                                </div>


                                <div class="pdv-field-value">

                                    {{ $item['notes'] }}

                                </div>


                            </div>


                        @endif


                        <!-- =====================================
                             ACTIONS
                        ====================================== -->

                        <div class="pdv-actions">


                            <a
                                class="pdv-action"
                                href="{{ route(
                                    'vault.item.edit',
                                    [
                                        $category,
                                        $item['id']
                                    ]
                                ) }}"
                            >

                                EDIT

                            </a>


                            <form
                                method="POST"
                                action="{{ route(
                                    'vault.item.destroy',
                                    [
                                        $category,
                                        $item['id']
                                    ]
                                ) }}"
                                onsubmit="return confirm(
                                    'Yakin ingin menghapus item ini?'
                                );"
                            >

                                @csrf

                                @method('DELETE')


                                <button
                                    type="submit"
                                    class="
                                        pdv-action
                                        pdv-action-delete
                                    "
                                >

                                    DELETE

                                </button>


                            </form>


                        </div>


                    </article>


                @endforeach


            </section>


        @endif


        <!-- =================================================
             FOOTER
        ================================================== -->

        <footer class="pdv-footer">


            <span>
                PDV // SECURE DIRECTORY
            </span>


            <span>
                AES-256-GCM // ACCESS GRANTED
            </span>


        </footer>


    </main>


    <!-- =====================================================
         JAVASCRIPT
    ===================================================== -->

    <script>


        /* =====================================================
           PDV // SECRET REVEAL
        ===================================================== */

        function toggleSecret(button) {

            const row =
                button.parentElement;


            const field =
                row.querySelector(
                    ".pdv-field-value"
                );


            const visible =
                field.dataset.visible === "true";


            if (visible) {

                field.textContent =
                    "••••••••••••";

                field.dataset.visible =
                    "false";

                field.classList.remove(
                    "revealed"
                );

                button.textContent =
                    "👁";

            } else {

                field.textContent =
                    field.dataset.secret;

                field.dataset.visible =
                    "true";

                field.classList.add(
                    "revealed"
                );

                button.textContent =
                    "🙈";


                field.classList.remove(
                    "pdv-flash"
                );


                void field.offsetWidth;


                field.classList.add(
                    "pdv-flash"
                );

            }

        }


        /* =====================================================
           PDV // COPY SECRET
        ===================================================== */

        function copySecret(button) {

            const row =
                button.parentElement;


            const field =
                row.querySelector(
                    ".pdv-field-value"
                );


            const value =
                field.dataset.secret;


            copyToClipboard(
                value,
                button
            );

        }


        /* =====================================================
           PDV // COPY NORMAL VALUE
        ===================================================== */

        function copyValue(button) {

            const row =
                button.parentElement;


            const field =
                row.querySelector(
                    ".pdv-field-value"
                );


            const value =
                field.textContent.trim();


            copyToClipboard(
                value,
                button
            );

        }


        /* =====================================================
           PDV // CLIPBOARD
        ===================================================== */

        function copyToClipboard(
            value,
            button
        ) {

            if (
                !navigator.clipboard ||
                !navigator.clipboard.writeText
            ) {

                button.textContent =
                    "ERROR";

                return;
            }


            navigator.clipboard
                .writeText(value)
                .then(function () {


                    const originalText =
                        button.textContent;


                    button.textContent =
                        "COPIED ✓";


                    button.classList.add(
                        "copy-success"
                    );


                    setTimeout(
                        function () {

                            button.textContent =
                                originalText;

                            button.classList.remove(
                                "copy-success"
                            );

                        },
                        1500
                    );

                })
                .catch(function () {


                    const originalText =
                        button.textContent;


                    button.textContent =
                        "ERROR";


                    setTimeout(
                        function () {

                            button.textContent =
                                originalText;

                        },
                        1500
                    );

                });

        }


        /* =====================================================
           PDV // MATRIX BACKGROUND
        ===================================================== */

        const matrixCanvas =
            document.getElementById(
                "pdv-matrix"
            );


        const matrixContext =
            matrixCanvas.getContext(
                "2d"
            );


        let matrixWidth;
        let matrixHeight;
        let matrixColumns;
        let matrixDrops;


        const matrixCharacters =
            "01ABCDEFGHIJKLMNOPQRSTUVWXYZ#$%&<>[]{}";


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
                    matrixWidth /
                    fontSize
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
                "rgba(1, 3, 2, 0.08)";


            matrixContext.fillRect(
                0,
                0,
                matrixWidth,
                matrixHeight
            );


            const fontSize = 13;


            matrixContext.font =
                fontSize +
                "px monospace";


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


                /*
                 * Cyan
                 */

                if (random > 0.985) {

                    matrixContext.fillStyle =
                        "#03e6f2";


                /*
                 * Bright green
                 */

                } else if (random > 0.965) {

                    matrixContext.fillStyle =
                        "#00f815";


                /*
                 * Normal green
                 */

                } else {

                    matrixContext.fillStyle =
                        "rgba(0, 248, 21, 0.62)";
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

                    matrixDrops[i] =
                        0;

                }


                matrixDrops[i]++;

            }


            requestAnimationFrame(
                drawMatrix
            );

        }


        window.addEventListener(
            "resize",
            resizeMatrix
        );


        resizeMatrix();

        drawMatrix();

    </script>


</body>

</html>
