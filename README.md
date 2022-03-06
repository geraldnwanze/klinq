KLINQ
=====

Klinq is an online web app for crypto exchange without limitations from any country

## Installation

>1. git clone https://github.com/geraldnwanze/klinq
>2. cd into the folder
>3. composer install
>4. create database
>5. open .env file and set QUEUE_CONNECTION AND QUEUE_DRIVER to database
>6. run *php artisan migrate*
>7. run *php artisan queue:work*
>8. don't forget to update your smtp settings