<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>


# 🚀 Project Management API

A modular **Project and Task Management REST API** built with **Laravel 12**, designed to manage projects, tasks, teams, collaboration, permissions, comments, files, and project-related workflows.

The project focuses on building a maintainable backend with clear separation between HTTP handling, business logic, authorization, resources, events, notifications, and persistence.

It is designed as an API-first backend that can be consumed by web applications, mobile applications, or other services.

---

## ✨ Overview

Project Management API provides the backend infrastructure required to organize projects and tasks inside a team environment.

The system is built around several core concepts:

```text
User
 │
 ├── Roles
 │    └── Permissions
 │
 ├── Companies
 │
 └── Projects
       │
       ├── Tasks
       │    ├── Comments
       │    └── Files
       │
       └── Team / Collaboration
```

The project is intentionally structured to keep different responsibilities separated instead of putting all application logic inside controllers.

---

# 🚀 Features

## 👤 User Management

The application provides user-related functionality as part of the project management workflow.

Users can participate in projects and collaborate with other users through the application's project and task system.

---

## 🔐 Authentication & Authorization

The API uses **Laravel Sanctum** for API authentication.

Authorization is handled through:

* Roles
* Permissions
* Permission/Role relationships
* Policies

This allows access control to be defined independently from the controllers.

The database includes dedicated tables for roles, permissions, and their relationships.

### Authorization flow

```text
Request
   │
   ▼
Authentication
   │
   ▼
Authenticated User
   │
   ▼
Role / Permission
   │
   ▼
Policy
   │
   ▼
Authorized Action
```

---

# 📁 Project Management

Projects are the main organizational unit of the application.

A project can contain tasks and related collaboration data.

The project domain is represented through dedicated models rather than treating everything as a single resource.

Typical project operations include:

* Create projects
* View projects
* Update projects
* Delete projects
* Manage project-related users
* Organize tasks inside projects
* Track project progress

---

# ✅ Task Management

Tasks represent the individual units of work inside a project.

The database contains a dedicated `tasks` table, allowing tasks to be managed independently while remaining associated with their projects.

The task domain is designed to support workflows such as:

* Creating tasks
* Updating tasks
* Assigning work
* Tracking task progress
* Adding comments
* Attaching files
* Organizing work inside projects

---

# 💬 Comments

Tasks can be associated with comments, allowing team members to communicate around individual pieces of work.

Comments are modeled independently from tasks, keeping collaboration data separate from the task's core information.

The database includes a dedicated `comments` table for this purpose.

---

# 📎 File Management

The project includes a dedicated file domain for project/task-related attachments.

Files are represented separately from the core task and project entities, allowing the attachment system to evolve without coupling it directly to the main business models.

The database contains a dedicated `files` table.

---

# 🤝 Collaboration

Collaboration is treated as a separate part of the domain.

The application contains a dedicated `Models/Collaboration` area, making collaboration logic independent from the core Project and User models.

This provides a foundation for managing relationships between users and projects and can be extended as collaboration requirements grow.

---

# 🏢 Company Management

The database also contains a dedicated `companies` table, providing a foundation for organizing users and project activity around companies or organizations.

This makes the system suitable for scenarios where multiple users operate within organizational boundaries.

---

# 🔔 Notifications

The application includes a dedicated Notifications layer.

Notifications are separated from the core business operations so that notification behavior does not need to be embedded directly inside controllers or domain workflows.

This provides a cleaner path for extending the system with additional notification channels in the future.

---

# 📡 Events & Listeners

The project includes dedicated:

```text
app/
├── Events/
└── Listeners/
```

This allows application events and their side effects to remain separated from the operations that trigger them.

For example, an operation can dispatch an event without needing to know every action that should happen afterward.

Conceptually:

```text
Business Operation
       │
       ▼
     Event
       │
       ├──► Listener
       ├──► Notification
       └──► Other Side Effects
```

This helps reduce coupling between different parts of the application.

---

# 🛡️ Policies

Authorization logic is implemented through Laravel Policies.

The project contains a dedicated:

```text
app/Policies/
```

directory for authorization concerns.

Policies keep permission checks close to the resource they protect while keeping controllers focused on HTTP/application coordination.

---

# ⚡ Cache Service

