<?php
/**
 * HashCity - Level 9: Mehrfamilienhäuser (Separate Chaining)
 * UPDATE: Suchphase erfordert "Durchklicken" (Traversieren) der Liste bis zum Ziel.
 */
$anzahl_haeuser = 10;
// Die Bewohner laut Text-Vorgabe
$bewohner_liste = [
        "Franz",    // Hash 3
        "Heinrich", // Hash 0
        "Nora",     // Hash 0 (Kollision)
        "Thomas",   // Hash 0 (Kollision)
        "Markus",   // Hash 7
        "Emma",     // Hash 4
        "Johannes", // Hash 2
        "Katrin",   // Hash 7 (Kollision)
        "Peter",    // Hash 2 (Kollision)
        "Nina",     // Hash 0 (Kollision)
        "Julia"     // Hash 1
];
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HashCity - Level 9: Mehrfamilienhäuser</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Rajdhani:wght@300;500;700&display=swap" rel="stylesheet">
    <style>
        /* --- BASIS STYLES --- */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Rajdhani', sans-serif;
            overflow-x: hidden;
            min-height: 100vh;
            background: #4CAF50;
        }
        /* Hintergrund */
        .sky-section {
            position: fixed; top: 0; left: 0; width: 100%; height: 50%;
            background: linear-gradient(180deg, #87CEEB 0%, #B0D4E3 100%); z-index: 0;
        }
        .grass-section {
            position: fixed; bottom: 0; left: 0; width: 100%; height: 50%;
            background: linear-gradient(180deg, #76B947 0%, #4CAF50 100%); z-index: 0;
        }
        .cloud {
            position: absolute; background: rgba(255, 255, 255, 0.7);
            border-radius: 100px; opacity: 0.8; animation: cloudFloat 60s linear infinite;
        }
        @keyframes cloudFloat { 0% { left: -200px; } 100% { left: 110%; } }
        /* Header */
        .game-header {
            padding: 1rem 2rem; position: relative; z-index: 1000; backdrop-filter: blur(10px);
        }
        .back-btn {
            padding: 0.7rem 1.3rem; background: rgba(255, 255, 255, 0.9);
            border: 2px solid rgba(102, 126, 234, 0.5); border-radius: 30px;
            font-weight: 700; color: #667eea; text-decoration: none;
            font-family: 'Orbitron', sans-serif;
        }
        /* Game Area Layout */
        .game-container { max-width: 1600px; margin: 1rem auto; padding: 0 2rem; position: relative; z-index: 1; }
        .game-area { display: grid; grid-template-columns: 300px 1fr 300px; gap: 2rem; min-height: 80vh; }
        /* Major Mike */
        .major-mike-section {
            background: rgba(255, 255, 255, 0.9); border-radius: 25px; padding: 1.5rem;
            position: sticky; top: 80px; border: 4px solid #fff; height: fit-content;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        .major-mike-avatar { width: 100%; height: 220px; display: flex; justify-content: center; margin-bottom: 10px;}
        .major-mike-avatar img { width: 100%; height: 100%; object-fit: contain; }
        .dialogue-box {
            background: #fff; border: 3px solid #667eea; border-radius: 20px; padding: 1.2rem;
            min-height: 160px; position: relative; cursor: pointer; transition: transform 0.2s;
        }
        .dialogue-box:hover { transform: scale(1.02); }
        .dialogue-text { font-size: 1rem; line-height: 1.5; color: #333; }
        .dialogue-continue {
            position: absolute; bottom: 8px; right: 15px; font-size: 0.8rem;
            color: #667eea; font-weight: 700; animation: blink 1.5s infinite;
        }
        @keyframes blink { 0%, 50%, 100% { opacity: 1; } 25%, 75% { opacity: 0.5; } }
        /* --- HAUS DESIGN --- */
        .houses-grid {
            background: rgba(255, 255, 255, 0.8); border-radius: 25px; padding: 3rem;
            border: 4px solid #fff; display: flex; flex-direction: column; justify-content: center;
        }
        .street-block { margin-bottom: 6rem; position: relative; }
        .houses-row {
            display: grid; grid-template-columns: repeat(5, 1fr); gap: 1rem;
            padding: 0 1rem 15px 1rem; position: relative; z-index: 2;
            align-items: end;
            min-height: 250px;
        }
        .street {
            width: 100%; height: 50px; background: #4a4a4a;
            border-radius: 8px; position: relative; z-index: 1; margin-top: -15px;
        }
        .street::after {
            content: ''; position: absolute; top: 50%; left: 0; width: 100%; height: 4px;
            background: repeating-linear-gradient(90deg, #fff 0px, #fff 30px, transparent 30px, transparent 50px);
            transform: translateY(-50%);
        }
        .house-container {
            position: relative;
            display: flex;
            flex-direction: column-reverse;
            align-items: center;
            cursor: pointer;
            transition: transform 0.2s;
        }
        .house-container:hover { transform: scale(1.05); z-index: 100; }
        /* --- STACKING LOGIK --- */
        .img-house-base {
            width: 90px; height: auto; z-index: 1;
            display: block; position: relative;
            filter: drop-shadow(0 4px 6px rgba(0,0,0,0.3));
        }
        .img-house-extension {
            width: 90px; height: auto; z-index: 10;
            display: block; position: relative;
            margin-bottom: -5px; /* Abstand */
            animation: fallDown 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        @keyframes fallDown {
            from { opacity: 0; transform: translateY(-50px); }
            to { opacity: 1; transform: translateY(0); }
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
            z-index: 100;
            background: rgba(0, 0, 0, 0.3);
            padding: 0.2rem 0.5rem;
            border-radius: 8px;
        }
        /* --- NAMENS-LOGIK --- */
        .name-badge-container {
            position: absolute;
            bottom: 10px;
            width: 100%;
            display: flex;
            flex-direction: column-reverse;
            align-items: center;
            gap: 8px;
            z-index: 200;
            pointer-events: none;
        }
        .resident-name {
            background: rgba(255, 255, 255, 0.95);
            color: #333;
            padding: 2px 8px;
            border-radius: 6px;
            font-size: 0.7rem;
            font-weight: bold;
            border: 2px solid #667eea;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            max-width: 120px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            transform: translateY(-25px);
            display: none;
            opacity: 0;
            transition: opacity 0.3s ease-out;
        }
        .resident-name.revealed {
            display: block;
            opacity: 1;
            animation: popIn 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        @keyframes popIn {
            0% { transform: scale(0) translateY(-25px); }
            100% { transform: scale(1) translateY(-25px); }
        }
        .resident-name.found {
            background: #4CAF50; color: white; border-color: #fff; transform: scale(1.3) translateY(-25px); z-index: 100;
        }
        /* Controls Right */
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
        /* Success Modal */
        .success-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.85);
            display: flex;
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
        .grid-title {
            font-family: 'Orbitron', sans-serif;
            font-size: 1.8rem;
            font-weight: 900;
            color: #2E7D32;
            text-align: center;
            margin-bottom: 2rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        }
        @media (max-width: 1200px) { .game-area { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
<div class="sky-section">
    <div class="cloud" style="top: 10%; width: 140px;"></div>
    <div class="cloud" style="top: 25%; width: 180px; left: 40%;"></div>
</div>
<div class="grass-section"></div>
<div class="game-header">
    <a href="level-select.php" class="back-btn">← Zurück</a>
</div>
<div class="game-container">
    <div class="game-area">
        <div class="major-mike-section">
            <div class="major-mike-avatar">
                <img src="./assets/wink_major.png" alt="Major Mike" id="majorMikeImage">
            </div>
            <div class="text-center fw-bold text-primary mb-2">🎖️ Major Mike 🎖️</div>
            <div class="dialogue-box" id="dialogueBox">
                <div class="dialogue-text" id="dialogueText">
                </div>
                <div class="dialogue-continue" id="dialogueContinue">Klicken oder Enter ↵</div>
            </div>
        </div>
        <div class="houses-grid">
            <h2 class="grid-title">🏘️ Level 9: Seperate Chaining</h2>
            <div class="street-block">
                <div class="houses-row">
                    <?php for ($i = 0; $i < 5; $i++): ?>
                        <div class="house-container" id="house-<?php echo $i; ?>" data-house="<?php echo $i; ?>">
                            <img src="./assets/Wohnhaus2BlauRot.svg" alt="Haus Basis" class="img-house-base">
                            <div class="name-badge-container" id="names-<?php echo $i; ?>"></div>
                            <div class="house-number"><?php echo $i; ?></div>
                        </div>
                    <?php endfor; ?>
                </div>
                <div class="street"></div>
            </div>
            <div class="street-block">
                <div class="houses-row">
                    <?php for ($i = 5; $i < 10; $i++): ?>
                        <div class="house-container" id="house-<?php echo $i; ?>" data-house="<?php echo $i; ?>">
                            <img src="./assets/Wohnhaus2BlauRot.svg" alt="Haus Basis" class="img-house-base">
                            <div class="name-badge-container" id="names-<?php echo $i; ?>"></div>
                            <div class="house-number"><?php echo $i; ?></div>
                        </div>
                    <?php endfor; ?>
                </div>
                <div class="street"></div>
            </div>
        </div>
        <div class="info-panel">
            <h3 class="info-title">📊 Stadtplanung</h3>
            <div class="info-item">
                <div class="info-label">Einziehende Familien:</div>
                <div class="family-list-container">
                    <ul id="familienListe" class="list-group">
                        <?php foreach ($bewohner_liste as $index => $familie): ?>
                            <li class="list-group-item to-do-family" data-family-index="<?php echo $index; ?>">
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
            <div class="info-item">
                <div class="info-label">Eingetragene Familien:</div>
                <div class="info-value" id="occupiedCount">0 / 11</div>
            </div>
        </div>
    </div>
</div>
<div class="success-overlay" id="successOverlay" style="display: none">
    <div class="success-modal">
        <div class="success-icon">🎉</div>
        <h2 class="success-title">Familie gefunden!</h2>
        <p class="success-message" id="successMessage">
            Danke für deine Hilfe!
        </p>
        <div class="success-buttons">
            <button class="btn-secondary" onclick="restartLevel()">↻ Nochmal spielen</button>
            <button class="btn-primary" onclick="nextLevel()">Weiter zu Level 10 →</button>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function() {
        // --- KONFIGURATION ---
        var HASH_SIZE = 10;
        var familien = <?php echo json_encode($bewohner_liste); ?>;
        var stadt = new Array(HASH_SIZE).fill(null).map(function() { return []; });
        var gamePhase = "intro";
        var currentIdx = 0;
        var currentName = null;
        var currentHash = null;
        var inputLocked = false;
        var SEARCH_TARGET = "Thomas";
        var occupiedHouses = 0;
        let isFading = false;

        // --- Dialoge & Sound Setup ---
        var dialogues = [
            "Willkommen zurück! Erinnerst du dich? Bisher mussten wir bei belegten Häusern immer lange nach einem freien Platz suchen (Probing). Das war mühsam.",
            "Hier machen wir es schlauer: Wir suchen <strong>nicht</strong> weiter! Wenn ein Haus belegt ist, bauen wir einfach an.",
            "Das Fachwort dafür ist <strong>Seperate Chaining</strong>. Wir erstellen quasi eine Liste von Bewohnern an derselben Adresse – wie in einem Mehrfamilienhaus.",
            "Leg los! Berechne die Hausnummer. Ist das Haus voll? Kein Problem: Wir bauen einfach eine Etage drauf!"
        ];

        let currentDialogue = -1;

        const soundClick   = new Audio('./assets/sounds/click.mp3');
        const soundSuccess = new Audio('./assets/sounds/success.mp3');
        const soundError   = new Audio('./assets/sounds/error.mp3');

        const dialogueAudios = [
            new Audio('./assets/sounds/Lvl9/Lvl9_1.mp3'),
            new Audio('./assets/sounds/Lvl9/Lvl9_2.mp3'),
            new Audio('./assets/sounds/Lvl9/Lvl9_3.mp3'),
            new Audio('./assets/sounds/Lvl9/Lvl9_4.mp3'),
        ];

        let currentAudioObj = null;

        function playDialogueAudio(index) {
            if (currentAudioObj) {
                currentAudioObj.pause();
                currentAudioObj.currentTime = 0;
            }
            if (index >= 0 && index < dialogueAudios.length) {
                currentAudioObj = dialogueAudios[index];
                currentAudioObj.play().catch(e => console.log("Audio blocked:", e));
            }
        }

        function playSound(type) {
            let audio;
            if (type === 'click') audio = soundClick;
            else if (type === 'success') audio = soundSuccess;
            else if (type === 'error') audio = soundError;
            if (audio) {
                audio.currentTime = 0;
                audio.play().catch(e => {});
            }
        }

        // --- Intro Logic (Vereinheitlicht!) ---
        function showNextDialogue() {
            if (isFading || gamePhase !== "intro") return;

            // Erst erhöhen (-1 -> 0)
            currentDialogue++;

            // Intro-Texte (0 bis 3)
            if (currentDialogue < 4) {
                playDialogueAudio(currentDialogue);
                isFading = true;

                $('#dialogueText').fadeOut(150, function() {
                    $(this).html(dialogues[currentDialogue]).fadeIn(150, function() {
                        isFading = false;
                    });

                    if(currentDialogue === 1) $('#majorMikeImage').attr('src', './assets/card_major.png');

                    // Letzter Intro-Text erreicht -> Spiel starten vorbereiten
                    if (currentDialogue === 3) {
                        $('#dialogueContinue').fadeOut();
                        setTimeout(startGamePlacement, 3000); // Automatischer Start nach Text 4
                    }
                });
            }
        }

        // Listener für Dialoge
        $('#dialogueBox').click(function() {
            if (gamePhase === "intro") showNextDialogue();
        });

        $(document).keydown(function(e) {
            if ((e.key === 'Enter' || e.key === ' ') && gamePhase === "intro") {
                showNextDialogue();
            }
        });

        // --- Init State ---
        $('#dialogueText').text("...");
        $('#dialogueContinue').show();

        // --- Game Logic ---
        function startGamePlacement() {
            gamePhase = "placement_calc";
            $('#dialogueContinue').hide();
            $('#majorMikeImage').attr('src', './assets/card_major.png');
            selectNextResident();
        }

        // Assets
        const housePairs = [
            { empty: "WH2BlauBraunLeer.svg", filled: "WH2BlauBraun.svg", extension: "WHBlauBraunErweiterung.svg" },
            { empty: "WH2BlauGrauLeer.svg", filled: "WH2BlauGrau.svg", extension: "WHBlauGrauErweiterung.svg" },
            { empty: "WH2BlauRotLeer.svg", filled: "WH2BlauRot.svg", extension: "WHBlauRotErweiterung.svg" },
            { empty: "WH2GrauBraunLeer.svg", filled: "WH2GrauBraun.svg", extension: "WHGrauBraunErweiterung.svg" },
            { empty: "WH2GruenBraunLeer.svg", filled: "WH2GruenBraun.svg", extension: "WHGruenBraunErweiterung.svg" },
            { empty: "WH2GruenGrauLeer.svg", filled: "WH2GruenGrau.svg", extension: "WHGruenGrauErweiterung.svg" },
            { empty: "WH2GelbBraunLeer.svg", filled: "WH2GelbBraun.svg", extension: "WHGelbBraunErweiterung.svg" },
            { empty: "WH2GelbRotLeer.svg", filled: "WH2GelbRot.svg", extension: "WHGelbRotErweiterung.svg" },
            { empty: "WH2RotBraunLeer.svg", filled: "WH2RotBraun.svg", extension: "WHRotBraunErweiterung.svg" },
            { empty: "WH2RotRotLeer.svg", filled: "WH2RotRot.svg", extension: "WHRotRotErweiterung.svg" }
        ];

        function getRandomHousePair() { return housePairs[Math.floor(Math.random() * housePairs.length)]; }

        $('.house-container').each(function() {
            const pair = getRandomHousePair();
            $(this).find('.img-house-base').attr('src', `./assets/${pair.empty}`);
        });

        function initFamilyList() {
            $('.to-do-family').addClass('disabled').css('opacity', '0.5').off('click');
            const currentFamily = familien[currentIdx];
            $(`.to-do-family[data-family="${currentFamily}"]`).removeClass('disabled').css('opacity', '1').on('click', selectNextResident);
        }
        initFamilyList();

        function selectNextResident() {
            if (currentIdx >= familien.length) {
                startSearchPhase();
                return;
            }
            currentName = familien[currentIdx];
            $('#nameInput').val(currentName);
            currentHash = null;
            $('.to-do-family[data-family-index=' + currentIdx + ']').addClass('active').css('opacity', '1');
            $('#hashButton').prop('disabled', false).text("Berechnen");
            $('#hashResult').text("Ergebnis ...");
            inputLocked = false;
        }

        function getHash(name) {
            var sum = 0;
            for (var i = 0; i < name.length; i++) sum += name.charCodeAt(i);
            return sum % HASH_SIZE;
        }

        $('#hashButton').click(function() {
            if (gamePhase === "placement_calc") {
                currentHash = getHash(currentName);
                $('#hashResult').text(`Hausnummer: ${currentHash}`);
                $(this).prop('disabled', true);
                $('#house-' + currentHash).addClass('highlight-target');
                gamePhase = "placement_click";
            } else if (gamePhase === "search_calc") {
                currentName = $('#nameInput').val().trimEnd();
                if (currentName === '') return;
                currentHash = getHash(currentName);
                if (currentName !== 'Thomas'){
                    $('#dialogueText').text(`Derzeit suchen wir nach ${SEARCH_TARGET} und nicht ${currentName}.`);
                    return;
                }
                $('#dialogueText').text(`Laut Rechner wohnt Thomas in Haus ${currentHash}. Klicke nun mehrmals auf das Haus, um jede Etage durchzugehen.`);
                $('#hashResult').text(`Hausnummer: ${currentHash}`);
                $(this).prop('disabled', true);
                $('#house-' + currentHash).addClass('highlight-target');
                gamePhase = "search_click";
            }
        });

        $(document).click(function(event) {
            if (!$(event.target).closest('.house-container').length) {
                if(gamePhase === "search_click") $('.resident-name').removeClass('revealed');
            }
        });

        $('.house-container').click(function(e) {
            e.stopPropagation();
            var clickedHouse = $(this).data('house');
            var $houseContainer = $(this);
            var $nameContainer = $('#names-' + clickedHouse);
            var $houseElement = $(`#house-${clickedHouse}`);

            // Search Traversing
            if (gamePhase === "search_click") {
                $('.house-container').not(this).find('.resident-name').removeClass('revealed');
                var $hiddenNames = $nameContainer.find('.resident-name').not('.revealed');

                if ($hiddenNames.length > 0) {
                    $hiddenNames.first().addClass('revealed');
                } else if ($nameContainer.find('.resident-name').length > 0) {
                    $nameContainer.find('.resident-name').removeClass('revealed');
                }
            }

            // Placement Phase
            if (gamePhase === "placement_click") {
                if (inputLocked) return;
                if (clickedHouse === currentHash) {
                    playSound('click');
                    inputLocked = true;
                    $('#dialogueText').text("Sehr gut. Das war das richtige Haus.");
                    $('#majorMikeImage').attr('src', './assets/wink_major.png');
                    stadt[clickedHouse].push(currentName);
                    var bewohnerAnzahl = stadt[clickedHouse].length;
                    var nameTag = $('<div class="resident-name">' + currentName + '</div>');
                    $nameContainer.append(nameTag);

                    const currentAsset = $houseElement.find('.img-house-base').attr('src');
                    const assetName = currentAsset.split('/').pop();
                    let matchingPair = null;
                    for (const pair of housePairs) {
                        if (pair.empty === assetName || pair.filled === assetName || pair.extension === assetName) {
                            matchingPair = pair; break;
                        }
                    }
                    if(!matchingPair) matchingPair = housePairs[0];

                    if(bewohnerAnzahl === 1){
                        $houseElement.find('.img-house-base').attr('src', `./assets/${matchingPair.filled}`);
                        occupiedHouses++;
                    } else if (bewohnerAnzahl > 1) {
                        $houseContainer.append($('<img>', {src: `./assets/${matchingPair.extension}`, alt: "Erweiterung", class: "img-house-extension"}));
                        occupiedHouses++;
                    }
                    $('#occupiedCount').text(occupiedHouses + ' / 11');

                    $houseContainer.removeClass('highlight-target');
                    $('.to-do-family[data-family-index=' + currentIdx + ']').removeClass('active').addClass('list-group-item-success').css('opacity', '1');
                    currentIdx++;
                    setTimeout(function() {
                        if(currentIdx < familien.length) {
                            gamePhase = "placement_calc";
                            selectNextResident();
                            if(bewohnerAnzahl > 1) $('#dialogueText').text("Kollision! Stockwerk hinzugefügt.");
                            else $('#dialogueText').text("Trage den nächsten Bewohner ein.");
                            $('#majorMikeImage').attr('src', './assets/card_major.png');
                        } else {
                            startSearchPhase();
                        }
                    }, 1000);
                } else {
                    playSound('error');
                    $('#dialogueText').html("Falsches Haus! Achte auf die Berechnung.");
                    $('#majorMikeImage').attr('src', './assets/sad_major.png');
                }
            }
            // Search Logic (Check)
            else if (gamePhase === "search_click") {
                var residentList = stadt[clickedHouse];
                var $thomasElement = $nameContainer.find('.resident-name').filter(function() {
                    return $(this).text() === SEARCH_TARGET;
                });

                if (clickedHouse === currentHash && residentList.includes(SEARCH_TARGET)) {
                    if ($thomasElement.hasClass('revealed')) {
                        $('#dialogueText').text("Da ist er ja! Danke für deine Hilfe!");
                        $('#majorMikeImage').attr('src', './assets/wink_major.png');
                        $thomasElement.addClass('found');
                        setTimeout(function() { $('#successOverlay').fadeIn(); }, 1000);
                        playSound('success');
                    } else {
                        playSound('click');
                        $('#dialogueText').text("Er wohnt in diesem Haus. Klicke weiter, um ihn in der Liste zu finden!");
                        $('#majorMikeImage').attr('src', './assets/card_major.png');
                    }
                } else {
                    playSound('error');
                    $('#dialogueText').html("Falsches Haus. Thomas wohnt hier nicht.");
                    $('#majorMikeImage').attr('src', './assets/sad_major.png');
                }
            }
        });

        function startSearchPhase() {
            gamePhase = "search_calc";

            // Sound für die Suche (Lvl9_5.mp3)
            playDialogueAudio(4);

            currentName = null;
            currentHash = null;
            $('#nameInput').prop('readonly', false).val('');
            $('#hashResult').text("?");
            $('.to-do-family').removeClass('active').css('opacity', '0.5');
            $('#hashButton').prop('disabled', false);
            $('.to-do-family[data-family-index=' + currentIdx + ']').addClass('active').css('opacity', '1');
            $('#dialogueText').text("Thomas hat noch eine Idee. Kannst du seine Hausnummer suchen?");
            $('#majorMikeImage').attr('src', './assets/card_major.png');
            $('.resident-name').removeClass('revealed');
        }

        // Globale Funktionen
        window.restartLevel = function() { location.reload(); };
        window.nextLevel = function() { window.location.href = 'Level-Auswahl?completed=9&next=10'; };
    });
</script>
</body>
</html>
