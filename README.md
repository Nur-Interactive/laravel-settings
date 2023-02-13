## Laravel Settings

Use `nurinteractive/laravel-settings` to store key value pair settings in the database.

> All the settings saved in db is cached to improve performance by reducing sql query to zero.

### Installation

**1** - Add this repository to your composer.json

```bash
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/Nur-Interactive/laravel-settings"
        }
    ],
```


**2** - You can install the package via composer:

```bash
$ composer require nurinteractive/laravel-settings
```

**3** - If you are installing on Laravel 5.4 or lower you will be needed to manually register Service Provider by adding it in `config/app.php` providers array and Facade in aliases arrays.

```php
'providers' => [
    //...
    Nurinteractive\Settings\SettingsServiceProvider::class
]

'aliases' => [
    //...
    "Settings" => Nurinteractive\Settings\Facade::class
]
```

In Laravel 5.5 or above the service provider automatically get registered and a facade `Setting::get('app_name')` will be available.

**4** - Now run the migration by `php artisan migrate` to create the settings table.

Publish the assets file by running:

```
php artisan vendor:publish --provider="Nurinteractive\Settings\SettingsServiceProvider"
```

### Getting Started

You can use helper function `settings('app_name')` or `Settings::get('app_name')` to use laravel settings.

### Available methods

```php
// Pass `true` to ignore cached settings
settings()->all($fresh = false);

// Get a single setting
settings()->get($key, $default = null);

// Set a single setting
settings()->set($key, $value, $secret=false);

// Set a multiple settings
settings()->set([
   'app_name' => 'Nurinteractive',
   'app_email' => 'info@email.com',
   'app_type' => 'SaaS'
]);

// check for setting key
settings()->has($key);

// remove a setting
settings()->remove($key);
```

### Groups

From `v 1.0.6` You can organize your settings into groups. If you skip the group name it will store settings with `default` group name.

> If you are updating from previous version dont forget to run the migration

You have all above methods available just set you working group by calling `->group('group_name')` method and chain on:

```php
settings()->group('team.1')->set('app_name', 'My Team App', true);
settings()->group('team.1')->get('app_name');
> My Team App

settings()->group('team.2')->set('app_name', 'My Team 2 App' , true);
settings()->group('team.2')->get('app_name');
> My Team 2 App

// You can use facade
\Settings::group('team.1')->get('app_name')
> My Team App
```

### Testing

The package contains some integration/smoke tests, set up with Orchestra. The tests can be run via phpunit.

```bash
$ composer test
```
