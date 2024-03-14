# PERSONAL NOTE: 
## SET THE CONFIG CORS LATER WHEN IS ONLINE...
## The providers Policies and Gates


sudo service postresql start
sudo service redis-server start
php artisan serve

# Simplified Banking System

## About the Project

This project aims to develop a simplified banking system supporting two types of users: customers and administrators. It is designed to allow customers to manage their finances through transactions and administrators to control deposits made by checks.

## Technology Stack

- **Backend**: Laravel 8.x, PHP 7.4 or higher
- **Frontend**: Vue.js 3.x, Vite as a build tool
- **Database**: PostgreSQL 13.x
- **Authentication**: JWT (JSON Web Tokens) with Laravel Sanctum
- **Testing**: PHPUnit for backend testing
- **Image Storage**: Amazon S3
- **Hosting**: Heroku

## Database

PostgreSQL is chosen for its robustness, performance, and advanced feature support, essential for the financial operations and management of this system.

### Models and Relationships

- **User**: Stores user information such as username, email, password (hash), and role (customer or administrator).
- **Account**: Linked to a User, stores the account number and current balance.
- **Transaction**: Related to an Account, records transactions including type (income, expense, deposit), amount, description, and date.
- **Check**: Also linked to an Account, stores information about checks deposited, including amount, description, image path, and status (pending, accepted, rejected).

## Backend (Laravel)

The backend is responsible for business logic, authentication, database operations, and file system interactions (for image storage). It will implement a RESTful API for communication with the frontend.

### Key Endpoints

- **Authentication**: Registration, login, and user details
- **Transactions**: Listing, recording expenses, and income
- **Checks**: Submission for deposit, listing, and administrative operations for approval/rejection

## Frontend (Vue.js)

Developed with Vue.js, the frontend will provide an interactive interface for users, enabling them to perform banking operations intuitively and efficiently.

## Author

- **Name**: [Thiago Cesar Soares]
- **GitHub**: [Github](https://github.com/thiagocsoaresbh)
- **LinkedIn**: [Linkedin](https://linkedin.com/in/thiago-csoares)

## Acknowledgments

This README provides a comprehensive overview of the Simplified that Banking System project, detailing the technologies used, database structure, backend and frontend information.

___

#ABOUT LARAVEL

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains over 1500 video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the Laravel [Patreon page](https://patreon.com/taylorotwell).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Cubet Techno Labs](https://cubettech.com)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[Many](https://www.many.co.uk)**
- **[Webdock, Fast VPS Hosting](https://www.webdock.io/en)**
- **[DevSquad](https://devsquad.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[OP.GG](https://op.gg)**
- **[WebReinvent](https://webreinvent.com/?utm_source=laravel&utm_medium=github&utm_campaign=patreon-sponsors)**
- **[Lendio](https://lendio.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).