<!--<p align="center">
<a href="https://github.com/wire-elements/modal/actions"><img src="https://github.com/wire-elements/modal/workflows/PHPUnit/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/wire-elements/modal"><img src="https://img.shields.io/packagist/dt/wire-elements/modal" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/livewire-ui/modal"><img src="https://img.shields.io/packagist/dt/livewire-ui/modal" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/wire-elements/modal"><img src="https://img.shields.io/packagist/v/wire-elements/modal" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/wire-elements/modal"><img src="https://img.shields.io/packagist/l/wire-elements/modal" alt="License"></a>
</p>
-->

## About Admin Panel
Admin Panel is a TALL based solution to be able to administer users, roles and permissions. It requires several composer packages:

| Package                            | Version   |
|------------------------------------|-----------|
| `laravel/jetstream`                | `^2.10`   |
| `livewire/livewire`                | `^2.5`    |
| `spatie/laravel-permission`        | `^5.5`    |
| `rappasoft/laravel-livewire-tables`| `^v2.7.0` |
| `wire-elements/modal`              | `^1.0.7`  |

These are all required dependencies that will be installed if needed.

## Installation
To get started, add a local repository to Composer:
```php
[...]
"repositories": {
        "admin-panel": {
            "type": "path",
            "url": "/path-to-downloaded-file/fefo-p/admin-panel",
            "options": {
                "symlink": true
            }
        }
    },
[...]
```

Then, require the package via Composer:
```
composer require fefo-p/admin-panel
```
> **Warning**
> 
> You might want to publish the config and migrations before running the install command as will automatically migrate the DB.
> 
> To find out the available options to publish, just issue a 
> ```php
> php artisan vendor:publish
> ```
> and check for every entry with *adminpanel*
> 
>If in doubt, [check laravel's artisan cli documentation](https://laravel.com/docs/9.x/artisan#options)
> 
> Seriously, you've been warned...

## Initial setup

Execute the following command to do the initial setup.
```php
php artisan adminpanel:install
```

It will create one role `administrador` with three permissions:
- administrar usuarios
- administrar roles
- administrar permisos

There are a few options available with the installation command, but not required

`--profile-image`
Updates jetstream to use profile images and publishes a default anonymous profile image

`--verification`
Makes the User model implement MustVerifyEmail, as well as allow email verification feature in fortify,

`--with-user`
Creates a default admin user<br>

> **Note:**
> 
> **TODO:**
> *ask for user details while installing*

```shell
 php artisan adminpanel:install [--with-user] [--profile-image] [--verification]
```

## Assets
You can publish all assets issuing the command, or just select individually the assets you want to publish
```php
php artisan vendor:publish --tag=adminpanel
```

## Configuration
You can customize the Modal via the `adminpanel.php` config file. This includes some additional options like ...

To publish the config run the vendor:publish command:
```shell
php artisan vendor:publish --tag=adminpanel-config
```

```php
<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Include CSS
    |--------------------------------------------------------------------------
    |
    | The modal uses TailwindCSS, if you don't use TailwindCSS you will need
    | to set this parameter to true. This includes the modern-normalize css.
    |
    */
    'include_css' => false,


    /*
    |--------------------------------------------------------------------------
    | Include JS
    |--------------------------------------------------------------------------
    |
    | Livewire UI will inject the required Javascript in your blade template.
    | If you want to bundle the required Javascript you can set this to false
    | and add `require('vendor/wire-elements/modal/resources/js/modal');`
    | to your script bundler like webpack.
    |
    */
    'include_js' => true,


    /*
    |--------------------------------------------------------------------------
    | Modal Component Defaults
    |--------------------------------------------------------------------------
    |
    | Configure the default properties for a modal component.
    | 
    | Supported modal_max_width
    | 'sm', 'md', 'lg', 'xl', '2xl', '3xl', '4xl', '5xl', '6xl', '7xl'
    */
    'component_defaults' => [
        'modal_max_width' => '2xl',
        
        'close_modal_on_click_away' => true,

        'close_modal_on_escape' => true,

        'close_modal_on_escape_is_forceful' => true,

        'dispatch_close_event' => false,
        
        'destroy_on_close' => false,
    ],
];
```

## Credits
- [Fernando M. Pintabona](https://github.com/fefo-p)

## License
Admin Panel is open-sourced software licensed under the [MIT license](LICENSE.md).