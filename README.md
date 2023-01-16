## How to set up and run this application

This application is built with Laravel 9 and it requires a PHP version of 8 and above.
  
Follow these steps to run this app:  
1)copy .env.example to .env  
2)run 'composer install' command  
3)run 'php artisan key:generate'  
4)create a mysql database called 'task_api'  
5)run 'php artisan migrate --seed'  
6)run 'php artisan serve' to run the application on the PHP dev server

Now this application should be running on http://localhost:8000/
