<?php
// Dynamic Level System - HashCity
// Generiert Levels basierend auf URL-Parameter

// Level-Parameter aus URL holen und validieren
$level = isset($_GET['level']) ? intval($_GET['level']) : 1;
$level = max(1, min(6, $level)); // Level 1-6 verfügbar

// Level-Konfiguration
$levelConfig = [
    1 => [
        'title' => 'Stadtgründung',
        'subtitle' => 'Hash-Map Grundlagen',
        'street' => 'Hash-Straße',
        'description' => 'Lerne die Grundlagen von Hash-Funktionen kennen und weise 5 Einwohner ihren Häusern zu.',
        'residents' => ['Alice', 'Bob', 'Carol', 'Dave', 'Eve'],
        'houses' => 7,
        'hashFunction' => 'ascii_sum_mod',
        'objective' => 'Ziehe alle Einwohner in die korrekten Häuser basierend auf der Hash-Funktion.',
        'hint' => 'Die Hash-Funktion berechnet: h(name) = Σ ASCII-Werte % 7'
    ],
    2 => [
        'title' => 'Der erste Konflikt',
        'subtitle' => 'Hash-Kollisionen',
        'street' => 'Kollisions-Allee',
        'description' => 'Entdecke was passiert, wenn zwei Namen zum gleichen Hash-Wert führen.',
        'residents' => ['Alice', 'Bob', 'Carol', 'Dave', 'Eve', 'Grace', 'Henry'],
        'houses' => 7,
        'hashFunction' => 'ascii_sum_mod',
        'objective' => 'Erkenne Kollisionen und verstehe das Problem der Überschreibung.',
        'hint' => 'Grace und Henry haben beide Hash-Wert #3 - was nun?'
    ],
    3 => [
        'title' => 'Linear Probing',
        'subtitle' => 'Erste Kollisionsstrategie',
        'street' => 'Linear-Probing-Weg',
        'description' => 'Löse Kollisionen mit Linear Probing: Suche den nächsten freien Platz.',
        'residents' => ['Alice', 'Bob', 'Carol', 'Dave', 'Eve', 'Grace', 'Henry', 'Ivy'],
        'houses' => 7,
        'hashFunction' => 'ascii_sum_mod',
        'objective' => 'Verwende Linear Probing um alle Einwohner unterzubringen.',
        'hint' => 'Bei Kollision: Probiere Index+1, Index+2, Index+3... bis ein freier Platz gefunden wird.'
    ],
    4 => [
        'title' => 'Chaining',
        'subtitle' => 'Verkettete Listen',
        'street' => 'Chaining-Boulevard',
        'description' => 'Löse Kollisionen durch Verkettung: Mehrere Einwohner pro Haus.',
        'residents' => ['Alice', 'Bob', 'Carol', 'Dave', 'Eve', 'Grace', 'Henry', 'Ivy', 'Jack'],
        'houses' => 7,
        'hashFunction' => 'ascii_sum_mod',
        'objective' => 'Verwende Chaining um alle Einwohner unterzubringen.',
        'hint' => 'Mehrere Einwohner können im gleichen Haus wohnen (verkettete Liste).'
    ],
    5 => [
        'title' => 'Performance-Optimierung',
        'subtitle' => 'Load Factor & Resize',
        'street' => 'Performance-Platz',
        'description' => 'Lerne über Load Factor und dynamisches Resizing der Hash-Tabelle.',
        'residents' => ['Alice', 'Bob', 'Carol', 'Dave', 'Eve', 'Grace', 'Henry', 'Ivy', 'Jack', 'Kate'],
        'houses' => 7,
        'hashFunction' => 'ascii_sum_mod',
        'objective' => 'Erkenne wann die Hash-Tabelle zu voll wird und erweitert werden muss.',
        'hint' => 'Load Factor = Einwohner / Häuser. Bei > 0.75 sollte resized werden.'
    ],
    6 => [
        'title' => 'Hash-Meister',
        'subtitle' => 'Fortgeschrittene Techniken',
        'street' => 'Hash-Meister-Straße',
        'description' => 'Meistere verschiedene Hash-Funktionen und Kollisionsstrategien.',
        'residents' => ['Alice', 'Bob', 'Carol', 'Dave', 'Eve', 'Grace', 'Henry', 'Ivy', 'Jack', 'Kate', 'Leo'],
        'houses' => 11,
        'hashFunction' => 'ascii_sum_mod',
        'objective' => 'Verwende fortgeschrittene Techniken für optimale Hash-Performance.',
        'hint' => 'Experimentiere mit verschiedenen Hash-Funktionen und Kollisionsstrategien.'
    ]
];

