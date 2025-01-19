# Smart Yatra

Make sure you have the following installed:

1. [PHP 8.1+](https://www.php.net/downloads)
2. [Composer](https://getcomposer.org/download/)
3. [MySQL](https://dev.mysql.com/downloads/) or any supported database
4. A web server (e.g., Apache)

## Project Setup Instructions

1.  **Clone the Repository**
    ```bash
    git clone <repository_url>
    cd <project_directory>
    ```
2.  **Install Dependencies**

    Install PHP dependencies:

    ```bash
    composer install
    ```

3.  **Environment Configuration**

    -   Copy the `.env.example` file to `.env`:

        ```bash
        cp .env.example .env
        ```

    -   Update the `.env` file with your database credentials and other necessary settings:

        ```env
        DB_CONNECTION=mysql
        DB_HOST=127.0.0.1
        DB_PORT=3306
        DB_DATABASE=your_database_name
        DB_USERNAME=your_database_user
        DB_PASSWORD=your_database_password
        ```

4.  **Generate Application Key**

    ```bash
    php artisan key:generate
    ```

5.  **Set Up the Database**

    -   Create a database in your database server.
    -   Run migrations to set up the tables.

        ```bash
        php artisan migrate
        ```

    -   Optionally you can run seeders.

        ```bash
        php artisan db:seed
        ```

6.  **Generate OAuth Keys using passport**

    ```bash
    php artisan passport:keys
    ```

7.  **Run server**

    ```bash
    php artisan serve
    ```

Server is hosted at `http://localhost:8000/`.

### Xampp Setup Instructions

1. **Download and Install XAMPP**

-   Download the XAMPP installer for your operating system from the official [Apache Friends website](https://www.apachefriends.org/).
-   Run the installer and follow the setup wizard to install XAMPP.
-   On Windows, install it at C:\xampp (default location).
-   On Mac, XAMPP installs to /Applications/XAMPP.

2. **Open the XAMPP Control Panel**

-   On Windows: Launch it from the start menu or the installation directory.
-   On Mac: Open the XAMPP application.
-   Start the Apache and MySQL modules by clicking Start next to each.

3. **Set Up a Database**

-   Open your browser and go to http://localhost/phpmyadmin.
-   Click on the Databases tab.
-   Create a new database:
-   Enter a name (e.g., smart_yatra_backend) in the "Database name" field.
-   Choose utf8mb4_general_ci as the collation (optional but recommended).
-   Click Create.

### Troubleshooting Port Conflicts

If Apache or MySQL doesn't start, it might be due to a **port conflict** (e.g., another application is using the default port). You can resolve this as follows:

#### Change Apache Port:

1. Open the XAMPP Control Panel.
2. Click **Config** next to Apache and select `httpd.conf`.
3. Change `Listen 80` to `Listen 8080` and `ServerName localhost:80` to `ServerName localhost:8080`.
4. Save the file and restart Apache.

#### Change MySQL Port:

1. Click **Config** next to MySQL and select `my.ini`.
2. Change `port=3306` to `port=3307`.
3. Save the file and restart MySQL.

#### Access URLs:

-   For Apache: Use `http://localhost:8080` in your browser.
-   For phpMyAdmin: Use `http://localhost:8080/phpmyadmin`.

### Xampp Setup Instructions

This guide will help you set up Apache, MySQL, and phpMyAdmin using Docker for local development.

**Prerequisites**

Make sure you have the following installed on your machine:

1. Docker: [Download Docker](https://www.docker.com/products/docker-desktop/)
2. Docker Compose: [Install Docker Compose](https://docs.docker.com/compose/install/)

## Setup

1. **Clone the repository and navigate to the project directory.**
2. **Change `docker-compose.yml` file**

    - Customize MySQL Credentials (Optional)

    ```docker-compose
    db:
      image: mysql:8.2
      container_name: mysql-container-sy
      environment:
        MYSQL_ROOT_PASSWORD: rootpassword  # Set a new password for the MySQL root user here
        MYSQL_DATABASE: mydb               # Set the database name you want to create
        MYSQL_USER: user                   # Set a new username here (optional)
        MYSQL_PASSWORD: userpassword       # Set a new password for the new user (optional)
        ports:
            - "3306:3306"  # Expose MySQL on port 3306
        volumes:
            - mysql-data:/var/lib/mysql
    ```

    - Change the path to your project for apache container

    ```docker-compose
    web:
    image: php:8.2-apache
    container_name: apache-container-sy
    depends_on:
      - db
    ports:
      - "8080:80"
    volumes:
      - path to your project #change here
    environment:
      APACHE_DOCUMENT_ROOT: /var/www/html/public
    ```

3. **Start the Services Using Docker Compose**

-   docker-compose.yml file is already included in the repository, you can directly run the following command to build and start the containers:

    ```bash
    docker-compose up -d
    ```

This will:

-   Pull the necessary Docker images (Apache, MySQL, phpMyAdmin).
-   Start the services as containers.

#### Access URLs:

-   For Apache: Use `http://localhost:8080` in your browser.
-   For phpMyAdmin: Use `http://localhost:8081`.
