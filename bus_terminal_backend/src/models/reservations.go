package models

import (
	u "../../src/utils"
	"github.com/jinzhu/gorm"
	"log"
	"net/url"
	"regexp"
	"time"
)

//a struct for reservation model
type Reservation struct {
	gorm.Model
	ID   uint  `gorm:"AUTO_INCREMENT;column:id;" json:"id"`
	Slot string `json:"slot"`
	Status string `gorm:"default:'p'" json:"status"`
	Route string `json:"route"`
	UserId uint `json:"user_id"`
	Time string ` json:"time"`
	ReservedTime time.Time ` json:"reserved_time"`
}

// join reservation and reservation struct
type Result struct {
	Reservation
	User
}


// Variables for regular expressions
var(
	regexpSlot = regexp.MustCompile("^[^0-9]+$")
	regexpRoute = regexp.MustCompile("^[^0-9]+$")
)

/*
 This struct function validate the required parameters sent through the http request body
returns message and true if the requirement is met
*/
func (reservation *Reservation) Validate() url.Values {

	errs := url.Values{}


	if reservation.Slot == "" {
		errs.Add("slot", "Slot should be on the payload!")
	}

	if reservation.Route == "" {
		errs.Add("slot_two", "Route should be on the payload!")
	}


	if !regexpSlot.Match([]byte(reservation.Slot)) {
		errs.Add("slot", "The slot field should be valid!")
	}
	if !regexpRoute.Match([]byte(reservation.Route)) {
		errs.Add("route", "The route field should be valid!")
	}

	log.Println(errs)

	return errs
}


// create reservation
func (reservation *Reservation) Create() (map[string] interface{}) {

	if validErrs := reservation.Validate(); len(validErrs) > 0 {
		err := map[string]interface{}{"validationError": validErrs}
		return err
	}

	GetDB().Create(reservation)

	resp := u.Message(true, "success")
	resp["reservation"] = reservation
	log.Println(resp)
	return resp
}


// get reservation
func GetReservation(id uint) ([]*Reservation) {

	t := time.Now()
	reservedTime := time.Date(t.Year(), t.Month(), t.Day(), 23, 59, 59, 0, t.Location())

	reservations := make([]*Reservation, 0)
	err := GetDB().Table("reservations").Where("user_id = ? and reservations.reserved_time > ?", id, reservedTime).Find(&reservations).Error
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

func GetCurrentReservation() ([]*Result) {

	t := time.Now()
	reservedTime := time.Date(t.Year(), t.Month(), t.Day(), 23, 59, 59, 0, t.Location())
	result := make([]*Result, 0)
	err := GetDB().Table("reservations").Select("reservations.*, reservations.id, users.username").Joins("left join users on users.id=reservations.user_id").Where("reservations.reserved_time > ?", reservedTime).Find(&result).Error
	log.Println(err)
	if err != nil {
		log.Println(err)
		return nil
	}

	return result
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





