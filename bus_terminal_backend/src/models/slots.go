package models

import (
	u "../../src/utils"
	"github.com/jinzhu/gorm"
	"log"
	"time"
)

//a struct to rep Slot model
type Slot struct {
	gorm.Model
	SlotOne string `gorm:"default:'open'" json:"slot_one"`
	SlotTwo string `gorm:"default:'open'" json:"slot_two"`
	SlotThree string `gorm:"default:'open'" json:"slot_three"`
	SlotFour string `gorm:"default:'open'" json:"slot_four"`
	SlotFive string `gorm:"default:'open'" json:"slot_five"`
	Time string `json:"time"`
	ReservationTime time.Time `json:"reservation_time"`
}

/*
 This struct function validate the required parameters sent through the http request body
returns message and true if the requirement is met
*/
func (slot *Slot) Validate() (map[string] interface{}, bool) {

	if slot.SlotOne == "" {
		log.Println(u.Message(false, "Slot One should be on the payload"))
		return u.Message(false, "Slot One should be on the payload"), false
	}

	if slot.SlotTwo == "" {
		log.Println(u.Message(false, "Slot Two should be on the payload"))
		return u.Message(false, "Slot One should be on the payload"), false
	}

	if slot.SlotThree == "" {
		log.Println(u.Message(false, "Slot Three should be on the payload"))
		return u.Message(false, "Slot Three should be on the payload"), false
	}

	if slot.SlotFour == "" {
		log.Println(u.Message(false, "Slot Four should be on the payload"))
		return u.Message(false, "Slot Four should be on the payload"), false
	}

	if slot.SlotFive == "" {
		log.Println(u.Message(false, "Slot Five should be on the payload"))
		return u.Message(false, "Slot One Five be on the payload"), false
	}

	//All the required parameters are present
	log.Println(u.Message(true, "success"))
	return u.Message(true, "success"), true
}


// create slots
func (slot *Slot) Create() (map[string] interface{}) {

	if resp, ok := slot.Validate(); !ok {
		return resp
	}

	GetDB().Create(slot)

	resp := u.Message(true, "success")
	resp["slot"] = slot
	log.Println(resp)
	return resp
}

// Retrieve Slots
func GetSlots() ([]*Slot) {

	slots := make([]*Slot, 0)
	err := GetDB().Table("slots").Find(&slots).Error
	log.Println(err)
	if err != nil {
		log.Println(err)
		return nil
	}

	return slots
}


// upadate Slots Table

func (slot *Slot) Update() (map[string] interface{}) {


	day := time.Now().AddDate(0,0,1)

	db.Model(&slot).UpdateColumns(Slot{SlotOne: "open", SlotTwo: "open", SlotThree: "open", SlotFour: "open", SlotFive: "open", ReservationTime: day})

	resp := u.Message(true, "success")
	resp["slot"] = slot
	log.Println(resp)
	return resp
}




