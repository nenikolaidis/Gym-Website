# Gym Management System (University Project)

A web-based Gym Management System built using PHP, HTML, CSS, and JavaScript. This system provides admin and user interfaces for managing gym services, news, history, and user accounts.

---

## Project Structure

`css/`

Contains all the stylesheet files used for different sections of the website:

 - `default.css` – Base styles applied throughout the site.

 - `history.css` – Styles for the history page.

 - `index.css` – Styles for the homepage.

 - `news.css` – Styles for the news section.

 - `open.css` – Styles for the open page.

 - `services.css` – Styles for the services page.

 - `umanagement.css` – Styles for user management pages.

 - `update.css` – Styles for update forms and pages.

`images/`

Directory for storing all images used in the website, including logos, banners, and content images.

`php-html/`

Contains all the PHP and HTML files, organized into subdirectories:

`admin/`

Files for administrative functionality:

 - `adminhistory.php` – Admin view for gym history.

 - `adminhome.php` – Admin dashboard/homepage.

 - `adminnews.php` – Admin management of news.

 - `adminservices.php` – Admin management of services.

 - `admissions.php` – Admission handling page.

 - `updatenews.php` – Page to update news.

 - `updatepage.php` – Generic update page for admin.

 - `updateservice.php` – Page to update gym services.

 - `usersmanagement.php` – Admin user management interface.

`classes/`

Contains PHP classes for handling backend logic:

 - `dbh.connection.php` – Database connection class.

 - `login.classes.php` – Class for handling user login.

 - `signup.classes.php` – Class for handling user registration.

`includes/`

Reusable PHP scripts for backend operations:

 - `login.inc.php` – Script to process login requests.

 - `logout.inc.php` – Script to handle user logout.

 - `signup.inc.php` – Script to process user registration.

`main/`

Core website files:

 - `index.php` – Main homepage of the website.

 - `script.js` – JavaScript file for frontend interactions.

 - `server.php` – Server-side processing scripts.

 - `success.php` – Page displayed on successful operations.

`user/`

Files for user-specific functionality:

 - `open.php` – Open page accessible by users.

 - `userhistory.php` – Page for users to view gym history.

 - `usernews.php` – Page for users to view news updates.

 - `userservices.php` – Page for users to view available services.

`txt/`

 - `arrays.txt` – Text file to have a clear view for all the arrays used.

---

## Features

### Admin Panel
- Manage gym services, news, and admissions
- Update existing pages or services
- Manage user accounts

### User Panel
- View gym news and services
- Access personal history
- Open general information pages

### Authentication
- Secure login and signup system
- Session management for users and admins

### Styling
- Modular CSS files for different pages and functionalities
- Responsive and clean layout

---

## Installation

1. Clone the repository:
   ```bash
   git clone <repository_url>
2. Place the project in your local server directory (e.g., htdocs for XAMPP).

3. Ensure PHP and MySQL are installed and running.

4. Configure database connection in `php-html/classes/dbh.connection.php`.

5. Access the project via http://localhost/Gym/php-html/main/index.php.

---

## Usage

### Admin

 - Login via the admin login page

 - Manage news, services, and users

### User

 - Login via the user login page

 - Browse gym services and news

 - Check personal history

---

## Technologies Used

 - PHP

 - MySQL

 - HTML5

 - CSS3

 - JavaScript

## Notes

 - Page-specific CSS is separated into individual files for easy maintenance.

 - JavaScript functionality is located in `main/script.js`.

 - Text data arrays are stored in `txt/arrays.txt`.
