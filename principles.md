# Gazingle app main principles

## Relations
### Polymorphism
* All services in app should register a models mutations as separate entity.
* All Mutations has same structure
````javascript
var mutation = {
  name: String, // field key
  display_name: String, // Mutation display name
  type: String, // type of a mutated field. Example: select, input, text, date, etc.
  values: String, // Predefined values for a mutation
  nullable: Boolean, // Allows null on a field
  overrides: Boolean, // Determines if mutation is overriding an existing field, or creates a new one
  hidden: Boolean, // Visibility in responses
  searchable: Boolean // Determines if model can be found by this field
}
// Example Mutation object
var equipment_meta = {
  name: 'last_service_at',
  display_name: 'Last service at',
  type: 'date',
  values: '',
  nullable: true,
  overrides: false, 
  hidden: false 
}
````

* All Mutations can inherit each other
* All entities in app should contain "meta" field, which is a storage of a model mutator data.
#####Meta object structure
````javascript
var meta = {
  key: String,
  value: String
}
````

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
