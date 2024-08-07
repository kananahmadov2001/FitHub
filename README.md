<div align="center">
    <h1 id="Header">FitHub</h1>
</div>

FitHub is a fitness management website where users can explore targeted workout plans for specific body areas, create personal accounts, log in, register, design and share their weekly workout routines. Designing and sharing workout plans with other users can be found in `plan.php`. This functionality basically copies all the selected workout plans from one user to another. The basics steps are:
* Form Handling: When a POST request is made with the action set to 'share', the script processes the request to share the current user's workout plans with another user.
* Fetching the Target User: The target user, referred to as sharedWith, is retrieved from the incoming request data ($input['shared_with']).
* Deleting Existing Plans: Before sharing the plans, the script deletes any existing plans that the target user ($sharedWith) might have.
* Copying Plans to the Target User: The script then retrieves all workout plans belonging to the current user ($userId). It iterates over each plan and inserts a new document into the collection with the username field set to the target user's username ($sharedWith).


<div align="center">
    <h2 id="Header">Homepage</h2>
</div>

[Homepage Link] (http://ec2-18-117-107-39.us-east-2.compute.amazonaws.com/~Gokuf/M7/orig-fithub/client/public/login.php)

<div align="center">
    <h2 id="Header">Login Details</h2>
</div>

When you navigate to the home page, use the following login credentials.

* Username(s): "abc" ; 

* Password(s): \"3tc!\" ;

Or you can create your own user by simply typing the username and password, then clicking on Register. Or you can Continue as Guest, but you won't have access to 'Build your plan' and 'Challenge' features.

<div align="center">
    <h2 id="Header">Technologies/Frameworks Used:</h2>
</div>

- HTML/CSS & JavaScript for front-end
- PHP for backend
- MongoDB for database

<div align="center">
    <h2 id="Header">MongoDB Setup</h2>
</div>

In order to set up MongoDB to be used for this project (assuming steps for conifugring PHP and Apache are followed from CS330 Wiki, as well as the instance being used is Amazon Linux 2023), one must run the following set of commands:
```bash
# Composer Setup
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('sha384', 'composer-setup.php') === 'dac665fdc30fdd8ec78b38b9800061b4150413ff2e3b6f88543c636f7cd84f6db9189d43a81e5503cda447da73c7e5b6') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php
php -r "unlink('composer-setup.php');"

# PECL setup
sudo yum install php-pear
sudo yum install php-devel

# Mongo setup
sudo nano /etc/yum.repos.d/mongodb-org-7.0.repo

    ## add following to the above new file: 
    [mongodb-org-7.0]
    name=MongoDB Repository
    baseurl=https://repo.mongodb.org/yum/amazon/2023/mongodb-org/7.0/x86_64/
    gpgcheck=1
    enabled=1
    gpgkey=https://pgp.mongodb.com/server-7.0.asc

sudo yum install -y mongodb-org
sudo systemctl start mongod
sudo systemctl enable mongod

# Clean up and make sure all is good
composer dump-autoload
composer require mongodb/mongodb
composer update

```

<div align="center">
    <h2 id="Header">Features</h2>
</div>

### Creative Feature: Challenge Activity
"Challenge" feature allows users to track their fitness activities and progress in a gamified manner. Users can select different workout types, specify the duration of each workout, and accumulate points based on their activities. The goal is to reach a specified number of points (60 points in this case), encouraging users to stay active. For this feature, I utilized a combination of PHP for backend processing and JavaScript for dynamic frontend updates. I also used sessions for tracking progress, AJAX for seamless updates, and visual feedback through a progress bar and congratulatory messages and effects enhances the user experience, making it both interactive and motivating. 

Here is the more in-depth analysis of 'Challenge' feature and steps in the `challenge.php`:
* Session Management and Initialization.
* User Identification: checking the username stored in the session. If no username is available, it defaults to 'Guest'.  
* Handling Form Submissions and AJAX Requests: updating and resetting progress.
* Form for Data Submission: capturing the workout type and duration from the user and triggering an AJAX request upon submission.
* Progress Bar and Points Display: displaying the user's progress and total points. The progress bar visually represents the percentage of the goal achieved.
* JavaScript for Dynamic Updates: updating the progress and points display, and showing the congratulatory message. The fetch API is used for AJAX requests, ensuring the page does not reload upon submission or reset.
* Confetti Effect: implemented using JavaScript and CSS. It involves dynamically creating and animating confetti elements on the screen when the user reaches the target points.

<div align="center">
    <h2 id="Header">Page Visuals</h2>
</div>

<p align="center" width="100%">
    <img width="40.5%" src="https://github.com/user-attachments/assets/7b77a02a-0f3c-4900-82ee-41bac5f112d7"> 
    <img width="39.2%" src="https://github.com/user-attachments/assets/6f06097a-e458-4494-8af4-69a52bad0c95"> 
    <img width="40.5%" src="https://github.com/user-attachments/assets/164e83a5-d19a-4e75-8e1c-66550b31e860"> 
    <img width="39.2%" src="https://github.com/user-attachments/assets/1bc00b78-3050-4329-a1c2-cfdf5b6553ab"> 
    <img width="40.5%" src="https://github.com/user-attachments/assets/a0e9d5a4-c1d9-4023-a12b-6d2a87a79eb9"> 
</p>
