# DentalTrack — QR-Based Production Tracking System for Dental Labs

A real-time production tracking system for dental laboratories. Workers scan QR codes at workstations to log each production step. Management gets full visibility into order status, employee performance, and AI-powered completion predictions.

---

## Tech Stack

| Layer | Technology |
|-------|-----------|
| Backend | Laravel 11 + PHP 8.3 |
| Admin Panel | Filament 3 |
| Frontend | Livewire 3 + Tailwind CSS |
| Database | SQLite (dev) / MySQL / PostgreSQL |
| Queue & Cache | Redis (via Laravel Horizon) |
| Real-time | Laravel Reverb (WebSockets) |
| QR Codes | simplesoftwareio/simple-qrcode |
| PDF Stickers | barryvdh/laravel-dompdf |
| Exports | maatwebsite/excel |
| Roles & Permissions | spatie/laravel-permission |
| Audit Trail | spatie/laravel-activitylog |
| Media | spatie/laravel-medialibrary |
| Static Analysis | PHPStan (level 5) via Larastan |
| Code Style | Laravel Pint |

---

## Features

### Phase 0 — Foundation
- Multi-company architecture with `company_id` scoping
- Role-based access: Super Admin, Company Admin, Lab Manager, Technician
- PIN-based quick login for technicians on shop floor
- Comprehensive database seeder (2 companies, 14 workstations, 50 orders, 420+ scan events)

### Phase 1 — QR Code Infrastructure
- Auto-generated QR codes for orders (`ORD-{ulid}`) and workstations (`WS-{ulid}`, `WA-{ulid}`)
- PDF sticker generation (small 25x15mm for orders, large 50x50mm for workstations)
- Batch sticker printing (multi-sticker A4 PDF sheets)
- QR regeneration and management via Filament admin panel

### Phase 2 — Scanning & Core Tracking
- Mobile-first QR scanner using `html5-qrcode` (works on any smartphone browser)
- PWA support with offline queue capability
- 4-step scan flow: Scan workstation → Scan order → Confirm action → Scan next station
- Business rules: prevent double-scan, auto-pause on idle timeout, full audit trail
- Event types: Start, Complete, Pause, Transfer to Waiting Area
- ScanService with duration calculation and order completion detection

### Phase 3 — Real-Time Dashboard
- Live Order Board with 5-second polling + WebSocket broadcasting via Reverb
- In-progress, pending, and overdue order sections
- Priority badges (Urgent/High/Normal/Low) with color coding
- Progress bars per order with search and filtering

### Phase 4 — Analytics & Reporting
- **Employee Performance Dashboard**: avg time per step, orders/day, utilization %
- **Production Analytics**: bottleneck analysis, daily throughput chart, product type breakdown
- **Company Comparison**: side-by-side KPIs across companies (Super Admin)
- **Reports & Export**: Excel/CSV export for orders and scan events with date range filters

### Phase 5 — AI-Powered Predictions
- Weighted historical average prediction model (`v2-weighted-avg`)
- Technician speed factor (0.5x–2.0x multiplier) + queue depth penalty
- Confidence intervals for predictions
- **Predictions Dashboard**: accuracy stats, accuracy trend, smart suggestions (bottleneck detection, fast technician identification, overloaded station alerts)
- Live Order Board ETA column
- OrderObserver: auto-generates predictions on creation, auto-updates accuracy on completion
- Artisan command: `php artisan predictions:generate --update-accuracy`

### Phase 6 — Advanced Features
- **Customer Portal** (`/track`): public page for doctors/clinics to track orders via 8-character tracking code — no login required
- **Rework & Quality Control**: flag failed QC steps, route back for rework, track root cause (Material Defect, Technique Error, Equipment Issue, Design Error, Other)
- **Quality Dashboard**: rework rate, cause breakdown, technician quality metrics
- **Multi-Language Support**: English + Urdu with RTL support, language switcher, session persistence

### Phase 7 — Production Hardening
- **Performance**: 12 composite indexes on scan_events, orders, predictions, rework_events
- **Caching**: DashboardCacheService with 60s TTL, cache invalidation and warm-up
- **Security**: Rate limiting (scan: 30/min per user, tracking: 10/min per IP)
- **Health Check**: `/health` endpoint with database and cache connectivity checks
- **Scheduled Tasks**: hourly prediction generation, 5-minute cache warm-up
- **Tests**: 19 feature tests (scan flow, customer portal, predictions, health check)

---

## Setup Guide

### Prerequisites

- PHP 8.2+ (8.3 recommended)
- Composer 2.x
- Node.js 18+ & npm
- SQLite (default for development) or MySQL 8 / PostgreSQL

### 1. Clone the Repository

```bash
git clone https://github.com/mrshahbazdev/DentalTrack.git
cd DentalTrack
```

### 2. Install Dependencies

```bash
composer install
npm install
```

### 3. Environment Setup

```bash
cp .env.example .env
php artisan key:generate
```

The default `.env` uses SQLite. For MySQL, update these values in `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=dental_track
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 4. Database Setup

```bash
# Create SQLite database (if using SQLite)
touch database/database.sqlite

# Run migrations
php artisan migrate

