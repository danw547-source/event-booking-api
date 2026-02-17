# 🎟️ Event Booking API (Improved)

A RESTful API built with Laravel 12 for managing events, attendees, and bookings.

This improved version keeps the same core API usage, but now applies cleaner architecture and stronger consistency rules:
- Service + Repository layering with repository contracts (Dependency Inversion)
- Centralized API exception mapping for consistent JSON error responses
- Better schema/validation alignment (nullable/unique/composite constraints)
- Typed method signatures across services and repositories for clearer contracts

---

## 🧩 Overview

The API allows you to:
- Create, update, delete, and list events (paginated)
- Register and manage attendees
- Create and manage bookings
- Prevent overbooking and duplicate bookings
- Validate incoming requests and return structured JSON responses

---

## ⚡ Quick Start (2 Minutes)

If your machine already has Laravel Herd installed:

```bash
composer install
cp .env.example .env
php artisan key:generate
type nul > database\database.sqlite   # Windows
php artisan migrate --seed
php artisan test
```

Then open:

```text
http://event-booking-api-improved.test/api
```

Use the full setup sections below if this is your first time configuring the project.

---

## ⚙️ Tech Stack

- Framework: Laravel 12 (PHP 8.2+)
- Local Development: Laravel Herd (required)
- Database: SQLite
- Testing: PHPUnit + Laravel testing tools
- API Testing: Postman (optional)

---

## 📋 Prerequisites

Before you begin, install:

### Required Tools

1. Laravel Herd
   - Provides PHP, Composer, and automatic .test domain routing
2. TablePlus (optional)
   - Used to inspect and manage the SQLite database

### TablePlus Setup

After project setup:
1. Open TablePlus → Create a new connection
2. Select SQLite
3. Choose the database/database.sqlite file
4. Connect and verify tables (events, attendees, bookings)

---

## 🚀 Getting Started

### 1) Clone the repository

Clone into your Herd directory so Herd auto-serves the project:

```bash
cd ~/Herd
# Windows example: cd C:\Users\YourUsername\Herd

git clone <your-repository-url>
cd event-booking-api-improved
```

If this repository is already on your machine, just open it and continue.

### 2) Open in your IDE

```bash
code .
```

### 3) Install dependencies

```bash
composer install
```

### 4) Create environment file (if needed)

```bash
cp .env.example .env
# Windows (cmd): copy .env.example .env
```

### 5) Generate app key

```bash
php artisan key:generate
```

### 6) Create SQLite database file

```bash
touch database/database.sqlite
# Windows: type nul > database\database.sqlite
```

### 7) Configure .env for SQLite

Set:

