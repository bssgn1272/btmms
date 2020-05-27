package controllers

import (
	"../models"
	u "../utils"
	"encoding/json"
	"log"
	"net/http"
)

// Function for time request function
var CreatePenaltyController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {


	penalty := &models.EdPenalty{}

	err := json.NewDecoder(r.Body).Decode(penalty)
	if err != nil {
		u.Respond(w, u.Message(false, "Error while decoding request body"))
		return
	}

	resp := penalty.CreatePenalty()
	u.Respond(w, resp)
})

// Function for retrieving penalty requests for the day
var GetPenaltiesController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	data := models.GetPenalties()
	resp := u.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	u.Respond(w, resp)
})

// Function for retrieving time requests for the day
var GetLatestPenaltyController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	data := models.GetLatestPenalty()
	resp := u.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	u.Respond(w, resp)
})