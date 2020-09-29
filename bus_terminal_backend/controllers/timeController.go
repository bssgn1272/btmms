package controllers

import (
	"encoding/json"
	"log"
	"net/http"
	"new-bus-management-backend/models"
	"new-bus-management-backend/utils"
)

// CreateTimeController Function for time request function
var CreateTimeController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	time := &models.EdTime{}

	err := json.NewDecoder(r.Body).Decode(time)
	if err != nil {
		utils.Respond(w, utils.Message(false, "Error while decoding request body"))
		return
	}

	resp := time.Create()
	utils.Respond(w, resp)
})

// GetTimesController Function for retrieving time requests for the day
var GetTimesController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	data := models.GetTimes()
	resp := utils.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	utils.Respond(w, resp)
})
