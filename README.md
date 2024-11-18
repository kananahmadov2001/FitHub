[![Review Assignment Due Date](https://classroom.github.com/assets/deadline-readme-button-22041afd0340ce965d47ae6ef1cefeee28c7c493a6346c4f15d667ab976d596c.svg)](https://classroom.github.com/a/pzGH3rCW)
# CSE330-Group

Kanan Ahmadov - 517599 - kananahmadov2001

Carmen Bland Jr - 511067 - washu-blandjr

Approved by Logan

## FitHub Fitness App 

FitHub is a fitness management website where users can explore targeted workout plans for specific body areas, create personal accounts, log in, register, and design their weekly workout routines.

### Homepage
[Homepage Link] (http://ec2-18-117-107-39.us-east-2.compute.amazonaws.com/~Gokuf/M7/orig-fithub/client/public/login.php)

### Login Details

When you navigate to the home page, use the following login credentials.

* Username(s): "abc" ; 

* Password(s): \"3tc!\" ;

Or you can create your own user by simply typing the username and password, then clicking on Register. Or you can Continue as Guest, but you won't have access to 'Build your plan' and 'Challenge' features.

### Technologies/Frameworks Used:
- HTML/CSS & JavaScript for front-end
- PHP for backend
- MongoDB for database

### MongoDB Setup

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

### Project Repo-File Analysis

Folder system has been modeled after other projects found on GitHub:
- client holds the underlying html pages
- commons holds the assets used such as the pictures used, flashcards (in the advertisement.php), and stylesheets. It also will includes our js.
- .env holds enviroment variables (if needed) 

### Creative Portion

<strong>Additional Feature #1: Challenge Activity</strong> <br><br>
For the Creative portion of this project, we implemented a "Challenge" feature which allows users to track their fitness activities and progress in a gamified manner. Users can select different workout types, specify the duration of each workout, and accumulate points based on their activities. The goal is to reach a specified number of points (60 points in this case), encouraging users to stay active. For this feature, we utilized a combination of PHP for backend processing and JavaScript for dynamic frontend updates. We also used sessions for tracking progress, AJAX for seamless updates, and visual feedback through a progress bar and congratulatory messages and effects enhances the user experience, making it both interactive and motivating. 

Here is the more in-depth analysis of 'Challenge' feature and steps in the `challenge.php`:
* Session Management and Initialization.
* User Identification: checking the username stored in the session. If no username is available, it defaults to 'Guest'.  
* Handling Form Submissions and AJAX Requests: updating and resetting progress.
* Form for Data Submission: capturing the workout type and duration from the user and triggering an AJAX request upon submission.
* Progress Bar and Points Display: displaying the user's progress and total points. The progress bar visually represents the percentage of the goal achieved.
* JavaScript for Dynamic Updates: updating the progress and points display, and showing the congratulatory message. The fetch API is used for AJAX requests, ensuring the page does not reload upon submission or reset.
* Confetti Effect: implemented using JavaScript and CSS. It involves dynamically creating and animating confetti elements on the screen when the user reaches the target points.

<strong>Additional Feature #2: Sharing Workout Plans</strong> <br><br>
The another creative feature we implemented was sharing workout plans with other users which can be found in `plan.php`. This functionality basically copies all the selected workout plans from one user to another. The basics steps are:
Form Handling: When a POST request is made with the action set to 'share', the script processes the request to share the current user's workout plans with another user.
* Fetching the Target User: The target user, referred to as sharedWith, is retrieved from the incoming request data ($input['shared_with']).
* Deleting Existing Plans: Before sharing the plans, the script deletes any existing plans that the target user ($sharedWith) might have.
* Copying Plans to the Target User: The script then retrieves all workout plans belonging to the current user ($userId). It iterates over each plan and inserts a new document into the collection with the username field set to the target user's username ($sharedWith).
