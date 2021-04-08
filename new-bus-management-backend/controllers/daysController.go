package controllers

import (
	"encoding/json"
	"log"
	"net/http"
	"new-bus-management-backend/models"
	"new-bus-management-backend/utils"
)

// CreateDayController Function for days request function
var CreateDayController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	day := &models.EdDay{}

	err := json.NewDecoder(r.Body).Decode(day)
	if err != nil {
		utils.Respond(w, utils.Message(false, "Error while decoding request body"))
		return
	}

	resp := day.Create()
	utils.Respond(w, resp)
})

// GetDaysController Function for retrieving days requests for the day
var GetDaysController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	data := models.GetDays()
	resp := utils.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	utils.Respond(w, resp)
})
