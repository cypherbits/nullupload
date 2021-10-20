## Maintenance mode. No new features expected. Centralized file hosting services will be replaced with decentralized, DAO, and crypto Web 3.0 soon, resistant to censorship with better privacy. There is no need to make a software like this file hosting anymore.

# nullupload.com PHP file hosting

**This is the source code of the free file hosting nullupload.com feel free to use to start your own file hosting. 
This file hosting is developed with security and privacy in mind.**

We will be using a Apache 2.4 MPM Event + >= PHP-FPM 7.3 + Mysql 8.0 stack.

We will be using Docker containers.

#### Setup:

1. Setup the database importing the setup.sql file.
2. Change src/settings.php settings.
2.1. Admin user is in plaintext, admin password should be bcrypt.
3. Change uploads/.htaccess file to reflect your directory.
4. Change classes/SessionHelper.php to set the admin username and password. (Username: cleartext, Password: hash sha512)
5. User Let's Encrypt and Strict HSTS to keep your site safe.
6. Setup a cron on your server to execute cron.sh (as web user like www-data) every hour.

### Files and folders
`_tcache` contains the Twig cache files.
`classes` contains our custom helpers classes.
`logs` contains our app log.
`public` is our public server folder. index and UI.
`templates` our UI/Twig templates.
`uploads` our uploaded files.

# Donate
https://ko-fi.com/cypherbits

Monero address:
`4BCveGZaPM7FejGkhFyHgtjVXZw52RrYxKs7znZdmnWLfB3xDKAW6SkYZPpNhqBvJA8crE8Tug8y7hx8U9KAmq83PwLtVLe`