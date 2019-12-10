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
	RID   uint  `gorm:"AUTO_INCREMENT;column:r_id;" json:"r_id"`
	Slot string `json:"slot"`
	Status string `gorm:"default:'p'" json:"status"`
	Route string `json:"route"`
	UserId uint `json:"user_id"`
	Time string ` json:"time"`
	ReservedTime time.Time ` json:"reserved_time"`
}

// join struct
type Result struct {
	Reservation
	User
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

	//if reservation.UserId <= 0 {
	//	log.Println(u.Message(false, "User is not recognized"))
	//	return u.Message(false, "User is not recognized"), false
	//}

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

// get slot one requests
func GetSlotOneFive() ([]*Result) {

	reservations := make([]*Result, 0)
	err := GetDB().Table("reservations").Select("reservations.*, reservations.id, users.username").Joins("left join users on users.id=reservations.user_id").Where("time = ? AND slot = ?", "05:00", "slot_one").Find(&reservations).Error
	log.Println(err)
	if err != nil {
		return nil
	}
	return reservations
}

// get slot one requests 05:00
func GetSlotOneSix() ([]*Result) {

	reservations := make([]*Result, 0)
	err := GetDB().Table("reservations").Select("reservations.*, reservations.id, users.username").Joins("left join users on users.id=reservations.user_id").Where("time = ? AND slot = ?", "06:00", "slot_one").Find(&reservations).Error
	log.Println(err)
	if err != nil {
		return nil
	}
	return reservations
}


func GetSlotOneSeven() ([]*Result) {

	reservations := make([]*Result, 0)
	err := GetDB().Table("reservations").Select("reservations.*, reservations.id, users.username").Joins("left join users on users.id=reservations.user_id").Where("time = ? AND slot = ?", "07:00", "slot_one").Find(&reservations).Error
	log.Println(err)
	if err != nil {
		return nil
	}
	return reservations
}


func GetSlotOneEight() ([]*Result) {

	reservations := make([]*Result, 0)
	err := GetDB().Table("reservations").Select("reservations.*, reservations.id, users.username").Joins("left join users on users.id=reservations.user_id").Where("time = ? AND slot = ?", "08:00", "slot_one").Find(&reservations).Error
	log.Println(err)
	if err != nil {
		return nil
	}
	return reservations
}


func GetSlotOneNine() ([]*Result) {

	reservations := make([]*Result, 0)
	err := GetDB().Table("reservations").Select("reservations.*, reservations.id, users.username").Joins("left join users on users.id=reservations.user_id").Where("time = ? AND slot = ?", "09:00", "slot_one").Find(&reservations).Error
	log.Println(err)
	if err != nil {
		return nil
	}
	return reservations
}


func GetSlotOneTen() ([]*Result) {

	reservations := make([]*Result, 0)
	err := GetDB().Table("reservations").Select("reservations.*, reservations.id, users.username").Joins("left join users on users.id=reservations.user_id").Where("time = ? AND slot = ?", "10:00", "slot_one").Find(&reservations).Error
	log.Println(err)
	if err != nil {
		return nil
	}
	return reservations
}


func GetSlotOneEleven() ([]*Result) {

	reservations := make([]*Result, 0)
	err := GetDB().Table("reservations").Select("reservations.*, reservations.id, users.username").Joins("left join users on users.id=reservations.user_id").Where("time = ? AND slot = ?", "11:00", "slot_one").Find(&reservations).Error
	log.Println(err)
	if err != nil {
		return nil
	}
	return reservations
}


func GetSlotOneTwelve() ([]*Result) {

	reservations := make([]*Result, 0)
	err := GetDB().Table("reservations").Select("reservations.*, reservations.id, users.username").Joins("left join users on users.id=reservations.user_id").Where("time = ? AND slot = ?", "12:00", "slot_one").Find(&reservations).Error
	log.Println(err)
	if err != nil {
		return nil
	}
	return reservations
}


func GetSlotOneThirteen() ([]*Result) {

	reservations := make([]*Result, 0)
	err := GetDB().Table("reservations").Select("reservations.*, reservations.id, users.username").Joins("left join users on users.id=reservations.user_id").Where("time = ? AND slot = ?", "13:00", "slot_one").Find(&reservations).Error
	log.Println(err)
	if err != nil {
		return nil
	}
	return reservations
}


func GetSlotOneFourteen() ([]*Result) {

	reservations := make([]*Result, 0)
	err := GetDB().Table("reservations").Select("reservations.*, reservations.id, users.username").Joins("left join users on users.id=reservations.user_id").Where("time = ? AND slot = ?", "14:00", "slot_one").Find(&reservations).Error
	log.Println(err)
	if err != nil {
		return nil
	}
	return reservations
}


func GetSlotOneFifteen() ([]*Result) {

	reservations := make([]*Result, 0)
	err := GetDB().Table("reservations").Select("reservations.*, reservations.id, users.username").Joins("left join users on users.id=reservations.user_id").Where("time = ? AND slot = ?", "15:00", "slot_one").Find(&reservations).Error
	log.Println(err)
	if err != nil {
		return nil
	}
	return reservations
}

// get slot one requests
func GetSlotTwoFive() ([]*Result) {

	reservations := make([]*Result, 0)
	err := GetDB().Table("reservations").Select("reservations.*, reservations.id, users.username").Joins("left join users on users.id=reservations.user_id").Where("time = ? AND slot = ?", "05:00", "slot_two").Find(&reservations).Error
	log.Println(err)
	if err != nil {
		return nil
	}
	return reservations
}

func GetSlotTwoSix() ([]*Result) {

	reservations := make([]*Result, 0)
	err := GetDB().Table("reservations").Select("reservations.*, reservations.id, users.username").Joins("left join users on users.id=reservations.user_id").Where("time = ? AND slot = ?", "06:00", "slot_two").Find(&reservations).Error
	log.Println(err)
	if err != nil {
		return nil
	}
	return reservations
}


func GetSlotTwoSeven() ([]*Result) {

	reservations := make([]*Result, 0)
	err := GetDB().Table("reservations").Select("reservations.*, reservations.id, users.username").Joins("left join users on users.id=reservations.user_id").Where("time = ? AND slot = ?", "07:00", "slot_two").Find(&reservations).Error
	log.Println(err)
	if err != nil {
		return nil
	}
	return reservations
}


func GetSlotTwoEight() ([]*Result) {

	reservations := make([]*Result, 0)
	err := GetDB().Table("reservations").Select("reservations.*, reservations.id, users.username").Joins("left join users on users.id=reservations.user_id").Where("time = ? AND slot = ?", "08:00", "slot_two").Find(&reservations).Error
	log.Println(err)
	if err != nil {
		return nil
	}
	return reservations
}


func GetSlotTwoNine() ([]*Result) {

	reservations := make([]*Result, 0)
	err := GetDB().Table("reservations").Select("reservations.*, reservations.id, users.username").Joins("left join users on users.id=reservations.user_id").Where("time = ? AND slot = ?", "09:00", "slot_two").Find(&reservations).Error
	log.Println(err)
	if err != nil {
		return nil
	}
	return reservations
}


func GetSlotTwoTen() ([]*Result) {

	reservations := make([]*Result, 0)
	err := GetDB().Table("reservations").Select("reservations.*, reservations.id, users.username").Joins("left join users on users.id=reservations.user_id").Where("time = ? AND slot = ?", "10:00", "slot_two").Find(&reservations).Error
	log.Println(err)
	if err != nil {
		return nil
	}
	return reservations
}


func GetSlotTwoEleven() ([]*Result) {

	reservations := make([]*Result, 0)
	err := GetDB().Table("reservations").Select("reservations.*, reservations.id, users.username").Joins("left join users on users.id=reservations.user_id").Where("time = ? AND slot = ?", "11:00", "slot_two").Find(&reservations).Error
	log.Println(err)
	if err != nil {
		return nil
	}
	return reservations
}


func GetSlotTwoTwelve() ([]*Result) {

	reservations := make([]*Result, 0)
	err := GetDB().Table("reservations").Select("reservations.*, reservations.id, users.username").Joins("left join users on users.id=reservations.user_id").Where("time = ? AND slot = ?", "12:00", "slot_two").Find(&reservations).Error
	log.Println(err)
	if err != nil {
		return nil
	}
	return reservations
}


func GetSlotTwoThirteen() ([]*Result) {

	reservations := make([]*Result, 0)
	err := GetDB().Table("reservations").Select("reservations.*, reservations.id, users.username").Joins("left join users on users.id=reservations.user_id").Where("time = ? AND slot = ?", "13:00", "slot_two").Find(&reservations).Error
	log.Println(err)
	if err != nil {
		return nil
	}
	return reservations
}


func GetSlotTwoFourteen() ([]*Result) {

	reservations := make([]*Result, 0)
	err := GetDB().Table("reservations").Select("reservations.*, reservations.id, users.username").Joins("left join users on users.id=reservations.user_id").Where("time = ? AND slot = ?", "14:00", "slot_two").Find(&reservations).Error
	log.Println(err)
	if err != nil {
		return nil
	}
	return reservations
}


func GetSlotTwoFifteen() ([]*Result) {

	reservations := make([]*Result, 0)
	err := GetDB().Table("reservations").Select("reservations.*, reservations.id, users.username").Joins("left join users on users.id=reservations.user_id").Where("time = ? AND slot = ?", "15:00", "slot_two").Find(&reservations).Error
	log.Println(err)
	if err != nil {
		return nil
	}
	return reservations
}


// get slot Three requests
func GetSlotThreeFive() ([]*Result) {

	reservations := make([]*Result, 0)
	err := GetDB().Table("reservations").Select("reservations.*, reservations.id, users.username").Joins("left join users on users.id=reservations.user_id").Where("time = ? AND slot = ?", "05:00", "slot_three").Find(&reservations).Error
	log.Println(err)
	if err != nil {
		return nil
	}
	return reservations
}

func GetSlotThreeSix() ([]*Result) {

	reservations := make([]*Result, 0)
	err := GetDB().Table("reservations").Select("reservations.*, reservations.id, users.username").Joins("left join users on users.id=reservations.user_id").Where("time = ? AND slot = ?", "06:00", "slot_three").Find(&reservations).Error
	log.Println(err)
	if err != nil {
		return nil
	}
	return reservations
}


func GetSlotThreeSeven() ([]*Result) {

	reservations := make([]*Result, 0)
	err := GetDB().Table("reservations").Select("reservations.*, reservations.id, users.username").Joins("left join users on users.id=reservations.user_id").Where("time = ? AND slot = ?", "07:00", "slot_three").Find(&reservations).Error
	log.Println(err)
	if err != nil {
		return nil
	}
	return reservations
}


func GetSlotThreeEight() ([]*Result) {

	reservations := make([]*Result, 0)
	err := GetDB().Table("reservations").Select("reservations.*, reservations.id, users.username").Joins("left join users on users.id=reservations.user_id").Where("time = ? AND slot = ?", "08:00", "slot_three").Find(&reservations).Error
	log.Println(err)
	if err != nil {
		return nil
	}
	return reservations
}


func GetSlotThreeNine() ([]*Result) {

	reservations := make([]*Result, 0)
	err := GetDB().Table("reservations").Select("reservations.*, reservations.id, users.username").Joins("left join users on users.id=reservations.user_id").Where("time = ? AND slot = ?", "09:00", "slot_three").Find(&reservations).Error
	log.Println(err)
	if err != nil {
		return nil
	}
	return reservations
}


func GetSlotThreeTen() ([]*Result) {

	reservations := make([]*Result, 0)
	err := GetDB().Table("reservations").Select("reservations.*, reservations.id, users.username").Joins("left join users on users.id=reservations.user_id").Where("time = ? AND slot = ?", "10:00", "slot_three").Find(&reservations).Error
	log.Println(err)
	if err != nil {
		return nil
	}
	return reservations
}


func GetSlotThreeEleven() ([]*Result) {

	reservations := make([]*Result, 0)
	err := GetDB().Table("reservations").Select("reservations.*, reservations.id, users.username").Joins("left join users on users.id=reservations.user_id").Where("time = ? AND slot = ?", "11:00", "slot_three").Find(&reservations).Error
	log.Println(err)
	if err != nil {
		return nil
	}
	return reservations
}


func GetSlotThreeTwelve() ([]*Result) {

	reservations := make([]*Result, 0)
	err := GetDB().Table("reservations").Select("reservations.*, reservations.id, users.username").Joins("left join users on users.id=reservations.user_id").Where("time = ? AND slot = ?", "12:00", "slot_three").Find(&reservations).Error
	log.Println(err)
	if err != nil {
		return nil
	}
	return reservations
}


func GetSlotThreeThirteen() ([]*Result) {

	reservations := make([]*Result, 0)
	err := GetDB().Table("reservations").Select("reservations.*, reservations.id, users.username").Joins("left join users on users.id=reservations.user_id").Where("time = ? AND slot = ?", "13:00", "slot_three").Find(&reservations).Error
	log.Println(err)
	if err != nil {
		return nil
	}
	return reservations
}


func GetSlotThreeFourteen() ([]*Result) {

	reservations := make([]*Result, 0)
	err := GetDB().Table("reservations").Select("reservations.*, reservations.id, users.username").Joins("left join users on users.id=reservations.user_id").Where("time = ? AND slot = ?", "14:00", "slot_three").Find(&reservations).Error
	log.Println(err)
	if err != nil {
		return nil
	}
	return reservations
}


func GetSlotThreeFifteen() ([]*Result) {

	reservations := make([]*Result, 0)
	err := GetDB().Table("reservations").Select("reservations.*, reservations.id, users.username").Joins("left join users on users.id=reservations.user_id").Where("time = ? AND slot = ?", "15:00", "slot_three").Find(&reservations).Error
	log.Println(err)
	if err != nil {
		return nil
	}
	return reservations
}


// get slot Four requests
func GetSlotFourFive() ([]*Result) {

	reservations := make([]*Result, 0)
	err := GetDB().Table("reservations").Select("reservations.*, reservations.id, users.username").Joins("left join users on users.id=reservations.user_id").Where("time = ? AND slot = ?", "05:00", "slot_four").Find(&reservations).Error
	log.Println(err)
	if err != nil {
		return nil
	}
	return reservations
}

func GetSlotFourSix() ([]*Result) {

	reservations := make([]*Result, 0)
	err := GetDB().Table("reservations").Select("reservations.*, reservations.id, users.username").Joins("left join users on users.id=reservations.user_id").Where("time = ? AND slot = ?", "06:00", "slot_four").Find(&reservations).Error
	log.Println(err)
	if err != nil {
		return nil
	}
	return reservations
}


func GetSlotFourSeven() ([]*Result) {

	reservations := make([]*Result, 0)
	err := GetDB().Table("reservations").Select("reservations.*, reservations.id, users.username").Joins("left join users on users.id=reservations.user_id").Where("time = ? AND slot = ?", "07:00", "slot_four").Find(&reservations).Error
	log.Println(err)
	if err != nil {
		return nil
	}
	return reservations
}


func GetSlotFourEight() ([]*Result) {

	reservations := make([]*Result, 0)
	err := GetDB().Table("reservations").Select("reservations.*, reservations.id, users.username").Joins("left join users on users.id=reservations.user_id").Where("time = ? AND slot = ?", "08:00", "slot_four").Find(&reservations).Error
	log.Println(err)
	if err != nil {
		return nil
	}
	return reservations
}


func GetSlotFourNine() ([]*Result) {

	reservations := make([]*Result, 0)
	err := GetDB().Table("reservations").Select("reservations.*, reservations.id, users.username").Joins("left join users on users.id=reservations.user_id").Where("time = ? AND slot = ?", "09:00", "slot_four").Find(&reservations).Error
	log.Println(err)
	if err != nil {
		return nil
	}
	return reservations
}


func GetSlotFourTen() ([]*Result) {

	reservations := make([]*Result, 0)
	err := GetDB().Table("reservations").Select("reservations.*, reservations.id, users.username").Joins("left join users on users.id=reservations.user_id").Where("time = ? AND slot = ?", "10:00", "slot_four").Find(&reservations).Error
	log.Println(err)
	if err != nil {
		return nil
	}
	return reservations
}


func GetSlotFourEleven() ([]*Result) {

	reservations := make([]*Result, 0)
	err := GetDB().Table("reservations").Select("reservations.*, reservations.id, users.username").Joins("left join users on users.id=reservations.user_id").Where("time = ? AND slot = ?", "11:00", "slot_four").Find(&reservations).Error
	log.Println(err)
	if err != nil {
		return nil
	}
	return reservations
}


func GetSlotFourTwelve() ([]*Result) {

	reservations := make([]*Result, 0)
	err := GetDB().Table("reservations").Select("reservations.*, reservations.id, users.username").Joins("left join users on users.id=reservations.user_id").Where("time = ? AND slot = ?", "12:00", "slot_four").Find(&reservations).Error
	log.Println(err)
	if err != nil {
		return nil
	}
	return reservations
}


func GetSlotFourThirteen() ([]*Result) {

	reservations := make([]*Result, 0)
	err := GetDB().Table("reservations").Select("reservations.*, reservations.id, users.username").Joins("left join users on users.id=reservations.user_id").Where("time = ? AND slot = ?", "13:00", "slot_four").Find(&reservations).Error
	log.Println(err)
	if err != nil {
		return nil
	}
	return reservations
}


func GetSlotFourFourteen() ([]*Result) {

	reservations := make([]*Result, 0)
	err := GetDB().Table("reservations").Select("reservations.*, reservations.id, users.username").Joins("left join users on users.id=reservations.user_id").Where("time = ? AND slot = ?", "14:00", "slot_four").Find(&reservations).Error
	log.Println(err)
	if err != nil {
		return nil
	}
	return reservations
}


func GetSlotFourFifteen() ([]*Result) {

	reservations := make([]*Result, 0)
	err := GetDB().Table("reservations").Select("reservations.*, reservations.id, users.username").Joins("left join users on users.id=reservations.user_id").Where("time = ? AND slot = ?", "15:00", "slot_four").Find(&reservations).Error
	log.Println(err)
	if err != nil {
		return nil
	}
	return reservations
}


// get slot Five requests
func GetSlotFiveFive() ([]*Result) {

	reservations := make([]*Result, 0)
	err := GetDB().Table("reservations").Select("reservations.*, reservations.id, users.username").Joins("left join users on users.id=reservations.user_id").Where("time = ? AND slot = ?", "05:00", "slot_five").Find(&reservations).Error
	log.Println(err)
	if err != nil {
		return nil
	}
	return reservations
}

func GetSlotFiveSix() ([]*Result) {

	reservations := make([]*Result, 0)
	err := GetDB().Table("reservations").Select("reservations.*, reservations.id, users.username").Joins("left join users on users.id=reservations.user_id").Where("time = ? AND slot = ?", "06:00", "slot_five").Find(&reservations).Error
	log.Println(err)
	if err != nil {
		return nil
	}
	return reservations
}


func GetSlotFiveSeven() ([]*Result) {

	reservations := make([]*Result, 0)
	err := GetDB().Table("reservations").Select("reservations.*, reservations.id, users.username").Joins("left join users on users.id=reservations.user_id").Where("time = ? AND slot = ?", "07:00", "slot_five").Find(&reservations).Error
	log.Println(err)
	if err != nil {
		return nil
	}
	return reservations
}


func GetSlotFiveEight() ([]*Result) {

	reservations := make([]*Result, 0)
	err := GetDB().Table("reservations").Select("reservations.*, reservations.id, users.username").Joins("left join users on users.id=reservations.user_id").Where("time = ? AND slot = ?", "08:00", "slot_five").Find(&reservations).Error
	log.Println(err)
	if err != nil {
		return nil
	}
	return reservations
}


func GetSlotFiveNine() ([]*Result) {

	reservations := make([]*Result, 0)
	err := GetDB().Table("reservations").Select("reservations.*, reservations.id, users.username").Joins("left join users on users.id=reservations.user_id").Where("time = ? AND slot = ?", "09:00", "slot_five").Find(&reservations).Error
	log.Println(err)
	if err != nil {
		return nil
	}
	return reservations
}


func GetSlotFiveTen() ([]*Result) {

	reservations := make([]*Result, 0)
	err := GetDB().Table("reservations").Select("reservations.*, reservations.id, users.username").Joins("left join users on users.id=reservations.user_id").Where("time = ? AND slot = ?", "10:00", "slot_five").Find(&reservations).Error
	log.Println(err)
	if err != nil {
		return nil
	}
	return reservations
}


func GetSlotFiveEleven() ([]*Result) {

	reservations := make([]*Result, 0)
	err := GetDB().Table("reservations").Select("reservations.*, reservations.id, users.username").Joins("left join users on users.id=reservations.user_id").Where("time = ? AND slot = ?", "11:00", "slot_five").Find(&reservations).Error
	log.Println(err)
	if err != nil {
		return nil
	}
	return reservations
}


func GetSlotFiveTwelve() ([]*Result) {

	reservations := make([]*Result, 0)
	err := GetDB().Table("reservations").Select("reservations.*, reservations.id, users.username").Joins("left join users on users.id=reservations.user_id").Where("time = ? AND slot = ?", "12:00", "slot_five").Find(&reservations).Error
	log.Println(err)
	if err != nil {
		return nil
	}
	return reservations
}


func GetSlotFiveThirteen() ([]*Result) {

	reservations := make([]*Result, 0)
	err := GetDB().Table("reservations").Select("reservations.*, reservations.id, users.username").Joins("left join users on users.id=reservations.user_id").Where("time = ? AND slot = ?", "13:00", "slot_five").Find(&reservations).Error
	log.Println(err)
	if err != nil {
		return nil
	}
	return reservations
}


func GetSlotFiveFourteen() ([]*Result) {

	reservations := make([]*Result, 0)
	err := GetDB().Table("reservations").Select("reservations.*, reservations.id, users.username").Joins("left join users on users.id=reservations.user_id").Where("time = ? AND slot = ?", "14:00", "slot_five").Find(&reservations).Error
	log.Println(err)
	if err != nil {
		return nil
	}
	return reservations
}


func GetSlotFiveFifteen() ([]*Result) {

	reservations := make([]*Result, 0)
	err := GetDB().Table("reservations").Select("reservations.*, reservations.id, users.username").Joins("left join users on users.id=reservations.user_id").Where("time = ? AND slot = ?", "15:00", "slot_five").Find(&reservations).Error
	log.Println(err)
	if err != nil {
		return nil
	}
	return reservations
}





