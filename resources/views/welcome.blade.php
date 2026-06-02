<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DentalTrack — QR-basierte Produktionsverfolgung</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Figtree', sans-serif; background: #f8fafc; color: #1e293b; }

        /* Navbar */
        .navbar { background: #fff; border-bottom: 1px solid #e2e8f0; padding: 1rem 2rem; display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 50; }
        .navbar-brand { display: flex; align-items: center; gap: 0.5rem; font-size: 1.25rem; font-weight: 700; color: #1e40af; text-decoration: none; }
        .navbar-brand svg { width: 32px; height: 32px; }
        .navbar-links { display: flex; gap: 1rem; align-items: center; }
        .navbar-links a { text-decoration: none; padding: 0.5rem 1rem; border-radius: 8px; font-weight: 500; font-size: 0.875rem; transition: all 0.2s; }
        .btn-outline { color: #1e40af; border: 1px solid #1e40af; }
        .btn-outline:hover { background: #1e40af; color: #fff; }
        .btn-primary { background: #1e40af; color: #fff; }
        .btn-primary:hover { background: #1e3a8a; }
        .btn-track { background: #059669; color: #fff; }
        .btn-track:hover { background: #047857; }
        .lang-switcher { display: flex; gap: 0.25rem; align-items: center; margin-left: 0.5rem; padding-left: 0.75rem; border-left: 1px solid #e2e8f0; }
        .lang-switcher a { text-decoration: none; padding: 0.25rem 0.5rem; border-radius: 6px; font-size: 0.75rem; font-weight: 600; color: #64748b; transition: all 0.2s; }
        .lang-switcher a:hover { background: #f1f5f9; color: #1e40af; }
        .lang-switcher a.active { background: #dbeafe; color: #1e40af; }

        /* Hero */
        .hero { padding: 5rem 2rem 4rem; text-align: center; background: linear-gradient(135deg, #eff6ff 0%, #f0fdf4 100%); }
        .hero h1 { font-size: 3rem; font-weight: 800; color: #0f172a; line-height: 1.2; margin-bottom: 1rem; }
        .hero h1 span { color: #1e40af; }
        .hero p { font-size: 1.25rem; color: #475569; max-width: 600px; margin: 0 auto 2rem; line-height: 1.6; }
        .hero-buttons { display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; }
        .hero-buttons a { text-decoration: none; padding: 0.875rem 2rem; border-radius: 10px; font-weight: 600; font-size: 1rem; transition: all 0.2s; }

        /* Features */
        .features { padding: 4rem 2rem; max-width: 1200px; margin: 0 auto; }
        .features h2 { text-align: center; font-size: 2rem; font-weight: 700; color: #0f172a; margin-bottom: 0.5rem; }
        .features .subtitle { text-align: center; color: #64748b; margin-bottom: 3rem; font-size: 1.1rem; }
        .features-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem; }
        .feature-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1.5rem; transition: all 0.2s; }
        .feature-card:hover { border-color: #93c5fd; box-shadow: 0 4px 12px rgba(30, 64, 175, 0.08); }
        .feature-icon { width: 48px; height: 48px; border-radius: 10px; display: flex; align-items: center; justify-content: center; margin-bottom: 1rem; font-size: 1.5rem; }
        .feature-card h3 { font-size: 1.1rem; font-weight: 600; color: #0f172a; margin-bottom: 0.5rem; }
        .feature-card p { color: #64748b; font-size: 0.9rem; line-height: 1.5; }
        .icon-blue { background: #dbeafe; }
        .icon-green { background: #dcfce7; }
        .icon-purple { background: #ede9fe; }
        .icon-orange { background: #ffedd5; }
        .icon-red { background: #fee2e2; }
        .icon-teal { background: #ccfbf1; }

        /* How It Works */
        .how-it-works { padding: 4rem 2rem; background: #fff; }
        .how-it-works h2 { text-align: center; font-size: 2rem; font-weight: 700; color: #0f172a; margin-bottom: 3rem; }
        .steps { max-width: 800px; margin: 0 auto; display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 2rem; }
        .step { text-align: center; }
        .step-number { width: 56px; height: 56px; border-radius: 50%; background: #1e40af; color: #fff; font-size: 1.5rem; font-weight: 700; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; }
        .step h3 { font-size: 1rem; font-weight: 600; color: #0f172a; margin-bottom: 0.5rem; }
        .step p { color: #64748b; font-size: 0.85rem; line-height: 1.5; }

        /* Stats */
        .stats { padding: 3rem 2rem; background: linear-gradient(135deg, #1e40af 0%, #7c3aed 100%); }
        .stats-grid { max-width: 900px; margin: 0 auto; display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 2rem; text-align: center; }
        .stat h3 { font-size: 2.5rem; font-weight: 800; color: #fff; }
        .stat p { color: #c7d2fe; font-size: 0.9rem; margin-top: 0.25rem; }

        /* Footer */
        .footer { padding: 2rem; text-align: center; border-top: 1px solid #e2e8f0; color: #94a3b8; font-size: 0.875rem; }

        @media (max-width: 640px) {
            .hero h1 { font-size: 2rem; }
            .hero p { font-size: 1rem; }
            .navbar-links { gap: 0.5rem; }
            .navbar-links a { padding: 0.4rem 0.75rem; font-size: 0.8rem; }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <a href="/" class="navbar-brand">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                <polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
            DentalTrack
        </a>
        <div class="navbar-links">
            <a href="{{ url('/track') }}" class="btn-track">Auftrag verfolgen</a>
            <a href="{{ url('/scan') }}" class="btn-outline">QR scannen</a>
            <a href="{{ url('/admin') }}" class="btn-primary">Admin-Bereich</a>
            <div class="lang-switcher">
                <a href="?lang=de" class="{{ app()->getLocale() === 'de' ? 'active' : '' }}">DE</a>
                <a href="?lang=en" class="{{ app()->getLocale() === 'en' ? 'active' : '' }}">EN</a>
                <a href="?lang=ur" class="{{ app()->getLocale() === 'ur' ? 'active' : '' }}">اردو</a>
            </div>
        </div>
    </nav>

    <!-- Hero -->
    <section class="hero">
        <h1>Intelligente Produktionsverfolgung<br>fuer <span>Dentallabore</span></h1>
        <p>QR-Code-basiertes Echtzeit-Tracking-System fuer Dentallabore. Scannen, verfolgen und analysieren Sie jeden Schritt des Produktionsprozesses — vom Abdruck bis zur Lieferung.</p>
        <div class="hero-buttons">
            <a href="{{ url('/admin') }}" class="btn-primary" style="background:#1e40af;color:#fff;">Dashboard oeffnen</a>
            <a href="{{ url('/track') }}" class="btn-track" style="background:#059669;color:#fff;">Auftrag verfolgen</a>
            <a href="{{ url('/scan') }}" class="btn-outline" style="color:#1e40af;border:2px solid #1e40af;">Scannen starten</a>
        </div>
    </section>

    <!-- Features -->
    <section class="features">
        <h2>Alles was Sie brauchen</h2>
        <p class="subtitle">Komplettes Produktionsmanagement fuer moderne Dentallabore</p>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon icon-blue">&#x1F4F1;</div>
                <h3>QR-Code-Scannen</h3>
                <p>Scannen Sie Auftrags- und Arbeitsstations-QR-Codes mit jedem Smartphone-Browser. Keine App-Installation noetig — funktioniert als PWA.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon icon-green">&#x1F4CA;</div>
                <h3>Live-Dashboard</h3>
                <p>Echtzeit-Auftragstafel mit WebSocket-Updates. Sehen Sie laufende, ausstehende und ueberfaellige Auftraege auf einen Blick.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon icon-purple">&#x1F916;</div>
                <h3>KI-Prognosen</h3>
                <p>Gewichtetes historisches Durchschnittsmodell sagt Fertigstellungszeiten voraus. Intelligente Vorschlaege zur Engpasserkennung und optimalen Weiterleitung.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon icon-orange">&#x1F4C8;</div>
                <h3>Analysen & Berichte</h3>
                <p>Mitarbeiterleistung, Produktionsanalysen, Firmenvergleich und exportierbare Berichte im Excel/CSV-Format.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon icon-red">&#x1F6E1;&#xFE0F;</div>
                <h3>Qualitaetskontrolle</h3>
                <p>Markieren Sie fehlgeschlagene QK-Schritte, verfolgen Sie Nacharbeitsursachen und ueberwachen Sie Technikerqualitaet mit dem QK-Dashboard.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon icon-teal">&#x1F310;</div>
                <h3>Kundenportal</h3>
                <p>Aerzte und Kliniken koennen ihre Auftraege online mit einem einfachen Tracking-Code verfolgen. Mehrsprachige Unterstuetzung.</p>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section class="how-it-works">
        <h2>So funktioniert es</h2>
        <div class="steps">
            <div class="step">
                <div class="step-number">1</div>
                <h3>Auftrag erstellen</h3>
                <p>Der Laborleiter erstellt einen Auftrag mit Patientendaten, Produkttyp und Faelligkeitsdatum. Der QR-Code wird automatisch generiert.</p>
            </div>
            <div class="step">
                <div class="step-number">2</div>
                <h3>QR-Sticker drucken</h3>
                <p>Drucken Sie kleine QR-Sticker fuer Auftraege und grosse fuer Arbeitsstationen. Kompatibel mit Thermodruckern.</p>
            </div>
            <div class="step">
                <div class="step-number">3</div>
                <h3>Scannen & Verfolgen</h3>
                <p>Techniker scannen den Arbeitsstations-QR, dann den Auftrags-QR. Arbeit starten, pausieren oder abschliessen mit einem Tipp.</p>
            </div>
            <div class="step">
                <div class="step-number">4</div>
                <h3>Ueberwachen & Liefern</h3>
                <p>Das Management verfolgt den Fortschritt in Echtzeit. KI sagt die Fertigstellung voraus. Aerzte erhalten Updates ueber das Portal.</p>
            </div>
        </div>
    </section>

    <!-- Stats -->
    <section class="stats">
        <div class="stats-grid">
            <div class="stat">
                <h3>4</h3>
                <p>Benutzerrollen</p>
            </div>
            <div class="stat">
                <h3>7</h3>
                <p>Dashboard-Seiten</p>
            </div>
            <div class="stat">
                <h3>19</h3>
                <p>Automatisierte Tests</p>
            </div>
            <div class="stat">
                <h3>2</h3>
                <p>Sprachen</p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <p>&copy; {{ date('Y') }} DentalTrack — QR-basiertes Produktionsverfolgungssystem fuer Dentallabore</p>
    </footer>
</body>
</html>
