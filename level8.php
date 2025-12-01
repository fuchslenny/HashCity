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
        /* --- Basis Styles --- */
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
        .house.found .house-icon { animation: pulse 1.5s infinite; filter: drop-shadow(0 8px 16px rgba(76, 175, 80, 0.8)); }
        .house-number { position: absolute; top: 25%; left: 50%; transform: translateX(-50%); font-family: 'Orbitron', sans-serif; font-size: 0.9rem; font-weight: 900; color: white; text-shadow: 2px 2px 6px rgba(0,0,0,0.7); z-index: 10; background: rgba(0, 0, 0, 0.3); padding: 0.1rem 0.4rem; border-radius: 6px; }
        .house-family { position: absolute; bottom: 10%; left: 50%; transform: translateX(-50%); font-size: 0.7rem; color: white; font-weight: 700; text-align: center; opacity: 0; transition: opacity 0.3s ease; background: rgba(0, 0, 0, 0.7); padding: 0.3rem 0.6rem; border-radius: 8px; white-space: nowrap; text-shadow: 1px 1px 2px rgba(0,0,0,0.8); pointer-events: none; }
        .info-panel { background: rgba(255, 255, 255, 0.85); border-radius: 25px; padding: 1.5rem; box-shadow: 0 10px 40px rgba(0,0,0,0.15); height: fit-content; position: sticky; top: 100px; border: 4px solid #fff; }
        .info-title { font-family: 'Orbitron', sans-serif; font-size: 1.4rem; font-weight: 700; color: #2E7D32; margin-bottom: 1.2rem; text-align: center; text-shadow: 2px 2px 4px rgba(0,0,0,0.1); }
        .info-item { background: #fff; padding: 1rem; border-radius: 15px; margin-bottom: 1rem; border: 3px solid #4CAF50; box-shadow: 0 4px 15px rgba(76, 175, 80, 0.15); }
        .info-label { font-weight: 700; color: #666; font-size: 0.95rem; margin-bottom: 0.4rem; }
        .hash-calculator { background: linear-gradient(135deg, #e3f2fd 0%, #fff 100%); border-color: #2196F3; }
        .step-calculator { background: linear-gradient(135deg, #fff3e0 0%, #fff 100%); border-color: #FF9800; opacity: 0.5; pointer-events: none; transition: opacity 0.3s; }
        .step-calculator.active { opacity: 1; pointer-events: all; }
        .hash-result-value { font-family: 'Orbitron', sans-serif; font-size: 2.2rem; font-weight: 900; color: #667eea; text-align: center; margin: 0.5rem 0; }
        .calc-button { padding: 0.6rem 1.5rem; border: none; border-radius: 30px; font-family: 'Orbitron', sans-serif; font-weight: 700; font-size: 0.9rem; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(0,0,0,0.1); color: white; width: 100%; margin-top: 0.5rem; }
        .btn-primary-calc { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .btn-secondary-calc { background: linear-gradient(135deg, #FF9800 0%, #FFB74D 100%); }
        .calc-button:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4); }
        .calc-button:disabled { background: #ccc; cursor: not-allowed; transform: none; box-shadow: none; }
        .family-list-container { max-height: 200px; overflow-y: auto; overflow-x: hidden; }
        .list-group-item { padding: 0.7rem 1rem; margin-bottom: 0.5rem; background: #f8f9fa; border-radius: 8px; cursor: pointer; font-weight: 700; color: #666; transition: all 0.2s; border: none; font-size: 1rem; display: block; }
        .list-group-item.active { background: #667eea; color: #fff; transform: scale(1.02); z-index: 10; }
        .list-group-item.done { background: #e9f5e9; color: #999; text-decoration: line-through; cursor: default; }
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
        @media (max-width: 1200px) { .game-area { grid-template-columns: 1fr; gap: 1.5rem; } .major-mike-section, .info-panel { position: static; } }
        @media (max-width: 768px) { .houses-row { grid-template-columns: repeat(3, 1fr); } }
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
            <h2 class="grid-title">🏘️ Double Hashing District (Praxis)</h2>
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
            <div class="info-item hash-calculator">
                <div class="info-label">1. Hash (Startposition)</div>
                <div style="font-size: 0.8rem; color: #666; margin-bottom: 5px; font-weight: 600;">(ASCII Summe) % 20</div>
                <div class="hash-result-value" id="h1Result">-</div>
                <button id="btnCalcH1" class="calc-button btn-primary-calc" disabled>Berechnen</button>
            </div>
            <div class="info-item step-calculator" id="stepCalcBox">
                <div class="info-label">2. Hash (Schrittweite)</div>
                <div style="font-size: 0.8rem; color: #666; margin-bottom: 5px; font-weight: 600;">(ASCII Summe) % 10 + 1</div>
                <div class="hash-result-value" id="h2Result">-</div>
                <button id="btnCalcH2" class="calc-button btn-secondary-calc" disabled>Sprungweite berechnen</button>
            </div>
            <div class="info-item">
                <div class="info-label">Einziehende Familien:</div>
                <div class="family-list-container">
                    <ul id="familienListe" class="list-group" style="padding-left: 0; list-style: none;">
                        <?php foreach ($familien_liste as $idx => $familie): ?>
                            <li class="list-group-item" data-index="<?php echo $idx; ?>">
                                <?php echo $familie; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
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
        // WICHTIG: Suchziel ist jetzt PAUL
        const SEARCH_TARGET = "Paul";
        let searchH1 = null;
        let searchH2 = null;
        let isFading = false;
        const dialogues = [
            "Das sieht ja schon richtig gut aus! Du darfst jetzt diesen neuen Stadtteil allein bearbeiten.",
            "Verwende dafür Double Hashing, falls es zu Kollisionen kommt. Beachte dabei, dass du die Liste von oben nach unten abarbeitest.",
            "Denk dran: Hinten rechnen wir immer **+1**, damit die Schrittweite nie 0 ist. Viel Erfolg!"
        ];
        let dialogueIdx = 0;
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
            if (dialogueIdx < dialogues.length) {
                showDialogue(dialogues[dialogueIdx]);
                dialogueIdx++;
            } else {
                if (phase === 'intro') {
                    $('#dialogueContinue').fadeOut();
                    phase = 'select_family';
                    highlightNextFamily();
                    showDialogue("Klicke auf " + families[currentFamilyIdx] + " in der Liste, um zu starten.", 'wink_major.png');
                }
            }
        }
        $('#dialogueBox').click(function() {
            if (phase === 'intro') advanceDialogue();
        });
        $(document).keydown(function(e) {
            if(e.key === 'Enter' || e.key === ' ') {
                if (phase === 'intro') advanceDialogue();
            }
        });
        // --- Game Logic ---
        function highlightNextFamily() {
            $('.list-group-item').removeClass('active');
            $(`.list-group-item[data-index="${currentFamilyIdx}"]`).addClass('active');
        }
        // 1. Liste Klicken
        $(document).on('click', '.list-group-item', function() {
            if ((phase === 'collision_mode') || (phase === 'probing_step')) return;
            let idx = $(this).data('index');
            let text = $(this).text().trim();
            if (phase === 'select_family') {
                if (idx !== currentFamilyIdx) {
                    showDialogue("Bitte arbeite die Liste von oben nach unten ab.");
                    return;
                }
                selectedFamily = families[idx];
                phase = 'calc_h1';
                showDialogue(`Platziere jetzt: ${selectedFamily}. Berechne den 1. Hash.`);
            }
            else if (phase === 'search_intro' || phase === 'search_calc') {
                selectedFamily = text;
                if (selectedFamily !== 'Paul'){
                    showDialogue(`Das ist nicht Paul, sondern ${selectedFamily}. Klicke bitte auf Paul.`);
                }else{
                    showDialogue(`Okay, wir suchen ${selectedFamily}. Berechne seinen 1. Hash.`);
                }
                phase = 'search_calc';
                $('.list-group-item').removeClass('active');
                $(this).addClass('active');
            }
            $('#btnCalcH1').prop('disabled', false);
            $('#btnCalcH2').prop('disabled', true);
            $('#h1Result').text('-');
            $('#h2Result').text('-');
            $('#stepCalcBox').removeClass('active');
        });
        // 2. H1 Berechnen
        $('#btnCalcH1').click(function() {
            if (isFading) return;
            if (phase !== 'calc_h1' && phase !== 'search_calc') return;
            let name = selectedFamily;
            let val = calcH1(name);
            if (phase === 'search_calc') {
                searchH1 = val;
                $('#h1Result').text(val);
                if(name !== `Paul`){
                    showDialogue(`${val} ist der Hash von ${name}. Den Suche ich aber nicht, berechne stattdessen den Wert von Paul!`);
                    return;
                }else{
                    showDialogue(`Initial-Hash: ${val}. Klicke auf Haus ${val}.`);
                }
                phase = 'search_find';
                $(this).prop('disabled', true);
                return;
            }
            h1Value = val;
            $('#h1Result').text(val);
            showDialogue(`Hash 1 ist ${val}. Klicke auf das entsprechende Haus.`);
            phase = 'find_spot';
            $(this).prop('disabled', true);
        });
        // 3. Haus Klicken
        $('.house').click(function() {
            if (isFading) return;
            let houseIdx = $(this).data('index');
            let $house = $(this);
            // --- Search Logic ---
            if (phase === 'search_find') {
                handleSearchClick(houseIdx, $house);
                return;
            }
            // --- Placement Logic ---
            if (phase === 'find_spot') {
                if (houseIdx !== h1Value) {
                    showDialogue("Das war das falsche Haus. (Rechner beachten!)");
                    return;
                }
                if (city[houseIdx] === null) {
                    placeFamily(houseIdx);
                } else {
                    handleCollision(houseIdx);
                }
            }
            else if (phase === 'collision_mode') {
                showDialogue("Berechne erst die Sprungweite (2. Hash)!");
            }
            else if (phase === 'probing_step') {
                let expectedIdx = (currentProbeIndex + h2Value) % HASH_SIZE;
                if (houseIdx !== expectedIdx) {
                    showDialogue(`Falsch! ${currentProbeIndex} + ${h2Value} (modulo 20) = ${expectedIdx}.`);
                    return;
                }
                if (city[houseIdx] === null) {
                    placeFamily(houseIdx);
                } else {
                    currentProbeIndex = houseIdx;
                    showDialogue(`Haus ${houseIdx} ist auch voll! Springe nochmal weiter (+${h2Value}).`);
                }
            }
        });
        function placeFamily(idx) {
            city[idx] = selectedFamily;
            let $house = $(`#house-${idx}`);
            setHouseAsset($house, true);
            $house.addClass('found');
            setTimeout(() => $house.removeClass('found').addClass('checked'), 500);
            $(`.list-group-item[data-index="${currentFamilyIdx}"]`).removeClass('active').addClass('done');
            $('#h1Result').text('-');
            $('#h2Result').text('-');
            $('#stepCalcBox').removeClass('active');
            // LOAD FACTOR Hinweis (10. Person)
            let msg = `Sehr gut! ${selectedFamily} wohnt jetzt in Haus ${idx}.`;
            if (currentFamilyIdx === 9) {
                msg += " Puh, es wird voll! Das nennt man einen hohen **Load Factor**. Da kracht es oft!";
            }
            showDialogue(msg);
            currentFamilyIdx++;
            if (currentFamilyIdx < families.length) {
                phase = 'select_family';
                highlightNextFamily();
                setTimeout(() => {
                    if(!isFading) showDialogue("Wähle den nächsten Bewohner aus der Liste.");
                }, 1500);
            } else {
                startSearchPhase();
            }
        }
        function handleCollision(idx) {
            showDialogue(`Haus ${idx} ist belegt! Nutze Double Hashing (2. Hash).`);
            currentProbeIndex = idx;
            $('#stepCalcBox').addClass('active');
            $('#btnCalcH2').prop('disabled', false);
            phase = 'collision_mode';
            let $house = $(`#house-${idx}`);
            $house.addClass('found');
            setTimeout(() => $house.removeClass('found'), 300);
        }
        // 4. H2 Berechnen
        $('#btnCalcH2').click(function() {
            if (isFading) return;
            let name = selectedFamily;
            let step = calcH2(name);
            // Search Phase H2
            if (phase === 'search_find') {
                searchH2 = step;
                $('#h2Result').text(step);
                let next = (searchH1 + step) % HASH_SIZE;
                showDialogue(`Schrittweite: ${step}. Rechne: ${searchH1} + ${step} = ${next}. Klicke darauf.`);
                $(this).prop('disabled', true);
                return;
            }
            if (phase !== 'collision_mode') return;
            h2Value = step;
            $('#h2Result').text(step);
            $(this).prop('disabled', true);
            let nextHouse = (currentProbeIndex + step) % HASH_SIZE;
            showDialogue(`Schrittweite: ${step}. Rechne: ${currentProbeIndex} + ${step} = ?. Klicke auf das Haus.`);
            phase = 'probing_step';
        });
        // --- Search Phase ---
        function startSearchPhase() {
            phase = 'search_intro';
            showDialogue("Sehr gut! Alle Bewohner sind im richtigen Haus.", 'wink_major.png');
            $('.list-group-item').removeClass('done').css('cursor', 'pointer').addClass('list-group-item');
            $('#btnCalcH1').prop('disabled', true);
            $('#btnCalcH2').prop('disabled', true);
            setTimeout(() => {
                showDialogue("Danke für deine Hilfe! ... Warte, wo wohnt Paul? Klicke auf ihn in der Liste.");
            }, 3000);
        }
        function handleSearchClick(houseIdx, $house) {
            // Namen anzeigen
            let occupant = city[houseIdx];
            if (occupant) {
                $house.find('.house-family').text(occupant).css('opacity', 1);
            }
            // 1. Versuch (nur H1)
            if (!searchH2) {
                if (houseIdx !== searchH1) {
                    showDialogue(`Das ist nicht Haus ${searchH1}.`);
                    return;
                }
                if (occupant === SEARCH_TARGET) {
                    endLevel();
                } else {
                    // Kollision! -> User muss H2 nutzen
                    showDialogue(`Das ist ${occupant}. Falsch! Berechne den 2. Hash (Sprungweite).`, 'sad_major.png');
                    $('#stepCalcBox').addClass('active');
                    $('#btnCalcH2').prop('disabled', false);
                }
            }
            // 2. Versuch (mit H2 / Step)
            else {
                let expected = (searchH1 + searchH2) % HASH_SIZE;
                if (houseIdx !== expected) {
                    showDialogue(`Falsch. ${searchH1} + ${searchH2} = ${expected}.`);
                    return;
                }
                if (occupant === SEARCH_TARGET) {
                    $house.addClass('found');
                    // Finaler Dialog
                    showDialogue("Super! Paul gefunden. Double Hashing erzeugt längere Ketten, aber verteilt besser!", 'wink_major.png');
                    setTimeout(endLevel, 3000);
                } else {
                    searchH1 = houseIdx;
                    showDialogue(`Das ist ${occupant}. Weiter springen (+${searchH2})!`);
                }
            }
        }
        function endLevel() {
            $('#successMessage').text("Danke für deine Hilfe!");
            $('#successOverlay').css('display', 'flex');
        }
        // Global Functions
        window.restartLevel = function() { location.reload(); };
        window.nextLevel = function() {
            $('body').css('transition', 'opacity 0.5s ease');
            $('body').css('opacity', '0');
            setTimeout(function() {
                window.location.href = 'Level-Auswahl?completed=8&next=9';
            }, 500);
        };
        // Start
        advanceDialogue();
    });
</script>
</body>
</html>
