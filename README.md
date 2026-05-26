# DentalTrack — QR-Based Production Tracking System for Dental Labs

A real-time production tracking system for dental laboratories. Workers scan QR codes at workstations to log each production step. Management gets full visibility into order status, employee performance, and AI-powered completion predictions.

## Tech Stack

- **Backend:** Laravel 11 + PHP 8.3
- **Frontend:** Livewire 3 + Filament 3 (admin panel)
- **Database:** SQLite (dev) / MySQL / PostgreSQL
- **Queue & Cache:** Redis
- **Real-time:** Laravel Reverb (WebSockets)
- **QR Codes:** simplesoftwareio/simple-qrcode
- **PDF Stickers:** barryvdh/laravel-dompdf
- **Roles & Permissions:** spatie/laravel-permission
- **Audit Trail:** spatie/laravel-activitylog
- **Media:** spatie/laravel-medialibrary

## Features

### Phase 0 — Foundation
- Multi-company architecture with `company_id` scoping
- Role-based access: Super Admin, Company Admin, Lab Manager, Technician
- PIN-based quick login for technicians on shop floor
- Comprehensive database seeder (2 companies, 14 workstations, 50 orders, 400+ scan events)

### Phase 1 — QR Code Infrastructure
- Auto-generated QR codes for orders (`ORD-{ulid}`) and workstations (`WS-{ulid}`)
- PDF sticker generation (small 25x15mm for orders, large 50x50mm for workstations)
- Batch sticker printing (multi-sticker A4 PDF sheets)
- QR regeneration and management via Filament admin panel

### Phase 2 — Scanning & Core Tracking
- Mobile-first QR scanner using `html5-qrcode` (works on any smartphone browser)
- PWA support with offline queue capability
- 4-step scan flow: Scan workstation → Scan order → Confirm action → Scan next station
- Business rules: prevent double-scan, auto-pause on idle, audit trail
- Event types: Start, Complete, Pause, Transfer to Waiting Area

### Phase 3 — Real-Time Dashboard
- Live Order Board with auto-refresh (5-second polling + WebSocket broadcasting)
- In-progress, pending, and overdue order sections
- Priority badges (Urgent/High/Normal/Low) with color coding
- Progress bars per order
- Search and priority filtering

### Phase 5a — Simple Predictions (PHP)
- Weighted historical average completion predictions
- `v1-weighted-avg` model using completed order data
- Predicted completion timestamps on orders

## Quick Start

```bash
# Clone
git clone https://github.com/mrshahbazdev/DentalTrack.git
cd DentalTrack

# Install dependencies
composer install
npm install && npm run build

# Setup environment
cp .env.example .env
php artisan key:generate

# Run migrations & seed
php artisan migrate
php artisan db:seed

# Start server
php artisan serve
```

**Admin Login:** `admin@dentaltrack.com` / `password`

**Access:** `http://localhost:8000/admin`

## Database Architecture

```
companies → labs → workstations
    ↓         ↓
  users    product_types → process_templates
    ↓
  orders → order_steps → scan_events
    ↓
  predictions
  qr_print_jobs
```

## Filament Admin Resources

| Resource | Path | Features |
|----------|------|----------|
| Companies | `/admin/companies` | CRUD, lab/user/order counts |
| Labs | `/admin/labs` | CRUD with company filter |
| Users | `/admin/users` | CRUD, role badges, PIN support |
| Product Types | `/admin/product-types` | CRUD with repeater for process steps |
| Workstations | `/admin/workstations` | CRUD, QR regenerate, sticker download |
| Orders | `/admin/orders` | CRUD, view with timeline, sticker print |
| Live Board | `/admin/live-order-board` | Real-time order tracking dashboard |

## Scanner (PWA)

Access the mobile scanner at `/scan` (requires authentication).

The scanner supports:
- Workstation QR scanning
- Order QR scanning
- Start/Pause/Complete actions
- Transfer to waiting areas
- Optional notes per scan

## Code Quality

```bash
# Format code
./vendor/bin/pint

# Static analysis (level 5)
./vendor/bin/phpstan analyse
```

## Seeded Data

The seeder creates:
- 2 companies: PrecisionDent Labs, SmileCraft Dental
- 2 labs with 7 workstations each (5 stations + 2 waiting areas)
- 4 product types per company (Full Denture, Crown PFM, Bridge, Partial Denture)
- Process templates with realistic step times
- 13 users across roles
- 50 orders with varied statuses and realistic scan histories

## License

MIT