The project contains a dedicated cache service layer:

```text
app/Services/Cache/
```

This provides a separate place for cache-related application behavior instead of scattering cache operations throughout controllers and models.

Keeping caching behind a dedicated layer makes future changes to caching strategies easier.

---

# 🧩 Trait Layer

The application also contains a dedicated:

```text
app/Trait/
```

directory for reusable behavior.

Traits can be used when a piece of behavior needs to be shared across multiple classes without duplicating implementation.

---

# 🏗️ Architecture

Project Management API follows a layered Laravel architecture with a clear separation between HTTP concerns and application logic.

The main application structure contains:

```text
app/
├── Events/
├── Exceptions/
├── Http/
├── Listeners/
├── Models/
├── Notifications/
├── Policies/
├── Providers/
├── Services/
└── Trait/
```

The HTTP layer is further separated into:

```text
app/Http/
├── Controllers/
├── Requests/
│   └── Api/V1/
└── Resources/
    └── Api/V1/
```

This structure keeps request validation, response transformation, and controller responsibilities separated.

---

# 🌐 API Versioning

The API uses a versioned structure:

```text
Api/
└── V1/
```

Both Request and Resource layers follow this versioning structure.

Versioning the API provides a stable foundation for future changes without forcing existing clients to immediately migrate to a new API contract.

For example:

```text
/api/v1/...
```

Future versions can be introduced independently:

```text
/api/v2/...
```

---

# 📦 API Resources

Laravel API Resources are used to control how application data is transformed into API responses.

The project contains:

```text
app/Http/Resources/Api/V1/
```

This keeps the external API representation separate from the underlying Eloquent models.

This is important because the database structure should not necessarily become the public API contract.

---

# 📝 Form Requests

Request validation is separated into:

```text
app/Http/Requests/Api/V1/
```

This allows validation rules to remain outside controllers and keeps HTTP input validation organized by API version.

Instead of:

```text
Controller
 ├── Validate input
 ├── Query database
 ├── Execute business logic
 ├── Authorize user
 └── Build response
```

the responsibilities can be separated:

```text
Request
   │
   ▼
Validation
   │
   ▼
Controller
   │
   ▼
Application Logic
   │
   ▼
Resource
   │
   ▼
API Response
```

---

# 🗄️ Database Structure

The database is built around dedicated tables for the application's main domains.

Current migrations include:

```text
users
permissions
roles
permission_role
projects
tasks
comments
files
companies
```

among the core application tables.

A simplified relationship view looks like:

```text
                   ┌──────────────┐
                   │     User     │
                   └──────┬───────┘
                          │
             ┌────────────┼────────────┐
             │            │            │
             ▼            ▼            ▼
          Roles       Companies    Collaboration
             │
             ▼
        Permissions

                   │
                   ▼
              ┌─────────┐
              │ Project │
              └────┬────┘
                   │
                   ▼
                ┌──────┐
                │ Task │
                └──┬───┘
                   │
             ┌─────┴─────┐
             ▼           ▼
         Comments       Files
```

The exact database relationships are defined through the migrations and Eloquent models.

---

# 🧱 Separation of Responsibilities

One of the main goals of the project is to avoid putting every responsibility inside a single controller.

The architecture separates concerns such as:

| Layer          | Responsibility                |
| -------------- | ----------------------------- |
| Controllers    | HTTP/application coordination |
| Requests       | Input validation              |
| Resources      | API response transformation   |
| Models         | Persistence and relationships |
| Services       | Reusable application logic    |
| Policies       | Authorization                 |
| Events         | Domain/application events     |
| Listeners      | Event side effects            |
| Notifications  | Notification behavior         |
| Exceptions     | Application error handling    |
| Traits         | Reusable behavior             |
| Cache Services | Cache-related operations      |

This separation makes the application easier to maintain and extend.

---

# 📚 API Documentation

The project uses **L5-Swagger** for API documentation.

The dependency is defined as:

```text
darkaonline/l5-swagger
```

inside the project dependencies.

This provides an OpenAPI/Swagger-based approach for documenting API endpoints.

After the application is configured, Swagger documentation can be generated using Laravel Artisan commands.

---

# 🧪 Testing

The project uses Laravel's testing infrastructure together with:

