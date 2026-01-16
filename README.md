# IlmHub

# IlmHub - The Islamic Event Management System

## Group Information

**Group Name**: Clove

**Section**: 5

**Group Members** :
- MOHAMAD FAZWAN BIN FADZLI - 2317181
- CHE MUHAMMAD HAKIMI BIN CHE ARSHAD - 2311665
- MOHAMAD RAZIF ARMAN BIN RIZUWAN - 2311911 
- MUHAMMAD HAZANI HARIZ BIN AZMAN - 2313199

**Presentation Link**: https://youtu.be/MNSRi0fUgHw

## Project Overview

Introduction :

IlmHub is a web-based Islamic event management system developed using the Laravel MVC framework. The platform centralizes access to Islamic educational and community events that are usually scattered across social media and personal networks.

Unlike generic event platforms, IlmHub integrates Shariah-compliant features such as gender separation indicators, prayer break scheduling, and curated Islamic categories. This ensures that all listed events meet both technical and religious requirements, providing a trustworthy and efficient solution for Muslim communities to discover and book events.

## Project Objectives

- Primary Goal: Provide a centralized platform for discovering and booking Islamic events
- Technical Goal: Implement Laravel MVC architecture with secure authentication and normalized database design
- User Experience Goal: Develop a responsive interface with real-time booking updates and notifications
- Business Goal: Prevent overbooking through robust capacity management
- Community Goal: Enable organizers to publish Shariah-compliant events and allow users to reserve seats easily

## Target Users

- Attendees (Users): Individuals looking to join Islamic talks, classes, and community events
- Organizers (Admins): Event organizers who manage and publish Islamic events
- System Administrators: Users who oversee platform operations and access control


## Features and Functionalities

**Customer Features**

- User Registration & Login: Secure account creation and authentication for attendees using email and password.
- Event Browsing: Users can view all available Islamic events on the homepage.
- Event Filtering: Events can be filtered by Shariah-compliant categories such as Fiqh, Seerah, Tajweed, Community, and Youth.
- Event Details Viewing: Users can view full event information including description, speaker, date, location, gender policy, and prayer break schedule.
- Event Booking: Registered users can book seats for events using the “Book Now” button.
- Booking History: Users can view all their past and current bookings in the “My Bookings” page.
- Booking Cancellation: Users can cancel their bookings if they are no longer able to attend.
- Profile Management: Users can manage their account information after logging in.
- Flash Notifications: Users receive instant feedback messages such as “Booking Confirmed” or “Event Full”.

**Admin Features**

- Event Dashboard: Organizers can view and manage all events they have created.
- Event Creation (CRUD): Admins can create new Islamic events with required details such as title, description, date, location, and capacity.
- Shariah-Compliant Event Settings: Admins can specify:
>Gender Policy (Brothers Only, Sisters Only, Mixed, Segregated)

>Shariah Category (Fiqh, Seerah, etc.)
- Event Editing: Admins can update event information and replace event posters.
- Event Deletion: Admins can remove events they no longer want to publish.
- Image Upload: Event posters can be uploaded and stored securely.
- Access Control: Only the event creator is allowed to edit or delete their events.

## Technical Implementation

**Technology Stack**

- Backend Framework: Laravel 10.x
- Frontend: Blade Templates with Bootstrap 5
- Database: MySQL
- Authentication: Laravel Auth
- Image Storage: Laravel File Storage
- Development Environment: XAMPP & VS Code

**Database Design**

The IlmHub database is designed to manage Islamic events, user accounts, bookings, and Shariah-compliant categories efficiently. The system consists of [four] core functional tables along with several Laravel system tables for authentication, sessions, and background jobs.

Core Tables:

- users - Stores attendee and admin user accounts, including name, email, encrypted password, and role (admin or attendee).
- events - Stores Islamic event information such as title, description, date, location, capacity, gender policy, image path, and category.
- bookings - Records user reservations for events, including booking status (confirmed, pending, or cancelled) and booking time.
- categories - Stores Shariah-compliant event categories such as Fiqh, Seerah, Tajweed, Community, and Youth.

Supporting System Tables:
- migrations - Tracks executed database migrations
- cache / cache_locks - Manages application caching
- jobs / job_batches / failed_jobs - Handles background job processing
- password_reset_tokens - Stores password recovery tokens
- sessions - Tracks user login sessions

### Entity Relationship Diagram (ERD)

https://docs.google.com/document/d/1ovjsCx8nprUwTBSmLJXobzOckBQiiAkJMAwuzy460lQ/edit?usp=sharing

Key Relationships:

