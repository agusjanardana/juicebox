# Laravel Developer Test Code

Created using Laravel v.11.23.5

# Init

1. Install all dependencies with composer install
2. Migrate all migration with php artisan migrate
3. Setting env for database MySQL
4. Create seeders with php artisan db:seed [SeederName; e.g NoteSeeder, UserSeeder]
5. Run with php artisan serve

# Folder Structure

Inside app, there will be important folder:

-   ./app/Repositories is folder that include only a databases query
-   ./app/Http is folder where all the logic, and handler.

This structure will seperate between handler query and handler http logic for better code quality.

# Queue Email

Since i don't have any smtp server and the time is not enough to set one, i set the queue to 5000 second, so we can notice there is a jobs that available in database table jobs.

For testing manual we cann test with : php artisan email:welcome test@mail.com

# Postman Colellection

Import file Laravel-Test.postman_collection.json to postman to see all the route.
