package models

import (
	"log"
	"net/url"
	"regexp"
	"time"

	u "../../src/utils"
	"github.com/jinzhu/gorm"
)

//EdReservation a struct for reservation model
type EdReservation struct {
	gorm.Model
	Slot               string    `json:"slot"`
	ResUuid            string    `json:"res_uuid"`
	Status             string    `gorm:"default:'p'" json:"status"`
	Route              uint      `json:"route"`
	UserId             uint      `json:"user_id"`
	BusId              uint      `json:"bus_id"`
	Time               string    `json:"time"`
	ReservedTime       time.Time `json:"reserved_time"`
	CancellationReason string    `json:"cancellation_reason"`
}

// join reservation and reservation struct
type EdResult struct {
	EdReservation
	ProbaseTblTravelRoutes
	ProbaseTblUser
	ProbaseTblBus
}

// Variables for regular expressions
var (
	regexpSlot  = regexp.MustCompile("^[^0-9]+$")
	regexpRoute = regexp.MustCompile("^[^0-9]+$")
)

/*
 This struct function validate the required parameters sent through the http request body
returns message and true if the requirement is met
*/
func (reservation *EdReservation) Validate() url.Values {

	errs := url.Values{}

	if reservation.Slot == "" {
		errs.Add("slot", "Slot should be on the payload!")
	}

	//if reservation.Route == "" {
	//	errs.Add("slot_two", "Route should be on the payload!")
	//}

	if !regexpSlot.Match([]byte(reservation.Slot)) {
		errs.Add("slot", "The slot field should be valid!")
	}
	//if !regexpRoute.Match([]byte(reservation.Route)) {
	//	errs.Add("route", "The route field should be valid!")
	//}

	log.Println(errs)

	return errs
}

// create reservation
func (reservation *EdReservation) Create() map[string]interface{} {

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
func GetReservation(id uint) []*EdResult {

	//t := time.Now()
	//reservedTime := time.Date(t.Year(), t.Month(), t.Day(), t.Hour(), t.Minute(), t.Second(), 0, t.Location())

	//reservations := make([]*EdReservation, 0)
	result := make([]*EdResult, 0)
	//err := GetDB().Select([]string{"ed_reservations.*", "probase_tbl_users.*", "probase_tbl_bus.*", "probase_tbl_travel_routes.*"}).Joins("left join probase_tbl_users on ed_reservations.user_id = probase_tbl_users.id").Joins("left join probase_tbl_bus on probase_tbl_users.id=probase_tbl_bus.operator_id").Joins("left join probase_tbl_travel_routes on ed_reservations.route = probase_tbl_travel_routes.id").Where("user_id = ? and ed_reservations.reserved_time > ?", id, reservedTime).Find(&result).Error
	err := GetDB().Table("ed_reservations").Select("ed_reservations.*, ed_reservations.id, probase_tbl_users.username, probase_tbl_bus.company, probase_tbl_bus.license_plate, probase_tbl_travel_routes.end_route").Joins("left join probase_tbl_users on ed_reservations.user_id = probase_tbl_users.id").Joins("left join probase_tbl_bus on ed_reservations.bus_id=probase_tbl_bus.id").Joins("left join probase_tbl_travel_routes on ed_reservations.route = probase_tbl_travel_routes.id").Where("user_id = ?", id).Order("ed_reservations.reserved_time ASC").Order("ed_reservations.time ASC").Find(&result).Error
	//err := GetDB().Table("ed_reservations").Where("user_id = ? and ed_reservations.reserved_time > ?", id, reservedTime).Find(&reservations).Error
	log.Println(err)
	if err != nil {
		return nil
	}
	return result
}

// get reservation
func GetReservationOperatorHistory(id uint) []*EdResult {

	reservations := make([]*EdResult, 0)
	//err := GetDB().Table("ed_reservations").Where("user_id = ?", id).Find(&reservations).Error
	err := GetDB().Table("ed_reservations").Select("ed_reservations.*, ed_reservations.id, probase_tbl_users.username, probase_tbl_bus.company, probase_tbl_bus.license_plate, probase_tbl_travel_routes.end_route").Joins("left join probase_tbl_users on ed_reservations.user_id = probase_tbl_users.id").Joins("left join probase_tbl_bus on ed_reservations.bus_id=probase_tbl_bus.id").Joins("left join probase_tbl_travel_routes on ed_reservations.route = probase_tbl_travel_routes.id").Where("user_id = ?", id).Find(&reservations).Error
	log.Println(err)
	if err != nil {
		return nil
	}
	return reservations
}

// get reservations
func GetReservations() []*EdReservation {

	reservations := make([]*EdReservation, 0)
	err := GetDB().Table("ed_reservations").Find(&reservations).Error
	log.Println(err)
	if err != nil {
		log.Println(err)
		return nil
	}

	return reservations
}

// get reservations
//func GetReservationsHistory(fromDate time.Time, toDate time.Time) ([]*EdResult) {
//
//	result := make([]*EdResult, 0)
//	err := GetDB().Table("ed_reservations").Select("ed_reservations.*, ed_reservations.id, ed_users.username").Joins("left join ed_users on ed_users.id=ed_reservations.user_id").Joins("left join probase_tbl_bus on ed_users.id=probase_tbl_bus.operator_id").Where("ed_reservations.reserved_time >= ? or ed_reservations.reserved_time <= ?", fromDate, toDate).Find(&result).Error
//	log.Println(err)
//	if err != nil {
//		log.Println(err)
//		return nil
//	}
//
//	return result
//}

// GetCurrentReservation get reservations for a particular day
func GetCurrentReservation() []*EdResult {

	t := time.Now()
	reservedTime := time.Date(t.Year(), t.Month(), t.Day(), 0, 0, 0, 0, t.Location())
	log.Println(reservedTime)
	result := make([]*EdResult, 0)
	err := GetDB().Table("ed_reservations").Select("ed_reservations.*, probase_tbl_users.username, probase_tbl_bus.company, probase_tbl_bus.license_plate, probase_tbl_travel_routes.end_route").Joins("left join probase_tbl_users on ed_reservations.user_id = probase_tbl_users.id").Joins("left join probase_tbl_bus on ed_reservations.bus_id=probase_tbl_bus.id").Joins("left join probase_tbl_travel_routes on ed_reservations.route = probase_tbl_travel_routes.id").Where("ed_reservations.reserved_time > ?", reservedTime).Find(&result).Error
	log.Println(err)
	if err != nil {
		log.Println(err)
		return nil
	}

	return result
}

// GetReservationHistory get reservations historically
func GetReservationHistory() []*EdResult {
	result := make([]*EdResult, 0)
	err := GetDB().Table("ed_reservations").Select("ed_reservations.*, ed_reservations.id, probase_tbl_users.username, probase_tbl_bus.company, probase_tbl_bus.license_plate").Joins("left join probase_tbl_users on ed_reservations.user_id = probase_tbl_users.id").Joins("left join probase_tbl_bus on ed_reservations.bus_id=probase_tbl_bus.id").Find(&result).Error
	log.Println(err)
	if err != nil {
		log.Println(err)
		return nil
	}

	return result
}

// Update Approve or reject reservation
func (reservation *EdReservation) Update(id string) map[string]interface{} {

	db.Model(&reservation).Where("res_uuid = ?", id).Updates(EdReservation{Status: reservation.Status, CancellationReason: reservation.CancellationReason, BusId: reservation.BusId})

	log.Println(reservation.Status)

	resp := u.Message(true, "success")
	resp["reservation"] = reservation
	log.Println(resp)
	return resp
}
