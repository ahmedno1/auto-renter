possible convenience using search filters.
# Auto Renter

## Summary
Auto Renter is a Laravel + Livewire web application built to streamline car rental operations for shop owners while giving customers a fast, filter-driven way to find and book vehicles. The platform supports owner-managed vehicle listings, customer booking requests, and date-aware availability checks that prevent overlapping reservations. It was originally created as a graduation project for the Islamic University of Gaza and has been refined here for a portfolio-ready presentation.

## Project Overview
Auto Renter provides two primary experiences:
- **Owners** can publish and manage their fleets, control availability status, and review booking requests.
- **Customers** can search by model, brand, or date availability, filter by price range, and submit booking requests with clear pricing feedback.

Reservations are validated against existing bookings, and owners can approve or reject incoming requests to control their schedules.

## Key Features
- Role-based access for owners and customers.
- Fleet management: create, edit, delete, and toggle availability status for cars.
- Advanced search: model/brand search, availability filtering, and price range filtering.
- Booking workflow with date validation, conflict detection, and total price calculation.
- Owner booking dashboard with approval and rejection actions.
- Livewire-driven UI updates for a reactive experience.

## Tech Stack
- **Backend:** PHP 8.2, Laravel 12
- **Frontend:** Laravel Livewire (Flux/Volt), Tailwind CSS, Vite
- **Database:** Laravel Eloquent ORM (SQLite/MySQL compatible)

## Local Development
1. Install dependencies:
   ```bash
   composer install
   npm install
   ```
2. Configure environment:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
3. Run migrations:
   ```bash
   php artisan migrate
   ```
4. Start the app:
   ```bash
   composer run dev
   ```

## Folder Highlights
- `app/Livewire`: Livewire components for search, bookings, and car management.
- `app/Models`: Eloquent models for cars and reservations.
- `routes/web.php`: Route definitions and role-based access control.
- `resources/views`: UI templates and layouts.

## Portfolio Notes
This project demonstrates full-stack Laravel development, including authentication, role-based workflows, dynamic searching, and reservation management. It also highlights experience with modern Laravel tooling (Livewire, Flux, Volt) and a production-friendly frontend toolchain.
