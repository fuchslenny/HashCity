<?php
/**
 * HashCity - Level 8: Anwendung Double Hashing (Advanced)
 *
 * Lernziel: Selbstständiges Anwenden von Double Hashing.
 * Suche: Paul (Erzeugt eine schöne Kollisionskette für den Lerneffekt).
 */
$anzahl_haeuser = 20; // 0-19
// Familien
$familien_liste = [
        "Thomas", "Ute", "David", "Sophie", "Tim",
        "Ada", "Leo", "Olaf", "Mika", "Georg",
        "Renate", "Paul", "Kurt", "Nora", "Ida"
];
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HashCity - Level 8: Double Hashing Praxis</title>
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
            font-size: 0.9rem;
            font-weight: 900;
            color: white;
            text-shadow: 2px 2px 6px rgba(0,0,0,0.7);
            z-index: 10;
            background: rgba(0, 0, 0, 0.3);
            padding: 0.1rem 0.4rem;
            border-radius: 6px;
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
        .list-group-item.to-do-family.done {
            opacity: 1;
            background: #e0e0e0;
            cursor: not-allowed;
            text-decoration: line-through;
        }
        .list-group-item.to-do-family.disabled {
            opacity: 0.5;
            cursor: not-allowed;
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
        .step-calculator { background: linear-gradient(135deg, #fff3e0 0%, #fff 100%); border-color: #FF9800; opacity: 0.5; pointer-events: none; transition: opacity 0.3s; }
        .step-calculator.active { opacity: 1; pointer-events: all; }
        .btn-secondary-calc { background: linear-gradient(135deg, #FF9800 0%, #FFB74D 100%); }
        .calc2-button { padding: 0.6rem 1.5rem; border: none; border-radius: 30px; font-family: 'Orbitron', sans-serif; font-weight: 700; font-size: 0.9rem; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(0,0,0,0.1); color: white; width: 100%; margin-top: 0.5rem; }
        .hash-result-value { font-family: 'Orbitron', sans-serif; font-size: 2.2rem; font-weight: 900; color: #667eea; text-align: center; margin: 0.5rem 0; }
    </style>
</head>
<body>
<div class="sky-section">
    <div class="cloud" style="top: 10%; left: 10%;"></div>
    <div class="cloud" style="top: 20%; left: 60%;"></div>
</div>
<div class="grass-section"></div>
<div class="game-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-auto">
                <a href="level-select.php" class="back-btn">Zurück</a>
            </div>
        </div>
    </div>
</div>
<div class="game-container">
    <div class="game-area">
        <div class="major-mike-section">
            <div class="major-mike-avatar">
                <img src="./assets/wink_major.png" alt="Major Mike" id="majorMikeImage">
            </div>
            <div class="major-mike-name">🎖️ Major Mike 🎖️</div>
            <div class="dialogue-box" id="dialogueBox">
                <div class="dialogue-text" id="dialogueText">...</div>
                <div class="dialogue-continue" id="dialogueContinue">
                    Klicken oder Enter ↵
                </div>
            </div>
        </div>
        <div class="houses-grid">
            <h2 class="grid-title">🏘️ Level 8: Double-Hashing 2</h2>
            <?php for ($b = 0; $b < 4; $b++): ?>
                <div class="street-block">
                    <div class="houses-row">
                        <?php for ($i = 0; $i < 5; $i++):
                            $hNum = $b * 5 + $i;
                            ?>
                            <div class="house" id="house-<?php echo $hNum; ?>" data-index="<?php echo $hNum; ?>">
                                <img src="./assets/empty_house.svg" alt="Haus <?php echo $hNum; ?>" class="house-icon">
                                <div class="house-number"><?php echo $hNum; ?></div>
                                <div class="house-family"></div>
                            </div>
                        <?php endfor; ?>
                    </div>
                    <div class="street"></div>
                </div>
            <?php endfor; ?>
        </div>
        <div class="info-panel">
            <h3 class="info-title">📊 Stadtplanung</h3>
            <div class="info-item">
                <div class="info-label">Einziehende Familien:</div>
                <div class="family-list-container">
                    <ul id="familienListe" class="list-group" style="padding-left: 0; list-style: none;">
                        <?php foreach ($familien_liste as $idx => $familie): ?>
                            <li class="list-group-item to-do-family disabled" data-index="<?php echo $idx; ?>">
                                <?php echo $familie; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <div class="info-item hash-calculator">
                <div class="info-label">Bewohnername:</div>
                <input type="text" id="nameInput" class="calculator-input" placeholder="Namen eingeben..." readonly>
                <button id="btnCalcH1" class="calculator-button">Berechne Haus-Nr.</button>
                <div class="calculator-result" id="h1Result">Ergebnis ...</div>
            </div>
            <div class="info-item step-calculator" id="stepCalcBox">
                <div class="info-label">2. Hash (Schrittweite)</div>
                <div style="font-size: 0.8rem; color: #666; margin-bottom: 5px; font-weight: 600;">(ASCII Summe) % 5 + 1</div>
                <div class="hash-result-value" id="h2Result">-</div>
                <button id="btnCalcH2" class="calc2-button btn-secondary-calc" disabled>Sprungweite berechnen</button>
            </div>
        </div>
    </div>
</div>
<div class="success-overlay" id="successOverlay">
    <div class="success-modal">
        <div class="success-icon">🎉</div>
        <h2 class="success-title">Geschafft!</h2>
        <p class="success-message" id="successMessage">
            Danke für deine Hilfe!
        </p>
        <div class="success-buttons">
            <button class="btn-secondary" onclick="restartLevel()">↻ Nochmal</button>
            <button class="btn-primary" onclick="nextLevel()">Weiter zu Level 9 →</button>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function() {
        // --- Haus-Paare für Assets ---
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
            { empty: "WohnhauRotRotLeerNeu.svg", filled: "WohnhauRotRotBesetztNeu.svg" },
        ];

        // Sound-Dateien laden
        const soundClick   = new Audio('./assets/sounds/click.mp3');
        const soundSuccess = new Audio('./assets/sounds/success.mp3');
        const soundError   = new Audio('./assets/sounds/error.mp3');

        soundSuccess.volume = 0.4;
        soundError.volume = 0.3;
        soundClick.volume = 0.5;

        const dialogueAudios = [
            new Audio('./assets/sounds/Lvl8/Lvl8_1.mp3'),
            new Audio('./assets/sounds/Lvl8/Lvl8_2.mp3')
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
                audio.currentTime = 0; // Spult zum Anfang zurück
                audio.play().catch(e => console.log("Audio play blocked", e)); // Fängt Browser-Blockaden ab
            }
        }

        // --- Zufällige Auswahl der Assets für die Häuser ---
        function getRandomHousePair() {
            const randomIndex = Math.floor(Math.random() * housePairs.length);
            return housePairs[randomIndex];
        }

        // --- Setzt das Haus-Asset ---
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

        // --- Initialisierung der Häuser mit zufälligen Assets ---
        $('.house').each(function() {
            const $house = $(this);
            const pair = getRandomHousePair();
            $house.find('.house-icon').attr('src', `./assets/${pair.empty}`);
            $house.data('empty-asset', pair.empty);
            $house.data('filled-asset', pair.filled);
        });

        // --- Konfiguration ---
        const HASH_SIZE = 20;
        const HASH_SIZE_2 = 10;
        const families = <?php echo json_encode($familien_liste); ?>;
        // State
        let city = new Array(HASH_SIZE).fill(null);
        let currentFamilyIdx = 0;
        let selectedFamily = null;
        let phase = 'intro';
        let h1Value = null;
        let h2Value = null;
        let currentProbeIndex = null;
        const SEARCH_TARGET = "Paul";
        let searchH1 = null;
        let searchH2 = null;
        // Sperr-Variable für Animationen
        let isFading = false;
        // Dialoge
        const dialogues = [
            "Das sieht ja schon richtig gut aus! Du darfst jetzt diesen neuen Stadtteil allein bearbeiten.",
            "Verwende dafür Double Hashing, falls es zu Kollisionen kommt. Beachte dabei, dass du die Liste von oben nach unten abarbeitest.",
        ];

        let currentDialogue = -1;
        // --- Helper Functions ---
        function getAsciiSum(name) {
            let sum = 0;
            for(let i=0; i<name.length; i++) sum += name.charCodeAt(i);
            return sum;
        }
        function calcH1(name) { return getAsciiSum(name) % HASH_SIZE; }
        function calcH2(name) { return (getAsciiSum(name) % HASH_SIZE_2) + 1; }
        function getRandomHouseAsset() {
            const randomIndex = Math.floor(Math.random() * housePairs.length);
            return housePairs[randomIndex].filled;
        }
        // --- UI Updates ---
        function showDialogue(text, image = 'card_major.png') {
            if (isFading && text !== dialogues[0]) return;
            playDialogueAudio(currentDialogue);
            isFading = true;
            $('#majorMikeImage').attr('src', './assets/' + image);
            $('#dialogueText').fadeOut(150, function() {
                $(this).text(text).fadeIn(150, function() {
                    isFading = false;
                });
            });
        }
        function advanceDialogue() {
            if(isFading) return;
            if (currentDialogue < dialogues.length) {
                showDialogue(dialogues[currentDialogue]);
                currentDialogue++;
            } else {
                if (phase === 'intro') {
                    $('#dialogueContinue').fadeOut();
                    phase = 'calc_h1';
                    selectNextFamily();
                    showDialogue("Klicke auf " + families[currentFamilyIdx] + " in der Liste, um zu starten.", 'wink_major.png');
                }
            }
        }
        // Interaktion
        $('#dialogueBox').click(function() {
            if (phase === 'intro') advanceDialogue();
        });
        $(document).keydown(function(e) {
            if(e.key === 'Enter' || e.key === ' ') {
                if (phase === 'intro') advanceDialogue();
            }
        });

        function selectNextFamily() {
            if (currentFamilyIdx >= families.length) {
                endLevel();
                return;
            }
            selectedFamily = families[currentFamilyIdx];
            $('#nameInput').val(selectedFamily);
            $('.list-group-item').removeClass('active');
            $(`.list-group-item[data-index="${currentFamilyIdx}"]`).removeClass('disabled').addClass('active');
        }

        // 1. Liste Klicken
        $(document).on('click', '.list-group-item', function() {
            if (isFading || phase !== 'select_family') return;
            let idx = $(this).data('index');
            if (idx !== currentFamilyIdx) {
                showDialogue("Bitte arbeite die Liste von oben nach unten ab.");
                return;
            }
            selectedFamily = families[idx];
            $('#btnCalcH1').prop('disabled', false);
            $('#h1Result').text('Ergebnis ...');
            $('#h2Result').text('-');
            $('#stepCalcBox').removeClass('active');
            phase = 'calc_h1';
            showDialogue(`Okay, wir platzieren ${selectedFamily}. Berechne zuerst die Startposition (1. Hash).`);
        });
        // 2. H1 Berechnen
        $('#btnCalcH1').click(function() {
            if (isFading) return;
            if (phase !== 'calc_h1' && phase !== 'search_calc_h1') return;
            let val = calcH1(selectedFamily);
            if (phase === 'search_calc_h1') {
                selectedFamily = $('#nameInput').val();
                val = calcH1(selectedFamily);
                if(selectedFamily === undefined || selectedFamily === null || selectedFamily === '' ){
                    return;
                }
                if(selectedFamily !== "Paul"){
                    showDialogue(`Derzeit suchen wir nach Paul und nicht nach ${selectedFamily}`);
                }else{
                    searchH1 = val;
                    $('#h1Result').text(`Hausnummer: ${val}`);
                    showDialogue(`Der Start-Hash für Paul ist ${val}. Klicke auf Haus ${val} um nachzusehen.`);
                    phase = 'search_check_h1';
                    $(this).prop('disabled', true);
                }
                return;
            }
            h1Value = val;
            $('#h1Result').text(`Hausnummer: ${val}`);
            phase = 'place_h1';

        });
        // 3. Haus Klicken
        $('.house').click(function() {
            if (isFading) return;
            let houseIdx = $(this).data('index');
            let $house = $(this);
            if (phase.startsWith('search_')) {
                handleSearchClick(houseIdx, $house);
                return;
            }
            console.log("phase: " + phase);
            if (phase === 'place_h1') {
                if (houseIdx !== h1Value) {
                    playSound('error');
                    showDialogue("Das war das falsche Haus. Der Rechner sagt " + h1Value + ".");
                    return;
                }
                if (city[houseIdx] === null) {
                    placeFamily(houseIdx);
                } else {
                    handleCollision(houseIdx);
                }
            }
            else if (phase === 'place_apply_step') {
                let expectedIdx = (currentProbeIndex + h2Value) % HASH_SIZE;
                if (houseIdx !== expectedIdx) {
                    playSound('error');
                    showDialogue(`Falsch! Wir waren bei ${currentProbeIndex}. Plus Schrittweite ${h2Value} (modulo 10) ist Haus ${expectedIdx}.`);
                    return;
                }
                if (city[houseIdx] === null) {
                    placeFamily(houseIdx);
                } else {
                    playSound('error');
                    currentProbeIndex = houseIdx;
                    showDialogue(`Oha! Haus ${houseIdx} ist AUCH besetzt. Wir müssen NOCHMAL springen. Addiere wieder ${h2Value}!`, 'sad_major.png');
                    $('.house').removeClass('highlight-target');
                    let nextTarget = (currentProbeIndex + h2Value) % HASH_SIZE;
                }
            }
        });
        function placeFamily(idx) {
            playSound('click');
            city[idx] = selectedFamily;
            let $house = $(`#house-${idx}`);
            setHouseAsset($house, true);
            $house.addClass('found');
            setTimeout(() => $house.removeClass('found').addClass('checked'), 500);
            $(`.list-group-item[data-index="${currentFamilyIdx}"]`).removeClass('active').addClass('done');
            $('.house').removeClass('highlight-target');
            $('#h1Result').text('Ergebnis ...');
            $('#h2Result').text('-');
            $('#stepCalcBox').removeClass('active');
            showDialogue(`Sehr gut! ${selectedFamily} wohnt jetzt in Haus ${idx}.`);
            currentFamilyIdx++;
            if (currentFamilyIdx < families.length) {
                phase = "calc_h1";
                $('#btnCalcH1').prop('disabled', false);
                selectNextFamily();
                setTimeout(() => {
                    if(!isFading) showDialogue("Okay, nächster Bewohner.");
                }, 1000);
            } else {
                startSearchPhase();
            }
        }
        function handleCollision(idx) {
            playSound('error');
            showDialogue(`Mist! Haus ${idx} ist schon belegt. Eine Kollision! Wir brauchen Double Hashing. Klicke auf den 2. Hash Rechner!`, 'sad_major.png');
            $('#stepCalcBox').addClass('active');
            $('#btnCalcH1').prop('disabled', true);
            $('#btnCalcH2').prop('disabled', false);
            currentProbeIndex = idx;
            phase = 'calc_h2';
            let $house = $(`#house-${idx}`);
            $house.addClass('found');
            setTimeout(() => $house.removeClass('found'), 500);
        }
        // 4. H2 Berechnen
        $('#btnCalcH2').click(function() {
            if (isFading) return;
            if (phase !== 'calc_h2' && phase !== 'search_calc_h2') return;
            let name = (phase.startsWith('search')) ? SEARCH_TARGET : selectedFamily;
            let step = calcH2(name);
            h2Value = step;
            $('#h2Result').text(step);
            $(this).prop('disabled', true);
            if (phase === 'search_calc_h2') {
                searchH2 = step;
                $('.house').removeClass('highlight-target');
                phase = 'search_check_step';
                return;
            }
            $('.house').removeClass('highlight-target');
            phase = 'place_apply_step';
        });
        // --- Search Phase ---
        function startSearchPhase() {
            phase = 'intro_search';
            showDialogue("Alle Bewohner sind untergebracht! Super Arbeit.", 'wink_major.png');
            $('#btnCalcH1').prop('disabled', true);
            $('#btnCalcH2').prop('disabled', true);
            setTimeout(() => {
                showDialogue("Ich bin heute Abend bei Paul eingeladen. Kannst du mir seine Hausnummer sagen? (Nutze den Rechner!)");
                $('#h1Result').text('Ergebnis ...');
                $('#h2Result').text('-');
                $('#stepCalcBox').removeClass('active');
                $('#nameInput').val('').prop('readonly',false);
                $('#btnCalcH1').prop('disabled', false);
                phase = 'search_calc_h1';
            }, 2000);
        }
        function handleSearchClick(houseIdx, $house) {
            let occupant = city[houseIdx];
            if (occupant) {
                $house.find('.house-family').text(occupant).css('opacity', 1);
            }
            if (phase === 'search_check_h1') {
                if (houseIdx !== searchH1) {
                    showDialogue(`Das ist nicht der Start-Hash (${searchH1}).`);
                    return;
                }
                if (city[houseIdx] === SEARCH_TARGET) {
                    endLevel();
                } else {
                    showDialogue(`Das ist ${city[houseIdx]}, nicht Paul! Wir brauchen den 2. Hash für die Suche. Klicke unten auf 'Sprungweite berechnen'.`, 'sad_major.png');
                    $('#stepCalcBox').addClass('active');
                    $('#btnCalcH2').prop('disabled', false);
                    phase = 'search_calc_h2';
                }
            }
            else if (phase === 'search_check_step') {
                let expected = (searchH1 + searchH2) % HASH_SIZE;
                if (houseIdx !== expected) {
                    showDialogue(`Falsch. Start (${searchH1}) + Schritt (${searchH2}) = ${expected}.`);
                    return;
                }
                if (city[houseIdx] === SEARCH_TARGET) {
                    $house.addClass('found');
                    endLevel();
                } else {
                    searchH1 = houseIdx;
                    showDialogue(`Das ist ${city[houseIdx]}! Immer noch nicht. Addiere nochmal die Schrittweite ${searchH2}.`);
                    let next = (searchH1 + searchH2) % HASH_SIZE;
                    $('.house').removeClass('highlight-target');
                }
            }
        }
        function endLevel() {
            playSound('success');
            $('#successMessage').text("Danke für deine Hilfe, so funktioniert alles viel besser!");
            $('#successOverlay').css('display', 'flex');
        }
        // Start
        advanceDialogue();
        // Globale Funktionen für Modal-Buttons
        window.restartLevel = function() {
            location.reload();
        };
        window.nextLevel = function() {
            $('body').css('transition', 'opacity 0.5s ease');
            $('body').css('opacity', '0');
            setTimeout(function() {
                window.location.href = 'level-select.php?page=2&completed=8&level=9';
            }, 500);
        };
    });
</script>
</body>
</html>
