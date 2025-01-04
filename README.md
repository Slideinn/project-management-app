# Basic Project Management App Made on Top of FilamentPHP

A simple project management application built with Laravel and FilamentPHP. This app allows users to manage projects and tasks effectively.

## Installation

### Prerequisites

- Docker
- Docker Compose
- Composer

### Setup

1. **Clone the repository:**

    ```sh
    git clone https://github.com/Slideinn/project-management-app.git
    cd project-management-app
    ```

2. **Copy the .env.example file to .env and configure the environment variables:**

    ```sh
    cp .env.example .env
    ```

3. **Install the dependencies:**

    ```sh
    composer install
    ```

4. **Start the Laravel Sail environment:**

    ```sh
    ./vendor/bin/sail up -d
    ```

5. **Generate the application key:**

    ```sh
    ./vendor/bin/sail artisan key:generate
    ```

6. **Run the migrations:**

    ```sh
    ./vendor/bin/sail artisan migrate
    ```

7. **Seed the database with demo data:**

    ```sh
    ./vendor/bin/sail artisan db:seed --class=DemoSeeder
    ```

### Access the Application

Once the server is running, you can access the application at `http://localhost/admin`.

- **Email:**
    - admin@admin.com
- **Password:**
    - admin

## Usage

### Managing Projects

- Create, update, and delete projects.
- Assign users to projects.

### Managing Tasks

- Create, update, and delete tasks.
- Assign users to tasks as assignees or watchers.
- Filter and sort tasks by name, start date, end date, and status.

### Notifications
- Custom notifications wrapper for FilamentPHP.

## Running Tests

To run the tests, use the following command:

```sh
./vendor/bin/sail artisan test
```