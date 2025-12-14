<?php
session_name("hashcity");
session_start();

if (!isset($_SESSION['levels_done'])) {
    $_SESSION['levels_done'] = [];
}

// Pagination
$levelsPerPage = 8;
$currentPage = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;

$isLevelSelected = 0;

if (isset($_REQUEST["completed"]) && $_REQUEST["completed"] !== "") {
    $completedLevel = intval($_REQUEST["completed"]);

    // Finde das höchste abgeschlossene Level
    $maxLevel = empty($_SESSION['levels_done']) ? -1 : max($_SESSION['levels_done']);

    // Prüfe ob das Level das nächste in der Reihe ist
    if ($completedLevel === $maxLevel + 1) {
        if (!in_array($completedLevel, $_SESSION['levels_done'])) {
            $_SESSION['levels_done'][] = $completedLevel;
            sort($_SESSION['levels_done']);
        }
    } else {
        header("Location: Level-Auswahl");
        exit;
    }
}

// Welches Level soll angezeigt/ausgewählt werden?
if (isset($_REQUEST["level"]) && $_REQUEST["level"] !== "") {
    $isLevelSelected = intval($_REQUEST["level"]);
}

// Levels aus Session holen
$levelsDone = $_SESSION['levels_done'];

$lastLevel = end($levelsDone);
if ($lastLevel !== 7 && isset($_REQUEST["page"]) && $_REQUEST["page"] === "2") {
    $currentUrl = $_SERVER['REQUEST_URI'];
    $newUrl = str_replace('page=2', 'page=1', $currentUrl);
    header("Location: " . $newUrl);
    exit;
}

// Alle verfügbaren Levels definieren
$allLevels = [
        ['title' => 'Einführung', 'description' => 'Lerne die Grundlagen von HashCity kennen'],
        ['title' => 'Grundlagen Hashmaps', 'description' => 'Verstehe, wie Hash-Funktionen Werte auf Indizes abbilden'],
        ['title' => 'Erste Kollision', 'description' => 'Was passiert, wenn zwei Werte denselben Index belegen?'],
        ['title' => 'Linear Probing', 'description' => 'Suche linear nach dem nächsten freien Platz'],
        ['title' => 'Linear Probing 2', 'description' => 'Wende Linear Probing in komplexeren Szenarien an'],
        ['title' => 'Quadratic Probing', 'description' => 'Nutze quadratische Schritte zur Kollisionsauflösung'],
        ['title' => 'Quadratic Probing 2', 'description' => 'Meistere quadratisches Sondieren mit mehr Kollisionen'],
        ['title' => 'Double Hashing', 'description' => 'Verwende eine zweite Hash-Funktion für die Schrittweite'],
        ['title' => 'Double-Hashing-2', 'description' => 'Perfektioniere Double Hashing bei hoher Auslastung'],
        ['title' => 'Separate-Chaining', 'description' => 'Verkette Einträge mit gleichem Index in Listen'],
        ['title' => 'Separate-Chaining-2', 'description' => 'Verwalte längere Ketten effizient'],
        ['title' => 'Load-Factor', 'description' => 'Verstehe den Füllgrad und seine Auswirkungen'],
        ['title' => 'Re-Hashing', 'description' => 'Vergrößere die Tabelle und ordne alle Elemente neu zu'],
        ['title' => 'Finale', 'description' => 'Zeige dein Können in der finalen Herausforderung'],
];

$totalLevels = count($allLevels);
$totalPages = ceil($totalLevels / $levelsPerPage);

// Verhindere ungültige Seitenzahlen
if ($currentPage > $totalPages) {
    header("Location: Level-Auswahl?page=" . $totalPages);
    exit;
}

// Berechne Start- und End-Index für aktuelle Seite
$startIndex = ($currentPage - 1) * $levelsPerPage;
$endIndex = min($startIndex + $levelsPerPage, $totalLevels);

// Filtere Levels für aktuelle Seite
$levels = array_slice($allLevels, $startIndex, $levelsPerPage);