```text
PHPUnit 11
```

The dependency is defined in `composer.json`.

Run the test suite with:

```bash
php artisan test
```

The repository also defines a Composer test script:

```bash
composer test
```

which clears the configuration cache and runs Laravel's test suite.

---

# ⚙️ Requirements

Before running the project, make sure you have:

* PHP 8.2+
* Composer
* Laravel 12
* MySQL or another supported relational database
* Node.js
* npm
* Git

The current project dependencies require PHP 8.2+ and Laravel 12.

---

# 📦 Installation

## 1. Clone the repository

```bash
git clone https://github.com/milirezai/project-management-api.git

cd project-management-api
```

## 2. Install PHP dependencies

```bash
composer install
```

## 3. Create the environment file

```bash
cp .env.example .env
```

## 4. Generate the application key

```bash
php artisan key:generate
```

## 5. Configure the database

Update your `.env` file:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=project_management
DB_USERNAME=root
DB_PASSWORD=
```

## 6. Run migrations

```bash
php artisan migrate
```

## 7. Install frontend dependencies

```bash
npm install
```

## 8. Build assets

```bash
npm run build
```

## 9. Start the application

```bash
php artisan serve
```

---

# ⚡ Quick Setup

The project also provides a Composer setup script.

You can use:

```bash
composer run setup
```

The setup script handles:

```text
Composer installation
        │
        ▼
Create .env
        │
        ▼
Generate application key
        │
        ▼
Run migrations
        │
        ▼
Install npm dependencies
        │
        ▼
Build frontend assets
```

The script is defined directly in `composer.json`.

---

# 🛠️ Development

For local development, the project provides:

```bash
composer run dev
```

The development script starts multiple processes including:

* Laravel development server
* Queue listener
* Laravel Pail logs
* Vite development server

This is defined in the Composer development script.

Instead of opening multiple terminals like it is still 2012, the project can start the required development processes together.

---

# 🔄 Queue Processing

The development environment includes a Laravel queue listener:

```bash
php artisan queue:listen --tries=1
```

Queue processing allows background operations to be separated from the main HTTP request lifecycle.

The queue worker is also included in the project's Composer development command.

---

# 🔐 Security

Security-related responsibilities are separated across different parts of the application.

The project uses:

* Laravel Sanctum
* Policies
* Roles
* Permissions
* Form Requests
* API Resources

This allows authentication, authorization, validation, and response formatting to be handled independently.

---

# 🎯 Design Goals

Project Management API was built with several goals in mind:

### Maintainability

The codebase is structured so that new features can be added without forcing unrelated parts of the application to change.

### Separation of Concerns

HTTP, validation, authorization, persistence, events, notifications, and services have dedicated responsibilities.

### API-First Design

The system is designed around RESTful API consumption rather than coupling business logic to a specific frontend.

### Extensibility

The architecture leaves room for future features such as advanced collaboration, reporting, dashboards, integrations, and additional project workflows.

### Testability

Important application behavior can be tested independently through Laravel's testing infrastructure.

---

# 🗺️ Roadmap

Potential future improvements include:

* [ ] Advanced project reporting
* [ ] Project dashboards
* [ ] Task filtering and advanced search
* [ ] Task priority management
* [ ] Task status workflows
* [ ] Project activity history
* [ ] Advanced team management
* [ ] More notification channels
* [ ] File storage integrations
* [ ] API rate limiting
* [ ] More comprehensive automated tests
* [ ] CI/CD pipeline
* [ ] Dockerized development environment
* [ ] Advanced API documentation
* [ ] Project analytics

---

# 🤝 Contributing

Contributions are welcome.

To contribute:

```bash
git checkout -b feature/my-feature

git add .

git commit -m "Add my feature"

git push origin feature/my-feature
```

Then open a Pull Request.

Before submitting a Pull Request, make sure the test suite passes:

```bash
php artisan test
```

---

# 📄 License

This project is open-source software licensed under the **MIT License**.

See the `LICENSE` file for more information.

---


# ⭐ Support

If you find this project useful, consider giving the repository a ⭐ on GitHub.

It costs nothing, improves the repository's visibility, and makes the developer feel that the hours spent arguing with controllers and migrations were not entirely wasted.

---
