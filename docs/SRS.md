# Software Requirements Specification (SRS)
## Online Children's Story Platform

### 1. Introduction
The Online Children's Story Platform is a web-based system connecting writers, children, and parents through digital storytelling.

### 2. Purpose
To provide a safe, engaging, and easy-to-use platform where writers can publish stories, children can read and track progress, and parents can monitor their children's reading activities.

### 3. Project Background
Digital storytelling is vital for child development. This platform provides a centralized, secure environment for this purpose, built using core web technologies without heavy frameworks.

### 4. Problem Statement
There is a need for a localized, lightweight, and secure web application where children can safely access age-appropriate stories, and parents can monitor their engagement without relying on complex or external frameworks.

### 5. General Objective
Develop a complete, secure, and responsive web application using HTML5, CSS3, Vanilla JavaScript, PHP (PDO), and MySQL.

### 6. Specific Objectives
- Allow users (Writers, Children, Parents) to register and log in securely.
- Enable Writers to create, edit, publish, and delete their stories.
- Enable Children to browse, search, and read published stories.
- Track reading progress automatically.
- Allow Parents to link to child accounts and monitor reading history.
- Implement robust security (CSRF, XSS prevention, SQL Injection prevention, Password Hashing).
- Implement a responsive UI matching the provided design.

### 7. Scope of the System
The system includes user authentication, role-based dashboards (Writer, Child, Parent), story CRUD, category CRUD, reading progress tracking, and a live search API.

### 8. Limitations
- No external frameworks (No Laravel, React, Vue, Bootstrap, Tailwind).
- No external JavaScript frameworks (No jQuery).
- Text-based stories only (no file uploads for stories required by the prompt, only images).

### 9. Stakeholders
- **Writers:** Create and manage content.
- **Children:** Read stories and track progress.
- **Parents:** Monitor children's reading.
- **Administrators:** Manage categories and users (as authorized).

### 10. User Roles
- **Writer:** Dashboard, Create/Edit/Delete/Publish stories.
- **Child:** Browse, Search, Read stories, Track progress.
- **Parent:** Link to child, View child's reading history and progress.

### 11. Functional Requirements
- **Auth:** Registration, Login, Logout.
- **Stories:** CRUD, Publish/Draft status.
- **Categories:** CRUD.
- **Readings:** Track progress (0-100%), mark completed.
- **Search:** Live AJAX search by title, category, age.
- **Parent-Child:** Secure linking mechanism.

### 12. Non-Functional Requirements
- **Security:** Password hashing, CSRF tokens, Prepared Statements, XSS escaping.
- **Responsiveness:** Must work on desktop, tablet, and mobile.
- **UI/UX:** Match the provided StoryNest design (Lavender, Purple, Soft Pink, Cream, Rounded corners).

### 13. Software Requirements
- XAMPP (Apache, PHP 7.4+, MySQL, phpMyAdmin)
- Visual Studio Code
- Web Browser

### 14. Hardware Requirements
- Standard PC with 4GB+ RAM.
- Localhost server environment.

### 15. Technology Stack
- Frontend: HTML5, CSS3, Vanilla JS, Fetch API, JSON.
- Backend: Vanilla PHP, PDO, Sessions.
- Database: MySQL (3NF).

### 16. Database Requirements
Tables: `users`, `stories`, `categories`, `readings`, `parent_child`.

### 17. Security Requirements
- `password_hash()` and `password_verify()`.
- PDO prepared statements for all queries.
- `htmlspecialchars()` for all output.
- CSRF token validation on all POST forms.

### 18. Interface Requirements
- **Home:** Hero section, Featured Stories, Browse by Category.
- **Reader:** Clean reading interface with progress bar.
- **Dashboards:** Role-specific views with appropriate actions.

### 19. System Architecture
MVC-inspired. `public/index.php` acts as the front controller. Models handle DB. Controllers handle logic. Views handle UI. `api/` handles JSON requests.

### 20. Use Cases
- User registers/logs in.
- Writer creates a story and publishes it.
- Child reads a story, progress is updated via Fetch.
- Parent logs in, selects child, views progress.

### 21. System Workflow
Request -> `index.php` -> Router -> Controller -> Model (DB) -> Controller -> View (HTML) OR API (JSON).

### 22. Assumptions
- XAMPP is installed and running.
- The database is imported via phpMyAdmin.

### 23. Future Improvements
- Story image uploads.
- Audio narration for stories.
- Advanced analytics for parents.