<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>PDV // Control Terminal</title>

    <style>
        * {
            box-sizing: border-box;
        }

        :root {
            --black: #010302;
            --black-soft: #050806;
            --panel: rgba(7, 13, 9, 0.88);

            --green: #00f815;
            --green-soft: #059e12;
            --green-dark: #1e6025;

            --cyan: #03e6f2;

            --white: #f8f8f8;
            --gray: #9aa29e;
            --gray-dark: #505379;

            --red: #ff4057;

            --border: rgba(0, 248, 21, 0.18);
        }

        html {
            background: var(--black);
        }

        body {
            margin: 0;
            min-height: 100vh;

            background:
                radial-gradient(
                    circle at 50% 0%,
                    rgba(0, 248, 21, 0.06),
                    transparent 35%
                ),
                var(--black);

            color: var(--white);

            font-family:
                "Courier New",
                Consolas,
                monospace;

            overflow-x: hidden;
        }

        /* =========================================
           BACKGROUND
        ========================================== */

        #matrix {
            position: fixed;
            inset: 0;

            width: 100%;
            height: 100%;

            z-index: 0;

            opacity: 0.25;

            pointer-events: none;

            display: block;
        }

        .scanlines {
            position: fixed;
            inset: 0;

            z-index: 1;

            pointer-events: none;

            background:
                repeating-linear-gradient(
                    to bottom,
                    rgba(255, 255, 255, 0.018) 0px,
                    rgba(255, 255, 255, 0.018) 1px,
                    transparent 1px,
                    transparent 5px
                );
        }

        .grid {
            position: fixed;
            inset: 0;

            z-index: 1;

            pointer-events: none;

            opacity: 0.07;

            background-image:
                linear-gradient(
                    rgba(0, 248, 21, 0.18) 1px,
                    transparent 1px
                ),
                linear-gradient(
                    90deg,
                    rgba(0, 248, 21, 0.18) 1px,
                    transparent 1px
                );

            background-size: 70px 70px;

            mask-image:
                linear-gradient(
                    to bottom,
                    black,
                    transparent 80%
                );
        }

        .vignette {
            position: fixed;
            inset: 0;

            z-index: 2;

            pointer-events: none;

            background:
                radial-gradient(
                    ellipse at center,
                    transparent 35%,
                    rgba(0, 0, 0, 0.82) 100%
                );
        }

        /* =========================================
           LAYOUT
        ========================================== */

        .container {
            position: relative;

            z-index: 10;

            width: min(
                calc(100% - 30px),
                1180px
            );

            margin: 0 auto;

            padding: 30px 0 50px;
        }

        /* =========================================
           TOP NAV
        ========================================== */

        .topbar {
            display: flex;

            justify-content: space-between;

            align-items: center;

            gap: 20px;

            padding: 12px 0 24px;

            border-bottom: 1px solid var(--border);
        }

        .brand {
            display: flex;

            align-items: center;

            gap: 14px;
        }

        .logo {
            width: 45px;
            height: 45px;

            display: flex;

            align-items: center;

            justify-content: center;

            border: 1px solid var(--green);

            color: var(--green);

            background:
                rgba(0, 248, 21, 0.035);

            box-shadow:
                0 0 25px
                rgba(0, 248, 21, 0.09);

            font-size: 19px;
        }

        .brand-title {
            margin: 0;

            color: var(--white);

            font-size: 18px;

            letter-spacing: 2px;
        }

        .brand-title span {
            color: var(--green);
        }

        .brand-subtitle {
            margin: 5px 0 0;

            color: var(--gray);

            font-size: 10px;

            letter-spacing: 2px;
        }

        .lock-button {
            border:
                1px solid
                rgba(255, 64, 87, 0.35);

            background:
                rgba(255, 64, 87, 0.04);

            color: var(--red);

            padding: 10px 15px;

            font-family: inherit;

            font-size: 11px;

            letter-spacing: 1px;

            cursor: pointer;

            transition: 0.2s;
        }

        .lock-button:hover {
            border-color: var(--red);

            background:
                rgba(255, 64, 87, 0.08);

            box-shadow:
                0 0 20px
                rgba(255, 64, 87, 0.08);
        }

        /* =========================================
           HERO
        ========================================== */

        .hero {
            display: grid;

            grid-template-columns:
                1.5fr 1fr;

            gap: 20px;

            margin-top: 25px;
        }

        .hero-panel {
            position: relative;

            min-height: 310px;

            padding: 35px;

            overflow: hidden;

            border:
                1px solid
                var(--border);

            background:
                linear-gradient(
                    135deg,
                    rgba(0, 248, 21, 0.025),
                    rgba(3, 230, 242, 0.018)
                );

            box-shadow:
                0 0 50px
                rgba(0, 0, 0, 0.35);
        }

        .hero-panel::before {
            content: "";

            position: absolute;

            top: 0;
            left: 0;

            width: 90px;
            height: 1px;

            background: var(--green);

            box-shadow:
                0 0 12px
                var(--green);
        }

        .hero-panel::after {
            content: "";

            position: absolute;

            right: 0;
            bottom: 0;

            width: 120px;
            height: 1px;

            background: var(--cyan);

            opacity: 0.6;
        }

        .eyebrow {
            color: var(--green);

            font-size: 10px;

            letter-spacing: 3px;

            margin-bottom: 22px;
        }

        .hero-title {
            margin: 0;

            max-width: 650px;

            font-size:
                clamp(
                    34px,
                    5vw,
                    64px
                );

            line-height: 0.95;

            letter-spacing: -2px;

            text-transform: uppercase;
        }

        .hero-title span {
            color: var(--green);

            text-shadow:
                0 0 25px
                rgba(0, 248, 21, 0.25);
        }

        .hero-description {
            max-width: 580px;

            margin-top: 25px;

            color: var(--gray);

            font-size: 12px;

            line-height: 1.8;
        }

        .terminal-command {
            position: absolute;

            bottom: 25px;
            left: 35px;

            color: var(--gray);

            font-size: 11px;
        }

        .terminal-command strong {
            color: var(--green);
        }

        .cursor {
            display: inline-block;

            width: 7px;
            height: 13px;

            margin-left: 4px;

            background: var(--green);

            vertical-align: middle;

            animation:
                blink 1s infinite;
        }

        @keyframes blink {
            0%,
            45% {
                opacity: 1;
            }

            46%,
            100% {
                opacity: 0;
            }
        }

        /* =========================================
           PROFILE + STATUS
        ========================================== */

        .profile-status-panel {
            display: flex;

            flex-direction: column;

            gap: 12px;
        }

        .profile-photo-wrapper {
            position: relative;

            padding: 18px;

            border:
                1px solid
                var(--border);

            background:
                rgba(4, 9, 6, 0.88);

            overflow: hidden;
        }

        .profile-photo-header {
            display: flex;

            justify-content: space-between;

            align-items: center;

            margin-bottom: 14px;

            font-size: 9px;

            letter-spacing: 1.5px;

            color: var(--gray);
        }

        .profile-online {
            color: var(--green);

            text-shadow:
                0 0 8px
                rgba(0, 248, 21, 0.7);
        }

        .profile-photo-frame {
            position: relative;

            width: 100%;

            height: 230px;

            overflow: hidden;

            border:
                1px solid
                rgba(0, 248, 21, 0.4);

            background: #020402;

            box-shadow:
                inset 0 0 30px
                rgba(0, 248, 21, 0.06),
                0 0 25px
                rgba(0, 248, 21, 0.05);
        }

        .profile-photo-frame::before {
            content: "";

            position: absolute;

            top: 0;
            left: 0;

            width: 35px;
            height: 35px;

            z-index: 5;

            border-top:
                2px solid
                var(--green);

            border-left:
                2px solid
                var(--green);

            pointer-events: none;
        }

        .profile-photo-frame::after {
            content: "";

            position: absolute;

            right: 0;
            bottom: 0;

            width: 35px;
            height: 35px;

            z-index: 5;

            border-right:
                2px solid
                var(--cyan);

            border-bottom:
                2px solid
                var(--cyan);

            pointer-events: none;
        }

        .profile-photo {
            display: block;

            width: 100%;
            height: 100%;

            object-fit: cover;

            object-position: center;

            filter:
                brightness(0.82)
                contrast(1.15)
                saturate(0.75)
                grayscale(0.15);

            transition:
                filter 0.08s linear,
                opacity 0.08s linear,
                transform 0.08s linear;

            animation:
                hackerBrightness 4.5s infinite;
        }

        .profile-photo-frame:hover .profile-photo {
            filter:
                brightness(0.9)
                contrast(1.15)
                saturate(0.9)
                grayscale(0.05);

            transform: scale(1.025);
        }

        @keyframes hackerBrightness {
            0% {
                filter:
                    brightness(0.78)
                    contrast(1.15)
                    saturate(0.7)
                    grayscale(0.15);
            }

            20% {
                filter:
                    brightness(0.9)
                    contrast(1.2)
                    saturate(0.8)
                    grayscale(0.1);
            }

            40% {
                filter:
                    brightness(0.82)
                    contrast(1.15)
                    saturate(0.75)
                    grayscale(0.15);
            }

            60% {
                filter:
                    brightness(1)
                    contrast(1.25)
                    saturate(0.9)
                    grayscale(0.05);
            }

            63% {
                filter:
                    brightness(0.72)
                    contrast(1.3)
                    saturate(0.6)
                    grayscale(0.2);
            }

            65% {
                filter:
                    brightness(0.95)
                    contrast(1.2)
                    saturate(0.8)
                    grayscale(0.1);
            }

            100% {
                filter:
                    brightness(0.78)
                    contrast(1.15)
                    saturate(0.7)
                    grayscale(0.15);
            }
        }

        .photo-scanlines {
            position: absolute;

            inset: 0;

            z-index: 3;

            pointer-events: none;

            background:
                repeating-linear-gradient(
                    to bottom,
                    rgba(0, 248, 21, 0) 0px,
                    rgba(0, 248, 21, 0) 2px,
                    rgba(0, 248, 21, 0.08) 3px,
                    rgba(0, 248, 21, 0) 4px
                );

            animation:
                scanMove 5s linear infinite;

            mix-blend-mode: screen;
        }

        @keyframes scanMove {
            from {
                transform: translateY(0);
            }

            to {
                transform: translateY(4px);
            }
        }

        .photo-overlay {
            position: absolute;

            inset: 0;

            z-index: 2;

            pointer-events: none;

            background:
                linear-gradient(
                    90deg,
                    rgba(0, 248, 21, 0.08),
                    transparent 40%,
                    rgba(3, 230, 242, 0.04)
                );

            mix-blend-mode: screen;

            animation:
                overlayPulse 1s ease-in-out infinite;
        }

        @keyframes overlayPulse {
            0%,
            100% {
                opacity: 0.5;
            }

            50% {
                opacity: 0.65;
            }
        }

        .profile-info {
            display: flex;

            justify-content: space-between;

            align-items: flex-end;

            gap: 15px;

            margin-top: 14px;
        }

        .profile-name {
            color: var(--white);

            font-size: 15px;

            font-weight: bold;

            letter-spacing: 1px;
        }

        .profile-role {
            margin-top: 4px;

            color: var(--gray);

            font-size: 8px;

            letter-spacing: 1.5px;
        }

        .profile-access {
            color: var(--gray);

            font-size: 8px;

            text-align: right;

            letter-spacing: 1px;
        }

        .profile-access span {
            color: var(--green);
        }

        /* =========================================
           STATUS PANEL
        ========================================== */

        .status-panel {
            padding: 25px;

            border:
                1px solid
                var(--border);

            background: var(--panel);
        }

        .panel-heading {
            display: flex;

            justify-content: space-between;

            padding-bottom: 15px;

            margin-bottom: 20px;

            border-bottom:
                1px solid
                var(--border);
        }

        .panel-heading span:first-child {
            color: var(--white);

            font-size: 11px;

            letter-spacing: 2px;
        }

        .online {
            color: var(--green);

            font-size: 10px;
        }

        .status-line {
            display: flex;

            justify-content: space-between;

            gap: 15px;

            padding: 11px 0;

            border-bottom:
                1px solid
                rgba(255, 255, 255, 0.035);

            font-size: 11px;
        }

        .status-label {
            color: var(--gray);
        }

        .status-value {
            color: var(--green);

            text-align: right;
        }

        .status-value.cyan {
            color: var(--cyan);
        }

        /* =========================================
           STAT CARDS
        ========================================== */

        .stats {
            display: grid;

            grid-template-columns:
                repeat(3, 1fr);

            gap: 12px;

            margin-top: 12px;
        }

        .stat {
            position: relative;

            padding: 18px;

            border:
                1px solid
                var(--border);

            background:
                rgba(4, 9, 6, 0.78);

            overflow: hidden;
        }

        .stat::after {
            content: "";

            position: absolute;

            right: 0;
            bottom: 0;

            width: 30px;
            height: 1px;

            background: var(--green);
        }

        .stat-label {
            color: var(--gray);

            font-size: 9px;

            letter-spacing: 2px;
        }

        .stat-value {
            margin-top: 8px;

            color: var(--green);

            font-size: 25px;

            font-weight: bold;

            text-shadow:
                0 0 15px
                rgba(0, 248, 21, 0.2);
        }

        /* =========================================
           DIRECTORY
        ========================================== */

        .section-header {
            display: flex;

            justify-content: space-between;

            align-items: flex-end;

            margin-top: 45px;

            margin-bottom: 15px;
        }

        .section-title {
            margin: 0;

            color: var(--white);

            font-size: 13px;

            letter-spacing: 2px;
        }

        .section-title::before {
            content: "> ";

            color: var(--green);
        }

        .section-meta {
            color: var(--gray-dark);

            font-size: 9px;

            letter-spacing: 1px;
        }

        .categories {
            display: grid;

            grid-template-columns:
                repeat(
                    auto-fit,
                    minmax(230px, 1fr)
                );

            gap: 10px;
        }

        .category {
            position: relative;

            min-height: 120px;

            padding: 20px;

            display: flex;

            flex-direction: column;

            justify-content: space-between;

            border:
                1px solid
                var(--border);

            background:
                rgba(3, 7, 4, 0.78);

            color: var(--white);

            text-decoration: none;

            overflow: hidden;

            transition: 0.2s;
        }

        .category::before {
            content: "";

            position: absolute;

            top: 0;
            left: -100%;

            width: 100%;
            height: 1px;

            background:
                linear-gradient(
                    90deg,
                    transparent,
                    var(--green),
                    transparent
                );

            transition: left 0.4s;
        }

        .category:hover {
            border-color:
                rgba(0, 248, 21, 0.5);

            background:
                rgba(0, 248, 21, 0.025);

            transform:
                translateY(-3px);

            box-shadow:
                0 10px 35px
                rgba(0, 0, 0, 0.35),
                0 0 25px
                rgba(0, 248, 21, 0.035);
        }

        .category:hover::before {
            left: 100%;
        }

        .category-number {
            color: var(--gray-dark);

            font-size: 9px;

            letter-spacing: 1px;
        }

        .category-name {
            margin-top: 15px;

            color: var(--green);

            font-size: 13px;

            font-weight: bold;

            letter-spacing: 1px;
        }

        .category-count {
            margin-top: 8px;

            color: var(--gray);

            font-size: 9px;
        }

        .category-arrow {
            position: absolute;

            right: 18px;

            bottom: 16px;

            color: var(--gray-dark);

            transition: 0.2s;
        }

        .category:hover .category-arrow {
            color: var(--cyan);

            transform:
                translateX(4px);
        }

        /* =========================================
           FOOTER
        ========================================== */

        .footer {
            display: flex;

            justify-content: space-between;

            gap: 15px;

            margin-top: 45px;

            padding-top: 18px;

            border-top:
                1px solid
                var(--border);

            color: var(--gray-dark);

            font-size: 9px;

            letter-spacing: 1px;
        }

        .footer .active {
            color: var(--green);
        }

        /* =========================================
           MOBILE
        ========================================== */

        @media (max-width: 800px) {

            .hero {
                grid-template-columns: 1fr;
            }

            .hero-panel {
                min-height: 330px;
            }
        }

        @media (max-width: 600px) {

            .container {
                width:
                    calc(100% - 24px);

                padding-top: 20px;
            }

            .topbar {
                align-items: flex-start;

                flex-direction: column;
            }

            .lock-button {
                width: 100%;
            }

            .hero-panel {
                padding: 25px;
            }

            .hero-title {
                font-size: 38px;
            }

            .terminal-command {
                left: 25px;

                bottom: 20px;
            }

            .stats {
                grid-template-columns: 1fr;
            }

            .section-header {
                align-items: flex-start;

                flex-direction: column;

                gap: 7px;
            }

            .footer {
                align-items: flex-start;

                flex-direction: column;
            }
        }

        @media (prefers-reduced-motion: reduce) {

            *,
            *::before,
            *::after {
                animation-duration:
                    0.01ms !important;

                animation-iteration-count:
                    1 !important;

                transition-duration:
                    0.01ms !important;
            }
        }
    </style>
