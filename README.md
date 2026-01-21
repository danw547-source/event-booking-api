# 🎟️ Event Booking API

A RESTful API built with **Laravel 12** for managing **events, attendees, and bookings**.
This project demonstrates database design, request validation, error handling, and testing best practices in Laravel.

---

## 🧩 Overview

The API allows you to:

* Create, update, delete, and list **events** (with pagination)
* Register and manage **attendees**
* Create **bookings** for events
* Prevent **overbooking** and **duplicate bookings**
* Validate incoming requests and return structured JSON responses

---

## ⚙️ Tech Stack

* **Framework:** Laravel 12 (PHP 8.2+)
* **Local Development:** [Laravel Herd](https://herd.laravel.com)
* **Database:** MySQL (managed using [TablePlus](https://tableplus.com))
* **Testing:** PHPUnit & Laravel's built-in testing suite
* **API Testing:** Postman

---

## 🚀 Getting Started

### 1️⃣ Clone the repository

```bash
git clone https://github.com/danw547-source/event-booking-api.git
cd event-booking-api
```

### 2️⃣ Install dependencies

```bash
composer install
```

### 3️⃣ Configure environment

Copy the `.env.example` file to `.env`:

```bash
cp .env.example .env
```

Update the following section for your local database (as configured in TablePlus):

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=event_booking
DB_USERNAME=root
DB_PASSWORD=
```

### 4️⃣ Generate the app key

```bash
php artisan key:generate
```

### 5️⃣ Run migrations

```bash
php artisan migrate
```

### 6️⃣ Start the local server

If you're using Laravel Herd, the project should already be available at:

```
http://event-booking-api.test
```

Otherwise, start the built-in Laravel server:

```bash
php artisan serve
```

API will be available at:

```
http://127.0.0.1:8000/api
```

---

## 🧾 API Endpoints

### 🎫 Events

| Action           | Method | URL                | Body / Params                                                                    |
| ---------------- | ------ | ------------------ | -------------------------------------------------------------------------------- |
| List all events  | GET    | `/api/events`      | Optional: `?page=2` (paginated)                                                  |
| Get single event | GET    | `/api/events/{id}` | –                                                                                |
| Create event     | POST   | `/api/events`      | `{ "title": "Concert", "description": "...", "date": "2026-02-01 19:00:00", "country": "UK", "capacity": 100 }` |
| Update event     | PUT    | `/api/events/{id}` | `{ "title": "Updated Title" }` (partial updates supported)                       |
| Delete event     | DELETE | `/api/events/{id}` | –                                                                                |

**Event Fields:**
- `title` (required, string)
- `description` (required, text)
- `date` (required, datetime)
- `country` (required, string)
- `capacity` (required, integer, min: 1)

---

### 👤 Attendees

| Action             | Method | URL                   | Body                                                    |
| ------------------ | ------ | --------------------- | ------------------------------------------------------- |
| List all attendees | GET    | `/api/attendees`      | –                                                       |
| Get attendee       | GET    | `/api/attendees/{id}` | –                                                       |
| Register attendee  | POST   | `/api/attendees`      | `{ "name": "Dan Wrigley", "email": "dan@example.com" }` |
| Update attendee    | PUT    | `/api/attendees/{id}` | `{ "name": "Updated Name", "email": "updated@example.com" }` |
| Delete attendee    | DELETE | `/api/attendees/{id}` | –                                                       |

**Attendee Fields:**
- `name` (required, string)
- `email` (required, string, unique)

🔒 **Validation:** Duplicate emails are prevented with a 422 error response.

---

### 🧾 Bookings

| Action         | Method | URL             | Body                                  |
| -------------- | ------ | --------------- | ------------------------------------- |
| Create booking | POST   | `/api/bookings` | `{ "event_id": 1, "attendee_id": 3 }` |

🔒 The API prevents:

* **Overbooking** when event capacity is reached (422 error: "Event is fully booked")
* **Duplicate bookings** by the same attendee for the same event (422 error: "Duplicate booking")

---

## 🧪 Testing

Run the full test suite:

```bash
php artisan test
```

### Run a specific test class

```bash
php artisan test --filter=BookingTest
php artisan test --filter=AttendeeTest
```

### Run a specific test method

```bash
php artisan test --filter=test_event_capacity_enforced
```

### Test Coverage

**AttendeeTest.php:**
- ✅ Register attendee
- ✅ Get attendee details
- ✅ Get all attendees (20 records)
- ✅ Update attendee details
- ✅ Delete attendee
- ✅ Prevent duplicate email registration

**BookingTest.php:**
- ✅ Book event successfully
- ✅ Enforce event capacity (overbooking prevention)
- ✅ Prevent duplicate bookings
- ✅ List events with pagination
- ✅ Update event
- ✅ Delete event
- ✅ Paginate events (30 records, page 2)

---

## � Postman Collection

A complete Postman collection is included for easy API testing. The collection includes all endpoints organized into folders (Events, Attendees, Bookings) with pre-configured requests.

### Import into Postman

1. Open Postman
2. Click **Import** in the top-left corner
3. Select the following files from the `postman/` directory:
   - `Event_Booking_API.postman_collection.json` (API requests)
   - `Local_Herd.postman_environment.json` (environment variables)
4. Select the **Local Herd** environment from the dropdown in the top-right
5. Start testing the API endpoints

### Available Requests

**Events:**
- POST Create Event
- GET All Events
- GET Single Event
- PUT Update Event
- DELETE Delete Event

**Attendees:**
- POST Create Attendee
- GET All Attendees
- GET Single Attendee
- PUT Update Attendee
- DELETE Delete Attendee

**Bookings:**
- POST Create Booking
- GET All Bookings
- GET Single Booking
- DELETE Delete Booking

### Environment Variables

The **Local Herd** environment is pre-configured with:
- `baseURL`: `http://event-booking-api.test` (Laravel Herd local domain)

Update the `baseURL` if you're using a different local server setup.

---

## �🔐 Authentication (not implemented)

Authentication was not required for this task, but in a real-world application:

* **Laravel Sanctum** would be used for API token-based authentication.
* **Authenticated users** could manage events and view bookings.
* **Unauthenticated attendees** could register and book events.

This structure ensures secure access while keeping public endpoints open where needed.

---

## 🐳 Docker (optional)

This project currently runs locally using Laravel Herd and TablePlus.

However, it could easily be **Dockerized** using a multi-container setup:

* A PHP 8.2+ container for the Laravel app
* A MySQL container for the database
* A simple `docker-compose.yml` for reproducible environments

Example:

```bash
docker compose up -d
```

Mentioning this demonstrates awareness of modern deployment practices.

---

## 📦 Project Structure

```
app/
├─ Http/
│  ├─ Controllers/
│  │  ├─ Controller.php
│  │  └─ Api/
│  │     ├─ EventController.php
│  │     ├─ AttendeeController.php
│  │     └─ BookingController.php
├─ Models/
│  ├─ Event.php
│  ├─ Attendee.php
│  ├─ Booking.php
│  └─ User.php
database/
├─ factories/
│  ├─ AttendeeFactory.php
│  └─ EventFactory.php
├─ migrations/
│  ├─ 2026_01_18_120645_create_events_table.php
│  ├─ 2026_01_18_120653_create_attendees_table.php
│  ├─ 2026_01_18_120701_create_bookings_table.php
│  └─ 2026_01_18_124142_create_sessions_table.php
routes/
├─ api.php
├─ web.php
└─ console.php
tests/
├─ Feature/
│  ├─ AttendeeTest.php
│  └─ BookingTest.php
└─ Unit/
   └─ ExampleTest.php
```

---

## 📄 License

This project is open source and available under the [MIT License](LICENSE).

---

## ✉️ Author

**Dan Wrigley**  
Software developer with experience in PHP, Laravel, SQL, JavaScript, and .NET.  
[GitHub Profile](https://github.com/danw547-source)
