# AdminTemplate
------------
This is admin template for laravel.


#Requirements
------------
 - PHP >= 7.0.0
 - Laravel >= 5.5.0


#Installation
------------

First, install laravel 5.5, and make sure that the database connection settings are correct.

```
composer require kamruljpi/admintemplate
```

Then run these commands to publish assets and config：


```
php artisan vendor:publish --provider="kamruljpi\admintemplate\providers\AdminTemplateServiceProvider" --tag=public --force

```

After run command you can find assets file in your base folder/media

```
php artisan vendor:publish --provider="kamruljpi\admintemplate\providers\AdminTemplateServiceProvider" --tag=config --force

```
After run command you can find config file in `config/admintemplate.php`, in this file you can change the configuration of this packages.


#Configurations
------------
The file `config/admintemplate.php` contains an array of configurations, you can find the default configurations in there.