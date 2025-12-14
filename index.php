<?php
// PHP Header
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HashCity - Lerne Hash Maps spielerisch</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Rajdhani:wght@300;500;700&display=swap" rel="stylesheet">

    <link rel="icon" type="image/png" sizes="32x32" href="assets/icons8-hash-scribby-32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="assets/icons8-hash-scribby-96.png">

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Rajdhani', sans-serif;
            overflow: hidden;
            height: 100vh;
            position: relative;
            background-color: #0a0a0a;
            user-select: none; /* Verhindert Textauswahl beim Start-Klick */
        }

        /* Background Image Container */
        .background-container {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            z-index: 0;
            overflow: hidden;
            opacity: 0;
            transition: opacity 1.5s ease-in-out;
        }

        .background-container.loaded {
            opacity: 1;
        }

        .background-container::before {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            animation: slowZoom 20s ease-in-out infinite alternate;
        }

        @keyframes slowZoom {
            0% { transform: scale(1); }
            100% { transform: scale(1.05); }
        }

        /* Overlay */
        .overlay {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: linear-gradient(to bottom, rgba(0,0,0,0.3) 0%, rgba(0,0,0,0.1) 50%, rgba(0,0,0,0.4) 100%);
            z-index: 1;
            pointer-events: none;
        }

        /* Main Container */
        .main-container {
            position: relative;
            z-index: 2;
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            opacity: 0; /* Unsichtbar bis Overlay weg ist */
            transition: opacity 1s ease-in;
        }

        .main-container.visible {
            opacity: 1;
        }

        /* Typography & UI */
        .logo-title {
            font-family: 'Orbitron', sans-serif;
            font-size: 8rem;
            font-weight: 900;
            color: #fff;
            text-shadow: 0 0 30px rgba(255, 255, 255, 0.8), 0 0 60px rgba(255, 165, 0, 0.6);
            margin-bottom: 1rem;
            letter-spacing: 8px;
            animation: titleGlow 3s ease-in-out infinite;
        }

        .logo-subtitle {
            font-family: 'Rajdhani', sans-serif;
            font-size: 2rem;
            color: rgba(255, 255, 255, 0.95);
            font-weight: 500;
            letter-spacing: 4px;
            margin-bottom: 4rem;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
        }

        @keyframes titleGlow {
            0%, 100% { text-shadow: 0 0 30px rgba(255,255,255,0.8), 0 0 60px rgba(255,165,0,0.6); }
            50% { text-shadow: 0 0 40px rgba(255,255,255,1), 0 0 80px rgba(255,165,0,0.8); }
        }

        /* Button */
        .start-button {
            position: relative;
            padding: 1.8rem 5rem;
            font-size: 2.2rem;
            font-weight: 700;
            font-family: 'Orbitron', sans-serif;
            color: #fff;
            background: linear-gradient(135deg, #FF8C00 0%, #FF6347 100%);
            border: 4px solid #fff;
            border-radius: 60px;
            cursor: pointer;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            box-shadow: 0 10px 40px rgba(0,0,0,0.4), 0 0 40px rgba(255,140,0,0.5), inset 0 -3px 10px rgba(0,0,0,0.2);
            text-transform: uppercase;
            letter-spacing: 4px;
            overflow: hidden;
            animation: buttonPulse 2s ease-in-out infinite;
        }

        .start-button:hover {
            transform: scale(1.1);
            box-shadow: 0 20px 80px rgba(0,0,0,0.6), 0 0 80px rgba(255,140,0,1);
            animation: none !important;
        }

        .start-button::after {
            content: '▶'; margin-left: 15px; font-size: 1.8rem; display: inline-block;
            animation: arrowBounce 1s ease-in-out infinite;
        }

        @keyframes buttonPulse { 0%, 100% { transform: scale(1); } 50% { transform: scale(1.05); } }
        @keyframes arrowBounce { 0%, 100% { transform: translateX(0); } 50% { transform: translateX(10px); } }

        /* Loading Overlay / Interaction Gate */
        .loading-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: #4FC3F7;
            display: flex; justify-content: center; align-items: center;
            flex-direction: column;
            z-index: 9999;
            transition: opacity 0.8s ease-out, visibility 0.8s;
            cursor: wait; /* Standard Cursor beim Laden */
        }

        .loading-overlay.ready-to-click {
            cursor: pointer; /* Hand-Cursor wenn bereit */
            background: #0277BD; /* Leichtes abdunkeln als Signal */
            transition: background 0.5s ease;
        }

        .loading-overlay.hide { opacity: 0; visibility: hidden; pointer-events: none; }

        .loading-text {
            font-family: 'Orbitron', sans-serif; font-size: 2.5rem; color: #fff; font-weight: 700;
            animation: loadingPulse 1.5s ease-in-out infinite;
            text-align: center;
            padding: 0 20px;
        }

        .loading-subtext {
            margin-top: 20px;
            font-family: 'Rajdhani', sans-serif; color: rgba(255,255,255,0.8);
            font-size: 1.2rem;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        @keyframes loadingPulse { 0%, 100% { opacity: 0.5; transform: scale(0.95); } 50% { opacity: 1; transform: scale(1); } }

        /* Hash Decorations */
        .hash-decoration { position: absolute; font-family: 'Orbitron'; color: rgba(255,255,255,0.15); font-size: 4rem; font-weight: 900; animation: floatRotate 10s infinite; z-index: 1;}
        .hash-decoration:nth-child(1) { top: 10%; left: 10%; } .hash-decoration:nth-child(2) { top: 20%; right: 15%; }
        @keyframes floatRotate { 0%, 100% { transform: translateY(0) rotate(0deg); } 50% { transform: translateY(-20px) rotate(180deg); } }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .logo-title { font-size: 4rem; }
            .start-button { padding: 1.2rem 3rem; font-size: 1.6rem; }
            .loading-text { font-size: 1.8rem; }
        }
    </style>
