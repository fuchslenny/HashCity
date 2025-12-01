<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HashCity - Zertifikat (Landscape)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Rajdhani:wght@300;500;700&family=Great+Vibes&display=swap" rel="stylesheet">
    <style>
        /* --- Global Styles --- */
        body {
            font-family: 'Rajdhani', sans-serif;
            background: #4CAF50;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow-x: hidden;
            margin: 0;
            padding: 0;
        }

        .bg-overlay {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: linear-gradient(180deg, #87CEEB 0%, #4CAF50 100%);
            z-index: -1;
            opacity: 0.8;
        }

        /* --- Intro Section --- */
        .intro-container {
            text-align: center;
            max-width: 600px;
            padding: 20px;
            z-index: 10;
            animation: fadeIn 0.5s ease;
            position: fixed; /* Damit es immer mittig bleibt */
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .mike-avatar {
            width: 150px; height: 150px;
            background: rgba(255,255,255,0.9);
            border-radius: 50%;
            border: 5px solid #DAA520;
            margin: 0 auto 20px auto;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        .mike-avatar img { width: 100%; height: 100%; object-fit: contain; }

        .dialogue-bubble {
            background: white; padding: 2rem;
            border-radius: 20px; border: 4px solid #2E7D32;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            position: relative;
        }
        .dialogue-bubble::after {
            content: ''; position: absolute; top: -20px; left: 50%; margin-left: -10px;
            border-width: 10px; border-style: solid;
            border-color: transparent transparent #2E7D32 transparent;
        }

        .btn-start {
            background: linear-gradient(135deg, #FF9800 0%, #F57C00 100%);
            color: white; border: none; padding: 12px 30px;
            font-family: 'Orbitron', sans-serif; font-weight: bold; font-size: 1.1rem;
            border-radius: 30px; cursor: pointer; transition: transform 0.2s;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            margin-top: 15px;
        }
        .btn-start:hover { transform: scale(1.05); }

        /* --- Zertifikat Wrapper --- */
        .cert-wrapper {
            display: none; /* Anfangs versteckt */
            width: 100%;
            height: 100vh;
            justify-content: center;
            align-items: center;
            overflow: auto; /* Scrollbar falls nötig */
        }

        /* --- Das physische Zertifikat (Screen Mode) - LANDSCAPE --- */
        .cert-container {
            background: #fff;
            /* A4 Landscape Maße */
            width: 297mm;
            height: 210mm;

            /* Skalierung für Bildschirm-Ansicht */
            transform: scale(0.65);
            /* Auf größeren Screens etwas größer, siehe Media Queries unten */

            box-shadow: 0 20px 60px rgba(0,0,0,0.4);
            text-align: center;
            border: 10mm solid #DAA520;
            background-image: radial-gradient(#fdfbf7 20%, #fff 20%);
            background-size: 5mm 5mm;
            position: relative;
            box-sizing: border-box;
            flex-shrink: 0; /* Verhindert Schrumpfen in Flexbox */
        }

        .cert-inner-border {
            border: 2px solid #DAA520;
            height: 100%;
            padding: 10mm;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        /* Typografie & Layout angepasst für Landscape (weniger Höhe) */
        .cert-header {
            font-family: 'Orbitron', sans-serif;
            font-size: 3.8rem; /* Etwas kleiner als Portrait */
            font-weight: 900;
            color: #2E7D32;
            text-transform: uppercase;
            letter-spacing: 8px;
            margin-bottom: 2mm;
            line-height: 1;
            margin-top: 5mm;
        }

        .cert-subheader {
            font-family: 'Rajdhani', sans-serif;
            font-size: 1.8rem;
            color: #555;
            text-transform: uppercase;
            letter-spacing: 3px;
            margin-bottom: 5mm;
        }

        .cert-body-text {
            font-size: 1.3rem;
            color: #333;
            margin: 4mm 0;
            line-height: 1.3;
        }

        /* Name Input - Breiter für Landscape */
        .name-input {
            display: block;
            width: 90%;
            margin: 2mm auto 5mm auto;
            border: none;
            border-bottom: 3px solid #333;
            font-family: 'Great Vibes', cursive;
            font-size: 4rem;
            text-align: center;
            color: #1a237e;
            background: transparent;
            outline: none;
            padding: 2mm 0;
        }
        .name-input::placeholder {
            color: #ddd;
            font-family: 'Rajdhani', sans-serif;
            font-size: 2rem;
        }

        /* Skills - Breiter verteilt */
        .skills-list {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 6mm;
            margin: 8mm 0;
            width: 100%;
        }

        .skill-badge {
            background: #e8f5e9;
            border: 2px solid #4CAF50;
            color: #2E7D32;
            padding: 3mm 8mm;
            border-radius: 8mm;
            font-weight: bold;
            font-family: 'Orbitron', sans-serif;
            font-size: 1rem;
        }

        /* Footer - Breiter verteilt für Landscape */
        .cert-footer {
            display: flex;
            justify-content: space-between; /* Unterschriften weiter auseinander */
            align-items: flex-end;
            padding: 0 15mm 5mm 15mm; /* Mehr seitlicher Abstand */
            position: relative;
        }

        .signature-group {
            text-align: center;
            width: 80mm;
            position: relative;
            z-index: 20;
        }

        .signature-line {
            border-bottom: 2px solid #333;
            margin-bottom: 2mm;
            height: 20mm;
            display: flex;
            align-items: flex-end;
            justify-content: center;
        }

        .date-display {
            font-family: 'Courier New', monospace;
            font-size: 1.2rem;
            color: #333;
            padding-bottom: 2mm;
        }

        /* Siegel - Zentriert unten */
        .seal {
            position: absolute;
            left: 50%;
            bottom: 5mm;
            transform: translateX(-50%);
            width: 40mm;
            height: 40mm;
            border-radius: 50%;
            background: #DAA520;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            border: 3mm solid #fff;
            z-index: 10;
        }
        .seal img { width: 80%; height: 80%; object-fit: contain; }

        /* Buttons */
        .action-bar {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(255,255,255,0.95);
            padding: 15px 30px;
            border-radius: 50px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.3);
            display: flex;
            gap: 20px;
            z-index: 100;
            opacity: 0;
            transition: opacity 1s;
        }

        .btn-custom {
            padding: 10px 25px;
            border-radius: 30px;
            font-family: 'Orbitron', sans-serif;
            font-weight: bold;
            text-decoration: none;
            cursor: pointer;
            border: none;
            color: white;
            font-size: 0.9rem;
            white-space: nowrap;
        }
        .btn-print { background: #DAA520; }
        .btn-home { background: #2196F3; }

        #confetti-canvas {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            pointer-events: none; z-index: 99;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translate(-50%, -30%); }
            to { opacity: 1; transform: translate(-50%, -50%); }
        }

        /* --- DRUCK MODUS: A4 LANDSCAPE (297mm x 210mm) --- */
        @page {
            size: A4 landscape;
            margin: 0;
        }

        @media print {
            /* Alles ausblenden was nicht Zertifikat ist */
            .bg-overlay, .action-bar, #confetti-canvas, .intro-container {
                display: none !important;
            }

            body, html {
                width: 297mm;
                height: 210mm;
                margin: 0 !important;
                padding: 0 !important;
                background: white !important;
                overflow: hidden;
            }

            .cert-wrapper {
                display: block !important;
                width: 100%;
                height: 100%;
                overflow: hidden;
            }

            .cert-container {
                /* Reset Transformations for Print */
                transform: none !important;
                width: 297mm;
                height: 210mm;
                position: absolute;
                top: 0; left: 0;
                margin: 0 !important;
                box-shadow: none;
                /* WICHTIG: Farben erzwingen */
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                background-image: radial-gradient(#fdfbf7 20%, #fff 20%) !important;
            }

            .name-input {
                border-bottom: 3px solid #333 !important;
            }
            .name-input::placeholder { color: transparent; }

            /* Skill Badges Druckfarben */
            .skill-badge {
                background: #e8f5e9 !important;
                border: 2px solid #4CAF50 !important;
                color: #2E7D32 !important;
                -webkit-print-color-adjust: exact;
            }
        }

        /* Responsive Screen Adjustments */
        @media screen and (max-width: 900px) {
            .cert-container { transform: scale(0.45); }
        }
        @media screen and (max-width: 600px) {
            .cert-container { transform: scale(0.28); }
            .name-input { font-size: 3rem; }
        }
    </style>
</head>
<body>

<div class="bg-overlay"></div>
<canvas id="confetti-canvas"></canvas>

<div class="intro-container" id="introSection">
    <div class="mike-avatar">
        <img src="./assets/wink_major.png" alt="Major Mike" onerror="this.src='https://via.placeholder.com/150/DAA520/FFFFFF?text=Major+Mike'">
    </div>
    <div class="dialogue-bubble">
        <h2 style="font-family:'Orbitron'; color:#2E7D32; margin-bottom:10px;">Gratulation!</h2>
        <p class="dialogue-text" style="font-size: 1rem;">
            "Du hast HashCity gerettet! Durch deine exzellenten Fähigkeiten in der Datenverwaltung und im Load Balancing läuft die Stadt wieder rund.<br><br>
            Hier ist deine offizielle Ernennungsurkunde."
        </p>
        <button class="btn-start" onclick="showCertificate()">Urkunde ansehen 📜</button>
    </div>
</div>

<div class="cert-wrapper" id="certSection">
    <div class="cert-container">
        <div class="cert-inner-border">

            <div>
                <div class="cert-header">URKUNDE</div>
                <div class="cert-subheader">HashCity Master Architect</div>

                <p class="cert-body-text">Hiermit wird bestätigt, dass</p>

                <input type="text" class="name-input" placeholder="Dein Name" id="userName" autocomplete="off" value="Max Mustermann">

                <p class="cert-body-text">
                    das Ausbildungsprogramm der Stadtverwaltung HashCity<br>
                    erfolgreich absolviert hat.
                </p>

                <p class="cert-body-text" style="font-size: 1.1rem; margin-top: 5mm; color: #666;">
                    Der Absolvent hat Expertenwissen in folgenden Bereichen bewiesen:
                </p>

                <div class="skills-list">
                    <span class="skill-badge">Modulo-Arithmetik</span>
                    <span class="skill-badge">Linear Probing</span>
                    <span class="skill-badge">Load Factor Control</span>
                    <span class="skill-badge">Rehashing</span>
                </div>
            </div>

            <div class="cert-footer">

                <div class="signature-group">
                    <div class="signature-line">
                        <div class="date-display" id="dateDisplay"></div>
                    </div>
                    <div style="font-size: 0.9rem; font-weight: bold; font-family:'Orbitron'; margin-top: 2mm;">DATUM</div>
                </div>

                <!--<div class="seal">
                    <img src="./assets/wink_major.png" alt="Seal" onerror="this.style.display='none'">
                </div>-->

                <div class="signature-group">
                    <div class="signature-line">
                        <span style="font-family: 'Great Vibes', cursive; font-size: 2.5rem; color: #2E7D32; transform:rotate(-5deg); display:block; padding-bottom:5px;">Major Mike</span>
                    </div>
                    <div style="font-size: 0.9rem; font-weight: bold; font-family:'Orbitron'; margin-top: 2mm;">BÜRGERMEISTER</div>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="action-bar" id="actionBar">
    <button class="btn-custom btn-print" onclick="window.print()">🖨️ Drucken (A4 Quer)</button>
    <a href="index.php" class="btn-custom btn-home">🏠 Menü</a>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    // 1. Datum
    const now = new Date();
    document.getElementById('dateDisplay').textContent = now.toLocaleDateString('de-DE', { year: 'numeric', month: 'long', day: 'numeric' });

    // 2. Anzeige Logik
    function showCertificate() {
        $('#introSection').fadeOut(300, function() {
            // Flex setzen und einfaden
            const wrapper = $('#certSection');
            wrapper.css('display', 'flex').hide().fadeIn(800);

            // Action Bar
            $('#actionBar').css('opacity', 1);

            // Konfetti
            startConfetti();
        });
    }

    // 3. Konfetti (Optimiert)
    const canvas = document.getElementById('confetti-canvas');
    const ctx = canvas.getContext('2d');
    let animationId = null;
    let particles = [];
    const colors = ['#f44336', '#2196f3', '#4CAF50', '#FFEB3B', '#FF9800'];

    function resizeCanvas() {
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
    }
    window.addEventListener('resize', resizeCanvas);
    resizeCanvas();

    function createParticles() {
        particles = [];
        for (let i = 0; i < 80; i++) {
            particles.push({
                x: Math.random() * canvas.width,
                y: Math.random() * canvas.height - canvas.height,
                w: 8 + Math.random() * 8,
                h: 8 + Math.random() * 8,
                color: colors[Math.floor(Math.random() * colors.length)],
                speedY: 3 + Math.random() * 3,
                speedX: -2 + Math.random() * 4,
                rotation: Math.random() * 360
            });
        }
    }

    function draw() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        particles.forEach(p => {
            p.y += p.speedY;
            p.x += p.speedX;
            p.rotation += 2;

            if (p.y > canvas.height) p.y = -20;
            if (p.x > canvas.width) p.x = 0;
            if (p.x < 0) p.x = canvas.width;

            ctx.save();
            ctx.translate(p.x, p.y);
            ctx.rotate(p.rotation * Math.PI / 180);
            ctx.fillStyle = p.color;
            ctx.fillRect(-p.w/2, -p.h/2, p.w, p.h);
            ctx.restore();
        });
        animationId = requestAnimationFrame(draw);
    }

    function startConfetti() {
        createParticles();
        draw();
        setTimeout(() => {
            $(canvas).fadeOut(1000, () => {
                cancelAnimationFrame(animationId);
                ctx.clearRect(0, 0, canvas.width, canvas.height);
            });
        }, 4000);
    }
</script>
</body>
</html>