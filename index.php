<?php

?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HashCity - Lerne Hash Maps spielerisch</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Rajdhani:wght@300;500;700&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Rajdhani', sans-serif;
            overflow: hidden;
            height: 100vh;
            position: relative;
        }

        /* Background Image */
        .background-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            overflow: hidden;
        }

        .background-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('./assets/background.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            animation: slowZoom 20s ease-in-out infinite alternate;
        }

        @keyframes slowZoom {
            0% {
                transform: scale(1);
            }
            100% {
                transform: scale(1.05);
            }
        }

        /* Overlay for better text readability */
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to bottom,
            rgba(0, 0, 0, 0.3) 0%,
            rgba(0, 0, 0, 0.1) 50%,
            rgba(0, 0, 0, 0.4) 100%);
            z-index: 1;
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
        }

        /* Logo Title */
        .logo-title {
            font-family: 'Orbitron', sans-serif;
            font-size: 8rem;
            font-weight: 900;
            color: #fff;
            text-shadow:
                    0 0 30px rgba(255, 255, 255, 0.8),
                    0 0 60px rgba(255, 165, 0, 0.6),
                    0 0 90px rgba(255, 140, 0, 0.4),
                    0 5px 20px rgba(0, 0, 0, 0.5);
            margin-bottom: 1rem;
            letter-spacing: 8px;
            animation: titleGlow 3s ease-in-out infinite, fadeInDown 1s ease-out;
        }

        @keyframes titleGlow {
            0%, 100% {
                text-shadow:
                        0 0 30px rgba(255, 255, 255, 0.8),
                        0 0 60px rgba(255, 165, 0, 0.6),
                        0 0 90px rgba(255, 140, 0, 0.4),
                        0 5px 20px rgba(0, 0, 0, 0.5);
            }
            50% {
                text-shadow:
                        0 0 40px rgba(255, 255, 255, 1),
                        0 0 80px rgba(255, 165, 0, 0.8),
                        0 0 120px rgba(255, 140, 0, 0.6),
                        0 5px 20px rgba(0, 0, 0, 0.5);
            }
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Subtitle */
        .logo-subtitle {
            font-family: 'Rajdhani', sans-serif;
            font-size: 2rem;
            color: rgba(255, 255, 255, 0.95);
            font-weight: 500;
            letter-spacing: 4px;
            margin-bottom: 4rem;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
            animation: fadeInUp 1s ease-out 0.3s both;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Start Button */
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
            opacity: 1 !important;
            visibility: visible !important;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            animation: fadeInUp 1s ease-out 0.6s forwards, buttonPulse 2s ease-in-out 1.6s infinite;
            box-shadow:
                    0 10px 40px rgba(0, 0, 0, 0.4),
                    0 0 40px rgba(255, 140, 0, 0.5),
                    inset 0 -3px 10px rgba(0, 0, 0, 0.2);
            text-transform: uppercase;
            letter-spacing: 4px;
            overflow: hidden;
            display: inline-block;
        }

        @keyframes buttonPulse {
            0%, 100% {
                transform: scale(1) translateY(0);
            }
            50% {
                transform: scale(1.05) translateY(0);
            }
        }

        .start-button:hover {
            transform: scale(1.1) translateY(0);
            box-shadow:
                    0 20px 80px rgba(0, 0, 0, 0.6),
                    0 0 80px rgba(255, 140, 0, 1),
                    inset 0 -3px 10px rgba(0, 0, 0, 0.2);
            animation: none !important;
        }

        .start-button:active {
            transform: scale(0.98);
        }

        .start-button::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            transform: translate(-50%, -50%);
            transition: width 0.6s ease, height 0.6s ease;
            z-index: 0;
            pointer-events: none;
        }

        .start-button:hover::before {
            width: 300px;
            height: 300px;
        }

        .start-button span {
            position: relative;
            z-index: 2;
            display: inline-block;
        }

        .start-button::after {
            content: '▶';
            position: relative;
            z-index: 2;
            margin-left: 15px;
            font-size: 1.8rem;
            display: inline-block;
            animation: arrowBounce 1s ease-in-out infinite;
        }

        @keyframes arrowBounce {
            0%, 100% {
                transform: translateX(0);
            }
            50% {
                transform: translateX(10px);
            }
        }

        /* Hash Symbol Decoration */
        .hash-decoration {
            position: absolute;
            font-family: 'Orbitron', sans-serif;
            color: rgba(255, 255, 255, 0.15);
            font-size: 4rem;
            font-weight: 900;
            pointer-events: none;
            animation: floatRotate 10s ease-in-out infinite;
        }

        @keyframes floatRotate {
            0%, 100% {
                transform: translateY(0) rotate(0deg);
            }
            50% {
                transform: translateY(-20px) rotate(180deg);
            }
        }

        .hash-decoration:nth-child(1) {
            top: 10%;
            left: 10%;
            animation-delay: 0s;
        }

        .hash-decoration:nth-child(2) {
            top: 20%;
            right: 15%;
            animation-delay: 1s;
        }

        .hash-decoration:nth-child(3) {
            bottom: 15%;
            left: 15%;
            animation-delay: 2s;
        }

        .hash-decoration:nth-child(4) {
            bottom: 20%;
            right: 10%;
            animation-delay: 1.5s;
        }

        /* Loading Overlay */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: #4FC3F7;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            transition: opacity 0.5s ease-out;
        }

        .loading-overlay.hide {
            opacity: 0;
            pointer-events: none;
        }

        .loading-text {
            font-family: 'Orbitron', sans-serif;
            font-size: 2.5rem;
            color: #fff;
            font-weight: 700;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
            animation: loadingPulse 1.5s ease-in-out infinite;
        }

        @keyframes loadingPulse {
            0%, 100% {
                opacity: 0.5;
                transform: scale(0.95);
            }
            50% {
                opacity: 1;
                transform: scale(1);
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .logo-title {
                font-size: 4rem;
                letter-spacing: 4px;
            }

            .logo-subtitle {
                font-size: 1.2rem;
                margin-bottom: 3rem;
            }

            .start-button {
                padding: 1.2rem 3rem;
                font-size: 1.6rem;
                letter-spacing: 2px;
            }

            .hash-decoration {
                font-size: 2.5rem;
            }
        }

        @media (max-width: 480px) {
            .logo-title {
                font-size: 3rem;
            }

            .logo-subtitle {
                font-size: 1rem;
            }

            .start-button {
                padding: 1rem 2.5rem;
                font-size: 1.3rem;
            }
        }
    </style>
</head>
<body>
<!-- Loading Overlay -->
<div class="loading-overlay" id="loadingOverlay">
    <div class="loading-text">HashCity wird geladen...</div>
</div>

<!-- Background -->
<div class="background-container"></div>
<div class="overlay"></div>

<!-- Hash Decorations -->
<div class="hash-decoration">#</div>
<div class="hash-decoration">#</div>
<div class="hash-decoration">#</div>
<div class="hash-decoration">#</div>

<!-- Main Container -->
<div class="main-container">
    <!-- Logo -->
    <h1 class="logo-title">HASHCITY</h1>
    <p class="logo-subtitle">Lerne Hash Maps spielerisch</p>

    <!-- Start Button -->
    <button class="start-button" id="startButton">
        <span>Start</span>
    </button>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    $(document).ready(function() {
        // Hide loading overlay after page load
        setTimeout(function() {
            $('#loadingOverlay').addClass('hide');
        }, 800);

        // Start button click handler
        $('#startButton').click(function() {
            const button = $(this);
            button.find('span').text('Wird geladen...');
            button.prop('disabled', true);
            button.css({
                'opacity': '0.7',
                'cursor': 'not-allowed'
            });

            // Add screen transition effect
            $('.main-container').css('transition', 'all 0.8s ease-out');
            $('.background-container').css('transition', 'all 0.8s ease-out');

            setTimeout(function() {
                $('.main-container').css({
                    'opacity': '0',
                    'transform': 'scale(0.95)'
                });
                $('.overlay').css('opacity', '1');
            }, 100);

            // Redirect to level select
            setTimeout(function() {
                window.location.href = 'Level-Auswahl';
            }, 1000);
        });

        // Add keyboard shortcuts
        $(document).keypress(function(e) {
            if (e.which === 13 || e.which === 32) { // Enter or Space
                e.preventDefault();
                $('#startButton').click();
            }
        });

        // Add touch support for mobile
        $('#startButton').on('touchstart', function(e) {
            e.preventDefault();
            $(this).click();
        });
    });
</script>
</body>
</html>

