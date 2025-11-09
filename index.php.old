<?php
// HashCity - Zentrale Steuerungsdatei
// Diese Datei verwaltet die gesamte Anwendung und Navigation

session_start();

// URL-Parameter verarbeiten
$page = isset($_GET['page']) ? $_GET['page'] : 'home';
$level = isset($_GET['level']) ? intval($_GET['level']) : null;
$action = isset($_GET['action']) ? $_GET['action'] : null;

// Benutzer-Session initialisieren falls nicht vorhanden
if (!isset($_SESSION['hashcity_user'])) {
    $_SESSION['hashcity_user'] = [
        'id' => uniqid(),
        'created' => time(),
        'progress' => [
            'maxUnlockedLevel' => 1,
            'currentLevel' => 1,
            'completedLevels' => [],
            'totalXP' => 0,
            'achievements' => []
        ]
    ];
}

// Routing-Logik
function routeRequest($page, $level, $action) {
    switch($page) {
        case 'level':
            if ($level && $level >= 1 && $level <= 6) {
                include __DIR__ . '/pages/dynamic_level.php';
                return;
            }
            // Fallback zur Level-Übersicht
            include __DIR__ . '/pages/level_overview.php';
            return;

        case 'overview':
        case 'levels':
            include __DIR__ . '/pages/level_overview.php';
            return;

        case 'tutorial':
            showTutorial();
            return;

        case 'about':
            showAbout();
            return;

        case 'api':
            handleAPI($action);
            return;

        case 'home':
        default:
            showHomepage();
            return;
    }
}

// API-Handler für AJAX-Requests
function handleAPI($action) {
    header('Content-Type: application/json');

    switch($action) {
        case 'save_progress':
            $input = json_decode(file_get_contents('php://input'), true);
            if ($input && is_array($input)) {
                $_SESSION['hashcity_user']['progress'] = array_merge(
                    $_SESSION['hashcity_user']['progress'],
                    $input
                );
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'error' => 'Invalid input']);
            }
            break;

        case 'get_progress':
            echo json_encode($_SESSION['hashcity_user']['progress']);
            break;

        case 'reset_progress':
            $_SESSION['hashcity_user']['progress'] = [
                'maxUnlockedLevel' => 1,
                'currentLevel' => 1,
                'completedLevels' => [],
                'totalXP' => 0,
                'achievements' => []
            ];
            echo json_encode(['success' => true]);
            break;

        default:
            http_response_code(400);
            echo json_encode(['error' => 'Unknown action']);
    }
    exit;
}

// Tutorial-Seite
function showTutorial() {
    include __DIR__ . '/templates/tutorial.php';
}

// About-Seite
function showAbout() {
    include __DIR__ . '/templates/about.php';
}

