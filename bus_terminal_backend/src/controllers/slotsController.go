package controllers

import (
	"encoding/json"
	"log"
	"net/http"
	"strconv"
	"time"

	"../models"
	u "../utils"
	"github.com/gorilla/mux"
)

// Function for Creating Slots
var CreateSlotController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	slot := &models.EdSlot{}

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

var GetSlotsByDateController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	params := mux.Vars(r)
	date, _ := params["date"]
	data := models.GetSlotsByDate(date)
	resp := u.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	u.Respond(w, resp)
})

// Function for openning slots at midnight
func InitMidNight() {
	t := time.Now()
	n := time.Date(t.Year(), t.Month(), t.Day(), 24, 00, 0, 0, t.Location())
	d := n.Sub(t)
	if d < 0 {
		n = n.Add(24 * time.Hour)
		d = n.Sub(t)
	}
	for {
		time.Sleep(d)
		d = 24 * time.Hour

		slot := &models.EdSlot{}

		reset := slot.Update()

		log.Println(reset)
	}
}

// Function for Creating Slots
var UpdateSlotController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	params := mux.Vars(r)
	id, _ := strconv.Atoi(params["id"])

	slot := &models.EdSlot{}

	err := json.NewDecoder(r.Body).Decode(slot)
	if err != nil {
		u.Respond(w, u.Message(false, "Error while decoding request body"))
		return
	}

	resp := slot.UpdateSlotTInterval(uint(id))
	u.Respond(w, resp)
})