// Flags für Navigation
$hasPrevPage = $currentPage > 1;
$hasNextPage = $currentPage < $totalPages;
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HashCity - Level Auswahl</title>
    <link rel="icon" type="image/png" sizes="32x32" href="./assets/icons8-hash-scribby-32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="./assets/icons8-hash-scribby-96.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Rajdhani:wght@300;500;700&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Rajdhani', sans-serif;
            overflow: hidden;
            height: 100vh;
            background: linear-gradient(180deg, #87CEEB 0%, #B0D4E3 50%, #4CAF50 100%);
            position: relative;
        }

        /* Sky Section */
        .sky-section {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 55%;
            background: linear-gradient(180deg, #87CEEB 0%, #B0D4E3 100%);
            z-index: 1;
            /* Wichtig: Dies schneidet die Hochhäuser oben ab, wenn sie zu groß sind */
            overflow: hidden;
        }

        /* Grass Section */
        .grass-section {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 45%;
            background: linear-gradient(180deg, #76B947 0%, #5FA73D 50%, #4CAF50 100%);
            z-index: 1;
            overflow: hidden;
        }

        /* Grass Blades */
        .grass-blade {
            position: absolute;
            width: 3px;
            background: linear-gradient(to top, #2D5016, #4CAF50);
            border-radius: 50% 50% 0 0;
            transform-origin: bottom center;
        }

        @keyframes grassMove {
            0% {
                right: -10px;
                opacity: 0;
            }
            5% {
                opacity: 1;
            }
            95% {
                opacity: 1;
            }
            100% {
                right: 110%;
                opacity: 0;
            }
        }

        /* Flowers */
        .flower {
            position: absolute;
            width: 20px;
            height: 20px;
            transform-origin: bottom center;
        }

        .flower-stem {
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 2px;
            height: 15px;
            background: #2D5016;
            transform: translateX(-50%);
        }

        .flower-head {
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 12px;
            height: 12px;
            border-radius: 50%;
            box-shadow: 0 0 5px rgba(0,0,0,0.2);
        }

        .flower-head::before,
        .flower-head::after {
            content: '';
            position: absolute;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: inherit;
        }

        .flower-head::before {
            top: -4px;
            left: 2px;
        }

        .flower-head::after {
            top: 4px;
            left: -3px;
        }

        .flower-center {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 5px;
            height: 5px;
            background: #FFD700;
            border-radius: 50%;
            z-index: 2;
        }

        /* Road */
        .road-container {
            position: absolute;
            top: 52%;
            left: 0;
            width: 100%;
            height: 200px;
            transform: translateY(-50%);
            z-index: 2;
            overflow: visible;
        }

        .main-road {
            position: absolute;
            top: 50%;
            left: 0;
            width: 100%;
            height: 100px;
            background: #6B6B6B;
            border-top: 3px solid #4A4A4A;
            border-bottom: 3px solid #4A4A4A;
            transform: translateY(-50%);
        }

        .road-line {
            position: absolute;
            top: 50%;
            width: 60px;
            height: 4px;
            background: #FFD700;
            transform: translateY(-50%);
            animation: roadLineMove 2s linear infinite;
        }

        @keyframes roadLineMove {
            0% { left: 100%; }
            100% { left: -60px; }
        }

        /* Truck */
        .truck {
            position: absolute;
            bottom: 100px;
            left: 0;
            width: 250px;
            height: auto;
            z-index: 10;
            transition: left 1.2s cubic-bezier(0.4, 0, 0.2, 1);
            filter: drop-shadow(0 5px 10px rgba(0,0,0,0.3));
            animation: tuckern 0.2s infinite ease-in-out;
        }

        @keyframes tuckern {
            0%, 100% {
                transform: translateY(0) translateX(-50%);
            }
            50% {
                transform: translateY(-2px) translateX(-50%);
            }
        }

        .truck img {
            width: 100%;
            height: auto;
            display: block;
        }

        /* Level Nodes Container */
        .levels-container {
            position: absolute;
            top: 65%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 95%;
            max-width: 1600px;
            height: 450px;
            z-index: 5;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 15px;
            padding: 0 20px;
        }

        .level-node {
            position: relative;
            width: 130px;
            height: 130px;
            background: linear-gradient(135deg, rgba(66, 88, 122, 0.95), rgba(52, 73, 94, 0.95));
            border: 4px solid rgba(41, 128, 185, 0.8);
            border-radius: 50%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 5;
            box-shadow: 0 8px 20px rgba(0,0,0,0.3),
            inset 0 2px 4px rgba(255, 255, 255, 0.1);
            flex-shrink: 0;
        }

        .level-node:hover:not(.locked):not(.active) {
            transform: scale(1.15);
            background: linear-gradient(135deg, rgba(66, 88, 122, 1), rgba(52, 73, 94, 1));
            box-shadow: 0 12px 30px rgba(0,0,0,0.4),
            0 0 20px rgba(41, 128, 185, 0.5);
        }

        /* Active Level */
        .level-node.active {
            width: 300px;
            height: 300px;
            background: linear-gradient(135deg, rgba(41, 128, 185, 0.98), rgba(52, 152, 219, 0.98));
            border-color: #3498db;
            border-width: 6px;
            box-shadow: 0 0 50px rgba(52, 152, 219, 0.8),
            0 15px 50px rgba(0,0,0,0.5),
            inset 0 2px 8px rgba(255, 255, 255, 0.2);
            z-index: 10;
            transform: translateY(70px);
        }

        .level-node.active:hover {
            transform: translateY(70px) scale(1.03);
        }

        .level-node.locked {
            background: linear-gradient(135deg, rgba(127, 140, 141, 0.7), rgba(149, 165, 166, 0.7));
            border-color: #95a5a6;
            cursor: not-allowed;
            opacity: 0.6;
        }

        .level-node.locked:hover {
            transform: scale(1);
        }

        .level-title {
            font-family: 'Orbitron', sans-serif;
            font-size: 2.5rem;
            font-weight: 700;
            color: #ecf0f1;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
            margin-bottom: 0;
            transition: all 0.5s ease;
        }

        .level-node.active .level-title {
            font-size: 4rem;
            margin-bottom: 0.5rem;
            color: #ffffff;
            text-shadow: 0 3px 8px rgba(0,0,0,0.4);
        }

        .level-number {
            font-size: 1rem;
            color: #bdc3c7;
            font-weight: 600;
            transition: all 0.5s ease;
        }

        .level-node.active .level-number {
            font-size: 1.5rem;

            margin-bottom: 0.3rem;
            color: #ecf0f1;
        }

        .level-status {
            position: absolute;
            top: 8px;
            right: 8px;
            font-size: 1.8rem;
            transition: all 0.5s ease;
        }

        .level-node.active .level-status {
            top: 15px;
            right: 15px;
            font-size: 2.5rem;
        }

        /* Level Details */
        .level-details {
            opacity: 0;
            transform: translateY(10px);
            transition: all 0.5s ease;
            text-align: center;
            padding: 0 1.5rem;
            max-height: 0;
            overflow: hidden;
        }

        .level-node.active .level-details {
            opacity: 1;
            transform: translateY(0);
            max-height: 200px;
        }

        .level-subtitle {
            font-size: 1.3rem;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 0.5rem;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }

        .level-description {
            font-size: 0.95rem;
            color: #ecf0f1;
            line-height: 1.4;
        }

        /* Connection Dots */
        .connection-dot {
            position: absolute;
            display: none;
            width: 16px;
            height: 16px;
            background: rgba(185, 244, 188, 0.9);
            border: 3px solid #4CAF50;
            border-radius: 50%;
            z-index: 4;
            bottom: 95px;
            transition: all 0.3s ease;
        }

        /* Header */
        .level-select-header {
            position: absolute;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 100;
            text-align: center;
        }

        .header-title {
            font-family: 'Orbitron', sans-serif;
            font-size: 2.2rem;
            font-weight: 900;
            color: #fff;
            text-shadow: 0 0 10px rgba(0,0,0,0.3),
            0 0 20px rgba(102, 126, 234, 0.5);
            margin-bottom: 0.3rem;
        }

        .header-subtitle {
            font-size: 1.1rem;
            color: rgba(255, 255, 255, 0.95);
            font-weight: 500;
        }

        .page-indicator {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.85);
            font-weight: 400;
            margin-top: 0.3rem;
        }

        /* Back Button */
        .back-button {
            position: absolute;
            top: 20px;
            left: 20px;
            padding: 0.6rem 1.1rem;
            background: rgba(52, 73, 94, 0.7);
            border: 2px solid rgba(52, 152, 219, 0.3);
            border-radius: 25px;
            font-weight: 600;
            color: rgba(236, 240, 241, 0.9);
            cursor: pointer;
            transition: all 0.3s ease;
            z-index: 100;
            font-family: 'Orbitron', sans-serif;
            text-decoration: none;
            display: inline-block;
            font-size: 0.85rem;
            backdrop-filter: blur(5px);
        }

        .back-button:hover {
            background: rgba(52, 73, 94, 0.85);
            border-color: rgba(52, 152, 219, 0.6);
            color: #ecf0f1;
            transform: translateX(-3px);
        }

        .back-button::before {
            content: '← ';
            margin-right: 5px;
            opacity: 0.8;
        }

        /* Start Button */
        .start-level-btn {
            position: absolute;
            bottom: -50px;
            left: 50%;
            transform: translateX(-50%);
            padding: 0.8rem 2rem;
            background: linear-gradient(135deg, #FF8C00 0%, #FF6347 100%);
            color: #fff;
            border: none;
            border-radius: 30px;
            font-weight: 700;
            cursor: pointer;
            font-family: 'Orbitron', sans-serif;
            font-size: 1rem;
            opacity: 0;
            transition: all 0.5s ease;
            white-space: nowrap;
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
        }

        .start-level-btn:hover {
            box-shadow:
                    0 20px 80px rgba(0, 0, 0, 0.6),
                    0 0 80px rgba(255, 140, 0, 1),
                    inset 0 -3px 10px rgba(0, 0, 0, 0.2);
            transform: translateX(-50%) scale(1.05);
        }

        .level-node.active .start-level-btn {
            opacity: 1;
        }

        /* Navigation Arrows */
        .nav-arrow {
            position: absolute;
            top: 52%;
            transform: translateY(-50%);
            width: 60px;
            height: 60px;
            background: rgba(255, 215, 0, 0.95);
            border: 3px solid #FFA500;
            border-radius: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            transition: all 0.3s ease;
            z-index: 15;
            font-size: 2rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            color: #333;
            font-weight: bold;
        }

        .nav-arrow:hover {
            background: #FFA500;
            color: #fff;
            transform: translateY(-50%) scale(1.1);
            box-shadow: 0 8px 20px rgba(0,0,0,0.4);
        }

        .nav-arrow.disabled {
            opacity: 0.2;
            cursor: not-allowed;
            pointer-events: none;
        }

        .nav-arrow-left {
            left: 20px;
        }

        .nav-arrow-right {
            right: 20px;
        }

        /* Progress Bar */
        .progress-bar-container {
            position: absolute;
            top: 20px;
            right: 20px;
            width: 250px;
            z-index: 100;
        }

        .progress-text {
            text-align: center;
            color: #fff;
            font-weight: 600;
            margin-bottom: 0.5rem;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
            font-size: 0.9rem;
        }

        .progress {
            height: 20px;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 15px;
            overflow: hidden;
        }

        .progress-bar {
            background: linear-gradient(90deg, #4CAF50, #8BC34A);
            font-weight: 700;
            transition: width 0.5s ease;
            font-size: 0.85rem;
            line-height: 20px;
        }

        /* Clouds */
        .cloud {
            position: absolute;
            background: rgba(255, 255, 255, 0.7);
            border-radius: 100px;
            opacity: 0.8;
            animation: cloudFloat 30s linear infinite;
        }

        @keyframes cloudFloat {
            0% { left: -200px; }
            100% { left: 110%; }
        }

        .cloud::before,
        .cloud::after {
            content: '';
            position: absolute;
            background: rgba(255, 255, 255, 0.7);
            border-radius: 100px;
        }

        /* Houses & City Animation (General) */
        @keyframes houseMove {
            0% {
                right: -800px; /* Start weiter rechts, da die Städte jetzt viel breiter sind */
                opacity: 0;
            }
            5% {
                opacity: 1;
            }
            95% {
                opacity: 1;
            }
            100% {
                right: 110%;
                opacity: 0;
            }
        }

        /* Single House/Tree (Bleiben normal groß) */
        .house {
            position: absolute;
            top: auto;
            bottom: 60px;
            width: 120px;
            height: auto;
            z-index: 2;
            filter: drop-shadow(0 5px 15px rgba(0,0,0,0.2));
            animation: houseMove linear;
        }

        .house img {
            width: 100%;
            height: auto;
            display: block;
        }

        /* --- CITY CSS (MASSIV VERGRÖSSERT) --- */
        .city-group {
            position: absolute;
            bottom: 60px;
            z-index: 1; /* Hinter dem LKW/Straße */
            display: flex;
            align-items: flex-end;
            gap: 5px;
            animation: houseMove linear;
            pointer-events: none;
        }

        .stacked-house-container {
            position: relative;
            display: flex;
            flex-direction: column-reverse; /* WICHTIG: Stapelt von unten nach oben */
            align-items: center;
            /* HIER: Viel breiter als normale Häuser */
            width: 220px;
            line-height: 0; /* WICHTIG: Entfernt Whitespace zwischen Bildern */
        }

        .img-house-base {
            width: 100%;
            height: auto;
            display: block;
            position: relative;
            z-index: 1;
            filter: drop-shadow(0 4px 6px rgba(0,0,0,0.3));
        }

        .img-house-extension {
            width: 100%;
            height: auto;
            display: block;
            position: relative;
            z-index: 10;
            margin-bottom: -10px; /* STARKER NEGATIVER MARGIN gegen den weißen Strich */
        }

        /* Responsive */
        @media (max-width: 1400px) {
            .level-node { width: 110px; height: 110px; }
            .level-node.active { width: 260px; height: 260px; }
            .level-title { font-size: 2rem; }
            .level-node.active .level-title { font-size: 3.5rem; }

            /* Anpassung der Riesenhäuser für kleinere Screens, aber immer noch groß */
            .stacked-house-container { width: 180px; }
        }

        @media (max-width: 1024px) {
            .levels-container { gap: 10px; }
            .level-node { width: 90px; height: 90px; }
            .level-node.active { width: 220px; height: 220px; }
            .level-title { font-size: 1.8rem; }
            .level-node.active .level-title { font-size: 3rem; }

            .house { width: 60px; }
            .stacked-house-container { width: 140px; } /* Immer noch deutlich größer als .house */

            .nav-arrow { width: 50px; height: 50px; font-size: 1.6rem; }
        }

        @media (max-width: 768px) {
            .header-title { font-size: 1.6rem; }
            .header-subtitle { font-size: 0.9rem; }
            .levels-container { flex-wrap: wrap; height: auto; gap: 15px; padding: 20px; }
            .level-node { width: 80px; height: 80px; }
            .level-node.active { width: 180px; height: 180px; }
            .level-title { font-size: 1.5rem; }
            .level-node.active .level-title { font-size: 2.5rem; }
            .truck { width: 100px; }

            .house { width: 50px; }
            .stacked-house-container { width: 100px; }

            .nav-arrow { width: 45px; height: 45px; font-size: 1.4rem; top: 50%; }
            .nav-arrow-left { left: 10px; }
            .nav-arrow-right { right: 10px; }
        }

        @media (max-width: 480px) {
            .truck { width: 80px; }
            .nav-arrow { width: 40px; height: 40px; font-size: 1.2rem; }
            .nav-arrow-left { left: 5px; }
            .nav-arrow-right { right: 5px; }
        }
    </style>
</head>
<body>
<div class="sky-section" id="skySection">
    <div class="cloud" style="width: 100px; height: 50px; top: 10%; animation-delay: 0s;"></div>
    <div class="cloud" style="width: 120px; height: 60px; top: 20%; animation-delay: 8s;"></div>
    <div class="cloud" style="width: 90px; height: 45px; top: 30%; animation-delay: 16s;"></div>
</div>

<div class="grass-section" id="grassSection"></div>

<div class="level-select-header">
    <h1 class="header-title">#CITY - Level Auswahl</h1>
    <p class="header-subtitle">Wähle dein Level und starte deine Hash-Map Reise</p>
    <?php if ($totalPages > 1): ?>
        <p class="page-indicator">Seite <?php echo $currentPage; ?> von <?php echo $totalPages; ?></p>
    <?php endif; ?>
</div>

<a href="Start" class="back-button">Zurück</a>

<div class="progress-bar-container">
    <div class="progress-text">Fortschritt: <span id="progressText">0/<?php echo $totalLevels; ?> Levels abgeschlossen</span></div>
    <div class="progress">
        <div class="progress-bar" role="progressbar" id="progressBar" style="width: 0%">0%</div>
    </div>
</div>

<div class="nav-arrow nav-arrow-left <?php echo !$hasPrevPage ? 'disabled' : ''; ?>"
     id="prevPageArrow"
        <?php if ($hasPrevPage): ?>
            onclick="window.location.href='Level-Auswahl?page=<?php echo $currentPage - 1; ?>'"
        <?php endif; ?>>
    ◀
</div>


<div class="nav-arrow nav-arrow-right <?php
$lastLevel = end($levelsDone);
if ($lastLevel !== 7 || !$hasNextPage) {
    echo "disabled";
}
?>"
     id="nextPageArrow"
        <?php if ($hasNextPage && $lastLevel === 7): ?>
            onclick="window.location.href='Level-Auswahl?page=<?php echo $currentPage + 1; ?>'"
        <?php endif; ?>>
    ▶
</div>


<div class="road-container">
    <div class="main-road">
        <div class="road-line" style="animation-delay: 0s;"></div>
        <div class="road-line" style="animation-delay: 0.5s;"></div>
        <div class="road-line" style="animation-delay: 1s;"></div>
        <div class="road-line" style="animation-delay: 1.5s;"></div>
    </div>

    <div id="truck" class="truck">
        <img src="./assets/Postauto.svg" alt="Postauto">
    </div>
</div>

<div class="levels-container" id="levelsContainer">
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    $(document).ready(function() {

        // --- SOUND SETUP START ---
        const audioBg = new Audio('./sounds/background.wav');
        audioBg.loop = true;
        audioBg.volume = 0.9; // 30% Lautstärke

        const audioDrive = new Audio('./sounds/drive.mp3');
        const audioStop = new Audio('./sounds/stop.mp3');

        // Versuch: Sofort abspielen
        var playPromise = audioBg.play();

        if (playPromise !== undefined) {
            playPromise.catch(error => {
                // Browser Autoplay Policy hat das Abspielen verhindert
                console.log("Autoplay blockiert, warte auf Interaktion.");
                // Fallback: Beim ersten Klick/Tastendruck/Touch starten
                $(document).one('click keydown touchstart', function() {
                    audioBg.play();
                });
            });
        }
        // --- SOUND SETUP END ---

        let currentLevel = 0;

        <?php
        if ($isLevelSelected !== 0) {
        // Prüfen ob das ausgewählte Level auf dieser Seite ist
        if ($isLevelSelected >= $startIndex && $isLevelSelected < $endIndex) {
        $selectedIsCompleted = in_array($isLevelSelected, $levelsDone);
        $selectedIsUnlocked = $selectedIsCompleted || ($isLevelSelected > 0 && in_array($isLevelSelected - 1, $levelsDone));

        // Level 0 ist immer unlocked
        if ($isLevelSelected === 0) {
            $selectedIsUnlocked = true;
        }

        // Nur setzen wenn Level freigeschaltet ist
        if ($selectedIsUnlocked) {
        // Lokaler Index auf der aktuellen Seite
        $localIndex = $isLevelSelected - $startIndex;
        ?>
        currentLevel = <?php echo $localIndex; ?>;
        <?php
        }
        }
        }
        ?>

        let previousLevelIndex = currentLevel; // Initiale Position merken

        // Level Data - dynamisch aus PHP generiert
        const levelData = [
            <?php
            foreach ($levels as $arrayIndex => $level) {
                $globalIndex = $startIndex + $arrayIndex;
                $isCompleted = in_array($globalIndex, $levelsDone);
                $isUnlocked = $isCompleted || ($globalIndex > 0 && in_array($globalIndex - 1, $levelsDone));

                // Level 0 ist immer unlocked
                if ($globalIndex === 0) {
                    $isUnlocked = true;
                }

                echo "{
                title: '" . addslashes($level['title']) . "',
                description: '" . addslashes($level['description']) . "',
                icon: '',
                unlocked: " . ($isUnlocked ? 'true' : 'false') . ",
                completed: " . ($isCompleted ? 'true' : 'false') . ",
                globalIndex: " . $globalIndex . "
            }";

                if ($arrayIndex < count($levels) - 1) {
                    echo ",\n            ";
                }
            }
            ?>
        ];

        // Pagination-Daten
        const currentPage = <?php echo $currentPage; ?>;
        const totalPages = <?php echo $totalPages; ?>;
        const hasPrevPage = <?php echo $hasPrevPage ? 'true' : 'false'; ?>;
        const hasNextPage = <?php echo $hasNextPage ? 'true' : 'false'; ?>;
        const startIndex = <?php echo $startIndex; ?>;
        const totalLevels = <?php echo $totalLevels; ?>;

        // Grass decoration system
        const flowerColors = [
            '#FF69B4', '#FFD700', '#FF6347', '#87CEEB',
            '#DDA0DD', '#FFA500', '#FF1493', '#00CED1'
        ];

        function createGrassBlade() {
            const bottom = Math.random() * 80 + '%';
            const height = 15 + Math.random() * 25;
            const duration = 2;

            const grassBlade = $(`
                <div class="grass-blade" style="
                    bottom: ${bottom};
                    height: ${height}px;
                    animation: grassMove ${duration}s linear;
                "></div>
            `);

            $('#grassSection').append(grassBlade);

            setTimeout(() => {
                grassBlade.remove();
            }, duration * 1000);
        }

        function createFlower() {
            const bottom = Math.random() * 80 + '%';
            const color = flowerColors[Math.floor(Math.random() * flowerColors.length)];
            const scale = 0.8 + Math.random() * 0.4;
            const duration = 2;

            const flower = $(`
                <div class="flower" style="
                    bottom: ${bottom};
                    transform: scale(${scale});
                    animation: grassMove ${duration}s linear;
                ">
                    <div class="flower-stem"></div>
                    <div class="flower-head" style="background: ${color};">
                        <div class="flower-center"></div>
                    </div>
                </div>
            `);

            $('#grassSection').append(flower);

            setTimeout(() => {
                flower.remove();
            }, duration * 1000);
        }

        function startGrassAnimation() {
            // Initiale Elemente
            for (let i = 0; i < 20; i++) {
                setTimeout(() => createGrassBlade(), Math.random() * 2000);
            }
            for (let i = 0; i < 5; i++) {
                setTimeout(() => createFlower(), Math.random() * 2000);
            }

            // Kontinuierliches Spawning
            setInterval(() => {
                for (let i = 0; i < 3; i++) {
                    createGrassBlade();
                }
            }, 200);

            setInterval(() => {
                createFlower();
            }, 800);
        }

        const trees = ["Baum1.svg", "Baum2.svg", "Baum3.svg"];
        const singleHouses = [
            "WohnhauBlauBraunBesetztNeu.svg",
            "WohnhauBlauGrauBesetztNeu.svg",
            "WohnhauBlauRotBesetztNeu.svg",
            "WohnhauGelbBraunBesetztNeu.svg",
            "WohnhauGelbRotBesetztNeu.svg",
            "WohnhauGrauBraunBesetztNeu.svg",
            "WohnhauGruenBraunBesetztNeu.svg",
            "WohnhauGruenGrauBesetztNeu.svg",
            "WohnhauRotRotBesetztNeu.svg",
            "WohnhauRotBraunBesetztNeu.svg"
        ];

        // --- LEVEL 9 ASSETS für Städte ---
        const stackedHousePairs = [
            { base: "Wohnhaus2BlauBraun.svg", extension: "WohnhausBlauBraunErweiterung.svg" },
            { base: "Wohnhaus2BlauGrau.svg", extension: "WohnhausBlauGrauErweiterung.svg" },
            { base: "Wohnhaus2BlauRot.svg", extension: "WohnhausBlauRotErweiterung.svg" },
            { base: "Wohnhaus2GrauBraun.svg", extension: "WohnhausGrauBraunErweiterung.svg" },
        ];

        const fence = "Zaun1.svg";

        let consecutiveHouses = 0;
        let stepsSinceLastCity = 5; // Initialwert, damit schnell eine Stadt kommen kann

        function createObject(objectType) {
            const duration = 15 + 1 * 1;

            let size = 0;
            if (objectType === "Zaun1.svg") {
                size = 120;
            } else {
                size = 200;
            }

            const object = $(`
                <div class="house" style="
                    width: ${size}px;
                    animation-duration: ${duration}s;
                ">
                    <img src="./assets/${objectType}" alt="Object">
                </div>
            `);

            $('#skySection').append(object);

            setTimeout(() => {
                object.remove();
            }, duration * 1000);
        }

        // --- City Spawner ---
        function createCity() {
            const duration = 15; // SCHNELLER: Angepasst an die anderen Objekte (vorher 20)
            const numberOfHouses = 3 + Math.floor(Math.random() * 4); // 3-6 Häuser pro Cluster

            // Container für die Stadt-Gruppe
            const cityGroup = $(`<div class="city-group" style="animation-duration: ${duration}s;"></div>`);

            for(let i = 0; i < numberOfHouses; i++) {
                // HIER GEÄNDERT: Viel höher (3-9 Stockwerke), damit sie oben raus ragen
                const stories = 3 + Math.floor(Math.random() * 7);
                const pair = stackedHousePairs[Math.floor(Math.random() * stackedHousePairs.length)];

                // Container für einzelnes gestapeltes Haus (flex-direction: column-reverse)
                const houseContainer = $(`<div class="stacked-house-container"></div>`);

                // Basis-Haus
                houseContainer.append(`<img src="./assets/${pair.base}" alt="Haus Basis" class="img-house-base">`);

                // Erweiterungen (Stockwerke)
                for(let j = 1; j < stories; j++) {
                    houseContainer.append(`<img src="./assets/${pair.extension}" alt="Haus Erweiterung" class="img-house-extension">`);
                }

                cityGroup.append(houseContainer);
            }

            $('#skySection').append(cityGroup);

            setTimeout(() => {
                cityGroup.remove();
            }, duration * 1000);
        }

        function spawnNext() {
            stepsSinceLastCity++; // Zähler erhöhen

            // Nach 3 Häusern muss ein Baum kommen
            if (consecutiveHouses >= 3) {
                spawnTree();
                consecutiveHouses = 0;
            } else {
                // Entscheidung: Stadt, Einzelhaus oder Baum?
                const rand = Math.random();

                // Cooldown: 5 Schritte. Chance: 20%
                if (stepsSinceLastCity > 5 && rand < 0.20) {
                    createCity();
                    consecutiveHouses++;
                    stepsSinceLastCity = 0; // Reset Cooldown
                } else if (rand < 0.60) {
                    // Chance für Einzelhaus (wenn keine Stadt)
                    spawnSingleHouse();
                    consecutiveHouses++;
                } else {
                    // Chance für Baum (Rest)
                    spawnTree();
                    consecutiveHouses = 0;
                }
            }
        }

        function spawnSingleHouse() {
            const houseType = singleHouses[Math.floor(Math.random() * singleHouses.length)];
            createObject(houseType);
        }

        function spawnTree() {
            const treeType = trees[Math.floor(Math.random() * trees.length)];
            createObject(treeType);

            // 30% Chance für einen Zaun zusammen mit dem Baum
            if (Math.random() < 0.3) {
                setTimeout(() => {
                    createObject(fence);
                }, 500);
            }
        }

        function startHouseSpawning() {
            // Initiale Objekte
            spawnNext();
            setTimeout(() => spawnNext(), 1500);
            setTimeout(() => spawnNext(), 3000);

            function scheduleNext() {
                const baseDelay = 2500;
                const variation = 500;
                const delay = baseDelay - variation/2 + Math.random() * variation;

                setTimeout(() => {
                    spawnNext();
                    scheduleNext();
                }, delay);
            }

            setTimeout(() => {
                scheduleNext();
            }, 3000);
        }

        // Generate Levels
        function generateLevels() {
            const container = $('#levelsContainer');
            container.empty();

            levelData.forEach((level, localIndex) => {
                const isActive = localIndex === currentLevel;
                const lockedClass = level.unlocked ? '' : 'locked';
                const activeClass = isActive ? 'active' : '';
                const completedIcon = level.completed ? '✔️' : level.icon;

                const levelNode = $(`
                    <div class="level-node ${lockedClass} ${activeClass}" data-level="${localIndex}" data-global-level="${level.globalIndex}">
                        <span class="level-status">${level.unlocked ? completedIcon : '❌'}</span>
                        <div class="level-number">Level</div>
                        <div class="level-title">${level.globalIndex}</div>
                        <div class="level-details">
                            <div class="level-subtitle">${level.title}</div>
                            <div class="level-description">${level.description}</div>
                        </div>
                        <button class="start-level-btn">Level starten</button>
                    </div>
                `);

                container.append(levelNode);

                // Add connection dot
                const dotPosition = (100 / 8) * localIndex + (100 / 16);
                const dot = $(`<div class="connection-dot" style="left: ${dotPosition}%;"></div>`);
                $('.road-container').append(dot);
            });
        }

        // Move truck to level (MODIFIED FOR SOUND DIRECTION)
        function moveTruckToLevel(level) {
            const truck = $('#truck');
            const position = (100 / 8) * level + (100 / 16);

            // 1. Alle Bewegungssounds stoppen/resetten
            audioDrive.pause();
            audioDrive.currentTime = 0;
            audioStop.pause();
            audioStop.currentTime = 0;

            // 2. Richtung bestimmen
            if (level > previousLevelIndex) {
                // Vorwärts -> Drive Sound
                audioDrive.play().catch(e => {});
            } else if (level < previousLevelIndex) {
                // Rückwärts -> Stop Sound
                audioStop.play().catch(e => {});
            }
            // (Bei level == previousLevelIndex passiert nichts, z.B. initiales Laden)

            // 3. Position aktualisieren
            previousLevelIndex = level;

            truck.css({
                'left': position + '%',
                'transform': 'translateX(-50%)'
            });
        }

        // Update progress bar
        function updateProgress() {
            const completed = <?php echo count($levelsDone); ?>;
            const percentage = (completed / totalLevels) * 100;

            $('#progressBar').css('width', percentage + '%').text(Math.round(percentage) + '%');
            $('#progressText').text(`${completed}/${totalLevels} Levels abgeschlossen`);
        }

        // Level Node Click
        $(document).on('click', '.level-node', function(e) {
            if ($(e.target).hasClass('start-level-btn')) {
                return;
            }

            const level = parseInt($(this).data('level'));
            console.log(level);
            const isLocked = $(this).hasClass('locked');

            if (isLocked) {
                // Shake animation for locked levels
                $(this).css('animation', 'shake 0.5s');
                setTimeout(() => {
                    $(this).css('animation', '');
                }, 500);
                return;
            }

            // Update current level
            currentLevel = level;

            // Update active state
            $('.level-node').removeClass('active');
            $(this).addClass('active');

            // Move truck
            moveTruckToLevel(level);
        });

        // Start Level Button
        $(document).on('click', '.start-level-btn', function(e) {
            e.stopPropagation();

            const globalLevel = parseInt($(this).parent().data('global-level'));
            $(this).text('Wird geladen...');
            $(this).prop('disabled', true);

            // Fade out effect
            $('body').css('transition', 'opacity 0.8s ease-out');
            setTimeout(() => {
                $('body').css('opacity', '0');
            }, 100);

            let level_name = "";

            switch(globalLevel) {
                case 0:
                    level_name = "Einführung";
                    break;
                case 1:
                    level_name = "Grundlagen-Hashmaps";
                    break;
                case 2:
                    level_name = "Erste-Kollision";
                    break;
                case 3:
                    level_name = "Linear-Probing";
                    break;
                case 4:
                    level_name = "Linear-Probing-2";
                    break;
                case 5:
                    level_name = "Quadratic-Probing";
                    break;
                case 6:
                    level_name = "Quadratic-Probing-2";
                    break;
                case 7:
                    level_name = "Double Hashing";
                    break;
                case 8:
                    level_name = "Double-Hashing-2";
                    break;
                case 9:
                    level_name = "Separate-Chaining";
                    break;
                case 10:
                    level_name = "Separate-Chaining-2";
                    break;
                case 11:
                    level_name = "Load-Factor";
                    break;
                case 12:
                    level_name = "Re-Hashing";
                    break;
                case 13:
                    level_name = "Finale";
                    break;
                default:
                    level_name = "Level-" + globalLevel;
            }

            // Redirect to game level
            setTimeout(() => {
                window.location.href = level_name;
            }, 1000);
        });

        // Keyboard Navigation
        $(document).keydown(function(e) {
            const unlockedLevels = levelData.map((l, i) => l.unlocked ? i : null).filter(i => i !== null);
            const currentIndex = unlockedLevels.indexOf(currentLevel);

            if (e.key === 'ArrowRight') {
                if (currentIndex < unlockedLevels.length - 1) {
                    const nextLevel = unlockedLevels[currentIndex + 1];
                    $(`.level-node[data-level="${nextLevel}"]`).click();
                } else if (hasNextPage) {
                    // Zur nächsten Seite
                    window.location.href = 'Level-Auswahl?page=' + (currentPage + 1);
                }
            } else if (e.key === 'ArrowLeft') {
                if (currentIndex > 0) {
                    const prevLevel = unlockedLevels[currentIndex - 1];
                    $(`.level-node[data-level="${prevLevel}"]`).click();
                } else if (hasPrevPage) {
                    // Zur vorherigen Seite
                    window.location.href = 'Level-Auswahl?page=' + (currentPage - 1);
                }
            } else if (e.key === 'Enter') {
                $('.level-node.active .start-level-btn').click();
            }
        });

        // Add shake animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes shake {
                0%, 100% { transform: translateX(0); }
                25% { transform: translateX(-10px); }
                75% { transform: translateX(10px); }
            }
        `;
        document.head.appendChild(style);

        // Add cloud details
        $('.cloud').each(function(index) {
            $(this).append(`
                <div style="position: absolute; width: 70%; height: 70%; background: rgba(255,255,255,0.7); border-radius: 100px; left: 25%; top: -20%;"></div>
                <div style="position: absolute; width: 60%; height: 60%; background: rgba(255,255,255,0.7); border-radius: 100px; right: 15%; top: -10%;"></div>
            `);
        });

        // Initialize
        generateLevels();
        moveTruckToLevel(currentLevel);
        console.log("current: " + currentLevel);
        updateProgress();
        startGrassAnimation();
        startHouseSpawning();
    });
</script>
</body>
</html>