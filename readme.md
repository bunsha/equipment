# Gazingle Equipment 

## Official Documentation
### Requirement

* PHP >=7.1.3
    * OpenSSL PHP Extension
    * PDO PHP Extension
    * Mbstring PHP Extension
* MySQL >=5.7
* Redis >= 3.2

### Installation

* Clone this repo
* Navigate to a project dir
* Setup permissions
```shell
sudo chown -R www-data:www-data .
sudo find . -type f -exec chmod 644 {} \
sudo find . -type d -exec chmod 755 {} \
sudo chgrp -R www-data storage bootstrap
sudo chmod -R ug+rwx storage bootstrap
```
* Create .env file
```shell
@php -r "file_exists('.env') || copy('.env.example', '.env');" 
```
* Edit .env file
* Update dependencies
``` 
composer update
composer dump-autoload
```

* Run from command line 
``` php artisan migrate:fresh --seed ```

## License

The Gazingle Equipment is NOT open-sourced software NOT licensed under the [MIT license](https://opensource.org/licenses/MIT).
