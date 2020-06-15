# ![](/public/images/favicon/favicon-32x32.png) TETHYS

TETHYS - Data Publisher for Geoscience Austria is a digital data library and a data publisher for earth system science. Data can be georeferenced in time (date/time) and space (latitude, longitude, depth/height). 

----------

# Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes. See deployment for notes on how to deploy the project on a live system.

## Installation

Please check the official laravel installation guide for server requirements before you start. [Official Documentation](https://laravel.com/docs/6.x/installation#installation)


Clone the repository

    git clone git@github.com:geolba/tethys.git

Switch to the repo folder

    cd tethys-app

Install all the dependencies using composer

    composer install --optimize-autoloader --no-dev

Copy the example env file and make the required configuration changes in the .env file

    cp .env.example .env

Generate a new application key

    php artisan key:generate

Run the database migrations (**Set the database connection in .env before migrating**)

    php artisan migrate

Grant folder permissions (**Set the database connection in .env before migrating**)

    sudo chgrp -R www-data storage bootstrap/cache
    sudo chmod -R ug+rwx storage bootstrap/cache

Start the local development server

    php artisan serve


## Environment variables

- .env - Environment variables can be set in this file

***Note*** : You can quickly set the database information, the solr connection string and other variables in this file and have the application fully working.

----------

## Versioning

For the versions available, see the [tags on this repository](https://github.com/geolba/tethys/tags). 

## Authors

* **Arno Kaimbacher** - *Initial work* 

See also the list of [contributors](https://github.com/geolba/tethys/contributors) who participated in this project.

## License

This project is licensed under the MIT License - see the [license](LICENSE) file for details


