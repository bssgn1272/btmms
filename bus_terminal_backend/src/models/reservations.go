package models

import (
	u "../../src/utils"
	"github.com/jinzhu/gorm"
	"log"
	"time"
)

//a struct to rep reservation model
type Reservation struct {
	gorm.Model
	Slot string `json:"slot"`
	Status string `gorm:"default:'p'" json:"status"`
	Route string `json:"route"`
	UserId uint `json:"user_id"`
	ReservedTime time.Time ` json:"reserved_time"`
}

/*
 This struct function validate the required parameters sent through the http request body
returns message and true if the requirement is met
*/
func (reservation *Reservation) Validate() (map[string] interface{}, bool) {

	if reservation.Slot == "" {
		log.Println(u.Message(false, "Reservation slot should be on the payload"))
		return u.Message(false, "Reservation slot should be on the payload"), false
	}

	if reservation.Route == "" {
		log.Println(u.Message(false, "Route should be on the payload"))
		return u.Message(false, "Route should be on the payload"), false
	}

	if reservation.UserId <= 0 {
		log.Println(u.Message(false, "User is not recognized"))
		return u.Message(false, "User is not recognized"), false
	}

	//All the required parameters are present
	log.Println(u.Message(true, "success"))
	return u.Message(true, "success"), true
}


// create reservation
func (reservation *Reservation) Create() (map[string] interface{}) {

	if resp, ok := reservation.Validate(); !ok {
		return resp
	}

	GetDB().Create(reservation)

	resp := u.Message(true, "success")
	resp["reservation"] = reservation
	log.Println(resp)
	return resp
}


// get reservation
func GetReservation(id uint) ([]*Reservation) {

	reservations := make([]*Reservation, 0)
	err := GetDB().Table("reservations").Where("user_id = ?", id).Find(&reservations).Error
	log.Println(err)
	if err != nil {
		return nil
	}
	return reservations
}

// get reservations
func GetReservations() ([]*Reservation) {

	reservations := make([]*Reservation, 0)
	err := GetDB().Table("reservations").Find(&reservations).Error
	log.Println(err)
	if err != nil {
		log.Println(err)
		return nil
	}

	return reservations
}



// get reservations for a particular day

// get reservation
func GetCurrentReservation() ([]*Reservation) {

	t := time.Now()
	reservedTime := time.Date(t.Year(), t.Month(), t.Day(), 23, 59, 59, 0, t.Location())
	reservations := make([]*Reservation, 0)
	err := GetDB().Table("reservations").Where("reserved_time > ?", reservedTime).Find(&reservations).Error
	log.Println(err)
	if err != nil {
		log.Println(err)
		return nil
	}

	return reservations
}


// Approve or reject reservation

func (reservation *Reservation) Update(id uint) (map[string] interface{}) {

	db.Model(&reservation).Where("id = ?", id).Updates(Reservation{Status: reservation.Status})

	log.Println(reservation.Status)

	resp := u.Message(true, "success")
	resp["reservation"] = reservation
	log.Println(resp)
	return resp
}
