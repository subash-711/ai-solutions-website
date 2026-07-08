# AI-Solutions Promotional Website

## Project Overview
A professional promotional website for AI-Solutions, a fictional next-generation AI start-up based in Sunderland, UK. Developed as part of the CET333 Product Development module at the University of Sunderland.

## Technologies Used
- PHP 8.2
- MySQL 8.0
- HTML5 / CSS3 / JavaScript
- XAMPP (Local Development)

## Features
- 7-page responsive public website
- Contact Us form with 7 fields
- Rule-based AI chatbot (accessible on all pages)
- Password-protected admin dashboard with 6 management tabs
- Dark mode toggle

## How to Run Locally

1. Install XAMPP (Apache + MySQL) from https://www.apachefriends.org.
2. Open XAMPP Control Panel and start Apache and MySQL.
3. **Important:** Configure XAMPP to use port 8080 (edit `httpd.conf` and change `Listen 80` to `Listen 8080`).
4. Copy the entire `ai-solutions-website` folder into your XAMPP `htdocs` folder.
5. Open phpMyAdmin at `http://localhost:8080/phpmyadmin` and create a new database named `ai_solutions_db`.
6. Import the `database.sql` file into that database.
7. Open your browser and go to:
   `http://127.0.0.1:8080/ai-solutions/index.php`

## Default Admin Login
- **Username:** `admin`
- **Password:** `Admin@1234`

## Default Admin Login
- Username: `admin`
- Password: `Admin@123`
*(Change this password immediately after first login)*

## Author
Subash Acharya – Student ID: 250699667
