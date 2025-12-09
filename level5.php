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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Rajdhani:wght@300;500;700&display=swap" rel="stylesheet">
    <style>
        /* --- Styles wie gehabt (Level 6 Standard) --- */
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

        /* Major Mike Section */
        .major-mike-section { background: rgba(255, 255, 255, 0.85); border-radius: 25px; padding: 1.5rem; box-shadow: 0 10px 40px rgba(0,0,0,0.15); height: fit-content; position: sticky; top: 100px; border: 4px solid #fff; }
        .major-mike-avatar { width: 100%; height: 240px; background: transparent; border-radius: 15px; display: flex; align-items: center; justify-content: center; margin-bottom: 1rem; overflow: hidden; position: relative; }
        .major-mike-avatar img { width: 100%; height: 100%; object-fit: contain; }
        .major-mike-name { font-family: 'Orbitron', sans-serif; font-size: 1.4rem; font-weight: 900; color: #667eea; text-align: center; margin-bottom: 1rem; text-shadow: 2px 2px 4px rgba(0,0,0,0.1); }
        .dialogue-box { background: #fff; border: 3px solid #667eea; border-radius: 20px; padding: 1.5rem; min-height: 180px; position: relative; box-shadow: 0 6px 20px rgba(102, 126, 234, 0.2); cursor: pointer; transition: transform 0.2s; }
        .dialogue-box:hover { transform: scale(1.02); }
        .dialogue-box::before { content: ''; position: absolute; top: -15px; left: 50%; transform: translateX(-50%); width: 0; height: 0; border-left: 15px solid transparent; border-right: 15px solid transparent; border-bottom: 15px solid #667eea; }
        .dialogue-text { font-size: 1.05rem; line-height: 1.7; color: #333; font-weight: 500; }
        .dialogue-continue { position: absolute; bottom: 10px; right: 15px; font-size: 0.85rem; color: #667eea; font-style: italic; font-weight: 700; animation: blink 1.5s infinite; }
        @keyframes blink { 0%, 50%, 100% { opacity: 1; } 25%, 75% { opacity: 0.5; } }

        /* Houses Grid */
        .houses-grid { background: rgba(255, 255, 255, 0.85); border-radius: 25px; padding: 2rem; box-shadow: 0 10px 40px rgba(0,0,0,0.15); border: 4px solid #fff; overflow: hidden; }
        .grid-title { font-family: 'Orbitron', sans-serif; font-size: 1.8rem; font-weight: 900; color: #2E7D32; text-align: center; margin-bottom: 2rem; text-shadow: 2px 2px 4px rgba(0,0,0,0.1); }
        .street-block { position: relative; margin-bottom: 2.5rem; }
        .street-block:last-child { margin-bottom: 0; }
        .houses-row { display: grid; grid-template-columns: repeat(5, 1fr); gap: 1rem; margin-bottom: 0.5rem; padding: 0 1rem; position: relative; z-index: 2; }
        .street { width: 100%; height: 60px; background-image: url('./assets/Strasse.svg'); background-size: cover; background-position: center; position: relative; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.15); z-index: 1; }
        .street::before { content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(180deg, #4a4a4a 0%, #2a2a2a 100%); border-radius: 8px; z-index: -1; }
        .street::after { content: ''; position: absolute; top: 50%; left: 0; width: 100%; height: 4px; background: repeating-linear-gradient(90deg, #fff 0px, #fff 30px, transparent 30px, transparent 50px); transform: translateY(-50%); z-index: 2; }

        /* House Elements */
        .house { aspect-ratio: 1; background: transparent; border: none; display: flex; flex-direction: column; align-items: center; justify-content: center; cursor: pointer; transition: all 0.3s ease; position: relative; border-radius: 10px; padding: 0.3rem; }
        .house:hover:not(.checked):not(.found) { transform: translateY(-8px) scale(1.08); z-index: 10; }

        /* Highlight Targets */
        .house.highlight-target { transform: translateY(-10px) scale(1.15) !important; box-shadow: 0 0 35px 12px rgba(255, 215, 0, 0.9); z-index: 11; }
        .house.quadratic-target { transform: translateY(-10px) scale(1.15) !important; box-shadow: 0 0 35px 12px rgba(255, 0, 0, 0.8); z-index: 11; /* Rot für Kollision */ }

        .house-icon { width: 100%; height: 100%; object-fit: contain; transition: all 0.3s ease; filter: drop-shadow(0 4px 8px rgba(0,0,0,0.2)); }
        .house.checked .house-icon { filter: drop-shadow(0 4px 8px rgba(255, 167, 38, 0.5)); }
        .house-number { position: absolute; top: 25%; left: 50%; transform: translateX(-50%); font-family: 'Orbitron', sans-serif; font-size: 1rem; font-weight: 900; color: white; text-shadow: 2px 2px 6px rgba(0,0,0,0.7); z-index: 10; background: rgba(0, 0, 0, 0.3); padding: 0.2rem 0.5rem; border-radius: 8px; }
        .house-family { position: absolute; bottom: 10%; left: 50%; transform: translateX(-50%); font-size: 0.7rem; color: white; font-weight: 700; text-align: center; opacity: 0; transition: opacity 0.3s ease; background: rgba(0, 0, 0, 0.7); padding: 0.3rem 0.6rem; border-radius: 8px; white-space: nowrap; text-shadow: 1px 1px 2px rgba(0,0,0,0.8); pointer-events: none; }
        .house.show-family .house-family { opacity: 1; }

        /* INFO-PANEL */
        .info-panel { background: rgba(255, 255, 255, 0.85); border-radius: 25px; padding: 1.5rem; box-shadow: 0 10px 40px rgba(0,0,0,0.15); height: fit-content; position: sticky; top: 100px; border: 4px solid #fff; }
        .info-title { font-family: 'Orbitron', sans-serif; font-size: 1.4rem; font-weight: 700; color: #2E7D32; margin-bottom: 1.2rem; text-align: center; text-shadow: 2px 2px 4px rgba(0,0,0,0.1); }
        .info-item { background: #fff; padding: 1rem; border-radius: 15px; margin-bottom: 1rem; border: 3px solid #4CAF50; box-shadow: 0 4px 15px rgba(76, 175, 80, 0.15); }
        .info-label { font-weight: 700; color: #666; font-size: 0.95rem; margin-bottom: 0.4rem; }
        .hash-calculator { background: linear-gradient(135deg, #e3f2fd 0%, #fff 100%); border-color: #2196F3; }
        .calculator-input { width: 100%; border: 2px solid #ccc; border-radius: 10px; padding: 0.7rem; font-family: 'Rajdhani', sans-serif; font-size: 1.1rem; font-weight: 600; margin-bottom: 0.7rem; transition: border-color 0.3s ease; }
        .calculator-input:focus { outline: none; border-color: #667eea; }
        .calculator-button { width: 100%; padding: 0.8rem; background: linear-gradient(135deg, #4CAF50 0%, #2E7D32 100%); color: white; border: none; border-radius: 10px; font-family: 'Orbitron', sans-serif; font-weight: 700; font-size: 1rem; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 12px rgba(76, 175, 80, 0.3); margin-top: 0.5rem; }
        .calculator-button:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(76, 175, 80, 0.4); }
        .calculator-button:disabled { background: #ccc; cursor: not-allowed; transform: none; box-shadow: none; }
        .calculator-result { margin-top: 1rem; padding: 0.8rem; background: #f8f9fa; border: 2px dashed #4CAF50; border-radius: 10px; text-align: center; font-family: 'Orbitron', sans-serif; font-weight: 700; color: #2E7D32; font-size: 1.1rem; }

        /* List Group */
        .family-list-container { max-height: 250px; padding: 0 5px; overflow-y: auto; }
        .list-group-item.to-do-family { cursor: pointer; font-weight: 700; transition: all 0.2s ease; font-size: 1.1rem; border: 2px solid #aab8c2; margin-bottom: 0.5rem; border-radius: 10px !important; }
        .list-group-item.to-do-family:hover:not(.placed):not(.disabled) { background: #e9ecef; border-color: #667eea; }
        .list-group-item.to-do-family.active { background: #667eea; color: white; border-color: #667eea; transform: scale(1.03); z-index: 10; }
        .list-group-item.to-do-family.list-group-item-success { opacity: 0.3; background: #e0e0e0; cursor: not-allowed; text-decoration: line-through; }
        .list-group-item.to-do-family.disabled { opacity: 0.5; cursor: not-allowed; }

        .info-value { font-family: 'Orbitron', sans-serif; font-size: 1.6rem; font-weight: 900; color: #2E7D32; }

        /* Success Overlay */
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

        @media (max-width: 1200px) { .game-area { grid-template-columns: 1fr; } .major-mike-section, .info-panel { position: static; } }
        @media (max-width: 768px) { .houses-row { grid-template-columns: repeat(3, 1fr); } }
    </style>
</head>
<body>
<div class="sky-section">
    <div class="cloud" style="width: 120px; height: 60px; top: 8%; animation-delay: 0s;"></div>
    <div class="cloud" style="width: 150px; height: 70px; top: 18%; animation-delay: 10s;"></div>
    <div class="cloud" style="width: 100px; height: 50px; top: 28%; animation-delay: 20s;"></div>
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
                </div>
                <div class="dialogue-continue" id="dialogueContinue">
                    Klicken oder Enter ↵
                </div>
            </div>
        </div>
        <div class="houses-grid">
            <h2 class="grid-title">🏘️ Level 5: Quadratic Probing</h2>
            <?php
            // Assets
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
            $houseAssets = [];
            for ($i = 0; $i < 10; $i++) {
                $houseAssets[$i] = $housePairs[array_rand($housePairs)];
            }
            ?>
            <div class="street-block">
                <div class="houses-row">
                    <?php for ($i = 0; $i < 5; $i++): ?>
                        <div class="house" data-house="<?php echo $i; ?>">
                            <img src="./assets/<?php echo $houseAssets[$i]['empty']; ?>" class="house-icon">
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
                        <div class="house" data-house="<?php echo $i; ?>">
                            <img src="./assets/<?php echo $houseAssets[$i]['empty']; ?>" class="house-icon">
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
            <div class="info-item">
                <div class="info-label">Einziehende Familien:</div>
                <div class="family-list-container">
                    <ul id="familienListe" class="list-group">
                        <li class="list-group-item to-do-family disabled" data-family="Levi">Levi</li>
                        <li class="list-group-item to-do-family disabled" data-family="Emil">Emil</li>
                        <li class="list-group-item to-do-family disabled" data-family="Lars">Lars</li>
                        <li class="list-group-item to-do-family disabled" data-family="Thomas">Thomas</li>
                        <li class="list-group-item to-do-family disabled" data-family="Noah">Noah</li>
                    </ul>
                </div>
            </div>
            <div class="info-item hash-calculator">
                <label class="info-label">Bewohnername:</label>
                <input type="text" id="nameInput" class="calculator-input" placeholder="Wähle einen Namen..." value="" autocomplete="off" readonly>
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
<div class="success-overlay" id="successOverlay">
    <div class="success-modal">
        <div class="success-icon">🎉</div>
        <h2 class="success-title">Familie gefunden!</h2>
        <p class="success-message" id="successMessage">Vielen Dank! Nun ist auch dieser Stadtteil fertig.</p>
        <div class="success-stats">
            <div class="stat-box">
                <div class="stat-label">Versuche</div>
                <div class="stat-value" id="finalAttempts">0</div>
            </div>
            <div class="stat-box">
                <div class="stat-label">Familien eingetragen</div>
                <div class="stat-value" id="finalOccupied">5</div>
            </div>
        </div>
        <div class="success-buttons">
            <button class="btn-secondary" onclick="restartLevel()">↻ Nochmal spielen</button>
            <button class="btn-primary" onclick="nextLevel()">Weiter zu Level 6 →</button>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function() {
        // --- Setup ---
        const HASH_SIZE = 10;
        $('#nameInput').val('');
        const families = ["Levi", "Emil", "Lars", "Thomas", "Noah"];

        // Assets
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

        // --- State ---
        let stadt = new Array(HASH_SIZE).fill(null);
        let currentFamilyIndex = 0;
        let selectedFamily = null;
        let gameStarted = false;
        let searchMode = false;
        let occupiedHouses = 0;
        let attempts = 0;

        // --- Dialoge (NEU: Weicherer Einstieg) ---
        const dialogues = [
            "Willkommen zurück! Das 'Linear Probing' aus dem letzten Stadtteil hat leider zu 'Clustern' geführt – also Grüppchenbildung, die die Suche verlangsamt.",
            "Deshalb nutzen wir heute <strong>Quadratic Probing</strong>. Wenn ein Haus besetzt ist, gehen wir nicht einfach zum Nachbarn (+1, +2...), sondern springen in Quadrat-Schritten weiter: +1² (1), +2² (4), +3² (9) usw.",
            "Dadurch verteilen sich die Bewohner viel besser in der Stadt. Probieren wir es direkt aus! Klicke auf 'Levi' in der Liste."
        ];
        let currentDialogue = 0;
        let isFading = false;

        // --- Logic Helper ---
        function getHash(key, size) {
            let sum = 0;
            for (let i = 0; i < key.length; i++) sum += key.charCodeAt(i);
            return sum % size;
        }

        function quadraticProbing(key, size, stadtArray) {
            let hash = getHash(key, size);
            let i = 1;
            let steps = [hash];
            let position = hash;

            while (stadtArray[position] !== null) {
                position = (hash + Math.pow(i, 2)) % size;
                steps.push(position);
                i++;
                if(i > 20) break;
            }
            return { finalIndex: position, steps: steps };
        }

        function setHouseAsset(houseElement, isFilled) {
            const currentAsset = houseElement.find('.house-icon').attr('src');
            const assetName = currentAsset.split('/').pop();
            let pair = housePairs.find(p => p.empty === assetName || p.filled === assetName) || housePairs[0];
            const newAsset = isFilled ? pair.filled : pair.empty;
            houseElement.find('.house-icon').attr('src', `./assets/${newAsset}`);
        }

        function clearMarkers() {
            $('.house').removeClass('highlight-target quadratic-target');
        }

        // --- Dialog Steuerung ---
        function nextDialogueStep() {
            if (isFading) return;

            if (currentDialogue < dialogues.length) {
                isFading = true;
                $('#dialogueText').fadeOut(200, function() {
                    $(this).html(dialogues[currentDialogue]); // .html für Fettdruck
                    currentDialogue++;
                    $(this).fadeIn(200, function() { isFading = false; });
                });
            } else {
                // Intro fertig -> Spiel starten
                $('#dialogueContinue').fadeOut();
                gameStarted = true;
                initFamilyList();
            }
        }

        // --- Main Game Loop ---
        function initFamilyList() {
            if (currentFamilyIndex >= families.length) {
                startSearchPhase();
                return;
            }
            // Listen-Reset
            $('.to-do-family').addClass('disabled').off('click');

            const currentFamily = families[currentFamilyIndex];
            const $item = $(`.to-do-family[data-family="${currentFamily}"]`);

            // Aktivieren
            $item.removeClass('disabled').css('opacity', '1').on('click', handleFamilyClick);
        }

        function handleFamilyClick() {
            if (!gameStarted || searchMode) return;

            // UI Update
            $('.to-do-family').removeClass('active');
            $(this).addClass('active');
            selectedFamily = $(this).data("family");
            // Reset
            clearMarkers();
            $('#nameInput').val($(this).data('family'));
            $('#hashResult').text('Ergebnis ...');
            $('#hashButton').prop('disabled', false); // Button freischalten

            // Kontext-Texte
            if (selectedFamily === "Thomas") {
                $('#dialogueText').text("Achtung bei Thomas! Hier wird gleich eine Kollision passieren. Klicke auf Berechnen.");
            } else if (selectedFamily === "Noah") {
                $('#dialogueText').text("Auch bei Noah müssen wir aufpassen. Berechne seine Position!");
            } else {
                $('#dialogueText').text(`Okay, Familie ${selectedFamily}. Berechne jetzt die Hausnummer!`);
            }
        }

        // Klick: Berechnen
        $('#hashButton').click(function() {
            selectedFamily = $('#nameInput').val();
            console.log(selectedFamily);
            console.log(gameStarted);
            if (!gameStarted || !selectedFamily || selectedFamily === '' || selectedFamily === undefined) return;
            console.log("tewt");
            clearMarkers();

            // Such-Modus Logik (Thomas suchen)
            if (searchMode) {
                if (selectedFamily === "Thomas"){
                    $('#dialogueText').text(`Der Rechner sagt, Thomas wohnt in Haus 0. Prüfe das Haus!`);
                    $('#hashResult').text(`Hausnummer: ${getHash(selectedFamily, HASH_SIZE)}`);

                }else{
                    $('#dialogueText').text(`Derzeit suchen wir nach Thomas und nicht nach ${selectedFamily}.`);
                    selectedFamily = null;
                }
                return;
            }

            const calcHash = getHash(selectedFamily, HASH_SIZE);
            $('#hashResult').text(`Hausnummer: ${calcHash}`);
            $(this).prop('disabled', true); // Deaktivieren -> User muss jetzt Haus klicken

            // Berechnung
            const result = quadraticProbing(selectedFamily, HASH_SIZE, stadt);
            const steps = result.steps;
            const target = result.finalIndex;



            // Einzugs-Modus Logik
            if (selectedFamily === "Thomas") {
                // Kollision visualisieren (Tutorial)
                steps.slice(0, -1).forEach(idx => $(`.house[data-house=${idx}]`).addClass('quadratic-target')); // ROT
                $(`.house[data-house=${target}]`).addClass('highlight-target'); // GOLD

                $('#dialogueText').html(`Haus 0 ist belegt!<br>Wir nutzen <b>Quadratic Probing</b>:<br>1. Versuch: 0 + 1² = 1 (Belegt 🔴)<br>2. Versuch: 0 + 2² = 4 (Frei 🟡).<br>Thomas zieht in Haus 4!`);
            }
            else if (steps.length > 1) {
                // Andere Kollision
                steps.slice(0, -1).forEach(idx => $(`.house[data-house=${idx}]`).addClass('quadratic-target'));
                $(`.house[data-house=${target}]`).addClass('highlight-target');
                $('#dialogueText').text("Kollision! Wir nutzen Quadratic Probing (Hash + 1², + 2²...), bis wir ein freies Haus finden.");
            }
            else {
                // Keine Kollision
                $('#dialogueText').text(`Haus ${target} ist frei. Klicke darauf!`);
            }
        });

        // Klick: Haus
        $('.house').click(function() {
            if (!gameStarted) return;
            const houseIdx = parseInt($(this).data('house'));

            // --- SUCH MODUS (Ende) ---
            if (searchMode) {
                if(selectedFamily === null) return;
                const occupant = stadt[houseIdx];
                if (occupant === 'Thomas') {
                    // GEWONNEN
                    $(this).addClass('show-family').find('.house-family').text('Thomas');
                    $('#successOverlay').css('display', 'flex');
                    $('#finalAttempts').text(attempts);
                } else if (occupant) {
                    attempts++;
                    $(this).addClass('show-family').find('.house-family').text(occupant);
                    $('#dialogueText').text(`Nein, hier wohnt ${occupant}. Wir suchen Thomas!`);
                } else {
                    attempts++;
                    $('#dialogueText').text("Hier wohnt niemand.");
                }
                return;
            }

            // --- PLATZIERUNGS MODUS ---
            if (!selectedFamily) {
                $('#dialogueText').text("Bitte wähle erst einen Namen aus der Liste.");
                return;
            }
            if ($('#hashResult').text().includes('Ergebnis')) {
                $('#dialogueText').text("Bitte klicke erst auf 'Berechnen'!");
                return;
            }

            const result = quadraticProbing(selectedFamily, HASH_SIZE, stadt);

            if (houseIdx !== result.finalIndex) {
                $('#dialogueText').text("Falsches Haus! Achte auf die Berechnung und die Markierungen.");
                return;
            }

            // Einziehen lassen
            if (stadt[houseIdx] === null) {
                stadt[houseIdx] = selectedFamily;
                setHouseAsset($(this), true);
                $(this).addClass('checked');
                $(this).find('.house-family').text(selectedFamily);

                // Aufräumen
                clearMarkers();
                $(`.to-do-family[data-family="${selectedFamily}"]`).removeClass('active').addClass('list-group-item-success').css("opacity", 1);

                occupiedHouses++;
                $('#occupiedCount').text(`${occupiedHouses} / 5`);
                $('#nameInput').val('');
                $('#hashResult').text('Ergebnis ...');
                selectedFamily = null;

                // Weiter
                currentFamilyIndex++;
                if (currentFamilyIndex < families.length) {
                    $('#dialogueText').text("Sehr gut! Nächster Bewohner.");
                    setTimeout(initFamilyList, 500);
                } else {
                    startSearchPhase();
                }
            }
        });

        function startSearchPhase() {
            searchMode = true;
            $('#dialogueText').text("Alle Häuser sind voll! Aber wo wohnt Thomas? Ich brauche seine Unterschrift.");
            $('#majorMikeImage').attr('src', './assets/card_major.png');

            // Input manipulieren
            $('.to-do-family').removeClass('active').addClass('disabled');
            $('#nameInput').prop('readonly', false);

            // Button wieder freigeben
            $('#hashButton').prop('disabled', false).text('Berechne Haus-Nr.');
            $('#hashResult').text('Suche...');
        }

        // --- Inputs ---
        $(document).keydown(function(e) {
            if ((e.key === 'Enter' || e.key === ' ') && !gameStarted) {
                nextDialogueStep();
            }
        });
        $('.dialogue-box').click(function() {
            if (!gameStarted) {
                nextDialogueStep();
            }
        });

        // --- Global ---
        window.restartLevel = function() { location.reload(); };
        window.nextLevel = function() { window.location.href = 'Level-Auswahl?completed=5&next=6'; };

        // Start
        nextDialogueStep();
    });
</script>
</body>
</html>