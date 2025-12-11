<?php
/**
 * HashCity - Level 11: Rehashing (Final Polish)
 */
$anzahl_haeuser = 40;
// Die 19 "Bestands-Bewohner" aus Level 10
$old_residents = [
        "Thomas", "Laura", "Paul", "Clara", "Emma",
        "Elena", "Mueller", "Jonas", "David", "Stefan",
        "Tobias", "Bernd", "Anton", "Legat", "Lea",
        "Thorsten", "Sophie", "Katrin", "Leon"
];
// Die "Neuen"
$new_residents = [
        "Levi", "Simon", "Kira"
];
// Zusammengefügte Liste
$familien_liste = array_merge($old_residents, $new_residents);
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HashCity - Level 11: Rehashing</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Rajdhani:wght@300;500;700&display=swap" rel="stylesheet">
    <style>
        /* --- Styles (Konsistent) --- */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Rajdhani', sans-serif; overflow-x: hidden; min-height: 100vh; position: relative; background: #4CAF50; }
        .sky-section { position: fixed; top: 0; left: 0; width: 100%; height: 50%; background: linear-gradient(180deg, #87CEEB 0%, #B0D4E3 100%); z-index: 0; }
        .grass-section { position: fixed; bottom: 0; left: 0; width: 100%; height: 50%; background: linear-gradient(180deg, #76B947 0%, #4CAF50 100%); z-index: 0; }
        .cloud { position: absolute; background: rgba(255, 255, 255, 0.7); border-radius: 100px; opacity: 0.8; animation: cloudFloat 40s linear infinite; }
        @keyframes cloudFloat { 0% { left: -200px; } 100% { left: 110%; } }
        .game-header { background: transparent; padding: 1rem 2rem; box-shadow: 0 4px 15px rgba(0,0,0,0.1); position: relative; top: 0; z-index: 1000; backdrop-filter: blur(10px); }
        .back-btn { padding: 0.7rem 1.3rem; background: rgba(255, 255, 255, 0.9); border: 2px solid rgba(102, 126, 234, 0.5); border-radius: 30px; font-weight: 700; color: #667eea; cursor: pointer; transition: all 0.3s ease; font-family: 'Orbitron', sans-serif; text-decoration: none; display: inline-block; font-size: 0.9rem; }
        .back-btn:hover { background: #667eea; color: #fff; transform: scale(1.05); }
        .back-btn::before { content: '← '; margin-right: 5px; }
        .game-container { max-width: 1800px; margin: 2rem auto; padding: 0 2rem; position: relative; z-index: 1; }
        .game-area { display: grid; grid-template-columns: 280px 1fr 400px; gap: 2rem; min-height: 70vh; }
        .major-mike-section { background: rgba(255, 255, 255, 0.85); border-radius: 25px; padding: 1.5rem; box-shadow: 0 10px 40px rgba(0,0,0,0.15); height: fit-content; position: sticky; top: 100px; border: 4px solid #fff; }
        .major-mike-avatar { width: 100%; height: 240px; background: transparent; border-radius: 15px; display: flex; align-items: center; justify-content: center; margin-bottom: 1rem; overflow: hidden; position: relative; }
        .major-mike-avatar img { width: 100%; height: 100%; object-fit: contain; }
        .major-mike-name { font-family: 'Orbitron', sans-serif; font-size: 1.4rem; font-weight: 900; color: #667eea; text-align: center; margin-bottom: 1rem; text-shadow: 2px 2px 4px rgba(0,0,0,0.1); }
        .dialogue-box { background: #fff; border: 3px solid #667eea; border-radius: 20px; padding: 1.5rem; min-height: 180px; position: relative; box-shadow: 0 6px 20px rgba(102, 126, 234, 0.2); cursor: pointer; }
        .dialogue-box::before { content: ''; position: absolute; top: -15px; left: 50%; transform: translateX(-50%); width: 0; height: 0; border-left: 15px solid transparent; border-right: 15px solid transparent; border-bottom: 15px solid #667eea; }
        .dialogue-text { font-size: 1.05rem; line-height: 1.7; color: #333; font-weight: 500; }
        .dialogue-continue { position: absolute; bottom: 10px; right: 15px; font-size: 0.85rem; color: #667eea; font-style: italic; font-weight: 700; animation: blink 1.5s infinite; }
        @keyframes blink { 0%, 50%, 100% { opacity: 1; } 25%, 75% { opacity: 0.5; } }
        /* GRID dynamisch */
        .houses-grid { background: rgba(255, 255, 255, 0.85); border-radius: 25px; padding: 2rem; box-shadow: 0 10px 40px rgba(0,0,0,0.15); border: 4px solid #fff; overflow: hidden; transition: all 0.5s ease; }
        .grid-title { font-family: 'Orbitron', sans-serif; font-size: 1.8rem; font-weight: 900; color: #2E7D32; text-align: center; margin-bottom: 2rem; text-shadow: 2px 2px 4px rgba(0,0,0,0.1); }
        .street-block { position: relative; margin-bottom: 2rem; }
        /* 40 Häuser -> 4 Reihen à 10 Häuser */
        .houses-row { display: grid; grid-template-columns: repeat(10, 1fr); gap: 0.5rem; padding: 0 0.5rem; position: relative; z-index: 2; transition: all 0.5s ease; }
        .street { width: 100%; height: 50px; background-image: url('./assets/Strasse.svg'); background-size: cover; background-position: center; position: relative; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.15); z-index: 10; margin-bottom: 1rem;}
        .street::before { content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(180deg, #4a4a4a 0%, #2a2a2a 100%); border-radius: 8px; z-index: -1; }
        .street::after { content: ''; position: absolute; top: 50%; left: 0; width: 100%; height: 4px; background: repeating-linear-gradient(90deg, #fff 0px, #fff 30px, transparent 30px, transparent 50px); transform: translateY(-50%); z-index: 2; }
        .house.hidden { display: none; }
        .street.hidden { display: none; }
        /* Pop-Animation für Rehashing */
        @keyframes popIn {
            0% { transform: scale(0.5); opacity: 0; }
            70% { transform: scale(1.2); opacity: 1; }
            100% { transform: scale(1); opacity: 1; }
        }
        .house.pop-in { animation: popIn 0.4s ease-out forwards; }
        .house {
            margin-bottom: -5px;
            position: relative;
            display: flex;
            flex-direction: column-reverse;
            align-items: center;
            cursor: pointer;
            transition: transform 0.2s;
        }
        .house:hover:not(.checked):not(.found) { transform: translateY(-5px) scale(1.1); z-index: 10; }
        .house.highlight-target { transform: translateY(-8px) scale(1.2) !important; box-shadow: 0 0 25px 5px gold; z-index: 11; }
        .house-icon { width: 100%; height: 100%; object-fit: contain; transition: all 0.3s ease; filter: drop-shadow(0 3px 6px rgba(0,0,0,0.2)); }
        .house.found .house-icon { animation: pulse 1.5s infinite; filter: drop-shadow(0 6px 12px rgba(76, 175, 80, 0.8)); }
        .house.checked .house-icon { filter: drop-shadow(0 0 5px rgba(255, 200, 0, 0.6)); }
        .house-number { position: absolute; top: 30%; left: 50%; transform: translateX(-50%); font-family: 'Orbitron', sans-serif; font-size: 0.7rem; font-weight: 900; color: white; text-shadow: 1px 1px 3px rgba(0,0,0,0.8); z-index: 10; background: rgba(0, 0, 0, 0.4); padding: 0 0.3rem; border-radius: 4px; }
        .house-family { display: none; }
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
        /* Für bereits eingezogene Familien */
        .list-group-item.done {
            opacity: 1;
            background: #e0e0e0;
            cursor: not-allowed;
            text-decoration: line-through;
        }
        /* Für Familien, die noch nicht dran sind */
        .list-group-item.disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        /* Aktive Familie */
        .list-group-item.active {
            background: #667eea;
            color: #fff;
            transform: scale(1.02);
            z-index: 10;
            opacity: 1;
        }
        /* Load Factor Display */
        .load-factor-box { text-align: center; padding: 0.5rem; background: #f0f0f0; border-radius: 10px; margin-bottom: 1rem; border: 2px solid #ccc; transition: all 0.5s ease; }
        .lf-value { font-family: 'Orbitron', sans-serif; font-size: 1.5rem; font-weight: bold; color: #333; }
        .lf-label { font-size: 0.8rem; color: #666; }
        /* Ampel-Farben für Load Factor */
        .lf-good {
            color: #4CAF50;
            border-color: #4CAF50;
            background: #e8f5e9;
        } /* <= 0.5 */
        .lf-medium {
            color: #FF9800;
            border-color: #FF9800;
            background: #fff3e0;
        } /* 0.5 - 0.75 */
        .lf-bad {
            color: #D32F2F;
            border-color: #D32F2F;
            background: #FFEBEE;
        } /* > 0.75 */
        /* Expand Button Style */
        .expand-btn { background: linear-gradient(135deg, #FF9800 0%, #FF5722 100%); font-size: 1.1rem; padding: 0.8rem; display: none; margin-bottom: 1.5rem;}
        .family-list-container { max-height: 200px; overflow-y: auto; overflow-x: hidden; }
        /* Load Factor Display */
        .load-factor-box { text-align: center; padding: 0.5rem; background: #f0f0f0; border-radius: 10px; margin-bottom: 1rem; border: 2px solid #ccc; transition: all 0.5s ease; }
        .lf-value { font-family: 'Orbitron', sans-serif; font-size: 1.5rem; font-weight: bold; color: #333; }
        .lf-label { font-size: 0.8rem; color: #666; }
        /* Ampel-Farben für Load Factor */
        .lf-good { color: #4CAF50; border-color: #4CAF50; background: #e8f5e9; } /* <= 0.5 */
        .lf-medium { color: #FF9800; border-color: #FF9800; background: #fff3e0; } /* 0.5 - 0.75 */
        .lf-bad { color: #D32F2F; border-color: #D32F2F; background: #FFEBEE; } /* > 0.75 */
        .success-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.85); display: none; align-items: center; justify-content: center; z-index: 2000; animation: fadeIn 0.3s ease; backdrop-filter: blur(5px); }
        .success-modal { background: white; border-radius: 30px; padding: 3rem; max-width: 650px; text-align: center; box-shadow: 0 20px 60px rgba(0,0,0,0.4); animation: slideUp 0.5s ease; border: 5px solid #4CAF50; }
        .success-icon { font-size: 5rem; margin-bottom: 1rem; animation: bounce 1s infinite; }
        .success-title { font-family: 'Orbitron', sans-serif; font-size: 2.8rem; font-weight: 900; color: #4CAF50; margin-bottom: 1rem; text-shadow: 2px 2px 4px rgba(0,0,0,0.1); }
        .success-message { font-size: 1.2rem; color: #666; line-height: 1.7; margin-bottom: 2rem; font-weight: 500; }
        .success-buttons { display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; }
        .btn-primary, .btn-secondary { padding: 1rem 2.5rem; border: none; border-radius: 30px; font-family: 'Orbitron', sans-serif; font-weight: 700; font-size: 1.05rem; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(0,0,0,0.2); }
        .btn-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
        .btn-secondary { background: white; color: #667eea; border: 3px solid #667eea; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes slideUp { from { transform: translateY(100px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        @keyframes bounce { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-10px); } }
        @keyframes pulse { 0%, 100% { transform: scale(1); } 50% { transform: scale(1.08); } }
        @media (max-width: 1400px) { .game-area { grid-template-columns: 1fr; gap: 1.5rem; } .major-mike-section, .info-panel { position: static; } .houses-row { grid-template-columns: repeat(8, 1fr); } }
        @media (max-width: 768px) { .houses-row { grid-template-columns: repeat(5, 1fr); } }
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
            <h2 class="grid-title" id="gridTitle">🏘️ Level 11: Re-Hashing</h2>
            <?php for ($b = 0; $b < 4; $b++):
                // Blöcke 2 und 3 (Häuser 20-39) sind anfangs hidden
                $blockClass = ($b >= 2) ? 'hidden' : '';
                ?>
                <div class="street-block <?php echo $blockClass; ?>" id="block-<?php echo $b; ?>">
                    <div class="houses-row">
                        <?php for ($i = 0; $i < 10; $i++):
                            $hNum = $b * 10 + $i;
                            $houseClass = ($b >= 2) ? 'hidden' : '';
                            ?>
                            <div class="house <?php echo $houseClass; ?>" id="house-<?php echo $hNum; ?>" data-index="<?php echo $hNum; ?>">
                                <img src="./assets/empty_house.svg" alt="Haus <?php echo $hNum; ?>" class="house-icon">
                                <div class="house-number"><?php echo $hNum; ?></div>
                            </div>
                        <?php endfor; ?>
                    </div>
                    <div class="street <?php echo $blockClass; ?>"></div>
                </div>
            <?php endfor; ?>
        </div>
        <div class="info-panel">
            <h3 class="info-title">📊 Stadtplanung</h3>
                <div class="info-item">
                    <div class="info-label">Einziehende Familien:</div>
                    <div class="family-list-container">
                        <ul id="familienListe" class="list-group">
                            <?php foreach ($familien_liste as $index => $familie): ?>
                                <li class="list-group-item to-do-family <?php echo ($index < 19) ? 'done' : ''; ?>" data-family-index="<?php echo $index; ?>">
                                    <?php echo $familie; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
                <div class="info-item hash-calculator">
                    <label for="nameInput" class="info-label" style="color: #666; font-size: 0.95rem;">Bewohnername:</label>
                    <input type="text" id="nameInput" class="calculator-input" placeholder="Namen eingeben..." readonly>
                    <button id="hashButton" class="calculator-button">Berechne Haus-Nr.</button>
                    <div class="calculator-result" id="hashResult">
                        Ergebnis ...
                    </div>
                </div>
                <div class="load-factor-box" id="lfBox">
                    <div class="lf-label">Load Factor (Belegung)</div>
                    <div class="lf-value" id="lfValue">0.95</div>
                    <div class="lf-label" id="lfText">Kritisch (Zu voll!)</div>
                </div>
                <button id="btnExpand" class="calculator-button expand-btn">🏗️ STADT ERWEITERN</button>
                <div class="info-item">
                    <div class="info-label">Eingetragene Familien:</div>
                    <div class="info-value" id="occupiedCount">19 / 23</div>
                </div>
        </div>
    </div>
</div>
<div class="success-overlay" id="successOverlay">
    <div class="success-modal">
        <div class="success-icon">🎉</div>
        <h2 class="success-title">Perfekt!</h2>
        <p class="success-message" id="successMessage">
            Danke für deine Hilfe!
        </p>
        <div class="success-buttons">
            <button class="btn-secondary" onclick="restartLevel()">↻ Nochmal</button>
            <button class="btn-primary" onclick="nextLevel()">Zum Finale →</button>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function() {
        // --- Konfiguration ---
        let currentHashSize = 20;
        let occupiedHouses = 19;
        const TARGET_HASH_SIZE = 40;
        const families = <?php echo json_encode($familien_liste); ?>;
        // Paare von leeren und gefüllten Häusern
        const housePairs = [
            { empty: 'WohnhauBlauBraunLeerNeu.svg', filled: 'WohnhauBlauBraunBesetztNeu.svg' },
            { empty: 'WohnhauBlauGrauLeerNeu.svg', filled: 'WohnhauBlauGrauBesetztNeu.svg' },
            { empty: 'WohnhauBlauRotLeerNeu.svg', filled: 'WohnhauBlauRotBesetztNeu.svg' },
            { empty: 'WohnhauGelbBraunLeerNeu.svg', filled: 'WohnhauGelbBraunBesetztNeu.svg' },
            { empty: 'WohnhauGelbRotLeerNeu.svg', filled: 'WohnhauGelbRotBesetztNeu.svg' },
            { empty: 'WohnhauGrauBraunLeerNeu.svg', filled: 'WohnhauGrauBraunBesetztNeu.svg' },
            { empty: 'WohnhauGruenBraunLeerNeu.svg', filled: 'WohnhauGruenBraunBesetztNeu.svg' },
            { empty: 'WohnhauGruenGrauLeerNeu.svg', filled: 'WohnhauGruenGrauBesetztNeu.svg' },
            { empty: 'WohnhauRotRotLeerNeu.svg', filled: 'WohnhauRotRotBesetztNeu.svg' },
            { empty: 'WohnhauRotBraunLeerNeu.svg', filled: 'WohnhauRotBraunBesetztNeu.svg' },
        ];
        // Initial: 19/20 belegt
        let city = new Array(TARGET_HASH_SIZE).fill(null);
        // Die "To-Do" Liste beginnt ab Index 19 (Levi)
        let currentFamilyIdx = 19;
        let selectedFamily = null;
        let phase = 'intro';
        let h1Value = null;
        let isFading = false;
        const dialogues = [
            "Puh, das war knapp in Level 10! Wir haben 19 von 20 Häusern belegt (Load Factor 0.95).",
            "Der arme Levi passt nirgendwo mehr rein. Wenn wir ihn jetzt noch reinquetschen (Linear Probing), dauert das ewig.",
            "Die einzige Lösung: <strong>Rehashing</strong>! Wir verdoppeln die Stadt auf 40 Häuser und berechnen alle Positionen neu.",
            "Klicke auf 'STADT ERWEITERN', um das Problem zu lösen!"
        ];
        let dialogueIdx = 0;

        // Sound-Dateien laden
        const soundClick   = new Audio('./assets/sounds/click.mp3');
        const soundSuccess = new Audio('./assets/sounds/success.mp3');
        const soundError   = new Audio('./assets/sounds/error.mp3');

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

        // --- Helper ---
        function getAsciiSum(name) {
            let sum = 0;
            for(let i=0; i<name.length; i++) sum += name.charCodeAt(i);
            return sum;
        }
        function calcHash(name, size) { return getAsciiSum(name) % size; }
        // Funktion, um ein zufälliges Haus-Paar zu wählen
        function getRandomHousePair() {
            const randomIndex = Math.floor(Math.random() * housePairs.length);
            return housePairs[randomIndex];
        }
        // Funktion, um das Asset zu setzen
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
        function updateLoadFactor() {
            let lf = currentFamilyIdx / currentHashSize;
            $('#lfValue').text(lf.toFixed(2));
            let $box = $('#lfBox');
            let $text = $('#lfText');
            // --- Neue Ampel-Logik ---
            $box.removeClass('lf-bad lf-medium lf-good');
            if (lf <= 0.5) {
                $box.addClass('lf-good');
                $text.text("Optimal (Viel Platz)");
            } else if (lf <= 0.75) {
                $box.addClass('lf-medium');
                $text.text("Okay (Wird voller)");
            } else {
                $box.addClass('lf-bad');
                $text.text("Kritisch (Zu voll!)");
            }
        }
        $('.house').each(function() {
            const $house = $(this);
            const pair = getRandomHousePair();
            $house.find('.house-icon').attr('src', `./assets/${pair.empty}`);
            $house.data('empty-asset', pair.empty);
            $house.data('filled-asset', pair.filled);
        });
        // Initiale Platzierung (Simuliert Level 10 - Chaos)
        function initCity() {
            for(let i=0; i<19; i++) {
                let fam = families[i];
                let pos = calcHash(fam, 20);
                // Simuliertes Probing
                while(city[pos] !== null) pos = (pos + 1) % 20;
                city[pos] = fam;
                let $house = $(`#house-${pos}`);
                const pair = getRandomHousePair();
                $house.find('.house-icon').attr('src', `./assets/${pair.filled}`);
                $house.data('empty-asset', pair.empty);
                $house.data('filled-asset', pair.filled);
                $house.addClass('checked');
            }
            // Für die neuen Häuser (20-39) ebenfalls zufällige Assets setzen
            for(let i=20; i<40; i++) {
                let $house = $(`#house-${i}`);
                const pair = getRandomHousePair();
                $house.find('.house-icon').attr('src', `./assets/${pair.empty}`);
                $house.data('empty-asset', pair.empty);
                $house.data('filled-asset', pair.filled);
            }
            $('#btnExpand').show();
            updateLoadFactor();
            // Initial die ersten 19 Familien als "done" markieren
            $('.list-group-item').each(function() {
                const idx = $(this).data('family-index');
                if (idx < 19) {
                    $(this).addClass('done');
                } else if (idx > currentFamilyIdx) {
                    $(this).addClass('disabled');
                }
            });
        }
        // --- UI Logic ---
        function showDialogue(text, image = 'card_major.png') {
            if (isFading && text !== dialogues[0]) return;
            isFading = true;
            $('#majorMikeImage').attr('src', './assets/' + image);
            $('#dialogueText').fadeOut(150, function() {
                $(this).html(text).fadeIn(150, function() { isFading = false; });
            });
        }
        function advanceDialogue() {
            if(isFading) return;
            if (dialogueIdx < dialogues.length) {
                if(dialogueIdx === dialogues.length -1){
                    $('#dialogueContinue').hide();
                    phase = "expand";
                }
                showDialogue(dialogues[dialogueIdx]);
                dialogueIdx++;
            }
        }
        $('#dialogueBox').click(() => {
            if (phase === 'intro') advanceDialogue();
        });
        $(document).keydown(e => { if((e.key === 'Enter') && phase === 'intro') advanceDialogue(); });
        // --- Expansion Logic ---
        $('#btnExpand').click(function() {
            if(phase === "intro") return;
            $(this).prop('disabled', true).text("BAUE HÄUSER...");
            // 1. Visuelle Erweiterung
            $('#block-2, #block-3').removeClass('hidden');
            $('.house.hidden').each(function(i) {
                let $el = $(this);
                setTimeout(() => {
                    $el.removeClass('hidden').addClass('pop-in');
                }, i * 30);
            });
            $('.street.hidden').removeClass('hidden');
            $('#occupiedCount').text(occupiedHouses + ' / 23');
            setTimeout(() => {
                // 2. Logik Update
                currentHashSize = 40;
                $('#gridTitle').text("🏘️ Level 11: Re-Hashing");
                updateLoadFactor(); // Load Factor halbiert sich (19/40)
                $(this).hide();
                showDialogue("Platz ist da! Aber alle wohnen noch an den 'alten' Adressen. Achtung, ich ordne jetzt ALLE neu an!", 'wink_major.png');
                setTimeout(performAutoRehash, 3000);
            }, 1500);
        });
        function performAutoRehash() {
            // Reset visuals
            // Reset Logic
            let tempResidents = [];
            for(let i=0; i<19; i++) tempResidents.push(families[i]);
            city.fill(null);
            // --- NEUE ANIMATION (Welleneffekt über ALLE Häuser) ---
            // 1. Logik berechnen
            let newPositions = {};
            tempResidents.forEach(fam => {
                let pos = calcHash(fam, 40);
                while(city[pos] !== null) pos = (pos + 1) % 40;
                city[pos] = fam;
                newPositions[pos] = true;
            });
            // 2. Animation von 0 bis 39
            for (let i = 0; i < 40; i++) {
                let $house = $(`#house-${i}`);
                $house.removeClass('checked');
                $house.removeClass('pop-in');
                setTimeout(() => {
                    $house.addClass('pop-in'); // "Schütteln" / Refresh Effekt
                    // Wenn hier wer wohnt -> Bild rein
                    if (newPositions[i]) {
                        setHouseAsset($house, true);
                        $house.addClass('checked');
                    }else{
                        setHouseAsset($house, false);
                    }
                }, i * 30); // Schnelle Welle (1.2 Sek total)
            }
            setTimeout(() => {
                phase = 'select_family';
                $('#calcContainer').css({opacity: 1, pointerEvents: 'all'});
                showDialogue(
                    "Siehst du? Weil wir jetzt <strong>durch 40 teilen</strong> (statt 20), ändern sich die Hausnummern!<br>" +
                    "Thomas (Hash 620) rechnet jetzt 620 % 40 = 20. Vorher war es 0.<br>" +
                    "Dadurch ist Haus 0 endlich frei für Levi!",
                    'wink_major.png'
                );
                placeNextFamily();
            }, 40 * 30 + 1000);
        }

        function placeNextFamily() {
            selectedFamily = families[currentFamilyIdx];
            $('#nameInput').val(selectedFamily);
            $('.list-group-item').removeClass('active');
            $(`.list-group-item[data-family-index="${currentFamilyIdx}"]`).removeClass('disabled').addClass('active');
            $('.family-list-container').animate({
                scrollTop: $('.family-list-container')[0].scrollHeight
            }, 300);

            h1Value = calcHash(selectedFamily, 40);
            phase = 'find_spot';
        }


        $('#hashButton').click(function() {
            if (currentFamilyIdx >= families.length || phase !== "find_spot") return;
            h1Value = calcHash(selectedFamily, 40);
            $('#hashResult').text(`Hausnummer: ${h1Value}`);
            let msg = `Hash: ${h1Value}. Klicke auf das Haus.`;
            if (selectedFamily === "Levi") {
                msg = `Hash: ${h1Value}. Siehst du? Das Haus ist jetzt FREI! Vorher wohnte da Thomas.`;
            }
            showDialogue(msg);
            $('.house').removeClass('highlight-target');
            $(`#house-${h1Value}`).addClass('highlight-target');
            phase = 'find_spot';
        });
        $('.house').click(function() {
            if (phase !== 'find_spot') return;
            let idx = $(this).data('index');
            if (idx !== h1Value) {
                playSound('error');
                showDialogue(`Falsches Haus. Ziel ist ${h1Value}.`);
                return;
            }
            if (city[idx] !== null) {
                playSound('click');
                h1Value = (h1Value + 1) % 40;
                showDialogue("Kollision? Nimm das nächste freie.");
                $('.house').removeClass('highlight-target');
                $(`#house-${h1Value}`).addClass('highlight-target');
                return;
            }
            playSound('click');
            city[idx] = selectedFamily;
            updateLoadFactor();
            let $house = $(this);
            setHouseAsset($house, true);
            $house.addClass('found');
            setTimeout(() => $house.removeClass('found').addClass('checked'), 300);
            $(`.list-group-item[data-family-index="${currentFamilyIdx}"]`).removeClass('active').addClass('done');
            $('.house').removeClass('highlight-target');
            $('#hashResult').text('Ergebnis ...');
            showDialogue(`Super! ${selectedFamily} ist eingezogen.`);
            $('#hashInput').val('');
            currentFamilyIdx++;
            $('#occupiedCount').text(currentFamilyIdx + ' / 23');
            updateLoadFactor();
            if (currentFamilyIdx < families.length) {
                phase = 'select_family';
                placeNextFamily();
            } else {
                endLevel();
            }
        });
        function endLevel() {
            playSound('success');
            $('#nameInput').val('');
            showDialogue("Fantastisch! Load Factor 0.55 (Okay). Rehashing hat funktioniert. Jetzt bist du bereit für das große Finale!", 'wink_major.png');
            $('.list-group-item').addClass('done');
            setTimeout(() => {
                $('#successMessage').text("Du hast gelernt, wie man eine überfüllte Hashmap rettet!");
                $('#successOverlay').css('display', 'flex');
            }, 3000);
        }
        window.restartLevel = function() { location.reload(); };
        window.nextLevel = function() {
            $('body').css('transition', 'opacity 0.5s ease');
            $('body').css('opacity', '0');
            setTimeout(() => window.location.href = 'level-select.php?completed=11&next=12', 500);
        };
        initCity();
        advanceDialogue();
    });
</script>
</body>
</html>
