<p align="center"><img src="https://raw.githubusercontent.com/infinitypaul/carcrash/master/public/clogo.png" /></p>
<p align="center">Business Date</p>
<p align="center"><a href="#">Creator</a> | I Craft Ideas Into Realities</p>

<p>&nbsp;</p>

## Download Instruction

> Application Requirements
* PHP >= 7.1.3
* OpenSSL PHP Extension
* PDO PHP Extension
* Mbstring PHP Extension

1. Clone the project.

```
git clone https://github.com/infinitypaul/businessdate.git projectname
```

2. Install dependencies via composer.

```
composer install 
```

4. Run php server.

```
php artisan serve
```

<p>&nbsp;</p>

> You can also install the Application via Docker:

## Pre-requisites

- Docker running on the host machine.
- Docker compose running on the host machine.

1. Clone the project.

```
git clone https://github.com/infinitypaul/businessdate.git projectname
```

2. Run the testrig.sh file on the Project Root Folder on your terminal/Command Prompt, This script does everything you need to run your this project. It starts up the servers, ensures the database is booted, installs dependencies. These services are exposed to your computer on the standard ports, then you can access your website on http:localhost

## Troubleshooting

- Port number might be already in use, change from `80` to another number in your `docker-compose.yml` file.
- If you have any other issues, [report them](https://github.com/infinitypaul/carcrash/issues).

Enjoy!


## Api Usage

> Using Get Request:

```
GET http://localhost/api/v1/businessDates/getBusinessDateWithDelay/<INITIAL DATE>/<DELAY>
```

> Using Post Request:

```
POST http://localhost/api/v1/businessDates/getBusinessDateWithDelay/
```

Where:
* http://localhost/ is your Base Url, You Can Replace it with yours
* INITIAL DATE, DELAY  are variables that are used when calling the Date API. Example values for these are:
* INITIAL DATE: 2018-5-14T10:10:10Z  DELAY: 3

> Is Business Day:

Check if a date is a business date

```
GET http://localhost/api/v1/businessDates/isBusinessDay/<DATE>
```

### License

The  Find Your Service App is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)



