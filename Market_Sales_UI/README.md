@auther: Francis Chulu

<p align="center">
    <a href="https://github.com/yiisoft" target="_blank">
        <img src="https://avatars0.githubusercontent.com/u/993323" height="100px">
    </a>
    <h1 align="center">Yii 2 Advanced Project Template</h1>
    <br>
</p>             
Market Sales UI setup Instructions
-----------------------------------
The Market Sales UI is developed using the Yii 2 Advanced Project Template

Yii 2 Advanced Project Template is a skeleton [Yii 2](http://www.yiiframework.com/) application best for
developing complex Web applications with multiple tiers.

The template includes three tiers: front end, back end, and console, each of which
is a separate Yii application.

The template is designed to work in a team development environment. It supports
deploying the application in different environments.

Documentation is at [docs/guide/README.md](docs/guide/README.md).

DIRECTORY STRUCTURE
-------------------

```
common
    config/              contains shared configurations
    mail/                contains view files for e-mails
    models/              contains model classes used in both backend and frontend
    tests/               contains tests for common classes    
console
    config/              contains console configurations
    controllers/         contains console controllers (commands)
    migrations/          contains database migrations
    models/              contains console-specific model classes
    runtime/             contains files generated during runtime
backend
    assets/              contains application assets such as JavaScript and CSS
    config/              contains backend configurations
    controllers/         contains Web controller classes
    models/              contains backend-specific model classes
    runtime/             contains files generated during runtime
    tests/               contains tests for backend application    
    views/               contains view files for the Web application
    web/                 contains the entry script and Web resources
frontend
    assets/              contains application assets such as JavaScript and CSS
    config/              contains frontend configurations
    controllers/         contains Web controller classes
    models/              contains frontend-specific model classes
    runtime/             contains files generated during runtime
    tests/               contains tests for frontend application
    views/               contains view files for the Web application
    web/                 contains the entry script and Web resources
    widgets/             contains frontend widgets
vendor/                  contains dependent 3rd-party packages
environments/            contains environment-based overrides
```

Installation instructions
-----------------------------------
All commands should be run from the command line. For Windows use cmd or install the git command from https://gitforwindows.org/ and install it
1. Clone the Market Sales UI from the NAPSA repo by running the commnd below or downloading the repo as a zipped folder
 - cd /apache_root_directory i.e cd /var/www/html/ on a linux environment
 - git clone https://github.com/repository Market_Sales_UI . Always use the folder Market_Sales_UI as 
its configured as the home directory in the app itself otherwise you face issues
 - if the project is downloaded as a zipped folder, create the folder Market_Sales_UI and unzip the zipped files in the folder
2. Once the project files are in the folder Market_Sales_UI, run below commands from the project folder to setup the app
 - php init   
        Above command intializes the app. The app will ask you whether you want to setup the app as Production 
        or Development environment. Choose [1] Production if you are deploying in production otherwise select 
        [0] Development if you developing on your local machine.
 - We have deliberately disabled the ognoring of certain files by git so that we do not have to reconfigure the app 
   everytime we clone from github hence the initialization will ask you to overwrite certain files, just Select no for
   all requests.
3. Once the initialization is done, run below command to download app dependancies
 - composer update
4. Once composer is done. Check database configs in common/config/main-local.php and make sure the db name and credentials
   are as you need them. In production you need to change the mailer details if you do not intend to use the ones configured.
5. The app has two parts the frontend and the backend. The frontend is for marketeers and other traders for trading and 
   the backend is for Market Administrators to Manage the Markets. Below are the two urls if the app is running on the localhost
  - frontend http://localhost/Market_Sales_UI/
  - backend http://localhost/Market_Sales_UI/admin
6. After the above steps are done, the system should be up and running. From the backend, you login using the shared admin credentials.
7. If there are issues in any case the app is not running, you may need to give the app folder executable permissions or you can
   refer to apache error log file to check what errors the system is throwing.

8. Voila!!!!!!!!!!!!!!!!!
 