<?php

?>



<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HashCity - Level Auswahl</title>

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
            background: linear-gradient(180deg, #87CEEB 0%, #B0D4E3 50%, #4CAF50 100%);
            position: relative;
        }

        /* Sky Section */
        .sky-section {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 55%;
            background: linear-gradient(180deg, #87CEEB 0%, #B0D4E3 100%);
            z-index: 1;
        }

        /* Grass Section */
        .grass-section {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 45%;
            background: linear-gradient(180deg, #76B947 0%, #5FA73D 50%, #4CAF50 100%);
            z-index: 1;
            overflow: hidden;
        }

        /* Grass Blades */
        /* Grass Blades */
        .grass-blade {
            position: absolute;
            width: 3px;
            background: linear-gradient(to top, #2D5016, #4CAF50);
            border-radius: 50% 50% 0 0;
            transform-origin: bottom center;
        }

        @keyframes grassMove {
            0% {
                right: -10px;
                opacity: 0;
            }
            5% {
                opacity: 1;
            }
            95% {
                opacity: 1;
            }
            100% {
                right: 110%;
                opacity: 0;
            }
        }

        /* Flowers */
        .flower {
            position: absolute;
            width: 20px;
            height: 20px;
            transform-origin: bottom center;
        }

        .flower-stem {
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 2px;
            height: 15px;
            background: #2D5016;
            transform: translateX(-50%);
        }

        .flower-head {
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 12px;
            height: 12px;
            border-radius: 50%;
            box-shadow: 0 0 5px rgba(0,0,0,0.2);
        }

        .flower-head::before,
        .flower-head::after {
            content: '';
            position: absolute;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: inherit;
        }

        .flower-head::before {
            top: -4px;
            left: 2px;
        }

        .flower-head::after {
            top: 4px;
            left: -3px;
        }

        .flower-center {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 5px;
            height: 5px;
            background: #FFD700;
            border-radius: 50%;
            z-index: 2;
        }

        /* Road */
        .road-container {
            position: absolute;
            top: 52%;
            left: 0;
            width: 100%;
            height: 200px;
            transform: translateY(-50%);
            z-index: 2;
            overflow: visible;
        }

        .main-road {
            position: absolute;
            top: 50%;
            left: 0;
            width: 100%;
            height: 100px;
            background: #6B6B6B;
            border-top: 3px solid #4A4A4A;
            border-bottom: 3px solid #4A4A4A;
            transform: translateY(-50%);
        }

        .road-line {
            position: absolute;
            top: 50%;
            width: 60px;
            height: 4px;
            background: #FFD700;
            transform: translateY(-50%);
            animation: roadLineMove 2s linear infinite;
        }

        @keyframes roadLineMove {
            0% { left: 100%; }      /* Start rechts */
            100% { left: -60px; }   /* Ende links */
        }

        /* Truck */
        .truck {
            position: absolute;
            bottom: 10px;
            width: 100px;
            height: 70px;
            background: url('assets/truck-red.png') no-repeat center center;
            background-size: contain;
            z-index: 10;
            transition: left 1.2s cubic-bezier(0.4, 0, 0.2, 1);
            filter: drop-shadow(0 5px 10px rgba(0,0,0,0.3));
        }

        .truck.placeholder {
            background: #C41E3A;
            border-radius: 8px 8px 4px 4px;
            position: relative;
        }

        .truck.placeholder::before {
            content: '🚚';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 2.5rem;
        }

        /* Level Nodes Container */
        .levels-container {
            position: absolute;
            top: 65%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 95%;
            max-width: 1600px;
            height: 450px;
            z-index: 5;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 15px;
            padding: 0 20px;
        }

        /* Level Nodes */
        .level-node {
            position: relative;
            width: 130px;
            height: 130px;
            background: rgba(185, 244, 188, 0.95);
            border: 4px solid rgba(76, 175, 80, 0.8);
            border-radius: 50%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 5;
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
            flex-shrink: 0;
        }

        .level-node:hover:not(.locked):not(.active) {
            transform: scale(1.15);
            background: rgba(185, 244, 188, 1);
            box-shadow: 0 12px 30px rgba(0,0,0,0.3);
        }

        /* Active Level - Gets bigger and shows more info */
        .level-node.active {
            width: 300px;
            height: 300px;
            background: rgba(255, 235, 59, 0.98);
            border-color: #FFC107;
            border-width: 6px;
            box-shadow: 0 0 50px rgba(255, 193, 7, 0.9),
            0 15px 50px rgba(0,0,0,0.4);
            z-index: 10;
            transform: translateY(70px);  /* ✨ NEU: Verschiebe nach unten */
        }

        .level-node.active:hover {
            transform: translateY(70px) scale(1.03);  /* ✨ AKTUALISIERT: Kombiniere beide Transforms */
        }

        /*.level-node.active:hover {
            transform: scale(1.03);
        }*/

        .level-node.locked {
            background: rgba(200, 200, 200, 0.7);
            border-color: #999;
            cursor: not-allowed;
            opacity: 0.6;
        }

        .level-node.locked:hover {
            transform: scale(1);
        }

        .level-title {
            font-family: 'Orbitron', sans-serif;
            font-size: 2.5rem;
            font-weight: 700;
            color: #2E7D32;
            margin-bottom: 0;
            transition: all 0.5s ease;
        }

        .level-node.active .level-title {
            font-size: 4rem;
            margin-bottom: 0.5rem;
        }

        .level-number {
            font-size: 1rem;
            color: #666;
            font-weight: 600;
            transition: all 0.5s ease;
        }

        .level-node.active .level-number {
            font-size: 1.5rem;
            margin-bottom: 0.3rem;
        }

        .level-status {
            position: absolute;
            top: 8px;
            right: 8px;
            font-size: 1.8rem;
            transition: all 0.5s ease;
        }

        .level-node.active .level-status {
            top: 15px;
            right: 15px;
            font-size: 2.5rem;
        }

        /* Level Details (only visible when active) */
        .level-details {
            opacity: 0;
            transform: translateY(10px);
            transition: all 0.5s ease;
            text-align: center;
            padding: 0 1.5rem;
            max-height: 0;
            overflow: hidden;
        }

        .level-node.active .level-details {
            opacity: 1;
            transform: translateY(0);
            max-height: 200px;
        }

        .level-subtitle {
            font-size: 1.3rem;
            font-weight: 700;
            color: #2E7D32;
            margin-bottom: 0.5rem;
        }

        .level-description {
            font-size: 0.95rem;
            color: #333;
            line-height: 1.4;
        }

        /* Connection Dots */
        .connection-dot {
            position: absolute;
            display: none;
            width: 16px;
            height: 16px;
            background: rgba(185, 244, 188, 0.9);
            border: 3px solid #4CAF50;
            border-radius: 50%;
            z-index: 4;
            bottom: 95px;
            transition: all 0.3s ease;
        }

        /* Header */
        .level-select-header {
            position: absolute;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 100;
            text-align: center;
        }

        .header-title {
            font-family: 'Orbitron', sans-serif;
            font-size: 2.2rem;
            font-weight: 900;
            color: #fff;
            text-shadow: 0 0 10px rgba(0,0,0,0.3),
            0 0 20px rgba(102, 126, 234, 0.5);
            margin-bottom: 0.3rem;
        }

        .header-subtitle {
            font-size: 1.1rem;
            color: rgba(255, 255, 255, 0.95);
            font-weight: 500;
        }

        /* Back Button */
        .back-button {
            position: absolute;
            top: 20px;
            left: 20px;
            padding: 0.7rem 1.3rem;
            background: rgba(255, 255, 255, 0.9);
            border: 2px solid rgba(102, 126, 234, 0.5);
            border-radius: 30px;
            font-weight: 700;
            color: #667eea;
            cursor: pointer;
            transition: all 0.3s ease;
            z-index: 100;
            font-family: 'Orbitron', sans-serif;
            text-decoration: none;
            display: inline-block;
            font-size: 0.9rem;
        }

        .back-button:hover {
            background: #667eea;
            color: #fff;
            transform: scale(1.05);
        }

        .back-button::before {
            content: '← ';
            margin-right: 5px;
        }

        /* Start Button (appears on active level) */
        .start-level-btn {
            position: absolute;
            bottom: -50px;
            left: 50%;
            transform: translateX(-50%);
            padding: 0.8rem 2rem;
            background: #667eea;
            color: #fff;
            border: none;
            border-radius: 30px;
            font-weight: 700;
            cursor: pointer;
            font-family: 'Orbitron', sans-serif;
            font-size: 1rem;
            opacity: 0;
            transition: all 0.5s ease;
            white-space: nowrap;
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
        }

        .level-node.active .start-level-btn {
            opacity: 1;
        }

        .start-level-btn:hover {
            background: #764ba2;
            transform: translateX(-50%) scale(1.05);
        }

        /* Progress Bar */
        .progress-bar-container {
            position: absolute;
            top: 20px;
            right: 20px;
            width: 250px;
            z-index: 100;
        }

        .progress-text {
            text-align: center;
            color: #fff;
            font-weight: 600;
            margin-bottom: 0.5rem;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
            font-size: 0.9rem;
        }

        .progress {
            height: 20px;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 15px;
            overflow: hidden;
        }

        .progress-bar {
            background: linear-gradient(90deg, #4CAF50, #8BC34A);
            font-weight: 700;
            transition: width 0.5s ease;
            font-size: 0.85rem;
            line-height: 20px;
        }

        /* Clouds */
        .cloud {
            position: absolute;
            background: rgba(255, 255, 255, 0.7);
            border-radius: 100px;
            opacity: 0.8;
            animation: cloudFloat 30s linear infinite;
        }

        @keyframes cloudFloat {
            0% { left: -200px; }
            100% { left: 110%; }
        }

        .cloud::before,
        .cloud::after {
            content: '';
            position: absolute;
            background: rgba(255, 255, 255, 0.7);
            border-radius: 100px;
        }

        /* Houses */
        .house {
            position: absolute;
            top: auto;
            bottom: 60px;
            width: 120px;
            height: auto;
            z-index: 2;
            filter: drop-shadow(0 5px 15px rgba(0,0,0,0.2));
            animation: houseMove linear;
        }

        @keyframes houseMove {
            0% {
                right: -200px;
                opacity: 0;
            }
            5% {
                opacity: 1;
            }
            95% {
                opacity: 1;
            }
            100% {
                right: 110%;
                opacity: 0;
            }
        }

        .house img {
            width: 100%;
            height: auto;
            display: block;
        }

        /* Responsive */
        @media (max-width: 1400px) {
            .level-node {
                width: 110px;
                height: 110px;
            }

            .level-node.active {
                width: 260px;
                height: 260px;
            }

            .level-title {
                font-size: 2rem;
            }

            .level-node.active .level-title {
                font-size: 3.5rem;
            }
        }

        @media (max-width: 1024px) {
            .levels-container {
                gap: 10px;
            }

            .level-node {
                width: 90px;
                height: 90px;
            }

            .level-node.active {
                width: 220px;
                height: 220px;
            }

            .level-title {
                font-size: 1.8rem;
            }

            .level-node.active .level-title {
                font-size: 3rem;
            }

            .house {
                width: 90px;
            }
        }

        @media (max-width: 768px) {
            .header-title {
                font-size: 1.6rem;
            }

            .header-subtitle {
                font-size: 0.9rem;
            }

            .levels-container {
                flex-wrap: wrap;
                height: auto;
                gap: 15px;
                padding: 20px;
            }

            .level-node {
                width: 80px;
                height: 80px;
            }

            .level-node.active {
                width: 180px;
                height: 180px;
            }

            .level-title {
                font-size: 1.5rem;
            }

            .level-node.active .level-title {
                font-size: 2.5rem;
            }

            .truck {
                width: 70px;
                height: 50px;
            }

            .house {
                width: 70px;
            }
        }

        /* Truck */
        .truck {
            position: absolute;
            bottom: 100px;
            left: 0;
            width: 250px;
            height: auto;
            z-index: 10;
            transition: left 1.2s cubic-bezier(0.4, 0, 0.2, 1);
            filter: drop-shadow(0 5px 10px rgba(0,0,0,0.3));
            animation: tuckern 0.2s infinite ease-in-out;
        }

        /* Entferne den placeholder Block komplett */

        /* Wackel-Animation */
        @keyframes tuckern {
            0%, 100% {
                transform: translateY(0) translateX(-50%);
            }
            50% {
                transform: translateY(-2px) translateX(-50%);
            }
        }

        /* SVG Styling */
        .truck img {
            width: 100%;
            height: auto;
            display: block;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .truck {
                width: 100px;
            }
        }

        @media (max-width: 480px) {
            .truck {
                width: 80px;
            }
        }
    </style>
</head>
<body>
<!-- Sky Section -->
<div class="sky-section" id="skySection">
    <!-- Clouds -->
    <div class="cloud" style="width: 100px; height: 50px; top: 10%; animation-delay: 0s;"></div>
    <div class="cloud" style="width: 120px; height: 60px; top: 20%; animation-delay: 8s;"></div>
    <div class="cloud" style="width: 90px; height: 45px; top: 30%; animation-delay: 16s;"></div>
    <!-- Houses will be dynamically added here -->
</div>

<!-- Grass Section -->
<div class="grass-section" id="grassSection">  <!-- NEU: id hinzugefügt -->
</div>

<!-- Header -->
<div class="level-select-header">
    <h1 class="header-title">#CITY - Level Auswahl</h1>
    <p class="header-subtitle">Wähle dein Level und starte deine Hash-Map Reise</p>
</div>

<!-- Back Button -->
<a href="Start" class="back-button">Zurück</a>


<div class="progress-bar-container">
    <div class="progress-text">Fortschritt: <span id="progressText">1/8 Levels abgeschlossen</span></div>
    <div class="progress">
        <div class="progress-bar" role="progressbar" id="progressBar" style="width: 12.5%">12.5%</div>
    </div>
</div>

<!-- Road Container -->
<div class="road-container">
    <!-- Main Road -->
    <div class="main-road">
        <!-- Road Lines -->
        <div class="road-line" style="animation-delay: 0s;"></div>
        <div class="road-line" style="animation-delay: 0.5s;"></div>
        <div class="road-line" style="animation-delay: 1s;"></div>
        <div class="road-line" style="animation-delay: 1.5s;"></div>
    </div>

    <!-- Truck -->
    <!--<div class="truck placeholder" id="truck"></div>-->
    <div id="truck" class="truck">
        <img src="./assets/Postauto.svg" alt="Postauto">
    </div>

</div>

<!-- Levels Container -->
<div class="levels-container" id="levelsContainer">
    <!-- Levels will be generated by JavaScript -->
</div>

<!-- Progress Bar -->


<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    $(document).ready(function() {
        // Level Data
        const levelData = [
            {
                title: 'Einführung',
                description: 'Lerne die Grundlagen von Hash-Funktionen',
                icon: '',
                unlocked: true,
                completed: true
            },
            {
                title: 'Hash-Kollisionen',
                description: 'Was passiert bei gleichen Hash-Werten?',
                icon: '',
                unlocked: true,
                completed: false
            },
            {
                title: 'Separate Chaining',
                description: 'Kollisionen mit verketteten Listen lösen',
                icon: '',
                unlocked: true,
                completed: false
            },
            {
                title: 'Open Addressing',
                description: 'Alternative Kollisionsbehandlung',
                icon: '',
                unlocked: false,
                completed: false
            },
            {
                title: 'Load Factor',
                description: 'Wann muss die Hash Map wachsen?',
                icon: '',
                unlocked: false,
                completed: false
            },
            {
                title: 'Rehashing',
                description: 'Die Hash Map dynamisch vergrößern',
                icon: '',
                unlocked: false,
                completed: false
            },
            {
                title: 'Optimierung',
                description: 'Performance-Tuning für Hash-Funktionen',
                icon: '',
                unlocked: false,
                completed: false
            },
            {
                title: 'Master Challenge',
                description: 'Baue die perfekte HashCity!',
                icon: '',
                unlocked: false,
                completed: false
            }
        ];

        let currentLevel = 0;

        // Grass decoration system
        // Grass decoration system - dynamic spawning
        const flowerColors = [
            '#FF69B4', '#FFD700', '#FF6347', '#87CEEB',
            '#DDA0DD', '#FFA500', '#FF1493', '#00CED1'
        ];

        function createGrassBlade() {
            const bottom = Math.random() * 80 + '%';
            const height = 15 + Math.random() * 25;
            const duration = 2; // Fixe 2s - exakt wie Straßenmarkierungen

            const grassBlade = $(`
        <div class="grass-blade" style="
            bottom: ${bottom};
            height: ${height}px;
            animation: grassMove ${duration}s linear;
        "></div>
    `);

            $('#grassSection').append(grassBlade);

            setTimeout(() => {
                grassBlade.remove();
            }, duration * 1000);
        }

        function createFlower() {
            const bottom = Math.random() * 80 + '%';
            const color = flowerColors[Math.floor(Math.random() * flowerColors.length)];
            const scale = 0.8 + Math.random() * 0.4;
            const duration = 2; // Fixe 2s - exakt wie Straßenmarkierungen

            const flower = $(`
        <div class="flower" style="
            bottom: ${bottom};
            transform: scale(${scale});
            animation: grassMove ${duration}s linear;
        ">
            <div class="flower-stem"></div>
            <div class="flower-head" style="background: ${color};">
                <div class="flower-center"></div>
            </div>
        </div>
    `);

            $('#grassSection').append(flower);

            setTimeout(() => {
                flower.remove();
            }, duration * 1000);
        }

        function startGrassAnimation() {
            // Initiale Elemente
            for (let i = 0; i < 20; i++) {
                setTimeout(() => createGrassBlade(), Math.random() * 2000);
            }
            for (let i = 0; i < 5; i++) {
                setTimeout(() => createFlower(), Math.random() * 2000);
            }

            // Kontinuierliches Spawning
            setInterval(() => {
                for (let i = 0; i < 3; i++) {
                    createGrassBlade();
                }
            }, 200); // Alle 200ms 3 Grashalme

            setInterval(() => {
                createFlower();
            }, 800); // Alle 800ms eine Blume
        }



        // House spawning system
        const houseTypes = ['filled_house.svg', 'empty_house.svg'];



        function createHouse() {
            const houseType = houseTypes[Math.floor(Math.random() * houseTypes.length)];
            const duration = 15 + Math.random() * 1; //12 + Math.random() * 8; // 12-20 Sekunden
            const size = 100 + Math.random() * 100; // 100-200px Breite

            const house = $(`
                <div class="house" style="
                    width: ${size}px;
                    animation-duration: ${duration}s;
                ">
                    <img src="./assets/${houseType}" alt="House">
                </div>
            `);

            $('#skySection').append(house);

            // Entferne das Haus nach Animation
            setTimeout(() => {
                house.remove();
            }, duration * 1000);
        }

        function startHouseSpawning() {
            // Initiale Häuser für sofortigen Start
            createHouse();
            setTimeout(() => createHouse(), 2000);

            // Kontinuierliches Spawning mit variablen Abständen
            function scheduleNextHouse() {
                const delay = 4000 + Math.random() * 4000; // 2-6 Sekunden Abstand zwischen Häusern
                setTimeout(() => {
                    createHouse();
                    scheduleNextHouse(); // Nächstes Haus planen
                }, delay);
            }

            scheduleNextHouse();
        }

        // Generate Levels
        function generateLevels() {
            const container = $('#levelsContainer');
            container.empty();

            levelData.forEach((level, index) => {
                const isActive = index === currentLevel;
                const lockedClass = level.unlocked ? '' : 'locked';
                const activeClass = isActive ? 'active' : '';
                const completedIcon = level.completed ? '✔️' : level.icon;

                const levelNode = $(`
                        <div class="level-node ${lockedClass} ${activeClass}" data-level="${index}">
                            <span class="level-status">${level.unlocked ? completedIcon : '❌'}</span>
                            <div class="level-number">Level</div>
                            <div class="level-title">${index}</div>
                            <div class="level-details">
                                <div class="level-subtitle">${level.title}</div>
                                <div class="level-description">${level.description}</div>
                            </div>
                            <button class="start-level-btn">Level starten</button>
                        </div>
                    `);

                container.append(levelNode);

                // Add connection dot
                const dotPosition = (100 / 8) * index + (100 / 16); // Center of each level slot
                const dot = $(`<div class="connection-dot" style="left: ${dotPosition}%;"></div>`);
                $('.road-container').append(dot);
            });
        }

        // Move truck to level
        function moveTruckToLevel(level) {
            const truck = $('#truck');
            const position = (100 / 8) * level + (100 / 16); // Center of level slot

            truck.css({
                'left': position + '%',
                'transform': 'translateX(-50%)'
            });
        }

        // Update progress bar
        function updateProgress() {
            const completed = levelData.filter(l => l.completed).length;
            const percentage = (completed / levelData.length) * 100;

            $('#progressBar').css('width', percentage + '%').text(Math.round(percentage) + '%');
            $('#progressText').text(`${completed}/${levelData.length} Levels abgeschlossen`);
        }

        // Level Node Click
        $(document).on('click', '.level-node', function(e) {
            if ($(e.target).hasClass('start-level-btn')) {
                return; // Let button handle its own click
            }

            const level = parseInt($(this).data('level'));
            const isLocked = $(this).hasClass('locked');

            if (isLocked) {
                // Shake animation for locked levels
                $(this).css('animation', 'shake 0.5s');
                setTimeout(() => {
                    $(this).css('animation', '');
                }, 500);
                return;
            }

            // Update current level
            currentLevel = level;

            // Update active state
            $('.level-node').removeClass('active');
            $(this).addClass('active');

            // Move truck
            moveTruckToLevel(level);
        });

        // Start Level Button
        $(document).on('click', '.start-level-btn', function(e) {
            e.stopPropagation();

            const level = currentLevel;
            $(this).text('Wird geladen...');
            $(this).prop('disabled', true);

            // Fade out effect
            $('body').css('transition', 'opacity 0.8s ease-out');
            setTimeout(() => {
                $('body').css('opacity', '0');
            }, 100);

            let level_name = "";

            switch(level) {
                case 0:
                    level_name = "Einführung";
            }

            // Redirect to game level
            setTimeout(() => {
                window.location.href = level_name;
                //alert(`Level ${level} wird gestartet!\n\n${levelData[level].title}\n${levelData[level].description}\n\nErsetze diese Zeile mit:\n`);
                $('body').css('opacity', '1');
                $(this).text('Level starten');
                $(this).prop('disabled', false);
            }, 1000);
        });

        // Keyboard Navigation
        $(document).keydown(function(e) {
            const unlockedLevels = levelData.map((l, i) => l.unlocked ? i : null).filter(i => i !== null);
            const currentIndex = unlockedLevels.indexOf(currentLevel);

            if (e.key === 'ArrowRight' && currentIndex < unlockedLevels.length - 1) {
                const nextLevel = unlockedLevels[currentIndex + 1];
                $(`.level-node[data-level="${nextLevel}"]`).click();
            } else if (e.key === 'ArrowLeft' && currentIndex > 0) {
                const prevLevel = unlockedLevels[currentIndex - 1];
                $(`.level-node[data-level="${prevLevel}"]`).click();
            } else if (e.key === 'Enter') {
                $('.level-node.active .start-level-btn').click();
            }
        });

        // Add shake animation
        const style = document.createElement('style');
        style.textContent = `
                @keyframes shake {
                    0%, 100% { transform: translateX(0); }
                    25% { transform: translateX(-10px); }
                    75% { transform: translateX(10px); }
                }
            `;
        document.head.appendChild(style);

        // Add cloud details (keep original delays from HTML)
        $('.cloud').each(function(index) {
            $(this).append(`
                    <div style="position: absolute; width: 70%; height: 70%; background: rgba(255,255,255,0.7); border-radius: 100px; left: 25%; top: -20%;"></div>
                    <div style="position: absolute; width: 60%; height: 60%; background: rgba(255,255,255,0.7); border-radius: 100px; right: 15%; top: -10%;"></div>
                `);
        });

        // Initialize
        generateLevels();
        moveTruckToLevel(0);
        updateProgress();
        startGrassAnimation(); // ← NEU: Generate grass and flowers

        startHouseSpawning(); // Start house animation
    });
</script>
</body>
</html>