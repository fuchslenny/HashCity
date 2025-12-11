<?php
/**
 * HashCity - Level 12: Das Finale (Hardcore Mode + Smart Probing + Level 7/11 Features)
 *
 * Updates:
 * - "Stadtplanung" statt "Verwaltung"
 * - Smart Probing: Erlaubt direktes Klicken des Zielhauses ODER Kollisions-Feedback beim Klicken besetzter Häuser.
 * - Integration Level 11 Animation & Level 7 Double Hashing Design.
 */

$final_residents = [
        "Julia", "Max", "Sven", "Lara", "Tom",
        "Sarah", "Ben", "Lea", "Paul", "Anna",
        "Jan", "Tim", "Lisa", "Kevin", "Eva",
        "Nico", "Maja", "Olaf", "Nina", "Kai",
        "Ute", "Roy", "Pia", "Ali", "Zoe",
        "Leo", "Amy", "Ian", "Rex", "Sam"
];
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HashCity - Level 12: FINALE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Rajdhani:wght@300;500;700&display=swap" rel="stylesheet">
    <style>
        /* --- Basis Styles --- */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Rajdhani', sans-serif; overflow-x: hidden; min-height: 100vh; position: relative; background: #4CAF50; }

        .sky-section { position: fixed; top: 0; left: 0; width: 100%; height: 50%; background: linear-gradient(180deg, #1E88E5 0%, #B0D4E3 100%); z-index: 0; }
        .grass-section { position: fixed; bottom: 0; left: 0; width: 100%; height: 50%; background: linear-gradient(180deg, #76B947 0%, #2E7D32 100%); z-index: 0; }
        .cloud { position: absolute; background: rgba(255, 255, 255, 0.7); border-radius: 100px; opacity: 0.8; animation: cloudFloat 60s linear infinite; }
        @keyframes cloudFloat { 0% { left: -200px; } 100% { left: 110%; } }

        .game-header { background: transparent; padding: 1rem 2rem; position: relative; top: 0; z-index: 1000; backdrop-filter: blur(5px); }
        .back-btn { padding: 0.7rem 1.3rem; background: rgba(255, 255, 255, 0.9); border: 2px solid rgba(102, 126, 234, 0.5); border-radius: 30px; font-weight: 700; color: #667eea; text-decoration: none; display: inline-block; transition: all 0.3s; font-family: 'Orbitron'; }
        .back-btn:hover { background: #667eea; color: #fff; transform: scale(1.05); }

        .game-container { max-width: 1800px; margin: 2rem auto; padding: 0 2rem; position: relative; z-index: 1; }
        .game-area { display: grid; grid-template-columns: 280px 1fr 350px; gap: 2rem; min-height: 70vh; }

        /* Mike Section */
        .major-mike-section { background: rgba(255, 255, 255, 0.85); border-radius: 25px; padding: 1.5rem; box-shadow: 0 10px 40px rgba(0,0,0,0.15); height: fit-content; position: sticky; top: 100px; border: 4px solid #FFD700; }
        .major-mike-avatar { width: 100%; height: 240px; border-radius: 15px; display: flex; align-items: center; justify-content: center; margin-bottom: 1rem; overflow: hidden; position: relative; }
        .major-mike-avatar img { width: 100%; height: 100%; object-fit: contain; }
        .major-mike-name { font-family: 'Orbitron', sans-serif; font-size: 1.4rem; font-weight: 900; color: #FFD700; text-align: center; margin-bottom: 1rem; text-shadow: 1px 1px 2px rgba(0,0,0,0.5); }
        .dialogue-box { background: #fff; border: 3px solid #667eea; border-radius: 20px; padding: 1.5rem; min-height: 180px; position: relative; box-shadow: 0 6px 20px rgba(102, 126, 234, 0.2); }
        .dialogue-text { font-size: 1.05rem; line-height: 1.7; color: #333; font-weight: 500; }

        /* Grid Area */
        .houses-grid { background: rgba(255, 255, 255, 0.85); border-radius: 25px; padding: 2rem; box-shadow: 0 10px 40px rgba(0,0,0,0.15); border: 4px solid #fff; overflow: hidden; position: relative; }
        .grid-title { font-family: 'Orbitron', sans-serif; font-size: 1.8rem; font-weight: 900; color: #2E7D32; text-align: center; margin-bottom: 0.5rem; }
        .mode-badge { text-align: center; margin-bottom: 1.5rem; background: #667eea; color: white; display: inline-block; padding: 0.3rem 1rem; border-radius: 20px; font-weight: bold; position: relative; left: 50%; transform: translateX(-50%); }

        .street-block { position: relative; margin-bottom: 2rem; transition: opacity 0.5s; }
        .street-block.hidden { display: none; }

        .houses-row { display: grid; grid-template-columns: repeat(10, 1fr); gap: 0.5rem; margin-bottom: 0.5rem; padding: 0 0.5rem; position: relative; z-index: 2; transition: all 0.5s ease; }
        .street { width: 100%; height: 60px; background-image: url('./assets/Strasse.svg'); background-size: cover; background-position: center; position: relative; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.15); z-index: 1; margin-top: -15px; }
        .street::before { content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(180deg, #4a4a4a 0%, #2a2a2a 100%); border-radius: 8px; z-index: -1; }
        .street::after { content: ''; position: absolute; top: 50%; left: 0; width: 100%; height: 4px; background: repeating-linear-gradient(90deg, #fff 0px, #fff 30px, transparent 30px, transparent 50px); transform: translateY(-50%); z-index: 2; }
        .street.hidden { display: none; }

        .house {
            position: relative;
            display: flex;
            flex-direction: column-reverse;
            align-items: center;
            cursor: pointer;
            transition: transform 0.2s;
        }

        .house.hidden { display: none; }
        .house:hover { transform: translateY(-5px) scale(1.05); z-index: 10; }

        .house.collision-highlight { box-shadow: 0 0 15px 5px rgba(211, 47, 47, 0.8); border-radius: 50%; animation: shake 0.4s; }
        .house.found-highlight { box-shadow: 0 0 25px 10px #4CAF50; border-radius: 50%; transform: scale(1.2) !important; z-index: 20; }

        @keyframes shake { 0%, 100% { transform: translateX(0); } 25% { transform: translateX(-5px); } 75% { transform: translateX(5px); } }

        /* Pop-In Animation (from Level 11) */
        @keyframes popIn {
            0% { transform: scale(0.5); opacity: 0; }
            70% { transform: scale(1.2); opacity: 1; }
            100% { transform: scale(1); opacity: 1; }
        }
        .house.pop-in { animation: popIn 0.4s ease-out forwards; }

        /* Images */
        .house-icon {
            width: 100%;
            height: 100%;
            object-fit: contain;
            transition: all 0.3s ease;
            filter: drop-shadow(0 4px 8px rgba(0,0,0,0.2));
        }
        .img-house-base {
            width: 100%;       /* Füllt die Breite des Eltern-Containers */
            height: auto;     /* Höhe automatisch anpassen, um das Seitenverhältnis zu wahren */
            max-width: 100%;  /* Maximale Breite begrenzen */
            max-height: 100%; /* Maximale Höhe begrenzen */
            object-fit: contain; /* Bild wird vollständig angezeigt, ohne Verzerrung */
            display: block;
            position: relative;
            z-index: 1;
            filter: drop-shadow(0 4px 6px rgba(0,0,0,0.3));
        }
        .img-house-extension {
            width: 100%;       /* Füllt die Breite des Eltern-Containers */
            height: auto;     /* Höhe automatisch anpassen */
            max-width: 100%;  /* Maximale Breite begrenzen */
            object-fit: contain; /* Bild wird vollständig angezeigt */
            display: block;
            position: relative;
            margin-bottom: -5px; /* Überlappung für den "Stapel"-Effekt */
            z-index: 10;
        }
        .img-house-extension.top-floor {animation: fallDown 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);}

        @keyframes fallDown { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }

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
        .house-occupant { display: none !important; }

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
        }
        @keyframes pulseRed { 0% { box-shadow: 0 0 0 0 rgba(211, 47, 47, 0.4); } 70% { box-shadow: 0 0 0 10px rgba(211, 47, 47, 0); } 100% { box-shadow: 0 0 0 0 rgba(211, 47, 47, 0); } }

        .hash-calculator {
            background: linear-gradient(135deg, #e3f2fd 0%, #fff 100%);
            border-color: #2196F3;
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
        .calculator-button { width: 100%; padding: 0.8rem; background: linear-gradient(135deg, #4CAF50 0%, #2E7D32 100%); color: white; border: none; border-radius: 10px; font-family: 'Orbitron', sans-serif; font-weight: 700; font-size: 1rem; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 12px rgba(76, 175, 80, 0.3); margin-top: 0.5rem; }
        .calculator-button:hover:not(:disabled) { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(76, 175, 80, 0.4); }
        .calculator-button:disabled { background: #ccc; cursor: not-allowed; box-shadow: none; transform: none; }
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

        .expand-btn { background: linear-gradient(135deg, #FF9800 0%, #FF5722 100%); font-size: 1.1rem; margin-bottom: 1.5rem; }

        /* --- LEVEL 7 SPECIAL STYLES --- */
        .step-calculator { background: linear-gradient(135deg, #fff3e0 0%, #fff 100%); border-color: #FF9800; opacity: 0.5; pointer-events: none; transition: opacity 0.3s; display: none; }
        .step-calculator.active { opacity: 1; pointer-events: all; }
        .btn-secondary-calc { background: linear-gradient(135deg, #FF9800 0%, #FFB74D 100%); }
        .calc2-button { padding: 0.6rem 1.5rem; border: none; border-radius: 30px; font-family: 'Orbitron', sans-serif; font-weight: 700; font-size: 0.9rem; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(0,0,0,0.1); color: white; width: 100%; margin-top: 0.5rem; }
        .hash-result-value { font-family: 'Orbitron', sans-serif; font-size: 2.2rem; font-weight: 900; color: #667eea; text-align: center; margin: 0.5rem 0; }

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
        .list-group-item.active { background: #667eea; color: white; transform: scale(1.02); border: none; box-shadow: 0 4px 10px rgba(0,0,0,0.2); }
        .list-group-item.disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        .list-group-item.done {
            opacity: 1;
            background: #e0e0e0;
            cursor: not-allowed;
            text-decoration: line-through;
        }
        .list-group-item.search-target { background: #FF9800; color: white; animation: pulseSearch 2s infinite; border: 2px solid #E65100; }
        @keyframes pulseSearch { 0% { transform: scale(1); } 50% { transform: scale(1.03); } 100% { transform: scale(1); } }

        .mode-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.9); z-index: 3000; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(8px); }
        .mode-box { background: white; padding: 3rem; border-radius: 30px; max-width: 800px; width: 90%; text-align: center; border: 5px solid #667eea; animation: zoomIn 0.4s ease; }
        @keyframes zoomIn { from { transform: scale(0.8); opacity: 0; } to { transform: scale(1); opacity: 1; } }
        .mode-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem; margin-top: 2rem; }
        .mode-card { background: #f8f9fa; padding: 1.5rem; border-radius: 15px; border: 2px solid #ddd; cursor: pointer; transition: all 0.3s; }
        .mode-card:hover { transform: translateY(-5px); border-color: #667eea; box-shadow: 0 10px 20px rgba(102, 126, 234, 0.2); }
        .mode-card h4 { color: #667eea; font-weight: 900; font-family: 'Orbitron'; }

        .overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.9); display: none; align-items: center; justify-content: center; z-index: 4000; }
        .modal-box { background: white; border-radius: 30px; padding: 3rem; text-align: center; border: 5px solid; max-width: 600px; box-shadow: 0 0 50px rgba(0,0,0,0.5); }
        .modal-win { border-color: #4CAF50; }
        .modal-fail { border-color: #D32F2F; }
        .dialogue-continue { position: absolute; bottom: 10px; right: 15px; font-size: 0.85rem; color: #667eea; font-style: italic; font-weight: 700; animation: blink 1.5s infinite; }

    </style>
</head>
<body>

<div class="mode-overlay" id="modeOverlay">
    <div class="mode-box">
        <h1 style="font-family: 'Orbitron'; font-weight: 900; color: #333;">Level 12: Die finale Prüfung</h1>
        <p>30 Einwohner. Keine Hilfestellung. Du bist auf dich allein gestellt.</p>
        <div class="mode-grid">
            <div class="mode-card" onclick="selectMode('linear')">
                <h4>Linear Probing</h4>
                <p>Kollision? +1, +2, +3...</p>
                <small class="text-muted">(Level 3)</small>
            </div>
            <div class="mode-card" onclick="selectMode('quadratic')">
                <h4>Quadratic Probing</h4>
                <p>Kollision? +1², +2², +3²...</p>
                <small class="text-muted">(Level 5)</small>
            </div>
            <div class="mode-card" onclick="selectMode('double')">
                <h4>Double Hashing</h4>
                <p>Kollision? 2. Hash berechnet Schrittweite.</p>
                <small class="text-muted">(Level 7/8)</small>

            </div>
            <div class="mode-card" onclick="selectMode('chaining')">
                <h4>Separate Chaining</h4>
                <p>Listen (Mehrfamilienhäuser).</p>
                <small class="text-muted">(Level 9)</small>
            </div>
        </div>
    </div>
</div>

<div class="sky-section"><div class="cloud" style="top:10%;left:10%"></div></div>
<div class="grass-section"></div>

<div class="game-header">
    <a href="level-select.php" class="back-btn">Zurück</a>
</div>

<div class="game-container">
    <div class="game-area">
        <div class="major-mike-section">
            <div class="major-mike-avatar">
                <img src="./assets/card_major.png" id="mmAvatar" alt="Major Mike">
            </div>
            <div class="major-mike-name">🎖️ Major Mike</div>
            <div class="dialogue-box">
                <div class="dialogue-text" id="dialogueText">Zeig was du gelernt hast!</div>
                <div class="dialogue-continue" id="dialogueContinue">
                    Klicken oder Enter ↵
                </div>
            </div>
        </div>

        <div class="houses-grid">
            <h2 class="grid-title" id="gridTitle">🏘️ Level 12: Finale</h2>
            <div class="mode-badge" id="modeBadge">Strategie wählen...</div>

            <div class="street-block" id="block-0">
                <div class="houses-row">
                    <?php for ($i = 0; $i < 10; $i++): ?>
                        <div class="house" id="house-<?php echo $i; ?>" data-index="<?php echo $i; ?>">
                            <div class="house-number"><?php echo $i; ?></div>
                            <div class="house-occupant"></div>
                        </div>
                    <?php endfor; ?>
                </div>
                <div class="street"></div>
            </div>

            <div class="street-block hidden" id="block-1">
                <div class="houses-row">
                    <?php for ($i = 10; $i < 20; $i++): ?>
                        <div class="house hidden" id="house-<?php echo $i; ?>" data-index="<?php echo $i; ?>">
                            <div class="house-number"><?php echo $i; ?></div>
                            <div class="house-occupant"></div>
                        </div>
                    <?php endfor; ?>
                </div>
                <div class="street hidden"></div>
            </div>

            <?php for ($b = 2; $b < 4; $b++): ?>
                <div class="street-block hidden" id="block-<?php echo $b; ?>">
                    <div class="houses-row">
                        <?php for ($i = 0; $i < 10; $i++): $hNum = $b*10 + $i; ?>
                            <div class="house hidden" id="house-<?php echo $hNum; ?>" data-index="<?php echo $hNum; ?>">
                                <div class="house-number"><?php echo $hNum; ?></div>
                                <div class="house-occupant"></div>
                            </div>
                        <?php endfor; ?>
                    </div>
                    <div class="street hidden"></div>
                </div>
            <?php endfor; ?>
        </div>

        <div class="info-panel">
            <h3 class="info-title">📊 Stadtplanung</h3>
            <div class="info-item">
                <div class="info-label">Warteschlange (<span id="queueCount">30</span>):</div>
                <div class="family-list-container">
                    <ul class="list-group" id="resList">
                        <?php foreach($final_residents as $idx => $name): ?>
                            <li class="list-group-item to-do-family disabled" id="res-<?php echo $idx; ?>" data-name="<?php echo $name; ?>">
                                <?php echo $name; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

            <div class="info-item hash-calculator">
                <div class="info-label">Bewohnername:</div>
                <input type="text" id="nameInput" class="calculator-input" placeholder="Namen eingeben..." readonly>
                <button id="hashButton" class="calculator-button">Berechne Haus-Nr.</button>
                <div class="calculator-result" id="hashResult">Ergebnis ...</div>
            </div>

            <div class="info-item step-calculator" id="stepCalcBox">
                <div class="info-label">2. Hash (Schrittweite)</div>
                <div style="font-size: 0.8rem; color: #666; margin-bottom: 5px; font-weight: 600;" id="stepFormula">(ASCII) % 5 + 1</div>
                <div class="hash-result-value" id="h2Result">-</div>
                <button id="btnCalcH2" class="calc2-button btn-secondary-calc" disabled>Sprungweite berechnen</button>
            </div>
            <div class="load-factor-box" id="lfBox">
                <div class="lf-label">Load Factor</div>
                <div class="lf-value" id="lfValue">0.00</div>
                <div class="lf-label" id="lfText">Limit: 0.75</div>
            </div>
            <button id="btnExpand" class="calculator-button expand-btn" disabled>🏗️ STADT ERWEITERN</button>
        </div>
    </div>
</div>

<div class="overlay" id="endOverlay">
    <div class="modal-box" id="endModal">
        <div style="font-size:5rem" id="endIcon">🏆</div>
        <h2 style="font-family:'Orbitron'" id="endTitle">Titel</h2>
        <p id="endMessage">Nachricht</p>
        <button class="btn btn-primary" onclick="location.reload()">Neustart</button>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    // --- Configuration ---
    const residents = <?php echo json_encode($final_residents); ?>;
    const MAX_PROBE_STEPS = 5;
    const dialogues = [
        "Nach all der Arbeit, all den Berechnungen, all den Kollisionen, den Cluster-Problemen und dem Rehashing brauche ich dringend eine Pause. Ich gehe also in den Urlaub! Du sollst ab jetzt die komplette Stadtverwaltung übernehmen. Die Stadt ist groß geworden, gut strukturiert – und du bist mittlerweile mehr als qualifiziert, alles selbst zu regeln.",

        "Ein Stadtteil ist noch frei, den du nun vervollständigen sollst, während ich im Urlaub bin. Natürlich habe ich noch ein paar Regeln für dich:",

        "der Load Factor muss unter 0,75 bleiben, " +
        "dir stehen maximal 40 Häuser zur Verfügung, " +
        "du hast zwei Rehashings zur Verfügung, " +
        "beim Einfügen oder Suchen eines Bewohners sind maximal fünf Schritte erlaubt.",

        "Du entscheidest also: " +
        "wann gerehasht wird, " +
        "wie du die Bewohner verteilst " +
        "und wie du sicherstellst, dass die Stadt effizient bleibt. " +
        "Ich gebe dir nur die Regeln vor – die Umsetzung liegt bei dir.",

        "Damit steht deiner erfolgreichen Verwaltung des letzten Stadtteils nichts mehr im Weg. Ich verabschiede mich jetzt offiziell in den Urlaub. Mach du hier weiter.",

        "Viel Erfolg und herzlichen Glückwunsch! Du hast das Finale erreicht. Jetzt zeig, was du gelernt hast."
    ];
    const urlaubmsg = "Major Mike befindet sich derzeit im Urlaub...";


    // Assets
    const housePairsProbing = [
        { base: "WohnhauBlauBraunLeerNeu.svg", fill: "WohnhauBlauBraunBesetztNeu.svg" },
        { base: "WohnhauBlauGrauLeerNeu.svg", fill: "WohnhauBlauGrauBesetztNeu.svg" },
        { base: "WohnhauBlauRotLeerNeu.svg", fill: "WohnhauBlauRotBesetztNeu.svg" },
        { base: "WohnhauGelbBraunLeerNeu.svg", fill: "WohnhauGelbBraunBesetztNeu.svg" },
        { base: "WohnhauGruenBraunLeerNeu.svg", fill: "WohnhauGruenBraunBesetztNeu.svg" },
    ];
    const housePairsChaining = [
        {empty: "WH2BlauBraunLeer.svg", filled: "WH2BlauBraun.svg", extension: "WHBlauBraunErweiterung.svg"},
        {empty: "WH2BlauGrauLeer.svg", filled: "WH2BlauGrau.svg", extension: "WHBlauGrauErweiterung.svg"},
        {empty: "WH2BlauRotLeer.svg", filled: "WH2BlauRot.svg", extension: "WHBlauRotErweiterung.svg"},
        {empty: "WH2GrauBraunLeer.svg", filled: "WH2GrauBraun.svg", extension: "WHGrauBraunErweiterung.svg"},
        {empty: "WH2GruenBraunLeer.svg", filled: "WH2GruenBraun.svg", extension: "WHGruenBraunErweiterung.svg"},
        {empty: "WH2GruenGrauLeer.svg", filled: "WH2GruenGrau.svg", extension: "WHGruenGrauErweiterung.svg"},
        {empty: "WH2GelbBraunLeer.svg", filled: "WH2GelbBraun.svg", extension: "WHGelbBraunErweiterung.svg"},
        {empty: "WH2GelbRotLeer.svg", filled: "WH2GelbRot.svg", extension: "WHGelbRotErweiterung.svg"},
        {empty: "WH2RotBraunLeer.svg", filled: "WH2RotBraun.svg", extension: "WHRotBraunErweiterung.svg"},
        {empty: "WH2RotRotLeer.svg", filled: "WH2RotRot.svg", extension: "WHRotRotErweiterung.svg"}
    ];

    // --- Global State ---
    let gameMode = null;
    let currentCapacity = 10;
    let placedResidents = [];
    let currentResIdx = 0;
    let isSearchPhase = false;
    let searchQueue = [];
    let currentSearchTarget = null;
    let h1 = null, h2 = null;
    let expansionCount = 0;
    let isFading = false;
    const maxExpansions = 2;
    let dialogueIdx = 1;

    // --- Initialization ---
    function selectMode(mode) {
        gameMode = mode;
        $('#modeOverlay').fadeOut();
        let modeName = "";

        // UI Reset
        $('.step-calculator').hide();

        if (mode === 'linear') modeName = "Linear Probing";
        if (mode === 'quadratic') modeName = "Quadratic Probing";
        if (mode === 'double') {
            modeName = "Double Hashing";
            $('.step-calculator').show();
            updateStepFormula();
        }
        if (mode === 'chaining') { modeName = "Separate Chaining"; $('#lfText').text("∞ (Kein Limit)"); }

        $('#modeBadge').text(modeName);
        $('#dialogueText').text(`Modus: ${modeName}. Keine Hilfen. Viel Erfolg!`);
        initVisuals();
        updateStats();
        showDialogue(dialogues[0]);
    }

    $('#dialogueText').click(() => {
        console.log("test");
        advanceDialogue();
    });
    $(document).keydown(e => { if((e.key === 'Enter')) advanceDialogue(); });

    function advanceDialogue() {
        if(isFading || dialogueIdx>dialogues.length) return;
        if (dialogueIdx < dialogues.length) {
            showDialogue(dialogues[dialogueIdx]);
            dialogueIdx++;
        }else{
            dialogueIdx++;
            highlightNextResident();
            $('#dialogueContinue').hide();
            showDialogue(urlaubmsg);
        }
    }

    function showDialogue(text) {
        if (isFading && text !== dialogues[0]) return;
        isFading = true;
        $('#dialogueText').fadeOut(150, function() {
            $(this).html(text).fadeIn(150, function() { isFading = false; });
        });
    }

    function updateStepFormula() {
        let h2Mod = Math.floor(currentCapacity / 2);
        $('#stepFormula').text(`(ASCII) % ${h2Mod} + 1`);
    }

    function initVisuals() {
        $('.house:lt(10)').each(function() {
            assignRandomAsset($(this));
        });
    }

    function assignRandomAsset($el) {
        const arr = (gameMode === 'chaining') ? housePairsChaining : housePairsProbing;
        const pair = arr[Math.floor(Math.random() * arr.length)];
        $el.data('pair', pair);
        $el.empty();
        $el.append(`<div class="house-number">${$el.data('index')}</div>`);
        $el.append(`<div class="house-occupant"></div>`);
        if(gameMode === 'chaining') $el.append(`<img src="./assets/${pair.empty}" class="img-house-base">`);
        else $el.append(`<img src="./assets/${pair.base}" class="house-icon">`);
    }

    function updateHouseVisual($el, count) {
        const idx = $el.data('index');
        const pair = $el.data('pair');
        if (!pair) return;

        $el.empty();
        $el.append(`<div class="house-number">${idx}</div>`);
        $el.append(`<div class="house-occupant"></div>`);

        if (gameMode === 'chaining') {

            if (count > 1) {
                $el.append(`<img src="./assets/${pair.filled}" class="img-house-base">`);
                for (let i = 1; i < count; i++) {
                    const $extension = $(`<img src="./assets/${pair.extension}" class="img-house-extension">`);
                    if (i === count - 1) { // Nur die oberste Etage
                        $extension.addClass('top-floor');
                    }
                    $el.append($extension);
                }
            }else if(count === 1) {
                $el.append(`<img src="./assets/${pair.filled}" class="img-house-base">`);
            }else{
                $el.append(`<img src="./assets/${pair.empty}" class="img-house-base">`);
            }
            let names = placedResidents.filter(r => r.houseIndex == idx).map(r => r.name).join(', ');
            $el.find('.house-occupant').text(names);
        } else {
            let img = (count > 0) ? pair.fill : pair.base;
            $el.append(`<img src="./assets/${img}" class="house-icon">`);
            if(count > 0) {
                let p = placedResidents.find(r => r.houseIndex == idx);
                if(p) $el.find('.house-occupant').text(p.name);
            }
        }
    }

    function getAsciiSum(name) {
        let sum = 0;
        for(let i=0; i<name.length; i++) sum += name.charCodeAt(i);
        return sum;
    }

    // --- Game Flow ---
    function highlightNextResident() {
        if(currentResIdx >= residents.length) {
            initSearchPhase();
            return;
        }
        $('.list-group-item').removeClass('active');
        $(`#res-${currentResIdx}`).removeClass('disabled').addClass('active');

        let container = $('.family-list-container');
        let scrollTo = $(`#res-${currentResIdx}`);
        let name = isSearchPhase ? currentSearchTarget.name : residents[currentResIdx];
        if(scrollTo.length) {
            container.animate({ scrollTop: scrollTo.offset().top - container.offset().top + container.scrollTop() - 50 });
        }

        h1 = null; h2 = null;
        $('#hashResult').text('Ergebnis ...');
        $('#h2Result').text('-');
        $('#stepCalcBox').removeClass('active');
        $('#hashButton').prop('disabled', false).text('1. Hash Berechnen');
        $('#btnCalcH2').prop('disabled', true);
        $('#nameInput').val(name);
        $('.house').removeClass('collision-highlight found-highlight');
        updateStats();
    }

    function updateStats() {
        let lf = 0;
        if (gameMode === 'chaining') {
            lf = placedResidents.length / currentCapacity;
        } else {
            const uniqueHouses = new Set(placedResidents.map(r => r.houseIndex));
            lf = uniqueHouses.size / currentCapacity;
        }

        $('#lfValue').text(lf.toFixed(2));
        $('#modBase').text(currentCapacity);

        let $lfBox = $('#lfBox');
        $lfBox.removeClass('lf-good lf-medium lf-bad');

        if (gameMode !== 'chaining') {
            if(lf > 0.75) {
                $lfBox.addClass('lf-bad');
                $('#lfText').text("Limit: 0.75 (Kritisch!)");
            } else if (lf > 0.5) {
                $lfBox.addClass('lf-medium');
                $('#lfText').text("Limit: 0.75 (Mittel)");
            } else {
                $lfBox.addClass('lf-good');
                $('#lfText').text("Limit: 0.75 (Gut)");
            }
        } else {
            $lfBox.addClass('lf-good');
        }

        if(!isSearchPhase && expansionCount < maxExpansions && h1 === null) {
            $('#btnExpand').prop('disabled', false);
        } else {
            $('#btnExpand').prop('disabled', true);
        }

        $('#queueCount').text(isSearchPhase ? searchQueue.length : residents.length - currentResIdx);
    }

    // --- EXPANSION LOGIC ---
    $('#btnExpand').click(function() {
        if(placedResidents.length === 0) {
            showDialogue("Niemand da zum Umziehen!");
            return;
        }

        $(this).prop('disabled', true).text("BAUE HÄUSER...");
        expansionCount++;
        let oldCap = currentCapacity;
        currentCapacity *= 2;
        updateStepFormula();

        showDialogue(`Erweitere Stadt auf ${currentCapacity}. Rehashing...`);

        if(currentCapacity >= 20) {
            $('#block-1').removeClass('hidden');
            $('#block-1 .street').removeClass('hidden');
        }
        if(currentCapacity >= 40) {
            $('#block-2, #block-3').removeClass('hidden');
            $('#block-2 .street, #block-3 .street').removeClass('hidden');
        }

        $('.house.hidden').each(function(i) {
            let $el = $(this);
            assignRandomAsset($el);
            setTimeout(() => {
                $el.removeClass('hidden').addClass('pop-in');
            }, i * 30);
        });

        setTimeout(() => {
            performRehashLogics(oldCap);
        }, 1500);
    });

    function performRehashLogics(oldCap) {
        let newPlacement = [];
        placedResidents.forEach(person => {
            // Re-calc logic for placement array only
            let sum = getAsciiSum(person.name);
            let pos = -1;
            if (gameMode === 'chaining') {
                pos = sum % currentCapacity;
                newPlacement.push({ name: person.name, houseIndex: pos });
            } else {
                // Calculate position simulation
                let localH1 = sum % currentCapacity;
                let localH2 = (gameMode === 'double') ? (sum % Math.floor(currentCapacity/2)) + 1 : 0;
                let step = 0;
                while(step < currentCapacity * 2) {
                    let attemptPos = -1;
                    if (gameMode === 'linear') attemptPos = (localH1 + step) % currentCapacity;
                    else if (gameMode === 'quadratic') attemptPos = (localH1 + step*step) % currentCapacity;
                    else if (gameMode === 'double') attemptPos = (localH1 + step*localH2) % currentCapacity;

                    if (!newPlacement.some(r => r.houseIndex === attemptPos)) {
                        pos = attemptPos; break;
                    }
                    step++;
                }
                if(pos !== -1) newPlacement.push({ name: person.name, houseIndex: pos });
            }
        });
        placedResidents = newPlacement;

        for (let i = 0; i < currentCapacity; i++) {
            let $house = $(`#house-${i}`);
            $house.removeClass('pop-in');
            setTimeout(() => {
                $house.addClass('pop-in');
                let count = placedResidents.filter(r => r.houseIndex === i).length;
                updateHouseVisual($house, count);
            }, i * 30);
        }

        setTimeout(() => {
            showDialogue("Umzug fertig! Alle Positionen neu berechnet.");
            $('#btnExpand').text("🏗️ STADT ERWEITERN");
            updateStats();
            highlightNextResident();
        }, currentCapacity * 30 + 500);
    }

    // --- Calc Logic ---
    $('#hashButton').click(function() {
        let name = isSearchPhase ? currentSearchTarget.name : residents[currentResIdx];
        let sum = getAsciiSum(name);
        h1 = sum % currentCapacity;
        $('#hashButton').prop('disabled', true);
    });

    $('#btnCalcH2').click(function() {
        let name = isSearchPhase ? currentSearchTarget.name : residents[currentResIdx];
        let sum = getAsciiSum(name);
        let hashSize2 = Math.floor(currentCapacity / 2);
        h2 = (sum % hashSize2) + 1;
        $('#h2Result').text(h2);
        $('#btnCalcH2').prop('disabled', true);
    });

    // --- INTELLIGENT PROBING LOGIC ---
    // Returns { path: [indices], target: int, stepsNeeded: int }
    function calculateProbingPath(startH1, name) {
        let path = [];
        let limit = currentCapacity * 2; // Safety break
        let step = 0;
        let sum = getAsciiSum(name);

        // Calculate H2 locally if needed (auto-calc for validation if not yet done)
        let localH2 = 1;
        if(gameMode === 'double') {
            let hashSize2 = Math.floor(currentCapacity / 2);
            localH2 = (sum % hashSize2) + 1;
        }

        while (step < limit) {
            let idx = -1;
            if (gameMode === 'linear') idx = (startH1 + step) % currentCapacity;
            else if (gameMode === 'quadratic') idx = (startH1 + step*step) % currentCapacity;
            else if (gameMode === 'double') idx = (startH1 + step*localH2) % currentCapacity;

            // Check occupancy (ignoring current person if in search mode logic, but here we place)
            // Note: placedResidents check
            let isOccupied = placedResidents.some(r => r.houseIndex === idx);

            if (!isOccupied) {
                // Found empty spot
                return { path: path, target: idx, steps: step };
            }
            path.push(idx); // Add occupied spot to path
            step++;
        }
        return { path: path, target: -1, steps: step }; // Should not happen in solvable game
    }

    // --- Click Handler ---
    $('.house').click(function() {
        if(h1 === null) return;
        let clickedIndex = $(this).data('index');
        let $el = $(this);
        let name = isSearchPhase ? currentSearchTarget.name : residents[currentResIdx];

        // 1. Chaining (Simple)
        if (gameMode === 'chaining') {
            if (clickedIndex === h1) {
                if(isSearchPhase) handleSearchClick(clickedIndex, $el, name);
                else placeResident(clickedIndex, name);
            } else {
                failFeedback("Falsches Haus! Rechne nochmal nach.");
            }
            return;
        }

        // 2. Probing Modes (Linear, Quad, Double)
        // Calculate the "Truth" path
        let result = calculateProbingPath(h1, name);
        let validPath = result.path; // Indices that are correct but occupied
        let correctTarget = result.target; // The final empty spot

        // --- LOGIC: Direct Hit or Path Clicking ---

        // Case A: User clicked the FINAL CORRECT EMPTY spot directly
        if (clickedIndex === correctTarget) {
            // Check strict limit
            if (result.steps > MAX_PROBE_STEPS) {
                failGame(`Zu viele Schritte (${result.steps}) nötig! Erweiterung war überfällig.`);
                return;
            }

            // Check Load Factor Limit
            let futureLF = (placedResidents.length + 1) / currentCapacity;
            if (futureLF > 0.76) {
                failGame("Load Factor Limit (0.75) überschritten! Das System ist zu langsam.");
                return;
            }

            if(isSearchPhase) handleSearchClick(clickedIndex, $el, name);
            else placeResident(clickedIndex, name);
            return;
        }

        // Case B: User clicked a Valid but Occupied spot (Collision path)
        if (validPath.includes(clickedIndex)) {
            $el.addClass('collision-highlight');
            setTimeout(() => $el.removeClass('collision-highlight'), 500);

            // Unlock Double Hashing Calc if appropriate
            if (gameMode === 'double') {
                $('#stepCalcBox').addClass('active');
                if($('#h2Result').text() === '-') $('#btnCalcH2').prop('disabled', false);
            }
            return;
        }

        // Case C: Wrong House
        failFeedback("Falsches Haus! Rechne nochmal nach.");
    });

    function handleSearchClick(clickedIndex, $el, targetName) {
        // Simplified search logic reusing the placement check
        // In search phase, we just check if person is there
        let residentsHere = placedResidents.filter(r => r.houseIndex === clickedIndex).map(r => r.name);

        if (residentsHere.includes(targetName)) {
            $el.addClass('found-highlight');
            showDialogue(`Gefunden! ${targetName} wohnt in Haus ${clickedIndex}.`);
            searchQueue.shift();
            $(`#search-0`).remove();
            setTimeout(() => startNextSearch(), 1500);
        } else {
            // If empty or wrong person
            // If it was part of the valid probe path (calculated in click handler), we gave hint.
            // If it's the correct target but empty (should not happen if logic matches)
            $el.addClass('collision-highlight');
            setTimeout(() => $el.removeClass('collision-highlight'), 500);
            if (gameMode === 'double') {
                showDialogue(`${targetName} nicht hier. Berechne Step!`);
                $('#stepCalcBox').addClass('active');
                if($('#h2Result').text() === '-') $('#btnCalcH2').prop('disabled', false);
            } else {
                showDialogue(`${targetName} nicht hier. Rechne weiter...`);
            }
        }
    }

    function placeResident(idx, name) {
        placedResidents.push({ name: name, houseIndex: idx });
        let count = placedResidents.filter(r => r.houseIndex === idx).length;
        updateHouseVisual($(`#house-${idx}`), count);
        $(`#res-${currentResIdx}`).addClass('done');
        currentResIdx++;
        setTimeout(() => { highlightNextResident(); }, 800);
    }

    // --- Search Phase Init ---
    function initSearchPhase() {
        isSearchPhase = true;
        let shuffled = [...placedResidents].sort(() => 0.5 - Math.random());
        searchQueue = shuffled.slice(0, 3);
        showDialogue("Alle platziert! Finde nun die 3 gesuchten Personen!");
        $('#btnExpand').prop('disabled', true);
        $('#resList').empty();
        searchQueue.forEach((p, index) => {
            $('#resList').append(`<li class="list-group-item search-item" id="search-${index}">${p.name}</li>`);
        });
        startNextSearch();
    }

    function startNextSearch() {
        if(searchQueue.length === 0) { winGame(); return; }
        currentSearchTarget = searchQueue[0];
        $('.list-group-item').removeClass('search-target');
        $(`#search-0`).addClass('search-target');
        h1 = null; h2 = null;
        $('#hashResult').text('-');
        $('#h2Result').text('-');
        $('#stepCalcBox').removeClass('active');
        $('#hashButton').prop('disabled', false).text('1. Hash (Suche)');
        $('.house').removeClass('collision-highlight found-highlight');
        showDialogue(`Suche: ${currentSearchTarget.name}.`);
    }

    // --- Helpers ---
    function failFeedback(msg) {
        $('#dialogueText').html(`<span style="color:red">⛔ ${msg}</span>`);
    }

    function winGame() {
        $('#endModal').removeClass('modal-fail').addClass('modal-win');
        $('#endIcon').text("🎓");
        $('#endTitle').text("ABSCHLUSS BESTANDEN!");
        $('#endMessage').html(`Du hast HashCity gemeistert.<br>Keine Hilfen, maximaler Stress.<br>Glückwunsch!`);
        $('.btn-primary').text("Zertifikat").attr('onclick', "window.location.href='certificate.php'");
        $('#endOverlay').fadeIn();
    }

    function failGame(reason) {
        $('#endModal').removeClass('modal-win').addClass('modal-fail');
        $('#endIcon').text("☠️");
        $('#endTitle').text("Gescheitert");
        $('#endMessage').text(reason);
        $('.btn-primary').text("Neustart").attr('onclick', 'location.reload()');
        $('#endOverlay').fadeIn();
    }
</script>
</body>
</html>