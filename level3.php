<?php
/**
 * HashCity - Level 3: Lineares Sondieren (PDF-Version)
 *
 * Lernziel: Das Konzept der Kollisionsbehandlung "Linear Probing" verstehen und anwenden.
 * Spielmechanik: Folgt exakt dem PDF-Ablaufplan für Level 3.
 * -> Häuser 0, 2, 3 sind vor-belegt (gemäß L2-Plan).
 * -> Spieler fügt Dieter (Kollision -> 1) hinzu.
 * -> Spieler fügt Lars (Kollision -> 4) hinzu.
 * -> Spieler sucht Jannes (Haus 2).
 * -> Hausnamen sind nach Platzierung versteckt (nicht bei Hover sichtbar).
 */
$anzahl_haeuser = 5;
// Voreingestellte Belegung laut L2/L3-Plan
$bewohner_start = [
        0 => "Chris",
        2 => "Jannes",
        3 => "Jana"
];
// Hash-Werte
$hash_werte = [
        "Chris" => 505, // % 5 = 0
        "Jana" => 378,  // % 5 = 3
        "Dieter" => 605, // % 5 = 0
        "Lars" => 402,   // % 5 = 2
        "Jannes" => 607  // % 5 = 2
];
// Aufgaben für die Liste
$neue_bewohner = ["Dieter", "Lars"];
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HashCity - Level 3: Linear Probing</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Rajdhani:wght@300;500;700&display=swap" rel="stylesheet">
    <style>
        /* Basis-Styles (Sky, Grass, Header, etc.) */
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
            position: fixed; top: 0; left: 0; width: 100%; height: 50%;
            background: linear-gradient(180deg, #87CEEB 0%, #B0D4E3 100%); z-index: 0;
        }
        .grass-section {
            position: fixed; bottom: 0; left: 0; width: 100%; height: 50%;
            background: linear-gradient(180deg, #76B947 0%, #4CAF50 100%); z-index: 0;
        }
        .cloud {
            position: absolute; background: rgba(255, 255, 255, 0.7);
            border-radius: 100px; opacity: 0.8;
            animation: cloudFloat 40s linear infinite;
        }
        @keyframes cloudFloat {
            0% { left: -200px; } 100% { left: 110%; }
        }
        .game-header {
            background: transparent; padding: 1rem 2rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            position: relative; top: 0; z-index: 1000;
            backdrop-filter: blur(10px);
        }
        .back-btn {
            padding: 0.7rem 1.3rem; background: rgba(255, 255, 255, 0.9);
            border: 2px solid rgba(102, 126, 234, 0.5); border-radius: 30px;
            font-weight: 700; color: #667eea; cursor: pointer;
            transition: all 0.3s ease; font-family: 'Orbitron', sans-serif;
            text-decoration: none; display: inline-block; font-size: 0.9rem;
        }
        .back-btn:hover {
            background: #667eea; color: #fff; transform: scale(1.05);
        }
        .back-btn::before { content: '← '; margin-right: 5px; }
        .game-container {
            max-width: 1600px; margin: 2rem auto; padding: 0 2rem;
            position: relative; z-index: 1;
        }
        .game-area {
            display: grid; grid-template-columns: 280px 1fr 320px;
            gap: 2rem; min-height: 70vh;
        }
        /* Major Mike Section */
        .major-mike-section {
            background: rgba(255, 255, 255, 0.85); border-radius: 25px;
            padding: 1.5rem; box-shadow: 0 10px 40px rgba(0,0,0,0.15);
            height: fit-content; position: sticky; top: 100px; border: 4px solid #fff;
        }
        .major-mike-avatar {
            width: 100%; height: 240px; background: transparent;
            border-radius: 15px; display: flex; align-items: center;
            justify-content: center; margin-bottom: 1rem; overflow: hidden; position: relative;
        }
        .major-mike-avatar img {
            width: 100%; height: 100%; object-fit: contain;
        }
        .major-mike-name {
            font-family: 'Orbitron', sans-serif; font-size: 1.4rem;
            font-weight: 900; color: #667eea; text-align: center;
            margin-bottom: 1rem; text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        }
        .dialogue-box {
            background: #fff; border: 3px solid #667eea;
            border-radius: 20px; padding: 1.5rem; min-height: 180px;
            position: relative; box-shadow: 0 6px 20px rgba(102, 126, 234, 0.2);
        }
        .dialogue-box::before {
            content: ''; position: absolute; top: -15px; left: 50%;
            transform: translateX(-50%); width: 0; height: 0;
            border-left: 15px solid transparent; border-right: 15px solid transparent;
            border-bottom: 15px solid #667eea;
        }
        .dialogue-text {
            font-size: 1.05rem; line-height: 1.7;
            color: #333; font-weight: 500;
        }
        .dialogue-continue {
            position: absolute; bottom: 10px; right: 15px; font-size: 0.85rem;
            color: #667eea; font-style: italic; font-weight: 700;
            animation: blink 1.5s infinite;
        }
        @keyframes blink {
            0%, 50%, 100% { opacity: 1; } 25%, 75% { opacity: 0.5; }
        }
        /* Houses Grid (5 Häuser) */
        .houses-grid {
            background: rgba(255, 255, 255, 0.85); border-radius: 25px;
            padding: 2rem; box-shadow: 0 10px 40px rgba(0,0,0,0.15);
            border: 4px solid #fff; overflow: hidden;
        }
        .grid-title {
            font-family: 'Orbitron', sans-serif; font-size: 1.8rem;
            font-weight: 900; color: #2E7D32; text-align: center;
            margin-bottom: 2rem; text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        }
        .street-block {
            position: relative; margin-bottom: 2.5rem;
        }
        .houses-row {
            display: grid;
            grid-template-columns: repeat(5, 1fr); /* 5 Häuser */
            gap: 0.8rem; margin-bottom: 0.5rem;
            padding: 0 1rem; position: relative; z-index: 2;
        }
        .street {
            width: 100%; height: 60px; background-image: url('./assets/Strasse.svg');
            background-size: cover; background-position: center;
            background-repeat: repeat-x; position: relative; border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.15); z-index: 1;
        }
        .street::before {
            content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            background: linear-gradient(180deg, #4a4a4a 0%, #2a2a2a 100%);
            border-radius: 8px; z-index: -1;
        }
        .street::after {
            content: ''; position: absolute; top: 50%; left: 0; width: 100%; height: 4px;
            background: repeating-linear-gradient(90deg, #fff 0px, #fff 30px, transparent 30px, transparent 50px);
            transform: translateY(-50%); z-index: 2;
        }
        /* Haus-Styles (Klick-basiert) */
        .house {
            aspect-ratio: 1; background: transparent; border: none;
            display: flex; flex-direction: column; align-items: center;
            justify-content: center; transition: all 0.3s ease;
            position: relative; border-radius: 10px; padding: 0.3rem;
            cursor: pointer; /* Klickbar */
        }
        .house:hover:not(.belegt) {
            transform: translateY(-8px) scale(1.08);
            z-index: 10;
        }
        .house-icon {
            width: 100%; height: 100%; max-width: 100%; max-height: 100%;
            object-fit: contain; transition: all 0.3s ease;
            filter: drop-shadow(0 4px 8px rgba(0,0,0,0.2));
            pointer-events: none;
        }
        .house.belegt .house-icon,
        .house.checked .house-icon {
            filter: drop-shadow(0 4px 8px rgba(255, 167, 38, 0.5));
        }
        .house.found .house-icon {
            animation: pulse 1.5s infinite;
            filter: drop-shadow(0 8px 16px rgba(255, 215, 0, 0.8));
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1); } 50% { transform: scale(1.08); }
        }
        .house-number {
            position: absolute; top: 25%; left: 50%;
            transform: translateX(-50%); font-family: 'Orbitron', sans-serif;
            font-size: 1rem; font-weight: 900; color: white;
            text-shadow: 2px 2px 6px rgba(0,0,0,0.7); z-index: 10;
            background: rgba(0, 0, 0, 0.3); padding: 0.2rem 0.5rem; border-radius: 8px;
        }
        /* ANPASSUNG: Namen sind standardmäßig ausgeblendet */
        .house-family {
            position: absolute; bottom: 10%; left: 50%;
            transform: translateX(-50%); font-size: 0.7rem; color: white;
            font-weight: 700; text-align: center; opacity: 0; /* Ausgeblendet */
            transition: opacity 0.3s ease; background: rgba(0, 0, 0, 0.7);
            padding: 0.3rem 0.6rem; border-radius: 8px; white-space: nowrap;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.8); max-width: 90%;
            overflow: hidden; text-overflow: ellipsis;
            pointer-events: none;
        }
        /* Nur anzeigen, wenn Feedback aktiv ist */
        .house.checked .house-family,
        .house.found .house-family {
            opacity: 1;
        }
        /* INFO-PANEL (Stil von L2) */
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
        /* Hash-Rechner */
        .hash-calculator {
            background: linear-gradient(135deg, #e3f2fd 0%, #fff 100%);
            border-color: #2196F3;
        }
        .calculator-input {
            width: 100%; border: 2px solid #ccc; border-radius: 10px;
            padding: 0.7rem; font-family: 'Rajdhani', sans-serif; font-size: 1.1rem;
            font-weight: 600; margin-bottom: 0.7rem;
            transition: border-color 0.3s ease;
        }
        .calculator-input:focus {
            outline: none; border-color: #667eea;
        }
        .calculator-button {
            width: 100%; padding: 0.8rem;
            background: linear-gradient(135deg, #4CAF50 0%, #2E7D32 100%);
            color: white; border: none; border-radius: 10px;
            font-family: 'Orbitron', sans-serif; font-weight: 700;
            font-size: 1rem; cursor: pointer; transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(76, 175, 80, 0.3);
        }
        .calculator-result {
            margin-top: 1rem; padding: 0.8rem; background: #f8f9fa;
            border: 2px dashed #4CAF50; border-radius: 10px;
            text-align: center; font-family: 'Orbitron', sans-serif;
            font-weight: 700; color: #2E7D32; font-size: 1.1rem;
        }
        /* Familien-Liste (Stil von L2) */
        .family-list-container {
            max-height: 250px;
            overflow-y: auto;
        }
        .list-group-item.family-to-assign {
            cursor: pointer;
            font-weight: 700;
            transition: all 0.2s ease;
            font-size: 1.1rem;
            border: 2px solid #aab8c2;
            margin-bottom: 0.5rem;
            border-radius: 10px !important;
        }
        .list-group-item.family-to-assign:hover:not(.placed) {
            background: #e9ecef;
            border-color: #667eea;
        }
        .list-group-item.family-to-assign.active {
            background: #667eea;
            color: white;
            border-color: #667eea;
            transform: scale(1.03);
            z-index: 10;
        }
        li.family-to-assign.placed {
            opacity: 1;
            background: #e0e0e0;
            cursor: not-allowed;
            text-decoration: line-through;
        }
        /* Lars-Box am Anfang gesperrt */
        .list-group-item.family-to-assign.locked {
            opacity: 0.5;
            cursor: not-allowed;
            background: #f0f0f0;
            border-color: #ccc;
            pointer-events: none;
        }
        /* Success Modal (von L3) */
        .success-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.85); display: none; align-items: center;
            justify-content: center; z-index: 2000; animation: fadeIn 0.3s ease;
            backdrop-filter: blur(5px);
        }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        .success-modal {
            background: white; border-radius: 30px; padding: 3rem;
            max-width: 650px; text-align: center;
            box-shadow: 0 20px 60px rgba(0,0,0,0.4);
            animation: slideUp 0.5s ease; border: 5px solid #4CAF50;
        }
        @keyframes slideUp {
            from { transform: translateY(100px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .success-icon { font-size: 5rem; margin-bottom: 1rem; animation: bounce 1s infinite; }
        @keyframes bounce {
            0%, 100% { transform: translateY(0); } 50% { transform: translateY(-10px); }
        }
        .success-title {
            font-family: 'Orbitron', sans-serif; font-size: 2.8rem;
            font-weight: 900; color: #4CAF50; margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        }
        .success-message {
            font-size: 1.2rem; color: #666; line-height: 1.7;
            margin-bottom: 2rem; font-weight: 500;
        }
        .success-buttons {
            display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;
        }
        .btn-primary, .btn-secondary {
            padding: 1rem 2.5rem; border: none; border-radius: 30px;
            font-family: 'Orbitron', sans-serif; font-weight: 700;
            font-size: 1.05rem; cursor: pointer; transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;
        }
        .btn-primary:hover {
            transform: translateY(-2px); box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }
        .btn-secondary {
            background: white; color: #667eea; border: 3px solid #667eea;
        }
        .btn-secondary:hover {
            background: #667eea; color: white; transform: translateY(-2px);
        }
        /* Responsive Design */
        @media (max-width: 1200px) {
            .game-area { grid-template-columns: 1fr; gap: 1.5rem; }
            .major-mike-section, .info-panel { position: static; }
        }
        @media (max-width: 768px) {
            .game-container { padding: 0 1rem; margin: 1rem auto; }
            .houses-grid { padding: 1.5rem 1rem; }
            .houses-row { grid-template-columns: repeat(3, 1fr); }
            .street { height: 40px; }
            .success-modal { padding: 2rem; margin: 1rem; }
        }
        @media (max-width: 480px) {
            .houses-row { grid-template-columns: repeat(2, 1fr); }
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
                    Lade...
                </div>
                <div class="dialogue-continue" id="dialogueContinue" style="display: none;">
                    Klicken oder Enter ↵
                </div>
            </div>
        </div>
        <div class="houses-grid">
            <h2 class="grid-title">🏘️ Stadtteil 3: Linear Probing</h2>
            <div class="street-block">
                <div class="houses-row">
                    <?php
                    for ($i = 0; $i < $anzahl_haeuser; $i++):
                        $is_belegt = isset($bewohner_start[$i]);
                        $family_name = $is_belegt ? $bewohner_start[$i] : "";
                        $icon_src = $is_belegt ? "./assets/filled_house.svg" : "./assets/empty_house.svg";
                        $class = $is_belegt ? "house belegt" : "house leer";
                        ?>
                        <div class="<?php echo $class; ?>" data-house="<?php echo $i; ?>" data-family="<?php echo $family_name; ?>">
                            <img src="<?php echo $icon_src; ?>" alt="Haus <?php echo $i; ?>" class="house-icon">
                            <div class="house-number"><?php echo $i; ?></div>
                            <div class="house-family"><?php echo $family_name; ?></div>
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
                <button id="hashButton" class="calculator-button" style="background: linear-gradient(135deg, #4CAF50 0%, #2E7D32 100%);">Berechne Initial-Hash</button>
                <div class="calculator-result" id="hashResult">
                    Ergebnis: ...
                </div>
            </div>
            <div class="info-item">
                <div class="info-label">Aufgaben (Klicken):</div>
                <div class="family-list-container">
                    <ul id="familienListe" class="list-group">
                        <li class="list-group-item family-to-assign" data-family="Dieter">
                            Dieter
                        </li>
                        <li class="list-group-item family-to-assign" data-family="Lars">
                            Lars
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="success-overlay" id="successOverlay">
    <div class="success-modal">
        <div class="success-icon">🎉</div>
        <h2 class="success-title">Level 3 geschafft!</h2>
        <p class="success-message" id="successMessage">
            Danke für deine Hilfe, so funktioniert alles viel besser!
        </p>
        <div class="success-buttons">
            <button class="btn-secondary" onclick="restartLevel()">↻ Nochmal spielen</button>
            <button class="btn-primary" onclick="nextLevel()">Weiter zu Level 3.5 →</button>
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
        // --- Spielstatus-Variablen ---
        let gameState = 'dialogue_start';
        let currentTask = 'Dieter'; // Startaufgabe
        let selectedFamily = null;
        let calculatedHash = null;
        // --- PHP-Daten ---
        const anzahlHaeuser = <?php echo $anzahl_haeuser; ?>; // 5
        const hashValues = <?php echo json_encode($hash_werte); ?>;
        const correctJannesPosition = <?php echo $bewohner_start[2] ? 2 : -1; ?>; // Haus 2
        // --- Dialoge & Task-Struktur ---
        const introDialogues = [
            // Monolog 1 (Teil 1)
            { text: "Hallo. Ich hatte eine Idee. Wenn ein Haus bereits belegt ist, soll der Bewohner einfach ins nächste freie Haus einziehen.", img: "wink_major.png" }, // [cite: 110]
            { text: "Dieses Verfahren heißt \"linear probing\".", img: "card_major.png" }, // [cite: 111]
            { text: "Fangen wir mit Dieter an. Wähle ihn aus der Liste und berechne seinen Hash.", img: "card_major.png" }
        ];
        let introDialogueIndex = 0;
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
        // --- UI Update-Funktionen ---
        function updateDialogue(text, img = 'card_major.png') {
            $('#dialogueText').html(text);
            $('#majorMikeImage').attr('src', './assets/' + img);
        }
        function updateHouse($house, familyName) {
            $house.removeClass('leer').addClass('belegt').data('family', familyName);
            $house.find('.house-family').text(familyName);
            setHouseAsset($house, true);
            $house.addClass('found');
            $house.find('.house-family').css('opacity', 1);
            setTimeout(() => {
                $house.removeClass('found');
                $house.find('.house-family').css('opacity', 0);
            }, 2000);
        }
        function gameCompleted() {
            gameState = 'game_completed';
            updateDialogue("Danke für deine Hilfe, so funktioniert alles viel besser!", "wink_major.png");
            setTimeout(showSuccessModal, 2500);
        }
        // --- Initialisierung der Häuser mit zufälligen Assets ---
        $('.house').each(function() {
            const $house = $(this);
            const isFilled = $house.hasClass('belegt');
            const pair = getRandomHousePair();
            const asset = isFilled ? pair.filled : pair.empty;
            $house.find('.house-icon').attr('src', `./assets/${asset}`);
            $house.data('empty-asset', pair.empty);
            $house.data('filled-asset', pair.filled);
        });
        // --- Task-Steuerung ---
        function showNextIntroDialogue() {
            if (introDialogueIndex < introDialogues.length) {
                const dialogue = introDialogues[introDialogueIndex];
                updateDialogue(dialogue.text, dialogue.img);
                introDialogueIndex++;
                if (introDialogueIndex === introDialogues.length) {
                    $('#dialogueContinue').fadeOut();
                    gameState = 'awaiting_selection';
                    currentTask = 'Dieter';
                }
            }
        }
        // --- Event Handler ---
        $(document).keydown(function(e) {
            if ((e.key === 'Enter' || e.key === ' ') && gameState === 'dialogue_start') {
                showNextIntroDialogue();
            }
        });
        $('.dialogue-box').click(function() {
            if (gameState === 'dialogue_start') {
                showNextIntroDialogue();
            }
        });
        $('li.family-to-assign').click(function() {
            if (gameState === 'game_completed' || $(this).hasClass('placed') || $(this).hasClass('locked')) {
                return;
            }
            const familyData = $(this).data('family');
            const familyName = $(this).data('name') || familyData;
            if (familyData !== 'Jannes-Suche' && currentTask !== familyName) {
                updateDialogue(`Moment! Wir müssen uns zuerst um ${currentTask} kümmern.`, "sad_major.png");
                return;
            }
            if (familyData === 'Jannes-Suche' && currentTask !== 'Jannes') {
                updateDialogue(`Moment! Wir müssen uns zuerst um ${currentTask} kümmern.`, "sad_major.png");
                return;
            }
            selectedFamily = familyName;
            $('li.family-to-assign').removeClass('active');
            $(this).addClass('active');
            $('#nameInput').val(selectedFamily);
            $('#hashResult').text('Ergebnis: ...');
            updateDialogue(`Okay, ${selectedFamily} ausgewählt. Klicke auf 'Berechnen'.`, 'card_major.png');
            gameState = 'awaiting_hash';
        });
        $('#hashButton').click(function() {
            if (gameState === 'game_completed' || !selectedFamily) return;
            const name = $('#nameInput').val();
            if (name !== selectedFamily) {
                updateDialogue(`Der Name im Rechner passt nicht zur ausgewählten Familie.`, "sad_major.png");
                return;
            }
            const hashSum = hashValues[name];
            calculatedHash = hashSum % anzahlHaeuser;
            $('#hashResult').text(`Initial-Hash: ${calculatedHash}`);
            if (currentTask === 'Dieter' && name === 'Dieter') {
                updateDialogue("Dieter soll ins Haus 0, dies ist aber schon belegt. Das nächste freie Haus ist das Haus 1. Trage Dieter ins Haus 1 ein.", "sad_major.png");
                gameState = 'awaiting_click';
            }
            else if (currentTask === 'Lars' && name === 'Lars') {
                updateDialogue(`Initial-Hash ist ${calculatedHash}. Klicke auf das korrekte *freie* Haus.`, "card_major.png");
                gameState = 'awaiting_click';
            }
            else if (currentTask === 'Jannes' && name === 'Jannes') {
                updateDialogue(`Initial-Hash ist ${calculatedHash}. Klicke auf das Haus, in dem Jannes wohnt.`, "card_major.png");
                gameState = 'awaiting_click';

            }
        });
        $('.house').click(function() {
            if (gameState !== 'awaiting_click' || !selectedFamily) {
                return;
            }
            const $house = $(this);
            const targetHouseNum = $house.data('house');
            if (currentTask === 'Dieter') {
                if ($house.data('family') && targetHouseNum !== 1) {
                    updateDialogue(`Haus ${targetHouseNum} ist bereits belegt!`, "sad_major.png");
                    return;
                }
                if (targetHouseNum === 1) {
                    updateHouse($house, "Dieter");
                    $('li.family-to-assign[data-family="Dieter"]').addClass('placed').removeClass('active');
                    // Lars-Box freischalten
                    $('li.family-to-assign[data-family="Lars"]').removeClass('locked');
                    updateDialogue("Der nächste Bewohner ist Lars. Trage Ihn ins richtige Haus ein.", "wink_major.png");
                    currentTask = 'Lars';
                    gameState = 'awaiting_selection';
                    $('#nameInput').val('');
                } else {
                    updateDialogue("Das war das falsche Haus. Beachte das aktuelle Verfahren bei einer Kollision (linear Probing).", "sad_major.png");
                }
                return;
            }
            if (currentTask === 'Lars') {
                if (!$house.data('family')) {
                    if (targetHouseNum === 4) {
                        updateHouse($house, "Lars");
                        $('li.family-to-assign[data-family="Lars"]').addClass('placed').removeClass('active');
                        $('#nameInput').val('');
                        setTimeout(() => {
                            updateDialogue("Ich bin heute Abend bei Jannes eingeladen. Kannst du mir noch seine Hausnummer sagen?", "card_major.png");
                            $('#nameInput').prop('readonly', false).val('').focus();
                            currentTask = 'Jannes';
                            selectedFamily = 'Jannes';
                            gameState = 'awaiting_selection';
                        }, 2000);
                    } else {
                        updateDialogue("Das war das falsche Haus, achte auf Rechtschreibung des Namens und lass die Hausnummer berechnen. Beachte auch das aktuelle Verfahren bei einer Kollision (linear Probing).", "sad_major.png");
                        $house.addClass('checked');
                        setTimeout(() => {
                            $house.removeClass('checked');
                            $house.find('.house-family').css('opacity', 0);
                        }, 1000);
                    }
                    return;
                } else {
                    updateDialogue(`Haus ${targetHouseNum} ist bereits belegt!`, "sad_major.png");
                    return;
                }
            }
            if (currentTask === 'Jannes') {
                if (targetHouseNum === correctJannesPosition) {
                    if ($house.data('family') === 'Jannes') {
                        $house.addClass('found');
                        $('li.family-to-assign[data-family="Jannes-Suche"]').addClass('placed').removeClass('active');
                        gameCompleted();
                    } else {
                        updateDialogue("Moment... da wohnt ja gar nicht Jannes. Seltsam!", "sad_major.png");
                    }
                } else {
                    updateDialogue("Das war das falsche Haus, achte auf Rechtschreibung des Namens und lass die Hausnummer berechnen.", "sad_major.png");
                    $house.addClass('checked');
                    setTimeout(() => {
                        $house.removeClass('checked');
                        $house.find('.house-family').css('opacity', 0);
                    }, 1000);
                }
                return;
            }
        });
        function startGame() {
            // Lars-Box am Anfang sperren
            $('li.family-to-assign[data-family="Lars"]').addClass('locked');
            updateDialogue(introDialogues[0].text, introDialogues[0].img);
            $('#dialogueContinue').fadeIn();
            gameState = 'dialogue_start';
            introDialogueIndex = 1;
        }
        function showSuccessModal() {
            $('#successMessage').text("Danke für deine Hilfe, so funktioniert alles viel besser!");
            $('#successOverlay').css('display', 'flex');
        }
        window.restartLevel = function() {
            location.reload();
        };
        window.nextLevel = function() {
            window.location.href = 'Level-Auswahl?completed=3&next=4';
        };
        startGame();
    });
</script>
</body>
</html>
