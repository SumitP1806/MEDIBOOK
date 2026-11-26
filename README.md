# Online Doctor Appointment System

A PHP + MySQL web application for managing patients, doctors and appointments.

## Requirements
- XAMPP (Apache + MySQL)
- PHP 8.0+ recommended
- MySQL / MariaDB
- Browser

## Setup
1. Extract `online_doctor_appointment_system.zip`.
2. Copy the `online_doctor_appointment_system` folder into `C:\xampp\htdocs\`.
3. Start **Apache** and **MySQL** from XAMPP Control Panel.
4. Open `http://localhost/phpmyadmin/`.
5. Import `database/doctor_appointment.sql`.
6. Check `config/database.php` (default XAMPP credentials: user `root`, blank password).
7. Open `http://localhost/online_doctor_appointment_system/`.

## Demo accounts
The SQL file creates:
- Admin: `admin@example.com` / `password`
- Doctor: `doctor@example.com` / `password`
- Patient: `patient@example.com` / `password`

Change these passwords before using outside a local demo environment.

## Included features
- Role-based login (admin, doctor, patient)
- Patient registration
- Doctor directory and specialization search
- Patient appointment booking
- Doctor approval / rejection / completion
- Patient appointment cancellation (pending or approved)
- Admin dashboard and doctor management
- Appointment status tracking
- Password hashing, prepared SQL statements, CSRF tokens and session checks

## Project structure
- `config/` database and app configuration
- `includes/` shared layout and security helpers
- `admin/` admin dashboard and doctor management
- `doctor/` doctor dashboard and appointment actions
- `patient/` patient dashboard and booking
- `database/` SQL schema and demo data
- `assets/` CSS and JavaScript

This is an educational college project, not a certified clinical system. Do not enter real patient medical data or deploy publicly without security, privacy, hosting and legal review.
