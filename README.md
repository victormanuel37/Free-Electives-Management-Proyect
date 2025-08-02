## Free-Electives-Management-Proyect 

# Description
This project is a web-based system developed in **PHP** and **MySQL** for managing university free electives. It allows administrators and coordinators to create, edit, and organize courses, terms, and users, while students can view active offerings.  
The system implements the **MVC** (Model-View-Controller) architecture and **Object-Oriented Programming (OOP)** to maintain modular and scalable code.

# Main Features
- *Administrators:*
  - Full CRUD for courses, users, departments, and academic terms.
  - Manage course offerings for the active term.
  - Control user roles and permissions.
- **Chairs & Coordinators:**
  - CRUD for courses and offerings within their own department.
- **Students/Visitors:**
  - View available free elective courses, organized by department.
- **Validations:**
  - Regex-based validation for course codes, prerequisites, user IDs, and passwords.
- **Authentication:**
  - Login system with session handling and password hashing.
- **Consistent Layout:**
  - `header.php` and `footer.php` for a unified site design.

# Installation & Usage

1. Clone repository: "bash - git clone https://github.com/victormanuel37/Free-Electives-Management-Proyect.git".
2. Import the SQL file from db/ into your MySQL server.
3. Configure database credentials in config/database.php.
4. Place the project in your local server (XAMPP/WAMP).
5. Access browser via localhost.

# Tech Stack
- Backend: PHP 7.3+
- Database: MySQL
- Frontend: HTML5, CSS3, JavaScript
- Architecture: MVC

Programming: OOP
# Technical Highlights
- Full MVC implementation.
- Secure database connection using PDO.
- Clear separation of concerns between controllers, models, and views.
- Input validation with regular expressions.
- Modular and scalable folder organization.
