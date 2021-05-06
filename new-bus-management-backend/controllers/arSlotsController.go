package controllers

import (
	"encoding/json"
	"log"
	"net/http"
	"new-bus-management-backend/models"
	"new-bus-management-backend/utils"
	"strconv"
	"time"

	"github.com/gorilla/mux"
)

// ArCreateSlotController Function for Creating Slots
var ArCreateSlotController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	slot := &models.EdArSlot{}

	err := json.NewDecoder(r.Body).Decode(slot)
	if err != nil {
		utils.Respond(w, utils.Message(false, err.Error() /*"Error while decoding request body"*/))
		return
	}

	resp := slot.ArCreate()
	utils.Respond(w, resp)
})

// ArGetSlotsController Function for retrieving Slots
var ArGetSlotsController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	data := models.ArGetSlots()
	resp := utils.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	utils.Respond(w, resp)
})

// ArGetSlotsByDateController correct method
var ArGetSlotsByDateController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	params := mux.Vars(r)
	date := params["date"]
	data := models.ArGetSlotsByDate(date)
	resp := utils.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	utils.Respond(w, resp)
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
		utils.Respond(w, utils.Message(false, "Error while decoding request body"))
		return
	}

	resp := slot.UpdateSlotTInterval(uint(id))
	utils.Respond(w, resp)
})