// Prüfe ob Level existiert
if (!isset($levelConfig[$level])) {
    header('Location: ?page=levels');
    exit;
}

$currentLevel = $levelConfig[$level];
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hash City - <?php echo htmlspecialchars($currentLevel['title']); ?></title>

    <!-- External Libraries -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js"></script>

    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2ecc71;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
            --bg-color: #87CEEB;
            --road-color: #2c3e50;
            --grass-color: #27ae60;
            --text-color: #2c3e50;
            --shadow: 0 4px 15px rgba(0,0,0,0.1);
            --house-color: #ecf0f1;
            --house-border: #bdc3c7;
        }

        body {
            background: linear-gradient(180deg, var(--bg-color) 0%, #98D8E8 50%, var(--grass-color) 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--text-color);
            min-height: 100vh;
            margin: 0;
        }

        .game-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, #2980b9 100%);
            color: white;
            padding: 1rem 0;
            box-shadow: var(--shadow);
            position: relative;
            z-index: 100;
        }

        .level-container {
            background:
                    radial-gradient(ellipse 200px 100px at 20% 20%, rgba(255,255,255,0.8) 0%, transparent 50%),
                    radial-gradient(ellipse 150px 80px at 80% 30%, rgba(255,255,255,0.6) 0%, transparent 50%),
                    linear-gradient(180deg, var(--bg-color) 0%, #98D8E8 70%, var(--grass-color) 100%);
            min-height: calc(100vh - 80px);
            padding: 2rem 0;
        }

        .street-header {
            background: var(--warning-color);
            color: white;
            padding: 1rem;
            border-radius: 15px 15px 0 0;
            text-align: center;
            font-weight: bold;
            font-size: 1.2rem;
            box-shadow: var(--shadow);
        }

        .level-info {
            background: white;
            padding: 1.5rem;
            border-radius: 0 0 15px 15px;
            box-shadow: var(--shadow);
            margin-bottom: 2rem;
        }

        .city-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 1rem;
            margin: 2rem 0;
            padding: 2rem;
            background: rgba(255,255,255,0.9);
            border-radius: 15px;
            box-shadow: var(--shadow);
        }

        .house {
            width: 120px;
            height: 140px;
            background: var(--house-color);
            border: 3px solid var(--house-border);
            border-radius: 15px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: relative;
            transition: all 0.3s ease;
            cursor: pointer;
            min-height: 140px;
        }

        .house::before {
            content: '';
            position: absolute;
            top: -15px;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 0;
            border-left: 50px solid transparent;
            border-right: 50px solid transparent;
            border-bottom: 30px solid var(--danger-color);
        }

        .house-number {
            position: absolute;
            top: 5px;
            left: 50%;
            transform: translateX(-50%);
            background: var(--primary-color);
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 0.9rem;
        }

        .house-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 5px;
            margin-top: 30px;
        }

        .house.occupied {
            background: linear-gradient(145deg, var(--secondary-color), #27ae60);
            border-color: var(--secondary-color);
            color: white;
        }

        .house.collision {
            background: linear-gradient(145deg, var(--danger-color), #c0392b);
            border-color: var(--danger-color);
            color: white;
            animation: shake 0.5s ease-in-out;
        }

        .house.probing {
            background: linear-gradient(145deg, var(--warning-color), #e67e22);
            border-color: var(--warning-color);
            color: white;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        .resident {
            width: 60px;
            height: 60px;
            background: linear-gradient(145deg, #3498db, #2980b9);
            border: 2px solid white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 0.8rem;
            cursor: grab;
            transition: all 0.3s ease;
            box-shadow: var(--shadow);
            user-select: none;
        }

        .resident:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(0,0,0,0.2);
        }

        .resident.dragging {
            cursor: grabbing;
            transform: rotate(5deg) scale(1.1);
            z-index: 1000;
        }

        .residents-pool {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: var(--shadow);
            margin-bottom: 2rem;
        }

        .residents-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            justify-content: center;
        }

        .hash-calculator {
            background: white;
            padding: 1.5rem;
            border-radius: 15px;
            box-shadow: var(--shadow);
            margin: 1rem 0;
        }

        .calculation-step {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 10px;
            margin: 0.5rem 0;
            border-left: 4px solid var(--primary-color);
            font-family: 'Courier New', monospace;
        }

        .controls {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: white;
            border-radius: 15px;
            padding: 1rem;
            box-shadow: var(--shadow);
            z-index: 200;
        }

        .control-btn {
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 10px;
            padding: 0.5rem 1rem;
            margin: 0.25rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .control-btn:hover {
            background: #2980b9;
            transform: translateY(-2px);
        }

        .control-btn.success {
            background: var(--secondary-color);
        }

        .control-btn.danger {
            background: var(--danger-color);
        }

        .progress-bar {
            background: #ecf0f1;
            height: 10px;
            border-radius: 5px;
            overflow: hidden;
            margin: 1rem 0;
        }

        .progress-fill {
            background: linear-gradient(90deg, var(--secondary-color), #27ae60);
            height: 100%;
            width: 0%;
            transition: width 0.5s ease;
        }

        .stats-panel {
            background: white;
            padding: 1rem;
            border-radius: 15px;
            box-shadow: var(--shadow);
            margin: 1rem 0;
        }

        .stat-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem 0;
            border-bottom: 1px solid #ecf0f1;
        }

        .stat-item:last-child {
            border-bottom: none;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .city-grid {
                grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
                gap: 0.5rem;
                padding: 1rem;
            }

            .house {
                width: 100px;
                height: 120px;
            }

            .resident {
                width: 50px;
                height: 50px;
                font-size: 0.7rem;
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
                <h1 class="mb-0">
                    <i class="fas fa-city"></i>
                    Level <?php echo $level; ?>: <?php echo htmlspecialchars($currentLevel['title']); ?>
                </h1>
                <p class="mb-0"><?php echo htmlspecialchars($currentLevel['subtitle']); ?></p>
            </div>
            <div class="col-md-4 text-end">
                <a href="?page=levels" class="btn btn-outline-light">
                    <i class="fas fa-arrow-left"></i> Zurück zur Übersicht
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Level Container -->
<div class="level-container">
    <div class="container">
        <!-- Street Header -->
        <div class="street-header">
            <i class="fas fa-road"></i> <?php echo htmlspecialchars($currentLevel['street']); ?>
        </div>

        <!-- Level Info -->
        <div class="level-info">
            <div class="row">
                <div class="col-md-8">
                    <h4>Aufgabe:</h4>
                    <p><?php echo htmlspecialchars($currentLevel['description']); ?></p>
                    <p><strong>Ziel:</strong> <?php echo htmlspecialchars($currentLevel['objective']); ?></p>
                </div>
                <div class="col-md-4">
                    <div class="progress-bar">
                        <div class="progress-fill" id="progress-fill"></div>
                    </div>
                    <small class="text-muted">Fortschritt: <span id="progress-text">0%</span></small>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Main Game Area -->
            <div class="col-lg-8">
                <!-- Residents Pool -->
                <div class="residents-pool">
                    <h5><i class="fas fa-users"></i> Einwohner</h5>
                    <p class="text-muted"><?php echo htmlspecialchars($currentLevel['hint']); ?></p>
                    <div class="residents-grid" id="residents-pool">
                        <?php foreach($currentLevel['residents'] as $resident): ?>
                            <div class="resident" data-name="<?php echo htmlspecialchars($resident); ?>">
                                <?php echo htmlspecialchars($resident); ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- City Grid -->
                <div class="city-grid" id="city-grid">
                    <?php for($i = 0; $i < $currentLevel['houses']; $i++): ?>
                        <div class="house" data-index="<?php echo $i; ?>">
                            <div class="house-number"><?php echo $i; ?></div>
                            <div class="house-content"></div>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>

            <!-- Side Panel -->
            <div class="col-lg-4">
                <!-- Hash Calculator -->
                <div class="hash-calculator">
                    <h5><i class="fas fa-calculator"></i> Hash-Rechner</h5>
                    <div id="calculation-display">
                        <p class="text-muted">Ziehe einen Einwohner, um die Hash-Berechnung zu sehen.</p>
                    </div>
                </div>

                <!-- Statistics -->
                <div class="stats-panel">
                    <h5><i class="fas fa-chart-bar"></i> Statistiken</h5>
                    <div class="stat-item">
                        <span>Platzierte Einwohner:</span>
                        <span id="placed-count">0 / <?php echo count($currentLevel['residents']); ?></span>
                    </div>
                    <div class="stat-item">
                        <span>Kollisionen:</span>
                        <span id="collision-count">0</span>
                    </div>
                    <?php if($level >= 3): ?>
                        <div class="stat-item">
                            <span>Probing-Schritte:</span>
                            <span id="probing-steps">0</span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Controls -->
<div class="controls">
    <button class="control-btn" id="reset-btn">
        <i class="fas fa-redo"></i> Reset
    </button>
    <button class="control-btn" id="hint-btn">
        <i class="fas fa-lightbulb"></i> Hinweis
    </button>
    <button class="control-btn success" id="check-btn" style="display: none;">
        <i class="fas fa-check"></i> Prüfen
    </button>
</div>

<script>
    class HashCityLevel {
        constructor(levelNumber) {
            this.level = levelNumber;
            this.residents = <?php echo json_encode($currentLevel['residents']); ?>;
            this.houses = <?php echo $currentLevel['houses']; ?>;
            this.placedResidents = {};
            this.collisions = 0;
            this.probingSteps = 0;

            this.init();
        }

        init() {
            this.setupDragAndDrop();
            this.bindEvents();
            this.updateStats();
        }

        setupDragAndDrop() {
            // Residents Pool - Sortable
            new Sortable(document.getElementById('residents-pool'), {
                group: {
                    name: 'residents',
                    pull: 'clone',
                    put: false
                },
                sort: false,
                onStart: (evt) => {
                    evt.item.classList.add('dragging');
                },
                onEnd: (evt) => {
                    evt.item.classList.remove('dragging');
                }
            });

            // Houses - Sortable
            document.querySelectorAll('.house').forEach(house => {
                new Sortable(house.querySelector('.house-content'), {
                    group: 'residents',
                    onAdd: (evt) => {
                        this.handleResidentPlacement(evt);
                    },
                    onRemove: (evt) => {
                        this.handleResidentRemoval(evt);
                    }
                });
            });
        }

        bindEvents() {
            document.getElementById('reset-btn')?.addEventListener('click', () => this.resetLevel());
            document.getElementById('hint-btn')?.addEventListener('click', () => this.showHint());
            document.getElementById('check-btn')?.addEventListener('click', () => this.checkSolution());

            // Resident hover for hash calculation
            document.querySelectorAll('.resident').forEach(resident => {
                resident.addEventListener('mouseenter', () => {
                    this.showHashCalculation(resident.dataset.name);
                });
            });
        }

        handleResidentPlacement(evt) {
            const resident = evt.item;
            const houseContent = evt.to;
            const house = houseContent.closest('.house');
            const residentName = resident.dataset.name;
            const houseIndex = parseInt(house.dataset.index);

            // Remove any existing resident in this house (for levels that don't support chaining)
            if (this.level < 4) {
                const existingResident = houseContent.querySelector('.resident:not([data-name="' + residentName + '"])');
                if (existingResident) {
                    this.returnResidentToPool(existingResident);
                }
            }

            // Calculate hash
            const expectedIndex = this.calculateHash(residentName);
            const isCorrectPlacement = this.isCorrectPlacement(residentName, houseIndex);

            // Update placement
            this.placedResidents[residentName] = houseIndex;

            // Visual feedback
            house.classList.remove('collision', 'probing');
            if (this.level >= 2 && expectedIndex !== houseIndex) {
                if (this.level === 2) {
                    // Level 2: Show collision
                    house.classList.add('collision');
                    this.collisions++;
                } else if (this.level >= 3) {
                    // Level 3+: Show probing
                    house.classList.add('probing');
                    this.probingSteps += Math.abs(houseIndex - expectedIndex);
                }
            } else {
                house.classList.add('occupied');
            }

            this.updateStats();
            this.showHashCalculation(residentName, houseIndex);

            // Check if level is complete
            if (Object.keys(this.placedResidents).length === this.residents.length) {
                const checkBtn = document.getElementById('check-btn');
                if (checkBtn) checkBtn.style.display = 'block';
            }
        }

        handleResidentRemoval(evt) {
            const resident = evt.item;
            const houseContent = evt.from;
            const house = houseContent.closest('.house');
            const residentName = resident.dataset.name;

            delete this.placedResidents[residentName];

            // Remove visual states if no residents remain
            if (!houseContent.querySelector('.resident')) {
                house.classList.remove('occupied', 'collision', 'probing');
            }

            this.updateStats();
            const checkBtn = document.getElementById('check-btn');
            if (checkBtn) checkBtn.style.display = 'none';
        }

        returnResidentToPool(resident) {
            const pool = document.getElementById('residents-pool');
            const residentName = resident.dataset.name;

            delete this.placedResidents[residentName];
            if (pool && resident.parentNode) {
                pool.appendChild(resident);
            }
        }

        calculateHash(name) {
            let sum = 0;
            for (let i = 0; i < name.length; i++) {
                sum += name.charCodeAt(i);
            }
            return sum % this.houses;
        }

        isCorrectPlacement(name, houseIndex) {
            const expectedIndex = this.calculateHash(name);

            if (this.level === 1) {
                return houseIndex === expectedIndex;
            } else if (this.level === 2) {
                // Level 2: Accept any placement for learning
                return true;
            } else if (this.level >= 3) {
                // Level 3+: Linear probing allowed
                return this.isValidLinearProbing(name, houseIndex);
            }

            return false;
        }

        isValidLinearProbing(name, houseIndex) {
            const expectedIndex = this.calculateHash(name);
            let currentIndex = expectedIndex;

            // Check if this position is reachable via linear probing
            for (let i = 0; i < this.houses; i++) {
                if (currentIndex === houseIndex) {
                    return true;
                }
                currentIndex = (currentIndex + 1) % this.houses;
            }

            return false;
        }

        showHashCalculation(name, actualIndex = null) {
            const display = document.getElementById('calculation-display');
            if (!display) return;

            let html = `<h6>Berechnung für "${name}":</h6>`;

            let sum = 0;
            let calculation = '';
            for (let i = 0; i < name.length; i++) {
                const ascii = name.charCodeAt(i);
                sum += ascii;
                calculation += `${name[i]}(${ascii})${i < name.length - 1 ? ' + ' : ''}`;
            }

            const hashValue = sum % this.houses;

            html += `<div class="calculation-step">`;
            html += `ASCII-Summe: ${calculation} = ${sum}<br>`;
            html += `Hash-Wert: ${sum} % ${this.houses} = <strong>${hashValue}</strong>`;
            html += `</div>`;

            if (actualIndex !== null && actualIndex !== hashValue) {
                if (this.level === 2) {
                    html += `<div class="calculation-step" style="border-left-color: var(--danger-color);">`;
                    html += `⚠️ Kollision! ${name} sollte zu Index ${hashValue}, ist aber bei ${actualIndex}`;
                    html += `</div>`;
                } else if (this.level >= 3) {
                    const steps = Math.abs(actualIndex - hashValue);
                    html += `<div class="calculation-step" style="border-left-color: var(--warning-color);">`;
                    html += `🔄 Linear Probing: ${hashValue} → ${actualIndex} (${steps} Schritte)`;
                    html += `</div>`;
                }
            }

            display.innerHTML = html;
        }

        updateStats() {
            const placedCount = Object.keys(this.placedResidents).length;
            const progress = (placedCount / this.residents.length) * 100;

            const placedCountEl = document.getElementById('placed-count');
            const collisionCountEl = document.getElementById('collision-count');
            const progressFillEl = document.getElementById('progress-fill');
            const progressTextEl = document.getElementById('progress-text');
            const probingStepsEl = document.getElementById('probing-steps');

            if (placedCountEl) placedCountEl.textContent = `${placedCount} / ${this.residents.length}`;
            if (collisionCountEl) collisionCountEl.textContent = this.collisions;
            if (progressFillEl) progressFillEl.style.width = `${progress}%`;
            if (progressTextEl) progressTextEl.textContent = `${Math.round(progress)}%`;
            if (probingStepsEl && this.level >= 3) probingStepsEl.textContent = this.probingSteps;
        }

        resetLevel() {
            // Return all residents to pool
            document.querySelectorAll('.house .resident').forEach(resident => {
                this.returnResidentToPool(resident);
            });

            // Reset house states
            document.querySelectorAll('.house').forEach(house => {
                house.classList.remove('occupied', 'collision', 'probing');
            });

            // Reset stats
            this.placedResidents = {};
            this.collisions = 0;
            this.probingSteps = 0;
            this.updateStats();

            // Hide check button
            const checkBtn = document.getElementById('check-btn');
            if (checkBtn) checkBtn.style.display = 'none';

            // Clear calculation display
            const calculationDisplay = document.getElementById('calculation-display');
            if (calculationDisplay) {
                calculationDisplay.innerHTML = '<p class="text-muted">Ziehe einen Einwohner, um die Hash-Berechnung zu sehen.</p>';
            }
        }

        showHint() {
            const hints = {
                1: "Berechne für jeden Namen die ASCII-Summe und teile durch 7. Der Rest ist der Hausindex!",
                2: "Grace und Henry haben beide Hash-Wert 3. Was passiert, wenn beide das gleiche Haus wollen?",
                3: "Bei Kollisionen: Probiere den nächsten Index (+1), dann +2, +3... bis ein freier Platz gefunden wird.",
                4: "Chaining: Mehrere Einwohner können im gleichen Haus wohnen (verkettete Liste).",
                5: "Load Factor = Einwohner / Häuser. Bei > 0.75 wird die Performance schlecht.",
                6: "Experimentiere mit verschiedenen Hash-Funktionen und Kollisionsstrategien!"
            };

            alert(hints[this.level] || "Experimentiere mit verschiedenen Platzierungen!");
        }

        checkSolution() {
            let correct = 0;
            let total = this.residents.length;

            for (const [name, houseIndex] of Object.entries(this.placedResidents)) {
                if (this.isCorrectPlacement(name, houseIndex)) {
                    correct++;
                }
            }

            if (correct === total) {
                this.levelComplete();
            } else {
                alert(`${correct}/${total} Einwohner sind korrekt platziert. Versuche es nochmal!`);
            }
        }

        levelComplete() {
            alert(`🎉 Level ${this.level} abgeschlossen!\n\nStatistiken:\n- Kollisionen: ${this.collisions}\n- Probing-Schritte: ${this.probingSteps}`);

            // Save progress to localStorage
            const progress = JSON.parse(localStorage.getItem('hashcity_progress') || '{}');
            progress.maxUnlockedLevel = Math.max(progress.maxUnlockedLevel || 1, this.level + 1);
            progress.currentLevel = this.level + 1;
            progress.completedLevels = progress.completedLevels || [];
            if (!progress.completedLevels.includes(this.level)) {
                progress.completedLevels.push(this.level);
            }
            localStorage.setItem('hashcity_progress', JSON.stringify(progress));

            // Save to session via API
            fetch('?page=api&action=save_progress', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(progress)
            }).catch(error => console.log('Progress save failed:', error));

            // Return to level overview after delay
            setTimeout(() => {
                window.location.href = '?page=levels';
            }, 2000);
        }
    }

    // Initialize Level
    document.addEventListener('DOMContentLoaded', () => {
        window.hashCityLevel = new HashCityLevel(<?php echo $level; ?>);
    });
</script>
</body>
</html>