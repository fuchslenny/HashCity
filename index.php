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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            position: relative;
        }

        .splash-container {
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            position: relative;
            z-index: 2;
        }

        /* Animated City Background */
        .city-background {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 40%;
            z-index: 1;
            overflow: hidden;
        }

        .building {
            position: absolute;
            bottom: 0;
            background: rgba(255, 255, 255, 0.1);
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 4px 4px 0 0;
            animation: buildingPulse 3s ease-in-out infinite;
        }

        @keyframes buildingPulse {
            0%, 100% { opacity: 0.6; }
            50% { opacity: 1; }
        }

        .window {
            position: absolute;
            background: rgba(255, 255, 0, 0.8);
            width: 8px;
            height: 8px;
            border-radius: 1px;
            animation: windowBlink 2s ease-in-out infinite;
        }

        @keyframes windowBlink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.3; }
        }

        /* Grid Pattern Overlay */
        .grid-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image:
                    linear-gradient(rgba(255, 255, 255, 0.05) 1px, transparent 1px),
                    linear-gradient(90deg, rgba(255, 255, 255, 0.05) 1px, transparent 1px);
            background-size: 50px 50px;
            z-index: 0;
            animation: gridMove 20s linear infinite;
        }

        @keyframes gridMove {
            0% { transform: translate(0, 0); }
            100% { transform: translate(50px, 50px); }
        }

        /* Logo and Title */
        .logo-container {
            text-align: center;
            margin-bottom: 3rem;
            animation: fadeInDown 1s ease-out;
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

        .logo-title {
            font-family: 'Orbitron', sans-serif;
            font-size: 5rem;
            font-weight: 900;
            color: #fff;
            text-shadow: 0 0 20px rgba(255, 255, 255, 0.5),
            0 0 40px rgba(102, 126, 234, 0.8),
            0 0 60px rgba(118, 75, 162, 0.6);
            margin-bottom: 0.5rem;
            letter-spacing: 4px;
        }

        .logo-subtitle {
            font-size: 1.5rem;
            color: rgba(255, 255, 255, 0.9);
            font-weight: 500;
            letter-spacing: 2px;
        }

        /* Info Box */
        .info-box {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 20px;
            padding: 2rem 3rem;
            max-width: 700px;
            margin: 0 2rem 3rem 2rem;
            animation: fadeInUp 1s ease-out 0.3s both;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .info-title {
            font-size: 1.8rem;
            color: #fff;
            font-weight: 700;
            margin-bottom: 1rem;
            text-align: center;
        }

        .info-description {
            font-size: 1.2rem;
            color: rgba(255, 255, 255, 0.95);
            text-align: center;
            line-height: 1.6;
        }

        /* Start Button */
        .start-button {
            position: relative;
            padding: 1.5rem 4rem;
            font-size: 1.8rem;
            font-weight: 700;
            font-family: 'Orbitron', sans-serif;
            color: #667eea;
            background: #fff;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s ease;
            animation: fadeInUp 1s ease-out 0.6s both, pulse 2s ease-in-out infinite;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
                box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
            }
            50% {
                transform: scale(1.05);
                box-shadow: 0 15px 60px rgba(255, 255, 255, 0.4);
            }
        }

        .start-button:hover {
            transform: scale(1.1);
            box-shadow: 0 15px 60px rgba(255, 255, 255, 0.5);
            animation: none;
        }

        .start-button:active {
            transform: scale(0.98);
        }

        .start-button::before {
            content: '▶';
            margin-right: 10px;
            font-size: 1.5rem;
        }

        /* Hash Particles */
        .hash-particle {
            position: absolute;
            color: rgba(255, 255, 255, 0.3);
            font-family: 'Courier New', monospace;
            font-size: 1.2rem;
            pointer-events: none;
            animation: floatUp 8s linear infinite;
        }

        @keyframes floatUp {
            0% {
                transform: translateY(0) rotate(0deg);
                opacity: 0;
            }
            10% {
                opacity: 0.6;
            }
            90% {
                opacity: 0.6;
            }
            100% {
                transform: translateY(-100vh) rotate(360deg);
                opacity: 0;
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .logo-title {
                font-size: 3rem;
            }

            .logo-subtitle {
                font-size: 1rem;
            }

            .info-box {
                padding: 1.5rem 2rem;
                margin: 0 1rem 2rem 1rem;
            }

            .info-title {
                font-size: 1.4rem;
            }

            .info-description {
                font-size: 1rem;
            }

            .start-button {
                padding: 1.2rem 3rem;
                font-size: 1.4rem;
            }
        }

        /* Loading Animation */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            font-size: 2rem;
            color: #fff;
            animation: loadingPulse 1.5s ease-in-out infinite;
        }

        @keyframes loadingPulse {
            0%, 100% { opacity: 0.3; }
            50% { opacity: 1; }
        }
    </style>
</head>
<body>
<!-- Grid Overlay -->
<div class="grid-overlay"></div>

<!-- Loading Overlay -->
<div class="loading-overlay" id="loadingOverlay">
    <div class="loading-text">HashCity wird geladen...</div>
</div>

<!-- City Background -->
<div class="city-background" id="cityBackground"></div>

<!-- Main Splash Container -->
<div class="splash-container">
    <!-- Logo -->
    <div class="logo-container">
        <h1 class="logo-title">#CITY</h1>
        <p class="logo-subtitle">HashCity</p>
    </div>

    <!-- Info Box -->
    <div class="info-box">
        <h2 class="info-title">Lerne Hash Maps durch interaktives Stadtbauen</h2>
        <p class="info-description">
            Entdecke die Welt der Hash-Funktionen, Kollisionen und Optimierungsstrategien
            in einer spielerischen 3D-Stadt-Simulation.
        </p>
    </div>

    <!-- Start Button -->
    <button class="start-button" id="startButton">Spiel Starten</button>
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
        }, 1000);

        // Generate random city buildings
        function generateCity() {
            const cityBackground = $('#cityBackground');
            const buildingCount = 20;

            for (let i = 0; i < buildingCount; i++) {
                const building = $('<div class="building"></div>');
                const width = Math.random() * 60 + 40; // 40-100px
                const height = Math.random() * 150 + 100; // 100-250px
                const left = (100 / buildingCount) * i + Math.random() * 3;
                const delay = Math.random() * 3;

                building.css({
                    width: width + 'px',
                    height: height + 'px',
                    left: left + '%',
                    animationDelay: delay + 's'
                });

                // Add windows to building
                const windowRows = Math.floor(height / 20);
                const windowCols = Math.floor(width / 15);

                for (let row = 0; row < windowRows; row++) {
                    for (let col = 0; col < windowCols; col++) {
                        if (Math.random() > 0.3) { // 70% chance of window
                            const window = $('<div class="window"></div>');
                            window.css({
                                left: (col * 15 + 4) + 'px',
                                bottom: (row * 20 + 6) + 'px',
                                animationDelay: (Math.random() * 2) + 's'
                            });
                            building.append(window);
                        }
                    }
                }

                cityBackground.append(building);
            }
        }

        // Generate floating hash particles
        function createHashParticle() {
            const particles = ['#', '{ }', '[ ]', '0x', '→', '⚡', '🏗️', '🏢', '📊'];
            const particle = $('<div class="hash-particle"></div>');
            particle.text(particles[Math.floor(Math.random() * particles.length)]);
            particle.css({
                left: Math.random() * 100 + '%',
                top: '100%',
                animationDuration: (Math.random() * 4 + 6) + 's',
                animationDelay: Math.random() * 2 + 's'
            });

            $('body').append(particle);

            setTimeout(function() {
                particle.remove();
            }, 10000);
        }

        // Start button click handler
        $('#startButton').click(function() {
            $(this).text('Wird geladen...');
            $(this).prop('disabled', true);

            // Add screen transition effect
            $('body').css('transition', 'all 0.8s ease-out');
            setTimeout(function() {
                $('body').css({
                    'opacity': '0',
                    'transform': 'scale(1.1)'
                });
            }, 100);

            // Redirect to level select
            setTimeout(function() {
                window.location.href = 'Level-Auswahl';
            }, 1000);
        });

        // Initialize
        generateCity();

        // Create particles periodically
        setInterval(createHashParticle, 1500);

        // Add hover effect to buildings
        $(document).on('mouseenter', '.building', function() {
            $(this).css('background', 'rgba(255, 255, 255, 0.2)');
        });

        $(document).on('mouseleave', '.building', function() {
            $(this).css('background', 'rgba(255, 255, 255, 0.1)');
        });

        // Add keyboard shortcut
        $(document).keypress(function(e) {
            if (e.which === 13 || e.which === 32) { // Enter or Space
                $('#startButton').click();
            }
        });
    });
</script>
</body>
</html>
