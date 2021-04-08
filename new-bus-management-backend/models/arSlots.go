package models

import (
	"log"
	"net/url"
	"new-bus-management-backend/utils"
	"regexp"
	"time"
	"github.com/jinzhu/gorm"
)

//EdArSlot a struct to rep Slot model
type EdArSlot struct {
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
	arregexpSlotOne   = regexp.MustCompile("^[^0-9]+$")
	arregexpSlotTwo   = regexp.MustCompile("^[^0-9]+$")
	arregexpSlotThree = regexp.MustCompile("^[^0-9]+$")
	arregexpSlotFour  = regexp.MustCompile("^[^0-9]+$")
	arregexpSlotFive  = regexp.MustCompile("^[^0-9]+$")
	arregexpSlotSix   = regexp.MustCompile("^[^0-9]+$")
	arregexpSlotSeven = regexp.MustCompile("^[^0-9]+$")
	arregexpSlotEight = regexp.MustCompile("^[^0-9]+$")
	arregexpSlotNine  = regexp.MustCompile("^[^0-9]+$")
)

// ArValidate validation of requests
func (slot *EdArSlot) ArValidate() url.Values {

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

	if !arregexpSlotOne.Match([]byte(slot.SlotOne)) {
		errs.Add("slot_one", "The slot one field should be valid!")
	}
	if !arregexpSlotTwo.Match([]byte(slot.SlotTwo)) {
		errs.Add("slot_two", "The slot two field should be valid!")
	}
	if !arregexpSlotThree.Match([]byte(slot.SlotThree)) {
		errs.Add("slot_three", "The slot three field should be valid!")
	}
	if !arregexpSlotFour.Match([]byte(slot.SlotFour)) {
		errs.Add("slot_four", "The slot four field should be valid!")
	}

	if !arregexpSlotFive.Match([]byte(slot.SlotFive)) {
		errs.Add("slot_five", "The slot five field should be valid!")
	}

	if !arregexpSlotSix.Match([]byte(slot.SlotSix)) {
		errs.Add("slot_six", "The slot six field should be valid!")
	}

	if !arregexpSlotSeven.Match([]byte(slot.SlotSeven)) {
		errs.Add("slot_seven", "The slot seven field should be valid!")
	}

	if !arregexpSlotEight.Match([]byte(slot.SlotEight)) {
		errs.Add("slot_eight", "The slot eight field should be valid!")
	}

	if !arregexpSlotNine.Match([]byte(slot.SlotNine)) {
		errs.Add("slot_nine", "The slot nine field should be valid!")
	}

	log.Println(errs)

	return errs
}

// ArCreate slots
func (slot *EdArSlot) ArCreate() map[string]interface{} {

	if validErrs := slot.ArValidate(); len(validErrs) > 0 {
		err := map[string]interface{}{"validationError": validErrs}
		return err
	}

	GetDB().Create(slot)

	resp := utils.Message(true, "success")
	resp["slot"] = slot
	log.Println(resp)
	return resp
}

// ArGetSlots Retrieve Slots
func ArGetSlots() []*EdArSlot {

	slots := make([]*EdArSlot, 0)
	err := GetDB().Table("ed_ar_slots").Order("time asc").Find(&slots).Error
	log.Println(err)
	if err != nil {
		log.Println(err)
		return nil
	}

	return slots
}

// ArGetSlotsByDate Retrieve Slots
func ArGetSlotsByDate(date string) []*EdArSlot {

	slots := make([]*EdArSlot, 0)
	err := GetDB().Raw("CALL ed_ar_slots_schedule_by_day(?)", date).Scan(&slots).Error
	log.Println(err)
	if err != nil {
		log.Println(err)
		return nil
	}

	return slots
}

// Update Slots Table
func (slot *EdArSlot) Update() map[string]interface{} {

	day := time.Now().AddDate(0, 0, 1)

	db.Model(&slot).UpdateColumns(EdArSlot{SlotOne: "open", SlotTwo: "open", SlotThree: "open", SlotFour: "open", SlotFive: "open", SlotSix: "open", SlotSeven: "open", SlotEight: "open", SlotNine: "open", ReservationTime: day})

	resp := utils.Message(true, "success")
	resp["slot"] = slot
	log.Println(resp)
	return resp
}

// UpdateSlotTInterval Slots Table
func (slot *EdArSlot) UpdateSlotTInterval(id uint) map[string]interface{} {

	db.Model(&slot).Where("id = ?", slot.ID).Updates(EdArSlot{Time: slot.Time})

	resp := utils.Message(true, "success")
	resp["slot"] = slot
	log.Println(resp)
	return resp
}

// Close Slots Table
func (slot *EdArSlot) Close() map[string]interface{} {

	db.Model(&slot).Where("time = ?", slot.Time).Updates(EdArSlot{SlotOne: slot.SlotOne, SlotTwo: slot.SlotTwo, SlotThree: slot.SlotThree, SlotFour: slot.SlotFour, SlotFive: slot.SlotFive, SlotSix: slot.SlotSix, SlotSeven: slot.SlotSeven, SlotEight: slot.SlotEight, SlotNine: slot.SlotNine})

	log.Println(slot.Time)
	resp := utils.Message(true, "success")
	resp["slot"] = slot
	log.Println(resp)
	return resp
}
