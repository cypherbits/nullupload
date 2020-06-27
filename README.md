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