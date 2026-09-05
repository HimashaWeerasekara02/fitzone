# FitZone - Gym & Fitness Center Management System

A full-stack gym management platform that combines a public-facing fitness website with secure internal portals for staff and members. Built with a monolithic architecture using PHP and MySQL.

## 🏋️ Key Features
* **Member Portal:** User authentication, personalized dashboards, and membership tier registration.
* **Admin Dashboard:** Centralized control for managing users, approving classes, and overseeing trainers.
* **Staff Portal:** Dedicated interfaces for gym employees to manage their schedules and track class attendance.
* **Public Website:** Features class listings, a trainer directory, contact forms, and a built-in fitness blog.

## 🛠️ Tech Stack
* **Frontend:** HTML5, CSS3, Bootstrap 5, JavaScript
* **Backend:** PHP (Vanilla)
* **Database:** MySQL
* **Architecture:** Monolithic

## ⚙️ Running Locally

1. **Prerequisites**
   You need a local server environment capable of running PHP and MySQL (e.g., XAMPP, MAMP, or WAMP).

2. **Clone the repository**
   ```bash
   git clone https://github.com/HimashaWeerasekara02/fitzone.git
   ```

3. **Move to Server Directory**
   Move the cloned folder into your local server's web directory:
   * **XAMPP:** `htdocs/`
   * **MAMP:** `htdocs/`
   * **WAMP:** `www/`

4. **Database Setup**
   * Open phpMyAdmin (`http://localhost/phpmyadmin`).
   * Create a new database named `fitzone_db`.
   * Import the `fitzone_db.sql` file provided in the root directory.

5. **Run the Application**
   Open your browser and navigate to `http://localhost/fitzone/index.php`.
