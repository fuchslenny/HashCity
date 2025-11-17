<?php
/**
 * HashCity - Level 4: Anwendung Linear Probing
 *
 * Lernziel: Spieler wendet Linear Probing selbstständig an, um eine
 * größere Menge an Daten zu platzieren und anschließend zu durchsuchen.
 */

$anzahl_haeuser = 15; // 0-14

// Familien für die Platzierung
$familien_liste = [
    "Sophie", "Dieter", "Emil", "Sammy", "Grit",
    "Marie", "Nele", "Claudia", "Sara", "Nils"
];

// Hash-Funktion (Referenz): (SUMME(ASCII) % 15)
// h(Sophie) = 616 % 15 = 1  -> Haus 1
// h(Dieter) = 605 % 15 = 5  -> Haus 5
// h(Emil) = 391 % 15 = 1    -> Kollision (1) -> Haus 2
// h(Sammy) = 519 % 15 = 9   -> Haus 9
// h(Grit) = 406 % 15 = 1    -> Kollision (1, 2) -> Haus 3
// h(Marie) = 502 % 15 = 7   -> Haus 7
// h(Nele) = 388 % 15 = 13   -> Haus 13
// h(Claudia) = 701 % 15 = 11 -> Haus 11
// h(Sara) = 391 % 15 = 1    -> Kollision (1, 2, 3) -> Haus 4
// h(Nils) = 406 % 15 = 1    -> Kollision (1, 2, 3, 4, 5) -> Haus 6
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
        /* Kompletter CSS-Block aus den vorherigen Levels */
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

        .game-container {
            max-width: 1600px;
            margin: 2rem auto;
            padding: 0 2rem;
            position: relative;
            z-index: 1;
        }

        .game-area {
            display: grid;
            grid-template-columns: 280px 1fr 320px;
            gap: 2rem;
            min-height: 70vh;
        }

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

        .street-block {
            position: relative;
            margin-bottom: 2.5rem;
        }

        .street-block:last-child {
            margin-bottom: 0;
        }

        /* NEU: 15 Häuser, 5 pro Reihe */
        .houses-row {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 1rem;
            margin-bottom: 0.5rem;
            padding: 0 1rem;
            position: relative;
            z-index: 2;
        }

        .street {
            width: 100%;
            height: 60px;
            background-image: url('./assets/Strasse.svg');
            background-size: cover;
            background-position: center;
            background-repeat: repeat-x;
            position: relative;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
            z-index: 1;
        }

        .street::before, .street::after {
            /* (CSS für Straße aus vorherigem Level) */
            content: '';
            position: absolute;
            left: 0;
            width: 100%;
        }
        .street::before {
            top: 0; height: 100%;
            background: linear-gradient(180deg, #4a4a4a 0%, #2a2a2a 100%);
            border-radius: 8px; z-index: -1;
        }
        .street::after {
            top: 50%; height: 4px;
            background: repeating-linear-gradient(90deg, #fff 0px, #fff 30px, transparent 30px, transparent 50px);
            transform: translateY(-50%); z-index: 2;
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

        /* "found" = Erfolgreicher Such-Klick */
        .house.found .house-icon {
            animation: pulse 1.5s infinite;
            filter: drop-shadow(0 8px 16px rgba(76, 175, 80, 0.8)); /* Grün für Erfolg */
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

        /* Name wird sichtbar, wenn gefunden */
        .house.found .house-family {
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
        .calc-button:disabled {
            background: #ccc;
            cursor: not-allowed;
        }

        .family-list-container {
            max-height: 250px;
            overflow-y: auto;
            overflow-x: hidden;
        }

        /* Familien-Liste (angepasst für Level 4) */
        .list-group-item.to-do-family {
            font-weight: 700;
            font-size: 1.1rem;
            background: #f8f9fa;
            color: #666;
            cursor: not-allowed; /* Nicht klickbar, Spiel steuert den Fluss */
        }
        .list-group-item.to-do-family.active {
            background: #667eea;
            color: #fff;
            transform: scale(1.03);
            z-index: 10;
        }
        .list-group-item.list-group-item-success {
            text-decoration: line-through;
            background: #e9f5e9;
            color: #999;
        }

        /* Modal (jetzt für Erfolg) */
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
            border: 5px solid #4CAF50; /* Grün für Erfolg */
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
            color: #4CAF50; /* Grün für Erfolg */
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

        .success-stats { display: none; } /* Nicht benötigt */

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

        /* Responsive */
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
            .game-container { padding: 0 1rem; margin: 1rem auto; }
            .houses-grid { padding: 1.5rem 1rem; }
            .houses-row { grid-template-columns: repeat(3, 1fr); gap: 0.6rem; }
        }
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
            <div class="dialogue-box">
                <div class="dialogue-text" id="dialogueText">
                    Das läuft ja schon sehr gut. Du darfst jetzt diesen neuen Stadtteil allein bearbeiten. Verwende dafür linear probing, falls es zu Kollisionen kommt. Hier ist eine Liste der Bewohner. Beachte dabei, dass du diese von oben nach unten abarbeitest.
                </div>
                <div class="dialogue-continue" id="dialogueContinue">
                    Drücke Enter ↵
                </div>
            </div>
        </div>

        <div class="houses-grid">
            <h2 class="grid-title">🏘️ Stadtteil (Level 4)</h2>

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
                <div class="info-label">Hash-Rechner 3000</div>
                <div class="info-label mt-3">Ergebnis (Hash / Haus-Nr.):</div>
                <div class="hash-result-value" id="hashResult">-</div>
                <button id="hashButton" class="calc-button" disabled>Berechne Haus-Nr.</button>
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
            <button class="btn-secondary" onclick="restartLevel()">↻ Level neustarten</button>
            <button class="btn-primary" onclick="nextLevel()">Weiter zu Level 5 →</button>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    $(document).ready(function() {
        // --- Level 4 Setup ---
        const HASH_SIZE = <?php echo $anzahl_haeuser; ?>;
        const familien = <?php echo json_encode($familien_liste); ?>;

        let stadt = new Array(HASH_SIZE).fill(null);
        let gameStarted = false;
        let isFading = false;

        // Phasen: placement_calculate, placement_find_spot, search_calculate, search_find
        let gamePhase = "placement_calculate";

        let currentFamilyIndex = 0;
        let selectedFamily = null;
        let correctTargetHouse = null;
        let initialHash = null;

        // --- NEU: Suchziel ist SARA (Kollisionskette) ---
        const SEARCH_TARGET_NAME = "Sara";
        let searchInitialHash = null;
        let searchCorrectHouse = null;


        // --- Hash-Funktion (0-indiziert) ---
        function getHash(key, size) {
            let sum = 0;
            for (let i = 0; i < key.length; i++) {
                sum += key.charCodeAt(i);
            }
            return (sum % size);
        }

        // --- Helper: Berechnet das finale Haus (inkl. Probing) ---
        function calculateFinalIndex(startHash) {
            let finalIndex = startHash;
            let probeCount = 0;
            while (stadt[finalIndex] !== null) {
                finalIndex = (finalIndex + 1) % HASH_SIZE;
                probeCount++;
                if (probeCount > HASH_SIZE) return -1; // Fehler, Stadt voll
            }
            return finalIndex;
        }

        // --- Helper: Findet das Haus einer Familie (inkl. Probing) ---
        function findFamilyByProbing(startHash, familyName) {
            let finalIndex = startHash;
            let probeCount = 0;
            while (probeCount < HASH_SIZE) {
                if (stadt[finalIndex] === familyName) {
                    return finalIndex; // Ja!
                }
                if (stadt[finalIndex] === null) {
                    return -1; // Nein, kann nicht weiter sein.
                }
                finalIndex = (finalIndex + 1) % HASH_SIZE;
                probeCount++;
            }
            return -1; // Nicht gefunden
        }

        // --- Dialog-Steuerung ---
        function showNextDialogue() {
            if (isFading || gameStarted) return;
            isFading = true;

            $('#dialogueText').fadeOut(200, function() {
                $(this).text(dialogues[currentDialogue]).fadeIn(200, function() {
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

        const dialogues = [
            "Das läuft ja schon sehr gut. Du darfst jetzt diesen neuen Stadtteil allein bearbeiten. Verwende dafür linear probing, falls es zu Kollisionen kommt. Hier ist eine Liste der Bewohner. Beachte dabei, dass du diese von oben nach unten abarbeitest."
        ];
        let currentDialogue = 0;

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

        // --- Level 4 Spiellogik (NEU) ---

        // Startet den Zyklus für die nächste Familie
        function selectNextFamily() {
            if (currentFamilyIndex >= familien.length) {
                startSearchPhase(); // Alle platziert
                return;
            }

            gamePhase = "placement_calculate"; // Zurücksetzen
            selectedFamily = familien[currentFamilyIndex];
            initialHash = null;
            correctTargetHouse = null;

            $('.to-do-family').removeClass('active');
            $(`.to-do-family[data-family-index=${currentFamilyIndex}]`).addClass('active');

            $('#hashButton').prop('disabled', false);
            $('#hashResult').text('-');
            $('.house').removeClass('highlight-target');

            $('#dialogueText').text(`Platziere jetzt: ${selectedFamily}. Klicke 'Berechnen'.`);
        }

        // Familienliste für Platzierung UND Suche
        $('#familienListe').on('click', '.to-do-family', function() {
            if (gamePhase === "placement_find_spot" || gamePhase === "search_find") return;

            const $item = $(this);

            if (gamePhase === "placement_calculate") {
                if (parseInt($item.data('family-index')) !== currentFamilyIndex) {
                    $('#dialogueText').text("Bitte arbeite die Liste von oben nach unten ab.");
                    return;
                }
                selectedFamily = familien[currentFamilyIndex];
            }
            else if (gamePhase === "search_calculate") {
                selectedFamily = $item.text(); // Holt den Namen (z.B. "Sara")
            }

            $('.to-do-family').removeClass('active');
            $item.addClass('active');
            $('#hashButton').prop('disabled', false);
            $('#hashResult').text('-');
            $('#dialogueText').text(`Okay, ${selectedFamily} ausgewählt. Klicke 'Berechnen'.`);
        });


        // 1. "Berechne" klicken (funktioniert jetzt in 2 Phasen)
        $('#hashButton').click(function() {
            if (!selectedFamily) return;

            initialHash = getHash(selectedFamily, HASH_SIZE);
            $('#hashResult').text(initialHash);
            $(this).prop('disabled', true);

            // --- Phase 1: Platzierung ---
            if (gamePhase === "placement_calculate") {
                correctTargetHouse = calculateFinalIndex(initialHash);

                $('#dialogueText').text(`Initial-Hash: ${initialHash}. Klicke auf das entsprechende Haus.`);
                $(`.house[data-house=${initialHash}]`).addClass('highlight-target');

                gamePhase = "placement_find_spot";
            }
            // --- Phase 2: Suche ---
            else if (gamePhase === "search_calculate") {
                searchInitialHash = initialHash; // Ist 1 (für Sara)
                searchCorrectHouse = findFamilyByProbing(searchInitialHash, selectedFamily); // Ist 4 (für Sara)

                $('#dialogueText').text(`Okay, der Initial-Hash für ${selectedFamily} ist ${initialHash}. Klick auf Haus ${initialHash}, um nachzusehen.`);
                $(`.house[data-house=${initialHash}]`).addClass('highlight-target');

                gamePhase = "search_find";
            }
        });

        // 2. Haus klicken (Platzierung ODER Suche)
        $('.house').click(function() {
            if (!gameStarted) return;
            const $house = $(this);
            const houseNumber = $house.data('house');

            // --- PHASE 1: PLATZIERUNG ---
            if (gamePhase === "placement_find_spot") {

                if (houseNumber === correctTargetHouse) {
                    placeFamily($house, houseNumber, selectedFamily);
                    currentFamilyIndex++;
                    selectNextFamily();
                }
                else if (stadt[houseNumber] !== null) {
                    $('#dialogueText').text("Halt! Dieses Haus ist auch belegt. Nutze Linear Probing und finde das *nächste* freie Haus.");
                    $house.addClass('checked');
                    $house.removeClass('highlight-target');
                }
                else {
                    $('#dialogueText').text("Mindestens ein Bewohner ist im falschen Haus. Versuche es erneut und achte dabei auf (...) dem Verfahren bei einer Kollision (linear probing)."); // Monolog 2
                }
            }

            // --- PHASE 2: SUCHE (nach Sara) ---
            else if (gamePhase === "search_find") {
                const clickedFamily = $house.data('family');

                // Namen aufdecken (wird bei jedem Klick gemacht)
                $house.find('.house-family').css('opacity', 1);

                // A: Spieler klickt auf das korrekte, finale Haus (Haus 4)
                if (houseNumber === searchCorrectHouse) {
                    if (clickedFamily === SEARCH_TARGET_NAME) { // Doppelte Prüfung
                        $('#dialogueText').text("Danke für deine Hilfe!"); // Monolog 6
                        $house.addClass('found');
                        gameCompleted = true;
                        setTimeout(showSuccessModal, 1500);
                    }
                }
                // B: Spieler klickt auf ein Haus in der Probing-Kette (Haus 1, 2, 3)
                else if (houseNumber >= searchInitialHash && houseNumber < searchCorrectHouse) {
                    $('#dialogueText').text(`Falsch! Das ist ${clickedFamily}. Da der Initial-Hash ${searchInitialHash} war, müssen wir jetzt linear weitersuchen (Probing). Klick auf das nächste Haus.`); // Monolog 5
                    $house.removeClass('highlight-target');
                    $(`.house[data-house=${houseNumber + 1}]`).addClass('highlight-target'); // Zeigt auf das nächste Haus
                }
                // C: Spieler klickt auf ein GANZ falsches Haus (z.B. Haus 10)
                else {
                    $('#dialogueText').text(`Das ist ${clickedFamily}. Das ist nicht das richtige Haus. Der Initial-Hash war ${searchInitialHash}.`);
                }
            }
        });

        // Helper-Funktion zum Platzieren
        function placeFamily($house, houseNumber, family) {
            stadt[houseNumber] = family;

            $house.find('.house-icon').attr('src', './assets/filled_house.svg');
            $house.find('.house-family').text(family); // Wichtig: Text setzen, auch wenn unsichtbar
            $house.addClass('checked');
            $house.removeClass('highlight-target');
            $house.attr('data-family', family);

            $(`.to-do-family[data-family-index=${currentFamilyIndex}]`)
                .removeClass('active')
                .addClass('list-group-item-success')
                .off('click');
        }

        // Startet die Such-Phase
        function startSearchPhase() {
            gamePhase = "search_calculate";
            selectedFamily = null;
            correctTargetHouse = null;

            $('#hashButton').prop('disabled', true);
            $('#hashResult').text('?');
            $('.house').removeClass('highlight-target');

            // Liste re-aktivieren (CSS und JS)
            $('.list-group-item.list-group-item-success')
                .removeClass('list-group-item-success')
                .addClass('to-do-family');

            // Monolog 3
            $('#dialogueText').text("Sehr gut! Alle Bewohner sind im richtigen Haus.");
            $('#majorMikeImage').attr('src', './assets/wink_major.png');

            // Monolog 4 (angepasst auf SARA)
            setTimeout(function() {
                $('#dialogueText').text(`Kannst du mir die Hausnummer von ${SEARCH_TARGET_NAME} geben? Nutze die Liste und den Rechner, um ihren Initial-Hash zu finden.`);
                $('#majorMikeImage').attr('src', './assets/card_major.png');
            }, 3000);
        }

        function showSuccessModal() {
            $('#successMessage').text("Danke für deine Hilfe!"); // Monolog 6
            $('#successOverlay').css('display', 'flex');
        }

        // Globale Funktionen für Modal-Buttons
        window.restartLevel = function() {
            location.reload();
        };

        window.nextLevel = function() {
            $('body').css('transition', 'opacity 0.5s ease');
            $('body').css('opacity', '0');
            setTimeout(function() {
                window.location.href = 'level-select.php?completed=4&next=5';
            }, 500);
        };

    });
</script>

</body>
</html>