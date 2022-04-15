<h2 align="center">How to Deploy a project in local Machine</h2>



## 1. Clone GitHub repo for this project locally

 This project is hosted on github, we can use git on your local computer to clone it from github onto your local computer.

Note: Make sure you have git installed locally on your computer first.

Find a location on your computer where you want to store the project. In my case I like all my projects to be a folder called sites/, so that is where I run the following command, which will pull the project from github and create a copy of it on my local computer at the sites directory inside another folder called “matresstodoor”. 
<h5><b>command</b>: git clone https://github.com/netraclos/matresstodoor.git.</h5>
    
Once this runs, you will have a copy of the project on your computer.

## 2. cd into your project

You will need to be inside that project file to enter all of the rest of the commands. So remember to type **cd matresstodoor** to move your terminal working location to the project file we just barely created. (Of course substitute “matresstodoor” in the command above, with the name of the folder you created in the previous step).

### 3. Install Composer Dependencies

Whenever you clone a new this project you must now install all of the project dependencies. This is what actually installs Laravel itself, among other necessary packages to get started.
**Note:** Apache serve is must to run this code(Window:**Xampp**, Ubuntu:**LAMP**, Mac:**MAMP**).Also composer installation is must.<a href="https://getcomposer.org/doc/00-intro.md#installation-windows">Install Composer</a>

When we run composer, it checks the **composer.json** file which is submitted to the github repo and lists all of the composer (PHP) packages that your repo requires. Because these packages are constantly changing, the source code is generally not submitted to github, but instead we let composer handle these updates. So to install all this source code we run composer with the following command.<br />
**composer install**

## 4. Install NPM Dependencies

Just like how we must install composer packages to move forward, we must also install necessary NPM packages to move forward. This will install  Bootstrap.css, Lodash, and Laravel Mix.<br>
**Note:** Node js is required for NPM.<a href="https://nodejs.org/en/download/">Download Node</a>

This is just like step 3, where we installed the composer PHP packages, but this is installing the Javascript (or Node) packages required. The list of packages that a repo requires is listed in the packages.json file which is submitted to the github repo. Just like in step 3, we do not commit the source code for these packages to version control (github) and instead we let NPM handle it.<br>
First run **npm install**<br>
Then run **npm run dev**

## 5. Create a copy of your .env file

**.env** files are not generally committed to source control for security reasons. But there is a **.env.example** which is a template of the **.env** file that the project expects us to have. So we will make a copy of the **.env.example** file and create a .env file that we can start to fill out to do things like database configuration in the next few steps.<br>
**cp .env.example .env**<br/>
This will create a copy of the **.env.example** file in your project and name the copy simply **.env**.

## 6. Generate an app encryption key

Laravel requires you to have an app encryption key which is generally randomly generated and stored in your **.env** file. The app will use this encryption key to encode various elements of your application from cookies to password hashes and more.

Laravel’s command line tools thankfully make it super easy to generate this. In the terminal we can run this command to generate that key. (Make sure that you have already installed Laravel via composer and created an **.env** file before doing this, of which we have done both).
**php artisan key:generate**

## 7. Create an empty database for our application
Create an empty database for your project using the database tools  (Mysql or Phpmyadmin). In our example we created a database called **“mtd”**. Just create an empty database here, the exact steps will depend on your system setup.
## 8. In the .env file, add database information to allow Laravel to connect to the database
We will want to allow Laravel to connect to the database that you just created in the previous step. To do this, we must add the connection credentials in the .env file and Laravel will handle the connection from there.

In the **.env** file fill in the **DB_HOST(127.0.0.1)**, **DB_PORT(3306), DB_DATABASE(mtd), DB_USERNAME(root), and DB_PASSWORD**(Your mysql password if you have otherwise left empty) options to match the credentials of the database you just created. This will allow us to run migrations and seed the database in the next step.
## 9. Migrate the database
Once your credentials are in the **.env** file, now you can migrate your database<br>
**php artisan migrate**<br>
then run **php artisan db:seed** <br>
Or import the **sql file** that we will provide.
## 10. Run the Project
Run **php artisan serve** from terminal.<br>
**You can now access your project at** <a href="http://localhost:800">localhost:8000</a> :)

**NOTE:** Make sure that your php is globally work in your window .<a href="https://dinocajic.medium.com/add-xampp-php-to-environment-variables-in-windows-10-af20a765b0ce">
    Add XAMPP PHP to Environment Variables in Windows 10</a>


