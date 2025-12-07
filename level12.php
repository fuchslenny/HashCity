<?php
/**
 * HashCity - Level 12: Das Finale (Hardcore Mode)
 *
 * Updates:
 * - 30 Residents (Random Names)
 * - Strict Probing Limits (Max 5 Steps)
 * - NO Visual Guides (User must calculate positions)
 * - Search Phase at the end
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
        .back-btn { padding: 0.7rem 1.3rem; background: rgba(255, 255, 255, 0.9); border: 2px solid rgba(102, 126, 234, 0.5); border-radius: 30px; font-weight: 700; color: #667eea; text-decoration: none; display: inline-block; transition: all 0.3s; }
        .back-btn:hover { background: #667eea; color: #fff; transform: scale(1.05); }

        .game-container { max-width: 1800px; margin: 2rem auto; padding: 0 2rem; position: relative; z-index: 1; }
        .game-area { display: grid; grid-template-columns: 280px 1fr 320px; gap: 2rem; min-height: 70vh; }

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

        .houses-row { display: grid; grid-template-columns: repeat(10, 1fr); gap: 0.5rem; margin-bottom: 0.5rem; padding: 0 0.5rem; position: relative; z-index: 2; align-items: end; min-height: 120px; }

        .street { width: 100%; height: 60px; background-image: url('./assets/Strasse.svg'); background-size: cover; background-position: center; position: relative; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.15); z-index: 1; margin-top: -15px; }
        .street::before { content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(180deg, #4a4a4a 0%, #2a2a2a 100%); border-radius: 8px; z-index: -1; }
        .street::after { content: ''; position: absolute; top: 50%; left: 0; width: 100%; height: 4px; background: repeating-linear-gradient(90deg, #fff 0px, #fff 30px, transparent 30px, transparent 50px); transform: translateY(-50%); z-index: 2; }

        /* House Logic */
        .house { position: relative; cursor: pointer; transition: transform 0.2s; display: flex; flex-direction: column-reverse; align-items: center; width: 100%; }
        .house:hover { transform: translateY(-5px) scale(1.05); z-index: 10; }
        /* .highlight-target ENTFERNT - Keine visuellen Hilfen mehr! */
        .house.collision-highlight { box-shadow: 0 0 15px 5px rgba(211, 47, 47, 0.8); border-radius: 50%; animation: shake 0.4s; }
        .house.found-highlight { box-shadow: 0 0 25px 10px #4CAF50; border-radius: 50%; transform: scale(1.2) !important; z-index: 20; }

        @keyframes shake { 0%, 100% { transform: translateX(0); } 25% { transform: translateX(-5px); } 75% { transform: translateX(5px); } }

        /* Images */
        .house-icon { width: 100%; height: auto; object-fit: contain; filter: drop-shadow(0 3px 6px rgba(0,0,0,0.2)); }
        .img-house-base { width: 100%; height: auto; z-index: 1; display: block; position: relative; filter: drop-shadow(0 4px 6px rgba(0,0,0,0.3)); }
        .img-house-extension { width: 100%; height: auto; z-index: 10; display: block; position: relative; margin-bottom: -5px; animation: fallDown 0.4s ease-out; }
        @keyframes fallDown { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }

        .house-number { position: absolute; bottom: -25px; left: 50%; transform: translateX(-50%); font-family: 'Orbitron', sans-serif; font-size: 0.8rem; font-weight: 900; color: white; background: #333; padding: 2px 6px; border-radius: 4px; z-index: 20; }

        /* HOVER DISABLED */
        .house-occupant { display: none !important; }

        /* Info Panel */
        .info-panel { background: rgba(255, 255, 255, 0.85); border-radius: 25px; padding: 1.5rem; position: sticky; top: 100px; border: 4px solid #fff; }
        .info-item { background: #fff; padding: 1rem; border-radius: 15px; margin-bottom: 1rem; border: 3px solid #4CAF50; }

        .load-factor-box { text-align: center; padding: 0.5rem; background: #f0f0f0; border-radius: 10px; margin-bottom: 1rem; border: 2px solid #ccc; transition: all 0.3s ease; }
        .lf-bad { background: #FFEBEE; border-color: #D32F2F; color: #D32F2F; animation: pulseRed 2s infinite; }
        @keyframes pulseRed { 0% { box-shadow: 0 0 0 0 rgba(211, 47, 47, 0.4); } 70% { box-shadow: 0 0 0 10px rgba(211, 47, 47, 0); } 100% { box-shadow: 0 0 0 0 rgba(211, 47, 47, 0); } }

        .calc-button { width: 100%; padding: 0.6rem; border: none; border-radius: 30px; font-weight: 700; cursor: pointer; color: white; margin-top: 5px; font-family: 'Orbitron'; transition: all 0.2s; }
        .btn-calc { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .btn-calc-2 { background: linear-gradient(135deg, #FF9800 0%, #F57C00 100%); display: none; margin-top: 10px; }
        .btn-expand { background: linear-gradient(135deg, #4CAF50 0%, #2E7D32 100%); font-size: 1.1rem; }
        .btn-expand:disabled { background: #ccc; cursor: not-allowed; }
        .btn-expand:hover:not(:disabled) { transform: scale(1.05); }

        .list-group-item { cursor: pointer; font-weight: bold; transition: 0.2s; border: 1px solid #ddd; margin-bottom: 4px; border-radius: 6px; }
        .list-group-item.active { background: #667eea; color: white; transform: scale(1.02); border: none; box-shadow: 0 4px 10px rgba(0,0,0,0.2); }
        .list-group-item.done { text-decoration: line-through; opacity: 0.5; background: #eee; color: #888; cursor: default; }
        .list-group-item.search-target { background: #FF9800; color: white; animation: pulseSearch 2s infinite; border: 2px solid #E65100; }
        @keyframes pulseSearch { 0% { transform: scale(1); } 50% { transform: scale(1.03); } 100% { transform: scale(1); } }

        /* Mode Selection Modal */
        .mode-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.9); z-index: 3000; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(8px); }
        .mode-box { background: white; padding: 3rem; border-radius: 30px; max-width: 800px; width: 90%; text-align: center; border: 5px solid #667eea; animation: zoomIn 0.4s ease; }
        @keyframes zoomIn { from { transform: scale(0.8); opacity: 0; } to { transform: scale(1); opacity: 1; } }
        .mode-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem; margin-top: 2rem; }
        .mode-card { background: #f8f9fa; padding: 1.5rem; border-radius: 15px; border: 2px solid #ddd; cursor: pointer; transition: all 0.3s; }
        .mode-card:hover { transform: translateY(-5px); border-color: #667eea; box-shadow: 0 10px 20px rgba(102, 126, 234, 0.2); }
        .mode-card h4 { color: #667eea; font-weight: 900; font-family: 'Orbitron'; }
        .mode-card p { font-size: 0.9rem; color: #666; margin-bottom: 0; }

        /* Success/Fail Overlays */
        .overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.9); display: none; align-items: center; justify-content: center; z-index: 4000; }
        .modal-box { background: white; border-radius: 30px; padding: 3rem; text-align: center; border: 5px solid; max-width: 600px; box-shadow: 0 0 50px rgba(0,0,0,0.5); }
        .modal-win { border-color: #4CAF50; }
        .modal-fail { border-color: #D32F2F; }

    </style>
</head>
<body>

<div class="mode-overlay" id="modeOverlay">
    <div class="mode-box">
        <h1 style="font-family: 'Orbitron'; font-weight: 900; color: #333;">Level 12: Die finale Prüfung</h1>
        <p>30 Einwohner. Keine Hilfslinien. Du bist auf dich allein gestellt.</p>
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
                <small class="text-muted">(Level 8 - Advanced)</small>
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
                        <div class="house" id="house-<?php echo $i; ?>" data-index="<?php echo $i; ?>">
                            <div class="house-number"><?php echo $i; ?></div>
                            <div class="house-occupant"></div>
                        </div>
                    <?php endfor; ?>
                </div>
                <div class="street"></div>
            </div>

            <?php for ($b = 2; $b < 4; $b++): ?>
                <div class="street-block hidden" id="block-<?php echo $b; ?>">
                    <div class="houses-row">
                        <?php for ($i = 0; $i < 10; $i++): $hNum = $b*10 + $i; ?>
                            <div class="house" id="house-<?php echo $hNum; ?>" data-index="<?php echo $hNum; ?>">
                                <div class="house-number"><?php echo $hNum; ?></div>
                                <div class="house-occupant"></div>
                            </div>
                        <?php endfor; ?>
                    </div>
                    <div class="street"></div>
                </div>
            <?php endfor; ?>
        </div>

        <div class="info-panel">
            <h3 style="text-align:center; color:#2E7D32; font-family:'Orbitron';">📊 Verwaltung</h3>

            <div class="load-factor-box" id="lfBox">
                <div style="font-size:0.8rem">Load Factor</div>
                <div style="font-size:1.5rem; font-weight:bold" id="lfValue">0.00</div>
                <div style="font-size:0.7rem">Limit: <span id="lfLimit">0.75</span></div>
            </div>

            <button id="btnExpand" class="calc-button btn-expand" disabled>🏗️ STADT ERWEITERN</button>

            <div class="info-item">
                <div class="info-label">Rechner <span style="float:right; font-size:0.8rem">Mod <span id="modBase">10</span></span></div>
                <div style="text-align:center; font-size:1.8rem; color:#667eea; font-weight:bold; margin:5px 0;" id="calcResult">-</div>

                <button id="btnCalc1" class="calc-button btn-calc" disabled>1. Hash Berechnen</button>
                <button id="btnCalc2" class="calc-button btn-calc-2" disabled>⚠️ Kollision! Step berechnen</button>
            </div>

            <div class="info-item">
                <div class="info-label">Warteschlange (<span id="queueCount">30</span>):</div>
                <div style="max-height:200px; overflow-y:auto">
                    <ul class="list-group" id="resList">
                        <?php foreach($final_residents as $idx => $name): ?>
                            <li class="list-group-item" id="res-<?php echo $idx; ?>" data-name="<?php echo $name; ?>">
                                <?php echo $name; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
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
    const MAX_PROBE_STEPS = 5; // Reduced from 12 to 5 for HARD difficulty

    // Probing-Mode Häuser
    const housePairsProbing = [
        { base: "WohnhauBlauBraunLeerNeu.svg", fill: "WohnhauBlauBraunBesetztNeu.svg" },
        { base: "WohnhauBlauGrauLeerNeu.svg", fill: "WohnhauBlauGrauBesetztNeu.svg" },
        { base: "WohnhauBlauRotLeerNeu.svg", fill: "WohnhauBlauRotBesetztNeu.svg" },
        { base: "WohnhauGelbBraunLeerNeu.svg", fill: "WohnhauGelbBraunBesetztNeu.svg" },
        { base: "WohnhauGruenBraunLeerNeu.svg", fill: "WohnhauGruenBraunBesetztNeu.svg" },
    ];

    // Chaining-Mode Häuser
    const housePairsChaining = [
        { base: "Wohnhaus2BlauBraun.svg", extension: "WohnhausBlauBraunErweiterung.svg" },
        { base: "Wohnhaus2BlauGrau.svg", extension: "WohnhausBlauGrauErweiterung.svg" },
        { base: "Wohnhaus2BlauRot.svg", extension: "WohnhausBlauRotErweiterung.svg" },
        { base: "Wohnhaus2GrauBraun.svg", extension: "WohnhausGrauBraunErweiterung.svg" },
    ];

    // --- Global State ---
    let gameMode = null;
    let currentCapacity = 10;
    let placedResidents = [];
    let currentResIdx = 0;
    let isSearchPhase = false;
    let searchQueue = [];
    let currentSearchTarget = null;

    // Step State
    let h1 = null;
    let h2 = null;
    let stepCount = 0;
    let expansionCount = 0;
    const maxExpansions = 2;

    // --- Initialization ---
    function selectMode(mode) {
        gameMode = mode;
        $('#modeOverlay').fadeOut();

        let modeName = "";
        if (mode === 'linear') modeName = "Linear Probing";
        if (mode === 'quadratic') modeName = "Quadratic Probing";
        if (mode === 'double') modeName = "Double Hashing";
        if (mode === 'chaining') {
            modeName = "Separate Chaining";
            $('#lfLimit').text("∞");
        }

        $('#modeBadge').text(modeName);
        $('#dialogueText').text(`Modus: ${modeName}. Keine Hilfen. Viel Erfolg!`);

        initVisuals();
        updateStats();
        highlightNextResident();
    }

    function initVisuals() {
        if (gameMode === 'chaining') {
            $('.house').each(function() {
                const pair = housePairsChaining[Math.floor(Math.random() * housePairsChaining.length)];
                $(this).data('pair', pair);
                $(this).empty();
                $(this).append(`<img src="./assets/${pair.base}" class="img-house-base">`);
                $(this).append(`<div class="house-number">${$(this).data('index')}</div>`);
                $(this).append(`<div class="house-occupant"></div>`);
            });
        } else {
            $('.house').each(function() {
                const pair = housePairsProbing[Math.floor(Math.random() * housePairsProbing.length)];
                $(this).data('pair', pair);
                $(this).empty();
                $(this).append(`<img src="./assets/${pair.base}" class="house-icon">`);
                $(this).append(`<div class="house-number">${$(this).data('index')}</div>`);
                $(this).append(`<div class="house-occupant"></div>`);
            });
        }
    }

    function updateHouseVisual($el, count) {
        const idx = $el.data('index');
        const pair = $el.data('pair');
        if (!pair) return;

        $el.empty();
        $el.append(`<div class="house-number">${idx}</div>`);
        $el.append(`<div class="house-occupant"></div>`);

        if (gameMode === 'chaining') {
            $el.append(`<img src="./assets/${pair.base}" class="img-house-base">`);
            if (count > 1) {
                for(let i = 1; i < count; i++) {
                    $el.append(`<img src="./assets/${pair.extension}" class="img-house-extension" style="z-index:${10+i}">`);
                }
            }
            if(count > 0) {
                let names = placedResidents.filter(r => r.houseIndex == idx).map(r => r.name).join(', ');
                $el.find('.house-occupant').text(names);
            }
        } else {
            let img = (count > 0) ? pair.fill : pair.base;
            $el.append(`<img src="./assets/${img}" class="house-icon">`);
            if(count > 0) {
                let p = placedResidents.find(r => r.houseIndex == idx);
                if(p) $el.find('.house-occupant').text(p.name);
            }
        }
    }

    // --- Math Helpers ---
    function getAsciiSum(name) {
        let sum = 0;
        for(let i=0; i<name.length; i++) sum += name.charCodeAt(i);
        return sum;
    }

    // --- Game Logic ---
    function highlightNextResident() {
        if(currentResIdx >= residents.length) {
            initSearchPhase();
            return;
        }

        $('.list-group-item').removeClass('active');
        $(`#res-${currentResIdx}`).addClass('active');

        h1 = null;
        h2 = null;
        stepCount = 0;

        $('#calcResult').text('-');
        $('#btnCalc1').prop('disabled', false).text('1. Hash Berechnen');
        $('#btnCalc2').hide().prop('disabled', true);

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

        if(gameMode !== 'chaining') {
            if(lf > 0.75) $('#lfBox').addClass('lf-bad');
            else $('#lfBox').removeClass('lf-bad');
        }

        if(!isSearchPhase && expansionCount < maxExpansions && h1 === null) {
            $('#btnExpand').prop('disabled', false);
        } else {
            $('#btnExpand').prop('disabled', true);
        }

        if(isSearchPhase) {
            $('#queueCount').text(searchQueue.length);
        } else {
            $('#queueCount').text(residents.length - currentResIdx);
        }
    }

    // --- SEARCH PHASE LOGIC ---
    function initSearchPhase() {
        isSearchPhase = true;
        let shuffled = [...placedResidents].sort(() => 0.5 - Math.random());
        searchQueue = shuffled.slice(0, 3);

        $('#dialogueText').text("Alle platziert! Finde nun die 3 gesuchten Personen ohne Hilfe!");
        $('#mmAvatar').attr('src', './assets/wink_major.png');
        $('#btnExpand').prop('disabled', true);

        $('#resList').empty();
        searchQueue.forEach((p, index) => {
            $('#resList').append(`<li class="list-group-item search-item" id="search-${index}">${p.name}</li>`);
        });

        startNextSearch();
    }

    function startNextSearch() {
        if(searchQueue.length === 0) {
            winGame();
            return;
        }

        currentSearchTarget = searchQueue[0];
        $('.list-group-item').removeClass('search-target');
        $(`#search-0`).addClass('search-target');

        h1 = null;
        h2 = null;
        stepCount = 0;

        $('#calcResult').text('-');
        $('#btnCalc1').prop('disabled', false).text('1. Hash (Suche)');
        $('#btnCalc2').hide().prop('disabled', true);
        $('.house').removeClass('collision-highlight found-highlight');

        $('#dialogueText').text(`Suche: ${currentSearchTarget.name}. Berechne den Hash!`);
    }

    // --- Button Handlers ---
    $('#btnCalc1').click(function() {
        let name = isSearchPhase ? currentSearchTarget.name : residents[currentResIdx];
        let sum = getAsciiSum(name);
        h1 = sum % currentCapacity;

        $('#calcResult').text(`H1: ${h1}`);

        // NO HAND HOLDING: Nur das Ergebnis zeigen
        let msg = isSearchPhase
            ? `Suche ${name}: 1. Hash ist ${h1}. Finde das richtige Haus!`
            : `1. Hash ist ${h1}. Wähle das richtige Haus!`;

        $('#dialogueText').text(msg);
        $('#btnCalc1').prop('disabled', true);
        // KEIN .addClass('highlight-target')
    });

    $('#btnCalc2').click(function() {
        let name = isSearchPhase ? currentSearchTarget.name : residents[currentResIdx];
        let sum = getAsciiSum(name);
        let hashSize2 = Math.floor(currentCapacity / 2);
        h2 = (sum % hashSize2) + 1;

        $('#calcResult').text(`H1: ${h1} | H2: ${h2}`);

        if(stepCount === 0) stepCount = 1;

        // NO HAND HOLDING: Nur die Schrittweite zeigen
        $('#dialogueText').html(`Sprungweite (H2) ist <b>${h2}</b>.<br>Wende die Strategie an um das Ziel zu finden!`);
        $('#btnCalc2').prop('disabled', true);
        // KEIN .addClass('highlight-target')
    });

    // --- Click House Logic ---
    $('.house').click(function() {
        if(h1 === null) return;

        let clickedIndex = $(this).data('index');
        let $el = $(this);
        let name = isSearchPhase ? currentSearchTarget.name : residents[currentResIdx];

        // --- Zielvalidierung (Im Hintergrund) ---
        let expectedIndex = -1;

        if (gameMode === 'chaining') {
            expectedIndex = h1;
        } else if (gameMode === 'linear') {
            expectedIndex = (h1 + stepCount) % currentCapacity;
        } else if (gameMode === 'quadratic') {
            expectedIndex = (h1 + (stepCount * stepCount)) % currentCapacity;
        } else if (gameMode === 'double') {
            if (h2 === null) expectedIndex = h1;
            else expectedIndex = (h1 + (stepCount * h2)) % currentCapacity;
        }

        if (clickedIndex !== expectedIndex) {
            failFeedback("Falsches Haus! Rechne nochmal nach.");
            return;
        }

        // --- Logik Weiche: Search vs Place ---
        if(isSearchPhase) {
            handleSearchClick(clickedIndex, $el, name);
        } else {
            handlePlaceClick(clickedIndex, $el, name);
        }
    });

    function handlePlaceClick(clickedIndex, $el, name) {
        let occupants = placedResidents.filter(r => r.houseIndex === clickedIndex);
        let isOccupied = occupants.length > 0;

        if (gameMode === 'chaining') {
            placeResident(clickedIndex, name);
            return;
        }

        // Kollision
        if (isOccupied) {
            $el.addClass('collision-highlight');
            setTimeout(() => $el.removeClass('collision-highlight'), 500);

            if (gameMode === 'double' && h2 === null) {
                $('#dialogueText').html(`Haus ${clickedIndex} belegt! Kollision.<br>Berechne die Sprungweite (Step).`);
                $('#btnCalc2').show().prop('disabled', false).addClass('btn-pulse');
                return;
            }

            stepCount++;

            // STRICT FAIL CONDITION (5 Steps)
            if (stepCount > MAX_PROBE_STEPS) {
                failGame(`Zu viele Kollisionen (${stepCount})! Die Performance ist im Keller. Du hättest erweitern müssen.`);
                return;
            }

            // KEIN Hinweis auf das nächste Haus. User muss selbst rechnen.
            $('#dialogueText').text(`Haus belegt! Kollision Nr. ${stepCount}. Wo musst du als nächstes hin?`);
        }
        else {
            let futureLF = (placedResidents.length + 1) / currentCapacity;
            if (futureLF > 0.76) {
                failGame("Load Factor Limit (0.75) überschritten! Das System ist zu langsam.");
                return;
            }
            placeResident(clickedIndex, name);
        }
    }

    function handleSearchClick(clickedIndex, $el, targetName) {
        let residentsHere = placedResidents.filter(r => r.houseIndex === clickedIndex).map(r => r.name);

        if (residentsHere.includes(targetName)) {
            $el.addClass('found-highlight');
            $('#dialogueText').text(`Gefunden! ${targetName} wohnt in Haus ${clickedIndex}.`);

            searchQueue.shift();
            $(`#search-0`).remove();
            setTimeout(() => startNextSearch(), 1500);

        } else {
            $el.addClass('collision-highlight');
            setTimeout(() => $el.removeClass('collision-highlight'), 500);

            if (gameMode === 'double' && h2 === null) {
                $('#dialogueText').text(`${targetName} ist nicht hier. Berechne den Step für die Suche!`);
                $('#btnCalc2').show().prop('disabled', false);
                return;
            }

            stepCount++;
            $('#dialogueText').text(`${targetName} nicht hier. Rechne weiter... Wo ist das nächste Haus?`);
        }
    }

    function placeResident(idx, name) {
        placedResidents.push({ name: name, houseIndex: idx });

        let count = placedResidents.filter(r => r.houseIndex === idx).length;
        updateHouseVisual($(`#house-${idx}`), count);

        $(`#res-${currentResIdx}`).addClass('done');
        currentResIdx++;

        $('#dialogueText').text(`${name} wohnt jetzt in Haus ${idx}.`);

        setTimeout(() => {
            highlightNextResident();
        }, 800);
    }

    function failFeedback(msg) {
        $('#dialogueText').html(`<span style="color:red">⛔ ${msg}</span>`);
        $('#mmAvatar').attr('src', './assets/sad_major.png');
        setTimeout(() => $('#mmAvatar').attr('src', './assets/card_major.png'), 1500);
    }

    // --- Expansion Logic ---
    $('#btnExpand').click(function() {
        $(this).prop('disabled', true);

        if(placedResidents.length === 0) {
            $('#dialogueText').text("Niemand da zum Umziehen!");
            setTimeout(() => updateStats(), 1000);
            return;
        }

        expansionCount++;
        let oldCap = currentCapacity;
        currentCapacity *= 2;

        $('#dialogueText').text(`Erweitere Stadt auf ${currentCapacity}. Rehashing...`);

        if(currentCapacity >= 20) $('#block-1').removeClass('hidden');
        if(currentCapacity >= 40) { $('#block-2').removeClass('hidden'); $('#block-3').removeClass('hidden'); }

        $('.house').each(function() {
            updateHouseVisual($(this), 0);
            $(this).removeClass('collision-highlight found-highlight');
            if($(this).data('index') >= oldCap) {
                let arr = (gameMode === 'chaining') ? housePairsChaining : housePairsProbing;
                let pair = arr[Math.floor(Math.random() * arr.length)];
                $(this).data('pair', pair);
                $(this).empty();
                if(gameMode === 'chaining') $(this).append(`<img src="./assets/${pair.base}" class="img-house-base">`);
                else $(this).append(`<img src="./assets/${pair.base}" class="house-icon">`);
                $(this).append(`<div class="house-number">${$(this).data('index')}</div>`);
                $(this).append(`<div class="house-occupant"></div>`);
            }
        });

        let newPlacement = [];

        placedResidents.forEach(person => {
            let sum = getAsciiSum(person.name);

            if (gameMode === 'chaining') {
                let pos = sum % currentCapacity;
                newPlacement.push({ name: person.name, houseIndex: pos });
            } else {
                let localH1 = sum % currentCapacity;
                let localH2 = 0;
                if(gameMode === 'double') {
                    localH2 = (sum % Math.floor(currentCapacity/2)) + 1;
                }

                let step = 0;
                let pos = -1;

                while(step < currentCapacity * 2) {
                    let attemptPos = -1;
                    if (gameMode === 'linear') attemptPos = (localH1 + step) % currentCapacity;
                    else if (gameMode === 'quadratic') attemptPos = (localH1 + step*step) % currentCapacity;
                    else if (gameMode === 'double') attemptPos = (localH1 + step*localH2) % currentCapacity;

                    if (!newPlacement.some(r => r.houseIndex === attemptPos)) {
                        pos = attemptPos;
                        break;
                    }
                    step++;
                }
                if(pos !== -1) {
                    newPlacement.push({ name: person.name, houseIndex: pos });
                }
            }
        });

        placedResidents = newPlacement;

        setTimeout(() => {
            $('.house').each(function() {
                let idx = $(this).data('index');
                let count = placedResidents.filter(r => r.houseIndex === idx).length;
                updateHouseVisual($(this), count);
            });

            $('#dialogueText').text("Umzug fertig! Weiter geht's.");
            updateStats();
            highlightNextResident();
        }, 1000);
    });

    // --- Win/Fail ---
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