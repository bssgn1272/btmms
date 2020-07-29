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

// ArCreateSlotController Function for Creating Slots
var ArCreateSlotController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	slot := &models.EdArSlot{}

	err := json.NewDecoder(r.Body).Decode(slot)
	if err != nil {
		u.Respond(w, u.Message(false, err.Error() /*"Error while decoding request body"*/))
		return
	}

	resp := slot.ArCreate()
	u.Respond(w, resp)
})

// ArGetSlotsController Function for retrieving Slots
var ArGetSlotsController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	data := models.ArGetSlots()
	resp := u.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	u.Respond(w, resp)
})

// ArGetSlotsByDateController correct method
var ArGetSlotsByDateController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	params := mux.Vars(r)
	date, _ := params["date"]
	data := models.ArGetSlotsByDate(date)
	resp := u.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	u.Respond(w, resp)
})

// ArInitMidNight Function for openning slots at midnight
func ArInitMidNight() {
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

		slot := &models.EdArSlot{}

		reset := slot.Update()

		log.Println(reset)
	}
}

// ArUpdateSlotController Function for Creating Slots
var ArUpdateSlotController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	params := mux.Vars(r)
	id, _ := strconv.Atoi(params["id"])

	slot := &models.EdArSlot{}

	err := json.NewDecoder(r.Body).Decode(slot)
	if err != nil {
		u.Respond(w, u.Message(false, "Error while decoding request body"))
		return
	}

	resp := slot.UpdateSlotTInterval(uint(id))
	u.Respond(w, resp)
})
