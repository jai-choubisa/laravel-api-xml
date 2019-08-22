# laravel-api-xml
Demo work on Laravel 5.8 framework of PHP. Build Two API's with xml request body and xml response. Unit Testing also done with dummy data using Faker library.

## About this Repo
Demo work on Laravel 5.8 framework of PHP. Build Two API's with xml request body and xml response. Unit Testing also done with dummy data using Faker library.

## Before Installation
1. Install Composer ( https://getcomposer.org/download/ )
2. Install Laravel 5.8 ( https://laravel.com/docs/5.8/installation )

## Installation

1. Clone the repo and cd into it
2. composer install
3. php artisan serve or use Laravel Valet or Laravel Homestead
4. Use Post API's 
    a. http://localhost:8000/api/ping_request For PING REQUEST with xml body content 
    b. http://localhost:8000/api/reverse_request For Reverse Request with xml body content.

## Code Details

1. Routing - <b>/routes/api.php</b> . I have developed a two post requests (/api/ping_request and /api/reverse_request).

2. Controller - <b>/app/Http/Controllers/ApiController</b> - 
    a. pingRequest() function - Handle Ping Request 
    b. reverseRequest() function - Handle Reverse Request

3. Testing - <b>/test/Unit/ApiTest.php</b> I have done Unit Testing using built in PHPUnit of laravel. To run it from terminal call <i>./vendor/phpunit/phpunit/phpunit</i> from root folder path.


## License

The Laravel framework is open-source software licensed under the [MIT license](https://opensource.org/licenses/MIT).
