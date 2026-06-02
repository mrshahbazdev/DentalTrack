<?php

return [
    'rework' => [
        'flag' => 'Flag for Rework',
        'cause' => 'Root Cause',
        'description' => 'Description',
        'status' => 'Status',
        'flagged_by' => 'Flagged By',
        'original_technician' => 'Original Technician',
        'resolved_by' => 'Resolved By',
        'resolved_at' => 'Resolved At',
        'material_defect' => 'Material Defect',
        'technique_error' => 'Technique Error',
        'equipment_issue' => 'Equipment Issue',
        'design_error' => 'Design Error',
        'other' => 'Other',
        'pending' => 'Pending',
        'in_rework' => 'In Rework',
        'resolved' => 'Resolved',
    ],
    'quality' => [
        'dashboard' => 'Quality Control',
        'rework_rate' => 'Rework Rate',
        'total_reworks' => 'Total Reworks',
        'pending_reworks' => 'Pending Reworks',
        'resolved_reworks' => 'Resolved Reworks',
        'cause_breakdown' => 'Cause Breakdown',
        'recent_reworks' => 'Recent Rework Events',
    ],

    // Navigation & Pages
    'nav' => [
        'dashboard' => 'Dashboard',
        'live_order_board' => 'Live Order Board',
        'production' => 'Production',
        'analytics' => 'Analytics',
        'quality_control' => 'Quality Control',
        'employee_performance' => 'Employee Performance',
        'production_analytics' => 'Production Analytics',
        'company_comparison' => 'Company Comparison',
        'reports_export' => 'Reports & Export',
        'predictions' => 'Predictions',
        'quality_dashboard' => 'Quality Dashboard',
        'station_monitoring' => 'Station Monitoring',
        'broadcasts_overview' => 'Broadcasts Overview',
        'monitoring' => 'Monitoring',
    ],

    // Common labels
    'common' => [
        'from' => 'From',
        'to' => 'To',
        'company' => 'Company',
        'all_companies' => 'All Companies',
        'all_priorities' => 'All Priorities',
        'search_orders' => 'Search orders...',
        'order' => 'Order',
        'order_number' => 'Order No.',
        'product' => 'Product',
        'product_type' => 'Product Type',
        'due_date' => 'Due Date',
        'priority' => 'Priority',
        'status' => 'Status',
        'lab' => 'Lab',
        'workstation' => 'Workstation',
        'technician' => 'Technician',
        'progress' => 'Progress',
        'eta' => 'ETA',
        'current_station' => 'Current Station',
        'notes' => 'Notes',
        'patient_ref' => 'Patient Reference',
        'doctor_name' => 'Doctor Name',
        'created_at' => 'Created At',
        'actions' => 'Actions',
        'no_data' => 'No data for this period.',
        'sticker' => 'Sticker',
        'download_sticker' => 'Download Sticker',
        'regenerate_qr' => 'Regenerate QR',
        'active' => 'Active',
    ],

    // Order statuses
    'status' => [
        'pending' => 'Pending',
        'in_progress' => 'In Progress',
        'completed' => 'Completed',
        'cancelled' => 'Cancelled',
        'on_hold' => 'On Hold',
    ],

    // Priority
    'priority' => [
        'low' => 'Low',
        'normal' => 'Normal',
        'high' => 'High',
        'urgent' => 'Urgent',
    ],

    // Scan events
    'scan' => [
        'start' => 'Start',
        'complete' => 'Complete',
        'pause' => 'Pause',
        'transfer_to_waiting' => 'Transfer to Waiting Area',
    ],

    // Workstation types
    'workstation_type' => [
        'station' => 'Station',
        'waiting_area' => 'Waiting Area',
    ],

    // Step statuses
    'step_status' => [
        'pending' => 'Pending',
        'in_progress' => 'In Progress',
        'done' => 'Done',
        'skipped' => 'Skipped',
    ],

    // QR Scanner
    'scanner' => [
        'title' => 'DentalTrack Scanner',
        'step1' => 'Step 1: Scan Workstation QR',
        'step2' => 'Step 2: Scan Order QR',
        'step3' => 'Step 3: Confirm Action',
        'step4' => 'Step 4: Scan Next Workstation',
        'workstation' => 'Workstation:',
        'order' => 'Order:',
        'current_step' => 'Current Step:',
        'add_note' => 'Add note (optional)',
        'start_work' => 'START WORK',
        'pause' => 'PAUSE',
        'complete_next' => 'COMPLETE & NEXT',
        'reset' => 'Reset',
    ],

    // Live Order Board
    'board' => [
        'overdue' => 'OVERDUE',
        'in_progress' => 'IN PROGRESS',
        'pending' => 'PENDING',
        'no_orders_in_progress' => 'No orders in progress',
        'no_orders_pending' => 'No pending orders',
    ],

    // Employee Performance
    'performance' => [
        'steps_done' => 'Steps Done',
        'orders_done' => 'Orders Done',
        'avg_min_step' => 'Avg Min/Step',
        'orders_per_day' => 'Orders/Day',
        'total_hours' => 'Total Hours',
        'utilization' => 'Utilization',
    ],

    // Production Analytics
    'analytics' => [
        'total_orders' => 'Total Orders',
        'completed' => 'Completed',
        'in_progress' => 'In Progress',
        'overdue' => 'Overdue',
        'on_time_rate' => 'On-Time Rate',
        'completion_rate' => 'Completion Rate',
        'bottleneck_analysis' => 'Bottleneck Analysis (Slowest Stations)',
        'station' => 'Station',
        'avg_min' => 'Avg Min.',
        'events' => 'Events',
        'slowest' => 'SLOWEST',
        'product_type_breakdown' => 'Product Type Breakdown',
        'avg_hours' => 'Avg Hours',
        'daily_throughput' => 'Daily Throughput (Completed Orders)',
        'orders_completed' => 'Orders Completed',
    ],

    // Company Comparison
    'comparison' => [
        'technicians' => 'Technicians',
        'workstations' => 'Workstations',
        'on_time_delivery' => 'On-Time Delivery',
        'avg_step_time' => 'Avg Step Time',
        'min_per_step' => 'Min/Step',
    ],

    // Reports
    'reports' => [
        'export_data' => 'Export Data',
        'report_type' => 'Report Type',
        'orders_report' => 'Orders Report',
        'scan_events_report' => 'Scan Events Report',
        'export_excel' => 'Export Excel (.xlsx)',
        'export_csv' => 'Export CSV',
        'orders_report_desc' => 'Export all orders with company, lab, product type, patient reference, doctor, priority, status, due date, progress and completion data.',
        'scan_events_report_desc' => 'Export all scan events with order, step, workstation, technician, event type, timestamp and duration.',
    ],

    // Predictions
    'predictions' => [
        'overall_accuracy' => 'Overall Accuracy',
        'predictions_count' => 'Predictions',
        'last_7_days' => 'Last 7 Days Accuracy',
        'model_versions' => 'Model Versions',
        'no_predictions_yet' => 'No predictions with actual values yet',
        'accuracy_trend' => 'Accuracy Trend (Last 30 Days)',
        'smart_suggestions' => 'Smart Suggestions',
        'no_suggestions' => 'No suggestions at the moment. Everything looks good!',
        'active_order_etas' => 'Active Order ETAs',
        'no_active_predictions' => 'No active orders with predictions',
        'recent_predictions' => 'Recent Predictions',
        'predicted' => 'Predicted',
        'actual' => 'Actual',
        'accuracy' => 'Accuracy',
        'version' => 'Version',
        'when' => 'When',
        'no_predictions_data' => 'No predictions yet',
    ],

    // Quality Control
    'qc' => [
        'root_cause_breakdown' => 'Root Cause Breakdown',
        'technician_quality' => 'Technician Quality',
        'reworks' => 'Reworks',
        'total_steps' => 'Total Steps',
        'rework_pct' => 'Rework %',
        'no_technician_data' => 'No technician rework data',
        'recent_rework_events' => 'Recent Rework Events',
        'step' => 'Step',
        'cause' => 'Cause',
        'flagged_by' => 'Flagged By',
        'when' => 'When',
        'no_rework_events' => 'No rework events in this period',
    ],

    // Station Monitoring
    'monitoring' => [
        'title' => 'Station Monitoring',
        'avg_time_per_station' => 'Average Processing Time per Station',
        'station_name' => 'Station Name',
        'station_type' => 'Type',
        'avg_duration' => 'Avg Duration',
        'total_events' => 'Total Events',
        'min_duration' => 'Min Duration',
        'max_duration' => 'Max Duration',
        'active_orders' => 'Active Orders at this Station',
        'no_monitoring_data' => 'No monitoring data for this period.',
        'recent_activity' => 'Recent Activity',
        'event_type' => 'Event Type',
        'duration' => 'Duration',
        'time' => 'Time',
        'no_recent_activity' => 'No recent activity',
    ],

    // Broadcasts Overview
    'broadcasts' => [
        'title' => 'Broadcasts Overview',
        'channel' => 'Channel',
        'event_type' => 'Event Type',
        'order_id' => 'Order No.',
        'payload' => 'Payload',
        'triggered_at' => 'Triggered At',
        'triggered_by' => 'Triggered By',
        'no_broadcasts' => 'No broadcast events in this period.',
        'total_broadcasts' => 'Total Broadcasts',
        'today' => 'Today',
        'last_hour' => 'Last Hour',
    ],

    // Sticker labels
    'sticker' => [
        'order' => 'Order',
    ],

    // Resource labels
    'resource' => [
        'administration' => 'Administration',
        'production' => 'Production',
    ],

    // Landing page
    'landing' => [
        'title' => 'DentalTrack — QR-Based Production Tracking',
        'track_order' => 'Track Order',
        'scan_qr' => 'Scan QR',
        'admin_area' => 'Admin Panel',
        'hero_heading' => 'Intelligent Production Tracking<br>for <span>Dental Labs</span>',
        'hero_text' => 'QR code-based real-time tracking system for dental labs. Scan, track and analyse every step of the production process — from impression to delivery.',
        'open_dashboard' => 'Open Dashboard',
        'start_scanning' => 'Start Scanning',
        'features_heading' => 'Everything You Need',
        'features_subtitle' => 'Complete production management for modern dental labs',
        'feat_qr_title' => 'QR Code Scanning',
        'feat_qr_desc' => 'Scan order and workstation QR codes with any smartphone browser. No app installation required — works as a PWA.',
        'feat_live_title' => 'Live Dashboard',
        'feat_live_desc' => 'Real-time order board with WebSocket updates. See running, pending and overdue orders at a glance.',
        'feat_ai_title' => 'AI Predictions',
        'feat_ai_desc' => 'Weighted historical average model predicts completion times. Smart suggestions for bottleneck detection and optimal routing.',
        'feat_analytics_title' => 'Analytics & Reports',
        'feat_analytics_desc' => 'Employee performance, production analytics, company comparison and exportable reports in Excel/CSV format.',
        'feat_qc_title' => 'Quality Control',
        'feat_qc_desc' => 'Flag failed QC steps, track rework causes and monitor technician quality with the QC dashboard.',
        'feat_portal_title' => 'Customer Portal',
        'feat_portal_desc' => 'Doctors and clinics can track their orders online with a simple tracking code. Multi-language support.',
        'how_heading' => 'How It Works',
        'step1_title' => 'Create Order',
        'step1_desc' => 'The lab manager creates an order with patient data, product type and due date. The QR code is generated automatically.',
        'step2_title' => 'Print QR Sticker',
        'step2_desc' => 'Print small QR stickers for orders and large ones for workstations. Compatible with thermal printers.',
        'step3_title' => 'Scan & Track',
        'step3_desc' => 'Technicians scan the workstation QR, then the order QR. Start, pause or complete work with a tap.',
        'step4_title' => 'Monitor & Deliver',
        'step4_desc' => 'Management tracks progress in real time. AI predicts completion. Doctors receive updates via the portal.',
        'stat_roles' => 'User Roles',
        'stat_pages' => 'Dashboard Pages',
        'stat_tests' => 'Automated Tests',
        'stat_languages' => 'Languages',
        'footer' => 'DentalTrack — QR-based production tracking system for dental labs',
    ],
];
