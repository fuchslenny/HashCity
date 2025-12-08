<?php
/**
 * HashCity - Level 4: Anwendung Linear Probing
 *
 * Lernziel: Selbstständiges Anwenden von Linear Probing.
 * Suche 1: Sara (Existiert, Kette 1->4).
 * Suche 2: Tina (Existiert NICHT, Kette 6->8, kurz und knackig).
 */
$anzahl_haeuser = 15; // 0-14
// Familien
$familien_liste = [
        "Sophie", "Emil", "Grit", "Sara",
        "Dieter", "Marie", "Nele", "Claudia", "Nils", "Sammy"
];
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HashCity - Level 4: Linear Probing</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Rajdhani:wght@300;500;700&display=swap" rel="stylesheet">
    <style>
        /* --- Styles wie immer --- */
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
        .game-container { max-width: 1600px; margin: 2rem auto; padding: 0 2rem; position: relative; z-index: 1; }
        .game-area { display: grid; grid-template-columns: 280px 1fr 320px; gap: 2rem; min-height: 70vh; }
        .major-mike-section { background: rgba(255, 255, 255, 0.85); border-radius: 25px; padding: 1.5rem; box-shadow: 0 10px 40px rgba(0,0,0,0.15); height: fit-content; position: sticky; top: 100px; border: 4px solid #fff; }
        .major-mike-avatar { width: 100%; height: 240px; background: transparent; border-radius: 15px; display: flex; align-items: center; justify-content: center; margin-bottom: 1rem; overflow: hidden; position: relative; }
        .major-mike-avatar img { width: 100%; height: 100%; object-fit: contain; }
        .major-mike-name { font-family: 'Orbitron', sans-serif; font-size: 1.4rem; font-weight: 900; color: #667eea; text-align: center; margin-bottom: 1rem; text-shadow: 2px 2px 4px rgba(0,0,0,0.1); }
        .dialogue-box { background: #fff; border: 3px solid #667eea; border-radius: 20px; padding: 1.5rem; min-height: 180px; position: relative; box-shadow: 0 6px 20px rgba(102, 126, 234, 0.2); cursor: pointer; }
        .dialogue-box::before { content: ''; position: absolute; top: -15px; left: 50%; transform: translateX(-50%); width: 0; height: 0; border-left: 15px solid transparent; border-right: 15px solid transparent; border-bottom: 15px solid #667eea; }
        .dialogue-text { font-size: 1.05rem; line-height: 1.7; color: #333; font-weight: 500; }
        .dialogue-continue { position: absolute; bottom: 10px; right: 15px; font-size: 0.85rem; color: #667eea; font-style: italic; font-weight: 700; animation: blink 1.5s infinite; }
        @keyframes blink { 0%, 50%, 100% { opacity: 1; } 25%, 75% { opacity: 0.5; } }
        .houses-grid { background: rgba(255, 255, 255, 0.85); border-radius: 25px; padding: 2rem; box-shadow: 0 10px 40px rgba(0,0,0,0.15); border: 4px solid #fff; overflow: hidden; }
        .grid-title { font-family: 'Orbitron', sans-serif; font-size: 1.8rem; font-weight: 900; color: #2E7D32; text-align: center; margin-bottom: 2rem; text-shadow: 2px 2px 4px rgba(0,0,0,0.1); }
        .street-block { position: relative; margin-bottom: 2.5rem; }
        .street-block:last-child { margin-bottom: 0; }
        .houses-row { display: grid; grid-template-columns: repeat(5, 1fr); gap: 1rem; margin-bottom: 0.5rem; padding: 0 1rem; position: relative; z-index: 2; }
        .street { width: 100%; height: 60px; background-image: url('./assets/Strasse.svg'); background-size: cover; background-position: center; position: relative; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.15); z-index: 1; }
        .street::before { content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(180deg, #4a4a4a 0%, #2a2a2a 100%); border-radius: 8px; z-index: -1; }
        .street::after { content: ''; position: absolute; top: 50%; left: 0; width: 100%; height: 4px; background: repeating-linear-gradient(90deg, #fff 0px, #fff 30px, transparent 30px, transparent 50px); transform: translateY(-50%); z-index: 2; }
        .house { aspect-ratio: 1; background: transparent; border: none; display: flex; flex-direction: column; align-items: center; justify-content: center; cursor: pointer; transition: all 0.3s ease; position: relative; border-radius: 10px; padding: 0.3rem; }
        .house:hover:not(.checked):not(.found) { transform: translateY(-8px) scale(1.08); z-index: 10; }
        .house.highlight-target { transform: translateY(-10px) scale(1.15) !important; box-shadow: 0 0 35px 12px rgba(255, 215, 0, 0.9); z-index: 11; }
        .house-icon { width: 100%; height: 100%; object-fit: contain; transition: all 0.3s ease; filter: drop-shadow(0 4px 8px rgba(0,0,0,0.2)); }
        .house.checked .house-icon { filter: drop-shadow(0 4px 8px rgba(255, 167, 38, 0.5)); }
        .house.found .house-icon { animation: pulse 1.5s infinite; filter: drop-shadow(0 8px 16px rgba(76, 175, 80, 0.8)); }
        .house-number { position: absolute; top: 25%; left: 50%; transform: translateX(-50%); font-family: 'Orbitron', sans-serif; font-size: 1rem; font-weight: 900; color: white; text-shadow: 2px 2px 6px rgba(0,0,0,0.7); z-index: 10; background: rgba(0, 0, 0, 0.3); padding: 0.2rem 0.5rem; border-radius: 8px; }
        .house-family { position: absolute; bottom: 10%; left: 50%; transform: translateX(-50%); font-size: 0.7rem; color: white; font-weight: 700; text-align: center; opacity: 0; transition: opacity 0.3s ease; background: rgba(0, 0, 0, 0.7); padding: 0.3rem 0.6rem; border-radius: 8px; white-space: nowrap; text-shadow: 1px 1px 2px rgba(0,0,0,0.8); pointer-events: none; }
        /* INFO-PANEL (Stil von Level 3) */
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
        /* Familien-Liste (Stil von Level 3) */
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
        .info-value {
            font-family: 'Orbitron', sans-serif;
            font-size: 1.6rem;
            font-weight: 900;
            color: #2E7D32;
        }
        .success-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.85); display: none; align-items: center; justify-content: center; z-index: 2000; animation: fadeIn 0.3s ease; backdrop-filter: blur(5px); }
        .success-modal { background: white; border-radius: 30px; padding: 3rem; max-width: 650px; text-align: center; box-shadow: 0 20px 60px rgba(0,0,0,0.4); animation: slideUp 0.5s ease; border: 5px solid #4CAF50; }
        .success-icon { font-size: 5rem; margin-bottom: 1rem; animation: bounce 1s infinite; }
        .success-title { font-family: 'Orbitron', sans-serif; font-size: 2.8rem; font-weight: 900; color: #4CAF50; margin-bottom: 1rem; text-shadow: 2px 2px 4px rgba(0,0,0,0.1); }
        .success-message { font-size: 1.2rem; color: #666; line-height: 1.7; margin-bottom: 2rem; font-weight: 500; }
        .success-buttons { display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; }
        .btn-primary, .btn-secondary { padding: 1rem 2.5rem; border: none; border-radius: 30px; font-family: 'Orbitron', sans-serif; font-weight: 700; font-size: 1.05rem; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(0,0,0,0.2); }
        .btn-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4); }
        .btn-secondary { background: white; color: #667eea; border: 3px solid #667eea; }
        .btn-secondary:hover { background: #667eea; color: white; transform: translateY(-2px); }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes slideUp { from { transform: translateY(100px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        @keyframes bounce { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-10px); } }
        @keyframes pulse { 0%, 100% { transform: scale(1); } 50% { transform: scale(1.08); } }
        @media (max-width: 1200px) { .game-area { grid-template-columns: 1fr; } .major-mike-section, .info-panel { position: static; } }
        @media (max-width: 768px) { .houses-row { grid-template-columns: repeat(3, 1fr); } }
    </style>
</head>
<body>
<div class="sky-section">
    <div class="cloud" style="width: 120px; height: 60px; top: 8%; animation-delay: 0s;"></div>
    <div class="cloud" style="width: 150px; height: 70px; top: 18%; animation-delay: 10s;"></div>
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
                <div class="dialogue-text" id="dialogueText">
                    Das läuft ja schon sehr gut. Du darfst jetzt diesen neuen Stadtteil allein bearbeiten. Verwende dafür linear probing, falls es zu Kollisionen kommt.
                </div>
                <div class="dialogue-continue" id="dialogueContinue">
                    Klicken oder Enter ↵
                </div>
            </div>
        </div>
        <div class="houses-grid">
            <h2 class="grid-title">🏘️ Level 4: Linear Probing 2</h2>
            <div class="street-block">
                <div class="houses-row">
                    <?php for ($i = 0; $i < 5; $i++): ?>
                        <div class="house" data-house="<?php echo $i; ?>" data-family="">
                            <img src="./assets/empty_house.svg" alt="Haus <?php echo $i; ?>" class="house-icon">
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
                            <img src="./assets/empty_house.svg" alt="Haus <?php echo $i; ?>" class="house-icon">
                            <div class="house-number"><?php echo $i; ?></div>
                            <div class="house-family"></div>
                        </div>
                    <?php endfor; ?>
                </div>
                <div class="street"></div>
            </div>
            <div class="street-block">
                <div class="houses-row">
                    <?php for ($i = 10; $i < 15; $i++): ?>
                        <div class="house" data-house="<?php echo $i; ?>" data-family="">
                            <img src="./assets/empty_house.svg" alt="Haus <?php echo $i; ?>" class="house-icon">
                            <div class="house-number"><?php echo $i; ?></div>
                            <div class="house-family"></div>
                        </div>
                    <?php endfor; ?>
                </div>
                <div class="street"></div>
            </div>
        </div>
        <div class="info-panel">
            <h3 class="info-title">📊 Stadtplanung</h3>
            <div class="info-item hash-calculator">
                <label for="nameInput" class="info-label" style="color: #666; font-size: 0.95rem;">Bewohnername:</label>
                <input type="text" id="nameInput" class="calculator-input" placeholder="Namen eingeben..." readonly>
                <button id="hashButton" class="calculator-button">Berechne Haus-Nr.</button>
                <div class="calculator-result" id="hashResult">
                    Ergebnis ...
                </div>
            </div>
            <div class="info-item">
                <div class="info-label">Einziehende Familien:</div>
                <div class="family-list-container">
                    <ul id="familienListe" class="list-group">
                        <?php foreach ($familien_liste as $index => $familie): ?>
                            <li class="list-group-item to-do-family" data-family-index="<?php echo $index; ?>">
                                <?php echo $familie; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <div class="info-item">
                <div class="info-label">Eingetragene Familien:</div>
                <div class="info-value" id="occupiedCount">0 / 10</div>
            </div>
        </div>
    </div>
</div>
<div class="success-overlay" id="successOverlay">
    <div class="success-modal">
        <div class="success-icon">🎉</div>
        <h2 class="success-title">Geschafft!</h2>
        <p class="success-message" id="successMessage">Danke für deine Hilfe!</p>
        <div class="success-buttons">
            <button class="btn-secondary" onclick="restartLevel()">↻ Nochmal</button>
            <button class="btn-primary" onclick="nextLevel()">Weiter zu Level 5 →</button>
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
        // --- Level 4 Setup ---
        const HASH_SIZE = <?php echo $anzahl_haeuser; ?>;
        const familien = <?php echo json_encode($familien_liste); ?>;
        let stadt = new Array(HASH_SIZE).fill(null);
        let gameStarted = false;
        let isFading = false;
        let gamePhase = "placement_calculate";
        let currentFamilyIndex = 0;
        let selectedFamily = null;
        let correctTargetHouse = null;
        let initialHash = null;
        const families = ["Sophie", "Emil", "Grit", "Sara", "Dieter", "Marie", "Nele", "Claudia", "Nils", "Sammy"];
        // Ziel 1: SARA (Existiert)
        const SEARCH_TARGET_1 = "Sara";
        let search1InitialHash = null;
        let search1CorrectHouse = null;
        // Ziel 2: TINA (Existiert NICHT)
        const SEARCH_TARGET_2 = "Tina";
        let search2InitialHash = null;

        function initFamilyListUI() {
            $('.to-do-family').addClass('disabled').css('opacity', '0.5').off('click');
        }
        initFamilyListUI();
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
            const isFilled = $house.hasClass('checked');
            const pair = getRandomHousePair();
            const asset = isFilled ? pair.filled : pair.empty;
            $house.find('.house-icon').attr('src', `./assets/${asset}`);
            $house.data('empty-asset', pair.empty);
            $house.data('filled-asset', pair.filled);
        });
        // --- Hash-Funktion ---
        function getHash(key, size) {
            let sum = 0;
            for (let i = 0; i < key.length; i++) { sum += key.charCodeAt(i); }
            return (sum % size);
        }
        // --- Helper: Berechnet das finale Haus (Placement) ---
        function calculateFinalIndex(startHash) {
            let finalIndex = startHash;
            let probeCount = 0;
            while (stadt[finalIndex] !== null) {
                finalIndex = (finalIndex + 1) % HASH_SIZE;
                probeCount++;
                if (probeCount > HASH_SIZE) return -1;
            }
            return finalIndex;
        }
        // --- Helper: Findet Haus (Search) ---
        function findFamilyByProbing(startHash, familyName) {
            let finalIndex = startHash;
            let probeCount = 0;
            while (probeCount < HASH_SIZE) {
                if (stadt[finalIndex] === familyName) return finalIndex;
                if (stadt[finalIndex] === null) return -1;
                finalIndex = (finalIndex + 1) % HASH_SIZE;
                probeCount++;
            }
            return -1;
        }
        // --- Dialoge ---
        const dialogues = [
            "Das läuft ja schon sehr gut. Du darfst jetzt diesen neuen Stadtteil allein bearbeiten. Verwende dafür linear probing, falls es zu Kollisionen kommt. Hier ist eine Liste der Bewohner. Beachte dabei, dass du diese von oben nach unten abarbeitest."
        ];
        let currentDialogue = 0;
        function showNextDialogue() {
            if (isFading || gameStarted) return;
            isFading = true;
            $('#dialogueText').fadeOut(150, function() {
                $(this).text(dialogues[currentDialogue]).fadeIn(150, function() {
                    isFading = false;
                });
                $('#majorMikeImage').attr('src', './assets/card_major.png');
                if (currentDialogue === dialogues.length - 1) {
                    $('#dialogueContinue').fadeOut();
                    gameStarted = true;
                    selectNextFamily();
                }
                currentDialogue++;
            });
        }
        // --- UI Update-Funktionen ---
        function placeFamily($house, houseNumber, family) {
            stadt[houseNumber] = family;
            setHouseAsset($house, true);
            $house.addClass('checked');
            $house.removeClass('highlight-target');
            $house.find('.house-family').text(family);
            $house.attr('data-family', family);
            $(`.to-do-family[data-family-index=${currentFamilyIndex}]`).removeClass('active').addClass('list-group-item-success').css('opacity', '1');
        }
        // --- Event-Listener ---
        $('#dialogueBox').click(function() { if (!gameStarted) showNextDialogue(); });
        $(document).keydown(function(e) {
            if ((e.key === 'Enter' || e.key === ' ') && !gameStarted) showNextDialogue();
        });
        // --- Spiellogik ---
        function selectNextFamily() {
            if (currentFamilyIndex >= familien.length) {
                startSearchPhase1();
                return;
            }
            gamePhase = "placement_calculate";
            selectedFamily = familien[currentFamilyIndex];
            initialHash = null;
            correctTargetHouse = null;
            $('.to-do-family').removeClass('active');
            $(`.to-do-family[data-family-index=${currentFamilyIndex}]`).removeClass('disabled').css('opacity', '1').addClass('active');
            $('#hashButton').prop('disabled', false);
            $('#hashResult').text('Ergebnis ...');
            $('#nameInput').val(selectedFamily);
            $('.house').removeClass('highlight-target');
            $('#dialogueText').text(`Platziere jetzt: ${selectedFamily}. Klicke 'Berechnen'.`);
        }
        // --- Button Klick ---
        $('#hashButton').click(function() {
            if (!selectedFamily) return;
            const name = $('#nameInput').val().trim();
            if (name==='') return;
            if (name !== selectedFamily) {
                if ('search_sara_calc' || 'search_tina_calc'){
                    $('#dialogueText').text(`Derzeit wird nicht nach ${name} gesucht, sondern nach ${selectedFamily}`);
                }else {
                    $('#dialogueText').text("Der Name im Rechner passt nicht zur ausgewählten Familie.");
                }
                return;
            }
            initialHash = getHash(selectedFamily, HASH_SIZE);
            $('#hashResult').text(`Hausnummer: ${initialHash}`);
            $(this).prop('disabled', true);
            if (gamePhase === "placement_calculate") {
                correctTargetHouse = calculateFinalIndex(initialHash);
                $('#dialogueText').text(`Hausnummer: ${initialHash}. Klicke auf das entsprechende Haus.`);
                $(`.house[data-house=${initialHash}]`).addClass('highlight-target');
                gamePhase = "placement_find_spot";
            }
            else if (gamePhase === "search_sara_calc") {
                search1InitialHash = initialHash;
                search1CorrectHouse = findFamilyByProbing(search1InitialHash, selectedFamily);
                $('#dialogueText').text(`Okay, Start bei Haus ${initialHash}. Such sie!`);
                $(`.house[data-house=${initialHash}]`).addClass('highlight-target');
                gamePhase = "search_sara_find";
            }
            else if (gamePhase === "search_tina_calc") {
                search2InitialHash = initialHash;
                $('#dialogueText').text(`Start bei Haus ${initialHash}. Prüfe, ob Tina dort wohnt. Wenn nicht, geh weiter bis zum ersten LEEREN Haus.`);
                $(`.house[data-house=${initialHash}]`).addClass('highlight-target');
                gamePhase = "search_tina_find";
            }
        });
        // --- Haus Klick ---
        $('.house').click(function() {
            if (!gameStarted) return;
            const $house = $(this);
            const houseNumber = $house.data('house');
            if (gamePhase === "placement_find_spot") {
                if (houseNumber === correctTargetHouse) {
                    placeFamily($house, houseNumber, selectedFamily);
                    currentFamilyIndex++;
                    $('#occupiedCount').text(currentFamilyIndex + ' / 10');
                    selectNextFamily();
                } else if (stadt[houseNumber] !== null) {
                    $('#dialogueText').text("Halt! Belegt. Nutze Linear Probing (nächstes freies Haus).");
                    $house.addClass('checked');
                    $house.removeClass('highlight-target');
                } else {
                    $('#dialogueText').text("Falsches Haus. Linear Probing beachten!");
                }
            }
            else if (gamePhase === "search_sara_find") {
                const clickedFamily = $house.data('family');
                if(clickedFamily) $house.find('.house-family').text(clickedFamily).css('opacity', 1);
                if (houseNumber === search1CorrectHouse) {
                    $('#dialogueText').text("Gefunden! Danke.");
                    $house.addClass('found');
                    $('.house').removeClass('highlight-target');
                    setTimeout(startSearchPhase2, 2000);
                } else if (houseNumber >= search1InitialHash && houseNumber < search1CorrectHouse) {
                    $('#dialogueText').text(`Das ist ${clickedFamily}. Weiter suchen (Linear Probing)!`);
                    $house.removeClass('highlight-target');
                    $(`.house[data-house=${houseNumber + 1}]`).addClass('highlight-target');
                } else {
                    $('#dialogueText').text(`Das ist ${clickedFamily}. Falsches Haus.`);
                }
            }
            else if (gamePhase === "search_tina_find") {
                const clickedFamily = $house.data('family');
                if(clickedFamily) $house.find('.house-family').text(clickedFamily).css('opacity', 1);
                if (stadt[houseNumber] !== null) {
                    $('#dialogueText').text(`Das ist ${clickedFamily}. Nicht Tina. Haus ist voll -> Weiter suchen!`);
                    $house.removeClass('highlight-target');
                    let next = (houseNumber + 1) % HASH_SIZE;
                    $(`.house[data-house=${next}]`).addClass('highlight-target');
                }
                else {
                    $house.addClass('found');
                    $('#dialogueText').html("<b>STOP!</b> Hier wohnt niemand. Da die Kette hier abreißt, wissen wir sicher: <b>Tina wohnt nicht in der Stadt!</b>");
                    $('#majorMikeImage').attr('src', './assets/wink_major.png');
                    setTimeout(showSuccessModal, 4000);
                }
            }
        });
        // --- Start Phase 2 (Sara) ---
        function startSearchPhase1() {
            gamePhase = "search_sara_calc";

            selectedFamily = SEARCH_TARGET_1;
            $('#hashButton').prop('disabled', false);
            $('#hashResult').text('Ergebnis ...');
            $('#nameInput').prop('readonly', false);
            $('#nameInput').val('');
            $('.house').removeClass('highlight-target');
            $('#dialogueText').text("Sehr gut! Alle Bewohner sind da. Kannst du mir sagen, wo Sara wohnt? Berechne ihren Hash.");
            $('#majorMikeImage').attr('src', './assets/wink_major.png');
        }
        // --- Start Phase 3 (Tina) ---
        function startSearchPhase2() {
            gamePhase = "search_tina_calc";
            selectedFamily = SEARCH_TARGET_2;
            $('#hashButton').prop('disabled', false);
            $('#hashResult').text('Ergebnis ...');
            $('.house').removeClass('highlight-target');
            $('.house').removeClass('found');
            $('#dialogueText').text("Eine Frage noch: Wohnt eigentlich 'Tina' hier? Berechne ihren Hash und prüf das mal.");
            $('#majorMikeImage').attr('src', './assets/card_major.png');
        }
        // --- Success Modal ---
        function showSuccessModal() {
            $('#successMessage').text("Klasse! Du hast verstanden, wie man in einer Hashmap sucht (und auch, wie man sieht, dass etwas fehlt).");
            $('#successOverlay').css('display', 'flex');
        }
        // --- Globale Funktionen ---
        window.restartLevel = function() { location.reload(); };
        window.nextLevel = function() {
            $('body').css('transition', 'opacity 0.5s ease');
            $('body').css('opacity', '0');
            setTimeout(function() {
                window.location.href = 'Level-Auswahl?completed=4&next=5';
            }, 500);
        };
    });
</script>
</body>
</html>
