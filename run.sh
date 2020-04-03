# JWT related task
generate_jwt_secret()
{
    yes | php artisan jwt:secret
}
php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"
generate_jwt_secret || true

# DB ralated task
composer update
composer dump-autoload
php artisan migrate
php artisan db:seed

# Run apps
php-fpm
