## WineMetrics 🍷📊

A structured and scalable API for managing wine measurements, built with [Symfony 7.2](https://symfony.com/releases/7.2), [Hexagonal Architecture](https://en.wikipedia.org/wiki/Hexagonal_architecture_(software)), [Domain-Driven Design](https://en.wikipedia.org/wiki/Domain-driven_design), and [SOLID](https://en.wikipedia.org/wiki/SOLID) principles.


### 📝 Project Description

WineMetrics is a highly modular and maintainable API designed to manage and track various wine measurements (e.g., temperature, pH, alcohol content, and color). This project is built using Symfony and follows best practices, including:

- ✅ Domain-Driven Design (DDD) → Clearly defined business logic.
- ✅ Hexagonal Architecture → Decoupled and scalable system design.
- ✅ SOLID Principles → Maintainable and extensible code.
- ✅ Clean Code → Readable, testable, and well-structured.


### ✨ Features

- **🔐 Authentication**
    - Secure login with **Bearer Token authentication**.
    - Token refresh support.


- **📡 Sensor & Measurement Management**
    - Register new **sensors**.
    - Retrieve a list of all sensors **sorted by name**.
    - Retrieve all **wines along with their measurements**.
    - Register **new wine measurements**.


- **👤 User Management**
    - Register a **new user**.
    - Retrieve the **authenticated user’s profile**.
    - Admins can **list all registered users**.


### 🛠️ Tech Stack

- **Framework**: Symfony 7.2
- **Database**: MySQL with Doctrine ORM
- **Authentication**: JWT with refresh tokens (LexikJWTAuthenticationBundle + GesdinetJWTRefreshTokenBundle)
- **Testing**: PHPUnit, Behat
- **Documentation**: Swagger OpenAPI
- **Containerization**: Docker
- **Code Quality**: PHP-CS-Fixer, PHPStan, PHP CodeSniffer, Rector
- **CQRS**: Command Query Responsibility Segregation
- **Async**: Asynchronous processing with Symfony Messenger
- **Translation**: Symfony Translation
- **Custom Features**: Pagination, Exception Handling, Transformers, Exceptions, Custom Commands

### 🚀 Installation & Setup

1️⃣ Clone the repository
    
    git clone <repository-url>

2️⃣ Configure environment variables

    cp .env .env.local
    cp .env .env.test.local

3️⃣ Install dependencies

    composer install

4️⃣ Generate the SSL keys for JWT

    php bin/console lexik:jwt:generate-keypair

5️⃣ Run database migrations

    php bin/console doctrine:migrations:migrate
    php bin/console doctrine:migrations:migrate --env=test

6️⃣ Start the server
    
    symfony server:start

### 🐳 Docker

Update the database host in your .env local files to match the database container name.

    DATABASE_URL="mysql://app:!ChangeMe!@database:3306/app?serverVersion=8.0.32&charset=utf8mb4"
    DATABASE_URL="mysql://app:!ChangeMe!@database_test:3306/app?serverVersion=8.0.32&charset=utf8mb4"

To run the project using Docker, you need to have Docker and Docker Compose installed on your machine.

    docker-compose up --build -d

### 🧪 Run the Tests
This script will automatically detect if the application is running inside a **Docker container** and execute the tests within it.  
If no container is found, the tests will be executed **locally** on your machine.

    composer test

### 📚 Swagger Documentation
The API documentation is available at the following URL:

    # Replace your server URL and port accordingly
    http://localhost:8888/api/doc 

## 📖 API Endpoints

### 🔐 Authentication
| Method   | Endpoint                  | Description                    | Security |
|----------|--------------------------|--------------------------------|----------|
| **POST** | `/api/v1/authentication`  | Get a Bearer token            | 🆓 Public |
| **PUT**  | `/api/v1/authentication`  | Refresh the token             | 🆓 Public |

---

### 📡 Sensor & Measurement Management
| Method   | Endpoint               | Description                                    | Security |
|----------|------------------------|------------------------------------------------|----------|
| **POST** | `/api/v1/sensors`      | Register a new sensor                          | 🔒 Secured (Any Role) |
| **GET**  | `/api/v1/sensors`      | Get all sensors ordered by name                | 🔒 Secured (Any Role) |
| **GET**  | `/api/v1/wines`        | Get all wines with their measurements          | 🔒 Secured (Any Role) |
| **POST** | `/api/v1/measurements` | Register a new wine measurement                | 🔒 Secured (Any Role) |

---

### 👤 User Management
| Method   | Endpoint              | Description                        | Security |
|----------|----------------------|------------------------------------|----------|
| **POST** | `/api/v1/users`       | Create a new user                  | 🆓 Public |
| **GET**  | `/api/v1/users/me`    | Get the authenticated user profile | 🔒 Secured (Any Role) |
| **GET**  | `/api/v1/users`       | List all users                     | 🔒 Secured (Admin Only) |

### 🚀 TODO List

- [x] Initial Symfony project
- [x] Create DDD structure
- [x] Swagger OpenAPI
- [x] Doctrine with MySQL and Fixtures
- [x] Symfony Security
- [x] Exception Handling
- [x] Symfony Translation
- [x] PHP CodeSniffer
- [x] PHP-CS-Fixer
- [x] PHPStan
- [x] Rector
- [x] PHPUnit
- [x] Behat
- [x] Docker
- [x] CQRS with Async (Symfony Messenger)
- [x] Custom Pagination
- [x] Transformers
- [x] Authentication: Create a JWT for a user
- [x] Authentication: Refresh the JWT
- [x] User management: Create a new user
- [x] User management: List all users
- [x] User management: Get the authenticated user profile
- [x] Sensor management: Register a new sensor
- [x] Sensor management: Get all sensors ordered by name
- [x] Create and populate the database with the necessary wine data
- [x] Measurement management: Register a new wine measurement
- [ ] Measurement management: Get all wines with their measurements
- [ ] Add an endpoint to get the list of wines without measurements (for the frontend)

📌 Future Enhancements
- [ ] Add logs
- [ ] Docker Messenger
- [ ] Symfony Cache
- [ ] Symfony Mailer for sending confirmation emails
- [ ] Add caching for frequently requested data, like wine and sensor lists.
- [ ] Update the endpoint to get the list of wines without measurements.
- [ ] Create a new endpoint to get a single wine with its measurements.
