package controllers

import (
	"../models"
	u "../utils"
	"encoding/json"
	"log"
	"net/http"
	"time"
)

// Function for Creating Slots
var CreateSlotController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	slot := &models.Slot{}

	err := json.NewDecoder(r.Body).Decode(slot)
	if err != nil {
		u.Respond(w, u.Message(false, "Error while decoding request body"))
		return
	}

	resp := slot.Create()
	u.Respond(w, resp)
})


// Function for retrieving Slots
var GetSlotsController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	data := models.GetSlots()
	resp := u.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	u.Respond(w, resp)
})


// Function for openning slots at midnight
func InitMidNight() {
	t := time.Now()
	n := time.Date(t.Year(), t.Month(), t.Day(), 25, 0, 0, 0, t.Location())
	d := n.Sub(t)
	if d < 0 {
		n = n.Add(24 * time.Hour)
		d = n.Sub(t)
	}
	for {
		time.Sleep(d)
		d = 24 * time.Hour

		slot := &models.Slot{}

		reset := slot.Update()

		log.Println(reset)
	}
}

