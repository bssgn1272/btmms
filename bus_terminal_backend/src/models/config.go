package models

import (
	"fmt"
	"github.com/jinzhu/gorm"
	_ "github.com/jinzhu/gorm/dialects/mysql"
	"github.com/joho/godotenv"
	_ "github.com/lib/pq"
	"log"
	"os"
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
	//dbHost := os.Getenv("db_host")


	//Building a connection string
	dbUri := fmt.Sprintf("%s:%s@tcp(127.0.0.1)/%s?charset=utf8&parseTime=True", username, password, dbName)
	log.Println(dbUri)

	conn, err := gorm.Open("mysql", dbUri)
	if err != nil {
		log.Println(err)
	}

	db = conn

	// DB Migration
	db.Debug().AutoMigrate(&User{})
	db.Debug().AutoMigrate(&Reservation{})
	db.Debug().AutoMigrate(&Slot{})
	db.Debug().AutoMigrate(&Town{})
	db.Debug().AutoMigrate(&Day{})
	db.Debug().AutoMigrate(&Time{})

	// Track Migrations
	log.Println(db.Debug().AutoMigrate(&User{}))
	log.Println(db.Debug().AutoMigrate(&Reservation{}))
	log.Println(db.Debug().AutoMigrate(&Slot{}))
	log.Println(db.Debug().AutoMigrate(&Town{}))
	log.Println(db.Debug().AutoMigrate(&Day{}))
	log.Println(db.Debug().AutoMigrate(&Time{}))

}

//returns a handle to the DB object
func GetDB() *gorm.DB {
	return db
}