</head>
<body>

<div class="loading-overlay" id="loadingOverlay">
    <div class="loading-text" id="loadingText">HashCity</div>
    <div class="loading-subtext" id="loadingSubtext">Ressourcen werden geladen...</div>
</div>

<div class="background-container" id="bgContainer"></div>
<div class="overlay"></div>

<audio id="bgMusic" loop preload="auto">
    <source src="./assets/sounds/title.mp3" type="audio/mpeg">
</audio>

<div class="hash-decoration">#</div>
<div class="hash-decoration">#</div>

<div class="main-container" id="mainMenu">
    <h1 class="logo-title">HASHCITY</h1>
    <p class="logo-subtitle">Lerne Hash Maps spielerisch</p>
    <button class="start-button" id="startButton"><span>Start</span></button>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    $(document).ready(function() {

        // --- 1. RESOURCE LOADING & INTERACTION GATE ---

        const bgImageUrl = './assets/background.png';
        const bgImg = new Image();

        // Konstanten für Elemente
        const $overlay = $('#loadingOverlay');
        const $text = $('#loadingText');
        const $subtext = $('#loadingSubtext');

        bgImg.onload = function() {
            // A. Hintergrund setzen
            $('<style>.background-container::before { background-image: url("' + bgImageUrl + '"); }</style>').appendTo('head');
            $('#bgContainer').addClass('loaded');

            // B. Overlay NICHT ausblenden, sondern auf Interaktion warten
            // Status ändern
            $overlay.addClass('ready-to-click');

            // Text ändern um User aufzufordern
            $text.text('KLICKEN UM ZU STARTEN');
            $subtext.text('Sound & Experience aktivieren');

            // C. Einmaligen Klick-Listener auf das gesamte Dokument/Overlay legen
            $(document).one('click touchstart keydown', function() {
                enterGame();
            });
        };

        bgImg.onerror = function() {
            // Fallback bei Fehler
            console.error("Bild konnte nicht geladen werden.");
            $text.text('FEHLER BEIM LADEN');
            setTimeout(enterGame, 1000); // Trotzdem reinlassen
        };

        // Download starten
        bgImg.src = bgImageUrl;


        // --- 2. ENTER GAME SEQUENCE ---

        function enterGame() {
            // Musik sicher starten (Browser erlaubt das jetzt, da es in einem Klick-Event ist)
            const bgMusic = document.getElementById('bgMusic');
            bgMusic.volume = 0.4;
            bgMusic.play().catch(e => console.log("Audio play error:", e));

            // Overlay ausblenden
            $overlay.addClass('hide');

            // Hauptmenü einblenden
            $('#mainMenu').addClass('visible');
        }


        // --- 3. MAIN MENU UI LOGIC ---

        $('#startButton').click(function(e) {
            // Verhindern, dass dieser Klick Events nach oben bubbled (falls noch nötig)
            e.stopPropagation();

            const button = $(this);
            button.find('span').text('Lade Level...');
            button.prop('disabled', true).css({'opacity': '0.7', 'cursor': 'not-allowed'});

            $('.main-container').css({'opacity': '0', 'transform': 'scale(0.95)', 'transition': 'all 0.8s ease-out'});

            setTimeout(function() {
                window.location.href = 'Level-Auswahl';
            }, 1000);
        });
    });
</script>
</body>
</html>