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
       
    • Add cloned file to either XAMP or MAMP'S Application folder i.e xamppfiles/htdocs
      and  open your browser and type localhost/napsa or  localhost:8080/napsa .
     NOTE: port (8080) depends on your localhost server.


System Requirements:

    • MacOS Catalina version 10.15

Software Requirements:

    • MAMP software package for local web server on Mac.
            PhpMyAdmin free open source administrative tool for MySQL.
            MySQL open-source relational database management system.

    • Text Editor
            Atom free open source-code editor.
            Sublime cross-platform source code editor.

Description:
 
The PA System displays on table the Bus Company, Type, Bay and Time for registered buses.
The user logins in with username and password to enter The PA System.
Five buses are displayed on the page based on current hour using a SELECT jquery.
The Home page refreshes every 5 seconds using a jquery Ajax function.
If there are no buses at current hour a dialog with a “No Buses Scheduled” message is displayed.

Languages:
HTML5, PHP 7.2, Javascript 1.8.5, MySQL

Bootstrap 4:
Bootstrap 4 was used for CSS & Javascript-based templates for forms, buttons and tables.



Public Announcement System Modules

Admin Module:
- Admin has overall control of the PA system.
  Username: test
  Password: test

- Bus Company Management
- Bus Type Management
- City Management
- Bay Management
- Seat Management


Login Module:
- Login page prompts user to enter credentials based on
    • Username
    • Password
- User enters PA System upon successful login.


PA Display Module (Home Page Module):
- The Home page is where user is taken upon successful login.
    • A session begins when user attempts sign in and authentication is done in PHP.
    • A Welcome Alert Message with Username is displayed at the top-left of the screen.
    • The display table pulls data from database and displays 5 buses at the current hour or displays a “No Buses Scheduled” dialog. 
    • An Ajax function is used to check the backend for new data entry and refresh the page every 5 seconds.
    • Snackbar button below the table has a onclick function that displays snackbar with fade in and fade out message for 3 seconds.


Profile Module:
Profile page displays account details.
    • Username
    • Password
    • Email


Log Out Module:
User is logged out and taken back to Login Page when log out button is clicked.






 
