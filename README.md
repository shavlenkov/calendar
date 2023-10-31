# Calendar

### Tools for launching the project
To start the project, you will need:
1. PHP >= **8.2.6**
2. Laravel >= **10**
3. Composer >= **2.6.4**
5. Docker >= **24.0.6**
6. Docker Compose >= **1.29.2**

### How to launch the project?
1. Clone a repository:

   `git clone https://github.com/shavlenkov/calendar.git`
2. Go to the calendar folder:

   `cd calendar`
3. Make an .env file from the .env.example file:

   `cp .env.example .env`
4. Make the necessary configuration changes to the .env file:
```
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=
```
5. Install all dependencies using Composer:

   `composer install`

6. Run containers using Docker Compose:

   `docker-compose up -d`
7. Connect to the container:

   `docker exec -it  calendar_laravel.test_1 bash`
    1. Give the correct access rights to the bootstrap folder:

       `chmod -R 777 ./bootstrap ./storage`
    2. Generate App Key:

       `php artisan key:generate`
    3. Run migrations and seeders:

       `php artisan migrate --seed`
10. Open a browser and go to the address:
    [http://localhost/signin](http://localhost/signin "http://localhost/login/signin") and login to the site
