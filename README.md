# btmms
Code base for the BTMMS project

# Frontend

The Frontend of this system has been developed using Angular material.

#Installation

Clone from Github.

Run 
```bash
git clone https://github.com/napsa-4805/btmms.git
```

```bash
git checkout bus_management
```

```bash
cd bus_terminal_frontend
```


use [npm](https://www.npmjs.com/get-npm) to install and build the frontend.

Run
```bash
npm install
```

To run the dev server
```bash
npm start
```

To build the frontend
```bash
npm run-script build
```

# Backend

The backend API has been developed using [Golang]()

Follow the installations steps from [golang.org](https://golang.org/doc/install)

Follow the setup process from [golang.org/doc/code.html](golang.org/doc/code.html)

Go into the backend folder

```bash
cd bus_terminal_backend
```

Run

```bash
go build
```

port Number
```bash
:7080
```

# Database

for the DB postgreSQL was used

 
To install follow the process from [w3resource](https://www.w3resource.com/PostgreSQL/install-postgresql-on-linux-and-windows.php) 


# Credentials

To create user postman can be used

URL 
> localhost:7080/api/user/register


Bus Operator

{
	"username": <username>,
	"password": <password>,
	"role": "operator",
	"email": <email>
}


Admin

{
	"username": <username>,
	"password": <password>,
	"role": "admin",
	"email": <email>
}


# Slots

To create Slots postman can be used

> localhost:7080/api/slots/create

{
    "slot_one": "open",
    "slot_two": "open",
    "slot_three": "open",
    "slot_four": "open",
    "slot_five": "open",
    "time":<string>,
    "reservation_time":<date(timeStamp with time zone)>
}

+ Time should be a string from '05:00' to 15:00
+ Date should be for the following day 

Date format
> 2019-12-04T21:38:02.2869463+02:00