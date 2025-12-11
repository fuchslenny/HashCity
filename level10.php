<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HashCity - Level 10: Load-Faktor</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Rajdhani:wght@300;500;700&display=swap" rel="stylesheet">
    <style>
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
        /* Sky and Grass Background */
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
        /* Clouds */
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
        /* Header */
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
        /* Game Container */
        .game-container {
            max-width: 1600px;
            margin: 2rem auto;
            padding: 0 2rem;
            position: relative;
            z-index: 1;
        }
        /* Main Game Area */
        .game-area {
            display: grid;
            grid-template-columns: 280px 1fr 320px;
            gap: 2rem;
            min-height: 70vh;
        }
        /* Major Mike Section */
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
        /* Houses Grid */
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
        /* Street Block Container */
        .street-block {
            position: relative;
            margin-bottom: 2.5rem;
        }
        /* Houses Row */
        .houses-row {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 1rem;
            padding: 0 1rem;
            position: relative;
            z-index: 2;
        }
        /* Straße */
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
        .street::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(180deg, #4a4a4a 0%, #2a2a2a 100%);
            border-radius: 8px;
            z-index: -1;
        }
        .street::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            width: 100%;
            height: 4px;
            background: repeating-linear-gradient(
                    90deg,
                    #fff 0px,
                    #fff 30px,
                    transparent 30px,
                    transparent 50px
            );
            transform: translateY(-50%);
            z-index: 2;
        }
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
        .house:hover:not(.checked):not(.found) {
            transform: translateY(-8px) scale(1.08);
            z-index: 10;
        }
        .house.highlight-target {
            transform: translateY(-10px) scale(1.15) !important;
            box-shadow: 0 0 35px 12px rgba(255, 215, 0, 0.9);
            z-index: 11;
        }
        .house.quadratic-target {
            transform: translateY(-10px) scale(1.15) !important;
            box-shadow: 0 0 35px 12px rgba(255, 0, 0, 0.9);
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
        .house.found .house-icon {
            animation: pulse 1.5s infinite;
            filter: drop-shadow(0 8px 16px rgba(255, 215, 0, 0.8));
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
        .house.show-family .house-family {
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
        .list-group-item.to-do-family.list-group-item-success {
            opacity: 0.3;
            background: #e0e0e0;
            cursor: not-allowed;
            text-decoration: line-through;
        }
        .list-group-item.to-do-family.disabled {
            opacity: 0.5;
            cursor: not-allowed;
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
        /* Success Modal */
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
        .success-stats {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            margin-bottom: 2rem;
        }
        .stat-box {
            background: #f8f9fa;
            padding: 1.2rem;
            border-radius: 15px;
            border: 3px solid #4CAF50;
            box-shadow: 0 4px 12px rgba(76, 175, 80, 0.15);
        }
        .stat-label {
            font-size: 0.95rem;
            color: #666;
            font-weight: 700;
            margin-bottom: 0.4rem;
        }
        .stat-value {
            font-family: 'Orbitron', sans-serif;
            font-size: 2.5rem;
            font-weight: 900;
            color: #2E7D32;
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
        /* Responsive Design */
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
            .game-container {
                padding: 0 1rem;
                margin: 1rem auto;
            }
            .houses-grid {
                padding: 1.5rem 1rem;
            }
            .houses-row {
                grid-template-columns: repeat(3, 1fr);
                gap: 0.6rem;
            }
        }
    </style>
</head>
<body>
<!-- Sky Section -->
<div class="sky-section">
    <div class="cloud" style="width: 120px; height: 60px; top: 8%; animation-delay: 0s;"></div>
    <div class="cloud" style="width: 150px; height: 70px; top: 18%; animation-delay: 10s;"></div>
    <div class="cloud" style="width: 100px; height: 50px; top: 28%; animation-delay: 20s;"></div>
    <div class="cloud" style="width: 130px; height: 65px; top: 12%; animation-delay: 30s;"></div>
</div>
<!-- Grass Section -->
<div class="grass-section"></div>
<!-- Header -->
<div class="game-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-auto">
                <a href="level-select.php" class="back-btn">Zurück</a>
            </div>
        </div>
    </div>
</div>
<!-- Game Container -->
<div class="game-container">
    <div class="game-area">
        <!-- Major Mike Section -->
        <div class="major-mike-section">
            <div class="major-mike-avatar">
                <img src="./assets/wink_major.png" alt="Major Mike" id="majorMikeImage">
            </div>
            <div class="major-mike-name">🎖️ Major Mike 🎖️</div>
            <div class="dialogue-box">
                <div class="dialogue-text" id="dialogueText">
                    Separate Chaining erzeugt bei vielen Daten lange Listen, die die Such Performance beeinträchtigen. Außerdem können einige Speicherbereiche ungenutzt bleiben. Also entstehen sehr große Mehrfamilienhäuser, in denen man dann auch keine Bewohner schnell findet. Zudem können Häuser so auch leer stehen bleiben.
                </div>
                <div class="dialogue-continue" id="dialogueContinue">
                    Klicken oder Enter ↵
                </div>
            </div>
        </div>
        <!-- Houses Grid -->
        <div class="houses-grid">
            <h2 class="grid-title">🏘️ Level 10: Load Factor</h2>
            <!-- 4 Straßen mit je 5 Häusern -->
            <?php
            // Paare der neuen Assets
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
            // Zufällige Zuordnung der Asset-Paare zu den Häusern
            $houseAssets = [];
            for ($i = 0; $i < 20; $i++) {
                $houseAssets[$i] = $housePairs[array_rand($housePairs)];
            }
            ?>
            <div class="street-block">
                <div class="houses-row">
                    <?php for ($i = 0; $i < 5; $i++): ?>
                        <div class="house" data-house="<?php echo $i; ?>" data-family="">
                            <img src="./assets/<?php echo $houseAssets[$i]['empty']; ?>" alt="Haus <?php echo $i; ?>" class="house-icon">
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
                            <img src="./assets/<?php echo $houseAssets[$i]['empty']; ?>" alt="Haus <?php echo $i; ?>" class="house-icon">
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
                            <img src="./assets/<?php echo $houseAssets[$i]['empty']; ?>" alt="Haus <?php echo $i; ?>" class="house-icon">
                            <div class="house-number"><?php echo $i; ?></div>
                            <div class="house-family"></div>
                        </div>
                    <?php endfor; ?>
                </div>
                <div class="street"></div>
            </div>
            <div class="street-block">
                <div class="houses-row">
                    <?php for ($i = 15; $i < 20; $i++): ?>
                        <div class="house" data-house="<?php echo $i; ?>" data-family="">
                            <img src="./assets/<?php echo $houseAssets[$i]['empty']; ?>" alt="Haus <?php echo $i; ?>" class="house-icon">
                            <div class="house-number"><?php echo $i; ?></div>
                            <div class="house-family"></div>
                        </div>
                    <?php endfor; ?>
                </div>
                <div class="street"></div>
            </div>
        </div>
        <!-- Info Panel -->
        <div class="info-panel">
            <h3 class="info-title">📊 Stadtplanung</h3>
            <!-- Bewerber-Liste -->
            <div class="info-item">
                <div class="info-label">Einziehende Familien:</div>
                <div class="family-list-container">
                    <ul id="familienListe" class="list-group">
                        <li class="list-group-item to-do-family" data-family="Levi">Levi</li>
                    </ul>
                </div>
            </div>
            <div class="info-item hash-calculator">
                <div class="info-label">Bewohnername:</div>
                <input type="text" id="nameInput" class="calculator-input" placeholder="Namen eingeben..." readonly>
                <button id="hashButton" class="calculator-button">Berechne Haus-Nr.</button>
                <div class="calculator-result" id="hashResult">Ergebnis ...</div>
            </div>
            <div class="load-factor-box lf-bad" id="lfBox">
                <div class="lf-label">Load Factor (Belegung)</div>
                <div class="lf-value" id="lfValue">0.95</div>
                <div class="lf-label" id="lfText">Kritisch (Zu voll!)</div>
            </div>
            <div class="info-item">
                <div class="info-label">Eingetragene Familien:</div>
                <div class="info-value" id="occupiedCount">0 / 20</div>
            </div>
        </div>
    </div>
</div>
<!-- Success Modal -->
<div class="success-overlay" id="successOverlay">
    <div class="success-modal">
        <div class="success-icon">🎉</div>
        <h2 class="success-title">Stadterweiterung!</h2>
        <p class="success-message" id="successMessage">
            Thomas und ich haben besprochen, dass man in so einem Fall eine Stadtteilerweiterung machen sollte. Mehr Häuser, mehr Platz, bessere Verteilung, weniger Kollisionen.
        </p>
        <div class="success-buttons">
            <button class="btn-primary" onclick="nextLevel()">Weiter zu Level 11 →</button>
        </div>
    </div>
</div>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function() {
        // --- Level 10 Setup ---
        const HASH_SIZE = 20;
        let stadt = new Array(HASH_SIZE).fill(null);
        let occupiedHouses = 19;
        let gameCompleted = false;
        let currentDialogueStep = -1;
        let probingActive = false;
        let maxProbes = 6;
        let houseAssets = [];
        let phase = '';
        // Paare der neuen Assets (für JavaScript)
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
        // Sound-Dateien laden
        const soundClick   = new Audio('./assets/sounds/click.mp3');
        const soundSuccess = new Audio('./assets/sounds/success.mp3');
        const soundError   = new Audio('./assets/sounds/error.mp3');

        const dialogueAudios = [
            new Audio('./assets/sounds/Lvl10/Lvl10_1.mp3'),
            new Audio('./assets/sounds/Lvl10/Lvl10_2.mp3'),
            new Audio('./assets/sounds/Lvl10/Lvl10_3.mp3'),
            new Audio('./assets/sounds/Lvl10/Lvl10_4.mp3'),
            new Audio('./assets/sounds/Lvl10/Lvl10_5.mp3'),
            new Audio('./assets/sounds/Lvl10/Lvl10_6.mp3'),
            new Audio('./assets/sounds/Lvl10/Lvl10_7.mp3'),
            new Audio('./assets/sounds/Lvl10/Lvl10_8.mp3'),
            new Audio('./assets/sounds/Lvl10/Lvl10_9.mp3')
        ];
        let currentAudioObj = null;
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

        // Funktion zum Setzen des Haus-Assets
        function setHouseAsset(houseElement, isFilled) {
            const houseIndex = parseInt(houseElement.data('house'));
            const asset = isFilled ? housePairs[houseIndex % housePairs.length].filled : housePairs[houseIndex % housePairs.length].empty;
            houseElement.find('.house-icon').attr('src', `./assets/${asset}`);
        }

        // Initialisierung der Stadt
        function initCity() {
            const initialResidents = [
                {name: "Thomas", house: 0}, {name: "Laura", house: 1}, {name: "Paul", house: 2},
                {name: "Clara", house: 3}, {name: "Emma", house: 4}, {name: "Elena", house: 5},
                {name: "Mueller", house: 6}, {name: "Jonas", house: 7}, {name: "David", house: 8},
                {name: "Stefan", house: 9}, {name: "Tobias", house: 10}, {name: "Bernd", house: 11},
                {name: "Anton", house: 12}, {name: "Legat", house: 13}, {name: "Lea", house: 14},
                {name: "Thorsten", house: 15}, {name: "Sophie", house: 16}, {name: "Katrin", house: 17},
                {name: "Leon", house: 18}
            ];
            initialResidents.forEach(resident => {
                stadt[resident.house] = resident.name;
                const houseElement = $(`.house[data-house="${resident.house}"]`);
                setHouseAsset(houseElement, true);
                houseElement.addClass('checked');
            });
            $('#occupiedCount').text(occupiedHouses + ' / 20');
        }

        // Alle Dialoge in einer Liste
        const dialogueSequence = [
            "Separate Chaining erzeugt bei vielen Daten lange Listen, die die Such Performance beeinträchtigen. Außerdem können einige Speicherbereiche ungenutzt bleiben. Also entstehen sehr große Mehrfamilienhäuser, in denen man dann auch keine Bewohner schnell findet. Zudem können Häuser so auch leer stehen bleiben.",
            "Ich habe hier mal etwas vorbereitet. 19 Bewohner sind bereits eingezogen, somit sind die Häuser 0 bis 18 belegt.",
            "Nun trage Levi in diesen Stadtteil ein und nutze Linear Probing.",
            "Levi soll die Hausnummer 0 haben, leider ist sie belegt, aber nach dem Prinzip des Linear Probings können wir ja einfach das nächste freie Haus nehmen. Das sollte kein Problem sein, oder?",
            "Der Computer sieht nicht, welche Stelle im Speicher belegt ist oder nicht. Er muss jedes Haus einzeln prüfen. Das sollst du nun auch nachvollziehen, indem du jedes Haus der Reihe nach durchgehst!",
            "Ganz schön viel Aufwand, was? Die Stadt ist einfach zu voll, das könnte mit Hashmaps genauso passieren.",
            "Schauen wir uns diesen Stadtteil nochmal genauer an. 19 von 20 Häusern sind belegt, die Anzahl der belegten Häuser durch die Anzahl der Häuser insgesamt ist der Load-Factor.",
            "Dieser ist ein aussagekräftiges Mittel, um zu bestimmen, wie voll eine Hashmap bzw. der Stadtteil ist. Wenn dieser über 0,75 liegt, entstehen riesige Suchketten und die Verfahren verlieren an Effizienz.",
            "Glücklicherweise haben wir dir eine Hilfe bereitgestellt, welcher immer die Übersicht bewahrt. Dieser zeigt gerade 0,95. Das ist viel zu hoch für eine effiziente Stadt, also sollten wir trotz der hohen Kosten eine Stadterweiterung durchführen."
        ];

        // Funktion zum Anzeigen eines bestimmten Dialogs
        function showDialogue(step) {
            playDialogueAudio(step);
            $('#dialogueText').fadeOut(200, function() {
                $(this).text(dialogueSequence[step]).fadeIn(200);
                if (step === 3) {
                    probingActive = true;
                    const startHash = parseInt($('#hashResult').text());
                    currentProbeIndex = startHash;
                    $(`.house[data-house="${currentProbeIndex}"]`).addClass('highlight-target');
                }
                if (step === 9) {
                    playSound('success');
                    gameCompleted = true;
                    $('#successOverlay').css('display', 'flex');
                }
            });
        }

        // --- Listener für Dialoge ---
        $(document).keydown(function(e) {
            if ((e.key === 'Enter' || e.key === ' ') && !gameCompleted) {
                if (currentDialogueStep === -1) {
                    currentDialogueStep = 0;
                    showDialogue(0);
                    return;
                }
                if (currentDialogueStep < 2 || (currentDialogueStep > 4 && currentDialogueStep < 9)) {
                    if(currentDialogueStep === 1){
                        $('#dialogueContinue').hide();
                    }
                    currentDialogueStep++;
                    showDialogue(currentDialogueStep);
                }
            }
        });

        $('.dialogue-box').click(function() {
            if (!gameCompleted) {
                if (currentDialogueStep < 2 || (currentDialogueStep > 4 && currentDialogueStep < 9)) {
                    if(currentDialogueStep === 1){
                        $('#dialogueContinue').hide();
                    }
                    currentDialogueStep++;
                    showDialogue(currentDialogueStep);
                }
            }
        });

        // --- Level 10 Spielmechanik ---
        // 1. Familie aus der Liste auswählen
        $('#familienListe .to-do-family').click(function() {
            if (gameCompleted || currentDialogueStep < 2) return;
            const $item = $(this);
            const family = $item.data('family');
            $('#nameInput').val(family);
            $('#hashButton').prop('disabled', false);
            $('.to-do-family').removeClass('active');
            $item.addClass('active');
        });

        // Aktivieren des Buttons, sobald etwas eingegeben ist
        $('#nameInput').on('input', function() {
            if (currentDialogueStep < 2) {
                $(this).val('');
                $('#hashButton').prop('disabled', true);
                return;
            }
            if ($(this).val().trim() !== '') {
                $('#hashButton').prop('disabled', false);
            } else {
                $('#hashButton').prop('disabled', true);
            }
        });

        // 2. Hash-Wert berechnen (ereignisgebunden)
        $('#hashButton').click(function() {
            if (gameCompleted || phase === 'block_button') return;
            const family = $('#nameInput').val().trim();
            if (!family) return;
            const startHash = getHash(family, HASH_SIZE);
            $('#hashResult').text(startHash);
            currentDialogueStep = 3;
            showDialogue(currentDialogueStep);
            phase = 'block_button';
        });

        // 3. Haus klicken, um zum nächsten Haus zu gehen
        $('.house').click(function() {
            playSound('click');
            if (!probingActive || gameCompleted) return;
            const $house = $(this);
            const houseNumber = parseInt($house.data('house'));
            if (houseNumber === currentProbeIndex) {
                if(houseNumber === 0){
                    currentDialogueStep = 4;
                    showDialogue(currentDialogueStep);
                }
                $(`.house[data-house="${currentProbeIndex}"]`).removeClass('highlight-target');
                currentProbeIndex = (currentProbeIndex + 1) % HASH_SIZE;
                if (currentProbeIndex > maxProbes) {
                    probingActive = false;
                    currentDialogueStep = 5;
                    $('#dialogueContinue').show();
                    showDialogue(currentDialogueStep);
                    return;
                }
                $(`.house[data-house="${currentProbeIndex}"]`).addClass('highlight-target');
            }
        });

        // --- Hash-Funktion (zero-based) ---
        function getHash(key, size) {
            let sum = 0;
            for (let i = 0; i < key.length; i++) {
                sum += key.charCodeAt(i);
            }
            return sum % size;
        }

        // --- Global functions for buttons ---
        window.restartLevel = function() {
            location.reload();
        };

        window.nextLevel = function() {
            $('body').css('transition', 'opacity 0.5s ease');
            $('body').css('opacity', '0');
            setTimeout(function() {
                window.location.href = 'level-auswahl.php?completed=10&next=11';
            }, 500);
        };

        // Stadt initialisieren
        initCity();
        $('#dialogueText').text("..."); // Start-Text
        $('#dialogueContinue').show();  // Blinker zeigen
    });
</script>
</body>
</html>
