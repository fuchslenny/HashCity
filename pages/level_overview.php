<?php
// Level Overview - HashCity
// Auto-basierte Navigation zwischen den Levels
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hash City - Level Übersicht</title>

    <!-- External Libraries -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2ecc71;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
            --bg-color: #87CEEB; /* Sky blue for background */
            --road-color: #2c3e50;
            --grass-color: #27ae60;
            --text-color: #2c3e50;
            --shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        body {
            background: linear-gradient(180deg, var(--bg-color) 0%, #98D8E8 50%, var(--grass-color) 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--text-color);
            min-height: 100vh;
            margin: 0;
            overflow-x: auto;
        }

        .level-overview-container {
            position: relative;
            width: 100%;
            min-height: 100vh;
            background:
                /* Clouds */
                    radial-gradient(ellipse 200px 100px at 20% 20%, rgba(255,255,255,0.8) 0%, transparent 50%),
                    radial-gradient(ellipse 150px 80px at 80% 30%, rgba(255,255,255,0.6) 0%, transparent 50%),
                    radial-gradient(ellipse 180px 90px at 50% 15%, rgba(255,255,255,0.7) 0%, transparent 50%),
                        /* Sky gradient */
                    linear-gradient(180deg, var(--bg-color) 0%, #98D8E8 70%, var(--grass-color) 100%);
        }

        .game-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, #2980b9 100%);
            color: white;
            padding: 1rem 0;
            box-shadow: var(--shadow);
            position: relative;
            z-index: 100;
        }

        .road-container {
            position: relative;
            width: 100%;
            height: 400px;
            margin: 50px 0;
            overflow-x: auto;
            overflow-y: hidden;
        }

        .road {
            position: absolute;
            width: 2000px; /* Breite für scrolling */
            height: 120px;
            background: var(--road-color);
            top: 50%;
            transform: translateY(-50%);
            border-radius: 10px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.3);
        }

        .road::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 4px;
            background: repeating-linear-gradient(
                    90deg,
                    #fff 0px,
                    #fff 30px,
                    transparent 30px,
                    transparent 60px
            );
            transform: translateY(-50%);
        }

        .level-station {
            position: absolute;
            width: 200px;
            height: 250px;
            top: -65px; /* Über der Straße positionieren */
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .level-station:hover {
            transform: translateY(-10px);
        }

        .level-building {
            width: 100%;
            height: 180px;
            background: linear-gradient(145deg, #ecf0f1, #bdc3c7);
            border-radius: 15px;
            box-shadow: var(--shadow);
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border: 3px solid #95a5a6;
            transition: all 0.3s ease;
        }

        .level-station.completed .level-building {
            background: linear-gradient(145deg, var(--secondary-color), #27ae60);
            border-color: var(--secondary-color);
            color: white;
        }

        .level-station.locked .level-building {
            background: linear-gradient(145deg, #7f8c8d, #95a5a6);
            border-color: #7f8c8d;
            opacity: 0.6;
            cursor: not-allowed;
        }

        .level-station.current .level-building {
            background: linear-gradient(145deg, var(--warning-color), #e67e22);
            border-color: var(--warning-color);
            color: white;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .level-icon {
            font-size: 3rem;
            margin-bottom: 0.5rem;
        }

        .level-title {
            font-weight: bold;
            font-size: 1.1rem;
            text-align: center;
            margin-bottom: 0.3rem;
        }

        .level-subtitle {
            font-size: 0.9rem;
            text-align: center;
            opacity: 0.8;
        }

        .street-sign {
            position: absolute;
            bottom: -40px;
            left: 50%;
            transform: translateX(-50%);
            background: var(--warning-color);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: bold;
            font-size: 0.9rem;
            box-shadow: var(--shadow);
        }

        .street-sign::before {
            content: '';
            position: absolute;
            bottom: -20px;
            left: 50%;
            transform: translateX(-50%);
            width: 4px;
            height: 20px;
            background: #8b4513;
        }

        .car {
            position: absolute;
            width: 80px;
            height: 40px;
            background: var(--danger-color);
            border-radius: 20px 20px 5px 5px;
            top: 50%;
            transform: translateY(-50%);
            transition: all 0.5s ease;
            z-index: 50;
            cursor: pointer;
        }

        .car::before {
            content: '';
            position: absolute;
            top: -15px;
            left: 15px;
            width: 50px;
            height: 20px;
            background: #3498db;
            border-radius: 10px 10px 0 0;
        }

        .car::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 10px;
            width: 15px;
            height: 15px;
            background: #2c3e50;
            border-radius: 50%;
            box-shadow: 45px 0 0 #2c3e50;
        }

        .car.moving {
            animation: drive 0.1s infinite;
        }

        @keyframes drive {
            0%, 100% { transform: translateY(-50%) rotate(0deg); }
            50% { transform: translateY(-52px) rotate(1deg); }
        }

        .progress-indicator {
            position: fixed;
            top: 100px;
            right: 20px;
            background: white;
            border-radius: 15px;
            padding: 1rem;
            box-shadow: var(--shadow);
            z-index: 200;
            min-width: 200px;
        }

        .progress-item {
            display: flex;
            align-items: center;
            margin: 0.5rem 0;
            padding: 0.3rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .progress-item.completed {
            background: rgba(46, 204, 113, 0.1);
            color: var(--secondary-color);
        }

        .progress-item.current {
            background: rgba(243, 156, 18, 0.1);
            color: var(--warning-color);
            font-weight: bold;
        }

        .progress-item.locked {
            opacity: 0.5;
        }

        .controls {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: white;
            border-radius: 25px;
            padding: 1rem 2rem;
            box-shadow: var(--shadow);
            z-index: 200;
        }

        .control-btn {
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            margin: 0 0.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 1.2rem;
        }

        .control-btn:hover {
            background: #2980b9;
            transform: scale(1.1);
        }

        .control-btn:disabled {
            background: #bdc3c7;
            cursor: not-allowed;
            transform: none;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .road {
                width: 1500px;
            }

            .level-station {
                width: 150px;
                height: 200px;
            }

            .level-building {
                height: 140px;
            }

            .level-icon {
                font-size: 2rem;
            }

            .car {
                width: 60px;
                height: 30px;
            }

            .progress-indicator {
                position: relative;
                top: auto;
                right: auto;
                margin: 1rem;
                width: calc(100% - 2rem);
            }
        }
    </style>
</head>
<body>
<!-- Header -->
<div class="game-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="mb-0"><i class="fas fa-city"></i> Hash City - Level Übersicht</h1>
                <p class="mb-0">Wähle dein nächstes Abenteuer in der Hash-Welt</p>
            </div>
            <div class="col-md-4 text-end">
                <div class="d-flex align-items-center justify-content-end">
                    <i class="fas fa-star text-warning me-2"></i>
                    <span id="xp-display" class="fw-bold">XP: 0</span>
                    <a href="?page=home" class="btn btn-outline-light ms-3">
                        <i class="fas fa-home"></i> Home
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Level Overview Container -->
<div class="level-overview-container">
    <!-- Road with Levels -->
    <div class="road-container" id="road-container">
        <div class="road"></div>

        <!-- Auto -->
        <div class="car" id="player-car"></div>

        <!-- Level 1: Hash-Straße -->
        <div class="level-station current" data-level="1" style="left: 200px;">
            <div class="level-building">
                <div class="level-icon">🏠</div>
                <div class="level-title">Stadtgründung</div>
                <div class="level-subtitle">Grundlagen der Hash-Funktion</div>
            </div>
            <div class="street-sign">Hash-Straße</div>
        </div>

        <!-- Level 2: Kollisions-Allee -->
        <div class="level-station locked" data-level="2" style="left: 500px;">
            <div class="level-building">
                <div class="level-icon">⚠️</div>
                <div class="level-title">Erste Konflikte</div>
                <div class="level-subtitle">Hash-Kollisionen verstehen</div>
            </div>
            <div class="street-sign">Kollisions-Allee</div>
        </div>

        <!-- Level 3: Linear-Probing-Weg -->
        <div class="level-station locked" data-level="3" style="left: 800px;">
            <div class="level-building">
                <div class="level-icon">🔄</div>
                <div class="level-title">Linear Probing</div>
                <div class="level-subtitle">Kollisionen elegant lösen</div>
            </div>
            <div class="street-sign">Linear-Probing-Weg</div>
        </div>

        <!-- Level 4: Chaining-Boulevard -->
        <div class="level-station locked" data-level="4" style="left: 1100px;">
            <div class="level-building">
                <div class="level-icon">⛓️</div>
                <div class="level-title">Chaining</div>
                <div class="level-subtitle">Verkettete Listen nutzen</div>
            </div>
            <div class="street-sign">Chaining-Boulevard</div>
        </div>

        <!-- Level 5: Performance-Platz -->
        <div class="level-station locked" data-level="5" style="left: 1400px;">
            <div class="level-building">
                <div class="level-icon">⚡</div>
                <div class="level-title">Optimierung</div>
                <div class="level-subtitle">Load Factor & Resize</div>
            </div>
            <div class="street-sign">Performance-Platz</div>
        </div>

        <!-- Level 6: Hash-Meister-Straße -->
        <div class="level-station locked" data-level="6" style="left: 1700px;">
            <div class="level-building">
                <div class="level-icon">👑</div>
                <div class="level-title">Hash-Meister</div>
                <div class="level-subtitle">Fortgeschrittene Techniken</div>
            </div>
            <div class="street-sign">Hash-Meister-Straße</div>
        </div>
    </div>
</div>

<!-- Progress Indicator -->
<div class="progress-indicator">
    <h5><i class="fas fa-trophy"></i> Fortschritt</h5>
    <div class="progress-item current">
        <i class="fas fa-play-circle me-2"></i>
        Level 1: Stadtgründung
    </div>
    <div class="progress-item locked">
        <i class="fas fa-lock me-2"></i>
        Level 2: Erste Konflikte
    </div>
    <div class="progress-item locked">
        <i class="fas fa-lock me-2"></i>
        Level 3: Linear Probing
    </div>
    <div class="progress-item locked">
        <i class="fas fa-lock me-2"></i>
        Level 4: Chaining
    </div>
    <div class="progress-item locked">
        <i class="fas fa-lock me-2"></i>
        Level 5: Optimierung
    </div>
    <div class="progress-item locked">
        <i class="fas fa-lock me-2"></i>
        Level 6: Hash-Meister
    </div>
</div>

<!-- Controls -->
<div class="controls">
    <button class="control-btn" id="move-left" title="Nach links fahren">
        <i class="fas fa-arrow-left"></i>
    </button>
    <button class="control-btn" id="select-level" title="Level auswählen">
        <i class="fas fa-play"></i>
    </button>
    <button class="control-btn" id="move-right" title="Nach rechts fahren">
        <i class="fas fa-arrow-right"></i>
    </button>
</div>

<script>
    class LevelOverview {
        constructor() {
            this.currentLevel = 1;
            this.maxUnlockedLevel = 1;
            this.carPosition = 200; // Startposition des Autos
            this.levels = [
                { id: 1, name: "Stadtgründung", street: "Hash-Straße", position: 200 },
                { id: 2, name: "Erste Konflikte", street: "Kollisions-Allee", position: 500 },
                { id: 3, name: "Linear Probing", street: "Linear-Probing-Weg", position: 800 },
                { id: 4, name: "Chaining", street: "Chaining-Boulevard", position: 1100 },
                { id: 5, name: "Optimierung", street: "Performance-Platz", position: 1400 },
                { id: 6, name: "Hash-Meister", street: "Hash-Meister-Straße", position: 1700 }
            ];

            this.init();
        }

        init() {
            this.loadProgress();
            this.updateCarPosition();
            this.bindEvents();
            this.updateUI();
        }

        loadProgress() {
            // Lade Fortschritt aus localStorage
            const savedProgress = localStorage.getItem('hashcity_progress');
            if (savedProgress) {
                try {
                    const progress = JSON.parse(savedProgress);
                    this.maxUnlockedLevel = progress.maxUnlockedLevel || 1;
                    this.currentLevel = progress.currentLevel || 1;

                    // Sync with server
                    this.syncWithServer(progress);
                } catch (error) {
                    console.log('Error loading progress:', error);
                }
            }
        }

        syncWithServer(localProgress) {
            // Get server progress and merge
            fetch('?page=api&action=get_progress')
                .then(response => response.json())
                .then(serverProgress => {
                    const merged = {
                        maxUnlockedLevel: Math.max(
                            localProgress.maxUnlockedLevel || 1,
                            serverProgress.maxUnlockedLevel || 1
                        ),
                        currentLevel: localProgress.currentLevel || serverProgress.currentLevel || 1,
                        completedLevels: [...(localProgress.completedLevels || []), ...(serverProgress.completedLevels || [])],
                        totalXP: Math.max(localProgress.totalXP || 0, serverProgress.totalXP || 0),
                        achievements: [...(localProgress.achievements || []), ...(serverProgress.achievements || [])]
                    };

                    this.maxUnlockedLevel = merged.maxUnlockedLevel;
                    this.currentLevel = Math.min(merged.currentLevel, this.maxUnlockedLevel);

                    this.saveProgress();
                    this.updateUI();
                    this.updateCarPosition();
                })
                .catch(error => console.log('Server sync failed:', error));
        }

        saveProgress() {
            const progress = {
                maxUnlockedLevel: this.maxUnlockedLevel,
                currentLevel: this.currentLevel,
                completedLevels: [],
                totalXP: 0,
                achievements: []
            };

            localStorage.setItem('hashcity_progress', JSON.stringify(progress));

            // Save to server
            fetch('?page=api&action=save_progress', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(progress)
            }).catch(error => console.log('Server save failed:', error));
        }

        bindEvents() {
            // Tastatur-Events
            document.addEventListener('keydown', (e) => {
                switch(e.key) {
                    case 'ArrowLeft':
                        e.preventDefault();
                        this.moveLeft();
                        break;
                    case 'ArrowRight':
                        e.preventDefault();
                        this.moveRight();
                        break;
                    case 'Enter':
                    case ' ':
                        e.preventDefault();
                        this.selectLevel();
                        break;
                }
            });

            // Button-Events
            const moveLeftBtn = document.getElementById('move-left');
            const moveRightBtn = document.getElementById('move-right');
            const selectLevelBtn = document.getElementById('select-level');

            if (moveLeftBtn) moveLeftBtn.addEventListener('click', () => this.moveLeft());
            if (moveRightBtn) moveRightBtn.addEventListener('click', () => this.moveRight());
            if (selectLevelBtn) selectLevelBtn.addEventListener('click', () => this.selectLevel());

            // Level-Station Clicks
            document.querySelectorAll('.level-station').forEach(station => {
                station.addEventListener('click', (e) => {
                    const level = parseInt(station.dataset.level);
                    if (level <= this.maxUnlockedLevel) {
                        this.currentLevel = level;
                        this.updateCarPosition();
                        this.updateUI();
                    }
                });

                // Double-click to enter level
                station.addEventListener('dblclick', (e) => {
                    const level = parseInt(station.dataset.level);
                    if (level <= this.maxUnlockedLevel) {
                        this.currentLevel = level;
                        this.selectLevel();
                    }
                });
            });
        }

        moveLeft() {
            if (this.currentLevel > 1) {
                this.currentLevel--;
                this.updateCarPosition();
                this.updateUI();
            }
        }

        moveRight() {
            if (this.currentLevel < this.maxUnlockedLevel) {
                this.currentLevel++;
                this.updateCarPosition();
                this.updateUI();
            }
        }

        updateCarPosition() {
            const car = document.getElementById('player-car');
            if (!car) return;

            const targetPosition = this.levels[this.currentLevel - 1]?.position || 200;

            car.classList.add('moving');
            car.style.left = targetPosition + 'px';

            // Scroll zur Position
            const roadContainer = document.getElementById('road-container');
            if (roadContainer) {
                roadContainer.scrollLeft = Math.max(0, targetPosition - 400);
            }

            setTimeout(() => {
                car.classList.remove('moving');
            }, 500);
        }

        updateUI() {
            // Update Level Stations
            document.querySelectorAll('.level-station').forEach(station => {
                const level = parseInt(station.dataset.level);
                station.classList.remove('current', 'completed', 'locked');

                if (level === this.currentLevel) {
                    station.classList.add('current');
                } else if (level < this.currentLevel) {
                    station.classList.add('completed');
                } else if (level > this.maxUnlockedLevel) {
                    station.classList.add('locked');
                }
            });

            // Update Progress Indicator
            document.querySelectorAll('.progress-item').forEach((item, index) => {
                const level = index + 1;
                item.classList.remove('current', 'completed', 'locked');

                if (level === this.currentLevel) {
                    item.classList.add('current');
                } else if (level < this.currentLevel) {
                    item.classList.add('completed');
                } else if (level > this.maxUnlockedLevel) {
                    item.classList.add('locked');
                }
            });

            // Update Controls
            const moveLeftBtn = document.getElementById('move-left');
            const moveRightBtn = document.getElementById('move-right');

            if (moveLeftBtn) moveLeftBtn.disabled = this.currentLevel <= 1;
            if (moveRightBtn) moveRightBtn.disabled = this.currentLevel >= this.maxUnlockedLevel;
        }

        selectLevel() {
            if (this.currentLevel <= this.maxUnlockedLevel) {
                // Weiterleitung zum gewählten Level mit korrektem Routing
                window.location.href = `?page=level&level=${this.currentLevel}`;
            } else {
                alert('Dieses Level ist noch nicht freigeschaltet!');
            }
        }

        unlockNextLevel() {
            if (this.maxUnlockedLevel < this.levels.length) {
                this.maxUnlockedLevel++;
                this.saveProgress();
                this.updateUI();
            }
        }
    }

    // Initialize Level Overview
    document.addEventListener('DOMContentLoaded', () => {
        window.levelOverview = new LevelOverview();
    });
</script>
</body>
</html>