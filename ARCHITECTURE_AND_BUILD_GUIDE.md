# Event Booking API — Architecture & Rebuild Summary

## 1) What this document is

This is a mid-level technical summary of the project for portfolio/recruiter review:

- Clear architecture and Laravel patterns used
- Why each pattern was chosen
- Step-by-step guide to recreate the project
- Key implementation notes (without dumping every file line-by-line)

---

## 2) High-Level Project Structure

The application is organized as a layered API:

1. Routes (`routes/api.php`)
   - Defines REST endpoints for events, attendees, and bookings.
2. Controllers (`app/Http/Controllers`)
   - Thin orchestration layer: receives request, delegates work, returns JSON.
3. Form Requests (`app/Http/Requests`)
   - Handles request validation before controller logic executes.
4. Services (`app/Services`)
   - Holds business rules (capacity checks, duplicate booking rules, etc.).
5. Repositories (`app/Repositories`)
   - Encapsulates Eloquent data access operations.
6. Contracts (`app/Repositories/Contracts`)
   - Interfaces used to enforce dependency inversion.
7. Models (`app/Models`)
   - Domain entities and relationships.
8. Migrations (`database/migrations`)
   - Database schema and constraints.
9. Central exception mapping (`bootstrap/app.php`)
   - Converts exceptions into consistent API JSON responses.
10. Tests (`tests/Feature`, `tests/Unit`)
    - Verifies endpoint behavior and core business logic.

---

## 3) Request Flow (How a typical API call is handled)

Example flow for `POST /api/bookings`:

1. Route points to `BookingController@store`
2. `StoreBookingRequest` validates payload (`event_id`, `attendee_id`)
3. Controller calls `BookingService::book(...)`
4. Service applies business rules:
   - event capacity not exceeded
   - attendee not already booked for event
5. Service uses repository contract to persist booking
6. Controller returns `201` JSON
7. If failures occur, centralized exception mapping returns consistent `400/404/500`

---

## 4) Laravel Concepts & Patterns Used (and Why)

### a) API Resource Routing
- `Route::apiResource(...)` for standard REST actions.
- Reduces boilerplate and keeps route registration consistent.

### b) Form Request Validation
- Validation is separated from controllers.
- Keeps controllers clean and ensures predictable 422 responses.

### c) Thin Controllers
- Controllers avoid business rules.
- They only orchestrate request -> service/repository -> response.

### d) Service Layer Pattern
- Business rules are centralized in services.
- Improves reuse and unit-testability.

### e) Repository Pattern
- Data access is isolated from business logic.
- Makes implementation changes easier and cleaner.

### f) Contracts + Dependency Inversion (DIP)
- Services/controllers depend on interfaces, not concrete classes.
- Improves testability (mocks/fakes) and maintainability.

### g) IoC Container Bindings
- `AppServiceProvider` binds interfaces to repository implementations.
- Enables auto-resolution of dependencies.

### h) Eloquent Relationships
- `Event` ↔ `Booking` ↔ `Attendee` relationships model the domain directly.
- Improves readability and query expressiveness.

### i) Eager Loading + Pagination
- Booking lists eager-load related models to avoid N+1 issues.
- List endpoints are paginated for scalability.

### j) Centralized Exception Handling
- Error mapping in `bootstrap/app.php` avoids repeated try/catch blocks.
- Response behavior is consistent across endpoints.

### k) Database Constraints as Integrity Safeguards
- Unique attendee email
- Composite unique booking (`event_id`, `attendee_id`)
- Foreign keys with cascade delete

Application rules + DB constraints provide defense-in-depth.

---

## 5) Core Domain Rules Implemented

### Events
- Create/update/delete/list events
- Optional filtering and pagination
- Capacity must be valid

### Attendees
- Create/update/delete/list attendees
- Email uniqueness enforced at validation + DB level

### Bookings
- Create/delete/list/show bookings
- Prevent overbooking
- Prevent duplicate attendee booking for same event

---

## 6) Error Handling Strategy

Centralized in `bootstrap/app.php`:

- `422` -> validation errors (Form Requests)
- `400` -> business rule violations (`DomainException`)
- `404` -> missing resource or route
- `500` -> unexpected API exceptions

This keeps responses predictable for API consumers.

---

## 7) Testing Strategy

Current tests cover key behavior:

- Feature tests
  - Booking endpoint behavior (success, overbooking prevention, duplicate prevention)
- Unit tests
  - Booking service business rule behavior

