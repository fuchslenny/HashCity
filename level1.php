<?php
/**
 * HashCity - Level 1: Einführung in Hashmaps (Drag & Drop) - angepasst an Level 2 Design
 */
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HashCity - Level 1: Hashmaps (Drag & Drop)</title>
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
            z-index: 10;
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
            margin-bottom: -10px;
            z-index: 0;
            aspect-ratio: 1;
            background: transparent;
            border: none;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            border-radius: 10px;
            padding: 0.3rem;
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
        /* INFO-PANEL (Stil von Level 3/4) */
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
        .calculator-input {
            width: 100%;
            border: 2px solid #ccc;
            border-radius: 10px;
            padding: 0.7rem;
            font-family: 'Rajdhani', sans-serif;
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 0.7rem;
            transition: border-color 0.3s ease;
        }
        .calculator-input:focus {
            outline: none;
            border-color: #667eea;
        }
        .calculator-button {
            width: 100%;
            padding: 0.8rem;
            background: linear-gradient(135deg, #4CAF50 0%, #2E7D32 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-family: 'Orbitron', sans-serif;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(76, 175, 80, 0.3);
            margin-top: 0.5rem;
        }
        .calculator-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(76, 175, 80, 0.4);
        }
        .calculator-result {
            margin-top: 1rem;
            padding: 0.8rem;
            background: #f8f9fa;
            border: 2px dashed #4CAF50;
            border-radius: 10px;
            text-align: center;
            font-family: 'Orbitron', sans-serif;
            font-weight: 700;
            color: #2E7D32;
            font-size: 1.1rem;
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
        .family-list-container {
            max-height: 250px;
            padding: 0 5px;
            overflow-y: auto;
        }
        .list-group-item.to-do-family {
            cursor: pointer;
            font-weight: 700;
            transition: all 0.2s ease;
            font-size: 1.1rem;
            border: 2px solid #aab8c2;
            margin-bottom: 0.5rem;
            border-radius: 10px !important;
        }
        .list-group-item.to-do-family:hover:not(.placed) {
            background: #e9ecef;
            border-color: #667eea;
        }
        .list-group-item.to-do-family.active {
            background: #667eea;
            color: white;
            border-color: #667eea;
            transform: scale(1.03);
            z-index: 10;
        }
        .list-group-item.to-do-family.list-group-item-success {
            opacity: 0.3;
            background: #e0e0e0;
            cursor: not-allowed;
            text-decoration: line-through;
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
    </style>
</head>
<body>
<!-- Sky Section -->
<div class="sky-section">
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
                <a href="level-select.php" class="back-btn">Zurück</a>
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
                    ...
                </div>
                <div class="dialogue-continue" id="dialogueContinue">
                    Klicken oder Enter ↵
                </div>
            </div>
        </div>
        <!-- Houses Grid -->
        <div class="houses-grid">
            <h2 class="grid-title">🏘️ Level 1: Grundlagen HashMaps</h2>
            <!-- Street Block: Houses 0-4 -->
            <?php
            // Paare der neuen Assets für PHP
            $housePairs = [
                    ["empty" => "WohnhauBlauBraunLeerNeu.svg", "filled" => "WohnhauBlauBraunBesetztNeu.svg"],
                    ["empty" => "WohnhauBlauGrauLeerNeu.svg", "filled" => "WohnhauBlauGrauBesetztNeu.svg"],
                    ["empty" => "WohnhauBlauRotLeerNeu.svg", "filled" => "WohnhauBlauRotBesetztNeu.svg"],
                    ["empty" => "WohnhauGelbBraunLeerNeu.svg", "filled" => "WohnhauGelbBraunBesetztNeu.svg"],
                    ["empty" => "WohnhauGelbRotLeerNeu.svg", "filled" => "WohnhauGelbRotBesetztNeu.svg"],
                    ["empty" => "WohnhauGrauBraunLeerNeu.svg", "filled" => "WohnhauGrauBraunBesetztNeu.svg"],
                    ["empty" => "WohnhauGruenBraunLeerNeu.svg", "filled" => "WohnhauGruenBraunBesetztNeu.svg"],
                    ["empty" => "WohnhauGruenGrauLeerNeu.svg", "filled" => "WohnhauGruenGrauBesetztNeu.svg"],
                    ["empty" => "WohnhauGruenBraunLeerNeu.svg", "filled" => "WohnhauGruenBraunBesetztNeu.svg"],
                    ["empty" => "WohnhauGruenGrauLeerNeu.svg", "filled" => "WohnhauGruenGrauBesetztNeu.svg"],
                    ["empty" => "WohnhauRotRotLeerNeu.svg", "filled" => "WohnhauRotRotBesetztNeu.svg"]
            ];
            // Zufällige Zuordnung der Asset-Paare zu den Häusern
            $houseAssets = [];
            for ($i = 0; $i <= 4; $i++) {
                $houseAssets[$i] = $housePairs[array_rand($housePairs)];
            }
            ?>
            <div class="street-block">
                <div class="houses-row">
                    <?php for ($i = 0; $i <= 4; $i++): ?>
                        <div class="house" data-house="<?php echo $i; ?>" data-family="">
                            <img src="./assets/<?php echo $houseAssets[$i]['empty']; ?>" alt="Haus <?php echo $i; ?>" class="house-icon">
                            <div class="house-number"><?php echo $i; ?></div>
                            <div class="house-family"></div>
                        </div>
                    <?php endfor; ?>
                </div>
                <div class="street"></div>
            </div>
        </div>
        <!-- Info Panel -->
        <div class="info-panel">
            <h3 class="info-title">📊 Stadtplanung</h3>
            <!-- Bewerber-Liste -->
            <div class="info-item">
                <div class="info-label">Einziehende Familien:</div>
                <div class="family-list-container">
                    <ul id="familienListe" class="list-group">
                        <li class="list-group-item to-do-family" data-family="Lars">Lars</li>
                        <li class="list-group-item to-do-family" data-family="Thomas">Thomas</li>
                        <li class="list-group-item to-do-family" data-family="Sophie">Sophie</li>
                        <li class="list-group-item to-do-family" data-family="Lennard">Lennard</li>
                        <li class="list-group-item to-do-family" data-family="Hans">Hans</li>
                    </ul>
                </div>
            </div>
            <div class="info-item hash-calculator">
                <label for="hashInput" class="info-label" style="color: #666; font-size: 0.95rem;">Bewohnername:</label>
                <input type="text" id="hashInput" class="calculator-input" placeholder="Namen eingeben..." readonly>
                <button id="hashButton" class="calculator-button" disabled>Berechne Haus-Nr.</button>
                <div class="calculator-result" id="hashResult">Ergebnis ...</div>
            </div>

            <div class="info-item">
                <div class="info-label">Eingetragene Familien:</div>
                <div class="info-value" id="occupiedCount">0 / 5</div>
            </div>
        </div>
    </div>
</div>
<!-- Success Modal -->
<div class="success-overlay" id="successOverlay">
    <div class="success-modal">
        <div class="success-icon">🎉</div>
        <h2 class="success-title">Level 1 geschafft!</h2>
        <p class="success-message" id="successMessage">
            Danke für deine Hilfe, so funktioniert alles viel besser!
        </p>
        <div class="success-buttons">
            <button class="btn-secondary" onclick="restartLevel()">↻ Nochmal spielen</button>
            <button class="btn-primary" onclick="nextLevel()">Weiter zu Level 2 →</button>
        </div>
    </div>
</div>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function() {
        // --- Level 1 Setup ---
        const HASH_SIZE = 5;
        let stadt = new Array(HASH_SIZE + 1).fill(null);
        let occupiedHouses = 0;
        let gameStarted = false;
        let gameCompleted = false;
        let searchMode = false;
        let selectedFamily = null;
        let hash = null;
        // Paare der neuen Assets für JavaScript
        const housePairs = [
            { empty: "WohnhauBlauBraunLeerNeu.svg", filled: "WohnhauBlauBraunBesetztNeu.svg" },
            { empty: "WohnhauBlauGrauLeerNeu.svg", filled: "WohnhauBlauGrauBesetztNeu.svg" },
            { empty: "WohnhauBlauRotLeerNeu.svg", filled: "WohnhauBlauRotBesetztNeu.svg" },
            { empty: "WohnhauGelbBraunLeerNeu.svg", filled: "WohnhauGelbBraunBesetztNeu.svg" },
            { empty: "WohnhauGelbRotLeerNeu.svg", filled: "WohnhauGelbRotBesetztNeu.svg" },
            { empty: "WohnhauGrauBraunLeerNeu.svg", filled: "WohnhauGrauBraunBesetztNeu.svg" },
            { empty: "WohnhauGruenBraunLeerNeu.svg", filled: "WohnhauGruenBraunBesetztNeu.svg" },
            { empty: "WohnhauGruenGrauLeerNeu.svg", filled: "WohnhauGruenGrauBesetztNeu.svg" },
            { empty: "WohnhauGruenBraunLeerNeu.svg", filled: "WohnhauGruenBraunBesetztNeu.svg" },
            { empty: "WohnhauRotRotLeerNeu.svg", filled: "WohnhauRotRotBesetztNeu.svg" }
        ];
        // Sound-Dateien laden
        const soundClick   = new Audio('./assets/sounds/click.mp3');
        const soundSuccess = new Audio('./assets/sounds/success.mp3');
        const soundError   = new Audio('./assets/sounds/error.mp3');

        const dialogueAudios = [
            new Audio('./assets/sounds/Lvl1/Lvl1_1.mp3'),
            new Audio('./assets/sounds/Lvl1/Lvl1_2.mp3'),
            new Audio('./assets/sounds/Lvl1/Lvl1_3.mp3'),
            new Audio('./assets/sounds/Lvl1/Lvl1_4.mp3'),
            new Audio('./assets/sounds/Lvl1/Lvl1_5.mp3')
        ];
        let currentAudioObj = null;
        function playDialogueAudio(index) {
            // 1. Altes Audio stoppen (falls noch läuft)
            if (currentAudioObj) {
                currentAudioObj.pause();
                currentAudioObj.currentTime = 0;
            }

            // 2. Neues Audio holen und abspielen
            if (index >= 0 && index < dialogueAudios.length) {
                currentAudioObj = dialogueAudios[index];
                currentAudioObj.play().catch(e => console.log("Audio play blocked:", e));
            }
        }

        function playSound(type) {
            let audio;
            if (type === 'click') audio = soundClick;
            else if (type === 'success') audio = soundSuccess;
            else if (type === 'error') audio = soundError;

            if (audio) {
                audio.currentTime = 0; // Spult zum Anfang zurück (wichtig bei schnellen Klicks!)
                audio.play().catch(e => console.log("Audio play blocked", e)); // Fängt Browser-Blockaden ab
            }
        }
        // Funktion zum Setzen des Haus-Assets
        function setHouseAsset(houseElement, isFilled) {
            const currentAsset = houseElement.find('.house-icon').attr('src');
            const assetName = currentAsset.split('/').pop();
            let matchingPair = null;
            for (const pair of housePairs) {
                if (pair.empty === assetName || pair.filled === assetName) {
                    matchingPair = pair;
                    break;
                }
            }
            const newAsset = isFilled ? matchingPair.filled : matchingPair.empty;
            houseElement.find('.house-icon').attr('src', `./assets/${newAsset}`);
        }
        const dialogues = [
            "Schau mal, das ist einer der neuen Stadtteile. Hier ziehen demnächst die neuen Stadtbewohner ein. Damit das nicht so unübersichtlich wie im vorherigen Stadtteil wird, habe ich mir etwas ganz Besonderes überlegt. Dafür dürfen keine Namen doppelt existieren.",
            "Hier rechts befindet sich unser Stadtplaner. Dort siehst du, welche Hausnummer zu welchem Namen gehört. Klicke einfach auf einen Namen aus der Liste, um den Rechner zu füllen, und klicke dann auf 'Berechne Haus-Nr.'.",
            "Unser Stadtplaner funktioniert ganz ähnlich wie ein Hashrechner bei einem Computer! Zunächst berechnet er die Summe der einzelnen ASCII-Zeichen der Namen unserer Bewohner.",
            "Danach rechnet er diese Zahl noch modulu der Größe des Stadtviertels, hier also 5.<br> Hashmaps machen das ganz ähnlich."
        ];
        const sophieDialogue = "Ich sehe, du hast für alle Bewohner ein Haus gefunden. Ich habe noch einen Termin mit Sophie, kannst du mir helfen sie zu finden? Nutze den Hash-Rechner, um ihre Hausnummer zu berechnen.";
        let currentDialogue = -1;
        let isFading = 0;
        // --- Hash-Funktion (identisch zur PHP-Logik) ---
        function getHash(key, size) {
            let sum = 0;
            for (let i = 0; i < key.length; i++) {
                sum += key.charCodeAt(i);
            }
            return (sum % size);
        }
        // --- Dialog Steuerung ---
        function showNextDialogue() {
            if (isFading || gameStarted) return;
            currentDialogue++;
            // Intro Dialoge (0 bis 3)
            if (currentDialogue < dialogues.length) {
                playDialogueAudio(currentDialogue); // Audio 0-3
                isFading = true;
                $('#dialogueText').fadeOut(200, function() {
                    $(this).html(dialogues[currentDialogue]).fadeIn(200, function() {
                        isFading = false;
                    });
                });
            }
            // Nach dem letzten Dialog -> Spiel starten
            else {
                $('#dialogueContinue').fadeOut();
                gameStarted = true;
                $('#dialogueText').text("Okay, lass uns anfangen! Wähle die erste Familie aus der Liste.");
            }
        }

        // --- Event Listener für Dialoge ---
        $('.dialogue-box').click(function() {
            if (!gameStarted) showNextDialogue();
        });

        $(document).keydown(function(e) {
            if ((e.key === 'Enter' || e.key === ' ') && !gameStarted) {
                showNextDialogue();
            }
        });

        // --- Initiale Anzeige ---
        $('#dialogueText').text("...");
        $('#dialogueContinue').show();

        // --- Level 1 Spielmechanik ---
        // 1. Familie aus der Liste auswählen
        $('#familienListe .to-do-family').click(function() {
            if (gameCompleted || !gameStarted || searchMode) return;
            const $item = $(this);
            if ($item.hasClass('list-group-item-success')) return;
            selectedFamily = $item.data('family');
            $('#hashInput').val(selectedFamily);
            $('#hashResult').text('Ergebnis ...');
            $('#hashButton').prop('disabled', false);
            $('.to-do-family').removeClass('active');
            $item.addClass('active');
            $('.house').removeClass('highlight-target');
            $('#dialogueText').text(`Okay, Familie ${selectedFamily}. Berechne jetzt die Hausnummer!`);
        });
        // Aktivieren des Buttons, sobald etwas eingegeben ist
        $('#hashInput').on('input', function() {
            if ($(this).val().trim() !== '' && searchMode) {
                $('#hashButton').prop('disabled', false);
            } else if (!searchMode) {
                $('#hashButton').prop('disabled', true);
            }
        });
        // 2. Hash-Wert berechnen
        $('#hashButton').click(function() {
            if (gameCompleted) return;
            const family = $('#hashInput').val().trim();
            if (!family) return;
            hash = getHash(family, HASH_SIZE);
            $('#hashResult').text(`Hausnummer: ${hash}`);
            if (searchMode) {
                if (family === 'Sophie' && hash === 4) {
                    $('.house[data-house="4"]').addClass('found show-family');
                    $('.house[data-house="4"] .house-family').text('Sophie');
                    $('#successMessage').html(`
                        <strong style="color: #667eea;">Major Mike sagt:</strong><br>
                        "Danke für deine Hilfe! So funktioniert alles viel besser!<br>
                        Lass uns im nächsten Level noch mehr über Hash-Funktionen lernen!"
                    `);
                    $('#finalOccupied').text(occupiedHouses);
                    $('#successOverlay').css('display', 'flex');
                    gameCompleted = true;
                }
            } else {
                $('#dialogueText').text(`Perfekt! Laut Rechner gehört Familie ${family} in Haus ${hash}. Klicke auf das Haus, um sie einziehen zu lassen.`);
                $('.house').removeClass('highlight-target');
                $(`.house[data-house=${hash}]`).addClass('highlight-target');
            }
        });
        // 3. Haus klicken, um Familie zu platzieren oder Bewohner zu suchen
        $('.house').click(function() {
            const $house = $(this);
            const houseNumber = $house.data('house');
            if (searchMode) {
                if(hash === null) return;
                const occupant = stadt[houseNumber];
                if (occupant) {
                    $house.addClass('show-family');
                    $house.find('.house-family').text(occupant);
                    $('#dialogueText').text(`In Haus ${houseNumber} wohnt ${occupant}.`);
                    if (occupant === 'Sophie') {
                        playSound('success');
                        $house.addClass('found');
                        $('#successMessage').html(`
                            <strong style="color: #667eea;">Major Mike sagt:</strong><br>
                            "Danke für deine Hilfe! So funktioniert alles viel besser!<br>
                            Lass uns im nächsten Level noch mehr über Hash-Funktionen lernen!"
                        `);
                        $('#finalOccupied').text(occupiedHouses);
                        $('#successOverlay').css('display', 'flex');
                        gameCompleted = true;
                    } else {
                        playSound('error');
                    }
                }
            } else {
                if (gameCompleted || !gameStarted || !selectedFamily) {
                    if(gameStarted && !gameCompleted) $('#dialogueText').text(`Du musst erst eine Familie auswählen und ihren Hash berechnen!`);
                    playSound('error');
                    return;
                }
                console.log(hash);
                if(hash === null){
                    $('#dialogueText').text(`Berechne erst den Hash mit dem Hashrechner!`);
                    playSound('error');
                    return;
                }
                if (houseNumber !== hash) {
                    $('#dialogueText').text(`Halt! Der Rechner hat Haus ${hash} für Familie ${selectedFamily} berechnet, nicht Haus ${houseNumber}.`);
                    playSound('error');
                    return;
                }
                const currentOccupant = stadt[houseNumber];
                if (currentOccupant === null) {
                    playSound('click');
                    stadt[houseNumber] = selectedFamily;
                    setHouseAsset($house, true);
                    $house.addClass('checked');
                    $house.removeClass('highlight-target');
                    $(`.to-do-family[data-family="${selectedFamily}"]`)
                        .removeClass('active')
                        .addClass('list-group-item-success')
                        .off('click');
                    occupiedHouses++;
                    $('#occupiedCount').text(occupiedHouses + ' / 5');
                    if (occupiedHouses < 5) {
                        $('#dialogueText').text(`Sehr gut! Familie ${selectedFamily} ist in Haus ${houseNumber} eingezogen. Wen nehmen wir als nächstes?`);
                    } else {
                        $('#dialogueText').text(sophieDialogue);
                        searchMode = true;
                        $('#hashInput').prop('readonly', false).val('').focus();
                        $('#hashButton').prop('disabled', true);
                    }
                    selectedFamily = null;
                    hash = null;
                    $('#hashInput').val('');
                    $('#hashResult').text('Ergebnis ...');
                }
            }
        });
        // --- Global functions for buttons ---
        window.restartLevel = function() {
            location.reload();
        };
        window.nextLevel = function() {
            $('body').css('transition', 'opacity 0.5s ease');
            $('body').css('opacity', '0');
            setTimeout(function() {
                window.location.href = 'Level-Auswahl?completed=1&next=2';
            }, 500);
        };
    });
</script>
</body>
</html>
