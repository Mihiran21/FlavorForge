# FlavorForge - Digital Recipe Book

FlavorForge is a dynamic, modern web application designed for cooking enthusiasts to explore, filter, and share unique culinary recipes.

## Requirements
- XAMPP or WAMP server
- PHP >= 7.4
- MySQL Server

## Setup Instructions

1. **Clone/Download Repository:**
   Place the project folder `flavorforge` inside your server local root directory:
   - For XAMPP: `C:/xampp/htdocs/flavorforge`
   - For WAMP: `C:/wamp64/www/flavorforge`

2. **Import Database:**
   - Open phpMyAdmin (`http://localhost/phpmyadmin/`).
   - Create a new database named `recipe_book`.
   - Select the `recipe_book` database and click **Import**.
   - Choose the `database.sql` file located in the root folder of this project and click **Go**.

3. **Run Application:**
   - Ensure Apache and MySQL modules are started in XAMPP/WAMP.
   - Open your browser and navigate to: `http://localhost/flavorforge/`

## Features Included
- **Frontend**: Responsive UI built using Bootstrap 5 and FontAwesome.
- **JavaScript**: Live recipe search filtering, interactive image slider, smooth scrolling, modal previews, and input validation.
- **Backend**: Secure PHP authentication system (Password hashing using `password_hash`), SQL statement preparation against SQL Injection, user dashboard, and contact submission storing.