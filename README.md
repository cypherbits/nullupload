# nullupload.com PHP file hosting

**This is the source code of the free file hosting nullupload.com feel free to use to start your own file hosting. 
This file hosting is developed with security and privacy in mind.**

We will be using a Apache 2.4 MPM Event + PHP-FPM 7.3 + Mysql 8.0 stack.

We will be using Docker containers.

#### Setup:

1. Setup the database importing the setup.sql file.
2. Change the src/propelconfig.php file with your database settings and create a new file: 'propelconfig_pro.php' for your production server.
Configure the way it get if you are on localhost or production on the dependecies.php file.
3. Change src/settings.php settings.
4. Change classes/SessionHelper.php to set the admin username and password. (Username: cleartext, Password: hash sha512)
5. User Let's Encrypt and Strict HSTS to keep your site safe.
6. Default admin panel is in ./_superadmin you can change this easily on the src/controllers/admin.php