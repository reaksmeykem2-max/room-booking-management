# RoomBook - Smart Room Booking System

A self-service room booking system built with the **TALL Stack** (Tailwind CSS, Alpine.js, Laravel, Livewire).

Users can book rooms themselves — the system automatically checks availability, prevents conflicts, and handles approvals. Admins only need to manage the initial setup.

## Features

### For Users
- **Book a Room** — 3-step wizard: Select room → Pick available time → Confirm
- **Real-time Availability** — See which slots are free/busy instantly
- **Auto-Approve** — No waiting for admin if room allows auto-booking
- **Smart Suggestions** — If your preferred time is busy, get alternative slots
- **My Bookings** — Track all your bookings, cancel if needed

### For Admins
- **Dashboard** — Stats overview, pending approvals, today's schedule
- **Manage Rooms** — Add/edit rooms, set capacity, approval mode
- **Manage Bookings** — Approve/reject with reasons, filter by status/room/date
- **Working Days** — Configure Mon-Fri schedule, set office hours
- **Holidays** — Add public holidays (recurring yearly supported)

### Smart Logic
- Prevents double-booking (conflict detection)
- Working days only (weekends blocked)
- Holidays blocked automatically
- Within office hours only
- Capacity validation

## Tech Stack

| Technology | Purpose |
|-----------|---------|
| **Laravel 11** | Backend framework |
| **Livewire 3** | Reactive UI components |
| **Tailwind CSS** | Styling |
| **Alpine.js** | Client-side interactions |
| **MySQL/SQLite** | Database |
| **maatwebsite/excel** | Excel import/export |

## Installation

### Requirements
- PHP >= 8.2
- Composer
- MySQL or SQLite
- Node.js (for Tailwind build, optional - CDN used in dev)

### Steps

```bash
# 1. Clone the project
cd room-booking-system

# 2. Install PHP dependencies
composer install

# 3. Setup environment
cp .env.example .env
php artisan key:generate

# 4. Configure database in .env
# For SQLite (quick start):
DB_CONNECTION=sqlite
# Then create the file:
touch database/database.sqlite

# For MySQL:
DB_CONNECTION=mysql
DB_DATABASE=room_booking
DB_USERNAME=root
DB_PASSWORD=

# 5. Run migrations & seed data
php artisan migrate
php artisan db:seed

# 6. Start the server
php artisan serve
```

### Access the app
- **URL:** http://localhost:8000
- **Admin:** admin@example.com / password
- **User:** user@example.com / password

## Project Structure

```
app/
├── Models/
│   ├── User.php             (with role: admin/user)
│   ├── Room.php             (with availability checking)
│   ├── Booking.php          (with status management)
│   ├── WorkingSchedule.php  (day configuration)
│   └── Holiday.php          (holiday management)
├── Livewire/
│   ├── User/
│   │   ├── BookRoom.php     (3-step booking wizard)
│   │   ├── MyBookings.php   (user's booking list)
│   │   └── AvailabilityBoard.php (daily overview)
│   └── Admin/
│       ├── Dashboard.php    (stats & quick actions)
│       ├── ManageBookings.php (approve/reject)
│       ├── ManageRooms.php  (CRUD rooms)
│       ├── ManageHolidays.php (CRUD holidays)
│       └── WorkingDays.php  (schedule config)
├── Services/
│   └── BookingService.php   (core booking logic)
├── Imports/
│   └── BookingsImport.php   (Excel import)
└── Http/Middleware/
    └── AdminMiddleware.php

database/
├── migrations/              (5 tables)
└── seeders/                 (sample data)

resources/views/
├── layouts/
│   ├── app.blade.php        (user layout)
│   └── admin.blade.php      (admin sidebar layout)
├── livewire/user/           (user views)
├── livewire/admin/          (admin views)
└── auth/                    (login/register)
```

## Import from Excel

You can import existing bookings from Excel. The import expects columns like:
- `room` or `room_name`
- `date` or `booking_date`
- `start_time` or `from`
- `end_time` or `to`
- `booked_by` or `user`
- `purpose` or `subject`

## Configuration

### Approval Modes (per room)
- **Auto-approve:** Bookings are instantly confirmed if the slot is free
- **Require approval:** Admin must approve/reject

### Working Schedule
- Default: Monday-Friday, 8:00 AM - 5:00 PM
- Configurable per day via Admin > Working Days

### Holidays
- Block specific dates from booking
- Support recurring holidays (same date every year)