</head>

<body>

<canvas id="matrix"></canvas>

<div class="grid"></div>

<div class="scanlines"></div>

<div class="vignette"></div>


<div class="container">

    {{-- =========================================
         TOP BAR
    ========================================== --}}

    <div class="topbar">

        <div class="brand">

            <div class="logo">
                ◈
            </div>

            <div>

                <h1 class="brand-title">
                    PERSONAL
                    <span>DIGITAL VAULT</span>
                </h1>

                <p class="brand-subtitle">
                    PRIVATE SECURITY ENVIRONMENT
                </p>

            </div>

        </div>


        <form
            method="POST"
            action="{{ route('vault.lock') }}"
        >

            @csrf

            <button
                type="submit"
                class="lock-button"
            >
                [ LOCK SESSION ]
            </button>

        </form>

    </div>


    {{-- =========================================
         DATA
    ========================================== --}}

    @php

        $categories =
            $vaultData['categories'] ?? [];

        $categoryCount =
            count($categories);

        $itemCount =
            collect($categories)
                ->flatten(1)
                ->count();

    @endphp


    {{-- =========================================
         HERO
    ========================================== --}}

    <section class="hero">

        {{-- LEFT : MAIN HERO --}}

        <div class="hero-panel">

            <div class="eyebrow">
                SYSTEM // PRIVATE // SECURE
            </div>

            <h2 class="hero-title">

                YOUR DATA.
                <br>

                <span>
                    YOUR CONTROL.
                </span>

            </h2>

            <p class="hero-description">

                A private encrypted environment
                for storing sensitive digital
                information. Access is granted
                only through your Root Secret.

            </p>


            <div class="terminal-command">

                <strong>
                    root@pdv
                </strong>

                :~$ vault status

                <span class="cursor"></span>

            </div>

        </div>


        {{-- RIGHT : PROFILE + STATUS --}}

        <div class="profile-status-panel">

            {{-- PROFILE PHOTO --}}

            <div class="profile-photo-wrapper">

                <div class="profile-photo-header">

                    <span>
                        OPERATOR // PROFILE
                    </span>

                    <span class="profile-online">
                        ● ONLINE
                    </span>

                </div>


                <div class="profile-photo-frame">

                    <img
                        src="{{ asset('images/profile.jpg') }}"
                        alt="Operator Profile"
                        class="profile-photo"
                    >

                    <div class="photo-scanlines"></div>

                    <div class="photo-overlay"></div>

                </div>


                <div class="profile-info">

                    <div>

                        <div class="profile-name">
                            BINTANG
                        </div>

                        <div class="profile-role">
                            VAULT OPERATOR
                        </div>

                    </div>


                    <div class="profile-access">

                        ACCESS :
                        <span>GRANTED</span>

                    </div>

                </div>

            </div>


            {{-- SYSTEM STATUS --}}

            <div class="status-panel">

                <div class="panel-heading">

                    <span>
                        SYSTEM STATUS
                    </span>

                    <span class="online">
                        ● ONLINE
                    </span>

                </div>


                <div class="status-line">

                    <span class="status-label">
                        VAULT
                    </span>

                    <span class="status-value">
                        UNLOCKED
                    </span>

                </div>


                <div class="status-line">

                    <span class="status-label">
                        ENCRYPTION
                    </span>

                    <span class="status-value">
                        AES-256-GCM
                    </span>

                </div>


                <div class="status-line">

                    <span class="status-label">
                        STORAGE
                    </span>

                    <span class="status-value">
                        ENCRYPTED
                    </span>

                </div>


                <div class="status-line">

                    <span class="status-label">
                        SESSION
                    </span>

                    <span class="status-value cyan">
                        AUTHENTICATED
                    </span>

                </div>


                <div class="status-line">

                    <span class="status-label">
                        ACCESS
                    </span>

                    <span class="status-value">
                        GRANTED
                    </span>

                </div>


                <div class="status-line">

                    <span class="status-label">
                        MODE
                    </span>

                    <span class="status-value cyan">
                        PRIVATE
                    </span>

                </div>

            </div>

        </div>

    </section>


    {{-- =========================================
         STATS
    ========================================== --}}

    <div class="stats">

        <div class="stat">

            <div class="stat-label">
                SECURITY STATUS
            </div>

            <div class="stat-value">
                SECURE
            </div>

        </div>


        <div class="stat">

            <div class="stat-label">
                DIRECTORIES
            </div>

            <div class="stat-value">
                {{ $categoryCount }}
            </div>

        </div>


        <div class="stat">

            <div class="stat-label">
                STORED OBJECTS
            </div>

            <div class="stat-value">
                {{ $itemCount }}
            </div>

        </div>

    </div>


    {{-- =========================================
         DIRECTORIES
    ========================================== --}}

    <div class="section-header">

        <h3 class="section-title">
            VAULT DIRECTORIES
        </h3>

        <div class="section-meta">
            /root/storage/*
        </div>

    </div>


    <div class="categories">

        @foreach (
            $categories
            as $category => $items
        )

            <a
                class="category"
                href="{{ route(
                    'vault.category',
                    $category
                ) }}"
            >

                <div class="category-number">

                    DIRECTORY /
                    {{ str_pad(
                        $loop->iteration,
                        2,
                        '0',
                        STR_PAD_LEFT
                    ) }}

                </div>


                <div>

                    <div class="category-name">

                        {{
                            strtoupper(
                                str_replace(
                                    '_',
                                    ' ',
                                    $category
                                )
                            )
                        }}

                    </div>

                    <div class="category-count">

                        {{ count($items) }}
                        stored object(s)

                    </div>

                </div>


                <div class="category-arrow">
                    →
                </div>

            </a>

        @endforeach

    </div>


    {{-- =========================================
         FOOTER
    ========================================== --}}

    <div class="footer">

        <div>
            PDV // PERSONAL DIGITAL VAULT
        </div>

        <div>

            <span class="active">
                ● ENCRYPTED
            </span>

            &nbsp; // &nbsp;

            PRIVATE SESSION

            &nbsp; // &nbsp;

            ACCESS GRANTED

        </div>

    </div>

</div>


<script>

    /* =========================================
       MATRIX BACKGROUND
    ========================================== */

    const canvas =
        document.getElementById('matrix');

    const ctx =
        canvas.getContext('2d');

    const characters =
        '01ABCDEFGHIJKLMNOPQRSTUVWXYZ' +
        '0123456789' +
        '><[]{}' +
        '/\\' +
        '$#@%';

    const fontSize = 14;

    let columns = 0;

    let drops = [];

    let speeds = [];


    function resizeCanvas() {

        const dpr =
            window.devicePixelRatio || 1;

        canvas.width =
            window.innerWidth * dpr;

        canvas.height =
            window.innerHeight * dpr;

        canvas.style.width =
            window.innerWidth + 'px';

        canvas.style.height =
            window.innerHeight + 'px';

        ctx.setTransform(
            dpr,
            0,
            0,
            dpr,
            0,
            0
        );


        columns =
            Math.ceil(
                window.innerWidth /
                fontSize
            );


        drops = [];

        speeds = [];


        for (
            let i = 0;
            i < columns;
            i++
        ) {

            drops[i] =
                Math.random() *
                (
                    window.innerHeight /
                    fontSize
                ) *
                -1;


            speeds[i] =
                0.35 +
                Math.random() * 0.75;
        }
    }


    resizeCanvas();


    window.addEventListener(
        'resize',
        resizeCanvas
    );


    function drawMatrix() {

        ctx.fillStyle =
            'rgba(1, 3, 2, 0.055)';

        ctx.fillRect(
            0,
            0,
            window.innerWidth,
            window.innerHeight
        );


        ctx.font =
            fontSize +
            'px "Courier New", monospace';


        for (
            let i = 0;
            i < columns;
            i++
        ) {

            const x =
                i * fontSize;

            const y =
                drops[i] * fontSize;


            const character =
                characters[
                    Math.floor(
                        Math.random() *
                        characters.length
                    )
                ];


            const random =
                Math.random();


            /*
             * Cyan dibuat lebih dulu
             * agar kondisi dapat tercapai.
             */

            if (random > 0.985) {

                ctx.fillStyle =
                    '#03e6f2';

                ctx.shadowColor =
                    '#03e6f2';

                ctx.shadowBlur = 10;

            } else if (random > 0.97) {

                ctx.fillStyle =
                    '#00f815';

                ctx.shadowColor =
                    '#00f815';

                ctx.shadowBlur = 12;

            } else {

                ctx.fillStyle =
                    'rgba(0, 248, 21, 0.65)';

                ctx.shadowBlur = 0;
            }


            ctx.fillText(
                character,
                x,
                y
            );


            drops[i] +=
                speeds[i];


            if (
                y > window.innerHeight
            ) {

                if (
                    Math.random() > 0.975
                ) {

                    drops[i] =
                        Math.random() * -20;

                }
            }
        }


        ctx.shadowBlur = 0;


        requestAnimationFrame(
            drawMatrix
        );
    }


    ctx.clearRect(
        0,
        0,
        window.innerWidth,
        window.innerHeight
    );


    drawMatrix();


    /* =========================================
       PROFILE FLICKER
    ========================================== */

    const profilePhoto =
        document.querySelector(
            '.profile-photo'
        );


    if (profilePhoto) {

        setInterval(() => {

            const flicker =
                Math.random();


            if (flicker > 0.82) {

                profilePhoto.style.opacity =
                    '0.72';


                setTimeout(() => {

                    profilePhoto.style.opacity =
                        '1';

                }, 40);
            }


            if (flicker > 0.94) {

                profilePhoto.style.transform =
                    `translateX(${Math.random() * 3 - 1.5}px)`;


                setTimeout(() => {

                    profilePhoto.style.transform =
                        'translateX(0)';

                }, 70);
            }

        }, 900);
    }

</script>

</body>

</html>
