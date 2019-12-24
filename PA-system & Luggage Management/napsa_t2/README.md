

Public Announcement System



Build Instructions:

    • Install local web server environment.
             - MAMP Version 4.2.1.
               On macOS, download MAMP https://www.mamp.info/en/downloads/

             - XAMP Version 7.3.9.
               On Windows, download XAMP https://www.apachefriends.org/index.html

    •  Install Text-Editor
             - Atom Version 1.41.0
               Download for macOS and Windows https://atom.io/

             - Sublime Version 3.0
               Download for macOS and Windows https://www.sublimetext.com/

    • Download zip or Clone the source repository from Github.
              On the command line enter https://github.com/napsa-4805/btmms.git





System Requirements:

    • MacOS Catalina version 10.15


Software Requirements:

    • MAMP software package for local web server on Mac.
            - PhpMyAdmin free open source administrative tool for MySQL.
            - MySQL open-source relational database management system.

    • Text Editor
            - Atom free open source-code editor.
            - Sublime cross-platform source code editor.


Description:

The Public Announcement System displays a Departures screen with 20 Buses scheduled for the given day.

The user logins in with username and password to enter The Public Announcement System.

The Public Announcement System displays Bus information by Bus License (Number), Time, Company, Bay and Status.
 - Status column include; Boarding, Canceled, On Time, Delayed and Canceled.
 - Bay Column include 5 Bays; A, B, C, D and E.






Languages:
HTML5, PHP 7.2, Javascript 1.8.5, MySQL


Bootstrap 4:
Bootstrap 4 was used for CSS & Javascript-based templates for forms, buttons and tables.



Public Announcement System Modules

User Module:
- User has overall control of the PA system.
  Username: test
  Password: test

- Bus Company Management
- Bay Management
- Destination Management
- Time Management
- Status Management


Login Module:
- Login page prompts user to enter credentials based on
    • Username
    • Password
- User enters PA System upon successful login.


PA Display Module (Home Page Module):
- The Home page is where user is taken upon successful login.
    • A session begins when user attempts sign in and authentication is done in PHP.
    • The table pulls data from database using a INNER JOIN query and displays data on screen.
    • An Ajax function is used to check the backend for new data entry and refresh the page every 5 seconds.


Profile Module:
Profile page displays account details.
    • Username
    • Password
    • Email


About Module:
Information about the Public Announcement System.

Log Out Module:
User is logged out and taken back to Login Page when log out button is clicked.
