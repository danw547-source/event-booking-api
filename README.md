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
* **Local Development:** [Laravel Herd](https://herd.laravel.com) (required)
* **Database:** SQLite (managed using [TablePlus](https://tableplus.com)) (required)
* **Testing:** PHPUnit & Laravel's built-in testing suite
* **API Testing:** Postman

---

## 📋 Prerequisites

Before you begin, ensure you have the following installed:

### Required Tools

1. **[Laravel Herd](https://herd.laravel.com)** - Download and install for your OS
   - Provides PHP, Composer, and automatic `.test` domain management
   - No additional configuration needed

2. **[TablePlus](https://tableplus.com)** - Download and install for database management

### Setting up TablePlus

After completing the setup steps below, you'll connect TablePlus to your SQLite database:

1. **Open TablePlus** and click **Create a new connection**
2. Select **SQLite** as the connection type
3. Configure the connection:
   - **Name:** `Event Booking API` (or any name you prefer)
   - Click **Browse** and navigate to your project folder
   - Select the `database.sqlite` file in the `database/` directory
4. Click **Connect**
5. You should now see all your tables (events, attendees, bookings) after running migrations

---

## 🚀 Getting Started

### 1️⃣ Clone the repository

Clone the project into your **Herd folder** (usually `~/Herd` on macOS/Linux or `C:\Users\YourUsername\Herd` on Windows):

```bash
cd ~/Herd  # or C:\Users\YourUsername\Herd on Windows
git clone https://github.com/danw547-source/event-booking-api.git

# Rename the folder to remove the branch name
mv event-booking-api-main event-booking-api  # macOS/Linux
# or
ren event-booking-api-main event-booking-api  # Windows

cd event-booking-api
```

> **Note:** Placing the project in the Herd folder allows Laravel Herd to automatically serve it at `http://event-booking-api.test`

### 2️⃣ Open in your IDE

Open the project in your preferred IDE (VS Code, PHPStorm, etc.):

```bash
code .  # For VS Code
# or simply open the folder in your IDE
```

### 3️⃣ Install dependencies

Install Laravel and all project dependencies using Composer (included with Herd):

```bash
composer install
```

This will install Laravel 12 and all required packages defined in `composer.json`. The `artisan` file (Laravel's command-line tool) is included with Laravel in the project root.

### 4️⃣ Generate the app key

```bash
php artisan key:generate
```

### 5️⃣ Create the database file

Create an empty SQLite database file:

```bash
touch database/database.sqlite  # On macOS/Linux
# or
type nul > database\database.sqlite  # On Windows
```

### 6️⃣ Run migrations and seed the database

```bash
php artisan migrate --seed
```

This command does two things:
1. **Migrates** - Creates all database tables (events, attendees, bookings, sessions)
2. **Seeds** - Populates the database with sample data using the factories and seeders defined in `database/seeders/`

The `--seed` flag automatically runs the seeders after migrations complete, giving you test data to work with immediately.

### 7️⃣ Access the application

With Laravel Herd, your project is automatically available at:

```
http://event-booking-api.test
```

API endpoints are accessible at:

```
http://event-booking-api.test/api
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
3. Select **both** of the following files from the `app/Postman/` directory:
   - `Event_Booking_API.postman_collection.json` (API requests)
   - `Local_Herd.postman_environment.json` (environment variables)
4. **Important:** After importing, select the **Local Herd** environment from the dropdown in the top-right corner
5. Start testing the API endpoints

> **Note:** You must import both files and activate the environment for the `baseURL` variable to work correctly in your requests.

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
