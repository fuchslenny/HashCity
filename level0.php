<?php
/**
 * HashCity - Level 0: Einführung
 *
 * Lernziel: Das Problem der linearen Suche demonstrieren
 * Spielmechanik: Spieler muss linear durch alle Häuser suchen, um Familie Müller zu finden
 * Familie Müller befindet sich in Haus 16
 */
$familien = [
        0 => "Schmidt",
        1 => "Weber",
        2 => "Wagner",
        3=> "Becker",
        4 => "Schulz",
        5 => "Hoffmann",
        6 => "Koch",
        7 => "Richter",
        8 => "Klein",
        9 => "Wolf",
        10 => "Schröder",
        11 => "Neumann",
        12 => "Schwarz",
        13 => "Zimmermann",
        14 => "Braun",
        15 => "Müller", // Ziel-Familie
        16 => "Krüger",
        17 => "Hofmann",
        18 => "Hartmann",
        19 => "Lange"
];
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HashCity - Level 0: Einführung</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Rajdhani:wght@300;500;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Rajdhani', sans-serif;
            overflow-x: hidden;
            min-height: 100vh;
            position: relative;
            background: #4CAF50;
        }
        /* Sky and Grass Background */
        .sky-section {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 50%;
            background: linear-gradient(180deg, #87CEEB 0%, #B0D4E3 100%);
            z-index: 0;
        }
        .grass-section {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 50%;
            background: linear-gradient(180deg, #76B947 0%, #4CAF50 100%);
            z-index: 0;
        }
        /* Clouds */
        .cloud {
            position: absolute;
            background: rgba(255, 255, 255, 0.7);
            border-radius: 100px;
            opacity: 0.8;
            animation: cloudFloat 40s linear infinite;
        }
        @keyframes cloudFloat {
            0% { left: -200px; }
            100% { left: 110%; }
        }
        /* Header */
        .game-header {
            background: transparent;
            padding: 1rem 2rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            position: relative;
            top: 0;
            z-index: 1000;
            backdrop-filter: blur(10px);
        }
        .back-btn {
            padding: 0.7rem 1.3rem;
            background: rgba(255, 255, 255, 0.9);
            border: 2px solid rgba(102, 126, 234, 0.5);
            border-radius: 30px;
            font-weight: 700;
            color: #667eea;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: 'Orbitron', sans-serif;
            text-decoration: none;
            display: inline-block;
            font-size: 0.9rem;
        }
        .back-btn:hover {
            background: #667eea;
            color: #fff;
            transform: scale(1.05);
        }
        .back-btn::before {
            content: '← ';
            margin-right: 5px;
        }
        /* Game Container */
        .game-container {
            max-width: 1600px;
            margin: 2rem auto;
            padding: 0 2rem;
            position: relative;
            z-index: 1;
        }
        /* Main Game Area */
        .game-area {
            display: grid;
            grid-template-columns: 280px 1fr 320px;
            gap: 2rem;
            min-height: 70vh;
        }
        /* Major Mike Section */
        .major-mike-section {
            background: rgba(255, 255, 255, 0.85);
            border-radius: 25px;
            padding: 1.5rem;
            box-shadow: 0 10px 40px rgba(0,0,0,0.15);
            height: fit-content;
            position: sticky;
            top: 100px;
            border: 4px solid #fff;
        }
        .major-mike-avatar {
            width: 100%;
            height: 240px;
            background: transparent;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            overflow: hidden;
            position: relative;
        }
        .major-mike-avatar img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
        .major-mike-name {
            font-family: 'Orbitron', sans-serif;
            font-size: 1.4rem;
            font-weight: 900;
            color: #667eea;
            text-align: center;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        }
        .dialogue-box {
            background: #fff;
            border: 3px solid #667eea;
            border-radius: 20px;
            padding: 1.5rem;
            min-height: 180px;
            position: relative;
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.2);
        }
        .dialogue-box::before {
            content: '';
            position: absolute;
            top: -15px;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 0;
            border-left: 15px solid transparent;
            border-right: 15px solid transparent;
            border-bottom: 15px solid #667eea;
        }
        .dialogue-text {
            font-size: 1.05rem;
            line-height: 1.7;
            color: #333;
            font-weight: 500;
        }
        .dialogue-continue {
            position: absolute;
            bottom: 10px;
            right: 15px;
            font-size: 0.85rem;
            color: #667eea;
            font-style: italic;
            font-weight: 700;
            animation: blink 1.5s infinite;
        }
        @keyframes blink {
            0%, 50%, 100% { opacity: 1; }
            25%, 75% { opacity: 0.5; }
        }
        /* Houses Grid */
        .houses-grid {
            background: rgba(255, 255, 255, 0.85);
            border-radius: 25px;
            padding: 2rem;
            box-shadow: 0 10px 40px rgba(0,0,0,0.15);
            border: 4px solid #fff;
            overflow: hidden;
        }
        .grid-title {
            font-family: 'Orbitron', sans-serif;
            font-size: 1.8rem;
            font-weight: 900;
            color: #2E7D32;
            text-align: center;
            margin-bottom: 2rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        }
        /* Street Block Container */
        .street-block {
            position: relative;
            margin-bottom: 2.5rem;
        }
        /* Houses Row */
        .houses-row {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 1rem;
            margin-bottom: 0.5rem;
            padding: 0 1rem;
            position: relative;
            z-index: 2;
        }
        /* Straße */
        .street {
            width: 100%;
            height: 60px;
            background-image: url('./assets/Strasse.svg');
            Background-size: cover;
            background-position: center;
            background-repeat: repeat-x;
            position: relative;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
            z-index: 1;
        }
        .street::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(180deg, #4a4a4a 0%, #2a2a2a 100%);
            border-radius: 8px;
            z-index: -1;
        }
        .street::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            width: 100%;
            height: 4px;
            background: repeating-linear-gradient(
                    90deg,
                    #fff 0px,
                    #fff 30px,
                    transparent 30px,
                    transparent 50px
            );
            transform: translateY(-50%);
            z-index: 2;
        }
        .house {
            aspect-ratio: 1;
            background: transparent;
            border: none;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            position: relative;
            border-radius: 10px;
            padding: 0.3rem;
            cursor: not-allowed;
            opacity: 0.7;
        }
        .house.clickable {
            cursor: pointer;
            opacity: 1;
            border: 3px solid #FFD700; /* Goldener Rahmen als Hinweis */
            box-shadow: 0 0 15px rgba(255, 215, 0, 0.6);
            transform: scale(1.05);
            z-index: 20;
        }
        .house.checked {
            opacity: 1;
            border: none;
            cursor: default;
            transform: none;
            box-shadow: none;
        }
        .house:hover:not(.checked):not(.found) {
            transform: translateY(-8px) scale(1.08);
            z-index: 10;
        }
        .house.highlight-target {
            transform: translateY(-10px) scale(1.15) !important;
            box-shadow: 0 0 35px 12px rgba(255, 215, 0, 0.9);
            z-index: 11;
        }
        .house.quadratic-target {
            transform: translateY(-10px) scale(1.15) !important;
            box-shadow: 0 0 35px 12px rgba(255, 0, 0, 0.9);
            z-index: 11;
        }
        .house-icon {
            width: 100%;
            height: 100%;
            object-fit: contain;
            transition: all 0.3s ease;
            filter: drop-shadow(0 4px 8px rgba(0,0,0,0.2));
        }
        .house.checked .house-icon {
            filter: drop-shadow(0 4px 8px rgba(255, 167, 38, 0.5));
        }
        .house.found .house-icon {
            animation: pulse 1.5s infinite;
            filter: drop-shadow(0 8px 16px rgba(255, 215, 0, 0.8));
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.08); }
        }
        .house-number {
            position: absolute;
            top: 25%;
            left: 50%;
            transform: translateX(-50%);
            font-family: 'Orbitron', sans-serif;
            font-size: 1rem;
            font-weight: 900;
            color: white;
            text-shadow: 2px 2px 6px rgba(0,0,0,0.7);
            z-index: 10;
            background: rgba(0, 0, 0, 0.3);
            padding: 0.2rem 0.5rem;
            border-radius: 8px;
        }
        .house-family {
            position: absolute;
            bottom: 10%;
            left: 50%;
            transform: translateX(-50%);
            font-size: 0.7rem;
            color: white;
            font-weight: 700;
            text-align: center;
            opacity: 0;
            transition: opacity 0.3s ease;
            background: rgba(0, 0, 0, 0.7);
            padding: 0.3rem 0.6rem;
            border-radius: 8px;
            white-space: nowrap;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.8);
            max-width: 90%;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .house.show-family .house-family {
            opacity: 1;
        }
        /* Info Panel */
        .info-panel {
            background: rgba(255, 255, 255, 0.85);
            border-radius: 25px;
            padding: 1.5rem;
            box-shadow: 0 10px 40px rgba(0,0,0,0.15);
            height: fit-content;
            position: sticky;
            top: 100px;
            border: 4px solid #fff;
        }
        .info-title {
            font-family: 'Orbitron', sans-serif;
            font-size: 1.4rem;
            font-weight: 700;
            color: #2E7D32;
            margin-bottom: 1.2rem;
            text-align: center;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        }
        .info-item {
            background: #fff;
            padding: 1rem;
            border-radius: 15px;
            margin-bottom: 1rem;
            border: 3px solid #4CAF50;
            box-shadow: 0 4px 15px rgba(76, 175, 80, 0.15);
        }
        .info-label {
            font-weight: 700;
            color: #666;
            font-size: 0.95rem;
            margin-bottom: 0.4rem;
        }
        .info-value {
            font-family: 'Orbitron', sans-serif;
            font-size: 1.6rem;
            font-weight: 900;
            color: #2E7D32;
        }
        .hash-calculator {
            background: linear-gradient(135deg, #e3f2fd 0%, #fff 100%);
            border-color: #2196F3;
        }
        .hash-result-value {
            font-family: 'Orbitron', sans-serif;
            font-size: 2.8rem;
            font-weight: 900;
            color: #667eea;
            text-align: center;
        }
        .calc-button {
            padding: 0.6rem 1.5rem;
            border: none;
            border-radius: 30px;
            font-family: 'Orbitron', sans-serif;
            font-weight: 700;
            font-size: 0.95rem;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            width: 100%;
            margin-top: 0.5rem;
        }
        .calc-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }
        .calc-button:disabled {
            background: #ccc;
            cursor: not-allowed;
        }
        .family-list-container {
            max-height: 250px;
            overflow-y: auto;
        }
        .list-group-item.to-do-family {
            cursor: pointer;
            font-weight: 700;
            transition: all 0.2s ease;
            font-size: 1.1rem;
        }
        .list-group-item.to-do-family:hover,
        .list-group-item.to-do-family.active {
            background: #667eea;
            color: #fff;
            transform: scale(1.03);
            z-index: 10;
        }
        .list-group-item.list-group-item-success {
            text-decoration: line-through;
            background: #f0f0f0;
            color: #999;
            cursor: default !important;
        }
        .list-group-item.list-group-item-success:hover {
            background: #f0f0f0;
            color: #999;
            transform: none;
        }

        /* Success Modal */
        .success-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.85);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 2000;
            animation: fadeIn 0.3s ease;
            backdrop-filter: blur(5px);
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .success-modal {
            background: white;
            border-radius: 30px;
            padding: 3rem;
            max-width: 650px;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0,0,0,0.4);
            animation: slideUp 0.5s ease;
            border: 5px solid #4CAF50;
        }
        @keyframes slideUp {
            from { transform: translateY(100px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .success-icon {
            font-size: 5rem;
            margin-bottom: 1rem;
            animation: bounce 1s infinite;
        }
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        .success-title {
            font-family: 'Orbitron', sans-serif;
            font-size: 2.8rem;
            font-weight: 900;
            color: #4CAF50;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        }
        .success-message {
            font-size: 1.2rem;
            color: #666;
            line-height: 1.7;
            margin-bottom: 2rem;
            font-weight: 500;
        }
        .success-stats {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            margin-bottom: 2rem;
        }
        .stat-box {
            background: #f8f9fa;
            padding: 1.2rem;
            border-radius: 15px;
            border: 3px solid #4CAF50;
            box-shadow: 0 4px 12px rgba(76, 175, 80, 0.15);
        }
        .stat-label {
            font-size: 0.95rem;
            color: #666;
            font-weight: 700;
            margin-bottom: 0.4rem;
        }
        .stat-value {
            font-family: 'Orbitron', sans-serif;
            font-size: 2.5rem;
            font-weight: 900;
            color: #2E7D32;
        }
        .success-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }
        .btn-primary, .btn-secondary {
            padding: 1rem 2.5rem;
            border: none;
            border-radius: 30px;
            font-family: 'Orbitron', sans-serif;
            font-weight: 700;
            font-size: 1.05rem;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }
        .btn-secondary {
            background: white;
            color: #667eea;
            border: 3px solid #667eea;
        }
        .btn-secondary:hover {
            background: #667eea;
            color: white;
            transform: translateY(-2px);
        }
        /* Responsive Design */
        @media (max-width: 1200px) {
            .game-area {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }
            .major-mike-section,
            .info-panel {
                position: static;
            }
        }
        @media (max-width: 768px) {
            .game-container {
                padding: 0 1rem;
                margin: 1rem auto;
            }
            .houses-grid {
                padding: 1.5rem 1rem;
            }
            .houses-row {
                grid-template-columns: repeat(3, 1fr);
                gap: 0.6rem;
            }
        }
        .search-target {
            background: linear-gradient(135deg, #FFD700 0%, #FFA726 100%);
            padding: 1.2rem;
            border-radius: 20px;
            text-align: center;
            margin-bottom: 1.2rem;
            box-shadow: 0 6px 20px rgba(255, 193, 7, 0.4);
            border: 3px solid #fff;
        }
        .search-target-label {
            font-size: 0.95rem;
            color: #333;
            font-weight: 700;
            margin-bottom: 0.4rem;
        }
        .search-target-name {
            font-family: 'Orbitron', sans-serif;
            font-size: 2rem;
            font-weight: 900;
            color: #fff;
            text-shadow: 2px 2px 6px rgba(0,0,0,0.3);
        }
    </style>
</head>
<body>
<!-- Sky Section -->
<div class="sky-section">
    <!-- Clouds -->
    <div class="cloud" style="width: 120px; height: 60px; top: 8%; animation-delay: 0s;"></div>
    <div class="cloud" style="width: 150px; height: 70px; top: 18%; animation-delay: 10s;"></div>
    <div class="cloud" style="width: 100px; height: 50px; top: 28%; animation-delay: 20s;"></div>
    <div class="cloud" style="width: 130px; height: 65px; top: 12%; animation-delay: 30s;"></div>
</div>
<!-- Grass Section -->
<div class="grass-section"></div>
<!-- Header -->
<div class="game-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-auto">
                <a href="Level-Auswahl" class="back-btn">Zurück</a>
            </div>
        </div>
    </div>
</div>
<!-- Game Container -->
<div class="game-container">
    <div class="game-area">
        <!-- Major Mike Section -->
        <div class="major-mike-section">
            <div class="major-mike-avatar">
                <img src="./assets/wink_major.png" alt="Major Mike" id="majorMikeImage">
            </div>
            <div class="major-mike-name">🎖️ Major Mike 🎖️</div>
            <div class="dialogue-box">
                <div class="dialogue-text" id="dialogueText">
                    Willkommen in HashCity! Ich bin Major Mike, der Bürgermeister!
                </div>
                <div class="dialogue-continue" id="dialogueContinue" style="display: none;">
                    Klicken oder Enter ↵
                </div>
            </div>
        </div>
        <!-- Houses Grid -->
        <div class="houses-grid">
            <h2 class="grid-title">🏘️ Level 0: Einführung in HashCity</h2>
            <!-- First Street Block: Houses 1-10 -->
            <div class="street-block">
                <div class="houses-row">
                    <?php for ($i = 0; $i < 5; $i++): ?>
                        <div class="house" data-house="<?php echo $i; ?>" data-family="<?php echo $familien[$i]; ?>">
                            <img src="./assets/empty_house.svg" alt="Haus <?php echo $i; ?>" class="house-icon">
                            <div class="house-number"><?php echo $i; ?></div>
                            <div class="house-family"><?php echo $familien[$i]; ?></div>
                        </div>
                    <?php endfor; ?>
                </div>
                <div class="street"></div>
            </div>
            <div class="street-block">
                <div class="houses-row">
                    <?php for ($i = 5; $i < 10; $i++): ?>
                        <div class="house" data-house="<?php echo $i; ?>" data-family="<?php echo $familien[$i]; ?>">
                            <img src="./assets/empty_house.svg" alt="Haus <?php echo $i; ?>" class="house-icon">
                            <div class="house-number"><?php echo $i; ?></div>
                            <div class="house-family"><?php echo $familien[$i]; ?></div>
                        </div>
                    <?php endfor; ?>
                </div>
                <div class="street"></div>
            </div>
            <div class="street-block">
                <div class="houses-row">
                    <?php for ($i = 10; $i < 15; $i++): ?>
                        <div class="house" data-house="<?php echo $i; ?>" data-family="<?php echo $familien[$i]; ?>">
                            <img src="./assets/empty_house.svg" alt="Haus <?php echo $i; ?>" class="house-icon">
                            <div class="house-number"><?php echo $i; ?></div>
                            <div class="house-family"><?php echo $familien[$i]; ?></div>
                        </div>
                    <?php endfor; ?>
                </div>
                <div class="street"></div>
            </div>
            <!-- Second Street Block: Houses 11-20 -->
            <div class="street-block">
                <div class="houses-row">
                    <?php for ($i = 15; $i < 20; $i++): ?>
                        <div class="house" data-house="<?php echo $i; ?>" data-family="<?php echo $familien[$i]; ?>">
                            <img src="./assets/empty_house.svg" alt="Haus <?php echo $i; ?>" class="house-icon">
                            <div class="house-number"><?php echo $i; ?></div>
                            <div class="house-family"><?php echo $familien[$i]; ?></div>
                        </div>
                    <?php endfor; ?>
                </div>
                <div class="street"></div>
            </div>
        </div>
        <!-- Info Panel -->
        <div class="info-panel">
            <h3 class="info-title">📊 Stadtplanung</h3>
            <div class="search-target">
                <div class="search-target-label">Gesuchte Familie:</div>
                <div class="search-target-name">Müller</div>
            </div>
            <div class="info-item">
                <div class="info-label">Überprüfte Häuser:</div>
                <div class="info-value" id="checkedCount">0 / 20</div>
            </div>
            <div class="info-item">
                <div class="info-label">Anzahl Versuche:</div>
                <div class="info-value" id="attemptsCount">0</div>
            </div>
        </div>
    </div>
</div>
<!-- Success Modal -->
<div class="success-overlay" id="successOverlay">
    <div class="success-modal">
        <div class="success-icon">🎉</div>
        <h2 class="success-title">Familie gefunden!</h2>
        <p class="success-message" id="successMessage">
            Gut gemacht! Du hast Familie Müller in Haus 16 gefunden!
        </p>
        <div class="success-stats">
            <div class="stat-box">
                <div class="stat-label">Versuche</div>
                <div class="stat-value" id="finalAttempts">0</div>
            </div>
            <div class="stat-box">
                <div class="stat-label">Häuser geprüft</div>
                <div class="stat-value" id="finalChecked">0</div>
            </div>
        </div>
        <div class="success-buttons">
            <button class="btn-secondary" onclick="restartLevel()">↻ Nochmal spielen</button>
            <button class="btn-primary" onclick="nextLevel()">Weiter zu Level 1 →</button>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function() {
        // --- 1. Konfiguration & Assets ---

        const housePairs = [
            { empty: "WohnhauBlauBraunLeerNeu.svg", filled: "WohnhauBlauBraunBesetztNeu.svg" },
            { empty: "WohnhauBlauGrauLeerNeu.svg", filled: "WohnhauBlauGrauBesetztNeu.svg" },
            { empty: "WohnhauBlauRotLeerNeu.svg", filled: "WohnhauBlauRotBesetztNeu.svg" },
            { empty: "WohnhauGelbBraunLeerNeu.svg", filled: "WohnhauGelbBraunBesetztNeu.svg" },
            { empty: "WohnhauGelbRotLeerNeu.svg", filled: "WohnhauGelbRotBesetztNeu.svg" },
            { empty: "WohnhauGrauBraunLeerNeu.svg", filled: "WohnhauGrauBraunBesetztNeu.svg" },
            { empty: "WohnhauGruenBraunLeerNeu.svg", filled: "WohnhauGruenBraunBesetztNeu.svg" },
            { empty: "WohnhauGruenGrauLeerNeu.svg", filled: "WohnhauGruenGrauBesetztNeu.svg" },
            { empty: "WohnhauGrauBraunLeerNeu.svg", filled: "WohnhauGrauBraunBesetztNeu.svg" },
            { empty: "WohnhauRotBraunLeerNeu.svg", filled: "WohnhauRotBraunBesetztNeu.svg" },
            { empty: "WohnhauRotRotLeerNeu.svg", filled: "WohnhauRotRotBesetztNeu.svg" }
        ];

        // --- 2. State Variablen ---
        let currentHouseIndex = 0;
        let attempts = 0;
        let gameStarted = false;
        let gameCompleted = false;
        let currentDialogue = 0;

        // --- 3. Dialoge ---
        const dialogues = [
            "Hallo! Willkommen in HashCity! Ich bin Major Mike, der Bürgermeister.",
            "Ich habe ein riesiges Problem: Ich habe meinen Stadtplan verloren!",
            "Ich muss Familie Müller finden. Da wir keine Hashmap haben, müssen wir <strong>linear suchen</strong>.",
            "Das heißt: Wir fangen bei Haus 0 an und prüfen jedes einzelne Haus nacheinander. Es gibt keine Abkürzung!",
            "Klicke jetzt auf das erste Haus (Haus 0), um zu starten."
        ];

        // --- 4. Helper Funktionen ---

        function getRandomHousePair() {
            return housePairs[Math.floor(Math.random() * housePairs.length)];
        }

        // --- 5. Initialisierung der Häuser ---
        $('.house').each(function() {
            const $house = $(this);
            const pair = getRandomHousePair();

            // Standardmäßig "Besetzt"-Bild anzeigen
            $house.find('.house-icon').attr('src', `./assets/${pair.filled}`);

            $house.data('filled-asset', pair.filled);
            $house.data('empty-asset', pair.empty);
        });

        // --- 6. Spiellogik ---

        function activateNextHouse() {
            $('.house').removeClass('clickable');
            let $next = $(`.house[data-house="${currentHouseIndex}"]`);
            $next.addClass('clickable');
        }

        function updateStats() {
            $('#checkedCount').text(currentHouseIndex + ' / 20');
            $('#attemptsCount').text(attempts);
        }

        // --- 7. Event Handler ---

        function showNextDialogue() {
            currentDialogue++;
            if (currentDialogue < dialogues.length) {
                $('#dialogueText').fadeOut(200, function() {
                    $(this).html(dialogues[currentDialogue]).fadeIn(200);

                    if (currentDialogue === 1) $('#majorMikeImage').attr('src', './assets/sad_major.png');
                    if (currentDialogue === 2) $('#majorMikeImage').attr('src', './assets/card_major.png');

                    if (currentDialogue === dialogues.length - 1) {
                        $('#dialogueContinue').fadeOut();
                        gameStarted = true;
                        activateNextHouse();
                    }
                });
            }
        }

        setTimeout(function() { $('#dialogueContinue').fadeIn(); }, 1000);

        $(document).keydown(function(e) {
            if ((e.key === 'Enter' || e.key === ' ') && !gameStarted) {
                showNextDialogue();
            }
        });
        $('.dialogue-box').click(function() {
            if (!gameStarted) {
                showNextDialogue();
            }
        });

        // --- KLICK-LOGIK (Hier ist die Änderung) ---
        $('.house').click(function() {
            if (!gameStarted || gameCompleted) return;

            const $house = $(this);
            const clickedIndex = $house.data('house');
            const family = $house.data('family');

            // FALL 1: Bereits geprüftes Haus (Rückwärts)
            if (clickedIndex < currentHouseIndex) {
                $('#majorMikeImage').attr('src', './assets/card_major.png'); // Neutrales Gesicht
                $('#dialogueText').html(
                    `Haus ${clickedIndex} haben wir doch schon geprüft! Da wohnt Familie ${family}.<br>` +
                    `Konzentrier dich bitte auf das nächste <strong>ungeprüfte</strong> Haus (Haus ${currentHouseIndex})!`
                );
                return;
            }

            // FALL 2: Zu weit gesprungen (Vorwärts)
            if (clickedIndex > currentHouseIndex) {
                $('#majorMikeImage').attr('src', './assets/sad_major.png');
                $('#dialogueText').html(
                    `Halt! Ein Computer kann nicht raten. Er muss linear vorgehen.<br>` +
                    `Wir haben Haus ${currentHouseIndex} noch nicht geprüft. Klicke bitte darauf!`
                );
                // Wackel-Animation beim korrekten Haus
                $(`.house[data-house="${currentHouseIndex}"]`)
                    .fadeOut(100).fadeIn(100).fadeOut(100).fadeIn(100);
                return;
            }

            // FALL 3: Korrektes Haus (clickedIndex === currentHouseIndex)
            attempts++;
            $house.addClass('checked show-family');
            $house.removeClass('clickable');

            if (family === 'Müller') {
                // GEWONNEN
                gameCompleted = true;
                $house.addClass('found');
                $('#majorMikeImage').attr('src', './assets/wink_major.png');

                $('#dialogueText').html(
                    `🎉 <strong>Gefunden!</strong> Familie Müller wohnt in Haus ${clickedIndex}!<br>` +
                    `Aber uff... wir mussten ${attempts} Häuser durchsuchen. Das ist ineffizient!`
                );

                setTimeout(showSuccessModal, 2500);
            } else {
                // FALSCHE FAMILIE -> WEITER
                $('#majorMikeImage').attr('src', './assets/card_major.png');
                $('#dialogueText').text(`Haus ${clickedIndex}: Hier wohnt Familie ${family}. Nicht Müller. Weiter zum nächsten!`);

                currentHouseIndex++;
                activateNextHouse();
            }

            updateStats();
        });

        // --- 8. Modal & Global ---

        function showSuccessModal() {
            $('#finalAttempts').text(attempts);
            $('#finalChecked').text(attempts);

            const msg = `
                <strong style="color: #667eea;">Major Mike sagt:</strong><br>
                "Stell dir vor, die Stadt hätte 1.000.000 Häuser. Wir müssten im schlimmsten Fall ALLE durchsuchen!<br>
                Das nennt man <strong>linearen Aufwand O(n)</strong>.<br>
                Lass uns im nächsten Level lernen, wie Hashmaps das lösen!"
            `;
            $('#successMessage').html(msg);
            $('#successOverlay').css('display', 'flex');
        }

        window.restartLevel = function() {
            location.reload();
        };

        window.nextLevel = function() {
            $('body').css('transition', 'opacity 0.5s ease');
            $('body').css('opacity', '0');
            setTimeout(function() {
                window.location.href = 'level-select.php?completed=0&next=1';
            }, 500);
        };
    });
</script>
</body>
</html>
