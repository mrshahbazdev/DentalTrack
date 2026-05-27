<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ur' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('landing.title') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Figtree', sans-serif; background: #f8fafc; color: #1e293b; }

        /* Navbar */
        .navbar { background: #fff; border-bottom: 1px solid #e2e8f0; padding: 1rem 2rem; display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 50; }
        .navbar-brand { display: flex; align-items: center; gap: 0.5rem; font-size: 1.25rem; font-weight: 700; color: #1e40af; text-decoration: none; }
        .navbar-brand svg { width: 32px; height: 32px; }
        .navbar-links { display: flex; gap: 0.75rem; align-items: center; }
        .navbar-links a { text-decoration: none; padding: 0.5rem 1rem; border-radius: 8px; font-weight: 500; font-size: 0.875rem; transition: all 0.2s; }
        .btn-outline { color: #1e40af; border: 1px solid #1e40af; }
        .btn-outline:hover { background: #1e40af; color: #fff; }
        .btn-primary { background: #1e40af; color: #fff; }
        .btn-primary:hover { background: #1e3a8a; }
        .btn-track { background: #059669; color: #fff; }
        .btn-track:hover { background: #047857; }
        .lang-switch { display: flex; gap: 0.25rem; align-items: center; margin-left: 0.5rem; padding-left: 0.75rem; border-left: 1px solid #e2e8f0; }
        .lang-switch a { padding: 0.3rem 0.6rem; font-size: 0.8rem; border-radius: 6px; color: #64748b; border: 1px solid transparent; }
        .lang-switch a:hover { color: #1e40af; border-color: #93c5fd; }
        .lang-switch a.active { background: #1e40af; color: #fff; font-weight: 600; }

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

        /* RTL adjustments */
        [dir="rtl"] .lang-switch { margin-left: 0; margin-right: 0.5rem; padding-left: 0; padding-right: 0.75rem; border-left: none; border-right: 1px solid #e2e8f0; }

        @media (max-width: 640px) {
            .hero h1 { font-size: 2rem; }
            .hero p { font-size: 1rem; }
            .navbar { flex-wrap: wrap; gap: 0.5rem; }
            .navbar-links { gap: 0.5rem; flex-wrap: wrap; }
            .navbar-links a { padding: 0.4rem 0.75rem; font-size: 0.8rem; }
            .lang-switch a { padding: 0.25rem 0.5rem; font-size: 0.75rem; }
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
            <a href="{{ url('/track') }}" class="btn-track">{{ __('landing.track_order') }}</a>
            <a href="{{ url('/scan') }}" class="btn-outline">{{ __('landing.scan_qr') }}</a>
            <a href="{{ url('/admin') }}" class="btn-primary">{{ __('landing.admin_panel') }}</a>
            <div class="lang-switch">
                <a href="?lang=en" class="{{ app()->getLocale() === 'en' ? 'active' : '' }}">EN</a>
                <a href="?lang=ur" class="{{ app()->getLocale() === 'ur' ? 'active' : '' }}">اردو</a>
            </div>
        </div>
    </nav>

    <!-- Hero -->
    <section class="hero">
        <h1>{{ __('landing.hero_title_1') }}<br>{{ __('landing.hero_title_2') }} <span>{{ __('landing.hero_title_3') }}</span></h1>
        <p>{{ __('landing.hero_desc') }}</p>
        <div class="hero-buttons">
            <a href="{{ url('/admin') }}" class="btn-primary" style="background:#1e40af;color:#fff;">{{ __('landing.open_dashboard') }}</a>
            <a href="{{ url('/track') }}" class="btn-track" style="background:#059669;color:#fff;">{{ __('landing.track_your_order') }}</a>
            <a href="{{ url('/scan') }}" class="btn-outline" style="color:#1e40af;border:2px solid #1e40af;">{{ __('landing.start_scanning') }}</a>
        </div>
    </section>

    <!-- Features -->
    <section class="features">
        <h2>{{ __('landing.features_title') }}</h2>
        <p class="subtitle">{{ __('landing.features_subtitle') }}</p>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon icon-blue">&#x1F4F1;</div>
                <h3>{{ __('landing.feat_qr_title') }}</h3>
                <p>{{ __('landing.feat_qr_desc') }}</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon icon-green">&#x1F4CA;</div>
                <h3>{{ __('landing.feat_dashboard_title') }}</h3>
                <p>{{ __('landing.feat_dashboard_desc') }}</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon icon-purple">&#x1F916;</div>
                <h3>{{ __('landing.feat_ai_title') }}</h3>
                <p>{{ __('landing.feat_ai_desc') }}</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon icon-orange">&#x1F4C8;</div>
                <h3>{{ __('landing.feat_analytics_title') }}</h3>
                <p>{{ __('landing.feat_analytics_desc') }}</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon icon-red">&#x1F6E1;&#xFE0F;</div>
                <h3>{{ __('landing.feat_qc_title') }}</h3>
                <p>{{ __('landing.feat_qc_desc') }}</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon icon-teal">&#x1F310;</div>
                <h3>{{ __('landing.feat_portal_title') }}</h3>
                <p>{{ __('landing.feat_portal_desc') }}</p>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section class="how-it-works">
        <h2>{{ __('landing.how_title') }}</h2>
        <div class="steps">
            <div class="step">
                <div class="step-number">1</div>
                <h3>{{ __('landing.step1_title') }}</h3>
                <p>{{ __('landing.step1_desc') }}</p>
            </div>
            <div class="step">
                <div class="step-number">2</div>
                <h3>{{ __('landing.step2_title') }}</h3>
                <p>{{ __('landing.step2_desc') }}</p>
            </div>
            <div class="step">
                <div class="step-number">3</div>
                <h3>{{ __('landing.step3_title') }}</h3>
                <p>{{ __('landing.step3_desc') }}</p>
            </div>
            <div class="step">
                <div class="step-number">4</div>
                <h3>{{ __('landing.step4_title') }}</h3>
                <p>{{ __('landing.step4_desc') }}</p>
            </div>
        </div>
    </section>

    <!-- Stats -->
    <section class="stats">
        <div class="stats-grid">
            <div class="stat">
                <h3>4</h3>
                <p>{{ __('landing.stat_roles') }}</p>
            </div>
            <div class="stat">
                <h3>7</h3>
                <p>{{ __('landing.stat_dashboards') }}</p>
            </div>
            <div class="stat">
                <h3>19</h3>
                <p>{{ __('landing.stat_tests') }}</p>
            </div>
            <div class="stat">
                <h3>2</h3>
                <p>{{ __('landing.stat_languages') }}</p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <p>&copy; {{ date('Y') }} {{ __('landing.footer') }}</p>
    </footer>
</body>
</html>
