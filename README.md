# Keyword Monitor

Keyword Monitor is a web application built with Laravel framework. It allows users to track the position of their website for specific keywords on search engines like Google. The application provides features such as:

![screenshot](https://github.com/yasinkuyu/keyword-monitor/blob/keyword-monitor/screenshot.png)

![screenshot](https://github.com/yasinkuyu/keyword-monitor/blob/keyword-monitor/screenshot1.png)

## Features

-   Keyword management: Add, edit, and delete keywords to track.
-   Search engine selection: Choose the search engine to track (e.g., Google, Bing, Yahoo).
-   Position tracking: Monitor the position of your website for each keyword on the selected search engine.
-   Reporting: Generate reports to analyze the performance of your keywords over time.
-   User authentication: Secure access to the application with user registration and login.

## Technologies Used

-   Laravel 11.9 as the backend framework
-   Vue.js 3 with Inertia.js for the frontend
-   Tailwind CSS for styling
-   Configuration files for Vite, Tailwind, and PHPUnit

## Installation

1. Clone the repository: `git clone https://github.com/yasinkuyu/keyword-monitor.git`
2. Navigate to the project directory: `cd keyword-monitor`
3. Install dependencies: `composer install` and `npm install`
4. Copy the environment file: `cp .env.example .env`
5. Generate an application key: `php artisan key:generate`
6. Configure your database credentials in the `.env` file
7. Run database migrations: `php artisan migrate`
8. Start the development server: `php artisan serve` && `npm run dev`

If you encounter any router issues, run the `php artisan ziggy:generate` command.

## Usage

1. Register a new account or log in with your existing credentials
2. Add keywords you want to track
3. Select the search engine for tracking
4. Monitor the position of your website for the added keywords
5. Generate reports to analyze the performance over time

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Contributing

Thank you for considering contributing to the Keyword Monitor application! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## License

The Keyword Monitor application is open-sourced software licensed under the [License](https://github.com/yasinkuyu/keyword-monitor/blob/keyword-monitor/LICENSE.md).