Why this matters:
- Demonstrates confidence in both HTTP behavior and domain logic.

---

## 8) Step-by-Step Guide to Recreate This Project

### Step 0 — Prerequisites

- PHP 8.2+
- Composer
- Laravel Herd (recommended)
- SQLite
- Optional: Postman / TablePlus

### Step 1 — Create a new Laravel 12 app

```bash
cd ~/Herd
composer create-project laravel/laravel event-booking-api-improved "^12.0"
cd event-booking-api-improved
```

### Step 2 — Configure environment

```bash
cp .env.example .env
php artisan key:generate
```

Create SQLite database file:

```bash
touch database/database.sqlite
# Windows: type nul > database\database.sqlite
```

Set `.env` values:

```env
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

### Step 3 — Create models + migrations + factories

```bash
php artisan make:model Event -m -f
php artisan make:model Attendee -m -f
php artisan make:model Booking -m -f
```

### Step 4 — Implement database schema

#### Events table
- title (string)
- description (nullable string)
- date (date)
- country (string)
- capacity (integer)

#### Attendees table
- name (string)
- email (unique string)

#### Bookings table
- event_id (foreign key, cascade delete)
- attendee_id (foreign key, cascade delete)
- unique composite index: `[event_id, attendee_id]`

### Step 5 — Add model relationships

- Event: `bookings()`, `attendees()`
- Attendee: `bookings()`, `events()`
- Booking: `event()`, `attendee()`

### Step 6 — Create repository contracts

Create interfaces in `app/Repositories/Contracts`:

- `EventRepositoryInterface`
- `AttendeeRepositoryInterface`
- `BookingRepositoryInterface`

Include signatures for CRUD and booking existence checks.

### Step 7 — Implement repositories

Implement concrete classes in `app/Repositories` using Eloquent.

Key behaviors:

- Event listing with optional `country` filter + pagination
- Booking listing with eager loading + optional `attendee_id` filter + pagination
- `exists(eventId, attendeeId)` for duplicate booking check

### Step 8 — Bind interfaces in the container

In `AppServiceProvider::register()` bind each contract to its repository implementation.

### Step 9 — Build service layer

Create:

- `EventService`
- `AttendeeService`
- `BookingService`

Implement business rules:

- Capacity validation
- Duplicate attendee email rule
- Overbooking and duplicate booking prevention

Throw `DomainException` for business-rule failures.

### Step 10 — Build Form Requests

Create:

- `StoreEventRequest`
- `UpdateEventRequest`
- `StoreAttendeeRequest`
- `UpdateAttendeeRequest`
- `StoreBookingRequest`

Use these requests directly in controller actions.

### Step 11 — Build thin controllers

Create controllers for events, attendees, and bookings.

Controller responsibilities:

- accept validated input
- call service/repository
- return JSON response with appropriate status code

### Step 12 — Register API routes

In `routes/api.php`:

```php
Route::apiResource('events', EventController::class);
Route::apiResource('attendees', AttendeeController::class);
Route::apiResource('bookings', BookingController::class);
```

### Step 13 — Configure centralized API exception mapping

In `bootstrap/app.php`, map exceptions:

- `ModelNotFoundException` -> 404 JSON
- `NotFoundHttpException` -> 404 JSON
- `DomainException` -> 400 JSON
- fallback for API routes -> 500 JSON

### Step 14 — Add factories/seeders

Generate realistic test data for events, attendees, and bookings.

### Step 15 — Add tests

- Feature: booking endpoint behavior
- Unit: booking service behavior

### Step 16 — Run and verify

```bash
php artisan migrate:fresh --seed
php artisan test
```

Local API base URL (Herd):

- `http://event-booking-api-improved.test/api`

---

## 9) Quick Verification Checklist

- [ ] `/api/events` returns paginated data
- [ ] Creating attendee enforces unique email
- [ ] Booking creation blocks duplicates
- [ ] Booking creation blocks over-capacity events
- [ ] Missing resource returns 404 JSON
- [ ] Business-rule failures return 400 JSON
- [ ] Test suite passes

---

## 10) Portfolio Notes (Why this is strong)

This project demonstrates:

- Solid Laravel API fundamentals
- Practical SOLID application (especially SRP + DIP)
- Clean layered architecture
- Good API error consistency and validation discipline
- Test coverage for important business rules

It is a credible mid-level backend portfolio project and a good base for future additions (auth, policies, concurrency locking, CI).
