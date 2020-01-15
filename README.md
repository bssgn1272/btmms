Luggage Management System




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
               Download for macOS and Windows https://www.sublimetext.com

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
            Sublime cross-platform sourcecode editor.

Description:

The Luggage Management system simplifies the process of storing and locating luggage, eliminates wrong 
retrieval or unauthorized removal. The system displays Passengers name's ,description, Luggage weight 
,phone number ,cost and destination .User enters passengers details in a form . on submission [INSERT API receipt INFO HERE]

Languages:
HTML5, PHP 7.2, Javascript 1.8.5, MySQL

Bootstrap 4:
Bootstrap 4 was used for CSS & Javascript-based templates for forms, buttons .



Luggage Management System Modules

Admin Module:
- Admin has overall control of the PA system.
  Username: test
  Password: test


Login Module:
- Login page prompts user to enter credentials based on
    • Username
    • Password
- User enters PA System upon successful login.


Luggage Module:
- The Luggage page is where user is taken upon successful login.
    • A session begins when user attempts sign in and authentication is done in PHP.
    • The displayed form inserts the passanger data into database.

 [INSERT OTHER MODULES HERE]




 
