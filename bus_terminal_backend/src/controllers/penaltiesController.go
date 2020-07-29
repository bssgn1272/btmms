package controllers

import (
	"encoding/json"
	"log"
	"net/http"

	"../models"
	u "../utils"
	"github.com/gorilla/mux"
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

// GetPenaltyChargeController get charge for bus' late cancellation
var GetPenaltyChargeController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {
	params := mux.Vars(r)
	id := params["id"]
	data := models.GetPenaltyCharge(id)
	resp := u.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	u.Respond(w, resp)
})

// GetLoadingFeeController get charge for bus' late cancellation
var GetLoadingFeeController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {
	params := mux.Vars(r)
	id := params["id"]
	data := models.GetLoadingFee(id)
	resp := u.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	u.Respond(w, resp)
})
