## About UiPath Watcher

UiPath Watcher is a web application designed in order to monitor UiPath Robots. It allows to create rules & trigger alerts on-top of UiPath Orchestrator & ElasticSearch APIs.
It’s easy to use, open-source & created by an automation specialist for automation specialists.

## Install UiPath Watcher

### 1. Requirements
#### 1.1. Apache version
This project has been developed using Apache 2.4.38+ web server.

#### 1.2. PHP version
This project has been developed using PHP 7.3.3+.
Following PHP extensions needs to be installed:
- bcmath
- bz2
- calendar
- ctype
- curl
- dba
- dom
- enchant
- exif
- fileinfo
- ftp
- gd
- gettext
- iconv
- intl
- json
- ldap
- mbstring
- mongodb
- mysqli
- mysqlnd
- oci8.a
- oci8
- odbc
- pdo_mysql
- pdo_oci.a
- pdo_oci
- pdo_odbc
- pdo_pgsql
- pdo
- pdo_sqlite
- pgsql
- phar
- posix
- pspell
- shmop
- simplexml
- snmp
- soap
- sockets
- sqlite3
- sysvmsg
- sysvsem
- sysvshm
- tokenizer
- wddx
- xmlreader
- xmlrpc
- xml
- xmlwriter
- xsl
- zip

#### 1.3. MariaDB version
This project has been developed using MariaDB 10.2.22+ as database.
You need to create an empty database named uipath_watcher.

#### 1.4. Compatible WEB browsers
This project has been tested on Mozilla Firefox, Google Chrome & Microsoft Edge.

### 2. Configuration
#### 2.1. Environment file
A default environment file comes with the web application: .env.example.ini. Copy this file or rename it to .env.

`cp .env.example.ini .env`

Here are the values you may need to update according to your installation:
- APP_ENV, possible values are local, staging (test environment) & production, choose the value that suits your installation mode
- APP_URL, you should put the URL of the server where UiPath Watcher is deployed
- ASSET_URL, same as APP_URL
- DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME & DB_PASSWORD are regarding database configuration, put the values according to your MariaDB installation

#### 2.2. Application deployment
After configuring environment variables you'll have to execute some php commands to deploy the application:

`php artisan migrate --seed --force`

`php artisan passport:install`

`php artisan key:generate`

That's it, you're UiPath Watcher instance is installed.

## Use UiPath Watcher

A user guide is available inside the project files. You'll find it here: https://github.com/masiref/UiPathWatcher/blob/main/UiPath%20Watcher%20-%20User%20guide.pdf.

## Support
Github: [https://github.com/masiref/UiPathWatcher](https://github.com/masiref/UiPathWatcher)
Email: masire.fofana@natixis.com

## License

UiPath is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
