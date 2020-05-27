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
	// dbUri := fmt.Sprintf("%s:%s@tcp(196.46.196.42)/%s?charset=utf8&parseTime=True", username, password, dbName)
	dbUri := fmt.Sprintf("%s:%s@tcp(localhost)/%s?charset=utf8&parseTime=True", username, password, dbName)
	log.Println(dbUri)

	conn, err := gorm.Open("mysql", dbUri)
	if err != nil {
		log.Println(err)
	}

	db = conn

	// DB Migration
	db.Debug().AutoMigrate(&ProbaseTblUser{})
	//db.Debug().AutoMigrate(&EdReservation{})
	db.Debug().AutoMigrate(&EdSlot{})
	db.Debug().AutoMigrate(&EdTown{})
	db.Debug().AutoMigrate(&EdDay{})
	db.Debug().AutoMigrate(&EdTime{})
	db.Debug().AutoMigrate(&ProbaseTblTravelRoutes{})
	db.Debug().AutoMigrate(&EdReservation{})
	db.Debug().AutoMigrate(&EdWorkFlow{})
	db.Debug().AutoMigrate(&ProbaseTblBus{})
	db.Debug().AutoMigrate(&EdPenaltyInterval{})
	db.Debug().AutoMigrate(&EdPenalty{})




	// Track Migrations
	log.Println(db.Debug().AutoMigrate(&ProbaseTblUser{}))
	log.Println(db.Debug().AutoMigrate(&ProbaseTblBus{}))
	log.Println(db.Debug().AutoMigrate(&EdSlot{}))
	log.Println(db.Debug().AutoMigrate(&EdTown{}))
	log.Println(db.Debug().AutoMigrate(&EdDay{}))
	log.Println(db.Debug().AutoMigrate(&EdTime{}))
	log.Println(db.Debug().AutoMigrate(&ProbaseTblTravelRoutes{}))
	log.Println(db.Debug().AutoMigrate(EdWorkFlow{}))
	log.Println(db.Debug().AutoMigrate(&EdPenaltyInterval{}))
	log.Println(db.Debug().AutoMigrate(&EdPenalty{}))

}

//returns a handle to the DB object
func GetDB() *gorm.DB {
	return db
}