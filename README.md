# Attendance Management System

This is a PHP-based Attendance Management System that runs offline using the XAMPP control panel. It allows administrators to manage students, record attendance, view visual reports, and export data. The system is secure, admin-restricted, and simple to use, making it ideal for schools, training centers, and offices.

##  Key Features

-  **Offline Mode**: Fully functional without internet using XAMPP.
-  **Admin Login System**: Only authenticated admins can access the dashboard and manage records.
-  **Student Management**: Add, update, and delete student data with contact details.
-  **Attendance Tracking**: Mark attendance by date with just a few clicks.
-  **Visual Reports**: View bar charts showing student-wise attendance records.
-  **Excel Export**: Export attendance data to Excel with a single click.
-  **Role-based Access**: Only admins can view student contact info; others see restricted views.

##  Built With

- **PHP** – Server-side scripting
- **MySQL** – Database backend (via phpMyAdmin)
- **HTML, CSS, JavaScript** – Frontend interface
- **Chart.js** – For graphical reports
- **XAMPP** – For local development (Apache + MySQL)

## How to Run

1. Install XAMPP from [https://www.apachefriends.org](https://www.apachefriends.org)
2. Copy this project folder to `C:/xampp/htdocs/`
3. Open XAMPP Control Panel and start **Apache** and **MySQL**
4. Go to `http://localhost/phpmyadmin` and import `internship_db.sql` into a new database
5. Visit `http://localhost/project` in your browser
6. Login using your admin credentials (defined in the database)

```markdown
## Project Structure

```

project/
├── index.html
├── login.php
├── add\_student.php
├── mark\_attendance.php
├── view\_attendance.php
├── export\_attendance.php
├── admin\_dashboard.php
├── script.js
└── uploads/


## Admin Dashboard Includes

- Total student count
- Today's attendance summary
- Bar chart of attendance frequency
- Quick navigation to all major features

##  License

This project is for academic and personal use. Feel free to modify and build upon it.

