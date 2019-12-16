package models

import (
	"fmt"
	"github.com/jinzhu/gorm"
	"github.com/joho/godotenv"
	"log"
	"os"
	_ "github.com/lib/pq"
)

var db *gorm.DB //database

func init() {

	//Load .env file
	e := godotenv.Load()
	if e != nil {
		log.Println(e)
	}

	username := os.Getenv("db_user")
	password := os.Getenv("db_pass")
	dbName := os.Getenv("db_name")
	dbHost := os.Getenv("db_host")


	//Building a connection string
	dbUri := fmt.Sprintf("host=%s user=%s dbname=%s sslmode=disable password=%s", dbHost, username, dbName, password)
	log.Println(dbUri)

	conn, err := gorm.Open("postgres", dbUri)
	if err != nil {
		log.Println(err)
	}

	db = conn

	// DB Migration
	db.Debug().AutoMigrate(&User{})
	db.Debug().AutoMigrate(&Reservation{})
	db.Debug().AutoMigrate(&Slot{})

	// Track Migrations
	log.Println(db.Debug().AutoMigrate(&User{}))
	log.Println(db.Debug().AutoMigrate(&Reservation{}))
	log.Println(db.Debug().AutoMigrate(&Slot{}))

}

//returns a handle to the DB object
func GetDB() *gorm.DB {
	return db
}