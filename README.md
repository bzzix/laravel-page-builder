# laravel-grapesjs
This package provides an easy way to integrate GrapesJS into your Laravel project making it easy to build professional pages with drag and drop.
## Installation

## Step 1: Navigate to Your Project Directory

Before you begin, navigate to the directory where your Laravel project is located. Use the following command to change into your project directory:

```bash
cd path/to/your/laravel-project
```

## Step 2: Installation

You can install this library using Composer. Run the following command:

```bash
composer require bzzix/laravel-page-builder
```

## Step 3: Register the ServiceProvider

Register the `PageBuilderServiceProvider` in your Laravel project. Open the `config/app.php` file and add the following line to the `providers` array:

```php
// config/app.php

'providers' => ServiceProvider::defaultProviders()->merge([
    /*
        * Package Service Providers...
    */
    // Other providers...
    Bzzix\PageBuilder\PageBuilderServiceProvider::class,
])->toArray(),
```

## Step 4: Dump Autoload Files
After registering the ServiceProvider, run the following command to re-generate Composer's autoloader files:

```bash
composer dump-autoload
```

## Step 5: Publish Configuration, Assets, and Migrations
To publish the configuration files, assets, and migrations provided by this package, run:

```bash
php artisan vendor:publish --provider="Bzzix\PageBuilder\PageBuilderServiceProvider"
```

This will deploy:
- `asset` files inside the public folder (`bzzix-pagebuilder`)
- the config file inside the `config` folder
- the migration file inside the `database/migrations` folder

## Step 6: Configure Basic Settings
To start using this package, follow these steps to configure the basic settings:

Open the `bzzix-pagebuilder.php` file inside the `config` folder. Adjust the basic settings according to your requirements.