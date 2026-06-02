<?php

return [
    'rework' => [
        'flag' => 'دوبارہ کام کے لیے نشان زد کریں',
        'cause' => 'بنیادی وجہ',
        'description' => 'تفصیل',
        'status' => 'حیثیت',
        'flagged_by' => 'نشان زد کرنے والا',
        'original_technician' => 'اصل ٹیکنیشن',
        'resolved_by' => 'حل کرنے والا',
        'resolved_at' => 'حل ہونے کی تاریخ',
        'material_defect' => 'مواد کی خرابی',
        'technique_error' => 'تکنیکی غلطی',
        'equipment_issue' => 'آلات کا مسئلہ',
        'design_error' => 'ڈیزائن کی غلطی',
        'other' => 'دیگر',
        'pending' => 'زیر التوا',
        'in_rework' => 'دوبارہ کام میں',
        'resolved' => 'حل ہو گیا',
    ],
    'quality' => [
        'dashboard' => 'کوالٹی کنٹرول',
        'rework_rate' => 'دوبارہ کام کی شرح',
        'total_reworks' => 'کل دوبارہ کام',
        'pending_reworks' => 'زیر التوا دوبارہ کام',
        'resolved_reworks' => 'حل شدہ دوبارہ کام',
        'cause_breakdown' => 'وجوہات کی تفصیل',
        'recent_reworks' => 'حالیہ دوبارہ کام',
    ],

    // نیویگیشن اور صفحات
    'nav' => [
        'dashboard' => 'ڈیش بورڈ',
        'live_order_board' => 'لائیو آرڈر بورڈ',
        'production' => 'پروڈکشن',
        'analytics' => 'تجزیات',
        'quality_control' => 'کوالٹی کنٹرول',
        'employee_performance' => 'ملازمین کی کارکردگی',
        'production_analytics' => 'پروڈکشن تجزیات',
        'company_comparison' => 'کمپنی موازنہ',
        'reports_export' => 'رپورٹس اور ایکسپورٹ',
        'predictions' => 'پیش گوئیاں',
        'quality_dashboard' => 'کوالٹی ڈیش بورڈ',
        'station_monitoring' => 'اسٹیشن مانیٹرنگ',
        'broadcasts_overview' => 'براڈکاسٹ جائزہ',
        'monitoring' => 'مانیٹرنگ',
    ],

    // عام لیبلز
    'common' => [
        'from' => 'سے',
        'to' => 'تک',
        'company' => 'کمپنی',
        'all_companies' => 'تمام کمپنیاں',
        'all_priorities' => 'تمام ترجیحات',
        'search_orders' => 'آرڈرز تلاش کریں...',
        'order' => 'آرڈر',
        'order_number' => 'آرڈر نمبر',
        'product' => 'پروڈکٹ',
        'product_type' => 'پروڈکٹ کی قسم',
        'due_date' => 'آخری تاریخ',
        'priority' => 'ترجیح',
        'status' => 'حیثیت',
        'lab' => 'لیب',
        'workstation' => 'ورک اسٹیشن',
        'technician' => 'ٹیکنیشن',
        'progress' => 'پیش رفت',
        'eta' => 'متوقع وقت',
        'current_station' => 'موجودہ اسٹیشن',
        'notes' => 'نوٹس',
        'patient_ref' => 'مریض کا حوالہ',
        'doctor_name' => 'ڈاکٹر کا نام',
        'created_at' => 'بنایا گیا',
        'actions' => 'اعمال',
        'no_data' => 'اس مدت کے لیے کوئی ڈیٹا نہیں۔',
        'sticker' => 'اسٹیکر',
        'download_sticker' => 'اسٹیکر ڈاؤن لوڈ',
        'regenerate_qr' => 'QR دوبارہ بنائیں',
        'active' => 'فعال',
    ],

    // آرڈر اسٹیٹس
    'status' => [
        'pending' => 'زیر التوا',
        'in_progress' => 'جاری',
        'completed' => 'مکمل',
        'cancelled' => 'منسوخ',
        'on_hold' => 'روک دیا گیا',
    ],

    // ترجیح
    'priority' => [
        'low' => 'کم',
        'normal' => 'نارمل',
        'high' => 'زیادہ',
        'urgent' => 'فوری',
    ],

    // اسکین ایونٹس
    'scan' => [
        'start' => 'شروع',
        'complete' => 'مکمل',
        'pause' => 'وقفہ',
        'transfer_to_waiting' => 'ویٹنگ ایریا منتقلی',
    ],

    // ورک اسٹیشن کی اقسام
    'workstation_type' => [
        'station' => 'اسٹیشن',
        'waiting_area' => 'ویٹنگ ایریا',
    ],

    // اسٹیپ اسٹیٹس
    'step_status' => [
        'pending' => 'زیر التوا',
        'in_progress' => 'جاری',
        'done' => 'مکمل',
        'skipped' => 'چھوڑ دیا',
    ],

    // QR اسکینر
    'scanner' => [
        'title' => 'DentalTrack اسکینر',
        'step1' => 'مرحلہ 1: ورک اسٹیشن QR اسکین کریں',
        'step2' => 'مرحلہ 2: آرڈر QR اسکین کریں',
        'step3' => 'مرحلہ 3: عمل کی تصدیق کریں',
        'step4' => 'مرحلہ 4: اگلا ورک اسٹیشن اسکین کریں',
        'workstation' => 'ورک اسٹیشن:',
        'order' => 'آرڈر:',
        'current_step' => 'موجودہ مرحلہ:',
        'add_note' => 'نوٹ شامل کریں (اختیاری)',
        'start_work' => 'کام شروع کریں',
        'pause' => 'وقفہ',
        'complete_next' => 'مکمل کریں اور اگلا',
        'reset' => 'دوبارہ شروع',
    ],

    // لائیو آرڈر بورڈ
    'board' => [
        'overdue' => 'تاخیر شدہ',
        'in_progress' => 'جاری',
        'pending' => 'زیر التوا',
        'no_orders_in_progress' => 'کوئی آرڈر جاری نہیں',
        'no_orders_pending' => 'کوئی زیر التوا آرڈر نہیں',
    ],

    // ملازمین کی کارکردگی
    'performance' => [
        'steps_done' => 'مکمل مراحل',
        'orders_done' => 'مکمل آرڈرز',
        'avg_min_step' => 'اوسط منٹ/مرحلہ',
        'orders_per_day' => 'آرڈرز/دن',
        'total_hours' => 'کل گھنٹے',
        'utilization' => 'استعمال',
    ],

    // پروڈکشن تجزیات
    'analytics' => [
        'total_orders' => 'کل آرڈرز',
        'completed' => 'مکمل',
        'in_progress' => 'جاری',
        'overdue' => 'تاخیر شدہ',
        'on_time_rate' => 'وقت پر شرح',
        'completion_rate' => 'تکمیل کی شرح',
        'bottleneck_analysis' => 'رکاوٹ تجزیہ (سست ترین اسٹیشنز)',
        'station' => 'اسٹیشن',
        'avg_min' => 'اوسط منٹ',
        'events' => 'واقعات',
        'slowest' => 'سست ترین',
        'product_type_breakdown' => 'پروڈکٹ کی قسم کی تفصیل',
        'avg_hours' => 'اوسط گھنٹے',
        'daily_throughput' => 'روزانہ پیداوار (مکمل آرڈرز)',
        'orders_completed' => 'مکمل آرڈرز',
    ],

    // کمپنی موازنہ
    'comparison' => [
        'technicians' => 'ٹیکنیشنز',
        'workstations' => 'ورک اسٹیشنز',
        'on_time_delivery' => 'بروقت ڈیلیوری',
        'avg_step_time' => 'اوسط مرحلہ وقت',
        'min_per_step' => 'منٹ/مرحلہ',
    ],

    // رپورٹس
    'reports' => [
        'export_data' => 'ڈیٹا ایکسپورٹ',
        'report_type' => 'رپورٹ کی قسم',
        'orders_report' => 'آرڈرز رپورٹ',
        'scan_events_report' => 'اسکین ایونٹس رپورٹ',
        'export_excel' => 'Excel ایکسپورٹ (.xlsx)',
        'export_csv' => 'CSV ایکسپورٹ',
        'orders_report_desc' => 'تمام آرڈرز کمپنی، لیب، پروڈکٹ کی قسم، مریض حوالہ، ڈاکٹر، ترجیح، حیثیت، آخری تاریخ، پیش رفت اور تکمیل ڈیٹا کے ساتھ ایکسپورٹ کریں۔',
        'scan_events_report_desc' => 'تمام اسکین ایونٹس آرڈر، مرحلہ، ورک اسٹیشن، ٹیکنیشن، ایونٹ کی قسم، ٹائم اسٹیمپ اور مدت کے ساتھ ایکسپورٹ کریں۔',
    ],

    // پیش گوئیاں
    'predictions' => [
        'overall_accuracy' => 'مجموعی درستگی',
        'predictions_count' => 'پیش گوئیاں',
        'last_7_days' => 'آخری 7 دن کی درستگی',
        'model_versions' => 'ماڈل ورژنز',
        'no_predictions_yet' => 'ابھی تک حقیقی اقدار کے ساتھ کوئی پیش گوئی نہیں',
        'accuracy_trend' => 'درستگی کا رجحان (آخری 30 دن)',
        'smart_suggestions' => 'ذہین تجاویز',
        'no_suggestions' => 'اس وقت کوئی تجاویز نہیں۔ سب ٹھیک لگ رہا ہے!',
        'active_order_etas' => 'فعال آرڈر کے متوقع اوقات',
        'no_active_predictions' => 'پیش گوئیوں والے کوئی فعال آرڈر نہیں',
        'recent_predictions' => 'حالیہ پیش گوئیاں',
        'predicted' => 'پیش گوئی',
        'actual' => 'حقیقی',
        'accuracy' => 'درستگی',
        'version' => 'ورژن',
        'when' => 'کب',
        'no_predictions_data' => 'ابھی تک کوئی پیش گوئی نہیں',
    ],

    // کوالٹی کنٹرول
    'qc' => [
        'root_cause_breakdown' => 'بنیادی وجوہات کی تفصیل',
        'technician_quality' => 'ٹیکنیشن کوالٹی',
        'reworks' => 'دوبارہ کام',
        'total_steps' => 'کل مراحل',
        'rework_pct' => 'دوبارہ کام %',
        'no_technician_data' => 'ٹیکنیشن دوبارہ کام کا ڈیٹا نہیں',
        'recent_rework_events' => 'حالیہ دوبارہ کام کے واقعات',
        'step' => 'مرحلہ',
        'cause' => 'وجہ',
        'flagged_by' => 'نشان زد کرنے والا',
        'when' => 'کب',
        'no_rework_events' => 'اس مدت میں دوبارہ کام کے واقعات نہیں',
    ],

    // اسٹیشن مانیٹرنگ
    'monitoring' => [
        'title' => 'اسٹیشن مانیٹرنگ',
        'avg_time_per_station' => 'فی اسٹیشن اوسط پروسیسنگ وقت',
        'station_name' => 'اسٹیشن کا نام',
        'station_type' => 'قسم',
        'avg_duration' => 'اوسط مدت',
        'total_events' => 'کل واقعات',
        'min_duration' => 'کم از کم مدت',
        'max_duration' => 'زیادہ سے زیادہ مدت',
        'active_orders' => 'اس اسٹیشن پر فعال آرڈرز',
        'no_monitoring_data' => 'اس مدت کے لیے مانیٹرنگ ڈیٹا نہیں۔',
        'recent_activity' => 'حالیہ سرگرمی',
        'event_type' => 'واقعہ کی قسم',
        'duration' => 'مدت',
        'time' => 'وقت',
        'no_recent_activity' => 'کوئی حالیہ سرگرمی نہیں',
    ],

    // براڈکاسٹ جائزہ
    'broadcasts' => [
        'title' => 'براڈکاسٹ جائزہ',
        'channel' => 'چینل',
        'event_type' => 'واقعہ کی قسم',
        'order_id' => 'آرڈر نمبر',
        'payload' => 'ڈیٹا',
        'triggered_at' => 'متحرک ہونے کی تاریخ',
        'triggered_by' => 'متحرک کرنے والا',
        'no_broadcasts' => 'اس مدت میں کوئی براڈکاسٹ واقعات نہیں۔',
        'total_broadcasts' => 'کل براڈکاسٹس',
        'today' => 'آج',
        'last_hour' => 'آخری گھنٹہ',
    ],

    // اسٹیکر لیبلز
    'sticker' => [
        'order' => 'آرڈر',
    ],

    // ریسورس لیبلز
    'resource' => [
        'administration' => 'انتظامیہ',
        'production' => 'پروڈکشن',
    ],

    // لینڈنگ پیج
    'landing' => [
        'title' => 'DentalTrack — QR پر مبنی پروڈکشن ٹریکنگ',
        'track_order' => 'آرڈر ٹریک کریں',
        'scan_qr' => 'QR اسکین',
        'admin_area' => 'ایڈمن پینل',
        'hero_heading' => 'ذہین پروڈکشن ٹریکنگ<br><span>ڈینٹل لیبز</span> کے لیے',
        'hero_text' => 'ڈینٹل لیبز کے لیے QR کوڈ پر مبنی ریئل ٹائم ٹریکنگ سسٹم۔ پروڈکشن کے ہر مرحلے کو اسکین، ٹریک اور تجزیہ کریں — امپریشن سے ڈیلیوری تک۔',
        'open_dashboard' => 'ڈیش بورڈ کھولیں',
        'start_scanning' => 'اسکیننگ شروع کریں',
        'features_heading' => 'آپ کو جو کچھ بھی چاہیے',
        'features_subtitle' => 'جدید ڈینٹل لیبز کے لیے مکمل پروڈکشن مینجمنٹ',
        'feat_qr_title' => 'QR کوڈ اسکیننگ',
        'feat_qr_desc' => 'کسی بھی اسمارٹ فون براؤزر سے آرڈر اور ورک اسٹیشن QR کوڈز اسکین کریں۔ کسی ایپ کی ضرورت نہیں — PWA کے طور پر کام کرتا ہے۔',
        'feat_live_title' => 'لائیو ڈیش بورڈ',
        'feat_live_desc' => 'WebSocket اپڈیٹس کے ساتھ ریئل ٹائم آرڈر بورڈ۔ جاری، زیر التوا اور تاخیر شدہ آرڈرز ایک نظر میں دیکھیں۔',
        'feat_ai_title' => 'AI پیش گوئیاں',
        'feat_ai_desc' => 'وزنی تاریخی اوسط ماڈل تکمیل کے اوقات کی پیش گوئی کرتا ہے۔ رکاوٹ کی نشاندہی اور بہترین روٹنگ کے لیے ذہین تجاویز۔',
        'feat_analytics_title' => 'تجزیات اور رپورٹس',
        'feat_analytics_desc' => 'ملازمین کی کارکردگی، پروڈکشن تجزیات، کمپنی موازنہ اور Excel/CSV فارمیٹ میں ایکسپورٹ ہونے والی رپورٹس۔',
        'feat_qc_title' => 'کوالٹی کنٹرول',
        'feat_qc_desc' => 'ناکام QC مراحل کو نشان زد کریں، دوبارہ کام کی وجوہات ٹریک کریں اور QC ڈیش بورڈ سے ٹیکنیشن کوالٹی مانیٹر کریں۔',
        'feat_portal_title' => 'کسٹمر پورٹل',
        'feat_portal_desc' => 'ڈاکٹر اور کلینک ایک سادہ ٹریکنگ کوڈ سے اپنے آرڈرز آن لائن ٹریک کر سکتے ہیں۔ کثیر زبان سپورٹ۔',
        'how_heading' => 'یہ کیسے کام کرتا ہے',
        'step1_title' => 'آرڈر بنائیں',
        'step1_desc' => 'لیب مینیجر مریض ڈیٹا، پروڈکٹ کی قسم اور آخری تاریخ کے ساتھ آرڈر بناتا ہے۔ QR کوڈ خودکار طور پر بنتا ہے۔',
        'step2_title' => 'QR اسٹیکر پرنٹ کریں',
        'step2_desc' => 'آرڈرز کے لیے چھوٹے QR اسٹیکرز اور ورک اسٹیشنز کے لیے بڑے پرنٹ کریں۔ تھرمل پرنٹرز کے ساتھ مطابقت رکھتا ہے۔',
        'step3_title' => 'اسکین اور ٹریک',
        'step3_desc' => 'ٹیکنیشن ورک اسٹیشن QR اسکین کرتے ہیں پھر آرڈر QR۔ ایک ٹیپ سے کام شروع، وقفہ یا مکمل کریں۔',
        'step4_title' => 'مانیٹر اور ڈیلیور',
        'step4_desc' => 'مینجمنٹ ریئل ٹائم میں پیش رفت ٹریک کرتی ہے۔ AI تکمیل کی پیش گوئی کرتا ہے۔ ڈاکٹرز کو پورٹل کے ذریعے اپڈیٹس ملتے ہیں۔',
        'stat_roles' => 'صارف کے کردار',
        'stat_pages' => 'ڈیش بورڈ صفحات',
        'stat_tests' => 'خودکار ٹیسٹس',
        'stat_languages' => 'زبانیں',
        'footer' => 'DentalTrack — ڈینٹل لیبز کے لیے QR پر مبنی پروڈکشن ٹریکنگ سسٹم',
    ],
];