# Seed with sample data
php artisan db:seed
```

### 5. Build Frontend Assets

```bash
npm run build
```

For development with hot-reload:

```bash
npm run dev
```

### 6. Start the Application

```bash
php artisan serve
```

The app will be available at `http://localhost:8000`

### 7. Access the Admin Panel

Open `http://localhost:8000/admin` and log in with:

| Email | Password | Role |
|-------|----------|------|
| `admin@dentaltrack.com` | `password` | Super Admin |
| `manager@precisiondent.com` | `password` | Company Admin |

### 8. Optional: WebSocket Server (for real-time updates)

```bash
php artisan reverb:start
```

### 9. Optional: Queue Worker (for background jobs)

```bash
php artisan queue:work
```

### 10. Optional: Run Scheduled Tasks

```bash
# View scheduled tasks
php artisan schedule:list

# Run the scheduler manually
php artisan schedule:run
```

---

## Usage Guide

### For Lab Technicians

1. Open `http://localhost:8000/scan` on your smartphone (or any browser)
2. Log in with your PIN or email/password
3. Scan the **workstation** QR code (the station where you'll work)
4. Scan the **order** QR code (the dental piece you're working on)
5. Choose an action: **Start Work**, **Pause**, or **Complete & Pass to Next**
6. If completing, scan the next workstation or waiting area QR code

### For Doctors / Clinics (Customer Portal)

1. Visit `http://localhost:8000/track`
2. Enter the 8-character tracking code provided with the order
3. View order status, progress, and estimated completion
4. Switch language with the EN/اردو toggle

### For Lab Managers / Admins

Access the Filament admin panel at `/admin`:

| Page | Path | Description |
|------|------|-------------|
| Live Order Board | `/admin/live-order-board` | Real-time order tracking |
| Employee Performance | `/admin/employee-performance` | Technician metrics |
| Production Analytics | `/admin/production-analytics` | Bottleneck analysis, throughput |
| Predictions Dashboard | `/admin/predictions-dashboard` | ETA accuracy, smart suggestions |
| Quality Dashboard | `/admin/quality-control` | Rework rate, root cause analysis |
| Company Comparison | `/admin/company-comparison` | Side-by-side KPIs |
| Reports & Export | `/admin/reports` | Excel/CSV exports |

### Filament Resources (CRUD)

| Resource | Path |
|----------|------|
| Companies | `/admin/companies` |
| Labs | `/admin/labs` |
| Users | `/admin/users` |
| Product Types | `/admin/product-types` |
| Workstations | `/admin/workstations` |
| Orders | `/admin/orders` |
| Rework Events | `/admin/rework-events` |

---

## Database Architecture

```
companies
├── labs
│   └── workstations (stations + waiting areas)
├── users (Super Admin, Company Admin, Lab Manager, Technician)
├── product_types
│   └── process_templates (step definitions with expected_minutes)
└── orders
    ├── order_steps (per-order step instances)
    │   └── scan_events (start, complete, pause, transfer)
    ├── predictions (AI completion estimates)
    └── rework_events (QC failures with root cause)

Supporting:
├── qr_print_jobs (sticker print tracking)
├── activity_log (audit trail via Spatie)
└── roles & permissions (RBAC via Spatie)
```

---

## Code Quality

```bash
# Format code
./vendor/bin/pint

# Static analysis (level 5)
./vendor/bin/phpstan analyse --memory-limit=512M

# Run tests
./vendor/bin/phpunit --testdox
```

All code passes PHPStan level 5 and Laravel Pint formatting.

---

## Seeded Data

The seeder creates:

| Data | Count |
|------|-------|
| Companies | 2 (PrecisionDent Labs, SmileCraft Dental) |
| Labs | 2 (one per company) |
| Workstations | 14 (5 stations + 2 waiting areas per lab) |
| Product Types | 4 per company (Full Denture, Crown PFM, Bridge, Partial Denture) |
| Process Templates | 4-8 steps per product type with realistic durations |
| Users | 13 across all roles |
| Orders | 50 with varied statuses and realistic scan histories |
| Scan Events | 420+ with duration data |

---

## Artisan Commands

```bash
# Generate predictions for all active orders
php artisan predictions:generate

# Generate predictions and update accuracy for completed orders
php artisan predictions:generate --update-accuracy

# View scheduled tasks
php artisan schedule:list
```

---

## Health Check

```bash
curl http://localhost:8000/health
```

Returns:
```json
{
  "status": "ok",
  "timestamp": "2024-01-01T00:00:00+00:00",
  "database": true,
  "cache": true
}
```

---

## Production Deployment

### Environment Variables

Set the following in your production `.env`:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=your-db-host
DB_DATABASE=dental_track
DB_USERNAME=your-db-user
DB_PASSWORD=your-db-password

CACHE_STORE=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis

REVERB_APP_ID=your-reverb-id
REVERB_APP_KEY=your-reverb-key
REVERB_APP_SECRET=your-reverb-secret
```

### Deployment Steps

```bash
composer install --no-dev --optimize-autoloader
npm ci && npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan migrate --force
php artisan queue:restart
```

### Scheduled Tasks (Crontab)

```bash
* * * * * cd /path/to/dental-track && php artisan schedule:run >> /dev/null 2>&1
```

### Queue Workers (Supervisor)

```ini
[program:dental-track-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/dental-track/artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
numprocs=2
```

---

## License

MIT
