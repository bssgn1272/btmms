package controllers

import (
	"encoding/json"
	"log"
	"net/http"
	"new-bus-management-backend/models"
	"new-bus-management-backend/utils"
)

// CreateTownController Function for town request function
var CreateTownController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	town := &models.EdTown{}

	err := json.NewDecoder(r.Body).Decode(town)
	if err != nil {
		utils.Respond(w, utils.Message(false, "Error while decoding request body"))
		return
	}

	resp := town.Create()
	utils.Respond(w, resp)
})

// GetTownsController Function for retrieving town requests for the day
var GetTownsController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	data := models.GetTowns()
	resp := utils.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	utils.Respond(w, resp)
})

// Function for Destination and time request function
//var CreateDestinationDayTimesController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {
//
//
//	destinationDayTime := &models.EdDestinationDayTime{}
//
//	err := json.NewDecoder(r.Body).Decode(destinationDayTime)
//	if err != nil {
//		utils.Respond(w, utils.Message(false, "Error while decoding request body"))
//		return
//	}
//
//	resp := destinationDayTime.Create()
//	utils.Respond(w, resp)
//})

// GetDestinationDayTimesController Function for retrieving Destination and time requests for the day
var GetDestinationDayTimesController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	data := models.GetDestinationDayTimes()
	resp := utils.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	utils.Respond(w, resp)
})