// Homepage anzeigen
function showHomepage() {
    ?>
    <!DOCTYPE html>
    <html lang="de">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Hash City - Hash Maps spielend lernen</title>

        <!-- External Libraries -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

        <style>
            :root {
                --primary-color: #3498db;
                --secondary-color: #2ecc71;
                --warning-color: #f39c12;
                --danger-color: #e74c3c;
                --bg-color: #87CEEB;
                --grass-color: #27ae60;
                --text-color: #2c3e50;
                --shadow: 0 4px 15px rgba(0,0,0,0.1);
            }

            body {
                background: linear-gradient(180deg, var(--bg-color) 0%, #98D8E8 50%, var(--grass-color) 100%);
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                color: var(--text-color);
                min-height: 100vh;
                margin: 0;
            }

            .hero-section {
                background:
                        radial-gradient(ellipse 300px 150px at 30% 20%, rgba(255,255,255,0.8) 0%, transparent 50%),
                        radial-gradient(ellipse 200px 100px at 70% 30%, rgba(255,255,255,0.6) 0%, transparent 50%),
                        linear-gradient(180deg, var(--bg-color) 0%, #98D8E8 100%);
                min-height: 100vh;
                display: flex;
                align-items: center;
                position: relative;
                overflow: hidden;
            }

            .hero-content {
                position: relative;
                z-index: 10;
            }

            .city-skyline {
                position: absolute;
                bottom: 0;
                left: 0;
                right: 0;
                height: 200px;
                background: linear-gradient(180deg, transparent 0%, var(--grass-color) 100%);
            }

            .building {
                position: absolute;
                bottom: 0;
                background: linear-gradient(145deg, #ecf0f1, #bdc3c7);
                border-radius: 10px 10px 0 0;
                box-shadow: var(--shadow);
            }

            .building-1 { width: 80px; height: 120px; left: 10%; }
            .building-2 { width: 100px; height: 150px; left: 20%; background: linear-gradient(145deg, var(--primary-color), #2980b9); }
            .building-3 { width: 60px; height: 100px; left: 35%; }
            .building-4 { width: 120px; height: 180px; left: 50%; background: linear-gradient(145deg, var(--secondary-color), #27ae60); }
            .building-5 { width: 90px; height: 130px; left: 70%; }
            .building-6 { width: 70px; height: 110px; left: 85%; background: linear-gradient(145deg, var(--warning-color), #e67e22); }

            .logo {
                font-size: 4rem;
                font-weight: bold;
                background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
                text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
                margin-bottom: 1rem;
            }

            .subtitle {
                font-size: 1.5rem;
                color: var(--text-color);
                margin-bottom: 2rem;
                opacity: 0.8;
            }

            .cta-button {
                background: linear-gradient(135deg, var(--secondary-color), #27ae60);
                color: white;
                border: none;
                padding: 1rem 2rem;
                font-size: 1.2rem;
                border-radius: 50px;
                box-shadow: var(--shadow);
                transition: all 0.3s ease;
                text-decoration: none;
                display: inline-block;
                margin: 0.5rem;
            }

            .cta-button:hover {
                transform: translateY(-3px);
                box-shadow: 0 8px 25px rgba(0,0,0,0.2);
                color: white;
            }

            .cta-button.secondary {
                background: linear-gradient(135deg, var(--primary-color), #2980b9);
            }

            .cta-button.outline {
                background: transparent;
                border: 2px solid var(--primary-color);
                color: var(--primary-color);
            }

            .cta-button.outline:hover {
                background: var(--primary-color);
                color: white;
            }

            .features-section {
                background: white;
                padding: 4rem 0;
                margin-top: -100px;
                position: relative;
                z-index: 5;
            }

            .feature-card {
                background: white;
                border-radius: 15px;
                padding: 2rem;
                box-shadow: var(--shadow);
                text-align: center;
                transition: all 0.3s ease;
                height: 100%;
            }

            .feature-card:hover {
                transform: translateY(-10px);
                box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            }

            .feature-icon {
                font-size: 3rem;
                color: var(--primary-color);
                margin-bottom: 1rem;
            }

            .stats-section {
                background: linear-gradient(135deg, var(--text-color), #34495e);
                color: white;
                padding: 3rem 0;
            }

            .stat-item {
                text-align: center;
                padding: 1rem;
            }

            .stat-number {
                font-size: 3rem;
                font-weight: bold;
                color: var(--secondary-color);
            }

            .floating-elements {
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                pointer-events: none;
            }

            .floating-hash {
                position: absolute;
                color: rgba(52, 152, 219, 0.1);
                font-size: 2rem;
                font-weight: bold;
                animation: float 6s ease-in-out infinite;
            }

            @keyframes float {
                0%, 100% { transform: translateY(0px) rotate(0deg); }
                50% { transform: translateY(-20px) rotate(5deg); }
            }

            .floating-hash:nth-child(1) { top: 20%; left: 10%; animation-delay: 0s; }
            .floating-hash:nth-child(2) { top: 40%; right: 15%; animation-delay: 2s; }
            .floating-hash:nth-child(3) { top: 60%; left: 20%; animation-delay: 4s; }
            .floating-hash:nth-child(4) { top: 30%; right: 30%; animation-delay: 1s; }

            /* Responsive Design */
            @media (max-width: 768px) {
                .logo {
                    font-size: 2.5rem;
                }

                .subtitle {
                    font-size: 1.2rem;
                }

                .cta-button {
                    padding: 0.8rem 1.5rem;
                    font-size: 1rem;
                }

                .building {
                    transform: scale(0.7);
                }
            }
        </style>
    </head>
    <body>
    <!-- Hero Section -->
    <div class="hero-section">
        <!-- Floating Hash Elements -->
        <div class="floating-elements">
            <div class="floating-hash">#</div>
            <div class="floating-hash">{ }</div>
            <div class="floating-hash">→</div>
            <div class="floating-hash">∑</div>
        </div>

        <!-- City Skyline -->
        <div class="city-skyline">
            <div class="building building-1"></div>
            <div class="building building-2"></div>
            <div class="building building-3"></div>
            <div class="building building-4"></div>
            <div class="building building-5"></div>
            <div class="building building-6"></div>
        </div>

        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 hero-content">
                    <div class="logo">
                        <i class="fas fa-city"></i> Hash City
                    </div>
                    <p class="subtitle">
                        Lerne Hash Maps durch interaktives Stadtbauen
                    </p>
                    <p class="lead mb-4">
                        Entdecke die Welt der Hash-Funktionen, Kollisionen und Optimierungsstrategien
                        in einer spielerischen 3D-Stadt-Simulation.
                    </p>

                    <div class="cta-buttons">
                        <a href="?page=levels" class="cta-button">
                            <i class="fas fa-play"></i> Spiel starten
                        </a>
                        <a href="?page=tutorial" class="cta-button secondary">
                            <i class="fas fa-graduation-cap"></i> Tutorial
                        </a>
                        <a href="?page=about" class="cta-button outline">
                            <i class="fas fa-info-circle"></i> Über Hash City
                        </a>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="text-center">
                        <div style="font-size: 8rem; color: rgba(52, 152, 219, 0.3);">
                            <i class="fas fa-city"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="features-section">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-12">
                    <h2>Warum Hash City?</h2>
                    <p class="lead">Lerne komplexe Datenstrukturen auf eine völlig neue Art</p>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-gamepad"></i>
                        </div>
                        <h4>Spielerisches Lernen</h4>
                        <p>Verstehe Hash Maps durch interaktive Drag & Drop Mechaniken und visuelle Animationen.</p>
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h4>Progressives System</h4>
                        <p>6 aufeinander aufbauende Level von Grundlagen bis zu fortgeschrittenen Optimierungsstrategien.</p>
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-calculator"></i>
                        </div>
                        <h4>Live-Berechnung</h4>
                        <p>Sieh Hash-Funktionen in Echtzeit arbeiten mit schrittweiser ASCII-Wert Berechnung.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Section -->
    <div class="stats-section">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <div class="stat-item">
                        <div class="stat-number">6</div>
                        <div>Interaktive Level</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-item">
                        <div class="stat-number">3</div>
                        <div>Kollisionsstrategien</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-item">
                        <div class="stat-number">∞</div>
                        <div>Lernmöglichkeiten</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-item">
                        <div class="stat-number">100%</div>
                        <div>Kostenlos</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5><i class="fas fa-city"></i> Hash City</h5>
                    <p>Ein interaktives Lernspiel für Hash Maps und Datenstrukturen.</p>
                </div>
                <div class="col-md-6 text-end">
                    <p>&copy; 2024 Hash City. Entwickelt für Bildungszwecke.</p>
                    <div>
                        <a href="?page=about" class="text-white me-3">Über uns</a>
                        <a href="?page=tutorial" class="text-white">Tutorial</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Progress aus Session und localStorage synchronisieren
        document.addEventListener('DOMContentLoaded', function() {
            // Session-Progress mit localStorage synchronisieren
            fetch('?page=api&action=get_progress')
                .then(response => response.json())
                .then(progress => {
                    const localProgress = JSON.parse(localStorage.getItem('hashcity_progress') || '{}');

                    // Merge und verwende den höchsten Fortschritt
                    const mergedProgress = {
                        maxUnlockedLevel: Math.max(
                            progress.maxUnlockedLevel || 1,
                            localProgress.maxUnlockedLevel || 1
                        ),
                        currentLevel: progress.currentLevel || localProgress.currentLevel || 1,
                        completedLevels: [...(progress.completedLevels || []), ...(localProgress.completedLevels || [])],
                        totalXP: Math.max(progress.totalXP || 0, localProgress.totalXP || 0),
                        achievements: [...(progress.achievements || []), ...(localProgress.achievements || [])]
                    };

                    localStorage.setItem('hashcity_progress', JSON.stringify(mergedProgress));

                    // Speichere zurück in Session
                    fetch('?page=api&action=save_progress', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify(mergedProgress)
                    });
                })
                .catch(error => {
                    console.log('Progress sync failed:', error);
                });

            // Smooth scrolling für interne Links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth'
                        });
                    }
                });
            });
        });
    </script>
    </body>
    </html>
    <?php
}

// Hauptrouting ausführen
routeRequest($page, $level, $action);
?>