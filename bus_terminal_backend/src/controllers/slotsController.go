package controllers

import (
	"../models"
	u "../utils"
	"encoding/json"
	"fmt"
	"log"
	"net/http"
	"time"
)

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

var GetSlotsController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	data := models.GetSlots()
	resp := u.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	u.Respond(w, resp)
})


func InitMidNight() {
	t := time.Now()
	n := time.Date(t.Year(), t.Month(), t.Day(), 24, 0, 0, 0, t.Location())
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
		fmt.Print("now")
	}
}

