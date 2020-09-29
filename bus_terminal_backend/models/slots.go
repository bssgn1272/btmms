package models

import (
	"log"
	"net/url"
	"new-bus-management-backend/utils"
	"regexp"
	"time"


	"github.com/jinzhu/gorm"
)

//a struct to rep Slot model
type EdSlot struct {
	gorm.Model
	SlotOne         string    `gorm:"default:'open'" json:"slot_one"`
	SlotTwo         string    `gorm:"default:'open'" json:"slot_two"`
	SlotThree       string    `gorm:"default:'open'" json:"slot_three"`
	SlotFour        string    `gorm:"default:'open'" json:"slot_four"`
	SlotFive        string    `gorm:"default:'open'" json:"slot_five"`
	SlotSix         string    `gorm:"default:'open'" json:"slot_six"`
	SlotSeven       string    `gorm:"default:'open'" json:"slot_seven"`
	SlotEight       string    `gorm:"default:'open'" json:"slot_eight"`
	SlotNine        string    `gorm:"default:'open'" json:"slot_nine"`
	Time            string    `json:"time"`
	ReservationTime time.Time `json:"reservation_time"`
}

// Variables for regular expressions
var (
	regexpSlotOne   = regexp.MustCompile("^[^0-9]+$")
	regexpSlotTwo   = regexp.MustCompile("^[^0-9]+$")
	regexpSlotThree = regexp.MustCompile("^[^0-9]+$")
	regexpSlotFour  = regexp.MustCompile("^[^0-9]+$")
	regexpSlotFive  = regexp.MustCompile("^[^0-9]+$")
	regexpSlotSix   = regexp.MustCompile("^[^0-9]+$")
	regexpSlotSeven = regexp.MustCompile("^[^0-9]+$")
	regexpSlotEight = regexp.MustCompile("^[^0-9]+$")
	regexpSlotNine  = regexp.MustCompile("^[^0-9]+$")
)

/*
 This struct function validate the required parameters sent through the http request body
returns message and true if the requirement is met
*/
func (slot *EdSlot) Validate() url.Values {

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

	if slot.SlotSix == "" {
		errs.Add("slot_six", "Slot Six should be on the payload!")
	}

	if slot.SlotSeven == "" {
		errs.Add("slot_seven", "Slot Seven should be on the payload!")
	}

	if slot.SlotEight == "" {
		errs.Add("slot_eight", "Slot Eight should be on the payload!")
	}

	if slot.SlotNine == "" {
		errs.Add("slot_nine", "Slot Nine should be on the payload!")
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

	if !regexpSlotSix.Match([]byte(slot.SlotSix)) {
		errs.Add("slot_six", "The slot six field should be valid!")
	}

	if !regexpSlotSeven.Match([]byte(slot.SlotSeven)) {
		errs.Add("slot_seven", "The slot seven field should be valid!")
	}

	if !regexpSlotEight.Match([]byte(slot.SlotEight)) {
		errs.Add("slot_eight", "The slot eight field should be valid!")
	}

	if !regexpSlotNine.Match([]byte(slot.SlotNine)) {
		errs.Add("slot_nine", "The slot nine field should be valid!")
	}

	log.Println(errs)

	return errs
}

// create slots
func (slot *EdSlot) Create() map[string]interface{} {

	if validErrs := slot.Validate(); len(validErrs) > 0 {
		err := map[string]interface{}{"validationError": validErrs}
		return err
	}

	GetDB().Create(slot)

	resp := utils.Message(true, "success")
	resp["slot"] = slot
	log.Println(resp)
	return resp
}

// Retrieve Slots
func GetSlots() []*EdSlot {

	slots := make([]*EdSlot, 0)
	err := GetDB().Table("ed_slots").Order("time asc").Find(&slots).Error
	log.Println(err)
	if err != nil {
		log.Println(err)
		return nil
	}

	return slots
}

func GetSlotsByDate(date string) []*EdSlot {

	slots := make([]*EdSlot, 0)
	err := GetDB().Raw("CALL ed_slots_schedule_by_day(?)", date).Scan(&slots).Error
	log.Println(err)
	if err != nil {
		log.Println(err)
		return nil
	}

	return slots
}

// upadate Slots Table

func (slot *EdSlot) Update() map[string]interface{} {

	day := time.Now().AddDate(0, 0, 1)

	db.Model(&slot).UpdateColumns(EdSlot{SlotOne: "open", SlotTwo: "open", SlotThree: "open", SlotFour: "open", SlotFive: "open", SlotSix: "open", SlotSeven: "open", SlotEight: "open", SlotNine: "open", ReservationTime: day})

	resp := utils.Message(true, "success")
	resp["slot"] = slot
	log.Println(resp)
	return resp
}

// upadate Slots Table

func (slot *EdSlot) UpdateSlotTInterval(id uint) map[string]interface{} {

	db.Model(&slot).Where("id = ?", slot.ID).Updates(EdSlot{Time: slot.Time})

	resp := utils.Message(true, "success")
	resp["slot"] = slot
	log.Println(resp)
	return resp
}

// update Slots Table

func (slot *EdSlot) Close() map[string]interface{} {

	db.Model(&slot).Where("time = ?", slot.Time).Updates(EdSlot{SlotOne: slot.SlotOne, SlotTwo: slot.SlotTwo, SlotThree: slot.SlotThree, SlotFour: slot.SlotFour, SlotFive: slot.SlotFive, SlotSix: slot.SlotSix, SlotSeven: slot.SlotSeven, SlotEight: slot.SlotEight, SlotNine: slot.SlotNine})

	log.Println(slot.Time)
	resp := utils.Message(true, "success")
	resp["slot"] = slot
	log.Println(resp)
	return resp
}
