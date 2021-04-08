package models

import (
	"fmt"
	"log"
	"os"

	"github.com/jinzhu/gorm"
	_ "github.com/jinzhu/gorm/dialects/mysql"
	"github.com/joho/godotenv"
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
	//dbHost := os.Getenv("db_host")

	//Building a connection string
	// dbURI := fmt.Sprintf("%s:%s@tcp(196.46.196.42)/%s?charset=utf8&parseTime=True", username, password, dbName)
	dbURI := fmt.Sprintf("%s:%s@tcp(%s)/%s?charset=utf8&parseTime=True", username, password, dbHost, dbName)
	log.Println(dbURI)

	conn, err := gorm.Open("mysql", dbURI)
	if err != nil {
		log.Println(err)
	}

	db = conn

	// DB Migration
	db.Debug().AutoMigrate(&EdSlotMapping{}).AddIndex("slot", "slot")
	db.Debug().AutoMigrate(&EdBusRoute{})
	db.Debug().AutoMigrate(&EdSubRoute{}).AddForeignKey("ed_bus_route_id", "ed_bus_routes(id)", "RESTRICT", "RESTRICT")
	db.Debug().AutoMigrate(&ProbaseTblUser{})
	db.Debug().AutoMigrate(&EdReservation{}).AddForeignKey("ed_bus_route_id", "ed_bus_routes(id)", "RESTRICT", "RESTRICT").AddForeignKey("slot", "ed_slot_mappings(slot)", "RESTRICT", "RESTRICT").AddForeignKey("bus_id", "probase_tbl_buses(id)", "RESTRICT", "RESTRICT").AddForeignKey("user_id", "probase_tbl_users(id)", "RESTRICT", "RESTRICT")
	db.Debug().AutoMigrate(&EdSlot{})
	db.Debug().AutoMigrate(&EdTown{})
	db.Debug().AutoMigrate(&EdDay{})
	db.Debug().AutoMigrate(&EdTime{})
	db.Debug().AutoMigrate(&ProbaseTblTravelRoutes{})
	db.Debug().AutoMigrate(&EdWorkFlow{})
	db.Debug().AutoMigrate(&ProbaseTblBus{})
	db.Debug().AutoMigrate(&EdPenaltyInterval{})
	db.Debug().AutoMigrate(&EdPenalty{})
	db.Debug().AutoMigrate(&EdOption{})
	db.Debug().AutoMigrate(&EdOption{})
	db.Debug().AutoMigrate(&EdArSlot{})
	db.Debug().AutoMigrate(&EdArReservation{}).AddForeignKey("ed_bus_route_id", "ed_bus_routes(id)", "RESTRICT", "RESTRICT")



	// Track Migrations
	//log.Println(db.Debug().AutoMigrate(&ProbaseTblUser{}))
	//log.Println(db.Debug().AutoMigrate(&ProbaseTblBus{}))
	//log.Println(db.Debug().AutoMigrate(&EdSlot{}))
	//log.Println(db.Debug().AutoMigrate(&EdTown{}))
	//log.Println(db.Debug().AutoMigrate(&EdDay{}))
	//log.Println(db.Debug().AutoMigrate(&EdTime{}))
	////log.Println(db.Debug().AutoMigrate(&ProbaseTblTravelRoutes{}))
	//log.Println(db.Debug().AutoMigrate(EdWorkFlow{}))
	//log.Println(db.Debug().AutoMigrate(&EdPenaltyInterval{}))
	//log.Println(db.Debug().AutoMigrate(&EdPenalty{}))

}

//returns a handle to the DB object
func GetDB() *gorm.DB {
	return db
}
