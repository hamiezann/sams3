# 🎓 Student Activity Management System (SAMS)

A modern web-based platform to manage university student activities, streamline event participation, and simplify attendance tracking. Built with **Laravel** and a responsive dashboard interface.

---

## ✨ Features

-   🧾 **Event Creation & Management** – Admins can create, update, and publish student events
-   📅 **Student Registration** – Students can apply and track their event applications
-   ✅ **Role-Based Access** – Separate dashboards and features for admins and students
-   🔍 **QR Code Attendance** – Uploadable QR codes for easy attendance verification
-   📊 **Activity Status Tracking** – Automatic flagging of ended or completed activities
-   📆 **Google Calendar Integration** – Approved events sync to student calendars

---

## 💻 Tech Stack

| Tech             | Description                         |
| ---------------- | ----------------------------------- |
| Laravel          | Backend framework                   |
| Blade / HTML/CSS | Frontend templating and UI styling  |
| MySQL            | Database for storing user data      |
| JavaScript       | Dynamic interactivity (QR/filters)  |
| Argon Dashboard  | Admin UI template (Bootstrap-based) |

---

## 🧑‍💻 Roles & Access

| Role    | Capabilities                                                                |
| ------- | --------------------------------------------------------------------------- |
| Admin   | Manage activities, approve/reject applications, upload QR codes, view stats |
| Student | Apply for events, view history, scan QR, sync to Google Calendar            |

---

## 📸 Screenshots

![Login](screenshots/admin/login.png)

### 🖥️ Admin Dashboard

![Admin Dashboard](screenshots/admin/admin%20dashboard.png)
![Activty List](screenshots/admin/activity%20management.png)
![Student List](screenshots/admin/student%20list.png)
![Activity Detail](screenshots/admin/activity%20detail.png)

### 🎓 Student Activity View

![Student View](screenshots/student/student%20dashboard.png)
![Activity List](screenshots/student/activity%20list.png)
![My Activity](screenshots/student/my%20activity.png)
![Activity Detail](screenshots/student/activity%20detail.png)

## 🚀 How to Run Locally

```bash
# Clone the repository
git clone https://github.com/yourusername/sams.git

# Navigate to the project
cd sams

# Install dependencies
composer install
npm install && npm run dev

# Set up your .env file
cp .env.example .env
php artisan key:generate

# Run migrations
php artisan migrate

# Start the server
php artisan serve
```