```env
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

### 8) Run migrations and seed data

```bash
php artisan migrate --seed
```

If you are upgrading from an older local schema, use:

```bash
php artisan migrate:fresh --seed
```

### 9) Access the app

With Herd, the app is available at:

```text
http://event-booking-api-improved.test
```

API base:

```text
http://event-booking-api-improved.test/api
```

---

## 🧾 API Endpoints

Routes are registered in `routes/api.php`.

### 🎫 Events

| Action           | Method | URL                | Body / Params |
|------------------|--------|--------------------|---------------|
| List events      | GET    | /api/events        | Optional: `?page=2`, `?country=UK` |
| Get single event | GET    | /api/events/{id}   | – |
| Create event     | POST   | /api/events        | `{ "title": "Concert", "description": "...", "date": "2026-02-20", "country": "UK", "capacity": 100 }` |
| Update event     | PUT/PATCH | /api/events/{id} | Partial updates supported |
| Delete event     | DELETE | /api/events/{id}   | – |

Event field rules:
- title: required, string, max 255
- description: nullable, string
- date: required, date
- country: required, string
- capacity: required, integer, min 1

### 👤 Attendees

| Action             | Method | URL                   | Body |
|--------------------|--------|-----------------------|------|
| List attendees     | GET    | /api/attendees        | – |
| Get attendee       | GET    | /api/attendees/{id}   | – |
| Register attendee  | POST   | /api/attendees        | `{ "name": "Dan Wrigley", "email": "dan@example.com" }` |
| Update attendee    | PUT/PATCH | /api/attendees/{id} | Partial updates supported |
| Delete attendee    | DELETE | /api/attendees/{id}   | – |

Attendee field rules:
- name: required on create, string
- email: required on create, valid email, unique

### 🧾 Bookings

| Action           | Method | URL                 | Body |
|------------------|--------|---------------------|------|
| List bookings    | GET    | /api/bookings       | Optional: `?attendee_id=1` |
| Get booking      | GET    | /api/bookings/{id}  | – |
| Create booking   | POST   | /api/bookings       | `{ "event_id": 1, "attendee_id": 3 }` |
| Delete booking   | DELETE | /api/bookings/{id}  | – |

Booking protections:
- Overbooking is blocked when event capacity is reached
- Duplicate attendee booking for the same event is blocked
- Composite unique constraint enforces this rule at DB level too

---

## ❗ Error Response Behavior (Improved)

The improved version centralizes API exception mapping in bootstrap configuration.

Typical responses:
- 422: validation failures (Form Requests)
- 400: business rule violations (for example, event full / duplicate booking)
- 404: resource or route not found (`Resource not found`)
- 500: unexpected server errors on API routes

This makes controller actions thinner and response behavior consistent.

---

## 🏗️ Architecture Notes (SOLID-Oriented)

The project now follows a clearer layering approach:

- Controllers: HTTP orchestration only
- Services: business rules
- Repositories: persistence logic
- Repository Contracts: abstractions consumed by services/controllers

Why this was applied:
- Dependency Inversion: high-level modules depend on interfaces, not concrete repositories
- Better testability: contracts are easier to mock/substitute
- Consistency: shared exception strategy instead of repetitive try/catch per controller

---

## 🧪 Testing

Run all tests:

```bash
php artisan test
```

Run a specific class:

```bash
php artisan test --filter=BookingTest
php artisan test --filter=BookingServiceTest
```

---

## 📬 Postman Collection

- Open the shared collection here: [Event Booking API Postman Collection](https://danw547-9969502.postman.co/workspace/event-booking-api~d34e23cb-5f3f-4594-904e-a8d658fb6140/collection/51383501-aaf9b77d-d0cc-4b37-96a7-74f2dc3dbca9?action=share&source=copy-link&creator=51383501)

How to use:
- Import the collection into Postman.
- Set your local base URL to `http://event-booking-api-improved.test/api`.
- Run `GET /events` first to confirm connectivity.
- Then run create/update/delete requests for events, attendees, and bookings.

---

## 📦 Project Structure (Current)

```text
app/
├─ Http/
│  ├─ Controllers/
│  │  ├─ EventController.php
│  │  ├─ AttendeeController.php
│  │  └─ BookingController.php
│  └─ Requests/
│     ├─ StoreEventRequest.php
│     ├─ UpdateEventRequest.php
│     ├─ StoreAttendeeRequest.php
│     ├─ UpdateAttendeeRequest.php
│     └─ StoreBookingRequest.php
├─ Models/
│  ├─ Event.php
│  ├─ Attendee.php
│  ├─ Booking.php
│  └─ User.php
├─ Repositories/
│  ├─ Contracts/
│  │  ├─ EventRepositoryInterface.php
│  │  ├─ AttendeeRepositoryInterface.php
│  │  └─ BookingRepositoryInterface.php
│  ├─ EventRepository.php
│  ├─ AttendeeRepository.php
│  └─ BookingRepository.php
└─ Services/
   ├─ EventService.php
   ├─ AttendeeService.php
   └─ BookingService.php
```

---

## 🔐 Authentication

Authentication is not implemented in this project version.

For production usage, Laravel Sanctum would be the natural next step for token-based API auth.

---

## ⚠️ Known Limitations

- This is an API-only project (no frontend UI).
- Authentication/authorization is intentionally not implemented.
- Booking creation currently relies on business-rule checks plus DB constraints; high-concurrency locking strategies are not yet implemented.

---

## 📄 License

Open source under the MIT License.
