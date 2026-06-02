<?php

return [
    'rework' => [
        'flag' => 'Zur Nacharbeit markieren',
        'cause' => 'Ursache',
        'description' => 'Beschreibung',
        'status' => 'Status',
        'flagged_by' => 'Markiert von',
        'original_technician' => 'Urspr. Techniker',
        'resolved_by' => 'Behoben von',
        'resolved_at' => 'Behoben am',
        'material_defect' => 'Materialfehler',
        'technique_error' => 'Technikfehler',
        'equipment_issue' => 'Geraeteproblem',
        'design_error' => 'Designfehler',
        'other' => 'Sonstiges',
        'pending' => 'Ausstehend',
        'in_rework' => 'In Nacharbeit',
        'resolved' => 'Behoben',
    ],
    'quality' => [
        'dashboard' => 'Qualitaetskontrolle',
        'rework_rate' => 'Nacharbeitsquote',
        'total_reworks' => 'Nacharbeiten gesamt',
        'pending_reworks' => 'Ausstehende Nacharbeiten',
        'resolved_reworks' => 'Behobene Nacharbeiten',
        'cause_breakdown' => 'Ursachenverteilung',
        'recent_reworks' => 'Aktuelle Nacharbeiten',
    ],

    // Navigation & Pages
    'nav' => [
        'dashboard' => 'Dashboard',
        'live_order_board' => 'Live-Auftragsboard',
        'production' => 'Produktion',
        'analytics' => 'Analysen',
        'quality_control' => 'Qualitaetskontrolle',
        'employee_performance' => 'Mitarbeiterleistung',
        'production_analytics' => 'Produktionsanalyse',
        'company_comparison' => 'Firmenvergleich',
        'reports_export' => 'Berichte & Export',
        'predictions' => 'Prognosen',
        'quality_dashboard' => 'Qualitaets-Dashboard',
        'station_monitoring' => 'Stationsmonitoring',
        'broadcasts_overview' => 'Broadcast-Uebersicht',
        'monitoring' => 'Monitoring',
    ],

    // Common labels
    'common' => [
        'from' => 'Von',
        'to' => 'Bis',
        'company' => 'Firma',
        'all_companies' => 'Alle Firmen',
        'all_priorities' => 'Alle Prioritaeten',
        'search_orders' => 'Auftraege suchen...',
        'order' => 'Auftrag',
        'order_number' => 'Auftrags-Nr.',
        'product' => 'Produkt',
        'product_type' => 'Produkttyp',
        'due_date' => 'Faelligkeitsdatum',
        'priority' => 'Prioritaet',
        'status' => 'Status',
        'lab' => 'Labor',
        'workstation' => 'Arbeitsstation',
        'technician' => 'Techniker',
        'progress' => 'Fortschritt',
        'eta' => 'ETA',
        'current_station' => 'Aktuelle Station',
        'notes' => 'Notizen',
        'patient_ref' => 'Patientenreferenz',
        'doctor_name' => 'Arztname',
        'created_at' => 'Erstellt am',
        'actions' => 'Aktionen',
        'no_data' => 'Keine Daten fuer diesen Zeitraum.',
        'sticker' => 'Aufkleber',
        'download_sticker' => 'Aufkleber herunterladen',
        'regenerate_qr' => 'QR neu generieren',
        'active' => 'Aktiv',
    ],

    // Order statuses
    'status' => [
        'pending' => 'Ausstehend',
        'in_progress' => 'In Bearbeitung',
        'completed' => 'Abgeschlossen',
        'cancelled' => 'Storniert',
        'on_hold' => 'Pausiert',
    ],

    // Priority
    'priority' => [
        'low' => 'Niedrig',
        'normal' => 'Normal',
        'high' => 'Hoch',
        'urgent' => 'Dringend',
    ],

    // Scan events
    'scan' => [
        'start' => 'Start',
        'complete' => 'Abgeschlossen',
        'pause' => 'Pause',
        'transfer_to_waiting' => 'Transfer zum Wartebereich',
    ],

    // Workstation types
    'workstation_type' => [
        'station' => 'Station',
        'waiting_area' => 'Wartebereich',
    ],

    // Step statuses
    'step_status' => [
        'pending' => 'Ausstehend',
        'in_progress' => 'In Bearbeitung',
        'done' => 'Erledigt',
        'skipped' => 'Uebersprungen',
    ],

    // QR Scanner
    'scanner' => [
        'title' => 'DentalTrack Scanner',
        'step1' => 'Schritt 1: Arbeitsstation-QR scannen',
        'step2' => 'Schritt 2: Auftrags-QR scannen',
        'step3' => 'Schritt 3: Aktion bestaetigen',
        'step4' => 'Schritt 4: Naechste Arbeitsstation scannen',
        'workstation' => 'Arbeitsstation:',
        'order' => 'Auftrag:',
        'current_step' => 'Aktueller Schritt:',
        'add_note' => 'Notiz hinzufuegen (optional)',
        'start_work' => 'ARBEIT STARTEN',
        'pause' => 'PAUSE',
        'complete_next' => 'ABSCHLIESSEN & WEITER',
        'reset' => 'Zuruecksetzen',
    ],

    // Live Order Board
    'board' => [
        'overdue' => 'UEBERFAELLIG',
        'in_progress' => 'IN BEARBEITUNG',
        'pending' => 'AUSSTEHEND',
        'no_orders_in_progress' => 'Keine Auftraege in Bearbeitung',
        'no_orders_pending' => 'Keine ausstehenden Auftraege',
    ],

    // Employee Performance
    'performance' => [
        'steps_done' => 'Schritte erledigt',
        'orders_done' => 'Auftraege erledigt',
        'avg_min_step' => 'Durchschn. Min/Schritt',
        'orders_per_day' => 'Auftraege/Tag',
        'total_hours' => 'Gesamtstunden',
        'utilization' => 'Auslastung',
    ],

    // Production Analytics
    'analytics' => [
        'total_orders' => 'Auftraege gesamt',
        'completed' => 'Abgeschlossen',
        'in_progress' => 'In Bearbeitung',
        'overdue' => 'Ueberfaellig',
        'on_time_rate' => 'Puenktlichkeitsrate',
        'completion_rate' => 'Abschlussrate',
        'bottleneck_analysis' => 'Engpassanalyse (Langsamste Stationen)',
        'station' => 'Station',
        'avg_min' => 'Durchschn. Min.',
        'events' => 'Ereignisse',
        'slowest' => 'LANGSAMSTE',
        'product_type_breakdown' => 'Produkttyp-Auswertung',
        'avg_hours' => 'Durchschn. Stunden',
        'daily_throughput' => 'Taeglicher Durchsatz (abgeschlossene Auftraege)',
        'orders_completed' => 'Auftraege abgeschlossen',
    ],

    // Company Comparison
    'comparison' => [
        'technicians' => 'Techniker',
        'workstations' => 'Arbeitsstationen',
        'on_time_delivery' => 'Puenktliche Lieferung',
        'avg_step_time' => 'Durchschn. Schrittzeit',
        'min_per_step' => 'Min/Schritt',
    ],

    // Reports
    'reports' => [
        'export_data' => 'Daten exportieren',
        'report_type' => 'Berichtstyp',
        'orders_report' => 'Auftragsbericht',
        'scan_events_report' => 'Scan-Ereignisbericht',
        'export_excel' => 'Excel exportieren (.xlsx)',
        'export_csv' => 'CSV exportieren',
        'orders_report_desc' => 'Alle Auftraege mit Firma, Labor, Produkttyp, Patientenreferenz, Arzt, Prioritaet, Status, Faelligkeitsdatum, Fortschritt und Abschlussdaten exportieren.',
        'scan_events_report_desc' => 'Alle Scan-Ereignisse mit Auftrag, Schritt, Arbeitsstation, Techniker, Ereignistyp, Zeitstempel und Dauer exportieren.',
    ],

    // Predictions
    'predictions' => [
        'overall_accuracy' => 'Gesamtgenauigkeit',
        'predictions_count' => 'Prognosen',
        'last_7_days' => 'Letzten 7 Tage Genauigkeit',
        'model_versions' => 'Modellversionen',
        'no_predictions_yet' => 'Noch keine Prognosen mit Ist-Werten',
        'accuracy_trend' => 'Genauigkeitstrend (Letzte 30 Tage)',
        'smart_suggestions' => 'Intelligente Vorschlaege',
        'no_suggestions' => 'Keine Vorschlaege derzeit. Alles sieht gut aus!',
        'active_order_etas' => 'Aktive Auftrags-ETAs',
        'no_active_predictions' => 'Keine aktiven Auftraege mit Prognosen',
        'recent_predictions' => 'Aktuelle Prognosen',
        'predicted' => 'Prognose',
        'actual' => 'Ist',
        'accuracy' => 'Genauigkeit',
        'version' => 'Version',
        'when' => 'Wann',
        'no_predictions_data' => 'Noch keine Prognosen',
    ],

    // Quality Control
    'qc' => [
        'root_cause_breakdown' => 'Ursachenverteilung',
        'technician_quality' => 'Technikerqualitaet',
        'reworks' => 'Nacharbeiten',
        'total_steps' => 'Schritte gesamt',
        'rework_pct' => 'Nacharbeit %',
        'no_technician_data' => 'Keine Techniker-Nacharbeitsdaten',
        'recent_rework_events' => 'Aktuelle Nacharbeitsereignisse',
        'step' => 'Schritt',
        'cause' => 'Ursache',
        'flagged_by' => 'Markiert von',
        'when' => 'Wann',
        'no_rework_events' => 'Keine Nacharbeitsereignisse in diesem Zeitraum',
    ],

    // Station Monitoring
    'monitoring' => [
        'title' => 'Stationsmonitoring',
        'avg_time_per_station' => 'Durchschnittliche Bearbeitungszeit pro Station',
        'station_name' => 'Stationsname',
        'station_type' => 'Typ',
        'avg_duration' => 'Durchschn. Dauer',
        'total_events' => 'Ereignisse gesamt',
        'min_duration' => 'Min. Dauer',
        'max_duration' => 'Max. Dauer',
        'active_orders' => 'Aktive Auftraege an dieser Station',
        'no_monitoring_data' => 'Keine Monitoring-Daten fuer diesen Zeitraum.',
        'recent_activity' => 'Letzte Aktivitaeten',
        'event_type' => 'Ereignistyp',
        'duration' => 'Dauer',
        'time' => 'Zeit',
        'no_recent_activity' => 'Keine aktuellen Aktivitaeten',
    ],

    // Broadcasts Overview
    'broadcasts' => [
        'title' => 'Broadcast-Uebersicht',
        'channel' => 'Kanal',
        'event_type' => 'Ereignistyp',
        'order_id' => 'Auftrags-Nr.',
        'payload' => 'Daten',
        'triggered_at' => 'Ausgeloest am',
        'triggered_by' => 'Ausgeloest von',
        'no_broadcasts' => 'Keine Broadcast-Ereignisse in diesem Zeitraum.',
        'total_broadcasts' => 'Broadcasts gesamt',
        'today' => 'Heute',
        'last_hour' => 'Letzte Stunde',
    ],

    // Sticker labels
    'sticker' => [
        'order' => 'Auftrag',
    ],

    // Resource labels
    'resource' => [
        'administration' => 'Administration',
        'production' => 'Produktion',
    ],

    // Landing page
    'landing' => [
        'title' => 'DentalTrack — QR-basierte Produktionsverfolgung',
        'track_order' => 'Auftrag verfolgen',
        'scan_qr' => 'QR scannen',
        'admin_area' => 'Admin-Bereich',
        'hero_heading' => 'Intelligente Produktionsverfolgung<br>fuer <span>Dentallabore</span>',
        'hero_text' => 'QR-Code-basiertes Echtzeit-Tracking-System fuer Dentallabore. Scannen, verfolgen und analysieren Sie jeden Schritt des Produktionsprozesses — vom Abdruck bis zur Lieferung.',
        'open_dashboard' => 'Dashboard oeffnen',
        'start_scanning' => 'Scannen starten',
        'features_heading' => 'Alles was Sie brauchen',
        'features_subtitle' => 'Komplettes Produktionsmanagement fuer moderne Dentallabore',
        'feat_qr_title' => 'QR-Code-Scannen',
        'feat_qr_desc' => 'Scannen Sie Auftrags- und Arbeitsstations-QR-Codes mit jedem Smartphone-Browser. Keine App-Installation noetig — funktioniert als PWA.',
        'feat_live_title' => 'Live-Dashboard',
        'feat_live_desc' => 'Echtzeit-Auftragstafel mit WebSocket-Updates. Sehen Sie laufende, ausstehende und ueberfaellige Auftraege auf einen Blick.',
        'feat_ai_title' => 'KI-Prognosen',
        'feat_ai_desc' => 'Gewichtetes historisches Durchschnittsmodell sagt Fertigstellungszeiten voraus. Intelligente Vorschlaege zur Engpasserkennung und optimalen Weiterleitung.',
        'feat_analytics_title' => 'Analysen & Berichte',
        'feat_analytics_desc' => 'Mitarbeiterleistung, Produktionsanalysen, Firmenvergleich und exportierbare Berichte im Excel/CSV-Format.',
        'feat_qc_title' => 'Qualitaetskontrolle',
        'feat_qc_desc' => 'Markieren Sie fehlgeschlagene QK-Schritte, verfolgen Sie Nacharbeitsursachen und ueberwachen Sie Technikerqualitaet mit dem QK-Dashboard.',
        'feat_portal_title' => 'Kundenportal',
        'feat_portal_desc' => 'Aerzte und Kliniken koennen ihre Auftraege online mit einem einfachen Tracking-Code verfolgen. Mehrsprachige Unterstuetzung.',
        'how_heading' => 'So funktioniert es',
        'step1_title' => 'Auftrag erstellen',
        'step1_desc' => 'Der Laborleiter erstellt einen Auftrag mit Patientendaten, Produkttyp und Faelligkeitsdatum. Der QR-Code wird automatisch generiert.',
        'step2_title' => 'QR-Sticker drucken',
        'step2_desc' => 'Drucken Sie kleine QR-Sticker fuer Auftraege und grosse fuer Arbeitsstationen. Kompatibel mit Thermodruckern.',
        'step3_title' => 'Scannen & Verfolgen',
        'step3_desc' => 'Techniker scannen den Arbeitsstations-QR, dann den Auftrags-QR. Arbeit starten, pausieren oder abschliessen mit einem Tipp.',
        'step4_title' => 'Ueberwachen & Liefern',
        'step4_desc' => 'Das Management verfolgt den Fortschritt in Echtzeit. KI sagt die Fertigstellung voraus. Aerzte erhalten Updates ueber das Portal.',
        'stat_roles' => 'Benutzerrollen',
        'stat_pages' => 'Dashboard-Seiten',
        'stat_tests' => 'Automatisierte Tests',
        'stat_languages' => 'Sprachen',
        'footer' => 'DentalTrack — QR-basiertes Produktionsverfolgungssystem fuer Dentallabore',
    ],
];
