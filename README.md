<div align="center">
    <h1 id="Header">FitHub</h1>
</div>

## Overview
FitHub is a fitness management website where users can explore targeted workout plans for specific body areas, create personal accounts, log in, register, and design their weekly workout routines.

## Homepage
Homepage: [FitHub Homepage](http://ec2-18-117-107-39.us-east-2.compute.amazonaws.com/~Gokuf/M7/orig-fithub/client/public/login.php)

## Login Details
Use the following login credentials:

| Username | Password |
|----------|----------|
| abc      | 3tc!     |

Alternatively, you can create your own user by entering a username and password and clicking **Register**. You can also continue as a guest, but you won't have access to the **Build Your Plan** and **Challenge** features.

## Technologies/Frameworks Used
- **Frontend**: HTML, CSS, JavaScript
- **Backend**: PHP
- **Database**: MongoDB

## Features
- **User Registration and Login**:
  - Users can securely register, log in, and manage accounts.
- **Workout Plan Management**:
  - Users can create, edit, and delete custom workout plans.
- **Challenge Activity**:
  - Users can track their fitness activities in a gamified manner. This feature includes:
    - Selecting workout types and specifying duration.
    - Accumulating points based on activity.
    - Tracking progress using a dynamic progress bar
    - Visual feedback with celebratory effects (e.g., confetti) when goals are achieved.
- **Sharing Workout Plans**:
  - Users can share their custom workout plans with other users. This involves:
    - Copying selected workout plans to another user's account.
    - Removing any existing plans for the target user before sharing.

Here is the more in-depth analysis of 'Challenge' feature and steps:
* "Challenge" feature allows users to track their fitness activities and progress in a gamified manner. Users can select different workout types, specify the duration of each workout, and accumulate points based on their activities. The goal is to reach a specified number of points (60 points in this case), encouraging users to stay active. For this feature, I utilized a combination of PHP for backend processing and JavaScript for dynamic frontend updates. I also used sessions for tracking progress, AJAX for seamless updates, and visual feedback through a progress bar and congratulatory messages and effects enhances the user experience, making it both interactive and motivating. 
* Session Management and Initialization.
* User Identification: checking the username stored in the session. If no username is available, it defaults to 'Guest'.  
* Handling Form Submissions and AJAX Requests: updating and resetting progress.
* Form for Data Submission: capturing the workout type and duration from the user and triggering an AJAX request upon submission.
* Progress Bar and Points Display: displaying the user's progress and total points. The progress bar visually represents the percentage of the goal achieved.
* JavaScript for Dynamic Updates: updating the progress and points display, and showing the congratulatory message. The fetch API is used for AJAX requests, ensuring the page does not reload upon submission or reset.
* Confetti Effect: implemented using JavaScript and CSS. It involves dynamically creating and animating confetti elements on the screen when the user reaches the target points.

Here is the more in-depth analysis of 'Sharing Workout Plans' feature and steps:
Form Handling: When a POST request is made with the action set to 'share', the script processes the request to share the current user's workout plans with another user.
* Fetching the Target User: The target user, referred to as sharedWith, is retrieved from the incoming request data ($input['shared_with']).
* Deleting Existing Plans: Before sharing the plans, the script deletes any existing plans that the target user ($sharedWith) might have.
* Copying Plans to the Target User: The script then retrieves all workout plans belonging to the current user ($userId). It iterates over each plan and inserts a new document into the collection with the username field set to the target user's username ($sharedWith).


## MongoDB Setup
To set up MongoDB for this project (assuming PHP and Apache are configured as per CS330 Wiki, and the instance is Amazon Linux 2023), run the following commands:

```bash
# Composer Setup
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('sha384', 'dac665fdc30fdd8ec78b38b9800061b4150413ff2e3b6f88543c636f7cd84f6db9189d43a81e5503cda447da73c7e5b6', 'composer-setup.php')) { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php
php -r "unlink('composer-setup.php');"

# PECL Setup
sudo yum install php-pear
sudo yum install php-devel

# MongoDB Setup
sudo nano /etc/yum.repos.d/mongodb-org-7.0.repo

# Add the following to the above new file:
# [mongodb-org-7.0]
# name=MongoDB Repository
# baseurl=https://repo.mongodb.org/yum/amazon/2023/mongodb-org/7.0/x86_64/
# gpgcheck=1
# enabled=1
# gpgkey=https://pgp.mongodb.com/server-7.0.asc

sudo yum install -y mongodb-org
sudo systemctl start mongod
sudo systemctl enable mongod

# Clean up and make sure all is good
composer dump-autoload
composer require mongodb/mongodb
composer update
```

## Project Repo-File Analysis

Folder system has been modeled after other projects found on GitHub:
- client holds the underlying html pages
- commons holds the assets used such as the pictures used, flashcards (in the advertisement.php), and stylesheets. It also will includes our js.
- .env holds enviroment variables (if needed) 
