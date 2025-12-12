<?php
/**
 * HashCity - Level 3: Linear Probing
 */
$anzahl_haeuser = 5;
// Voreingestellte Belegung laut Plan
$bewohner_start = [
        0 => "Chris",
        2 => "Jannes",
        3 => "Jana"
];
// Hash-Werte für Validierung
$hash_werte = [
        "Chris" => 505,
        "Jana" => 378,
        "Dieter" => 605,
        "Lars" => 402,
        "Jannes" => 607
];
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
        /* --- Styles identisch zu Level 2 --- */
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
        .houses-row {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 1rem;
            padding: 0 1rem;
            position: relative;
            z-index: 2;
        }
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
        .street::before { content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(180deg, #4a4a4a 0%, #2a2a2a 100%); border-radius: 8px; z-index: -1; }
        .street::after { content: ''; position: absolute; top: 50%; left: 0; width: 100%; height: 4px; background: repeating-linear-gradient(90deg, #fff 0px, #fff 30px, transparent 30px, transparent 50px); transform: translateY(-50%); z-index: 2; }
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
        .house:hover:not(.belegt) { transform: translateY(-8px) scale(1.08); z-index: 10; }
        .house-icon { width: 100%; height: 100%; object-fit: contain; transition: all 0.3s ease; filter: drop-shadow(0 4px 8px rgba(0,0,0,0.2)); pointer-events: none; }
        .house.belegt .house-icon, .house.checked .house-icon { filter: drop-shadow(0 4px 8px rgba(255, 167, 38, 0.5)); }
        .house.found .house-icon { animation: pulse 1.5s infinite; filter: drop-shadow(0 8px 16px rgba(255, 215, 0, 0.8)); }
        @keyframes pulse { 0%, 100% { transform: scale(1); } 50% { transform: scale(1.08); } }
        .house-number { position: absolute; top: 25%; left: 50%; transform: translateX(-50%); font-family: 'Orbitron', sans-serif; font-size: 1rem; font-weight: 900; color: white; text-shadow: 2px 2px 6px rgba(0,0,0,0.7); z-index: 10; background: rgba(0, 0, 0, 0.3); padding: 0.2rem 0.5rem; border-radius: 8px; }
        .house-family { position: absolute; bottom: 10%; left: 50%; transform: translateX(-50%); font-size: 0.7rem; color: white; font-weight: 700; text-align: center; opacity: 0; transition: opacity 0.3s ease; background: rgba(0, 0, 0, 0.7); padding: 0.3rem 0.6rem; border-radius: 8px; white-space: nowrap; text-shadow: 1px 1px 2px rgba(0,0,0,0.8); max-width: 90%; overflow: hidden; text-overflow: ellipsis; pointer-events: none; }
        .house.checked .house-family, .house.found .house-family { opacity: 1; }
        /* INFO-PANEL */
        .info-panel { background: rgba(255, 255, 255, 0.85); border-radius: 25px; padding: 1.5rem; box-shadow: 0 10px 40px rgba(0,0,0,0.15); height: fit-content; position: sticky; top: 100px; border: 4px solid #fff; }
        .info-title { font-family: 'Orbitron', sans-serif; font-size: 1.4rem; font-weight: 700; color: #2E7D32; margin-bottom: 1.2rem; text-align: center; text-shadow: 2px 2px 4px rgba(0,0,0,0.1); }
        .info-item { background: #fff; padding: 1rem; border-radius: 15px; margin-bottom: 1rem; border: 3px solid #4CAF50; box-shadow: 0 4px 15px rgba(76, 175, 80, 0.15); }
        .info-label { font-weight: 700; color: #666; font-size: 0.95rem; margin-bottom: 0.4rem; }
        .hash-calculator { background: linear-gradient(135deg, #e3f2fd 0%, #fff 100%); border-color: #2196F3; }
        .calculator-input { width: 100%; border: 2px solid #ccc; border-radius: 10px; padding: 0.7rem; font-family: 'Rajdhani', sans-serif; font-size: 1.1rem; font-weight: 600; margin-bottom: 0.7rem; transition: border-color 0.3s ease; }
        .calculator-input:focus { outline: none; border-color: #667eea; }
        .calculator-button { width: 100%; padding: 0.8rem; background: linear-gradient(135deg, #4CAF50 0%, #2E7D32 100%); color: white; border: none; border-radius: 10px; font-family: 'Orbitron', sans-serif; font-weight: 700; font-size: 1rem; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 12px rgba(76, 175, 80, 0.3); }
        .calculator-result { margin-top: 1rem; padding: 0.8rem; background: #f8f9fa; border: 2px dashed #4CAF50; border-radius: 10px; text-align: center; font-family: 'Orbitron', sans-serif; font-weight: 700; color: #2E7D32; font-size: 1.1rem; }
        .family-list-container { max-height: 250px; padding: 0 5px; overflow-y: auto; }
        .list-group-item.family-to-assign { cursor: pointer; font-weight: 700; transition: all 0.2s ease; font-size: 1.1rem; border: 2px solid #aab8c2; margin-bottom: 0.5rem; border-radius: 10px !important; }
        .list-group-item.family-to-assign:hover:not(.placed) { background: #e9ecef; border-color: #667eea; }
        .list-group-item.family-to-assign.active { background: #667eea; color: white; border-color: #667eea; transform: scale(1.03); z-index: 10; }
        li.family-to-assign.placed { opacity: 1; background: #e0e0e0; cursor: not-allowed; text-decoration: line-through; }
        .list-group-item.family-to-assign.locked { opacity: 0.5; cursor: not-allowed; background: #f0f0f0; border-color: #ccc; pointer-events: none; }
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
        @media (max-width: 1200px) { .game-area { grid-template-columns: 1fr; gap: 1.5rem; } .major-mike-section, .info-panel { position: static; } }
        @media (max-width: 768px) { .game-container { padding: 0 1rem; margin: 1rem auto; } .houses-grid { padding: 1.5rem 1rem; } .houses-row { grid-template-columns: repeat(3, 1fr); } }
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
                    ...
                </div>
                <div class="dialogue-continue" id="dialogueContinue">
                    Klicken oder Enter ↵
                </div>
            </div>
        </div>
        <div class="houses-grid">
            <h2 class="grid-title">🏘️ Level 3: Linear Probing</h2>
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
            <div class="info-item">
                <div class="info-label">Aufgaben (Klicken):</div>
                <div class="family-list-container">
                    <ul id="familienListe" class="list-group">
                        <li class="list-group-item family-to-assign" data-family="Dieter">Dieter</li>
                        <li class="list-group-item family-to-assign" data-family="Lars">Lars</li>
                    </ul>
                </div>
            </div>
            <div class="info-item hash-calculator">
                <label for="nameInput" class="info-label" style="color: #666; font-size: 0.95rem;">Bewohnername:</label>
                <input type="text" id="nameInput" class="calculator-input" placeholder="Namen eingeben..." readonly value="" autocomplete="off">
                <button id="hashButton" class="calculator-button">Berechne Haus-Nr.</button>
                <div class="calculator-result" id="hashResult">Ergebnis: ...</div>
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
            <button class="btn-primary" onclick="nextLevel()">Weiter zu Level 4 →</button>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function() {
        // --- Setup Assets ---
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

        let gameStarted = false; // Steuert den Intro-Modus
        let isFading = false;
        let currentTask = 'Dieter';
        let selectedFamily = null;
        let calculatedHash = null;
        // --- Dialoge (Level 2 Style) ---
        const dialogues = [
            "Hallo. Ich habe eine Idee, wie wir die Kollisionen beheben könnten. Wenn ein Haus bereits belegt ist, soll der Bewohner einfach ins nächste freie Haus einziehen.",
            "Dieses Verfahren heißt <strong>Linear Probing</strong>.",
            "Fangen wir mit Dieter an. Wähle ihn aus der Liste und berechne seinen Hash."
        ];
        const dialogueAudios = [
            new Audio('./assets/sounds/Lvl3/Lvl3_1.mp3'),
            new Audio('./assets/sounds/Lvl3/Lvl3_2.mp3'),
            new Audio('./assets/sounds/Lvl3/Lvl3_3.mp3')
        ];
        let currentDialogue = -1;

        $('#nameInput').val('');

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
        // --- Helper: Dialog Update (für Game-Logic) ---
        function updateDialogue(text, img = 'card_major.png') {
            $('#dialogueText').html(text);
            $('#majorMikeImage').attr('src', './assets/' + img);
        }

        // --- Helper: Nächster Intro-Dialog ---
        function showNextDialogue() {
            if (isFading || currentDialogue >= dialogues.length) return;
            currentDialogue++;
            isFading = true;
            playDialogueAudio(currentDialogue);
            $('#dialogueText').fadeOut(200, function() {
                $(this).html(dialogues[currentDialogue]).fadeIn(200, function() {
                    isFading = false;
                });
                $('#majorMikeImage').attr('src', './assets/card_major.png');
                if (currentDialogue === dialogues.length - 1) {
                    $('#dialogueContinue').fadeOut();
                    gameStarted = true;
                    // Lars Box erst sperren wenn Spiel startet (oder beim laden)
                }
            });
        }

        // --- Initialisierung ---
        // Lars sperren
        $('li.family-to-assign[data-family="Lars"]').addClass('locked');

        // Assets setzen
        $('.house').each(function() {
            const $house = $(this);
            const isFilled = $house.hasClass('belegt');
            const pair = housePairs[Math.floor(Math.random() * housePairs.length)];
            const asset = isFilled ? pair.filled : pair.empty;
            $house.find('.house-icon').attr('src', `./assets/${asset}`);
        });

        // --- Intro Event Listener ---
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

        // --- Spielmechanik (nur wenn gameStarted) ---
        $('li.family-to-assign').click(function() {
            if (!gameStarted || $(this).hasClass('placed') || $(this).hasClass('locked')) return;
            const familyName = $(this).data('family');
            // Logik-Check
            if (currentTask === 'Dieter' && familyName !== 'Dieter') {
                updateDialogue("Wir müssen zuerst Dieter eintragen!", "sad_major.png");
                return;
            }
            if (currentTask === 'Lars' && familyName !== 'Lars') {
                updateDialogue("Wir müssen zuerst Lars eintragen!", "sad_major.png");
                return;
            }
            // Auswahl
            selectedFamily = familyName;
            $('li.family-to-assign').removeClass('active');
            $(this).addClass('active');
            $('#nameInput').val(selectedFamily);
            $('#hashResult').text('Ergebnis: ...');
            updateDialogue(`Okay, ${selectedFamily} ausgewählt. Klicke auf 'Berechnen'.`, 'card_major.png');
        });

        $('#hashButton').click(function() {
            if (!gameStarted || !selectedFamily) return;
            // Hash Berechnung (Hardcoded für L3 Logik)
            const hashValues = { "Dieter": 0, "Lars": 2, "Jannes": 2 }; // Haus 0, Haus 2, Haus 2
            calculatedHash = hashValues[selectedFamily];
            $('#hashResult').text(`Initial-Hash: ${calculatedHash}`);

            if (selectedFamily === 'Dieter') {
                updateDialogue("Dieter soll ins Haus 0. Das ist belegt! Das nächste freie Haus ist Haus 1. Trage ihn dort ein.", "sad_major.png");
            } else if (selectedFamily === 'Lars') {
                updateDialogue("Lars Hash ist 2. Haus 2 ist belegt! Suche das nächste freie Haus (Linear Probing).", "card_major.png");
            } else if (selectedFamily === 'Jannes') {
                updateDialogue("Jannes wohnt in Haus 2 (Hash 2). Klicke darauf.", "card_major.png");
            }
        });

        $('.house').click(function() {
            if (!gameStarted || !selectedFamily) return;
            const houseNum = $(this).data('house');
            const $house = $(this);

            // --- DIETER (Ziel: Haus 1) ---
            if (currentTask === 'Dieter') {
                if (houseNum === 1) {
                    playSound('click');
                    // Einziehen lassen
                    $house.removeClass('leer').addClass('belegt checked').data('family', 'Dieter');
                    $house.find('.house-family').text('Dieter').css('opacity', 1);
                    setHouseAsset($house, true);

                    // Liste aktualisieren
                    $('li.family-to-assign[data-family="Dieter"]').addClass('placed').removeClass('active');

                    // Übergang zu Lars
                    currentTask = 'Lars';
                    $('li.family-to-assign[data-family="Lars"]').removeClass('locked');
                    updateDialogue("Sehr gut! Jetzt ist Lars dran. Wähle ihn aus der Liste.", "wink_major.png");

                    // Reset für nächsten Schritt
                    selectedFamily = null;
                    $('#nameInput').val('');
                    $('#hashButton').prop('disabled', false); // Button wieder aktiv machen für Auswahl
                } else if ($house.hasClass('belegt')) {
                    playSound('error');
                    updateDialogue("Haus ist belegt! Linear Probing = Nächstes freies Haus.", "sad_major.png");
                } else {
                    playSound('error');
                    updateDialogue("Falsches Haus. Dieter gehört in Haus 1 (0 -> besetzt -> 1).", "sad_major.png");
                }
            }
            // --- LARS (Ziel: Haus 4) ---
            else if (currentTask === 'Lars') {
                if (houseNum === 4) {
                    playSound('click');
                    // Einziehen lassen
                    $house.removeClass('leer').addClass('belegt checked').data('family', 'Lars');
                    $house.find('.house-family').text('Lars').css('opacity', 1);
                    setHouseAsset($house, true);

                    // Liste aktualisieren
                    $('li.family-to-assign[data-family="Lars"]').addClass('placed').removeClass('active');

                    // Übergang zu Jannes
                    updateDialogue("Perfekt! Ich muss jetzt noch Jannes besuchen. Berechne seinen Hash, um ihn zu finden.", "card_major.png");
                    currentTask = 'Jannes';
                    selectedFamily = 'Jannes';

                    // Input setzen und Button freigeben
                    $('#nameInput').val('Jannes').prop('readonly', true);
                    $('#hashButton').prop('disabled', false);
                    $('#hashResult').text('Ergebnis: ...');
                } else if ($house.hasClass('belegt')) {
                    playSound('error');
                    updateDialogue("Besetzt! Weiter suchen (Linear Probing).", "sad_major.png");
                } else {
                    playSound('error');
                    updateDialogue("Falsch. Hash war 2. 2->belegt, 3->belegt -> 4.", "sad_major.png");
                }
            }
            // --- JANNES (Suche in Haus 2) ---
            else if (currentTask === 'Jannes') {
                if (houseNum === 2) {
                    playSound('click');
                    $house.addClass('found');
                    playSound('success');
                    $('#successOverlay').css('display', 'flex');
                } else {
                    // Besseres Feedback beim falschen Haus
                    const occupant = $house.data('family');
                    if (occupant) {
                        playSound('error');
                        updateDialogue(`Hier wohnt ${occupant}. Wir suchen Jannes (Hash 2)!`, "sad_major.png");
                    } else {
                        playSound('error');
                        updateDialogue("Dieses Haus ist leer. Wir suchen Jannes (Hash 2)!", "sad_major.png");
                    }
                }
            }
        });

        // Grafik-Helper
        function setHouseAsset(houseElement, isFilled) {
            const currentAsset = houseElement.find('.house-icon').attr('src');
            const assetName = currentAsset.split('/').pop();
            let matchingPair = housePairs[0];
            for (const pair of housePairs) {
                if (pair.empty === assetName || pair.filled === assetName) {
                    matchingPair = pair;
                    break;
                }
            }
            const newAsset = isFilled ? matchingPair.filled : matchingPair.empty;
            houseElement.find('.house-icon').attr('src', `./assets/${newAsset}`);
        }

        window.restartLevel = function() { location.reload(); };
        window.nextLevel = function() { window.location.href = 'Level-Auswahl?completed=3&next=4'; };
    });
</script>
</body>
</html>