- Users can have multiple Bookings (One-to-Many)
- Events can have multiple Bookings (One-to-Many)
- Categories can have multiple Events (One-to-Many)
- Each Booking belongs to one User and one Event

**Laravel Components Implementation**

- Routes (Web.php)

php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\PublicEventController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;

`// Public Homepage Route`
Route::get('/', [PublicEventController::class, 'index'])->name('home');

`// View Single Event Details`
Route::get('/events/{id}', [PublicEventController::class, 'show'])
    ->whereNumber('id')
    ->name('events.show');

`// Registration Routes`
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

`// Login Routes`
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

`// Logout Route`
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

`// Protected Routes`(Authenticated Users Only)
Route::middleware(['auth'])->group(function () {

    `// Event Management Routes`
    Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
    Route::post('/events/save', [EventController::class, 'store'])->name('events.store');
    Route::get('/events', function () {
        return redirect()->route('events.create');
    });

    Route::get('/my-events', [EventController::class, 'index'])->name('events.mine');
    Route::get('/events/{event}/edit', [EventController::class, 'edit'])->name('events.edit');
    Route::put('/events/{event}', [EventController::class, 'update'])->name('events.update');
    Route::delete('/events/{event}', [EventController::class, 'destroy'])->name('events.destroy');

    `// Booking Routes`
    Route::post('/events/{id}/book', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/my-bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::delete('/bookings/{id}', [BookingController::class, 'destroy'])->name('bookings.destroy');
});

- Controllers
  
  *Main Controllers Implemented are below :*

  1. PublicEventController: Handles the homepage display and public event listings.
  2. EventController: Manages Islamic event creation, editing, updating, and deletion (CRUD operations).
  3. BookingController: Processes event bookings, capacity checks, and booking cancellations.
  4. AuthController: Manages user registration, login, and logout authentication processes.

- Models and Relationships
  
`// User Model`
class User extends Authenticatable {
    public function bookings() {
        return $this->hasMany(Booking::class);
    }

    public function events() {
        return $this->hasMany(Event::class);
    }
}

`// Booking Model`
class Booking extends Model {
    public function user() {
        return $this->belongsTo(User::class);
    }
    
    public function event() {
        return $this->belongsTo(Event::class);
    }
}

`// Event Model`
class Event extends Model {
    public function category() {
        return $this->belongsTo(Category::class);
    }
    
    public function user() {
        return $this->belongsTo(User::class);
    }

    public function bookings() {
        return $this->hasMany(Booking::class);
    }
}

`// Category Model`
class Category extends Model {
    public function events() {
        return $this->hasMany(Event::class);
    }
}

- Views and User Interface

  *Blade Templates Structure:*
  - layouts/app.blade.php - Main layout
  - welcome.blade.php - Homepage
  - events/show.blade.php - Event details
  - events/create.blade.php - Create event
  - events/edit.blade.php - Edit event
  - events/my.blade.php - Organizer dashboard
  - bookings/index.blade.php - My bookings
  - auth/login.blade.php - Login
  - auth/register.blade.php - Register

   *UI Design Features:*
   - Responsive Design: Bootstrap 5 for mobile-first approach
   - Color Scheme: Islamic green & white color scheme
   - Navigation: Simple navigation menu
   - Interactive Elements: Event card layout for browsing, floating card and use of colours when hovers over the cards

## User Authentication System

### **Authentication Features**
- **Registration System**: Secure user registration with email validation and password confirmation
- **Login System**: Secure authentication using email and password with session management
- **Password Reset**: Email-based password recovery system
- **Role-Based Access**: Different access levels for Organizers (Admins) and Attendees, ensuring only organizers can manage events
- **Profile Management**: Users can update their personal information after logging in

### **Security Measures**
- CSRF protection
- Input validation
- Middleware route protection
- Secure session handling

## Installation and Setup Instructions
### Prerequisites :
Before running the IlmHub system, ensure the following software is installed:
- PHP >= 8.1
- Composer
- Node.js and NPM
- MySQL 8.0
- XAMPP 

### Step-by-Step Installation

1. Clone the Repository

git clone https://github.com/[your-username]/IlmHub.git

cd IlmHub

2. Install Dependencies

composer install

npm install

3. Environment Configuration

- Copy the example environment file and generate the app key:

cp .env.example .env

php artisan key:generate

- Then open .env and configure your database:

DB_DATABASE=ilmhub_db

DB_USERNAME=root

DB_PASSWORD=

4. Database Setup

- Create a new database named ilmhub_db in phpMyAdmin, then run:

php artisan migrate

php artisan db:seed

5. Start Development Server

php artisan serve
npm run dev

6. Access the Application

- Open your browser and go to:

http://127.0.0.1:8000

## Testing and Quality Assurance

### Functionality Testing

 - User registration and login system
 - Role-based access for organizers and attendees
 - Event creation, editing, and deletion (CRUD)
 - Event browsing and category filtering
 - Event booking and cancellation process
 - Capacity checking to prevent overbooking
 - Duplicate booking prevention
 - Display of booking history in user dashboard
 - Image upload and display for events
 - Flash notifications for booking status
 - Responsive design across devices

### Browser Compatibility

-  Google Chrome (Latest)
-  Mozilla Firefox (Latest)
-  Microsoft Edge (Latest)

### Performance Testing

- Page load times kept under 3 seconds
- Optimized database queries using Eloquent relationships
- Image files stored efficiently in the public folder
- System tested on different screen sizes


## Challenges Faced and Solutions
### Challenge 1: Preventing Overbooking
- Problem: Multiple users could attempt to book the same event at the same time, which may exceed the event’s capacity.
- Solution: Implemented a capacity check in the BookingController that counts confirmed bookings before allowing a new booking. If the event is full, the system displays an error message.
### Challenge 2: Duplicate Bookings
- Problem: Users could book the same event multiple times, causing inaccurate booking records.
- Solution: Added a validation check to ensure that a user cannot book the same event more than once unless their previous booking was cancelled.
### Challenge 3: Role-Based Access Control
- Problem: Only organizers should be allowed to create, edit, or delete events, while normal users should only be able to book events.
- Solution: Used Laravel middleware and role validation to restrict access to event management features for administrators only.
### Challenge 4: Secure User Authentication
- Problem: User login and registration needed to be secure to protect personal data.
- Solution: Implemented Laravel’s built-in authentication system with password hashing, input validation, and CSRF protection.
### Challenge 5: Shariah-Compliant Event Filtering
- Problem: Generic event platforms do not provide Islamic-specific filters such as gender policy and Shariah categories.
- Solution: Added custom fields for gender policy and Islamic categories, ensuring that all events follow Shariah guidelines.

## Future Enhancements
### Phase 2 Features
- Email & SMS reminders
- Online payment integration
- QR code attendance system
- Advanced event filters
- Mobile app version
- Analytics dashboard

### Scalability

- Database optimization for larger datasets
- Caching implementation for improved performance
- API development for mobile app integration
- Load balancing for high traffic scenarios


## Learning Outcomes
### Technical Skills Gained

- Laravel Framework: Understanding MVC architecture and using Eloquent ORM for database operations
- Database Design: Designing structured tables and relationships for efficient data management
- Authentication: Implementing secure login, registration, and role-based access control
- Frontend Development: Creating responsive interfaces using Blade templates and Bootstrap
- Version Control: Managing project files using Git and GitHub

### Soft Skills Developed

- **Team Collaboration** : Working effectively with group members to complete tasks
- **Project Management** : Planning and organizing development activities
- **Problem Solving** : Identifying and fixing system errors during development
- **Documentation** : Preparing clear and structured project reports

## References

1. Laravel Documentation. (2024). Laravel 10.x Documentation. Retrieved from https://laravel.com/docs/10.x
2. Bootstrap Documentation. (2024). Bootstrap 5.3 Documentation. Retrieved from https://getbootstrap.com/docs/5.3/
3. MySQL Documentation. (2024). MySQL 8.0 Reference Manual. Retrieved from https://dev.mysql.com/doc/refman/8.0/en/
4. MDN Web Docs. (2024). Web Development Resources. Retrieved from https://developer.mozilla.org/
5. Stack Overflow. (2024). Programming Q&A Platform. Retrieved from https://stackoverflow.com/

## Conclusion
IlmHub successfully demonstrates the development of a Shariah-compliant Islamic event management system using the Laravel framework. The project showcases strong implementation of MVC architecture, database design, authentication, and responsive UI design.

### Key Achievements

- Implemented Laravel MVC components (Routes, Controllers, Views, Models)
- Built event creation & booking system
- Applied secure authentication
- Ensured Islamic compliance
- Prevented overbooking
- Designed user-friendly interface

### Project Impact
The IlmHub project allowed students to apply web development concepts in a real-world scenario using the Laravel framework. Students gained practical experience in backend and frontend development, database design, and authentication systems.
Working in a group improved teamwork, communication, and time management skills. The project also helped students become more confident in using tools like GitHub, MySQL, and Laravel for professional web application development.

- Project Completion Date: 16/1/2026
- Course: INFO 3305 Web Application Development
