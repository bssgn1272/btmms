package models

import (
	u "../../src/utils"
	"github.com/jinzhu/gorm"
	"log"
	"net/url"
	"regexp"
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


// Variables for regular expressions
var(
	regexpSlotOne = regexp.MustCompile("^[^0-9]+$")
	regexpSlotTwo = regexp.MustCompile("^[^0-9]+$")
	regexpSlotThree = regexp.MustCompile("^[^0-9]+$")
	regexpSlotFour = regexp.MustCompile("^[^0-9]+$")
	regexpSlotFive = regexp.MustCompile("^[^0-9]+$")
)

/*
 This struct function validate the required parameters sent through the http request body
returns message and true if the requirement is met
*/
func (slot *Slot) Validate()  url.Values {


	errs := url.Values{}


	if slot.SlotOne == "" {
		errs.Add("slot_one", "Slot One should be on the payload!")
	}

	if slot.SlotTwo == "" {
		errs.Add("slot_two", "Slot Two should be on the payload!")
	}

	if slot.SlotThree == "" {
		errs.Add("slot_three", "Slot Three should be on the payload!")
	}

	if slot.SlotFour == "" {
		errs.Add("slot_four", "Slot Four should be on the payload!")
	}

	if slot.SlotFive == "" {
		errs.Add("slot_five", "Slot Five should be on the payload!")
	}

	if slot.Time == "" {
		errs.Add("slot_one", "Time should be on the payload!")
	}



	if !regexpSlotOne.Match([]byte(slot.SlotOne)) {
		errs.Add("slot_one", "The slot one field should be valid!")
	}
	if !regexpSlotTwo.Match([]byte(slot.SlotTwo)) {
		errs.Add("slot_two", "The slot two field should be valid!")
	}
	if !regexpSlotThree.Match([]byte(slot.SlotThree)) {
		errs.Add("slot_three", "The slot three field should be valid!")
	}
	if !regexpSlotFour.Match([]byte(slot.SlotFour)) {
		errs.Add("slot_four", "The slot four field should be valid!")
	}

	if !regexpSlotFive.Match([]byte(slot.SlotFive)) {
		errs.Add("slot_five", "The slot five field should be valid!")
	}

	log.Println(errs)

	return errs
}


// create slots
func (slot *Slot) Create() (map[string] interface{}) {

	if validErrs := slot.Validate(); len(validErrs) > 0 {
		err := map[string]interface{}{"validationError": validErrs}
		return err
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
	err := GetDB().Table("slots").Order("time asc").Find(&slots).Error
	log.Println(err)
	if err != nil {
		log.Println(err)
		return nil
	}

	return slots
}


// upadate Slots Table

func (slot *Slot) Update() (map[string] interface{}) {


	day := time.Now().AddDate(0,0,1,)

	db.Model(&slot).UpdateColumns(Slot{SlotOne: "open", SlotTwo: "open", SlotThree: "open", SlotFour: "open", SlotFive: "open", ReservationTime: day})

	resp := u.Message(true, "success")
	resp["slot"] = slot
	log.Println(resp)
	return resp
}


// upadate Slots Table

func (slot *Slot) UpdateSlotTInterval(id uint) (map[string] interface{}) {

	db.Model(&slot).Where("id = ?", slot.ID).Updates(Slot{Time:slot.Time})

	resp := u.Message(true, "success")
	resp["slot"] = slot
	log.Println(resp)
	return resp
}


// update Slots Table

func (slot *Slot) Close() (map[string] interface{}) {


	db.Model(&slot).Where("time = ?", slot.Time).Updates(Slot{SlotOne:slot.SlotOne, SlotTwo:slot.SlotTwo,SlotThree:slot.SlotThree,SlotFour:slot.SlotFour, SlotFive:slot.SlotFive})

	resp := u.Message(true, "success")
	resp["slot"] = slot
	log.Println(resp)
	return resp
}




