<?php
/**
 * HashCity - Level 5: Quadratic Probing
 */
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HashCity - Level 5: Quadratic Probing</title>
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
        .calculator-button:disabled {
            background: #ccc;
            cursor: not-allowed;
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
        .family-list-container {
            max-height: 250px;
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
                    Linear probing erzeugt Cluster, was zu einem großen Suchaufwand führt, wenn man viele Daten speichern möchte. Also entstehen große Nachbarschaften, in denen man sehr lang suchen muss, bis man das richtige Haus gefunden hat.
                </div>
                <div class="dialogue-continue" id="dialogueContinue">
                    Klicken oder Enter ↵
                </div>
            </div>
        </div>
        <!-- Houses Grid -->
        <div class="houses-grid">
            <h2 class="grid-title">🏘️ HashCity Neuer Stadtteil</h2>
            <!-- Street Block: Houses 0-9 -->
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
            for ($i = 0; $i < 10; $i++) {
                $houseAssets[$i] = $housePairs[array_rand($housePairs)];
            }
            ?>
            <div class="street-block">
                <div class="houses-row">
                    <?php for ($i = 0; $i < 5; $i++): ?>
                        <div class="house" data-house="<?php echo $i; ?>" data-family="">
                            <img src="./assets/<?php echo $houseAssets[$i]['empty']; ?>" alt="Haus <?php echo $i; ?>" class="house-icon">
                            <div class="house-number"><?php echo $i; ?></div>
                            <div class="house-family"></div>
                        </div>
                    <?php endfor; ?>
                </div>
                <div class="street"></div>
            </div>
            <div class="street-block">
                <div class="houses-row">
                    <?php for ($i = 5; $i < 10; $i++): ?>
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
            <div class="info-item hash-calculator">
                <div class="info-label">Bewohnername:</div>
                <input type="text" id="hashInput" class="calculator-input" placeholder="Namen eingeben...">
                <button id="hashButton" class="calculator-button">Berechne Haus-Nr.</button>
                <div class="calculator-result" id="hashResult">Ergebnis: ...</div>
            </div>
            <!-- Bewerber-Liste -->
            <div class="info-item">
                <div class="info-label">Einziehende Familien:</div>
                <div class="family-list-container">
                    <ul id="familienListe" class="list-group">
                        <li class="list-group-item to-do-family" data-family="Levi">Levi</li>
                        <li class="list-group-item to-do-family" data-family="Emil">Emil</li>
                        <li class="list-group-item to-do-family" data-family="Lars">Lars</li>
                        <li class="list-group-item to-do-family" data-family="Thomas">Thomas</li>
                        <li class="list-group-item to-do-family" data-family="Noah">Noah</li>
                    </ul>
                </div>
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
        <h2 class="success-title">Familie gefunden!</h2>
        <p class="success-message" id="successMessage">
            Vielen Dank! Nun ist auch dieser Stadtteil fertig.
        </p>
        <div class="success-stats">
            <div class="stat-box">
                <div class="stat-label">Versuche</div>
                <div class="stat-value" id="finalAttempts">0</div>
            </div>
            <div class="stat-box">
                <div class="stat-label">Familien eingetragen</div>
                <div class="stat-value" id="finalOccupied">0</div>
            </div>
        </div>
        <div class="success-buttons">
            <button class="btn-secondary" onclick="restartLevel()">↻ Nochmal spielen</button>
            <button class="btn-primary" onclick="nextLevel()">Weiter zu Level 6 →</button>
        </div>
    </div>
</div>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function() {
        // --- Level 5 Setup ---
        const HASH_SIZE = 10;
        let stadt = new Array(HASH_SIZE).fill(null);
        let occupiedHouses = 0;
        let attempts = 0;
        let gameStarted = false;
        let gameCompleted = false;
        let searchMode = false;
        let selectedFamily = null;
        let firstCollisionHandled = false;
        let currentFamilyIndex = 0;
        const families = ["Levi", "Emil", "Lars", "Thomas", "Noah"];
        const dialogues = [
            "Linear probing erzeugt Cluster, was zu einem großen Suchaufwand führt, wenn man viele Daten speichern möchte. Also entstehen große Nachbarschaften, in denen man sehr lang suchen muss, bis man das richtige Haus gefunden hat.",
            "Trage zunächst erstmal diese Bewohner ein, bevor ich meine neue Idee vorstelle."
        ];
        const successDialogue = "Vielen Dank! Nun ist auch dieser Stadtteil fertig.";
        const errorDialogue = "Das war das falsche Haus, achte auf Rechtschreibung des Namens und lass die Hausnummer berechnen.";
        const correctDialogue = "Sehr gut! Dies ist das richtige Haus.";
        const thomasDialogue = "Sehr gut! Dies ist das richtige Haus. Wie du siehst, ist es aber schon belegt. So kann Thomas also nicht einziehen. Das neue Verfahren heißt 'quadratic probing'. Der Bewohner soll ins nächste freie Haus ziehen. Allerdings berechnet man erst das Quadrat, startend bei 1, und verschiebt den Bewohner dann um das Ergebnis. Falls auch dieses belegt ist, geht es weiter mit 2, 3, 4, usw.. Thomas soll also ins Haus 0, dies ist schon belegt und wir rechnen 0 + 1^2. Haus 1 ist ebenfalls belegt und deswegen rechnen wir 0 + 2^2. Haus 4 ist frei und Thomas kann einziehen.";
        const noahDialogue = "Trage nach demselben Prinzip Noah ein.";
        const thomasSearchDialogue = "Kannst du mir die Hausnummer von Thomas geben? Ich brauche noch ein paar Unterlagen von ihm.";
        const thomasSearchErrorDialogue = "Das war das falsche Haus, achte auf Rechtschreibung des Namens und lass die Hausnummer berechnen. Beachte auch das Verfahren bei einer Kollision (quadratic probing).";
        let currentDialogue = 0;
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
            { empty: "WohnhauGruenGrauLeerNeu.svg", filled: "WohnhauGruenGrauBesetztNeu.svg" },
            { empty: "WohnhauRotRotLeerNeu.svg", filled: "WohnhauRotRotBesetztNeu.svg" }
        ];
        // Funktion zum Setzen des Haus-Assets
        function setHouseAsset(houseElement, isFilled) {
            // Aktuelles Asset des Hauses auslesen
            const currentAsset = houseElement.find('.house-icon').attr('src');
            const assetName = currentAsset.split('/').pop(); // z. B. "WohnhauBlauBraunLeerNeu.svg"
            // Passendes Paar in housePairs finden
            let matchingPair = null;
            for (const pair of housePairs) {
                if (pair.empty === assetName || pair.filled === assetName) {
                    matchingPair = pair;
                    break;
                }
            }
            // Neues Asset basierend auf isFilled setzen
            const newAsset = isFilled ? matchingPair.filled : matchingPair.empty;
            houseElement.find('.house-icon').attr('src', `./assets/${newAsset}`);
        }
        // --- Hash-Funktion (zero-based) ---
        function getHash(key, size) {
            let sum = 0;
            for (let i = 0; i < key.length; i++) {
                sum += key.charCodeAt(i);
            }
            return sum % size;
        }
        // --- Quadratic Probing ---
        function quadraticProbing(key, size, stadt) {
            let hash = getHash(key, size);
            let i = 1;
            let steps = [hash];
            let position = hash;
            while (stadt[position] !== null) {
                position = (hash + Math.pow(i, 2)) % size;
                steps.push(position);
                i++;
            }
            return { finalIndex: position, steps: steps };
        }
        // Familienliste initial ausgrauen
        function initFamilyListUI() {
            $('.to-do-family').addClass('disabled').css('opacity', '0.5').off('click');
            const currentFamily = families[currentFamilyIndex];
            $(`.to-do-family[data-family="${currentFamily}"]`).removeClass('disabled').css('opacity', '1').on('click', handleFamilyClick);
            selectedFamily = currentFamily;
        }
        // --- Dialog-Steuerung ---
        function showNextDialogue() {
            if (currentDialogue >= dialogues.length) {
                $('#dialogueContinue').fadeOut();
                gameStarted = true;
                $('#dialogueText').text('Okay, lass uns anfangen! Wähle die erste Familie aus der Liste.');
                return;
            }
            $('#dialogueText').fadeOut(200, function() {
                $(this).text(dialogues[currentDialogue]).fadeIn(200);
            });
            currentDialogue++;
        }
        // Familie anklicken
        function handleFamilyClick() {
            if (gameStarted){
                const $item = $(this);
                if ($item.hasClass('disabled') || $item.hasClass('list-group-item-success')) return;
                selectedFamily = $item.data('family');
                $('#hashInput').val(selectedFamily);
                $('#hashResult').text('Ergebnis ...');
                $('#hashButton').prop('disabled', false);
                $('.to-do-family').removeClass('active');
                $item.addClass('active');
                $('.house').removeClass('highlight-target quadratic-target');
                $('#dialogueText').text(`Okay, Familie ${selectedFamily}. Berechne jetzt die Hausnummer!`);
            }
        }
        // --- Listener für Dialoge ---
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
        // --- Level 5 Spielmechanik ---
        // Aktivieren des Buttons, sobald etwas eingegeben ist
        $('#hashInput').on('input', function() {
            if ($(this).val().trim() !== '') {
                $('#hashButton').prop('disabled', false);
            } else {
                $('#hashButton').prop('disabled', true);
            }
        });
        // Familienliste direkt beim Laden ausgrauen
        initFamilyListUI();
        // 2. Hash-Wert berechnen
        $('#hashButton').click(function() {
            if (gameCompleted) return;
            const family = $('#hashInput').val().trim();
            if (!family) return;
            const anzeige = getHash(family, HASH_SIZE);
            const result = quadraticProbing(family, HASH_SIZE, stadt);
            const hashSteps = result.steps;
            const finalIndex = result.finalIndex;
            $('#hashResult').text(`Hausnummer: ${anzeige}`);
            if (searchMode) {
                if (family === 'Thomas') {
                    $('#dialogueText').text(`Laut Rechner wohnt Thomas in Haus ${anzeige}. Doch durch das Probing könnte sich der Index verschoben haben. Vollziehe die Schritte von vorher nach!`);
                }
            } else {
                $('.house').removeClass('highlight-target quadratic-target');
                if (!firstCollisionHandled && hashSteps.length > 1) {
                    // Belegte Häuser rot markieren
                    hashSteps.slice(0, -1).forEach(step => {
                        $(`.house[data-house=${step}]`).addClass('quadratic-target');
                    });
                    // Freies Haus gelb markieren
                    $(`.house[data-house=${finalIndex}]`).addClass('highlight-target');
                    let stepsText = hashSteps.map((step, index) => {
                        if (index === 0) {
                            return `Initial-Hash: ${step}`;
                        } else if (index < hashSteps.length - 1) {
                            return `Haus ${step} (belegt)`;
                        } else {
                            return `Haus ${step} (frei)`;
                        }
                    }).join(" → ");
                    $('#dialogueText').html(
                        `Kollision! Der Platzierungsprozess war: <strong>${stepsText}</strong>. Klicke auf das freie Haus.`
                    );
                    firstCollisionHandled = true;
                } else {
                    $('#dialogueText').text(
                        `Laut Rechner gehört Familie ${family} in Haus ${anzeige}. Klicke auf das Haus, um sie einziehen zu lassen.`
                    );
                }
            }
        });
        // 3. Haus klicken, um Familie zu platzieren oder Bewohner zu suchen
        $('.house').click(function() {
            const $house = $(this);
            const houseNumber = parseInt($house.data('house'));
            if (searchMode) {
                const occupant = stadt[houseNumber];
                if (occupant) {
                    $house.addClass('show-family');
                    $house.find('.house-family').text(occupant);
                    const result = quadraticProbing('Thomas', HASH_SIZE, stadt);
                    const steps = result.steps;
                    if (steps.includes(houseNumber)) {
                        $('#dialogueText').text("Du bist auf dem richtigen Weg!");
                    } else {
                        $('#dialogueText').text("Dieses Haus kommt nicht in Frage.");
                    }
                    if (occupant === 'Thomas') {
                        $('#successMessage').html(
                            `<strong style="color: #667eea;">Major Mike sagt:</strong><br>
                            "${successDialogue}"`
                        );
                        $('#finalAttempts').text(attempts);
                        $('#finalOccupied').text(occupiedHouses);
                        $('#successOverlay').css('display', 'flex');
                        gameCompleted = true;
                    }
                }
            } else {
                if (gameCompleted || !gameStarted || !selectedFamily) {
                    if (gameStarted && !gameCompleted) $('#dialogueText').text(`Du musst erst eine Familie auswählen und ihren Hash berechnen!`);
                    return;
                }
                const finalIndex = quadraticProbing(selectedFamily, HASH_SIZE, stadt).finalIndex;
                if (houseNumber !== finalIndex) {
                    $('#dialogueText').text(errorDialogue);
                    return;
                }
                const currentOccupant = stadt[houseNumber];
                if (currentOccupant === null) {
                    // --- HAUS IST FREI ---
                    stadt[houseNumber] = selectedFamily;
                    setHouseAsset($house, true);
                    $house.addClass('checked');
                    $house.removeClass('highlight-target quadratic-target');
                    $house.find('.house-family').text(selectedFamily);
                    $(`.to-do-family[data-family="${selectedFamily}"]`)
                        .removeClass('active')
                        .addClass('list-group-item-success')
                        .css('opacity', '1');
                    occupiedHouses++;
                    $('#occupiedCount').text(occupiedHouses + ' / 5');
                    currentFamilyIndex++;
                    if (currentFamilyIndex < families.length) {
                        $('#dialogueText').text(`Sehr gut! Familie ${selectedFamily} ist in Haus ${houseNumber} eingezogen.`);
                        const nextFamily = families[currentFamilyIndex];
                        $('.to-do-family').removeClass('active');
                        $(`.to-do-family[data-family="${nextFamily}"]`).removeClass('disabled').css('opacity', '1').on('click', handleFamilyClick).addClass('active');
                        selectedFamily = nextFamily;
                        $('#hashInput').val(selectedFamily);
                        $('#hashButton').prop('disabled', false);
                    } else {
                        $('#dialogueText').text(thomasSearchDialogue);
                        searchMode = true;
                        $('#hashInput').prop('readonly', false).val('');
                    }
                    $('#hashResult').text('Ergebnis: ...');
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
                window.location.href = 'level-auswahl.php?completed=5&next=6';
            }, 500);
        };
    });
</script>
</body>
</html>
